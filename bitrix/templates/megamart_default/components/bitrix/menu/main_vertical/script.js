if (!window.RS) {
	window.RS = {};
}

RS.VerticalMenu = (function() {

	var VerticalMenu = function(menuBlockId) {
		this.$el = $("#" + menuBlockId);
		this.menuHeight = this.$el.outerHeight();

		var $itemsWithNesting = this.$el.find('.has-children');

		if ($('html').hasClass('bx-no-touch')) {
			$itemsWithNesting.on({
				mouseenter: this.reveal,
				mouseleave: this.conceal
			});
		} else {
			var _this = this;
			$itemsWithNesting.on({
				click: function (e) {
					var touchedEl = this;
					if (!$(touchedEl).data('is-open')) {
						e.preventDefault();
					}

					$(touchedEl).data('is-open', true);
				},
				touchstart: function(e) {
					var touchedEl = this;
					if ($(touchedEl).data('is-open')) {
						return;
					}

					_this.reveal.call(touchedEl, [e]);

					var closeFn = BX.debounce(function (e) {
						if (!$(e.target).is(touchedEl) && !$(e.target).closest(touchedEl).length) {
							$(touchedEl).data('is-open', false);

							_this.conceal.call(touchedEl, [e]);
							$('html').off('click touchstart', closeFn);
						}
					}, 100);

					$('html').on('click touchstart', closeFn);
				}
			});
		}
	};

	VerticalMenu.prototype.reveal = function() {
		var $item = $(this);
		var $target = $item.children('.mmenu-vertical-item__dropdown');
		var $container = $item.closest('.container');

		$target.show();

		if ($item.hasClass('mmenu-vertical-item--dd-item')) {
			$target
				.velocity('stop')
				.velocity(
					'transition.fadeIn', {
						duration: 250,
						delay: 0,
					}
				);
		} else {
			$target
				.velocity('stop')
				.velocity(
					'transition.slideUpIn', {
						duration: 300,
						delay: 0,
					}
				);
		}

	};

	VerticalMenu.prototype.conceal = function() {
		var $target = $(this).children('.mmenu-vertical-item__dropdown');

		$target.velocity('stop').velocity(
			'transition.fadeOut', {
				duration: 100,
				delay: 0,
				complete: function() {}
			}
		);
	}

	return VerticalMenu;
})();
