;
(function(BX, document, window) {
	'use strict';

	if (!window.RS) {
		window.RS = {};
	}

	if (!!window.RS.Basket || !BX) {
		return;
	}

	function Basket() {
		this.ajaxUrl = '/bitrix/components/bitrix/sale.basket.basket/ajax.php';
		this.inBasketProducts = [];
	}

	Basket.prototype.inbasket = function(ids, isRewrite) {

		isRewrite = isRewrite || false;

		if (ids)
		{
			ids = $.isArray(ids) ? ids : [ids];

			ids = ids.map(function(val) {
				return parseInt(val, 10);
			});

			if (isRewrite) {
				this.inBasketProducts = ids;
			} else {
				this.inBasketProducts = BX.util.array_merge(this.inBasketProducts, ids);
			}
		}

		return this.inBasketProducts;
	};

	Basket.prototype.frombasket = function (removeId) {
		this.inBasketProducts.forEach(function (id, index) {
			if (id == removeId) {
				this.inBasketProducts = BX.util.deleteFromArray(this.inBasketProducts, index);
			}
		}.bind(this));
	}

	Basket.prototype.add = function(productId, quantity) {
		var params = {};

		params.action = 'add2basket';
		params.product_id = productId;
		params.sessid = BX.bitrix_sessid();

		if (quantity) {
			params.quantity = quantity;
		}

		return BX.ajax.post(this.ajaxUrl, params, function(result) {
			result = BX.parseJSON(result);
			if (result) {
				this.inbasket(productId, false);
				BX.onCustomEvent('OnBasketChange');
			}
		}.bind(this));

		return url;
	};

	Basket.prototype.updateQuantity = function(itemId, newQuantity) {
		var params = {
				'action': 'recalculate',
				'sessid': BX.bitrix_sessid(),
				'site_id': BX.message('SITE_ID'),
				'props': {},
				'action_var': 'action',
				'select_props': 'QUANTITY',
				'offers_props': '',
				'quantity_float': 'N',
				'count_discount_4_all_quantity': 'Y',
				'price_vat_show_value': 'Y',
				'hideCoupon': 'Y',
				'use_prepayment': 'N'
		};

		params['QUANTITY_' + itemId] = newQuantity;


		return BX.ajax.post(this.ajaxUrl, params, function(result) {
			result = BX.parseJSON(result);

			if(result) {
				BX.onCustomEvent('OnBasketChange', [result.BASKET_DATA]);
			}
		});
	};

	Basket.prototype.delete = function(itemId, productId) {
		var params = {
				'action': 'recalculate',
				'sessid': BX.bitrix_sessid(),
				'site_id': BX.message('SITE_ID'),
				'props': {},
				'action_var': 'action',
				'select_props': 'DELETE',
				'offers_props': '',
				'quantity_float': 'N',
				'count_discount_4_all_quantity': 'Y',
				'price_vat_show_value': 'Y',
				'hideCoupon': 'Y',
				'use_prepayment': 'N'
		};

		params['DELETE_' + itemId] = 'Y';

		return BX.ajax.post(this.ajaxUrl, params, function(result) {
			result = BX.parseJSON(result);

			if(result) {
				this.frombasket(productId);
				BX.onCustomEvent('OnBasketChange', [result.BASKET_DATA]);
			}
		}.bind(this));
	};


	Basket.prototype.clear = function(ids) {

		var promise = new BX.Promise();

		var params = {
				'action': 'recalculate',
				'sessid': BX.bitrix_sessid(),
				'site_id': BX.message('SITE_ID'),
				'props': {},
				'action_var': 'action',
				'select_props': 'DELETE',
				'offers_props': '',
				'quantity_float': 'N',
				'count_discount_4_all_quantity': 'Y',
				'price_vat_show_value': 'Y',
				'hideCoupon': 'Y',
				'use_prepayment': 'N'
		};


		ids.forEach(function (itemId) {
			params['DELETE_' + itemId] = 'Y';
		});

		BX.ajax.post(this.ajaxUrl, params, function(result) {
			result = BX.parseJSON(result);

			if(result) {
				this.inbasket([], true);
				BX.onCustomEvent('OnBasketChange', [result.BASKET_DATA]);
				promise.resolve();
			}
		}.bind(this));

		return promise;
	}

	window.Basket = new Basket;

}(BX, document, window));
