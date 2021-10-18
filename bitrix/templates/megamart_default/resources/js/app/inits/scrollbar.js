import $ from 'jquery';
import _parseOptions from '../../utils/parseOptions'
import assign from 'lodash/assign'


export default function (context) {
	const Default = {
	};
	$(context).find('[data-scrollbar]').each((key, node) => {

		let options = _parseOptions(node.getAttribute('data-slider-options'))
		
		options = assign({}, options);

		$(node).scrollbar(options);

	});
}
