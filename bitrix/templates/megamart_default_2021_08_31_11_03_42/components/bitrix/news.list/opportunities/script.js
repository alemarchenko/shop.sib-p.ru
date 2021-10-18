if (!window.RSFilterAccordion) {
	window.RSFilterAccordion = (function () {

		var FilterAccordion = function (elementId, items) {
			var $element = $('#' + elementId)
			var $cards = $element.find('.card');

            var $accordion = $element.find('#' + elementId + '_accordion');

			$accordion.on('shown.bs.collapse', function (e) {
				var $target = $(e.target);
                var $active = $target.closest('.card');

				window.location.hash = $active.data('code');
            });
            
            $accordion.on('show.bs.collapse', function(e) {
                var $target = $(e.target);
                var $active = $target.closest('.card');
                
                var itemId = $active.data('item-id');

                if (items.hasOwnProperty(itemId))
                {
                    var $body = $target.find('[data-text]');
                    $body.html(items[itemId].description);
                }
            });

            var filters = {};

            $element.find('[data-filter]').each(function () {
                var filter = $(this).data('filter');

                filters[filter] = {
                    $link: $(this),
                    isHasNewItems: false
                };
            });

			$element.find('[data-filter]').on('click', function (e) {
				e.preventDefault();

				var filter = $(this).data('filter');

				$cards.show();

				if (filter) {
                    $cards.not('[data-type="' + filter  + '"]').hide();
				}
            });
            
            if (window.location.hash)
            {
                var $active = $accordion.find('[data-code="' + window.location.hash.substring(1) + '"]');

                if ($active.length)
                {
                    var $collapse = $active.find('.collapse');
                    $collapse.collapse('show');

                    $active.addClass('card-active');

                    $(document).ready(function () {
                        
                        if ('scrollRestoration' in history) {
                            history.scrollRestoration = 'manual';
                        }
                    
                        $(document).scrollTop($active.offset().top);
                    });
                }
            }


            var addDays = function (date, days) {
                var result = new Date(date);
                result.setDate(result.getDate() + days);
                return result;
            }

            if (items)
            {
                for (var i in items)
                {
                    if (!items.hasOwnProperty(i) || !items[i].activeFrom)
                    {
                        continue;
                    }

                    var activeFromDate = new Date(items[i].activeFrom * 1000);
                    var lastNewDate = addDays(activeFromDate, 30);
                    var currentDate = new Date();

                    if (currentDate.getTime() <= lastNewDate.getTime())
                    {
                        var $item = $accordion.find('[data-item-id="' + i + '"]');
                        $item.find('.card-header-link__title').append('<div class="c-sticker mb-0 ml-3" style="background-color: #59ba47;color: #59ba47;"><span class="c-sticker__name">' + BX.message('RS_NL_ITEM_NEW') + '</span></div>');
                
                        var filter = $item.data('type');
                        if (filters[filter])
                        {
                            filters[filter].isHasNewItems = true;
                        }
                    }
                }
            }

            for (var i in filters)
            {
                if (!filters.hasOwnProperty(i))
                {
                    continue;
                }

                if (filters[i].isHasNewItems)
                {
                    filters[i].$link.addClass('is-has-new');
                }
            }
        }

		return FilterAccordion;
	})();
}