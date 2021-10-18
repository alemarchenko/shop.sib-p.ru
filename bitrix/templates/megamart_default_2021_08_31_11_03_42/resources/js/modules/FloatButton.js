import $ from 'jquery';
import _merge from 'lodash/merge';
import _debounce from 'lodash/debounce';
import isDesktop from '../utils/isDesktop';

const FloatButton = ($ => {

	const Default = {
		button: '[data-float-button]',

		buttonOffset: 32,

		showClass: 'showed',
		unfixedClass: 'unfixed'
	};

	class FloatButton
	{
		constructor(options)
		{
			this.options = _merge({}, Default, options);

			this.init();
		}

		init()
		{
			this.findDomElements();
			this.initEvents();
		}

		findDomElements()
		{
			this.$button = $(this.options.button);
			this.$parent = this.$button.parent();
		}

		initEvents()
		{
			const onScroll = () => this.onScroll();

			if (isDesktop())
			{
				$(window).scroll(onScroll);
			}

			$(window).resize(_debounce(() => {

				$(window).off('scroll', onScroll);

				if (isDesktop())
				{
					if (this.$button.hasClass(this.options.unfixedClass))
					{

						let buttonStyles = {
							position: 'fixed',
							top: 'auto'
						};

						this.$button
							.removeClass(this.options.unfixedClass)
							.css(buttonStyles)
					}

					$(window).scroll(onScroll);
				}
			}));
		}

		checkShow()
		{
			const scrollTop = $(window).scrollTop();
			const windowHeight = $(window).outerHeight();

			if (scrollTop > windowHeight)
			{
				if (!this.$button.hasClass(this.options.showClass))
				{
					this.$button.addClass(this.options.showClass);
				}

				return true;
			}
			else
			{
				if (this.$button.hasClass(this.options.showClass))
				{
					this.$button.removeClass(this.options.showClass);
				}

				return false;
			}
		}

		toggleFixing()
		{
			if (this.$button.hasClass(this.options.unfixedClass))
			{
				let scrollTop = $(window).scrollTop();
				let windowHeight = $(window).outerHeight();
				let parentOffset = this.$parent.offset().top;


				if (scrollTop + windowHeight < parentOffset)
				{
					let buttonStyles = {
						position: 'fixed',
						top: 'auto'
					};

					this.$button
						.removeClass(this.options.unfixedClass)
						.css(buttonStyles)
				}
			}
			else
			{
				let buttonOffset = this.$button.offset().top;
				let buttonHeight = this.$button.height();
				let parentOffset = this.$parent.offset().top;

				if (buttonOffset + buttonHeight + this.options.buttonOffset >= parentOffset)
				{
					let buttonStyles = {
						position: 'absolute',
						top: parentOffset - buttonHeight - this.options.buttonOffset,
					};

					this.$button
						.addClass(this.options.unfixedClass)
						.css(buttonStyles);
				}
			}
		}

		onScroll()
		{

			if (this.checkShow())
			{
				this.toggleFixing();
			}
		}
	}

	return FloatButton;
})($);

export default FloatButton;
