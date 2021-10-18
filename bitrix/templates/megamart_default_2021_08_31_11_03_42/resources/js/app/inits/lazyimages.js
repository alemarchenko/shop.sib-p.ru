export default function (context) {
	$(context).find('[data-lazy-img]').Lazy({
		effect: "fadeIn",
		effectTime: 300,

		afterLoad: ($item) => {
			$item
				.removeClass('lazy-anim-bg lazy-anim-img')
				.removeAttr('data-lazy-img')
		}
	});
}
