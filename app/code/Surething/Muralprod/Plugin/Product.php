<?php
 
namespace Surething\Muralprod\Plugin;
 
class Product
{
    public function afterGetPrice($product, $result)
    {
        //return $result + 100;
		return 111.45;
    }
}