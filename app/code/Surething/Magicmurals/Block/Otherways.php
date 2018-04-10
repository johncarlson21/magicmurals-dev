<?php
/**
 * Copyright © 2015 Surething . All rights reserved.
 */
namespace Surething\Magicmurals\Block;

class Otherways extends \Magento\Framework\View\Element\Template
{
	protected $categoryFactory;
	
	public function __construct(
		\Surething\Magicmurals\Block\Context $context,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory
	) {
		$this->categoryFactory = $categoryFactory;
		parent::__construct($context);
	}
	
	public function getCategories($catId = null, $sorted = false, $asCollection = true, $toLoad = true) {
		if (!$catId) { return ""; }
		$recursionLevel = 1;
		$catModel = $this->categoryFactory->create();
		$categories = $catModel->getCategories($catId, $recursionLevel, $sorted, $asCollection, $toLoad);
		return $categories;
	}
}