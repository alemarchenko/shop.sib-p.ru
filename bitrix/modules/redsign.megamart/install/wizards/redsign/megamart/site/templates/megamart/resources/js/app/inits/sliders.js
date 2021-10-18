import _parseOptions from '../../utils/parseOptions'
import Slider from '../../modules/slider'

export default function (context) {
	var sliders = {};
	$(context).find('[data-slider]').each((key, node) => {
		var name = node.getAttribute('data-slider');
		let options = _parseOptions(node.getAttribute('data-slider-options'))

		if (!name) {
			name = 'slider_' + Object.keys(app.sliders).length + 1
		}
		sliders[name] = new Slider(node, name, options);
	});
}
