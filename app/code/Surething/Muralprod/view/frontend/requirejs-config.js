var config = {
	map: {
        '*': {
            jCrop: 'Surething_Muralprod/js/jcrop/js/jquery.Jcrop.min',
			remodal: 'js/remodal/remodal'
        }
    },
    config: {
        mixins: {
            'Magento_Catalog/js/price-box': {
                'Surething_Muralprod/js/price-box': true
            },
			'Magento_Catalog/js/price-options': {
				'Surething_Muralprod/js/price-options': true
			}
        }
    }

};