<?php
namespace Surething\Testimonials\Block\Adminhtml\Testimonials\Edit\Tab;
class TestimonyInformation extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = array()
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
		/* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('testimonials_testimonials');
		$isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('Testimony Information')));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array('name' => 'id'));
        }

		$fieldset->addField(
            'testimony_name',
            'text',
            array(
                'name' => 'testimony_name',
                'label' => __('Customer Name'),
                'title' => __('Customer Name'),
                'required' => true,
				"class" => "required-entry",
            )
        );
		$fieldset->addField(
            'testimony_company',
            'text',
            array(
                'name' => 'testimony_company',
                'label' => __('Company'),
                'title' => __('Company'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'testimony_install',
            'text',
            array(
                'name' => 'testimony_install',
                'label' => __('Install City/State'),
                'title' => __('Install City/State'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'testimony_filename',
            'image',
            array(
                'name' => 'testimony_filename',
                'label' => __('Filename'),
                'title' => __('Filename'),
				'note' => '(*.jpg, *.png, *.gif)',
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'testimony_headline',
            'text',
            array(
                'name' => 'testimony_headline',
                'label' => __('Headline'),
                'title' => __('Headline'),
				"class" => "required-entry",
                'required' => true,
            )
        );
		$fieldset->addField(
            'testimony_description',
            'textarea',
            array(
                'name' => 'testimony_description',
                'label' => __('Description'),
                'title' => __('Description'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'testimony_type',
            'multiselect',
            array(
                'name' => 'testimony_type',
                'label' => __('Type'),
                'title' => __('Type'),
                'required' => true,
				"class" => "required-entry",
				"values" => array(
					array('value' => '-1', 'label' => "Please Select.."),
					array('value' => "R", 'label' => "Residential"),
					array('value' => "Ba", 'label' => "-- Bathroom"),
					array('value' => "Be", 'label' => "-- Bedroom"),
					array('value' => "Bor", 'label' => "-- Bonus Room"),
					array('value' => "Bo", 'label' => "-- Boy's Room"),
					array('value' => "D", 'label' => "-- Dining Room"),
					array('value' => "Do", 'label' => "-- Dorm Room"),
					array('value' => "Eh", 'label' => "-- Entry/Hallway"),
					array('value' => "Rf", 'label' => "-- Residential 'Framed'"),
					array('value' => "G", 'label' => "-- Garage"),
					array('value' => "Gi", 'label' => "-- Girl's Room"),
					array('value' => "Ho", 'label' => "-- Home Office"),
					array('value' => "Kid", 'label' => "-- Kid's Room"),
					array('value' => "Ki", 'label' => "-- Kitchen"),
					array('value' => "L", 'label' => "-- Living Room"),
					array('value' => "M", 'label' => "-- Man Cave"),
					array('value' => "N", 'label' => "-- Nursery"),
					array('value' => "P", 'label' => "-- Playroom"),
					array('value' => "T", 'label' => "-- Teen's Room"),
					array('value' => "Ro", 'label' => "-- Other"),

					array('value' => "B", 'label' => "Business"),
					array('value' => "Br", 'label' => "-- Breweries & Wineries"),
					array('value' => "Ch", 'label' => "-- Churches & Religion"),
					array('value' => "Co", 'label' => "-- Corporate & Offices"),
					array('value' => "E", 'label' => "-- Entertainment"),
					array('value' => "Bf", 'label' => "-- Business 'Framed'"),
					array('value' => "Hot", 'label' => "-- Hotels"),
					array('value' => "I", 'label' => "-- Interior Designers"),
					array('value' => "Li", 'label' => "-- Libraries"),
					array('value' => "Me", 'label' => "-- Medical Facilities"),
					array('value' => "Mu", 'label' => "-- Museums"),
					array('value' => "Rc", 'label' => "-- Residential Communities"),
					array('value' => "Re", 'label' => "-- Restaurants"),
					array('value' => "Ret", 'label' => "-- Retail"),
					array('value' => "S", 'label' => "-- Salons & Spas"),
					array('value' => "Sc", 'label' => "-- Schools & Universities"),
					array('value' => "Buo", 'label' => "-- Other")
					)// end main array
            )
        );
		$fieldset->addField(
            'testimony_mural',
            'text',
            array(
                'name' => 'testimony_mural',
                'label' => __('Mural'),
                'title' => __('Mural'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'testimony_url',
            'text',
            array(
                'name' => 'testimony_url',
                'label' => __('URL'),
                'title' => __('URL'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'testimony_order',
            'text',
            array(
                'name' => 'testimony_order',
                'label' => __('Order'),
                'title' => __('Order'),
                /*'required' => true,*/
            )
        );
		/*{{CedAddFormField}}*/
        
        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();   
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Testimony Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Testimony Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
