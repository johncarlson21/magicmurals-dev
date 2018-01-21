<?php
namespace Surething\Testimonials\Controller\Adminhtml\TestimonialsImport;

class Edit extends \Magento\Backend\App\Action {

	public function execute() {
		$model = $this->_objectManager->create('Surething\Testimonials\Model\Testimonials');
		
		$registryObject = $this->_objectManager->get('Magento\Framework\Registry');
		$registryObject->register('testimonials_testimonials', $model);
		
		$this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
	}
}