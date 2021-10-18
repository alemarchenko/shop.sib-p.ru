;(function(window, document, BX) {
	'use strict';

	if (!window.RS) {
		window.RS = {};
	}

	if (window.RS.GlobalCart) {
		return;
	}

	var globalCompareInstance;

	RS.GlobalCompare = function(ajaxPath, ajaxId, items) {
		this.ajaxPath = ajaxPath;
		this.ajaxId = ajaxId;
		this.items = items;
		
		
		BX.addCustomEvent(window, 'OnCompareChange', this.refresh.bind(this));
	};

	RS.GlobalCompare.init = function (ajaxPath, ajaxId, items) {
		if (!RS.GlobalCompare.getInstance()) {
			globalCompareInstance = new RS.GlobalCompare(ajaxPath, ajaxId, items);
		}

		return RS.GlobalCompare.getInstance();
	}

	RS.GlobalCompare.getInstance = function () {
		return globalCompareInstance;
	}

	RS.GlobalCompare.prototype = {
		refresh: function(data) {

			data = data || {};
			data['compare_list_reload'] = 'Y';
			data['ajax_id'] = this.ajaxId;

			BX.ajax.post(
				this.ajaxPath,
				data,
				BX.proxy(this.reloadResult, this)
			);
		},

		reloadResult: function(result) {
			var blocks = document.querySelectorAll('.js-global-compare');
			result = BX.parseJSON(result);

			for (var i = 0; i < blocks.length; i++) {
				var block = blocks[i];
				var countBlock = block.querySelector('.js-global-compare__count');

				if (result.COUNT > 0) {
					block.classList.add("has-items");
				}

				if (countBlock) {
					countBlock.innerHTML = result.COUNT;
				}
			}

			BX.onCustomEvent('GlobalStateChanged');
		}
	};
})(window, document, BX);
