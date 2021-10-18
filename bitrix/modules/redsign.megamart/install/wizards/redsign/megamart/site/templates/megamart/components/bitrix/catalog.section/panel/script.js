(function (window, document, $) {
	'use strict';

	window.JCWishlistPanel = function (blockId, params) {
		this.$block = $("#" + blockId);
		this.params = params;

		this.init();
	}

	$.extend(window.JCWishlistPanel.prototype, {

		init: function () {
			var obj = this;
			var $removeLinks = this.$block.find('[data-entity="remove-item"]');

			$removeLinks.on('click', function () {
				var elementId = $(this).closest('[data-entity="item"]').data('id');

				if (typeof elementId != 'string') {
	 				elementId = elementId.toString();
	 			}

				obj.removeItem(elementId);
			});

			BX.addCustomEvent(window, 'change.rs_favorite', function () {
				$('.js-global-favorite[data-type="ajax"][data-need-cache="Y"]').attr('data-need-reload', 'Y');
			});
		},

		removeItem: function (elementId) {
			if (!elementId) {
				return;
			}

			var url = window.location.href;

			var data = {};
			data['action'] = 'add2wishlist';
			data['element_id'] = elementId;
			data['sessid'] = BX.bitrix_sessid();

			this.$block.addClass('overlay');

			$.post(url, data, $.proxy(function (result) {
				
				RS.Favorite.remove(Number(elementId));
				BX.onCustomEvent('change.rs_favorite');

				var $item = this.$block.find('[data-entity="item"][data-id="' + elementId + '"]');
				$item.remove();

				this.$block.removeClass('overlay');
			}, this));
		},

		update: function () {

		}
	});
	
})(window, document, jQuery);
