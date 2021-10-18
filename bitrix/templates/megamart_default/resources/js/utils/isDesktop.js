import $ from 'jquery'

export default function (options) {
	return $(window).innerWidth() >= 768;
}
