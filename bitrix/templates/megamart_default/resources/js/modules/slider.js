import assign from 'lodash/assign'

const Slider = ($ => {
	const Default = {
		items: 4,
		margin: 30,
		navText: [
			'<svg class="icon-svg"><use xlink:href="#svg-arrow-left"></use></svg>',
			'<svg class="icon-svg"><use xlink:href="#svg-arrow-right"></use></svg>'
		],
		responsive: {
			0:		{items: 1},
			380:	{items: 2},
			576:	{items: 2},
			768:	{items: 2},
			992:	{items: 3},
			1200:	{items: 4}
		}
	};

	class Slider {
		constructor(element, name, config) {
			this.element = element;
			this.name = name;
			this.$element = $(this.element);
			this.config = assign({}, Default, config);
			this.instance = undefined;


			this.prepare();
			this.findDotsContainer();
			this.findNavContainer();

			if (this.config.nav) {
				this.createMobileNav();
			}

			this.initSlider();
		}

		prepare() {

			// remove show classes
			this.$element.removeClass((index, className) => {
				return (className.match (/(^|\s)show-items-\S+/g) || []).join(' ');
			});

			// add owl-carousel container
			this.$element.addClass('owl-carousel');

			// Clear Grid
			if (this.$element.hasClass('row')) {
				this.$element.removeClass('row');
				this.$element.children('[class*=col]').removeClass((index, className) => {
					return (className.match (/(^|\s)col-\S+/g) || []).join(' ');
				});
			}
		}

		findDotsContainer() {
			const $container = $('[data-slider-dots=' + this.name + ']');

			if ($container.length) {
				let dotsId = 'slider-dots-' + this.name;

				$container
					.addClass('slider-dots')
					.attr('id', dotsId);

				this.config.dots = true;
				this.config.dotsContainer = '#' + dotsId;
			} else {
				this.config.dots = false;
			}
		}

		findNavContainer() {
			const $container = $('[data-slider-nav=' + this.name + ']');

			if ($container.length) {
				let navId = 'slider-nav-' + this.name;

				$container
					.addClass('slider-nav')
					.attr('id', navId);

				this.config.nav = true;
				this.config.navContainer = '#' + navId;
			} else {
				this.config.nav = false;
			}
		}

		createMobileNav() {
			let $prevButton = $('<button>').addClass('owl-prev').html(this.config.navText[0]),
				$nextButton = $('<button>').addClass('owl-next').html(this.config.navText[1]),
				$container = $('[data-slider-nav-sm=' + this.name + ']');


			if (!$container.length) {
				$container  = $('<div>').addClass('slider-nav-sm');
				this.$element.after($container);
			}

			$container.append($prevButton, $nextButton);

			$prevButton.on('click', () => {
				this.$element.trigger('prev.owl.carousel');
			});

			$nextButton.on('click', () => {
				this.$element.trigger('next.owl.carousel');
			});
		}

		initSlider() {

			this.config.onTranslated = () => {
		        var lazyloadInstance = $('.lazyload').data("plugin_lazy");
		        if (lazyloadInstance) {
		            lazyloadInstance.update();
		        }
		    }

			this.instance = this.$element.owlCarousel(this.config);
		}
	}

	return Slider;
})(jQuery);

export default Slider;
