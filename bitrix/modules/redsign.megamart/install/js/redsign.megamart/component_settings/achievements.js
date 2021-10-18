function CustomSettingsEdit(params)
{
	var jsOptions = JSON.parse(params.data);
	var showArray = {
		"element" : params.oCont,
		"options" : jsOptions
	};

	if (params.oInput.value != "" && params.oInput.value != "[]")
	{
		this.inputData = JSON.parse(params.oInput.value);
		this.inputData.forEach(function(page){
			CustomSettingsEdit.prototype.addInputBlock(showArray, page);
		},this);
	}
	else
	{
		CustomSettingsEdit.prototype.addInputBlock(showArray);
	}
	addPageParams = params.oCont.appendChild(BX.create('input',{
		props: {
			"value": "+",
			"type": "button",
			"className" : "addPageParams"
		}
	}));

	BX.bind(addPageParams, 'click', function(){
		CustomSettingsEdit.prototype.addInputBlock(showArray);
		params.oCont.appendChild(addPageParams);
	});
}

CustomSettingsEdit.prototype.addInputBlock = function(params, values)
{
	var number = "";
	var content = "";
	var hidden = params.element.querySelector("input[type='hidden']");
    
	if (typeof(values) !== "undefined")
	{
		number = values[0];
		content = values[1];
	}
    
	var block = params.element.appendChild(BX.create('div', {
		props : {
			"className" : "sps-params-input-block"
		},
		html : [
			'<div>',
			'<label>'+ params.options['labelNumber'] + '</label><br>',
			'<input type="text" class="sps-params-input-values" value="'+ number +'">',
			'</div>',
			'<div >',
			'<label>'+ params.options['labelContent'] + '</label><br>',
			'<textarea class="sps-params-input-values" style="min-width: 87%;">' + content + '</textarea>',
			'</div>',
			'<hr>'
		].join('')
	}));

	BX.bindDelegate(block, 'change', { 'class': 'sps-params-input-values' }, BX.proxy(function()
	{
		var valuesArray = [];
		var inputBlocks = params.element.getElementsByClassName("sps-params-input-block");
		Array.prototype.forEach.call(inputBlocks, function(inputBlock)
		{
			inputs = inputBlock.getElementsByClassName("sps-params-input-values");
			if (inputs[0].value !== "" || inputs[1].value !== "")
			{   
				valuesArray.push([inputs[0].value, inputs[1].value]);
			}
		});
		hidden.value = JSON.stringify(valuesArray);
	}, this));
};