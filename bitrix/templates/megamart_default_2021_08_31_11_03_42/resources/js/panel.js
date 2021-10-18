import $ from 'jquery'
import {show as showOverlay, hide as hideOverlay} from './utils/overlay'
import RightPanel from './app/panel/RightPanel'
import BottomPanel from './app/panel/BottomPanel'

const ESCAPE_KEY_CODE = 27;

const Panel = ($ => {

	class Panel {

		static get Defaults() {
			return {
				classes: {}
			};
		}

		constructor(options) {
			this.options = $.extend({}, Panel.Defaults, options);

			this.openned = null;

			this.initPanels();

			this.isFancyboxOpened = false;
			$(document).on('beforeLoad.fb', () => this.isFancyboxOpened = true);
			$(document).on('afterClose.fb', () => this.isFancyboxOpened = false);

			$(document).keyup((e) => {
				if (!this.isFancyboxOpened && e.keyCode === ESCAPE_KEY_CODE) {
					this.close();
				}
			});

			$(document).on('click', '[data-panel-close]', () => this.close());
		}

		initPanels() {
			this.panels = {};

			this.panels['right'] = new RightPanel(this);
			this.panels['bottom'] = new BottomPanel(this);
		}

		load(url) {
			const d = $.Deferred();

			$(document).trigger('panel.before_load', [url]);
			$.ajax({
				url: url
			}).then((result) => {

				if (!this.openned) {
					d.reject();
				}

				var resultJson = BX.parseJSON(result);

				if (resultJson) {

					if (resultJson.STYLES)
					{
						this.processStyles(resultJson.STYLES);
					}

					if (resultJson.SCRIPTS) {
						this.processScripts(resultJson.SCRIPTS, () => {
							d.resolve(resultJson.HTML);
						});
					} else {
						d.resolve(resultJson.HTML);
					}
				} else {
					d.resolve(result);
				}
			});

			return d.promise();
		}

		reload(url) {

			for (let i in this.panels) {

				if (!this.panels.hasOwnProperty(i)) {
					continue;
				}

				let panel = this.panels[i];

				if(panel && panel.blocks[url]) {
					panel.update(url);
				}
			}

		}

		processStyles(html = '', sucessFn = () => {}) {
			const processed = BX.processHTML(html, false);
			BX.loadCSS(processed.STYLE);
		}

		processScripts(html = '', successFn = () => {}) {
			const processed = BX.processHTML(html, false);
			
			BX.ajax.processScripts(processed.SCRIPT, false, () => successFn());
		}


		open(link = undefined, position = 'right') {

			const panel = this.panels[position];

			if (!panel || !link) {
				return;
			}

			if (this.openned) {
				return this.close().then(() => this.open(link, position));
			}

			this.openned = panel;

			$(document).trigger('panel.before_open');

			return panel.open(link);
		}

		close() {

			if (this.openned) {
				$(document).trigger('panel.before_close');

				return this.openned.close().then(() => {
					$(document).trigger('panel.closed');

					this.openned = null;

					return true;
				});
			}

			return $.Deferred().promise();
		}
	}

	return Panel;
})(jQuery);

export default Panel;
