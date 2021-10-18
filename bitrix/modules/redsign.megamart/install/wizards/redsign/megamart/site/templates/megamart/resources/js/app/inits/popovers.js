import _parseOptions from '../../utils/parseOptions'
import Popover from '../../modules/popovers'

export default function (context) {
	var popovers = {};
	$(context).find('[data-popover]').each((key, node) => {
		var name = node.getAttribute('data-popover');
		let options = _parseOptions(node.getAttribute('data-popover-options'))

		if (!name) {
			name = 'popover_' + Object.keys(app.popovers).length + 1
		}
		popovers[name] = new Popover(node, name, options);
	});
}
