<?php
namespace Surething\Testimonials\Block\Adminhtml\Import;

/**
 * CMS block edit form container
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected function _construct()
    {
		$this->_objectId = 'id';
        $this->_blockGroup = 'Surething_Testimonials';
        $this->_controller = 'adminhtml_testimonials';

        parent::_construct();
		
		$this->_removeButton('delete');
		//$this->_removeButton('save');
		$this->_removeButton('reset');


        $this->buttonList->update('save', 'label', __('Import'));
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        return __('Import Testimonials');
    }
}
