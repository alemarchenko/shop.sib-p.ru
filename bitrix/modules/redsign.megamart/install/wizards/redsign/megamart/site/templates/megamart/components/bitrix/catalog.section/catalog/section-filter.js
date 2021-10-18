(function() {
	'use strict';

	if (!!window.JCSectionFilterComponent)
		return;

	window.JCSectionFilterComponent = function(params) {
		this.formPosting = false;
		this.id = params.id || '';
		this.ajaxId = params.ajaxId || '';

		this.section = null;
		this.sectionFilter = null;
		this.filterBtns = null;
		this.filterSelect = null;

		BX.ready(BX.delegate(this.init,this));
	};

	window.JCSectionFilterComponent.prototype =
	{
		init: function(event)
		{
			this.sectionFilter = BX(this.id);
			this.section = BX(this.ajaxId);

			this.filterBtns = BX.findChild(this.sectionFilter, {tag: 'a'}, true, true);

			if (this.filterBtns.length > 0)
			{
				for (var i in this.filterBtns)
				{
					BX.bind(this.filterBtns[i], 'click', BX.proxy(this.clickFilterItems, this));
				}
			}


			this.filterSelect = BX.findChild(this.sectionFilter, {tag: 'select'}, true);

			if (this.filterSelect)
			{
				BX.bind(this.filterSelect, 'change', BX.proxy(this.selectFilterItems, this));
			}
		},

		clickFilterItems: function(event)
		{
			var data = {};
			data['action'] = 'updateItems';

			this.sendRequest(data);

			BX.PreventDefault(event);
		},

		selectFilterItems: function(event)
		{
			var data = {},
					obSelect = BX.proxy_context;

			data['action'] = 'updateItems';
			data[obSelect.name] = obSelect.value;

			this.sendRequest(data);

			BX.PreventDefault(event);
		},

		sendRequest: function(data)
		{
			var url = BX.proxy_context.getAttribute('href');

			url = url ? url : document.location.href;

			if (!this.formPosting)
			{
				this.formPosting = true;
				BX.addClass(this.section, 'overlay is-loading');

				if (this.ajaxId)
				{
					data.AJAX_ID = this.ajaxId;
				}

				BX.ajax({
					url: (document.location.href.indexOf('clear_cache=Y') !== -1 ? BX.util.add_url_param(url, {'clear_cache': 'Y'}) : url),
					method: 'POST',
					dataType: 'json',
					timeout: 60,
					data: data,
					onsuccess: BX.delegate(function(result){
						if (!result || !result.JS)
							return;

						BX.ajax.processScripts(
							BX.processHTML(result.JS).SCRIPT,
							false,
							BX.delegate(function(){this.showAction(result, data);}, this)
						);
					}, this)
				});
			}
		},

		showAction: function(result, data)
		{
			if (!data)
				return;

			switch (data.action)
			{
				case 'updateItems':
					this.processFilterItemsAction(result);
					break;
			}
		},

		processFilterItemsAction: function(result)
		{
			this.formPosting = false;

			if (result)
			{
				this.processSection(result.section);
			}


			BX.removeClass(this.section, 'overlay is-loading');
		},

		processSection: function(sectionHtml)
		{
			if (!sectionHtml)
				return;

			var processed = BX.processHTML(sectionHtml, false);


			if (this.section)
			{
				this.section.innerHTML = processed.HTML
			}

			BX.ajax.processScripts(processed.SCRIPT);
			
			if (RS !== undefined)
			{
				RS.Init(['popup'], this.section);
			}
		},

	};
})();