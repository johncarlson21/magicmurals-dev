<?php
/**
 * Copyright © 2015 Surething. All rights reserved.
 */

namespace Surething\Testimonials\Model;

use Magento\Framework\Exception\TestimonialsException;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Testimonialstab testimonials model
 */
class Testimonials extends \Magento\Framework\Model\AbstractModel implements TestimonialsInterface, \Magento\Framework\DataObject\IdentityInterface
{
	
	const CACHE_TAG = 'surething_testimonials_testimonials';
	
	protected $_filesystem;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\Db $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('Surething\Testimonials\Model\ResourceModel\Testimonials');
    }
	
	public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
	
	public function getGalleryItems($filters="", $page=1) {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$filesystem = $objectManager->get('Magento\Framework\Filesystem');
		$mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
		$fileDir = $mediaDirectory->getAbsolutePath('');
		//error_log('filter: '.$filters);
		if (!empty($filters)) {
			$testimonials = $this->getCollection()
				->setOrder('testimony_order', 'ASC')
				->setPageSize(20)
				->setCurPage($page)
				//->addFieldToFilter('testimony_type', array('regexp' => '[[:<:]]'.$filters.'[[:>:]]'))//;
				->addFieldToFilter('testimony_type', array(
					//array('finset', $filters)
					'finset' => $filters
				))
				->load();
		} else {
			$testimonials = $this->getCollection()
				->setOrder('testimony_order', 'ASC')
				->setPageSize(20)
				->setCurPage($page)
				->load();
		}
		
		$lastPage = $testimonials->getLastPageNumber();
		
		$testArray = array('data'=>'','lastPage'=>'');
		
		if ($page > $lastPage) { $testArray['data'] = ""; $testArray['lastPage'] = $lastPage; return json_encode($testArray); }
		
		foreach($testimonials as $t) {
			$url = $t->getTestimonyUrl();
			$urls = explode(",", $url);
			$testUrl = $urls[0];
			
			$testArray['data'][] = array(
				'url' => $testUrl,
				'imageUrl' => "/lib/web/phpThumb/phpThumb.php?src=" . $fileDir.$t->getTestimonyFilename() . "&w=300",
				'fullImage' => "/lib/web/phpThumb/phpThumb.php?src=" . $fileDir.$t->getTestimonyFilename() . "&w=800",
				'name' => $t->getTestimonyName(),
				'filename' => $t->getTestimonyFilename(),
				'company' => $t->getTestimonyCompany(),
				'install' => $t->getTestimonyInstall(),
				'headline' => $t->getTestimonyHeadline(),
				'description' => $t->getTestimonyDescription(),
				'type' => $t->getTestimonyType(),
				'mural' => $t->getTestimonyMural()
			);
			
		}
		$testArray['lastPage'] = $lastPage;
		return $testArray;
	}
   
}