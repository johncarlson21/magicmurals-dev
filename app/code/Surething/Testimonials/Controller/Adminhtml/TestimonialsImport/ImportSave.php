<?php
namespace Surething\Testimonials\Controller\Adminhtml\TestimonialsImport;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use ZipArchive;

class ImportSave extends \Magento\Backend\App\Action {
	
	protected $_moduleFactory;
	
	public function __construct(Context $context,
			\Surething\Testimonials\Model\TestimonialsFactory $moduleFactory) {
		parent::__construct($context);
		$this->_moduleFactory = $moduleFactory;
	}
	
	public function execute() {
		
		if (isset($_FILES)){
			if ($_FILES['import_file']['name']) {
				
				$uploader = $this->_objectManager->create(
					'Magento\MediaStorage\Model\File\Uploader',
					['fileId' => 'import_file']
				);
				$uploader->setAllowedExtensions(array('zip'));
				$uploader->setAllowRenameFiles(false);
				$uploader->setFilesDispersion(false);
				$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
					->getDirectoryRead(DirectoryList::MEDIA);
				$result = $uploader->save($mediaDirectory->getAbsolutePath('testimonials/testimony/tmp'));
				
				$file = $result['file'];
				$fileDir = $mediaDirectory->getAbsolutePath('testimonials/testimony/tmp/');
				$filePath = $fileDir . $file;
				
				$this->messageManager->addSuccess(__('File Uploaded!' . $filePath));
				
				// unzip file
				$zip = new ZipArchive;
				$res = $zip->open($filePath);
				if ($res === TRUE) {
					$zip->extractTo($fileDir);
					$zip->close();
					$this->messageManager->addSuccess(__('File Extracted!'));
				} else {
					$this->messageManager->addError('Error opening zip file!');
				}

				$importFile = $fileDir . "import.csv";
				if (($handle = fopen($importFile, "r")) !== FALSE) {
					while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
						$tdata = array(
							'testimony_name' => isset($data[1]) && !empty($data[1]) ? $data[1] : '',
							'testimony_company' => isset($data[2]) && !empty($data[2]) ? $data[2] : '',
							'testimony_install' => isset($data[3]) && !empty($data[3]) ? $data[3] : '',
							'testimony_filename' => isset($data[0]) && !empty($data[0]) ? 'testimonials/testimony/'.$data[0] : '',
							//'testimony_thumb' => isset($data[1]) && !empty($data[1]) ? 'testimonials/testimony/'.$data[1] : '',
							'testimony_headline' => isset($data[4]) && !empty($data[4]) ? $data[4] : '',
							'testimony_description' => isset($data[5]) && !empty($data[5]) ? $data[5] : '',
							'testimony_type' => isset($data[6]) && !empty($data[6]) ? $data[6] : '',
							'testimony_mural' => isset($data[7]) && !empty($data[7]) ? $data[7] : '',
							'testimony_url' => isset($data[8]) && !empty($data[8]) ? $data[8] : '',
							'testimony_order' => 0
						);
						if (file_exists($fileDir.$data[0])) {
							if (file_exists(str_replace('tmp/', '', $fileDir).$data[0])) {
								// need to rename the file so use a timestamp
								$fileTmp = str_replace(".jpg", "_".time().".jpg", $data[0]);
								$nfile = str_replace('tmp/', '', $fileDir).$fileTmp;
								@rename($fileDir.$data[0], $nfile);
								$tdata['testimony_filename'] = 'testimonials/testimony/'.$fileTmp;
							} else {
								$nfile = str_replace('tmp/', '', $fileDir).$data[0];
								@rename($fileDir.$data[0], $nfile);
							}
							$model = $this->_objectManager->create('Surething\Testimonials\Model\Testimonials');
							$model->setData($tdata)
								->save();
						} else {
							$this->messageManager->addError(__("Testimony File: ".$data[0].", or the thumb file was not found!"));
							@unlink($fileDir.$data[0]);
							//@unlink($fileDir.$data[1]);
						}
					}
					fclose($handle);
				}

				@unlink($filePath);
				@unlink($fileDir . "import.csv");

				$this->messageManager->addSuccess(__("Testimonies Imported"));
			} else {
				$this->messageManager->addError(__("That is not a valid file"));
			}
		}
		
		$this->fixorderAction();
		
		$this->_redirect('*/*/index');
		return;
		
	}
	
	public function fixorderAction() {
		// $testimonials = $this->testimonialsTestimonyFactory->create()->getTestimonials();
		$testModel = $this->_moduleFactory->create();
		$testimonials = $testModel->getCollection();
		$z = 10;
		foreach($testimonials as $t) {
			$t->setTestimonyOrder($z);
			$t->save();
			error_log('test name: ' . $t->getTestimonyName());
			$z = $z+10;
		}
	}
	
}