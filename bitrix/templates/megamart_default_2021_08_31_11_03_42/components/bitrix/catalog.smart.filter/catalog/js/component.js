function JCSmartFilter(ajaxURL, viewMode, params)
{
	this.ajaxURL = ajaxURL;
	this.form = null;
	this.timer = null;
	this.cacheKey = '';
	this.cache = [];
	this.popups = [];
	this.viewMode = viewMode;
	
	if (params && params.SEF_SET_FILTER_URL)
	{
		this.bindUrlToButton('set_filter', params.SEF_SET_FILTER_URL);
		this.sef = true;
	}
	if (params && params.SEF_DEL_FILTER_URL)
	{
		this.bindUrlToButton('del_filter', params.SEF_DEL_FILTER_URL);
	}
}

JCSmartFilter.prototype.hideNotAvailable = function(input)
{
	input.form.elements.hide_not_available.value = input.checked ? 'Y' : 'N';
	this.keyup(input);
}

JCSmartFilter.prototype.keyup = function(input)
{
	if (this.viewMode == 'HORIZONTAL')
	{
		this.updateUsing();
	}

	if(!!this.timer)
	{
		clearTimeout(this.timer);
	}
	this.timer = setTimeout(BX.delegate(function(){
		this.reload(input);
	}, this), 500);
};

JCSmartFilter.prototype.click = function(checkbox)
{
	if (this.viewMode == 'HORIZONTAL')
	{
		this.updateUsing();
	}

	if(!!this.timer)
	{
		clearTimeout(this.timer);
	}

	this.timer = setTimeout(BX.delegate(function(){
		this.reload(checkbox);
	}, this), 500);
};

JCSmartFilter.prototype.reload = function(input)
{
	if (this.cacheKey !== '')
	{
		//Postprone backend query
		if(!!this.timer)
		{
			clearTimeout(this.timer);
		}
		this.timer = setTimeout(BX.delegate(function(){
			this.reload(input);
		}, this), 1000);
		return;
	}
	this.cacheKey = '|';

	this.position = BX.pos(input, true);
	this.form = BX.findParent(input, {'tag':'form'});
	if (this.form)
	{
		var values = [];
		values[0] = {name: 'ajax', value: 'y'};
		this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': new RegExp('^(input|select)$', 'i')}, true));

		for (var i = 0; i < values.length; i++)
			this.cacheKey += values[i].name + ':' + values[i].value + '|';

		if (this.cache[this.cacheKey])
		{
			this.curFilterinput = input;
			this.postHandler(this.cache[this.cacheKey], true);
		}
		else
		{
			if (this.sef)
			{
				var set_filter = BX('set_filter');
				set_filter.disabled = true;
				// $('.bx-filter').addClass('overlay is-loading');
			}

			this.curFilterinput = input;
			BX.ajax.loadJSON(
				this.ajaxURL,
				this.values2post(values),
				BX.delegate(this.postHandler, this)
			);
		}
	}
};

JCSmartFilter.prototype.updateItem = function (PID, arItem)
{
	if (arItem.PROPERTY_TYPE === 'N' || arItem.PRICE)
	{
		var trackBar = window['trackBar' + PID];
		if (!trackBar && arItem.ENCODED_ID)
			trackBar = window['trackBar' + arItem.ENCODED_ID];

		if (trackBar && arItem.VALUES)
		{
			if (arItem.VALUES.MIN)
			{
				if (arItem.VALUES.MIN.FILTERED_VALUE)
					trackBar.setMinFilteredValue(arItem.VALUES.MIN.FILTERED_VALUE);
				else
					trackBar.setMinFilteredValue(arItem.VALUES.MIN.VALUE);
			}

			if (arItem.VALUES.MAX)
			{
				if (arItem.VALUES.MAX.FILTERED_VALUE)
					trackBar.setMaxFilteredValue(arItem.VALUES.MAX.FILTERED_VALUE);
				else
					trackBar.setMaxFilteredValue(arItem.VALUES.MAX.VALUE);
			}
		}
	}
	else if (arItem.VALUES)
	{
		for (var i in arItem.VALUES)
		{
			if (arItem.VALUES.hasOwnProperty(i))
			{
				var value = arItem.VALUES[i];
				var control = BX(value.CONTROL_ID);

				if (!!control)
				{

					var label = document.querySelectorAll('[data-role="label_'+value.CONTROL_ID+'"]');
					
					if (value.DISABLED)
					{
						switch(control.type.toLowerCase())
						{
							case 'radio':
							case 'checkbox':
								control.disabled = true;
								break;
							default:
								break;
						}
						if (label.length)
						{
							for (var i = 0; i < label.length; i++)
							{
								BX.addClass(label[i], 'disabled');
							}
						}
						else
						{
							BX.addClass(control.parentNode, 'disabled');
						}
					}
					else if (value.CHECKED)
					{
						if (label.length)
						{
							for (var i = 0; i < label.length; i++)
							{
								BX.addClass(label[i], 'checked');
								BX.addClass(BX.findParent(label[i], {'class': 'filter_top__box'}), 'checked');
							}
						}
						else
						{
							BX.addClass(control.parentNode, 'checked');
						}
					}
					else
					{
						switch(control.type.toLowerCase())
						{
							case 'radio':
							case 'checkbox':
								control.removeAttribute('disabled');
								break;
							default:
								break;
						}
						if (label.length)
						{
							for (var i = 0; i < label.length; i++)
							{
								BX.removeClass(label[i], 'disabled');
							}
						}
						else
						{
							BX.removeClass(control.parentNode, 'disabled');
						}
					}

					if (value.hasOwnProperty('ELEMENT_COUNT'))
					{
						label = document.querySelectorAll('[data-role="count_'+value.CONTROL_ID+'"]');
						if (label.length)
						{
							for (var i = 0; i < label.length; i++)
							{
								label[i].innerHTML = value.ELEMENT_COUNT;
							}
						}
					}
				}
			}
		}
	}
};

