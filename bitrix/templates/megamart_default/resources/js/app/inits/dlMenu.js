import DLMenu from '../../modules/dlMenu'

export default function () {

	new DLMenu($('.b-dl-menu'), {
		selectors: {
			parent: '.l-compact-menu'
		}
	});

}
