window.JCCatalogStoreSKU = function(params)
{
	var i;

	if(!params)
		return;

	this.config = {
		'id'				: params.ID,
		'mainId'			: params.ID + '_main',
		'noneId'			: params.ID + '_none',
		'showEmptyStore'	: params.SHOW_EMPTY_STORE,
		'useMinAmount'		: params.USE_MIN_AMOUNT,
		'minAmount'			: params.MIN_AMOUNT
	};

	this.messages = params.MESSAGES;
	this.classes = params.CLASSES;
	this.sku = params.SKU;
	this.stores = params.STORES;
	this.obStores = {};
	for (i in this.stores)
		this.obStores[this.stores[i]] = BX(this.config.id+"_"+this.stores[i]);

	BX.addCustomEvent(window, "onCatalogStoreProductChange", BX.proxy(this.offerOnChange, this));
};

window.JCCatalogStoreSKU.prototype.offerOnChange = function(id)
{
	if (!this.sku[id])
		return;

	var curSku = this.sku[id],
		k,
		message,
		parent,
		hasShowedStock = false;

	for (k in this.obStores)
	{
		BX.adjust(this.obStores[k], {props: {className: 'product-cat-limit'}});
		message = (!!this.config.useMinAmount) ? this.getStringCount(0, this.messages) : '';
		BX.adjust(this.obStores[k].querySelector('[data-entity="quantity-limit-value"]'), {html: message});
		
		
		if (!!curSku[k])
		{
			BX.addClass(this.obStores[k], this.getStringCount(curSku[k], this.classes));
			message = (!!this.config.useMinAmount) ? this.getStringCount(curSku[k], this.messages) : curSku[k];
			BX.adjust(this.obStores[k].querySelector('[data-entity="quantity-limit-value"]'), {html: message});
		}
		else
		{
			BX.addClass(this.obStores[k], this.getStringCount(0, this.classes));
		}

		parent = BX.findParent(this.obStores[k], {tagName: 'li'});
		if (!!this.config.showEmptyStore || curSku[k] > 0)
		{
			BX.adjust(parent, {props: {className: 'shop-list-item ' +this.getStringCount(curSku[k], this.classes)}});
			var obAmount = parent.querySelector('[data-entity="stock-amount"]');
			if (curSku[k] > 0)
			{
				BX.adjust(obAmount, {props: {className: 'badge badge-primary'}, html: curSku[k]});
			}
			else
			{
				BX.adjust(obAmount, {props: {className: 'badge badge-secondary'}, html: ''});
			}
			
			BX.show(parent);
			hasShowedStock = true;
		}
		else
		{
			BX.hide(parent);
		}
	}

	if (BX(this.config.mainId) && !this.config.showEmptyStore)
	{
		if (!hasShowedStock)
		{
			this.hideBlock();
		}
		else
		{
			this.showBlock();
		}
	}
};

window.JCCatalogStoreSKU.prototype.getStringCount = function(num, arMessage)
{
	if (num == 0)
		return arMessage['ABSENT'];
	else if (num >= this.config.minAmount)
		return arMessage['LOT_OF_GOOD'];
	else
		return arMessage['NOT_MUCH_GOOD'];
};

window.JCCatalogStoreSKU.prototype.hideBlock = function()
{
	if (BX(this.config.mainId))
	{
		// BX.hide(this.config.mainId);
		BX(this.config.mainId).style.display = 'none';
	}

	if (BX(this.config.noneId))
	{
		BX(this.config.noneId).style.display = 'block';
	}
};

window.JCCatalogStoreSKU.prototype.showBlock = function()
{
	if (BX(this.config.mainId))
	{
		// BX.show(this.config.mainId);
		BX(this.config.mainId).style.display = 'block';
	}

	if (BX(this.config.noneId))
	{
		BX(this.config.noneId).style.display = 'none';
	}
};
