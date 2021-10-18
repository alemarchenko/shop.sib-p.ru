;
(function() {
	'use strict';

	var createSimpleBasketComponent = function () {
		var component = function () {}

		component = $.extend({}, BX.clone(BX.Sale.BasketComponentCopy, true));

		component.ids = {
			item: 'simple-basket-item-',
			quantity: 'simple-basket-item-quantity-',
			price: 'simple-basket-item-price-',
			sumPrice: 'simple-basket-item-sum-price-',
			sumPriceOld: 'basket-item-sum-price-old-',
			sumPriceDiff: 'basket-item-sum-price-difference-',
			itemHeightAligner: 'simple-basket-item-height-aligner-',
			total: 'basket-total-price',
			basketRoot: 'simple-basket-root',
			itemListWrapper: 'basket-items-list-wrapper',
			itemListContainer: 'basket-items-list-container',
			itemList: 'basket-item-list',
			itemListTable: 'simple-basket-item-table',
			itemListEmptyResult: 'basket-item-list-empty-result',
			itemListOverlay: 'basket-items-list-overlay',
			warning: 'simple-basket-warning'
		}

		component.getTemplate = function (templateName) {
			if (!this.templates.hasOwnProperty(templateName))
			{
				var template = BX("simple-" + templateName);
				this.templates[templateName] = BX.type.isDomNode(template) ? template.innerHTML : '';
			}

			return this.templates[templateName];
		}

		return component;
	}

	if (BX.Sale.BasketComponent) {
		BX.Sale.SimpleBasketComponent = createSimpleBasketComponent();

		$(document).on('panel.before_load', function (event, url) {
			if (url.indexOf('/ajax/cart.php') !== -1) {
				delete BX.Sale.SimpleBasketComponent;
				BX.Sale.SimpleBasketComponent = createSimpleBasketComponent();
			}
		});

		$(document).on('panel.show', function () {
			if (BX.Sale.SimpleBasketComponent.params) {
				BX.Sale.SimpleBasketComponent.checkStickyHeaders();
			}
		});

		$(document).on('panel.showed', function () {
			if (BX.Sale.SimpleBasketComponent.params) {
				BX.Sale.SimpleBasketComponent.checkStickyHeaders();
			}
		});

		BX.addCustomEvent(window, 'OnBasketChange', function () {
			$('.js-global-cart[data-type="ajax"][data-need-cache="Y"]').attr('data-need-reload', 'Y');
		});
	}
})();
