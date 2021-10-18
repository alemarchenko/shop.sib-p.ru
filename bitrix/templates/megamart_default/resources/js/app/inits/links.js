import $ from 'jquery';

export default function (context) {

	$(context).find('.js-link-scroll[href*="#"]:not([href="#"])').each((key, node) => {
		node.addEventListener('click', function(e) {
			e.preventDefault();

			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname )
			{
				var hash = this.href.replace(/[^#]*(.*)/, '$1'),
					$target = $(this.hash);
						// element = document.getElementById(hash.slice(1));

				$target = $target.length ? $target : $('[name=' + this.hash.slice(1) +']');

				if ($target.length)
				{
					var t = Math.round($target.offset().top);

					if (RS.Options.fixingCompactHeader == true)
					{
						var compactHeader = document.querySelector(RS.Options.compactHeaderSelector);
						if (compactHeader != undefined)
						{
							t += (t < 0) ? -70 : -70;
						}
					}

					window.scroll({top: t, behavior: 'smooth'});
					window.history.replaceState('', document.title, window.location.href.replace(location.hash, '') + this.hash);

					$target.click();
				}
			}
		}, false);
	});

	$(context).find('.js-link-up').each((key, node) => {
		node.addEventListener('click', function(e) {
			e.preventDefault();

			window.scroll({top: 0, behavior: 'smooth'});
		});
	});
};