JCSmartFilter.prototype.postHandler = function (result, fromCache)
{
	var hrefFILTER, url, curProp;
	var modef = BX('modef');
	var modef_num = BX('modef_num');

	if (!!result && !!result.ITEMS)
	{
		for(var popupId in this.popups)
		{
			if (this.popups.hasOwnProperty(popupId))
			{
				this.popups[popupId].destroy();
			}
		}
		this.popups = [];

		if (result.SEF_SET_FILTER_URL)
		{
			this.bindUrlToButton('set_filter', result.SEF_SET_FILTER_URL);
		}

		for(var PID in result.ITEMS)
		{
			if (result.ITEMS.hasOwnProperty(PID))
			{
				this.updateItem(PID, result.ITEMS[PID]);
			}
		}

		if (!!modef && !!modef_num)
		{
			modef_num.innerHTML = result.ELEMENT_COUNT;

			$('.js-filter-chosed-box__modef-products').html(this.getMessageProduct(result.ELEMENT_COUNT));

			if ($('#modef a').length > 0)
			{
				hrefFILTER = BX.findChildren(modef, {tag: 'A'}, true);

				if (result.FILTER_URL && hrefFILTER)
				{
					hrefFILTER[0].href = BX.util.htmlspecialcharsback(result.FILTER_URL);
				}
			}

			if (result.FILTER_AJAX_URL && result.COMPONENT_CONTAINER_ID)
			{
				BX.unbindAll(hrefFILTER[0]);
				BX.bind(hrefFILTER[0], 'click', function(e)
				{
					url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
					var container = BX(result.COMPONENT_CONTAINER_ID);
					BX.addClass(container, 'overlay is-loading');
					BX.ajax({
						url: url,
						method: 'POST',
						data: {
							action: 'updateItems',
							AJAX_ID: result.COMPONENT_CONTAINER_ID,
						},
						onsuccess: BX.delegate(function(data){
							if (!data || !data.JS)
								return;

							BX.ajax.processScripts(
								BX.processHTML(data.JS).SCRIPT,
								false,
								BX.delegate(function(){
									this.onLoadSuccess(result, data);
								}, this)
							);
						}, this),
						onfailure: BX.delegate(function(){
							this.onLoadFailure(result);
						}, this),
					});
					//BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
					return BX.PreventDefault(e);
				});
			}

			curProp = BX.findChild(BX.findParent(this.curFilterinput, {'class':'bx-filter-parameters-box'}), {'class':'bx-filter-container-modef'}, true, false);
			if (curProp)
			{
				modef.style.display = 'inline-block';
				clearTimeout(this.iTimeoutModef);
				if (this.viewMode == 'VERTICAL')
				{
					curProp.appendChild(modef);

					this.iTimeoutModef = setTimeout(function(){
						modef.style.display = 'none';
					}, 4000);
				}
			}

			if (result.INSTANT_RELOAD && result.COMPONENT_CONTAINER_ID)
			{
				//url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
				//BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
				var container = BX(result.COMPONENT_CONTAINER_ID);
				url = (result.SEF_SET_FILTER_URL == undefined)
					? BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL)
					: result.SEF_SET_FILTER_URL;

				BX.addClass(container, 'overlay is-loading');
				BX.ajax({
					url: (result.SEF_SET_FILTER_URL == undefined)
						? BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL)
						: result.SEF_SET_FILTER_URL,
					method: 'POST',
				data: {
					action: 'updateItems',
					AJAX_ID: result.COMPONENT_CONTAINER_ID,
				},
				dataType: 'json',
				onsuccess: BX.delegate(function(data){
					if (!data || !data.JS)
						return;

					BX.ajax.processScripts(
						BX.processHTML(data.JS).SCRIPT,
						false,
						BX.delegate(function(){
							this.onLoadSuccess(result, data);
						}, this)
					);
				}, this),
				onfailure: BX.delegate(function(){
					this.onLoadFailure(result);
				}, this),
				});
			}
/*
			else
			{
				if (modef.style.display === 'none')
				{
					modef.style.display = 'inline-block';
				}

				if (this.viewMode == "VERTICAL")
				{
					curProp = BX.findChild(BX.findParent(this.curFilterinput, {'class':'bx-filter-parameters-box'}), {'class':'bx-filter-container-modef'}, true, false);
					curProp.appendChild(modef);
				}

				if (result.SEF_SET_FILTER_URL)
				{
					this.bindUrlToButton('set_filter', result.SEF_SET_FILTER_URL);
				}
			}
*/
		}

		if (this.viewMode == 'HORIZONTAL')
		{
			this.updateChosedBlock();
			this.updateUsing();
		}
	}

	if (this.sef)
	{
		var set_filter = BX('set_filter');
		set_filter.disabled = false;
		// $('.bx-filter').removeClass('overlay is-loading');
	}

	if (!fromCache && this.cacheKey !== '')
	{
		this.cache[this.cacheKey] = result;
	}
	this.cacheKey = '';
};

JCSmartFilter.prototype.bindUrlToButton = function (buttonId, url)
{
	var button = BX(buttonId);
	if (button)
	{
		var proxy = function(j, func)
		{
			return function()
			{
				return func(j);
			}
		};

		if (button.type == 'submit')
			button.type = 'button';

		BX.bind(button, 'click', proxy(url, function(url)
		{
			window.location.href = url;
			return false;
		}));
	}
};

