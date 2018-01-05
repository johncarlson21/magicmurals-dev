<?php
namespace Kadro\ImageAnalyzer\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const KADRO_IMAGE_ANALYZER_LOW_LIMIT_CONFIG_PATH = 'kadro_imageanalyzer/settings/limit_low';
    const KADRO_IMAGE_ANALYZER_HIGH_LIMIT_CONFIG_PATH = 'kadro_imageanalyzer/settings/limit_high';

    /**#@+
     * High and Low limits for image quality ratio
     */
    protected $_lowLimit  = 0.05;
    protected $_highLimit = 0.50;
    /**#@-**/

    /**
     * Image to be analyzed
     */
    protected $_image;

    /**
     * Requested sq.ft.
     */
    protected $_rSqFt;

    /**
     * Set necessary values
     */
    protected $_ratio = 0.00;
    protected $_quality = 0;
    protected $_qText;
    protected $_MP = 0;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
	
	protected $_layout;

    /**
     * Construct Class with arguments
     *
     * @param $image
     * @param $wFt
     * @param $wIn
     * @param $hFt
     * @param $hIn
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Framework\View\LayoutInterface $layout,
        $image = false, $wFt = false, $wIn = false, $hFt = false, $hIn = false)
    {
        $this->scopeConfig = $scopeConfig;
		$this->_layout = $layout;
		
        /*parent::__construct(
            $context
        );*/

        // Set image if provided
        if ($image) {
            $this->setImage($image);
        }

        // Set Sq. Footage if provided
        if ((false !== $wFt && false !== $wIn) && (false !== $hFt && false !== $hIn)) {
            $this->setDims($wFt, $wIn, $hFt, $hIn);
        }
    }

    /**
     * Set Image File
     *
     * @param $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        if (strlen($image) > 0 && file_Exists($image)) {
            $this->_image = $image;
        }

        return $this;
    }

    /**
     * Set Dimensions and Calculate SqFt.
     *
     * @param $wFt
     * @param $wIn
     * @param $hFt
     * @param $hIn
     *
     * @return $this
     */
    public function setDims($wFt, $wIn, $hFt, $hIn)
    {
        // Set Sq. Footage if provided
        if ((false !== $wFt && false !== $wIn) && (false !== $hFt && false !== $hIn)) {
            // Calculate Square Footage
            $_rW = (float)$wFt + ($wIn / 12);
            $_rH = (float)$hFt + ($hIn / 12);
            $this->_rSqFt = $_rW * $_rH;
        }

        return $this;
    }

    /**
     * Get Low Limit Config Value
     *
     * @return float
     */
    public function getLowLimit()
    {
        $limitConfig = floatval($this->scopeConfig->getValue(self::KADRO_IMAGE_ANALYZER_LOW_LIMIT_CONFIG_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        $this->_lowLimit = ($limitConfig > 0.00) ? $limitConfig : $this->_lowLimit;

        return $this->_lowLimit;
    }

    /**
     * Get High Limit Config Value
     *
     * @return float
     */
    public function getHighLimit()
    {
        $limitConfig = floatval($this->scopeConfig->getValue(self::KADRO_IMAGE_ANALYZER_HIGH_LIMIT_CONFIG_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        $this->_highLimit = ($limitConfig > 0.00) ? $limitConfig : $this->_highLimit;

        return $this->_highLimit;
    }

    /**
     * Perform Analysis of image and return results
     * 	Quality = good (2), fair (1), bad (0)
     *
     * @return array
     */
    public function getQuality()
    {
        $megaPixels = $this->_getMegaPixels();
		
        // Get MP to sq.ft. ratio
        $this->_ratio = $megaPixels / $this->_rSqFt;

        // Determine Quality based on pixel ratio
        if ($this->_ratio < $this->getLowLimit()) {
            $this->_quality = 0;
        } elseif ($this->_ratio >= $this->getHighLimit()) {
            $this->_quality = 2;
        } else {
            $this->_quality = 1;
        }

        // Provide array of result details
        $results = array(
            'sqFt'    => $this->_rSqFt,
            'MP'      => $megaPixels,
            'dims'    => getimagesize($this->_image),
            'ratio'   => $this->_ratio,
            'quality' => $this->_quality,
            'text'    => $this->getQualityText(),
            'dpi'     => $this->_getDpi(),
        );

        return $results;
    }

    protected function _getDpi(){
        $a = fopen($this->_image,'r');
        $string = fread($a,20);
        fclose($a);

        $data = bin2hex(substr($string,14,4));
        $x = substr($data,0,4);
        $y = substr($data,0,4);

        return array(hexdec($x),hexdec($y));
    }

    /**
     * Get MegaPixel value for Image
     *
     * @return float
     */
    protected function _getMegaPixels()
    {
        if ($this->_MP <= 0 && $this->_image) {
            list($width, $height) = getimagesize($this->_image);

            // Calculate MegaPixels
            $this->_MP = ($width / 1000) * ($height / 1000);
        }

        return $this->_MP;
    }

    /**
     * Get Text value for quality
     *
     * @return string
     */
    public function getQualityText()
    {
        switch ($this->_quality)
        {
            case 0:
                $this->_qText = 'poor';
                break;
            case 1:
                $this->_qText = 'fair';
                break;
            case 2:
                $this->_qText = 'good';
                break;
            default:
                $this->_qText = 'unknown';
                break;
        }

        return $this->_qText;
    }
	
	/**
     * Create new block
     */
     public function getBlock($file) {

        $html = $this->_layout
            ->createBlock('Magento\Framework\View\Element\Template')
            ->setTemplate('Kadro_ImageAnalyzer::' . $file)
            ->toHtml();

        return $html;

     }
}