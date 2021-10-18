import FloatButton from '../../modules/FloatButton'

export default function (context) {
	$(context).find('[data-float-button]').each((key, node) => {

		new FloatButton({
			button: this
		});

	});
}
