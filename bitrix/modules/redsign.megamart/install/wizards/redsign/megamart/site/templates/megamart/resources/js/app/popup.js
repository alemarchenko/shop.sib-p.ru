import _parseOptions from '../utils/parseOptions';
import _merge from 'lodash/merge';
import $ from 'jquery';

export const windowPopupOptions = {
	infobar: false,
	buttons: false,
	slideShow: false,
	fullScreen: false,
	animationEffect: "slide-down-in",
	animationDuration: 300,
	thumbs: false,
	//modal: true,
	// hideScrollbar: false,
	ajax: {
		settings: {
			cache: true,
			data: {
				cache: true,
				fancybox: true
			}
		}
	},
	touch: false,
	keyboard: true,
	btnTpl: {
		smallBtn: ''
	},
	baseTpl: '<div class="fancybox-container popup-form" role="dialog" tabindex="-1">' +
		'<div class="fancybox-bg"></div>' +
		'<div class="fancybox-inner">' +
		'<div class="fancybox-stage"></div>' +
		'</div>' +
		'</div>',
	beforeLoad: function (instance, slide, b) {

		$('.l-page').addClass('filter-blur');

		if (RS.Panel && RS.Panel.openned)
		{
			// RS.Panel.close();
		}
	},
	afterLoad: function(instance, slide) {

		var obContent = slide.$content.get(0),
			data = BX.parseJSON(obContent.innerHTML);

		if (data)
		{
			var pageAssets = BX.processHTML(data.JS);
			
			if (pageAssets.STYLE.length > 0)
			{
				BX.loadCSS(pageAssets.STYLE);
			}

			if (pageAssets.SCRIPT)
			{
				var processed = BX.processHTML(data.DATA, false);

				obContent.innerHTML = processed.HTML;

				BX.ajax.processScripts(
					pageAssets.SCRIPT,
					false,
					BX.proxy(function(){
						BX.ajax.processScripts(processed.SCRIPT);
					}, this)
				);
			}

		}
		
		if (RS.Init)
		{
			RS.Init(['bmd', 'popup', 'nav-slide', 'scrollbar'], this.$content);
		}

		this.$content.wrapAll('<div>');

		var $wrapper = this.$content.parent();
		$wrapper.prepend('<button data-fancybox-close class="fancybox-close-small"><svg class="icon-svg text-secondary"><use xlink:href="#svg-close"></use></svg></button>');

		if (slide.opts.$orig.data('fancybox-title') !== false)
		{
			var title = !!slide.opts.title && slide.opts.title.length ?
				slide.opts.title :
				!!instance.opts.title && instance.opts.title.length ?
				instance.opts.title :
				!!slide.opts.$orig ?
				slide.opts.$orig.data('fancybox-title') || slide.opts.$orig.attr('title') || this.opts.$orig.text() :
				undefined;

			if (title !== undefined) {
				this.$content.parent().prepend('<div class="fancybox-title fancybox-title-inside-wrap">' + title + '</div>');
			}
		}
	},

	afterShow: function(instance, slide) {
	},

	beforeClose: function () {
		$('.l-page').removeClass('filter-blur');
	},

	afterClose: function (instance) { 
		setTimeout(() => {
			$('.js-fix-scroll').removeClass('js-fix-scroll--fixed');
		});
	}
}

const fullscreenPopupOptions = _merge({}, windowPopupOptions, {
	slideClass: "fullscreen",
	animationEffect: 'zoom-in-out',

	spinnerTpl: '<div><div class="fancybox-loading"></div></div>',

	ajax: {
		settings: {
			cache: true,
			data: {
				cache: true,
				fancybox: true,
				fancyboxFullscreen: true
			}
		}
	},

	afterLoad: function(instance, slide) {
		this.$content.wrapAll('<div>');

		var $wrapper = this.$content.parent();
		$wrapper.prepend('<button data-fancybox-close class="fancybox-close-small"><svg class="icon-svg text-secondary"><use xlink:href="#svg-close"></use></svg></button>');
	}
});

export function init(context) {

	$(context).find('[data-type="ajax"],[data-fancybox][data-type="inline"]').each(function() {
		
		if (this.dataset.popupInited == 'true')
		{
			return;
		}

		let options = _parseOptions(this.getAttribute('data-popup-options'));
		let popupType = (RS.Options || {}).defaultPopupType;


		if (this.getAttribute('data-popup-type')) {
			popupType = this.getAttribute('data-popup-type')
		}

		const openPanel = (link, type) => {

			const activeItem = () => {
				link.classList.add('is-active');

				$(document).one('panel.closed', () => {
					link.classList.remove('is-active');
				});
			};

			if (!link.classList.contains('is-active'))
			{
				if (RS.Panel.openned) {
					$(document).one('panel.before_open', () => {
						activeItem();
					});
				} else {
					activeItem();
				}

				RS.Panel.open(link, type)
					.then((content) => {
						if (RS.Init)
						{
							RS.Init(['bmd', 'popup'], content);
						}
					});

			} else {
				RS.Panel.close(link);
			}
		};

		switch (popupType) {

			case 'side':
				
				$(this).click((e) => {
					e.preventDefault();
					openPanel(this, 'right');
				});
				break;

			case 'fullscreen':
				options = _merge({}, fullscreenPopupOptions, options);
				$(this).fancybox(options);

				break;

			case 'bottom':
				$(this).click((e) => {
					e.preventDefault();
					openPanel(this, 'bottom');
				});
				break;

			case 'window':
			default:
				options = _merge({}, windowPopupOptions, options);
				$(this).fancybox(options);

				break;
		}

		this.dataset.popupInited = 'true';
	});
}

export default function (content = '', type = 'window', options = {}) {

	switch (type) {
		case 'window':
		default:
			options = _merge({}, windowPopupOptions, options);

			$.fancybox.open(content, options);

			break;
	}
}
