import $ from 'jquery';

function onReady()
{
	// prevent sidebar menu item click event
	$(document)
		.on('click', '.js-smenu-item__toggle', (e) =>
		{
			e.preventDefault();
		});

	// reload page after city change
	BX.addCustomEvent('rs.location_change', (result) =>
	{
		if (result.redirect != undefined)
		{
			window.location.href = result.redirect;
		}
		else
		{
			BX.reload();
		}
	}); 

	// Fix work of fancybox
	var scrollbarWidth = (window.innerWidth - document.documentElement.clientWidth);
	$(document)
		.on('beforeLoad.fb', function(e, instance, slide)
		{

			$(`.side-panel-controls, .side-panel__inner`)
				.css('margin-right', scrollbarWidth);

			$(`.bottom-panel__container`)
				.css('overflow-y', 'scroll');

			$('.js-fix-scroll')
				.addClass('js-fix-scroll--fixed')
				.css('padding-right', scrollbarWidth);
			
		});

	$(document)
		.on('afterClose.fb', function(e, instance, slide)
		{
			if (!$.fancybox.getInstance())
			{
				$(`.side-panel-controls, .side-panel__inner, .bottom-panel__inner`)
					.css('margin-right', 0);

				$(`.bottom-panel__container`)
					.css('overflow-y', 'visible');

				$('.js-fix-scroll')
					.removeClass('js-fix-scroll--fixed')
					.css('padding-right', 0);
			}
		});


	// accordions fix
	$(document)
		.on('show.bs.collapse', '.collapse', function()
		{
			var $card = $(this)
				.closest('.card');

			$card.addClass('card-active');
		});

	$(document)
		.on('hidden.bs.collapse', '.collapse', function()
		{
			var $card = $(this)
				.closest('.card');

			$card.removeClass('card-active');
		});

	// Update captcha code
	$(document)
		.on('click', '[data-captcha-update-code]', function(e)
		{
			e.preventDefault();

			var $el = $(this);
			var $form = $el.closest('form');
			if (!$form.length)
			{
				retirn;
			}
			$.getJSON(RS.Options.siteDir + 'ajax/captcha.php', function(res)
			{
				var $img = $form.find('img[src*="/bitrix/tools/captcha.php"]')
				$img.attr('src', res.src);

				var $captchaSid = $form.find('input[name="captcha_sid"]');
				$captchaSid.val(res.code);
			})
		});

}

$(window)
	.ready(onReady);