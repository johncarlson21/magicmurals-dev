<?php
namespace Surething\Testimonials\Block\Adminhtml;
class Testimonials extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_testimonials';/*block grid.php directory*/
        $this->_blockGroup = 'Surething_Testimonials';
        $this->_headerText = __('Testimonials');
        $this->_addButtonLabel = __('Add New Testimony'); 
		
		$this->buttonList->add(
            'import',
            array(
                'label' => __('Import Testimonials'),
                'onclick' => 'setLocation("' . $this->getUrl('testimonials/testimonialsimport/index') . '")',
            ),
            -100
        );
		
        parent::_construct();
		
    }
}
