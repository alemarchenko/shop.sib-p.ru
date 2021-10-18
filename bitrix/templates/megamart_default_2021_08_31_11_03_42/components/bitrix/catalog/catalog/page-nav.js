(function() {
	'use strict';

	if (!!window.JCPageNavComponent)
		return;

	window.JCPageNavComponent = function(params) {

		this.id = params.ID || '';
		this.target = '#'+this.id;
		this.scrollSpyOptions = {};

		this.scrollSpyOptions.target = this.target;

		if (window.frameCacheVars !== undefined)
		{
			BX.addCustomEvent("onFrameDataReceived" , BX.delegate(this.init, this));
		}
		else
		{
			BX.ready(BX.delegate(this.init, this));
		}
	};

	window.JCPageNavComponent.prototype =
	{
		init: function(event)
		{
			var i;

			this.nav = BX(this.id);

			this.items = document.querySelectorAll('[data-spy="item"][data-target="'+this.target+'"]');
			this.navItems = [];

			for (i in this.items)
			{
				if (this.items.hasOwnProperty(i))
				{
					var newItem = this.insertItem({
						id: this.items[i].getAttribute('id'),
						title: this.items[i].getAttribute('data-title'),
					});

					this.navItems.push(newItem);
				}
			}

			if (RS.Options.fixingCompactHeader == true)
			{
				this.scrollSpyOptions.offset = 71;
			}
			new $.fn.scrollspy.Constructor(
				document.body,
				this.scrollSpyOptions
			);

			$(window).on('scroll.bs.scrollspy', BX.proxy(this.onScroll, this));

			BX.data(this.nav, 'pageNav', this)
		},

		addItem: function(item)
		{
			var i;

			for (i = 0; i < this.items.length; i++)
			{
				if (this.items.hasOwnProperty(i))
				{
					if (this.items[i].getAttribute('id') == item.id)
					{
						return false;
					}
				}
			}
			
			var items = document.querySelectorAll('[data-target="'+this.target+'"]');

			for (i in items)
			{
				if (items.hasOwnProperty(i))
				{
					if (items[i].getAttribute('id') == item.id)
					{
						var newItem = this.createItem({
							id: item.id,
							title: item.title,
						});

						if (this.navItems[i].nextSibling == null)
						{
							this.navItems[i].parentNode.insertBefore(newItem, this.navItems[i]);
						}
						else
						{
							this.navItems[i].parentNode.insertBefore(newItem, this.navItems[i].nextSibling);
						}
						
						newItem.style.height = "auto";
						newItem.style.overflow = "hidden";

						var obj_children_height = newItem.offsetHeight;

						newItem.style.height = 0;
		
						new BX.easing({
							duration: 500,
							start: {opacity: 0, height: 0},
							finish: {opacity: 100, height: obj_children_height},
							transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
							step: function(state){
								newItem.style.opacity = state.opacity / 100;
								newItem.style.height = state.height + 'px';
							},
							complete: function(){
								newItem.removeAttribute('style');
							}
						}).animate();
						
						this.items = items;
						this.navItems.splice(i, 0 , newItem);
					}
				}
			}
		},

		removeItem: function(id)
		{
			BX.remove(this.nav.querySelector('[href="#'+id+'"]'));
		},

		insertItem: function(item)
		{
			return this.nav.appendChild(
				this.createItem(item)
			);
		},

		createItem: function(item)
		{
			return BX.create('DIV', {
				props: {
					className: 'nav-item'
				},
				children: [
					BX.create('A', {
						props: {
							href: '#'+item.id,
							className: 'nav-link js-link-scroll'
						},
						children: [
							BX.create('SPAN', {
								props: {
									className: 'text-truncate'
								},
								text: item.title,
							}),
							'<svg class="nav-link-icon icon-svg"><use xlink:href="#svg-arrow-right"></use></svg>'
						]
					})
				]
			});
		},

		onScroll: function(event)
		{

			var $target = $(this.nav);

			if ($target.find('.nav-link.active, .list-group-item.active').length > 0)
			{
				$target.addClass('active');
			}
			else
			{
				$target.removeClass('active');
			}
		},
	};

})();
