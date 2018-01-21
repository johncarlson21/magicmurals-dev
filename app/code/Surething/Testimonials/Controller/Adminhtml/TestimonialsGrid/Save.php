<?php
namespace Surething\Testimonials\Controller\Adminhtml\TestimonialsGrid;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
	
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public function execute()
    {
		
        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = $this->_objectManager->create('Surething\Testimonials\Model\Testimonials');
		
            if(isset($_FILES['testimony_filename']['name']) && $_FILES['testimony_filename']['name'] != '') {
				try {
						$uploader = $this->_objectManager->create(
							'Magento\MediaStorage\Model\File\Uploader',
							['fileId' => 'testimony_filename']
						);
						$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(true);
						$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
							->getDirectoryRead(DirectoryList::MEDIA);
						$result = $uploader->save($mediaDirectory->getAbsolutePath('testimonials/testimony'));
						unset($result['tmp_name']);
						unset($result['path']);
						$data['testimony_filename'] = "testimonials/testimony" . $result['file'];
				} catch (Exception $e) {
					$data['testimony_filename'] = "testimonials/testimony" . $_FILES['testimony_filename']['name'];
				}
			}
			else{
				$data['testimony_filename'] = $data['testimony_filename']['value'];
			} 
			
			$id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }
			
			$data['testimony_type'] = implode(",", $data['testimony_type']);
			
            $model->setData($data);
			
            try {
                $model->save();
                $this->messageManager->addSuccess(__('Testimonial Saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the testimonial.') . $e->getMessage());
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}
