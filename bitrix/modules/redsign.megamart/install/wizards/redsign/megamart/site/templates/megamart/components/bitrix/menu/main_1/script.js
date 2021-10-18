if (!window.RS) {
	window.RS = {};
}

RS.MainMenu = (function() {

	var MainMenu = function(menuBlockId) {
		this.$el = $("#" + menuBlockId);
		this.menuHeight = this.$el.outerHeight();

		var $itemsWithNesting = this.$el.find('.has-children');
		setTimeout(BX.proxy(this.resizeMenu, this), 100);
		$(document).ready(BX.proxy(this.resizeMenu, this));

		$(window).on('resize', BX.debounce(BX.proxy(this.resizeMenu, this), 300));

		if (false || $('html').hasClass('bx-no-touch')) {
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

		this.$el.addClass('is-ready');
	};

	MainMenu.prototype.resizeMenu = function() {
		var $items = this.$el.children(),
			$isMore = $items.filter('.is-more'),
			containerWidth = this.$el.outerWidth(),
			usedWidth = 0,
			lastIndex = 0,
			isMoreWidth = 0,
			$cloneItems;

		this.$el.css({
			'overflow': 'hidden',
			'max-height': this.menuHeight,
			'opacity': '0',
		});

		$items.show();
		$isMore.insertAfter($items.filter(':last'));

		$items = this.$el.children();

		isMoreWidth = $isMore.width();

		$items.filter(':not(.is-more)').each(function(index, item) {
			var itemWidth = $(item).width();

			if (usedWidth + itemWidth > containerWidth) {
				$items.filter(':gt(' + (index - 1) + ')').hide();
				return false;
			}

			usedWidth += itemWidth;

			return true;
		});

		while (true) {
			if (usedWidth + isMoreWidth > containerWidth) {
				var lastVisibleItem = $items.filter(':visible:last');
				usedWidth -= lastVisibleItem.width();
				lastVisibleItem.hide();
			} else {
				if ($items.length - 1 > $items.filter(':visible').length) {
					$cloneItems = $items.filter(':hidden:not(.is-more)').clone().show();

					$isMore.insertAfter($items.filter(':visible:last'));

					$cloneItems
						.removeClass('mmenu-type1-item--primary mmenu-type1-item--dark mmenu-type1-item--transparent mmenu-type1-item--inheader has-children')
						.addClass('mmenu-type1-item--dd-item')
						.find('.mmenu-type1-item__dropdown').remove();

					$isMore.find('.mmenu-type1-item__dropdown').html($cloneItems);
					$isMore.show();
				} else {
					$isMore.hide();
					$isMore.find('.mmenu-type1-item__dropdown').html('');
				}
				break;
			}
		}

		this.$el.css({
			'overflow': 'visible',
			'max-height': 'none',
			'opacity': '1',
			'visibility': 'visible'
		});
	};

	MainMenu.prototype.reveal = function() {
		var $item = $(this);
		var $target = $item.children('.mmenu-type1-item__dropdown, .mmenu-type1-item__wide');
		var $container = $item.closest('.js-menu-container');
		var containerWidth = $container.width();

		console.log(containerWidth);

		if ($container.find('.l-mmenu-vertical').length) {
			containerWidth -= $container.find('.l-mmenu-vertical').width();
		}

		if ($target.hasClass('mmenu-type1-item__wide')) {
			$target.css('width', $container.width());
		}

		$target.show();

		if ($item.position().left + $target.outerWidth() > containerWidth) {
			$target
				.css('left', containerWidth - $item.position().left - $target.outerWidth());
		}

		if ($item.position().left + $target.outerWidth() * 2 > $container.width()) {
			$target
				.addClass('is-invert');
		}

		if ($item.hasClass('mmenu-type1-item--dd-item')) {
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

	MainMenu.prototype.conceal = function() {
		var $target = $(this).children('.mmenu-type1-item__dropdown, .mmenu-type1-item__wide');
		$target.velocity('stop').velocity(
			'transition.fadeOut', {
				duration: 100,
				delay: 0,
				complete: function() {
					$target.removeClass('is-invert');
					$target.css('left', '');
				}
			}
		);
	}

	return MainMenu;
})();
