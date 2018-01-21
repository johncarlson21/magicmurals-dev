<?php
/**
 * Copyright © 2015 Surething. All rights reserved.
 */
namespace Surething\Testimonials\Model\ResourceModel;

/**
 * Testimonials resource
 */
class Testimonials extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('testimonials_testimonials', 'id');
    }

  
}