JCSmartFilter.prototype.gatherInputsValues = function (values, elements)
{
	if(elements)
	{
		for(var i = 0; i < elements.length; i++)
		{
			var el = elements[i];
			if (el.disabled || !el.type)
				continue;

			switch(el.type.toLowerCase())
			{
				case 'text':
				case 'number':
				case 'textarea':
				case 'password':
				case 'hidden':
				case 'select-one':
					if(el.value.length)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'radio':
				case 'checkbox':
					if(el.checked)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'select-multiple':
					for (var j = 0; j < el.options.length; j++)
					{
						if (el.options[j].selected)
							values[values.length] = {name : el.name, value : el.options[j].value};
					}
					break;
				default:
					break;
			}
		}
	}
};

JCSmartFilter.prototype.values2post = function (values)
{
	var post = [];
	var current = post;
	var i = 0;

	while(i < values.length)
	{
		var p = values[i].name.indexOf('[');
		if(p == -1)
		{
			current[values[i].name] = values[i].value;
			current = post;
			i++;
		}
		else
		{
			var name = values[i].name.substring(0, p);
			var rest = values[i].name.substring(p+1);
			if(!current[name])
				current[name] = [];

			var pp = rest.indexOf(']');
			if(pp == -1)
			{
				//Error - not balanced brackets
				current = post;
				i++;
			}
			else if(pp == 0)
			{
				//No index specified - so take the next integer
				current = current[name];
				values[i].name = '' + current.length;
			}
			else
			{
				//Now index name becomes and name and we go deeper into the array
				current = current[name];
				values[i].name = rest.substring(0, pp) + rest.substring(pp+1);
			}
		}
	}
	return post;
};

JCSmartFilter.prototype.hideFilterProps = function(element)
{
	var obj = element.parentNode,
		filterBlock = obj.querySelector("[data-role='bx_filter_block']"),
		propAngle = obj.querySelector("[data-role='prop_angle']");

	var windowSize = BX.GetWindowInnerSize();

	if(BX.hasClass(obj, "bx-active"))
	{
		filterBlock.style.overflow = "hidden";

		if (this.viewMode != 'HORIZONTAL' || windowSize.innerWidth < 767) {
			new BX.easing({
				duration : 300,
				start : { height: filterBlock.offsetHeight },
				finish : { height:0 },
				transition : BX.easing.transitions.linear,
				step : function(state){
					// filterBlock.style.opacity = state.opacity;
					filterBlock.style.height = state.height + "px";
				},
				complete : function() {
					filterBlock.setAttribute("style", "");
					BX.removeClass(obj, "bx-active");
				}
			}).animate();
		} else {
			new BX.easing({
				duration : 200,
				start : { height: filterBlock.offsetHeight, width: filterBlock.offsetWidth },
				finish : { height:0, width:0 },
				transition : BX.easing.transitions.linear,
				step : function(state){
					// filterBlock.style.opacity = state.opacity;
					filterBlock.style.height = state.height + "px";
					filterBlock.style.width = state.width + "px";
				},
				complete : function() {
					filterBlock.setAttribute("style", "");
					BX.removeClass(obj, "bx-active");
				}
			}).animate();
		}

		// BX.addClass(propAngle, "fa-angle-down");
		// BX.removeClass(propAngle, "fa-angle-up");
		propAngle.setAttribute("xlink:href", "#svg-chevron-down");
	}
	else
	{
		filterBlock.style.display = "block";
		// filterBlock.style.opacity = 0;
		filterBlock.style.height = "auto";
		filterBlock.style.overflow = "hidden";

		var obj_children_height = filterBlock.offsetHeight;
		var obj_children_width = filterBlock.offsetWidth;
		filterBlock.style.height = 0;
		if (this.viewMode == 'HORIZONTAL'  && !(windowSize.innerWidth < 767)) {
			filterBlock.style.width = 0;
		}

		if (this.viewMode != 'HORIZONTAL' || windowSize.innerWidth < 767) {
			new BX.easing({
				duration : 300,
				start : { height: 0 },
				finish : { height: obj_children_height },
				transition : BX.easing.transitions.linear,
				step : function(state){
					// filterBlock.style.opacity = state.opacity;
					filterBlock.style.height = state.height + "px";
				},
				complete : function() {
					BX.addClass(obj, "bx-active");
					filterBlock.setAttribute("style", "");
				}
			}).animate();
		} else {
			new BX.easing({
				duration : 200,
				start : { height: 0, width: 0 },
				finish : { height: obj_children_height, width: obj_children_width },
				transition : BX.easing.transitions.linear,
				step : function(state){
					// filterBlock.style.opacity = state.opacity;
					filterBlock.style.height = state.height + "px";
					filterBlock.style.width = state.width + "px";
				},
				complete : function() {
					BX.addClass(obj, "bx-active");
					filterBlock.setAttribute("style", "");
				}
			}).animate();
		}

		// BX.addClass(obj, "bx-active");
		// BX.removeClass(propAngle, "fa-angle-down");
		// BX.addClass(propAngle, "fa-angle-up");
		propAngle.setAttribute("xlink:href", "#svg-chevron-up");
	}
};
/*
bug on send ajax filter: removed dropdown body.
using bootstrap dropdown;
JCSmartFilter.prototype.showDropDownPopup = function(element, popupId)
{
	var contentNode = element.querySelector('[data-role="dropdownContent"]');
	this.popups["smartFilterDropDown"+popupId] = BX.PopupWindowManager.create("smartFilterDropDown"+popupId, element, {
		autoHide: true,
		offsetLeft: 0,
		offsetTop: 3,
		overlay : false,
		draggable: {restrict:true},
		closeByEsc: true,
		content: BX.clone(contentNode)
	});
	this.popups["smartFilterDropDown"+popupId].show();
};
*/

