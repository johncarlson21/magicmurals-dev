<?php
namespace Surething\Muralprod\Model\Product\Type;
 
class Muralprod extends \Magento\Catalog\Model\Product\Type\AbstractType {

	const TYPE_ID = 'muralprod';
	
	/*public function save($product)
    {
        parent::save($product);
        //  your additional saving logic
        return $this;
    }*/
 
    /**
     * {@inheritdoc}
     */
	public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
        // method intentionally empty
    }
 
 
}