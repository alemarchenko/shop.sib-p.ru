import $ from 'jquery';
import {show as showOverlay, hide as hideOverlay} from '../../utils/overlay';

class PositionPanel {

	constructor(panel) {
		this.panel = panel;
		this.blocks = {};

		this.$preload = undefined;
	}

	createBlock(link = undefined, content = '') {
		const url = link.getAttribute('data-src') || link.href;

		const block = document.createElement('div');
		block.classList.add('panel-block');

		if (link.title || link.innerText) {
			const blockTitlte = document.createElement('div');
			blockTitlte.classList.add('panel-block__title');

			const title = link.title || link.innerText;
			blockTitlte.innerText = title;

			block.appendChild(blockTitlte);
		}

		const blockContent = document.createElement('div');
		blockContent.classList.add('panel-block__content');
		blockContent.innerHTML = content;

		block.appendChild(blockContent);

		this.blocks[url] = block;

		this.$inner.append(block);

		return block;
	}

	updateBlock(link = undefined, content = '') {
		const url = link.getAttribute('data-src') || link.href;

		const block = this.blocks[url];
		$(block).find('.panel-block__content').html(content);

		return block;
	}

	update(url = undefined) {
		const d = $.Deferred();

		if (!this.blocks[url]) {
			d.reject();
			return d.promise();
		}

		this.panel.load(url)
			.then((content) => {
				const block = this.blocks[url];
				$(block).find('.panel-block__content').html(content);

				d.resolve();
			});

		return d;
	}

	open(link = undefined) {
		const d = $.Deferred();
		const url = link.getAttribute('data-src') || link.href;

		const afterFn = () => {
			this.hidePreload();
			link.setAttribute('data-need-reload', 'N');
			d.resolve(this.blocks[url]);

			this.$panel.addClass('is-open');
		}

		if (this.blocks[url]) {

			const block = this.blocks[url];

			const needCache = link.getAttribute('data-need-cache') == 'Y'
			const needReload = link.getAttribute('data-need-reload') == 'Y';

			if (!needCache || (needCache && needReload)) {

				showOverlay().then(($overlay) => {
					this.showPreload($overlay);
					
					this.panel.load(url)
						.then((content) => {
							const block = this.updateBlock(link, content);
							return this.show(block);
						})
						.then(() => afterFn());
				});

			} else {

				showOverlay();
				this.show(block);
				afterFn();
			}

		} else {

			showOverlay().then(($overlay) => {
				this.showPreload($overlay);

				this.panel.load(url)
					.then((content) => {
						const block = this.createBlock(link, content);
						return this.show(block);
					})
					.then(() => afterFn());

				$overlay.on('click', () => this.panel.close());
			});
		}

		d.then(() => {
			$(document).on('click.outside', (e) => {
				// if (
				// 	$(e.target) != this.$inner &&
				// 	!!!$(e.target).closest(this.$panel).length &&
				// 	$(e.target).closest(document).length > 0
				// ) {
				// 	this.panel.close();
				// }
			});
		});


		return d.promise();
	}

	close() {
		$(document).off('click.outside');

		return $.when(
				this.hide(),
				this.hidePreload(),
				hideOverlay()
			)
			.then(() => {
				this.$panel.removeClass('is-open');

				return true;
			});
	}
}

export default PositionPanel;