JCSmartFilter.prototype.selectDropDownItem = function(element, controlId)
{
	this.keyup(BX(controlId));

	var wrapContainer = BX.findParent(BX(controlId), {className:"bx-filter-select-container"}, false);

	var currentOption = wrapContainer.querySelector('[data-role="currentOption"]');

	var newOption = BX.clone(element, true);
	BX.remove(
		BX.findChild(newOption, {"class": "ripple-container"}, false, false)
	);
	currentOption.innerHTML = newOption.innerHTML;

	var nodes = BX.findChild(element.parentNode, {}, false, true);
	if (nodes.length > 0)
	{
		for (var i in nodes)
		{
			BX.removeClass(nodes[i], 'active');
		}
	}

	BX.addClass(element, 'active');
	// BX.PopupWindowManager.getCurrentPopup().close();
};

JCSmartFilter.prototype.setTopFilter = function(input, event)
{
	input.checked = true;
	this.keyup(input);
	event.preventDefault();
};

JCSmartFilter.prototype.onLoadSuccess = function (result, data)
{
	history.pushState(
		null,
		null,
		(result.SEF_SET_FILTER_URL == undefined)
			? BX.util.htmlspecialcharsback(result.FILTER_URL)
			: result.SEF_SET_FILTER_URL
	);

	var processed,
			obContainer = BX(result.COMPONENT_CONTAINER_ID);

	if (obContainer)
	{
		if (data.section)
		{
			processed = BX.processHTML(data.section, false)

			obContainer.innerHTML = processed.HTML;
			BX.ajax.processScripts(processed.SCRIPT);
		}
/*
		if (data.items)
		{
			processed = BX.processHTML(data.items, false)
			var obItems = obContainer.querySelector('[data-entity^="container-"]');
			if (obItems)
			{
				obItems.innerHTML = processed.HTML;
				BX.ajax.processScripts(processed.SCRIPT);
			}
		}

		if (data.pagination)
		{
			var obPagination = obContainer.querySelectorAll('[data-entity="pagination"]');

			for (var k in obPagination)
			{
				if (obPagination.hasOwnProperty(k) && BX.type.isDomNode(obPagination[k]))
				{
					obPagination[k].innerHTML = data.pagination;
				}
			}
		}

		var obLazyload = obContainer.querySelector('[data-entity="lazyload"]');
		if (obLazyload)
		{
			if (!data.lazyload)
			{
				BX.hide(obLazyload);
			}
			else
			{
				BX.show(obLazyload);
			}
		}
*/
		BX.removeClass(BX(result.COMPONENT_CONTAINER_ID), 'overlay is-loading');
	}

	if (data.sorter)
	{
		var obSorter = BX(result.COMPONENT_CONTAINER_ID + '_sorter');
		if (obSorter)
		{
			obSorter.innerHTML = data.sorter;
		}
	}

};

JCSmartFilter.prototype.onLoadFailure = function (result)
{
	BX.removeClass(BX(result.COMPONENT_CONTAINER_ID), 'overlay is-loading');
};

JCSmartFilter.prototype.getChosedBlockTemplate = function (templateName)
{
	if (!this.templates)
	{
		this.templates = [];
	}

	if (!this.templates[templateName])
	{
		var template = BX(templateName);
		this.templates[templateName] = BX.type.isDomNode(template) ? template.innerHTML : '';
	}

	return this.templates[templateName];
};

JCSmartFilter.prototype.updateChosedBlock = function ()
{
	if (this.viewMode != 'HORIZONTAL')
		return false;
	
	var arValues = this.getChoseBlockValues();
	this.setChoseBlockHtml(arValues);
};

JCSmartFilter.prototype.getChoseBlockValues = function ()
{
	var properties = [];

	$('.js-filter-box').each(function(){
		var $box = $(this);

		if ($box.find('input[type=number]').filter(function(){
			return !!this.value;
		}).length > 0)
		{
			var prop = {},
				arValues = [],
				val = '';

			$box.find('input[type=number]').filter(function(){
				return !!this.value;
			}).each(function(){
				var $formControl = $(this);
				val = '';
				if ($formControl.data('chosed-prefix'))
				{
					val+= BX.message($formControl.data('chosed-prefix'));
				}
				val+= $formControl.val();
				if ($formControl.data('chosed-postfix'))
				{
					val+= BX.message($formControl.data('chosed-postfix'));
				}
				arValues.push({control_id: $formControl.attr('id'), val: val});
			});

			prop = {
				name: $box.find('.js-filter__prop-name').text(),
				type: 'numbers',
				values: arValues,
			};

			properties.push(prop);

		}
		else if ($box.find('input[type=radio]:checked').filter(function(){
			return !!this.value;
		}).length > 0)
		{
			var prop = {},
				arValues = [],
				val = '';

			$box.find('input[type=radio]:checked').filter(function(){
				return !!this.value;
			}).each(function(){
				var $formControl = $(this);
				val = '';
				if ($formControl.data('chosed-prefix'))
				{
					val+= BX.message($formControl.data('chosed-prefix'));
				}
				val+= $formControl.data('text-value');
				if ($formControl.data('chosed-postfix'))
				{
					val+= BX.message($formControl.data('chosed-postfix'));
				}
				arValues.push({
					control_id: $box.find('input[type=radio]').filter(function(){
						return !this.value;
					}).attr('id'),
					val: val
				});
			});

			prop = {
				name: $box.find('.js-filter__prop-name').text(),
				type: 'radio',
				values: arValues,
			};

			properties.push(prop);

		}
		else if ($box.find('input[type=checkbox]:checked').filter(function(){
			return !!this.value;
		}).length > 0)
		{
			var prop = {},
				arValues = [],
				val = '';

			$box.find('input[type=checkbox]:checked').filter(function(){
				return !!this.value;
			}).each(function(){
				var $formControl = $(this);
				val = '';
				if ($formControl.data('chosed-prefix'))
				{
					val+= BX.message($formControl.data('chosed-prefix'));
				}
				val+= $formControl.data('text-value');
				if ($formControl.data('chosed-postfix'))
				{
					val+= BX.message($formControl.data('chosed-postfix'));
				}
				arValues.push({control_id: $formControl.attr('id'), val: val});
			});

			prop = {
				name: $box.find('.js-filter__prop-name').text(),
				values: arValues,
			};

			properties.push(prop);

		}
	});

	return properties;
};

