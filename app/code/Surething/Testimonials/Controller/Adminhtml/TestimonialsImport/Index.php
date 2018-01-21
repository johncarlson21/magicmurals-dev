<?php
namespace Surething\Testimonials\Controller\Adminhtml\TestimonialsImport;

use Magento\Backend\App\Action;

class Index extends \Magento\Backend\App\Action {
	
	/*public function execute() {
		$this->_forward('edit');
	}*/
	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	//protected $resultPageFactory;

	/**
	 * Constructor
	 *
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 */
	public function __construct( \Magento\Backend\App\Action\Context $context, 
			\Magento\Framework\View\Result\PageFactory $resultPageFactory ) {
		
		parent::__construct( $context );
		$this->resultPageFactory = $resultPageFactory;
		
	}

	/**
	 * Load the page defined in view/adminhtml/layout/exampleadminnewpage_helloworld_index.xml
	 *
	 * @return \Magento\Framework\View\Result\Page
	 */
	public function execute() {
		//return $resultPage = $this->resultPageFactory->create();
		$this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
	}
}