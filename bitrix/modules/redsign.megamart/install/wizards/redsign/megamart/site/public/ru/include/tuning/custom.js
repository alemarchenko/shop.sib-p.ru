$(document).ready(function() {

    document.addEventListener('rs.tuning.onBeforeGetReadyMacros', function(e) {

        var macrosList = e.detail.macrosList;
            color11 = macrosList['COLOR_1_1'],
            rsColor11 = new RS.Color(color11),
            color12 = macrosList['COLOR_1_2'],
            rsColor12 = new RS.Color(color12);
        
        rsTuning.setMacros('COLOR_1_1_DARKEN_7_5_PERSENT', rsColor11.darken(7.5).getHex());
        rsTuning.setMacros('COLOR_1_1_DARKEN_10_PERSENT', rsColor11.darken(10).getHex());
        rsTuning.setMacros('COLOR_1_1_LIGHTEN_15_PERSENT', rsColor11.lighten(15).getHex());
        rsTuning.setMacros('COLOR_1_1_LIGHTEN_25_PERSENT', rsColor11.lighten(25).getHex());
        rsTuning.setMacros('COLOR_1_1_LIGHTEN_25_OPACITY', rsColor11.getRgba(0.25));
        
    });

    document.addEventListener('rs.tuning.option.dd.onEnd', function(e) {
		var sortingBlocks = e.detail.value;
		var indexPage = document.querySelector('[data-entity="index-page"]');
		
		if (!sortingBlocks || !indexPage) 
		{
			return;
		}
		
		for (var i = 0; i < sortingBlocks.length; i++) 
		{
			var block = document.querySelector('[data-entity="index-page-block"][data-block-name="' + sortingBlocks[i] + '"]');
			
			if (block)
			{				
				indexPage.appendChild(block);
			}
		}
	
		
    });

});