JCSmartFilter.prototype.setChoseBlockHtml = function (arAllValues)
{
	var fullChoseBlockHtml = '',
		template = '';
		propHtml = '';

	if (arAllValues.length > 0)
	{
		$('.js-filter-chosed-box').show();

		for (var key in arAllValues) {
			var arValues = arAllValues[key].values;
			
			fullChoseBlockHtml += '<div class="bx-filter-chosed-box__conainer">';

			// add property name
			template = this.getChosedBlockTemplate('filter-chosed-name-template');

			propHtml = Mustache.render(
				template,
				{
					NAME: arAllValues[key].name,
				}
			);

			fullChoseBlockHtml+= propHtml;

			// add property values by type
			if (arAllValues[key].type == 'numbers')
			{
				template = this.getChosedBlockTemplate('filter-chosed-numbers-item-template');
				
				var controlId = (!!arValues[0] && arValues[0].control_id) ? arValues[0].control_id : arValues[1].control_id;

				propHtml = Mustache.render(
					template,
					{
						VALUE_MIN: (!!arValues[0] && arValues[0].val ? arValues[0].val : ''),
						VALUE_MAX: (!!arValues[1] && arValues[1].val ? arValues[1].val : ''),
						ID: (!!controlId ? controlId : ''),
					}
				);

				fullChoseBlockHtml+= propHtml;
			}
			else if (arAllValues[key].type == 'radio')
			{
				template = this.getChosedBlockTemplate('filter-chosed-radio-item-template');

				arValues.forEach(function(item, i, arValues) {
					propHtml = Mustache.render(
						template,
						{
							VALUE: item.val,
							CONTROL_ID: item.control_id,
						}
					);

					fullChoseBlockHtml+= propHtml;
				});
			}
			else
			{
				template = this.getChosedBlockTemplate('filter-chosed-default-item-template');

				arValues.forEach(function(item, i, arValues) {
					propHtml = Mustache.render(
						template,
						{
							VALUE: item.val,
							CONTROL_ID: item.control_id,
						}
					);

					fullChoseBlockHtml+= propHtml;
				});
			}
			
			fullChoseBlockHtml += '</div>';
		}
	}
	else
	{
		$('.js-filter-chosed-box').hide();
	}

	$('.js-filter-chosed-box__values').html(fullChoseBlockHtml);
};

JCSmartFilter.prototype.updateUsing = function ()
{
	$('.js-filter-box').each(function(){
		var $box = $(this);

		if ($box.find('input[type=number]').filter(function(){
			return !!this.value;
		}).length > 0)
		{
			$box.addClass('bx-filter-using');
		}
		else if ($box.find('input[type=radio]:checked').filter(function(){
			return !!this.value;
		}).length > 0)
		{
			$box.addClass('bx-filter-using');
			$box.find('.js-filter-finded-count').text('(1)');
		}
		else if ($box.find('input[type=checkbox]:checked').filter(function(){
			return !!this.value;
		}).length > 0)
		{
			$count = $box.find('input[type=checkbox]:checked').filter(function(){
				return !!this.value;
			}).length;

			$box.addClass('bx-filter-using');
			$box.find('.js-filter-finded-count').text('(' + $count + ')');
		}
		else
		{
			$box.removeClass('bx-filter-using');
			$box.find('.js-filter-finded-count').text('');
		}
	});
};

JCSmartFilter.prototype.getMessageProduct = function(count, customMessage)
{
	var mesCode,
		countReminder = (count > 10 && count < 20) ? 0 : count % 10;

	if (countReminder === 1)
	{
		mesCode = customMessage || 'SBB_GOOD';
	}
	else if (countReminder >= 2 && countReminder <= 4)
	{
		mesCode = customMessage ? customMessage + '_2' : 'SBB_GOOD_2';
	}
	else
	{
		mesCode = customMessage ? customMessage + 'S' : 'SBB_GOODS';
	}

	return BX.message(mesCode);
};

