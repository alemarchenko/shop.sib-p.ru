BX.namespace("BX.Iblock.Catalog");

BX.Iblock.Catalog.CompareClass = (function()
{
	var CompareClass = function(arParams)
	{
		this.errorCode = 0;
		this.obCompare = null;
		this.node = {};
		this.config = {
			name: 'CATALOG_COMPARE_LIST',
			iblockId: null,
		};

		this.items = [];
		this.obItems = [];
		this.obScrolls = [];
		this.obTables = [];

		this.obScrollbarParams = {};
		this.obScrollActive = false;
		this.scrollTimer;
		
		this.hoverStateChangeForbidden = false;
		
		if (typeof arParams === 'object')
		{
			this.visual = arParams.VISUAL;

			this.config.name = arParams.CONFIG.NAME;
			this.config.iblockId = arParams.CONFIG.IBLOCK_ID;
			this.ajaxUrl = arParams.CONFIG.TEMPLATE_FOLDER + '/ajax.php';
			this.iMinColumsCount = parseInt(arParams.CONFIG.MIN_COLUMNS_COUNT, 10);

			this.items = arParams.ITEMS;

			this.useFavorite = arParams.USE_FAVORITE && RS.Favorite !== undefined;
		}

		this.isTouchDevice = BX.hasClass(document.documentElement, 'bx-touch');

		this.breakpoints = {
			xxs: 0,
			xs: 380,
			sm: 576,
			md: 768,
			lg: 992,
			xl: 1200,
		};

		if (this.errorCode === 0)
		{
			BX.ready(BX.delegate(this.init, this));
		}
		
		this.obCompare = BX(this.visual.ID);
		
		BX.addCustomEvent(window, "OnCompareSort", BX.proxy(this.compareSort, this));
	};

	CompareClass.prototype.MakeAjaxAction = function(url, event)
	{
		this.showWait();
		BX.ajax.post(
			url,
			{
				ajax_action: 'Y',
				ajax_id: this.visual.ID
			},

			BX.proxy(this.reloadResult, this)
		);

		if (event)
		{
			BX.PreventDefault(event);
		}
	};

	CompareClass.prototype.reloadResult = function(result)
	{
		this.obCompare.innerHTML = result;
		this.init();
		this.closeWait();
		
		BX.onCustomEvent('OnCompareChange');
	};

	CompareClass.prototype.init = function()
	{
		var i, j, k;

		this.obTable = BX(this.visual.TABLE);

		if (!this.obCompare)
		{
			this.errorCode = -1;
		}

		this.showWait();

		this.node.scrollItems = this.obCompare.querySelector('[data-entity="scroll-items"]');
		this.node.scrollItemsTop = this.obCompare.querySelector('[data-entity="scroll-items-top"]');
		this.node.topPanel = this.obCompare.querySelector('[data-entity="top-panel"]');
		this.node.scrollProps = this.obCompare.querySelector('[data-entity="scroll-props"]');

		if (this.node.scrollItems)
		{
			this.obScrolls.push(this.node.scrollItems);
		}

		if (this.node.scrollItemsTop)
		{
			this.obScrolls.push(this.node.scrollItemsTop);
		}

		if (this.node.scrollItems)
		{
			this.obScrolls.push(this.node.scrollProps);
		}

		this.obItems = [];

		if (this.node.scrollItems)
		{
			var items = this.node.scrollItems.querySelectorAll('[data-entity="compare-item"]');
			for (i in items)
			{
				if (items.hasOwnProperty(i))
				{
					this.obItems.push(items[i]);
				}
			}
		}

		for (i in this.obItems)
		{
			this.initItem(this.obItems[i]);
		}

		// topPanel
		if (this.node.topPanel)
		{
			this.checkTopPanels();
			BX.bind(window, 'scroll', BX.proxy(this.checkTopPanels, this));

			var items = this.node.topPanel.querySelectorAll('[data-entity="compare-item"]');
			for (i in items)
			{
				if (items.hasOwnProperty(i))
				{
					this.initItem(items[i]);
				}
			}
		}

		if (this.obScrolls.length)
		{
			$(this.obScrolls).scrollbar(this.obScrollbarParams);
		}

		// scroll sync
		if (this.obScrolls.length > 0)
		{
			for (i = 0; i < this.obScrolls.length; i++)
			{
				BX.bind(this.obScrolls[i], 'scroll', BX.proxy(function(){
					var target = BX.proxy_context;

					if (this.obScrollActive == false)
					{
						this.obScrollActive = target;
					}

					if(target == this.obScrollActive)
					{
						clearTimeout(this.scrollTimer);
						if (this.obScrolls.length > 0)
						{
							for (var j = 0; j < this.obScrolls.length; j++)
							{
								this.obScrolls[j].scrollLeft = target.scrollLeft;
							}
						}

						this.scrollTimer = setTimeout(BX.proxy(function() {
							this.obScrollActive = false;
						}, this), 100);
					}
				}, this));
			}
		}

		var tables = this.obCompare.querySelectorAll('[data-entity="compare-table"]');

		for (i in tables)
		{
			if (tables.hasOwnProperty(i))
			{
				this.obTables.push(tables[i]);
			}
		}

		if (this.obTables.length)
		{
			for (i in this.obTables)
			{
				for (j = 0; j < this.obTables[i].children.length; j++)
				{
					var obBody = this.obTables[i].children.item(j);
					for (k = 0; k < obBody.children.length; k++)
					{
						BX.bind(obBody.children.item(k), 'mouseenter', BX.proxy(this.tableRowHoverOn, this));
						BX.bind(obBody.children.item(k), 'mouseleave', BX.proxy(this.tableRowHoverOff, this));
					}
				}
			}
		}

		// resize
		this.onResize();
		BX.bind(window, 'resize', BX.proxy(this.onResize, this));


		if (this.obTable)
		{
			new BX.easing({
				duration: 1000,
				start: {opacity: 0},
				finish: {opacity: 100},
				transition: BX.easing.makeEaseOut(BX.easing.transitions.linear),
				step: BX.proxy(function(state){
					this.obTable.style.opacity = state.opacity / 100;
				}, this),
				complete: BX.proxy(function(){
					this.closeWait();
					this.obTable.removeAttribute('style');
				}, this)
			}).animate();
		}
	};

	CompareClass.prototype.initItem = function(item)
	{
		var iProductid = item.getAttribute('data-product-id');

		this.compareItemDragInit(item.parentNode);
		BX.bind(item, 'mouseenter', BX.proxy(this.itemHoverOn, this));
		BX.bind(item, 'mouseleave', BX.proxy(this.itemHoverOff, this));

		if (this.useFavorite)
		{
			obFavorite = item.querySelector('[data-entity="compare-item-favorite"]');

			if (obFavorite)
			{
				BX.bind(obFavorite, 'click', BX.proxy(this.favorite.bind(this, iProductid), this));
				BX.addCustomEvent('change.rs_favorite', BX.proxy(this.checkFavorite.bind(this, obFavorite, iProductid), this));

				this.checkFavorite(obFavorite, iProductid);
			}
		}
	};

	CompareClass.prototype.compareSort = function()
	{
		var data = {
			action: 'compare-sort',
			ITEMS: [],
		};

		if (this.node.scrollItems)
		{
			var items = this.node.scrollItems.querySelectorAll('[data-entity="compare-item"]');
			for (i in items)
			{
				if (items.hasOwnProperty(i))
				{
					var index = this.obItems.indexOf(items[i]);
					data.ITEMS.push(this.items[index]);
				}
			}
		}

		this.sendRequest(data);
	};

	CompareClass.prototype.sendRequest = function(data)
	{
		var defaultData = {
			siteId: this.siteId,
			AJAX: 'Y',
			NAME: this.config.name,
			IBLOCK_ID: this.config.iblockId,
		};

		BX.ajax({

			method: 'POST',
			dataType: 'json',
			url: this.ajaxUrl,
			data: BX.merge(defaultData, data),
			onsuccess: BX.delegate(function(result){
				this.showAction(result, data)
			}, this)
		});
	};

	CompareClass.prototype.showAction = function(result, data)
	{
		if (!data)
			return;

		switch (data.action)
		{
			case 'compare-sort':
				this.compareSortResult(result);

				break;
		}
	};

	CompareClass.prototype.compareSortResult = function()
	{
	};


	CompareClass.prototype.onResize = function()
	{
		var match = -1,
				responsive = {};

		responsive[this.breakpoints.xxs] = {
			items: 2
		};
		responsive[this.breakpoints.md] = {
			items: 3
		};
		responsive[this.breakpoints.lg] = {
			items: 4
		};
		responsive[this.breakpoints.xl] = {
			items: 5
		};

		for (var breakpoint in responsive)
		{
			breakpoint = Number(breakpoint);

			if (breakpoint <= this.getWidth() && breakpoint > match)
			{
				match = breakpoint;
			}
		}

		var content = this.obCompare.querySelectorAll('[data-entity="compare-content"]');
		for (var k in content)
		{
			if (content.hasOwnProperty(k))
			{
				//content[k].style.width = (100 * this.obItems.length / responsive[match].items) + '%';

				if (100 * this.obItems.length / responsive[match].items > 100)
				{
					content[k].style.width = (100 * this.obItems.length / responsive[match].items) + '%';
				}
				else
				{
					content[k].style.width = '';
				}

				var compreRows = content[k].querySelectorAll('[data-entity="compare-items"], [data-entity="compare-table"] > tbody > tr');
				if (compreRows.length)
				{
					for (var i in compreRows)
					{
						if (compreRows.hasOwnProperty(i))
						{
							for (var j = 0; j < compreRows[i].children.length; j++)
							{
								var cell = compreRows[i].children.item(j);
								if (j + 1 > responsive[match].items && BX.hasClass(cell, 'compare-page__placeholder'))
								{
									cell.style.display = 'none';
								}
								else
								{
									cell.style.display = '';
								}
							}
						}
					}
				}
			}
		}
	};

	CompareClass.prototype.onScroll = function()
	{
	};

	CompareClass.prototype.checkTopPanels = function(e)
	{
		var scrollTop = BX.GetWindowScrollPos().scrollTop,
				scrollItemsPos, scrollPropsPos;
		
		if (this.node.topPanel)
		{
			scrollItemsPos = BX.pos(this.node.scrollItems).bottom - 50;
			scrollPropsPos = BX.pos(this.node.scrollProps).bottom;

			if (
				(this.isTouchDevice && scrollTop < this.lastScrollTop || !this.isTouchDevice)
				&& scrollTop > scrollItemsPos
				&& scrollTop < scrollPropsPos - this.node.topPanel.offsetHeight - this.getPanelOffset()
			)
			{
				this.showTopPanel();
			}
			else if (BX.hasClass(this.node.topPanel, 'active'))
			{
				this.hideTopPanel();
			}
		}

		this.lastScrollTop = scrollTop;
	},

	CompareClass.prototype.showTopPanel = function()
	{
		BX.addClass(this.node.topPanel, 'active');
		this.node.topPanel.style.top = this.getPanelOffset() +'px';
	};
	
	CompareClass.prototype.hideTopPanel = function()
	{
		BX.removeClass(this.node.topPanel, 'active');
		this.node.topPanel.style.top = '';
	};

	
	CompareClass.prototype.getPanelOffset = function()
	{
		var t = 0;

		if (RS.Options.fixingCompactHeader == true)
		{
			var compactHeader = document.querySelector(RS.Options.compactHeaderSelector);
			if (compactHeader != undefined)
			{
				t += BX.firstChild(compactHeader).offsetHeight;
			}
		}

		return t;
	};

	CompareClass.prototype.showWait = function()
	{
		BX.addClass(this.obTable, 'overlay is-loading');
	};

	CompareClass.prototype.closeWait = function()
	{
		BX.removeClass(this.obTable, 'overlay is-loading');
	};

	CompareClass.prototype.compareItemDragStart = function()
	{
		var div = document.body.appendChild(
			document.createElement("DIV")
		);
		div.style.position = 'absolute';
		div.style.zIndex = 1100;
		div.className = 'bx-drag-object';
		div.innerHTML = this.innerHTML;
		div.style.width = this.clientWidth+'px';
		this.__dragCopyDiv = div;
		// this.className += ' bx-drag-source';
/*
		var arrowDiv = document.body.appendChild(document.createElement("DIV"));
		arrowDiv.style.position = 'absolute';
		arrowDiv.style.zIndex = 1110;
		arrowDiv.className = 'bx-drag-arrow';
		this.__dragArrowDiv = arrowDiv;
*/
		return true;
	};

	CompareClass.prototype.compareItemDrag = function(x, y, e)
	{
		var div = this.__dragCopyDiv,
				dest = this.__currentDest;

		if (this.__dragOffset == undefined)
		{
			this.__dragOffset = {
				left: this.__bxpos[0] - x,
				top: this.__bxpos[1] - y,
			};
		}
		
		div.style.left = (x + this.__dragOffset.left)+'px';
		div.style.top = (y + this.__dragOffset.top)+'px';

		var itemHover;
		if (dest == null)
			itemHover = this;
		else
			itemHover = dest

		if (itemHover)
		{
			var rowItems = BX.findChildren(itemHover.parentNode, {}, false),
					indexCurrent = rowItems.indexOf(itemHover),
					obCompare = BX.findParent(itemHover, {attribute: {'data-entity': 'compare-page'}});
			
			var compreRows = obCompare.querySelectorAll('[data-entity="compare-items"], [data-entity="compare-table"] > tbody > tr:not([data-entity="compare-prop-name"])');
			if (compreRows.length)
			{
				for (var i in compreRows)
				{
					if (compreRows.hasOwnProperty(i))
					{
						for (var j = 0; j < compreRows[i].children.length; j++)
						{
							compreRows[i].children.item(j).style.opacity = '';
							if (j == indexCurrent)
							{
								compreRows[i].children.item(j).style.opacity = '';
							}
							else
							{
								compreRows[i].children.item(j).style.opacity = '0.5';
							}
						}
					}
				}
			}
		}

		return true;
	};


	CompareClass.prototype.compareItemDragStop = function(x, y, e)
	{
		this.className = this.className.replace(/\s*bx-grid-drag-source/ig, "");

		this.__dragCopyDiv.parentNode.removeChild(this.__dragCopyDiv);
		this.__dragCopyDiv = null;
		this.__dragOffset = null;
/*
		this.__dragArrowDiv.parentNode.removeChild(this.__dragArrowDiv);
		this.__dragArrowDiv = null;
*/
		var itemHover = this,
				obCompare = BX.findParent(itemHover, {attribute: {'data-entity': 'compare-page'}});

		var compreRows = obCompare.querySelectorAll('[data-entity="compare-items"], [data-entity="compare-table"] > tbody > tr:not([data-entity="compare-prop-name"])');
		if (compreRows.length)
		{
			for (var i in compreRows)
			{
				if (compreRows.hasOwnProperty(i))
				{
					for (var j = 0; j < compreRows[i].children.length; j++)
					{
						compreRows[i].children.item(j).style.opacity = '';
					}
				}
			}
		}

		return true;
	};

	CompareClass.prototype.compareItemDragHover = function(dest, x, y)
	{
		if (this != dest)
		{
			this.__currentDest = dest;
		}
		else
		{
			this.__currentDest = null;
		}
	};

	CompareClass.prototype.compareItemDragOut = function(dest, x, y)
	{
		// BX.removeClass(dest, 'is-hover');
	};

	CompareClass.prototype.compareItemDestDragFinish = function(curNode, x, y, e)
	{
		var dest = this,
				items = BX.findChildren(curNode.parentNode),
				indexCurrent = items.indexOf(curNode),
				indexDest = items.indexOf(dest),
				pos = BX.pos(dest),
				obCompare = BX.findParent(dest, {attribute: {'data-entity': 'compare-page'}});

		BX.removeClass(dest, 'is-hover');

		var compreRows = obCompare.querySelectorAll('[data-entity="compare-items"], [data-entity="compare-table"] > tbody > tr:not([data-entity="compare-prop-name"])');
		if (compreRows.length)
		{
			for (var i in compreRows)
			{
				if (compreRows.hasOwnProperty(i))
				{
					var currentCell = compreRows[i].children.item(indexCurrent),
							destCell = compreRows[i].children.item(indexDest);

					if (currentCell && destCell)
					{
						if (x - pos.left < pos.width / 2)
						{
							destCell.parentNode.insertBefore(currentCell, destCell);
						}
						else
						{
							destCell.parentNode.insertBefore(currentCell, destCell.nextSibling);
						}
					}
				}
			}
		}

		BX.onCustomEvent('OnCompareSort');
	};

	CompareClass.prototype.compareItemDragInit = function(target)
	{
		if (this.isTouchDevice)
			return;

		// var target = BX.proxy_context;
		
		if (undefined == target.onbxdragstart)
		{
			target.onbxdragstart = this.compareItemDragStart;
			target.onbxdragstop = this.compareItemDragStop;
			target.onbxdrag = this.compareItemDrag;

			target.onbxdraghover = this.compareItemDragHover;
			target.onbxdraghout = this.compareItemDragOut;

			target.onbxdestdragfinish = this.compareItemDestDragFinish;

			jsDD.registerObject(target);
			jsDD.registerDest(target);
		}
	};

	CompareClass.prototype.getWidth = function()
	{
    return BX.GetWindowSize().innerWidth;
  };

	CompareClass.prototype.tableRowHoverOn = function(event)
	{
		var target = BX.proxy_context;

		var items = [].slice.call(target.parentNode.children),
				index = Math.trunc(items.indexOf(target) / 2) * 2;

		BX.addClass(items[index], 'hover');
		BX.addClass(items[index + 1], 'hover');

		BX.PreventDefault(event);
	};

	CompareClass.prototype.tableRowHoverOff = function(event)
	{
		var target = BX.proxy_context;

		var items = [].slice.call(target.parentNode.children),
				index = Math.trunc(items.indexOf(target) / 2) * 2;

		BX.removeClass(items[index], 'hover');
		BX.removeClass(items[index + 1], 'hover');

		BX.PreventDefault(event);
	};

	CompareClass.prototype.itemHoverOn = function(event)
	{
		var target = BX.proxy_context;
		clearTimeout(this.hoverTimer);
		target.style.height = getComputedStyle(target).height;
		BX.addClass(target, 'hover');

		BX.PreventDefault(event);
  };

	CompareClass.prototype.itemHoverOff = function(event)
	{
		if (this.hoverStateChangeForbidden)
			return;

		var target = BX.proxy_context;
		
		BX.removeClass(target, 'hover');
		target.style.height = '';

		BX.PreventDefault(event);
  };

	CompareClass.prototype.favorite = function(productId, event)
	{
		BX.PreventDefault(event);
		RS.Favorite.request(productId);
	};

	CompareClass.prototype.checkFavorite = function(obFavorite, productId)
	{
		if (!obFavorite)
			return;

		var obFavoriteText = obFavorite.querySelector('[data-entity="favorite-title"]'),
				state = BX.util.in_array(productId, RS.Favorite.getItems());

		if (state)
		{
			BX.addClass(obFavorite, 'checked');
			if (!!obFavoriteText)
			{
				obFavoriteText.innerHTML = BX.message('BTN_FAVORITE_DEL');
			}
		}
		else
		{
			BX.removeClass(obFavorite, 'checked');
			if (!!obFavoriteText)
			{
				obFavoriteText.innerHTML = BX.message('BTN_FAVORITE_ADD');
			}
		}
	};

	return CompareClass;
})();