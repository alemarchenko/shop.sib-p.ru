import merge from 'lodash/merge'
import debounce from 'lodash/debounce'
import $ from 'jquery'
import ResizeSensor from '../../vendor/ResizeSensor'

import StickySidebar from 'sticky-sidebar'

const stickyDefaultOptions = {
	bottomSpacing: 0,
	topSpacing: 10,
	resizeSensor: false
	// containerSelector: '.l-main__container'
}

export default function (element, stickyOptions = {})
{
	var $lastChildrenItem, stickyOptions, sticky;

	$lastChildrenItem = $(element).children(':not(script,style,link,.resize-sensor):last');
	if ($lastChildrenItem.length)
	{
		$lastChildrenItem.attr('style', 'margin-bottom: 0 !important');
	}

	if (RS.Options.fixingCompactHeader)
	{
		stickyOptions.topSpacing = $('.js-compact-header').children(0).height() + 10;
	}

	stickyOptions = merge({}, stickyDefaultOptions, stickyOptions)
	sticky = new StickySidebar(element, stickyOptions);

	if (sticky)
	{
		let updateSticky = debounce(() => {
			sticky.updateSticky()
		}, 0);

		new ResizeSensor(sticky.container,  function () {
			updateSticky();
		});

		new ResizeSensor(sticky.sidebarInner,  function () {
			updateSticky();
		});

		// kostyl' dlya obnovleniya polozheniya sajdbara pri izmenenii vysoty rabochej oblasti
		new ResizeSensor(document.querySelector('.l-main__container'),  function () {
			sticky.direction = 'down';
			sticky.updateSticky();
		});
	}
}
