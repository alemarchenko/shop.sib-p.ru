(function(window){
	'use strict';

	if (window.JCGrupperList)
		return;

	window.JCGrupperList = function(arParams)
	{
		this.params = {};
		this.errorCode = 0;
		
		this.visual = {};
		this.scrollspy = null;
		this.scrollSpyOptions = {};

		if (typeof arParams === 'object')
		{
			this.params = arParams;
			this.initConfig();
		}
		else
		{
			this.errorCode = -1;
		}
		
		if (this.errorCode === 0)
		{
			BX.ready(BX.delegate(this.init, this));
		}
	};
	
	JCGrupperList.ScrollSpyDefault = {
		items: 3,
	};

	window.JCGrupperList.prototype = {
		initConfig: function()
		{
			if (!this.params.VISUAL || typeof this.params.VISUAL !== 'object')
			{
				this.errorCode = -1;
				return;
			}
			
			if (!this.params.VISUAL.NAV)
			{
				this.errorCode = -1;
				return;
			}

			this.visual = this.params.VISUAL;
			
			this.scrollSpyOptions.target = '#'+this.params.VISUAL.NAV;

			if (RS.Options.fixingCompactHeader == true)
			{
				this.scrollSpyOptions.offset = 71;
			}
		},
		
		init: function()
		{
			this.obNavigation = BX(this.visual.NAV);
			if (!this.obNavigation)
			{
				this.errorCode = -1;
			}
			
			if (this.errorCode === 0)
			{	
				if (this.obNavigation)
				{
					this.scrollspy = new $.fn.scrollspy.Constructor(
						document.body,
						this.scrollSpyOptions
					);
					
					$(window).on('scroll.bs.scrollspy', BX.proxy(function (event, params) {
						var $target = $(this.scrollSpyOptions.target);	
						if ($target.find('.nav-link.active, .list-group-item.active').length > 0)
						{
							$target.addClass('active');
						}
						else
						{
							$target.removeClass('active');
						}
					}, this));
				}
			}
		},

	}
})(window);