<?php
/**
 * Mural Product
 * Copyright (C) 2017 Sure Thing Web 2017
 * 
 * This file is part of Surething/Muralprod.
 * 
 * Surething/Muralprod is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Surething\Muralprod\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class CustomPrice implements ObserverInterface {
	
	protected $_logger;
	protected $messageManager;
	protected $dataObjectConverter;
	
	public function __construct(\Psr\Log\LoggerInterface $logger,
			\Magento\Framework\Message\ManagerInterface $messageManager,
			\Magento\Framework\Api\ExtensibleDataObjectConverter $dataObjectConverter
		) {
		$this->_logger = $logger;
		$this->messageManager = $messageManager;
		$this->dataObjectConverter = $dataObjectConverter;
	}
	
	public function checkOptions($item) {
	
	}
	
	
	public function execute(\Magento\Framework\Event\Observer $observer ) {
		$this->_logger->info("Starting Custom Price execute() -----------------------");
		//$item = $observer->getEvent()->getData( 'quote_item' );
		$item = $observer->getQuoteItem();
		
		$product = $item->getProduct();
		
		$productType = $product->getTypeID();
		
		// only run if mural product or pattern product
		if($productType == 'muralprod' || $productType == "patternprod") {
			
			$_customOptions = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
			
			$customSize = false;
			$hf = 0;
			$hi = 0;
			$wf = 0;
			$wi = 0;
			$searchedOption = null;
			
			foreach($_customOptions['options'] as $o) {
				if($o['label'] == "Square Ft") {
					$searchedOption = $o['value'];
				}
			}
			
			//error_log(print_r($searchedOption,true));
			$this->_logger->info(print_r($searchedOption,true));
			
			$sqft = $searchedOption;
			$sqft = !empty($sqft) ? $sqft : 12;
			
			//error_log('sqft: '.$sqft);
			$this->_logger->info("sqft: ".$sqft);
			
			$finalprice = round($product->getFinalPrice(),2);
			$specialPrice = $finalprice * $sqft;
			
			if ($specialPrice > 0) {
				$item->setCustomPrice( $specialPrice );
				$item->setOriginalCustomPrice( $specialPrice );
				$item->getProduct()->setIsSuperMode( true );
				$customWeight = $item->getWeight() * $sqft;
				$item->setWeight($customWeight);
			}
			
			
		} // end if for product type
		
		$this->_logger->info("Ending Custom Price execute() -----------------------");
	}

}