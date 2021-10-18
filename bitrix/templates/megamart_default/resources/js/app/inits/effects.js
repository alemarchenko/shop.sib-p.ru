export default function() {

	// c-icon-count siblings
	$(document).on('mouseenter', '.c-icon-count', function () {

		$(this).velocity({
			opacity: 1
		}, {
			duration: 200
		});

		$(this).siblings('.c-icon-count').velocity('stop').velocity({
			opacity: 0.6
		}, {
			duration: 200
		});

		$(this).one('mouseleave', () => {

			$(this).velocity({
				opacity: 1
			}, {
				duration: 200
			});

			$(this).siblings('.c-icon-count').velocity({
				opacity: 1
			}, {
				duration: 200
			});
		});
	});
}
