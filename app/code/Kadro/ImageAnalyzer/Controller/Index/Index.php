<?php


namespace Kadro\ImageAnalyzer\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $jsonHelper;
	protected $imageAnalyzerHelper;
	protected $directory_list;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
		\Kadro\ImageAnalyzer\Helper\Data $imageAnalyzerHelper,
		\Magento\Framework\App\Filesystem\DirectoryList $directory_list
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
		$this->imageAnalyzerHelper = $imageAnalyzerHelper;
		$this->directory_list = $directory_list;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
		
		$response = $this->anlyzeImage();
		
        try {
            return $this->jsonResponse($response);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
	
	public function anlyzeImage() {
		$request = $this->getRequest();
		$analyzerHelper = $this->imageAnalyzerHelper;
		$dir = $this->directory_list->getPath('media') . "/analyze/";

		$image               = $request->getParam('image', FALSE);
		$requestHeightFeet   = floatval($request->getParam('hFt', 0));
		$requestHeightInches = floatval($request->getParam('hIn', 0));
		$requestWidthFeet    = floatval($request->getParam('wFt', 0));
		$requestWidthInches  = floatval($request->getParam('wIn', 0));
		//$image = str_replace(Mage::getBaseUrl(), Mage::getBaseDir().'/', $image);
		$image = $dir . $image;
		$response = array();

		if (!file_exists($image)) {
			// Image Doesn't Exist, Return Error
			$response['error'] = 'Image File Missing: ' . $image;
			$response['text']  = 'error';
		} else if (
			$requestHeightFeet < 0 ||
			$requestHeightInches < 0 ||
			($requestHeightFeet == 0 && $requestHeightInches == 0) ||
			$requestWidthFeet < 0 ||
			$requestWidthInches < 0 ||
			($requestWidthFeet == 0 && $requestWidthInches == 0)
		) {
			// Dimensions are invalid, Return Error
			$response['error'] = 'Invalid Dimensions';
			$response['results']['text']  = 'error';
		} else {
			// Run Quality Analyzer, Return Results
			/** @var \Kadro\ImageAnalyzer\Helper\Data $analyzerHelper */
			
            $analyzerHelper->setImage($image)
                ->setDims($requestWidthFeet, $requestWidthInches, $requestHeightFeet, $requestHeightInches);

			$response['results'] = $analyzerHelper->getQuality();
		}

		if (isset($response['error']) && $response['error'] != '') {
			$response['template'] = $analyzerHelper->getBlock('error.phtml');
		} else {
			switch ($response['results']['quality']) {
				case 1:
					$response['template'] = $analyzerHelper->getBlock('fair.phtml');
					break;
				case 2:
					$response['template'] = $analyzerHelper->getBlock('good.phtml');
					break;
				case 0:
				default:
					$response['template'] = $analyzerHelper->getBlock('poor.phtml');
					break;
			}
		}

		//$block->setAnalyzerResponse($response);
		
		return $response;
	}
}