BX.namespace("BX.Iblock.SmartFilter");
BX.Iblock.SmartFilter = (function()
{
	/** @param {{
			leftSlider: string,
			rightSlider: string,
			tracker: string,
			trackerWrap: string,
			minInputId: string,
			maxInputId: string,
			minPrice: float|int|string,
			maxPrice: float|int|string,
			curMinPrice: float|int|string,
			curMaxPrice: float|int|string,
			fltMinPrice: float|int|string|null,
			fltMaxPrice: float|int|string|null,
			precision: int|null,
			colorUnavailableActive: string,
			colorAvailableActive: string,
			colorAvailableInactive: string
		}} arParams
	 */
	var SmartFilter = function(arParams)
	{
		if (typeof arParams === 'object')
		{
			this.leftSlider = BX(arParams.leftSlider);
			this.rightSlider = BX(arParams.rightSlider);
			this.tracker = BX(arParams.tracker);
			this.trackerWrap = BX(arParams.trackerWrap);

			this.minInput = BX(arParams.minInputId);
			this.maxInput = BX(arParams.maxInputId);

			this.minPrice = parseFloat(arParams.minPrice);
			this.maxPrice = parseFloat(arParams.maxPrice);

			this.curMinPrice = parseFloat(arParams.curMinPrice);
			this.curMaxPrice = parseFloat(arParams.curMaxPrice);

			this.fltMinPrice = arParams.fltMinPrice ? parseFloat(arParams.fltMinPrice) : parseFloat(arParams.curMinPrice);
			this.fltMaxPrice = arParams.fltMaxPrice ? parseFloat(arParams.fltMaxPrice) : parseFloat(arParams.curMaxPrice);

			this.precision = arParams.precision || 0;

			this.priceDiff = this.maxPrice - this.minPrice;

			this.leftPercent = 0;
			this.rightPercent = 0;

			this.fltMinPercent = 0;
			this.fltMaxPercent = 0;

			this.colorUnavailableActive = BX(arParams.colorUnavailableActive);//gray
			this.colorAvailableActive = BX(arParams.colorAvailableActive);//blue
			this.colorAvailableInactive = BX(arParams.colorAvailableInactive);//light blue

			this.isTouch = false;

			this.init();

			if ('ontouchstart' in document.documentElement)
			{
				this.isTouch = true;

				BX.bind(this.leftSlider, "touchstart", BX.proxy(function(event){
					this.onMoveLeftSlider(event)
				}, this));

				BX.bind(this.rightSlider, "touchstart", BX.proxy(function(event){
					this.onMoveRightSlider(event)
				}, this));
			}
			else
			{
				BX.bind(this.leftSlider, "mousedown", BX.proxy(function(event){
					this.onMoveLeftSlider(event)
				}, this));

				BX.bind(this.rightSlider, "mousedown", BX.proxy(function(event){
					this.onMoveRightSlider(event)
				}, this));
			}

			BX.bind(this.minInput, "keyup", BX.proxy(function(event){
				this.onInputChange();
			}, this));

			BX.bind(this.maxInput, "keyup", BX.proxy(function(event){
				this.onInputChange();
			}, this));
		}
	};

	SmartFilter.prototype.init = function()
	{
		var priceDiff;

		if (this.curMinPrice > this.minPrice)
		{
			priceDiff = this.curMinPrice - this.minPrice;
			this.leftPercent = (priceDiff*100)/this.priceDiff;

			this.leftSlider.style.left = this.leftPercent + "%";
			this.colorUnavailableActive.style.left = this.leftPercent + "%";
		}

		this.setMinFilteredValue(this.fltMinPrice);

		if (this.curMaxPrice < this.maxPrice)
		{
			priceDiff = this.maxPrice - this.curMaxPrice;
			this.rightPercent = (priceDiff*100)/this.priceDiff;

			this.rightSlider.style.right = this.rightPercent + "%";
			this.colorUnavailableActive.style.right = this.rightPercent + "%";
		}

		this.setMaxFilteredValue(this.fltMaxPrice);
	};

	SmartFilter.prototype.setMinFilteredValue = function (fltMinPrice)
	{
		this.fltMinPrice = parseFloat(fltMinPrice);
		if (this.fltMinPrice >= this.minPrice)
		{
			var priceDiff = this.fltMinPrice - this.minPrice;
			this.fltMinPercent = (priceDiff*100)/this.priceDiff;

			if (this.leftPercent > this.fltMinPercent)
				this.colorAvailableActive.style.left = this.leftPercent + "%";
			else
				this.colorAvailableActive.style.left = this.fltMinPercent + "%";

			this.colorAvailableInactive.style.left = this.fltMinPercent + "%";
		}
		else
		{
			this.colorAvailableActive.style.left = "0%";
			this.colorAvailableInactive.style.left = "0%";
		}
	};

	SmartFilter.prototype.setMaxFilteredValue = function (fltMaxPrice)
	{
		this.fltMaxPrice = parseFloat(fltMaxPrice);
		if (this.fltMaxPrice <= this.maxPrice)
		{
			var priceDiff = this.maxPrice - this.fltMaxPrice;
			this.fltMaxPercent = (priceDiff*100)/this.priceDiff;

			if (this.rightPercent > this.fltMaxPercent)
				this.colorAvailableActive.style.right = this.rightPercent + "%";
			else
				this.colorAvailableActive.style.right = this.fltMaxPercent + "%";

			this.colorAvailableInactive.style.right = this.fltMaxPercent + "%";
		}
		else
		{
			this.colorAvailableActive.style.right = "0%";
			this.colorAvailableInactive.style.right = "0%";
		}
	};

	SmartFilter.prototype.getXCoord = function(elem)
	{
		var box = elem.getBoundingClientRect();
		var body = document.body;
		var docElem = document.documentElement;

		var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft;
		var clientLeft = docElem.clientLeft || body.clientLeft || 0;
		var left = box.left + scrollLeft - clientLeft;

		return Math.round(left);
	};

	SmartFilter.prototype.getPageX = function(e)
	{
		e = e || window.event;
		var pageX = null;

		if (this.isTouch && event.targetTouches[0] != null)
		{
			pageX = e.targetTouches[0].pageX;
		}
		else if (e.pageX != null)
		{
			pageX = e.pageX;
		}
		else if (e.clientX != null)
		{
			var html = document.documentElement;
			var body = document.body;

			pageX = e.clientX + (html.scrollLeft || body && body.scrollLeft || 0);
			pageX -= html.clientLeft || 0;
		}

		return pageX;
	};

	SmartFilter.prototype.recountMinPrice = function()
	{
		var newMinPrice = (this.priceDiff*this.leftPercent)/100;
		newMinPrice = (this.minPrice + newMinPrice).toFixed(this.precision);

		if (newMinPrice != this.minPrice)
			this.minInput.value = newMinPrice;
		else
			this.minInput.value = "";
		/** @global JCSmartFilter smartFilter */
		smartFilter.keyup(this.minInput);
	};

	SmartFilter.prototype.recountMaxPrice = function()
	{
		var newMaxPrice = (this.priceDiff*this.rightPercent)/100;
		newMaxPrice = (this.maxPrice - newMaxPrice).toFixed(this.precision);

		if (newMaxPrice != this.maxPrice)
			this.maxInput.value = newMaxPrice;
		else
			this.maxInput.value = "";
		/** @global JCSmartFilter smartFilter */
		smartFilter.keyup(this.maxInput);
	};

	SmartFilter.prototype.onInputChange = function ()
	{
		var priceDiff;
		if (this.minInput.value)
		{
			var leftInputValue = this.minInput.value;
			if (leftInputValue < this.minPrice)
				leftInputValue = this.minPrice;

			if (leftInputValue > this.maxPrice)
				leftInputValue = this.maxPrice;

			priceDiff = leftInputValue - this.minPrice;
			this.leftPercent = (priceDiff*100)/this.priceDiff;

			this.makeLeftSliderMove(false);
		}

		if (this.maxInput.value)
		{
			var rightInputValue = this.maxInput.value;
			if (rightInputValue < this.minPrice)
				rightInputValue = this.minPrice;

			if (rightInputValue > this.maxPrice)
				rightInputValue = this.maxPrice;

			priceDiff = this.maxPrice - rightInputValue;
			this.rightPercent = (priceDiff*100)/this.priceDiff;

			this.makeRightSliderMove(false);
		}
	};

	SmartFilter.prototype.makeLeftSliderMove = function(recountPrice)
	{
		recountPrice = (recountPrice !== false);

		this.leftSlider.style.left = this.leftPercent + "%";
		this.colorUnavailableActive.style.left = this.leftPercent + "%";

		var areBothSlidersMoving = false;
		if (this.leftPercent + this.rightPercent >= 100)
		{
			areBothSlidersMoving = true;
			this.rightPercent = 100 - this.leftPercent;
			this.rightSlider.style.right = this.rightPercent + "%";
			this.colorUnavailableActive.style.right = this.rightPercent + "%";
		}

		if (this.leftPercent >= this.fltMinPercent && this.leftPercent <= (100-this.fltMaxPercent))
		{
			this.colorAvailableActive.style.left = this.leftPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.right = 100 - this.leftPercent + "%";
			}
		}
		else if(this.leftPercent <= this.fltMinPercent)
		{
			this.colorAvailableActive.style.left = this.fltMinPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.right = 100 - this.fltMinPercent + "%";
			}
		}
		else if(this.leftPercent >= this.fltMaxPercent)
		{
			this.colorAvailableActive.style.left = 100-this.fltMaxPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.right = this.fltMaxPercent + "%";
			}
		}

		if (recountPrice)
		{
			this.recountMinPrice();
			if (areBothSlidersMoving)
				this.recountMaxPrice();
		}
	};

	SmartFilter.prototype.countNewLeft = function(event)
	{
		var pageX = this.getPageX(event);

		var trackerXCoord = this.getXCoord(this.trackerWrap);
		var rightEdge = this.trackerWrap.offsetWidth;

		var newLeft = pageX - trackerXCoord;

		if (newLeft < 0)
			newLeft = 0;
		else if (newLeft > rightEdge)
			newLeft = rightEdge;

		return newLeft;
	};

	SmartFilter.prototype.onMoveLeftSlider = function(e)
	{
		if (!this.isTouch)
		{
			this.leftSlider.ondragstart = function() {
				return false;
			};
		}

		BX.addClass(this.leftSlider, 'active');

		if (!this.isTouch)
		{
			document.onmousemove = BX.proxy(function(event) {
				this.leftPercent = ((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
				this.makeLeftSliderMove();
			}, this);

			document.onmouseup = BX.proxy(function(event) {
				document.onmousemove = document.onmouseup = null;
				BX.removeClass(this.leftSlider, 'active');
			}, this);
		}
		else
		{
			document.ontouchmove = BX.proxy(function(event) {
				this.leftPercent = ((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
				this.makeLeftSliderMove();
			}, this);

			document.ontouchend = BX.proxy(function(event) {
				document.ontouchmove = document.touchend = null;
				BX.removeClass(this.leftSlider, 'active');
			}, this);
		}

		return false;
	};

	SmartFilter.prototype.makeRightSliderMove = function(recountPrice)
	{
		recountPrice = (recountPrice !== false);

		this.rightSlider.style.right = this.rightPercent + "%";
		this.colorUnavailableActive.style.right = this.rightPercent + "%";

		var areBothSlidersMoving = false;
		if (this.leftPercent + this.rightPercent >= 100)
		{
			areBothSlidersMoving = true;
			this.leftPercent = 100 - this.rightPercent;
			this.leftSlider.style.left = this.leftPercent + "%";
			this.colorUnavailableActive.style.left = this.leftPercent + "%";
		}

		if ((100-this.rightPercent) >= this.fltMinPercent && this.rightPercent >= this.fltMaxPercent)
		{
			this.colorAvailableActive.style.right = this.rightPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.left = 100 - this.rightPercent + "%";
			}
		}
		else if(this.rightPercent <= this.fltMaxPercent)
		{
			this.colorAvailableActive.style.right = this.fltMaxPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.left = 100 - this.fltMaxPercent + "%";
			}
		}
		else if((100-this.rightPercent) <= this.fltMinPercent)
		{
			this.colorAvailableActive.style.right = 100-this.fltMinPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.left = this.fltMinPercent + "%";
			}
		}

		if (recountPrice)
		{
			this.recountMaxPrice();
			if (areBothSlidersMoving)
				this.recountMinPrice();
		}
	};

	SmartFilter.prototype.onMoveRightSlider = function(e)
	{
		if (!this.isTouch)
		{
			this.rightSlider.ondragstart = function() {
				return false;
			};
		}

		BX.addClass(this.rightSlider, 'active');

		if (!this.isTouch)
		{
			document.onmousemove = BX.proxy(function(event) {
				this.rightPercent = 100-(((this.countNewLeft(event))*100)/(this.trackerWrap.offsetWidth));
				this.makeRightSliderMove();
			}, this);

			document.onmouseup = BX.proxy(function(event) {
				document.onmousemove = document.onmouseup = null;
				BX.removeClass(this.rightSlider, 'active');
			}, this);
		}
		else
		{
			document.ontouchmove = BX.proxy(function(event) {
				this.rightPercent = 100-(((this.countNewLeft(event))*100)/(this.trackerWrap.offsetWidth));
				this.makeRightSliderMove();
			}, this);

			document.ontouchend = BX.proxy(function(event) {
				document.ontouchmove = document.ontouchend = null;
				BX.removeClass(this.rightSlider, 'active');
			}, this);
		}

		return false;
	};

	return SmartFilter;
})();

