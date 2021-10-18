import $ from 'jquery';
import _assign from 'lodash/assign';
import isDesktop from '../utils/isDesktop';

const Compact = ($ => {

	const Default = {
		selectors: {
			root: 'body',
			wrapper: '.l-page',
			menu: '.l-compact-menu',
			toggleButton: '[data-compact-menu-toggle]',
			search: '#compact-title-search'
		}
	};

	const Events = {
		fixed: 'fixed.compact-header',
		unfixed: 'unfixed.compact-header',
		revealMenu: 'reveal.compact-menu',
		concealMenu: 'conceal.compact-menu'
	};

	class Compact {

		constructor(el, options) {
			this.options = $.extend({}, Default, options);

			this.isRevealMenu = false;
			this.isFixing = false;
			this.prevScroll = $(window).scrollTop();

			this.$element = $(el);
			this.resolveSelectors();
		}

		resolveSelectors() {
			this.$root = $(this.options.selectors.root);
			this.$wrapper = $(this.options.selectors.wrapper);
			this.$menu = $(this.options.selectors.menu);
			this.$search = $(this.options.selectors.search);
		}

		toggleMenu(button) {
			if (this.isRevealMenu) {
				this.concealMenu(button);
			} else {
				this.revealMenu(button);
			}
		}

		revealMenu(button) {
			this.$menu.addClass('is-open');
			$(this.options.selectors.toggleButton).addClass('is-active');

			if (isDesktop()) {
				this.revealMenuDesktop(button);
			} else {
				this.revealMenuMobile(button);
			}

			this.isRevealMenu = true;

			this.$menu.trigger(Events.revealMenu);
		}

		revealMenuMobile(button) {
			let menuOffset = 0;

			if (this.isFixing) {
				menuOffset = this.$element.position().top + this.$element.outerHeight();
			} else {
				menuOffset = this.$element.offset().top + this.$element.outerHeight() - $(window).scrollTop();
			}

			this.$element.css({
				paddingRight: window.innerWidth - $(document).width() // scrollbar width
			});

			this.$root.css({
				overflow: 'hidden'
			});

			this.$menu.css({
				paddingTop: menuOffset + 'px',
			});

			const checkResize = () => {
				if (isDesktop()) {
					this.$menu.one(Events.concealMenu, () => $(window).off('resize', checkResize));
					this.concealMenu();
				}
			}

			$(window).resize(checkResize);
		}

		revealMenuDesktop(button) {
			var documentHeight = $(document).height();
			var maxHeight = documentHeight - this.$element.outerHeight();

			const calc = () => {

				this.$menu
					.css({
						top: this.$element.children().outerHeight(), //(this.$element.offset().top + this.$element.children().outerHeight()) + 'px',
						left: $(button).offset().left,
					// height: $(window).outerHeight() - this.$element.children().outerHeight()
					});
			};

			const conceal = () => {
				this.$menu.one(
					Events.concealMenu,
					() => {
						$(window).off('scroll', onScroll);
						this.$menu.one(Events.concealMenu, () => $(window).off('resize', checkResize));
						$(document).off('click', onKeyDown);
					}
				);

				this.concealMenu();
			};

			const onScroll = () => {
				if (this.isFixing) {
					calc();
				} else {
					conceal();
				}
			};

			const checkResize = () => {
				documentHeight = $(document).height();
				maxHeight = documentHeight - this.$element.outerHeight();

				if (!isDesktop()) {
					conceal();
				} else {
					calc();
				}
			}

			const onKeyDown = () => {
				var $target = $(event.target);
				if (!$target.is(this.$menu) || $target.closest(this.$menu).length == 0) {
					conceal();
				}
			}

			//setTimeout(() => {
				calc();
			//});

			$(window).on('scroll', onScroll);
			$(window).resize(checkResize);
			$(document).on('click', onKeyDown);
		}

		concealMenu() {
			this.$root.css('overflow', 'auto');
			this.$wrapper.attr('style', '');
			this.$menu.attr('style', '');
			this.$element.css({
				paddingRight: 0
			});

			this.$menu.removeClass('is-open');
			$(this.options.selectors.toggleButton).removeClass('is-active');
			this.isRevealMenu = false;

			this.$menu.trigger(Events.concealMenu);
		}

		revealMobileSearch() {
			if (!this.$search.length) {
				return;
			}

			this.$search
				.addClass('js-is-open')
				.css({
					'top': -this.$search.outerHeight(),
					'opacity': 0,
					'display': 'block',
					'will-change': 'top'
				});

			setTimeout(() => {
				this.$search
					.velocity('stop')
					.velocity({
						'top': 0,
						'opacity': 1
					}, {
						complete: () => {
							this.$search.find('input:eq(0)').focus()
						}
					});
			});
		}

		concealMobileSearch() {
			this.$search
				.velocity('stop')
				.velocity({
					'opacity': 0,
					'top': -this.$search.outerHeight(),
				}, {
					complete: () => {
						this.$search.css({
							display: 'none'
						});
					}
				});
		}

		fixing() {
			let height = this.$element.outerHeight();
			const $wrap = $('<div>')
				.css({
					height: isDesktop() ? 0 : height,
					position: 'relative',
					top: 0
				});

			this.$element.wrap($wrap);

			$(window).resize(() => {
				height = this.$element.outerHeight();

				this.$element.parent().css({
					height: isDesktop() ? 0 : height
				});
			});

			const fixing = () => {
				if (!this.isFixing) {
					let checkScroll = isDesktop() ? (
						$(window).scrollTop() >= 200 && $(window).scrollTop() <= this.prevScroll
					) : (
						$(window).scrollTop() >= this.$element.offset().top
					);

					this.prevScroll = $(window).scrollTop(); 

					if (checkScroll) {
						this.$element.css({
							position: 'fixed',
							width: '100%',
							top: '-1px' // i dont undestand why safari is so stupid
						});

						this.isFixing = true;
						this.$element.addClass('is-fixed');
						this.$element.trigger(Events.fixed);
					}
				} else {
					let checkScroll = isDesktop() ? (
						$(window).scrollTop() < 200 || $(window).scrollTop() > this.prevScroll
					) : (
						$(window).scrollTop() < this.$element.parent().offset().top
					);

					this.prevScroll = $(window).scrollTop(); 

					if (checkScroll) {
						this.$element.css({
							position: 'relative'
						});

						this.isFixing = false;
						this.$element.removeClass('is-fixed');
						this.$element.trigger(Events.unfixed);
					}
				}
			}

			fixing();
			$(window).scroll(fixing);
		}
	}

	return Compact;
})($);

export default Compact;
