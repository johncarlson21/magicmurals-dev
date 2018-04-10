<?php
/**
 * Copyright © 2015 Surething . All rights reserved.
 */
namespace Surething\Testimonials\Block;
use Magento\Framework\UrlFactory;
use Infortis\Brands\Helper\Data;

class BaseBlock extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \Surething\Testimonials\Helper\Data
     */
	 protected $_devToolHelper;
	 
	 /**
     * @var \Magento\Framework\Url
     */
	 protected $_urlApp;
	 
	 /**
     * @var \Surething\Testimonials\Model\Config
     */
    protected $_config;
	
	protected $testimonialsFactory;
	protected $brandHelper;

    /**
     * @param \Surething\Testimonials\Block\Context $context
	 * @param \Magento\Framework\UrlFactory $urlFactory
     */
    public function __construct( 
		\Surething\Testimonials\Block\Context $context,
		\Surething\Testimonials\Model\TestimonialsFactory $testimonialsFactory,
		\Infortis\Brands\Helper\Data $brandHelper
	)
    {
        $this->_devToolHelper = $context->getTestimonialsHelper();
		$this->_config = $context->getConfig();
        $this->_urlApp=$context->getUrlFactory()->create();
		$this->testimonialsFactory = $testimonialsFactory;
		$this->brandHelper = $brandHelper;
		parent::__construct($context);
	
    }
	
	/**
	 * Function for getting event details
	 * @return array
	 */
    public function getEventDetails()
    {
		return  $this->_devToolHelper->getEventDetails();
    }
	
	/**
     * Function for getting current url
	 * @return string
     */
	public function getCurrentUrl(){
		return $this->_urlApp->getCurrentUrl();
	}
	
	/**
     * Function for getting controller url for given router path
	 * @param string $routePath
	 * @return string
     */
	public function getControllerUrl($routePath){
		
		return $this->_urlApp->getUrl($routePath);
	}
	
	/**
     * Function for getting current url
	 * @param string $path
	 * @return string
     */
	public function getConfigValue($path){
		return $this->_config->getCurrentStoreConfigValue($path);
	}
	
	/**
     * Function canShowTestimonials
	 * @return bool
     */
	public function canShowTestimonials(){
		$isEnabled=$this->getConfigValue('testimonials/module/is_enabled');
		if($isEnabled)
		{
			$allowedIps=$this->getConfigValue('testimonials/module/allowed_ip');
			 if(is_null($allowedIps)){
				return true;
			}
			else {
				$remoteIp=$_SERVER['REMOTE_ADDR'];
				if (strpos($allowedIps,$remoteIp) !== false) {
					return true;
				}
			}
		}
		return false;
	}
	
	public function getBrandHelper() {
		return $this->brandHelper;
	}
	
	public function getMediaDirectory() {
		$testModel = $this->testimonialsFactory->create();
		$dir = $testModel->getMediaDirectory();
		return $dir;
	}
	
	public function getTestimonialsDESC() {
		$testModel = $this->testimonialsFactory->create();
		$testimonials = $testModel->getTestimonialsDESC();
		return $testimonials;
	}
	
}
