import $ from 'jquery';
import PositionPanel from './PositionPanel'

class RightPanel extends PositionPanel {

	get type() {
		return 'right';
	}

	get $panel() {
		return $('#side-panel');
	}

	get $inner() {
		if (!this.$innerObj) {
			this.$innerObj = this.$panel.find('#side-panel-inner');
		}

		return this.$innerObj;
	}

	get $container() {
		return this.$panel.find('#side-panel-container');
	}

	showPreload($container) {
		if ($container && $container.length) {

			this.$preload = $('<div>')
				.addClass('panel-loader')
				.append('<span></span><span></span><span></span><span></span>');

			$container.append(this.$preload);
		}
	}

	hidePreload() {
		if (this.$preload) {
			this.$preload.remove();
		}
	}

	getInnerWidth(block) {
		const $block = $(block)
		const $blockClone = $block.clone();

		let innerWidth;

		$blockClone.css({
			position: 'absolute',
			visibility: 'hidden',
			left: '-99999px',
			top: '-99999px',
			display: 'block'
		});


		$('body').append($blockClone);
		innerWidth = $blockClone.outerWidth() > 500 ? $blockClone.outerWidth() : 500;

		if (innerWidth > $(window).width()) {
			innerWidth = $(window).width() - 60;
		}

		return innerWidth;
	}

	show(block) {
		$(document).trigger('panel.show');

		const blockWidth = this.getInnerWidth(block);

		const AnimationComplete = () => {
			$(block).addClass('is-showed');

			$(document).trigger('panel.showed');

			setTimeout(() => {
				$(block).velocity({ opacity: 1 }, { duration: 300 });
			}, 100);
		}

		const $controls = $('.side-panel__controls');
		this.$panel.append($controls.clone());
		this.$inner.append($controls);

		this.$inner
			.css({
				width: blockWidth,
				right: -blockWidth,
			})
			.velocity({
				right: 0
			}, {
				duration: 300,
				easing: [.17, .67, .83, .67],
				complete: AnimationComplete
			});
	}

	hide() {
		const d = $.Deferred();

		const $inner = this.$inner;
		const blockWidth = $inner.outerWidth();

		$inner
			.velocity({
				right: -blockWidth
			}, {
				duration: 300,
				complete: () => {
					const $controls = this.$inner.find('.side-panel__controls');

					this.$panel.children('.side-panel__controls').remove();
					this.$panel.append($controls);

					$inner
						.children('.panel-block')
						.removeClass('is-showed')
						.css('opacity', 0);

					d.resolve();
				}
			});

		return d.promise();
	}
}

export default RightPanel;
