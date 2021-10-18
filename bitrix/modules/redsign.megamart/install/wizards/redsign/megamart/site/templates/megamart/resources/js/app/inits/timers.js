import _parseOptions from '../../utils/parseOptions'
import Timer from '../../modules/timer'

export default function (context, options) {
	var timers = {};
	$(context).find('[data-timer]').each((key, node) => {
		var name = node.getAttribute('data-timer');
		
		if ($.isEmptyObject(options))
		{
			options = _parseOptions(node.getAttribute('data-options'));
		}

		if (!name) {
			name = 'timer_' + Object.keys(app.timers).length + 1
		}
		
		let $node =$(node),
				data = $node.data('redsign.timer');

		if (!data)
		{
			timers[name] = new Timer(node, name, options);
			$node.data('redsign.timer', timers[name]);
		}
		else
		{
			data.setup(options);
		}
	});
}
