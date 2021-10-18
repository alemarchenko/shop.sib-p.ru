import $ from 'jquery'
import assign from 'lodash/assign'

const Popover = ($ => {
	const Default = {
		delay: { show: 300, hide: 600 },
		trigger: 'focus',
		template: '<div class="popover" role="tooltip">' +
		'<svg class="popover-close icon-svg"><use xlink:href="#svg-close"></use></svg>' +
		'<div class="arrow"></div>' +
		'<h3 class="popover-header"></h3>' +
		'<div class="popover-body"></div></div>',
		sanitize: false, // svg close icon
	};

	class Popover {
		constructor(element, name, config) {
			this.element = element;
			this.name = name;
			this.$element = $(this.element);
			this.config = assign({}, Default, config);
			this.instance = undefined;

			this.prepare();

			this.initPopover();
		}

		prepare() {
		}

		initPopover() {
			this.instance = this.$element.popover(this.config);

			this.$element.on('click', function(e){
				e.stopPropagation();
				return false;
			});

			this.$element.on('shown.bs.popover', function (e) {
				$(this).addClass('active');
			});

			this.$element.on('inserted.bs.popover', function (e) {

				let id = e.target.getAttribute('aria-describedby');

				$('#'+id).find('.popover-close').on('click', function(){
					this.$element.popover('hide');
				}.bind(this));

			}.bind(this));

			if (
				this.config.trigger == 'focus' ||
				this.config.trigger == 'click'
			)
			{
				this.$element.on('keyup', e => {
					if (e.key === 'Escape')
					{
						this.$element.popover('hide');
						this.$element.blur();
					}
				});
			}

			this.$element.on('hidden.bs.popover', function (e) {
				$(this).removeClass('active');
			});
		}
	}

	return Popover;
})(jQuery);

export default Popover;
