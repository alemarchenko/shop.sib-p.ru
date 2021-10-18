import $ from 'jquery'

import lazyImages from './inits/lazyimages'
import sidebar from './inits/sidebar'
import sliders from './inits/sliders'
import popovers from './inits/popovers'
import timers from './inits/timers'
import dlMenu from './inits/dlMenu'
import effects from './inits/effects'
import links from './inits/links'
import navSlide from './inits/navSlide'
import upButton from './inits/upButton'
import scrollbar from './inits/scrollbar'
import smoothscroll from 'smoothscroll-polyfill';

import { init as compact } from './compact'
import { init as popup } from './popup'

const AllModules = [
	'bmd',
	'sidebar',
	'sliders',
	'popovers',
	// 'timers',
	'popup',
	'lazy-images',
	'compact-header',
	'dl-menu',
	'effects',
	'links',
	'nav-slide',
	'upbutton'
];

export default function(modules = AllModules, context = document.body, options ={}) {

	modules.forEach(module => {
		switch (module) {
			case 'bmd':
				$(context).bootstrapMaterialDesign();
				break;

			case 'sidebar':
				sidebar(context);
				break;

			case 'sliders':
				sliders(context);
				break;

			case 'popovers':
				popovers(context);
				break;

			case 'timers':
				timers(context, options);
				break;

			case 'popup':
				popup(context);
				break;

			case 'lazy-images':
				lazyImages(context);
				break;

			case 'compact-header':
				compact();
				break;

			case 'dl-menu':
				dlMenu();
				break;

			case 'links':
				smoothscroll.polyfill();
				links(context);
				break;

			case 'effects':
				effects();
				break;

			case 'nav-slide':
				navSlide(context);
				break;

			case 'upbutton':
				upButton(context);
				break;

			case 'scrollbar':
				scrollbar(context);
				break;
		}
	})
}
