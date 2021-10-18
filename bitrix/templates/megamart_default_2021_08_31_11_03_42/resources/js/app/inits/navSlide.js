export default function (context) {
	$(context).find('.nav-slide').each(function () {
		var $nav = $(this);
		var $line = $('<li>').addClass('nav-slide-line');
		var $currentActive = $nav.find('.active');
		var $items = $nav.find('.nav-item');

		var setActive = function ($item) {
			var $span = $item.children('span');

			$line.css({
				'width': $span.outerWidth(),
				'left': $span.position().left + $nav.scrollLeft()
			});
		}

		$nav.append($line);

		setActive($nav.find('.active'));

		$nav.find('.nav-link').on('mouseenter', function () {
			var $item = $(this);

			setActive($item);
		});

		$(this).on('mouseleave', function () {
			setActive($currentActive);
		});

		if ($nav.attr('role') == 'tablist') {
			$items.on('shown.bs.tab', function () {
				var $item = $(this).children('.nav-link');
				$currentActive = $item;

				setActive($currentActive);
			});
		} else {
			$items.find('.nav-link').on('click', function () {
				var $item = $(this);

				$currentActive.removeClass('active');
				$item.addClass('active')

				$currentActive = $item;
				setActive($currentActive);
			});
		}

	});
	
	$(context).find('.nav-wrap').each(function () {

		let $this = $(this),
				$nav = $this.children('.nav');
		
		let $navScroll = $('<div/>', {
					'class': 'nav-scroll scroll-element',
				})
				.html('<div class="scroll-arrow scroll-arrow_less"><svg class="icon-svg"><use xlink:href="#svg-arrow-left"></use></svg></div>\
					<div class="scroll-arrow scroll-arrow_more"><svg class="icon-svg"><use xlink:href="#svg-arrow-right"></use></svg></div>\
					<div class="scroll-element_outer">\
						<div class="scroll-element_size"></div>\
						<div class="scroll-element_track"></div>\
						<div class="scroll-bar"></div>\
				</div>').appendTo($this);

		$nav.scrollbar({
		  showArrows: true,
		  scrollx: $navScroll,
		  scrollStep: 200
		});
	});
}
