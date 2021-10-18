import _parseOptions from '../../utils/parseOptions'
import stickySidebar from '../../modules/stickySidebar'

export default function (context) {
	$(context).find('[data-sticky-sidebar]').each((key, node) => {
		let options = _parseOptions(node.getAttribute('data-sticky-sidebar'));
		stickySidebar(node, options);
	});

	// stickySidebar(document.querySelector('.l-main__inner-content'));
}
