<?php

namespace Surething\Tooltip\Observer\Product;

class AttributeFormBuildMainTab implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        // Add an extra field to the base fieldset:
        $fieldset = $observer->getForm()->getElement('base_fieldset');
        $fieldset->addField('tooltip', 'text', array(
            'name' => 'tooltip',
            'label' => __('Tooltip'),
            'title' => __('Tooltip')
        ));
    }
}
