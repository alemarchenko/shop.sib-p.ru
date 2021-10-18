import CompactHeader from '../modules/compactHeader';
import $ from 'jquery'

var instance = null;

export function getInstance() {
	let selector = (RS.Options || {}).compactHeaderSelector;

	if (!instance)
	{
		if (selector)
		{
			let header = document.querySelector(selector);
			instance = new CompactHeader(header);
		}
	}
	else
	{
		instance.resolveSelectors();
	}

	return instance;
}

export function init() {
	const compactHeader = getInstance();

	// Fixing header
	if((RS.Options || {}).fixingCompactHeader) {
		compactHeader.fixing();
	}

	// Toggle compact menu
	$(document).on('click', '[data-compact-menu-toggle]', function(event) {
		event.preventDefault();

		compactHeader.toggleMenu(this);
	});

	// Mobile search
	$(document).on('click', '[data-compact-search-open]', function(event) {
		event.preventDefault();

		compactHeader.revealMobileSearch();
	});

	$(document).on('click', '[data-compact-search-close]', (event) => {
		event.preventDefault();

		compactHeader.concealMobileSearch();
	});

	// update menu indicator
	BX.addCustomEvent('GlobalStateChanged', () => {
		const el = compactHeader.$element;
		if (el.find('.c-icon-count.has-items').length)
			el.find('.hamburger').addClass('hamburger--has-indicator');
		else
			el.find('.hamburger').removeClass('hamburger--has-indicator');
	});

	// $(window).resize(() => {
	// 	if (compactHeader.$search.hasClass('js-is-open')) {
	// 		compactHeader.concealMobileSearch();
	// 	}
	// });
}
