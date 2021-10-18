import './vendor/velocity';
import 'velocity-animate';

// import svg4everybody from 'svg4everybody';

BX.ready(function(){
	window.RS = window.RS || {};
	
	window.RS.Options = {
		compactHeaderSelector: '.js-compact-header',
		fixingCompactHeader: true,
	};

	//bitrix wtf
	let forms = document.querySelectorAll('.landing-block-node-form');
	for (let i in forms)
	{
		if (forms.hasOwnProperty(i))
		{
			let blockId = forms[i].closest('.block-wrapper').getAttribute('id');
			let formId = forms[i].getAttribute('id');

			let newForm = document.createElement('form');
			newForm.setAttribute('id', formId ? formId : blockId + '_from');
			newForm.setAttribute('class', forms[i].getAttribute('class'));
			newForm.setAttribute('action', forms[i].getAttribute('action'));
			newForm.innerHTML = forms[i].innerHTML;

			forms[i].parentNode.replaceChild(newForm, forms[i]);
		}
	}

	BX.ready(function () {
		$(document).on('mouseenter', '.product-item-image-wrapper .product-item-image-slider-control', function () {
	
			var $this = $(this),
				index = this.getAttribute('data-go-to'),
				$parent = $this.closest('.product-item-image-wrapper'),
				$slides = $parent.find('.product-item-image-slide');
	
			$slides.each(function(index, item){
				item.style.transition = 'none';
			}).removeClass('active');
			
			$(this).addClass('active').siblings().removeClass('active');
	
			$slides.eq(index).addClass('active');
	
			$slides.each(function(index, item){
				item.style.transition = '';
			});
		})
	});

	// svg4everybody({
	// 	nosvg: true, // shiv <svg> and <use> elements and use image fallbacks
	// 	polyfill: true // polyfill <use> elements for External Content
	// });
 });