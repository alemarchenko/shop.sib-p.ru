;
(function(window, document, BX) {
	'use strict';

	if (!window.RS) {
		window.RS = {};
	}

	if (window.RS.GlobalCart) {
		return;
	}

	var globalBasketInstance;

	RS.GlobalBasket = function(siteId, templateName, ajaxPath, arParams) {
		this.itemRemoved = false;

		this.siteId = siteId;
		this.templateName = templateName;
		this.arParams = arParams;
		this.ajaxPath = ajaxPath;

		BX.addCustomEvent(window, 'OnBasketChange', this.refresh.bind(this));
	};

	RS.GlobalBasket.init = function(siteId, templateName, ajaxPath, arParams) {
		if (!globalBasketInstance) {
			globalBasketInstance = new RS.GlobalBasket(siteId, templateName, ajaxPath, arParams);
		}

		return globalBasketInstance;
	}

	RS.GlobalBasket.prototype = {
		refresh: function(data) {
			if (this.itemRemoved) {
				this.itemRemoved = false;
				return;
			}

			data = data || {};

			data.sessid = BX.bitrix_sessid();
			data.siteId = this.siteId;
			data.templateName = this.templateName;
			data.arParams = this.arParams;
			BX.ajax({
				url: this.ajaxPath,
				method: 'POST',
				dataType: 'json',
				data: data,
				onsuccess: this.updateBlocks
			});
		},

		updateBlocks: function(result) {
			var blocks = document.querySelectorAll('.js-global-cart');

			if (blocks !== undefined)
			{
				for (var i = 0; i < blocks.length; i++) {
					var block = blocks[i];
					var countBlock = block.querySelector('.js-global-cart__count');

					if (result.numProducts > 0) {
						block.classList.add("has-items");
					} else {
						block.classList.remove("has-items");
					}

					if (countBlock) {
						countBlock.innerHTML = result.numProducts;
					}
				}
			}

			BX.onCustomEvent('GlobalStateChanged');
		},

		removeItemFromCart: function(id) {
			this.refreshCart({
				sbblRemoveItemFromCart: id
			});
			this.itemRemoved = true;
			BX.onCustomEvent('OnBasketChange');
		}
	};
})(window, document, BX);
