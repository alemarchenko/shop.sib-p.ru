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
		
		this.hoverStateChangeForbidden = false;
		
		if (typeof arParams === 'object')
		{
			this.visual = arParams.VISUAL;

			this.config.name = arParams.CONFIG.NAME;
			this.config.iblockId = arParams.CONFIG.IBLOCK_ID;
			this.ajaxUrl = arParams.CONFIG.TEMPLATE_FOLDER + '/ajax.php';

			this.items = arParams.ITEMS;
		}

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
	};

	CompareClass.prototype.init = function()
	{
		var i;

		this.obTable = BX(this.visual.TABLE);

		if (!this.obCompare)
		{
			this.errorCode = -1;
		}

		this.showWait();

		this.node.columnNames = this.obCompare.querySelector('[data-entity="column-names"]');
		this.node.columnItems = this.obCompare.querySelector('[data-entity="column-items"]');
		this.node.itemsTable = this.obCompare.querySelector('[data-entity="items-table"]');

		this.obItems = [];

		if (this.node.itemsTable)
		{
			var items = this.node.itemsTable.querySelectorAll('[data-entity="compare-item"]');
			for (i in items)
			{
				if (items.hasOwnProperty(i))
				{
					this.obItems.push(items[i])
				}
			}
		}

		for (i in this.obItems)
		{
			// BX.bind(this.obItems[i].parentNode, 'mouseover', BX.proxy(this.compareItemDragInit, this));
			this.compareItemDragInit(this.obItems[i].parentNode);
			BX.bind(this.obItems[i], 'mouseenter', BX.proxy(this.hoverOn, this));
			BX.bind(this.obItems[i], 'mouseleave', BX.proxy(this.hoverOff, this));
		}

		this.setRhythm();

		var columnItems = this.node.columnItems;

		$(this.node.columnItems).children(':first').scrollbar({
			// showArrows: true,
			// scrollx: this.node.scrollWrap,
			// scrollStep: 104
			onUpdate: function(a) {
				var $tableHead = $(columnItems).find('thead'),
						$scroll = $(this.wrapper).find('.scroll-element.scroll-x');

				$scroll.css({
					'top': $tableHead.position().top
								 + $tableHead.outerHeight()
								 + $(columnItems).find('tbody > tr:first-child').outerHeight()/2
								 - $scroll.outerHeight()/2
				});
			}
		});

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

		$(window).on('resize', BX.debounce($.proxy(this.setRhythm, this), 250));
	};

	CompareClass.prototype.compareSort = function()
	{
		var data = {
			action: 'compare-sort',
			ITEMS: [],
		};

		if (this.node.itemsTable)
		{
			var items = this.node.itemsTable.querySelectorAll('[data-entity="compare-item"]');
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


	CompareClass.prototype.setRhythm = function()
	{
		var rowsData =	BX.findChildren(this.node.itemsTable, {'tag': 'tr'}, true),
				rowsName =	BX.findChildren(this.node.columnNames, {'tag': 'tr'}, true);

		if (!!rowsName && rowsName.length > 0
			&& !!rowsData && rowsData.length)
		{
			var match = -1,
					responsive = {};

			responsive[this.breakpoints.xxs] = {
				items: 1
			};
			responsive[this.breakpoints.sm] = {
				items: 2
			};
			responsive[this.breakpoints.lg] = {
				items: 3
			};

			for (var breakpoint in responsive)
			{
				breakpoint = Number(breakpoint);

				if (breakpoint <= this.getWidth() && breakpoint > match)
				{
					match = breakpoint;
				}
			}

			this.node.itemsTable.style.width = (100 * BX.findChildren(rowsData[0], {'tag': 'td'}, true).length / responsive[match].items) + '%';
			for (i = 0; rowsName.length > i; i++)
			{
				rowsName[i].style.height = rowsData[i].style.height = 'auto';

				if(rowsData[i].offsetHeight > rowsName[i].offsetHeight)
				{
					rowsName[i].style.height = rowsData[i].offsetHeight + 'px';
				}
				else
				{
					rowsData[i].style.height = rowsName[i].offsetHeight + 'px';
				}
			}
		}
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
			// BX.addClass(itemHover, 'is-hover');

			var rowItems = BX.findChildren(itemHover.parentNode, {}, false),
					indexCurrent = rowItems.indexOf(itemHover),
					obTable = BX.findParent(itemHover, {tagName: 'table'});
			
			if (obTable)
			{
				var rows = BX.findChildren(obTable, {tagName: 'tr'}, true);
				if (rows.length)
				{
					for (var i in rows)
					{
						for (var j = 0; j < rows[i].children.length; j++)
						{
							if (j == indexCurrent)
							{
								rows[i].children.item(j).style.opacity = '';
							}
							else
							{
								rows[i].children.item(j).style.opacity = '0.5';
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
		var itemHover = this;

		if (itemHover)
		{
			var obTable = BX.findParent(itemHover, {tagName: 'table'});
			
			if (obTable)
			{
				var rows = BX.findChildren(obTable, {tagName: 'tr'}, true);
				if (rows.length)
				{
					for (var i in rows)
					{
						for (var j = 0; j < rows[i].children.length; j++)
						{
							rows[i].children.item(j).style.opacity = '';
						}
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
				obTable = BX.findParent(dest, {tagName: 'table'});

		// BX.removeClass(dest, 'is-hover');

		if (obTable)
		{
			var rows = BX.findChildren(obTable, {tagName: 'tr'}, true);
			if (rows.length)
			{
				for (var i in rows)
				{
					var currentCell = rows[i].children.item(indexCurrent),
							destCell = rows[i].children.item(indexDest);

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
			BX.unbind(target, "mouseover",	BX.proxy(this.compareItemDragInit, this));
		}
	};

	CompareClass.prototype.getWidth = function()
	{
    return BX.GetWindowSize().innerWidth;
  };

	CompareClass.prototype.hoverOn = function(event)
	{
		var target = BX.proxy_context;
		clearTimeout(this.hoverTimer);
		target.style.height = getComputedStyle(target).height;
		BX.addClass(target, 'hover');

		BX.PreventDefault(event);
  };

	CompareClass.prototype.hoverOff = function(event)
	{
		if (this.hoverStateChangeForbidden)
			return;

		var target = BX.proxy_context;
		
		BX.removeClass(target, 'hover');
		target.style.height = '';

		BX.PreventDefault(event);
  };

	CompareClass.prototype.columnHoverOn = function(item)
	{
  };

	CompareClass.prototype.columnHoverOff = function(item)
	{
  };

	return CompareClass;
})();