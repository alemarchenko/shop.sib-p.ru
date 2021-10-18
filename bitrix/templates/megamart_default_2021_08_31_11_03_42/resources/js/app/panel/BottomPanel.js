import $ from 'jquery';
import PositionPanel from './PositionPanel';
import dragResize, { MIN_HEIGHT } from '../../utils/dragResize.js';

const STORAGE_KEY = 'bottom_panel_inner_height';

const getSavedHeight = function() {
	return parseInt(localStorage.getItem(STORAGE_KEY)) || MIN_HEIGHT ;
}
const saveHeight = function(height) {
	localStorage.setItem(STORAGE_KEY, height);
}

class BottomPanel extends PositionPanel {

	constructor(panel) {
		super(panel);

		const container = this.$container.get(0);
		const dragArea = $('#bottom-panel-drag-area').get(0);

		dragResize({
			dragArea: dragArea,
			container: container,
			onResize: (height) => this.onResizeContainer(height)
		});

		const $wrapEl = $('<div>').css('height', this.$panel.height());
		this.$panel.wrap($wrapEl);
	}

	get type() {
		return 'bottom';
	}

	get $panel() {
		return $('#bottom-panel');
	}

	get $inner() {
		if (!this.$innerObj) {
			this.$innerObj = this.$panel.find('#bottom-panel-inner');
		}

		return this.$innerObj;
	}

	get $container() {
		return this.$panel.find('#bottom-panel-container');
	}

	onResizeContainer(height) {
		this.$container.css('height', height);
		saveHeight(height);
	}

	open(link = undefined) {

		this.$panel.addClass('is-open');

		this.$container
			.css({
				height: getSavedHeight(),
				bottom: -getSavedHeight(),
			})
			.velocity({
				bottom: 0
			}, {
				duration: 300
			});

		return super.open(link);
	}

	close () {

		const d = $.Deferred();

		this.$container
			.velocity({
				bottom: -getSavedHeight()
			}, {
				duration: 300,
				complete: () => {
					this.$panel.removeClass('is-open');

					this.$container
						.css({
							height: 0,
							bottom: 0
						})

					d.resolve();
				}
			});

		return d.promise().pipe(() => {
			return super.close()
		});
	}

	showPreload($container) {
		this.$preload = $('<div>')
			.addClass('panel-loader')
			.append('<span></span><span></span><span></span><span></span>');

		this.$inner.append(this.$preload);
	}

	hidePreload() {
		if (this.$preload) {
			this.$preload.remove();
		}
	}

	show(block) {
		$(block).addClass('is-showed');

		$(block).addClass('is-showed');
		$(document).trigger('panel.showed');
		$(block).velocity({ opacity: 1 }, { duration: 300 });
	}

	hide() {

		this.$inner
			.children('.panel-block')
			.removeClass('is-showed')
			.css('opacity', 0);

		return true;
	}
}

export default BottomPanel;
