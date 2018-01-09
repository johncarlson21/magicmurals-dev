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

namespace Surething\Muralprod\Observer\Sales;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Exception\LocalizedException;

class SalesQuoteAddItem implements \Magento\Framework\Event\ObserverInterface
{
	protected $_logger;
	protected $productFactory;
	
	public function __construct(\Psr\Log\LoggerInterface $logger,
			ProductFactory $productFactory
		) {
		$this->_logger = $logger;
		$this->productFactory = $productFactory;
	}
	
	public function execute(\Magento\Framework\Event\Observer $observer) {
		$this->_logger->info("Starting SalesQuoteAddItem execute() -----------------------");
		$item = $observer->getQuoteItem();
		$this->_logger->info('item id: '.$item->getId());
		$quote = $item->getQuote();
		
		$product = $item->getProduct();
		
		
		$productType = $product->getTypeID();
		
		// only run if mural product or pattern product
		if($productType == 'muralprod' || $productType == "patternprod") {
			
			$this->_logger->info('we have a muralprod');
			
			$_product = $this->productFactory->create()->load($product->getId());
			$this->_logger->info("small sq ft: ".$_product->getData('small_sq_ft'));
			
			$opts = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());

			$customSize = false;
			
			$hf = 0;
			$hi = 0;
			$wf = 0;
			$wi = 0;
			$sqft = 0;
			$minSqFt = 0;
			$smallSqFt = $_product->getData('small_sq_ft');
			$medSqFt = $_product->getData('medium_sq_ft');
			$largeSqFt = $_product->getData('large_sq_ft');
			$xlargeSqFt = $_product->getData('x_large_sq_ft');
			$clearanceSqFt = $_product->getData('clearance_sq_ft');
			
			foreach($opts['options'] as $ops) {
				if ($ops['label'] == "Size") {
					if (stripos($ops['value'], "Custom") !== false) {
						// got a custom size product
						$customSize = true;
					} elseif (stripos($ops['value'], "Small") !== false) {
						$minSqFt = $smallSqFt;
					} elseif (stripos($ops['value'], "Medium") !== false) {
						$minSqFt = $medSqFt;
					} elseif (stripos($ops['value'], "XLarge") !== false) {
						$minSqFt = $xlargeSqFt;
					} elseif (stripos($ops['value'], "Large") !== false) {
						$minSqFt = $largeSqFt;
					} elseif (stripos($ops['value'], "Clearance") !== false) {
						$minSqFt = $clearanceSqFt;
					} 
				}
				$this->_logger->info('--- minSqFt: '.$minSqFt);
				switch($ops['label']) {
					case 'width_ft':
						$wf = $ops['value'];
					break;
					case 'width_in':
						$wi = $ops['value'];
					break;
					case 'height_ft':
						$hf = $ops['value'];
					break;
					case 'height_in':
						$hi = $ops['value'];
					break;
					case 'Square Ft':
						$sqft = $ops['value'];
					break;
				}
				$this->_logger->info('--- sqft: '.$sqft);
			}
			
			if ($customSize == true) {
				// check for sqft calculation
				$height = 0;
				$width = 0;
				if ($hf > 0) { $height = $hf * 12; }
				if ($hi > 0) { $height = $height + $hi; }
				$height = ($height / 12);
				$height = round($height, 2);

				if ($wf > 0) { $width = $wf * 12; }
				if ($wi > 0) { $width = $width + $wi; }
				$width = ($width / 12);
				$width = round($width, 2);

				$actualSqFt = round($height * $width);
				if ( $actualSqFt < 12 ) {
					$actualSqFt = 12;
				}
				
				if ($actualSqFt != $sqft) {
					$this->_logger->info("---------- The square ft sent does not calculate to the size entered!");
					$quote->deleteItem($item);
					throw new LocalizedException(__('There was an error with adding your product ( '.$product->getName().') to the cart! Please contact us for assistance!'));
					//$quote->save();
					$this->_logger->info("---------- Removing Item ID: ".$item->getItemId());
				}
				
			} // end if for custom size
			else {
				
				if ($minSqFt != $sqft) {
					$this->_logger->info("---------- The sent square ft does not match the product square ft");
					$quote->deleteItem($item);
					throw new LocalizedException(__('There was an error with adding your product ( '.$product->getName().') to the cart! Please contact us for assistance!'));
					//$quote->save();
					$this->_logger->info("---------- Removing Item ID: ".$item->getItemId());
				}
				
			} // end else for custom size
			
			
		} // end if for product type
		
		$this->_logger->info("Ending SalesQuoteAddItem execute() -----------------------");
	}
	
}