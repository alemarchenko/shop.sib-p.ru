import $ from 'jquery';
import _merge from 'lodash/merge';
import isDesktop from '../utils/isDesktop';

const Menu = ($ => {

	const Default = {
		animationIn: 'animate-in',
		animationOut: 'animate-out',
		animateInBack: 'animate-in-back',
		animateOutBack: 'animate-out-back',

		selectors: {
			items: 'li',
			submenu: 'ul',
			isOpen: '.is-open',
			back: '.is-back',
			parent: 'body'
		}
	}

	const onAnimationEvent = function(el, type, listener) {
		const events = {
			'animationend': ['webkitAnimationEnd', 'oAnimationEnd', 'MSAnimationEnd', 'animationend']
		};

		if (events[type]) {
			events[type].forEach(function(eventName) {
				$(el).on(eventName, listener)
			});
		}
	}

	const offAnimationEvent = function(el, type) {
		const events = {
			'animationend': ['webkitAnimationEnd', 'oAnimationEnd', 'MSAnimationEnd', 'animationend']
		};

		if (events[type]) {
			events[type].forEach(function(eventName) {
				$(el).off(eventName)
			});
		}
	}

	class Menu {

		constructor($el, options) {
			this.options = _merge({}, Default, options);


			this.$menu = $el;
			this.$items = this.$menu.find(this.options.selectors.items).not(this.options.selectors.back);
			this.$back = this.$menu.find(this.options.selectors.back);
			this.$parent = this.$menu.closest(this.options.selectors.parent);


			this.offsets = [];

			this.initEvents();
		}

		hasSubmenu($item) {
			return $item.children(this.options.selectors.submenu).length > 0;
		}

		openSubmenu($item) {
			const $submenu = $item.children(this.options.selectors.submenu);
			const $flyin = $submenu.clone().css('opacity', 0).insertAfter(this.$menu);

			setTimeout(() => {
				this.offsets.push(this.$parent.scrollTop());
				this.$parent.scrollTop(0)
				$flyin.addClass(this.options.animationIn);
				this.$menu.addClass(this.options.animationOut);

				onAnimationEvent(this.$menu, 'animationend', () => {
					offAnimationEvent(this.$menu, 'animationend');
					this.$menu.removeClass(this.options.animationOut).addClass('is-view');
					$item.addClass('is-open').closest(this.options.selectors.submenu).addClass('is-view');

					$flyin.remove();
				});
			});
		}

		back($item) {
			const $submenu = $item.closest(this.options.selectors.submenu);
			const $flyin = $submenu.clone().insertAfter(this.$menu);

			setTimeout(() => {
				$flyin.addClass(this.options.animateOutBack);
				this.$menu.addClass(this.options.animateInBack);

				onAnimationEvent(this.$menu, 'animationend', () => {
					offAnimationEvent(this.$menu, 'animationend');
					this.$menu.removeClass(this.options.animationOut + ' ' + this.options.animateInBack);
					$flyin.remove();
				});

				$item.closest('.is-open').removeClass('is-open');
				$item.closest('.is-view').removeClass('is-view');
				this.$parent.scrollTop(this.offsets.pop());
			});
		}

		initEvents() {
			const self = this;

			self.$items.on('click', function(event) {
				event.stopPropagation();

				var $item = $(this);

				if (self.hasSubmenu($item)) {
					event.preventDefault();

					self.openSubmenu($item);
				}
			});

			self.$back.on('click', function(event) {
				event.stopPropagation();
				event.preventDefault();

				self.back($(this));
			});

		}
	}

	return Menu;
})($);

export default Menu;
