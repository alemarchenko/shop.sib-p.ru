BX.ready(function(){
	if (RS.Favorite)
	{
		BX.addCustomEvent(
			'change.rs_favorite',
			function()
			{

					$('.js-favorite-count').html(RS.Favorite.products.length);
			}
		);
	}
});