$(document).ready(function(){

	var $bxfilter = $('.bx-filter');
	$bxfilter.find('.bx-filter-scroll').each(function(){
		$(this).scrollbar({});
	});

	if (smartFilter.viewMode == 'HORIZONTAL')
	{
		smartFilter.updateChosedBlock();
		smartFilter.updateUsing();
	}

	$bxfilter.find('.bx-filter-search > input').on('keyup', function(){
		var value = $(this).val().toLowerCase(),
				$filterBlock = $(this).closest('.bx-filter-block'),
 				$scrollItems = $filterBlock.find('[data-entity="filter-value"]');

		if (value.length < 1)
		{
			$scrollItems.css('display','block');
		}
		else
		{
			$scrollItems.each(function(){
				if ($(this).find('.bx-filter-param-text').text().toLowerCase().indexOf(value) >= 0)
				{
					$(this).css('display','block');
				}
				else
				{
					$(this).css('display','none');
				}
			});
		}
		//window.RSAL_JspApi[$(this).closest('.bx-filter-parameters-box').data('prop-id')].reinitialise();
	});

	/*
	$(document).on(
		'click.bs.dropdown',
		'.bx-filter-horizontal .bx-filter-parameters-box',
		function(e) {
			e.stopPropagation();
		}
	);

	$(document).on(
		'show.bs.dropdown hide.bs.dropdown',
		'.bx-filter-horizontal .bx-filter-parameters-box',
		function(e) {
			var $this = $(this),
				windowSize = BX.GetWindowInnerSize();
			if (windowSize.innerWidth < 767)
			{
				e.preventDefault();
				smartFilter.hideFilterProps($this.find('[data-toggle="dropdown"]').get(0));
			}
		}
	);*/

	/*
	$(document).on(
		'click.bs.dropdown',
		'.bx-filter-horizontal .bx-filter-parameters-box',
		function(e) {
			e.stopPropagation();
		}
	);
	*/

	$(document).on(
		'click',
		'.js-filter-box-drop-all',
		function(e) {
			var elem = this,
				$this = $(elem),
				$filterBox = $this.closest('.js-filter-box');
			$filterBox.removeClass('bx-filter-using');
			$filterBox.find('.js-filter-finded-count').html('');
			$filterBox.find('input[type=text], input[type=number]').val('');
			$filterBox.find('input[type=checkbox], input[type=radio]').prop("checked", false);
			$filterBox.find('label.checked').removeClass('checked');

			setTimeout(function(){
				smartFilter.reload(elem);
			},100)

			e.stopPropagation();
		}
	);

	$(document).on(
		'click',
		'.js-filter-chosed-box__reset',
		function(e) {
			var $this = $(this),
				id = $this.data('property-id'),
				$inputs = $('#' + id).closest('.js-filter-box').find('input[type=text], input[type=number]');

			$inputs.val('');
			smartFilter.keyup($inputs.get(0));
		}
	);

	$(document).on('click', dropdownHide);
	$(document).on('click', '.js-filter-box', dropdownHide, dropdownAlign);
	$(document).on('click', '.js-filter-box-drop-all', dropdownAlign);

	function dropdownHide(e) {
		var $this = $(this);
		var windowSize = BX.GetWindowInnerSize();
		if (windowSize.innerWidth < 767) return;

		var activeDropdown = $('.bx-filter-horizontal .js-filter-box.bx-active');
		if(activeDropdown.find('.bx-filter-block').length == 0) return;
		
		if($this[0] == activeDropdown[0]) return;
		if($(e.target).closest(activeDropdown).length > 0) return;
		
		smartFilter.hideFilterProps(activeDropdown.find('.bx-filter-parameters-box-title')[0]);
	}

	function dropdownAlign(e) {
		var $this;
		if ($(e.target).hasClass('js-filter-box')) {
			$this = $(e.target);
		} else if ($(e.target).closest('.js-filter-box').length > 0) {
			$this = $(e.target).closest('.js-filter-box');
		}
	
		if ($this == undefined) return;

		var $thisDropdown = $this.find('.bx-filter-block');
		var clientSize = document.body.clientWidth;

		$thisDropdown.removeClass("bx-filter-block--right");

		if (clientSize < $thisDropdown.offset().left + $thisDropdown.width()) {
			$thisDropdown.addClass("bx-filter-block--right");
		}
	}
});
