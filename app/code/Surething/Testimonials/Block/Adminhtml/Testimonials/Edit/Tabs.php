<?php
namespace Surething\Testimonials\Block\Adminhtml\Testimonials\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_testimonials_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Testimonials Information'));
    }
}