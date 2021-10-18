if (!window.RS) {
	window.RS = {};
}

RS.ToplineMenu = (function() {

	var ToplineMenu = function(menuBlockId) {
		this.$el = $("#" + menuBlockId);
		this.menuHeight = this.$el.outerHeight();

		var $itemsWithNesting = this.$el.find('.has-children');
		setTimeout(BX.proxy(this.resizeMenu, this), 100);
		$(document).ready(BX.proxy(this.resizeMenu, this));
		$(window).on('resize', BX.debounce(BX.proxy(this.resizeMenu, this), 100));

		$itemsWithNesting.on({
			mouseenter: this.reveal,
			mouseleave: this.conceal
		});
	};

	ToplineMenu.prototype.resizeMenu = function() {
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

					$isMore.find('.topline-menu-item__dropdown').html($cloneItems);
					$isMore.show();
				} else {
					$isMore.hide();
					$isMore.find('.topline-menu-item__dropdown').html('');
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

	ToplineMenu.prototype.reveal = function() {
		var $item = $(this);
		var $target = $item.children('.topline-menu-item__dropdown');
		var $container = $item.closest('.topline-menu');
		var containerWidth = $container.width();

		$target.show();

		console.log($target);

		$target
			.velocity('stop')
			.velocity(
				'transition.slideUpIn', {
					duration: 300,
					delay: 0,
				}
			);

	};

	ToplineMenu.prototype.conceal = function() {
		var $target = $(this).children('.topline-menu-item__dropdown');
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

	return ToplineMenu;
})();
