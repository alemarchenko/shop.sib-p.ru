BX.ready(function() {
	if (RS.Favorite) {
		BX.addCustomEvent(
			'change.rs_favorite',
			function() {
				var blocks = document.querySelectorAll('.js-global-favorite');
				var count = RS.Favorite.products.length;

				for (var i = 0; i < blocks.length; i++) {
					var block = blocks[i];
					var countBlock = block.querySelector('.js-global-favorite__count');

					if (count > 0) {
						block.classList.add("has-items");
					}
					else
					{
						block.classList.remove("has-items");
					}

					if (countBlock) {
						countBlock.innerHTML = count;
					}
				}

				BX.onCustomEvent('GlobalStateChanged');
			}
		);
	}
});
