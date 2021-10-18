if (!window.RSFilterAccordion) {
	var RSFilterAccordion = (function () {

		var FilterAccordion = function (elementId) {
			var $element = $('#' + elementId)
			var $cards = $element.find('.card');

			var $accordion = $element.find('#' + elementId + '_accordion');

			$accordion.on('shown.bs.collapse', function () {
				var $active = $element.find('.collapse.in').closest('.panel');
				window.location.hash = $active.data('code');
			});

			$element.find('[data-filter]').on('click', function (e) {
				e.preventDefault();

				var filter = $(this).data('filter');

				$cards.show();

				if (filter) {
					$cards.not('[data-type="' + filter  + '"]').hide();
				}
			});
		}

		return FilterAccordion;
	})();
}
