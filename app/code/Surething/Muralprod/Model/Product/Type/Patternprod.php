<?php

 
namespace Surething\Muralprod\Model\Product\Type;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
 
//class Type extends \Magento\Catalog\Model\Product\Type\AbstractType {
class Patternprod extends \Magento\Catalog\Model\Product\Type\Simple {

	const TYPE_ID = 'patternprod';
	
	public function __construct(
        \Magento\Catalog\Model\Product\Option $catalogProductOption,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger,
        ProductRepositoryInterface $productRepository
    ) {
        $this->_catalogProductOption = $catalogProductOption;
        $this->_eavConfig = $eavConfig;
        $this->_catalogProductType = $catalogProductType;
        $this->_coreRegistry = $coreRegistry;
        $this->_eventManager = $eventManager;
        $this->_fileStorageDb = $fileStorageDb;
        $this->_filesystem = $filesystem;
        $this->_logger = $logger;
        $this->productRepository = $productRepository;
    }
	
	public function save($product)
    {
        parent::save($product);
        //  your additional saving logic
        return $this;
    }
 
    /**
     * {@inheritdoc}
     */
	public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
        // method intentionally empty
    }
	
 
}