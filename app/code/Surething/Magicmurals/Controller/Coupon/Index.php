<?php

namespace Surething\Magicmurals\Controller\Coupon;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $jsonHelper;
	protected $customerRepository;
	protected $subscriber;
	protected $transportBuilder;
	protected $scopeConfig;
	protected $storeManager;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
		\Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
		\Magento\Newsletter\Model\Subscriber $subscriber,
		\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $subscriber);
		$this->customerRepository = $customerRepositoryInterface;
		$this->subscriber = $subscriber;
		$this->transportBuilder = $transportBuilder;
		$this->scopeConfig = $scopeConfig;
		$this->storeManager = $storeManager;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->indexAction();
		
		/* for json stuff */
		/*try {
            return $this->jsonResponse('your response');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }*/
    }
	
	public function indexAction()
    {
		$request = $this->getRequest();
		if($request->getParam('subscribe') == '1') {
			$subscriber = null;
			$status = 0;
			try {
				$checkSubscriber = $this->subscriber->loadByEmail($request->getParam('email'));
				if ($checkSubscriber->isSubscribed()) {
					$status = 1;
				}
			} catch (\Magento\Framework\Exception\LocalizedException $e) {
				// we have a non subscribed person
				return;
			}
			
			if ($status == 1) { echo "subscribed"; return; }

			//$this->subscriber->subscribe($request->getParam('email'));
			$this->subscribe($request->getParam('email'));
		} else {
			echo "error";
			return;
		}
		
    	$to_email = $request->getParam('email');
		
		$sender = [
            'email' => $this->scopeConfig->getValue(
				'trans_email/ident_sales/email',
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			),
			'name' => $this->scopeConfig->getValue(
				'trans_email/ident_sales/name',
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			)
        ];
		$transport = $this->transportBuilder
            ->setTemplateIdentifier('coupon_template') // this code we have mentioned in the email_templates.xml
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(['data' => ""])
            ->setFrom($sender)
            ->addTo($to_email)
            ->getTransport();
		
		try {
			$transport->sendMessage();
			echo "success";
		}  catch (\Magento\Framework\Exception\LocalizedException $e) {
			echo "error";
		}
		
    }
	
	public function subscribe($email) {
		$customer = null;
		try {
			$customer = $this->customerRepository->get($email);
		} catch (\Magento\Framework\Exception\LocalizedException $e) {
			// nothing to do here
		}
		
		$this->subscriber->setSubscriberConfirmCode($this->subscriber->randomSequence());
		$this->subscriber->setSubscriberEmail($email);
		$this->subscriber->setStatus($this->subscriber::STATUS_SUBSCRIBED);
		if ($customer != null) {
			$this->subscriber->setStoreId($customer->getStoreId());
			$this->subscriber->setCustomerId($customer->getId());
		} else {
			$this->subscriber->setStoreId($this->storeManager->getStore()->getId());
            $this->subscriber->setCustomerId(0);
		}
		$this->subscriber->setStatusChanged(true);
		try {
			$this->subscriber->save();
		} catch (\Magento\Framework\Exception\LocalizedException $e) {
			echo "error";
		}
		
	}

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}
