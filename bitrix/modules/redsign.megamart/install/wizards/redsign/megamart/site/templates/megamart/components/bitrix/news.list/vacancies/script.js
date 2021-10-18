$(document).ready(function () {
	var $vacancies = $('.js-vacancies');
	var $cards = $vacancies.find('.card');

	if (window.location.hash) {
	  $vacancies.find('.collapsed[href="#collapse_' + window.location.hash.split('#')[1] + '"]').click();
	}

	$('#vacancies_accordion').on('shown.bs.collapse', function () {
		var $active = $vacancies.find('.collapse.in').closest('.panel');
		window.location.hash = $active.data('code');
	});

	$vacancies.find('[data-filter]').on('click', function (e) {
		e.preventDefault();

		var filter = $(this).data('filter');

		$cards.show();

		if (filter) {
			$cards.not('[data-type="' + filter  + '"]').hide();
		}
	});
});
