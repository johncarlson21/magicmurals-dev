define([
		'jquery',
		'Magento_Catalog/js/price-utils',
		'underscore',
		'mage/template',
		'jquery/ui'
	], function ($, utils, _, mageTemplate) {
		'use strict';
		
		return function (priceBox) {
			return $.widget('mage.priceBox', priceBox, {
				reloadPrice: function reDrawPrices() {
					
					var priceFormat = (this.options.priceConfig && this.options.priceConfig.priceFormat) || {},
						priceTemplate = mageTemplate(this.options.priceTemplate);

					_.each(this.cache.displayPrices, function (price, priceCode) {
						price.final = _.reduce(price.adjustments, function(memo, amount) {
							return memo + amount;
						}, price.amount);
						// you can put your custom code here.
						
						price.final = price.final * minSqFt;

						price.formatted = utils.formatPrice(price.final, priceFormat);

						$('[data-price-type="' + priceCode + '"]', this.element).html(priceTemplate({data: price}));
					}, this);
				}
			});
		};
		
	});