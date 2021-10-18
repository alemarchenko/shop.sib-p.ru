

function SliderResponsiveSettingsInit(params) {;
    var jsOptions = JSON.parse(params.data);
    
    /** var params = {
        element: params.oCont,
        fields: jsOptions.fields,
        labels: jsOptions.labels,
        input: params.oInput
    } **/
    

    
    var title = params.oCont.previousSibling.textContent;
    params.oCont.previousSibling.style.display = 'none';
    params.oCont.colSpan = 2;
    
    var settingsNode = BX.create('div', {
        children: [
            BX.create('h4', {
               text: title,
               style: {textAlign: 'center'}
            })
        ],
        style: {
            margin: 'auto',
            maxWidth: '75%',
            textAlign: 'center'
        }
    })
    params.oCont.appendChild(settingsNode);
    
    
    var currentValue = null;
    if (params.oInput.value != "" && params.oInput.value != "[]")
	{
		currentValue = JSON.parse(params.oInput.value);
	} else {
        currentValue = jsOptions.defaultValue;
    }
    
    var q = new SliderResponsiveSettings({
        element: settingsNode,
        input: params.oInput,
        messages: jsOptions.labels,
        defaultResolutions: jsOptions.defaultResolutions,
        values: currentValue
    });
}

function SliderResponsiveSettings(params) 
{ 
    this.element = params.element;
    this.input = params.input;
    this.messages = params.messages;
    this.defaultResolutions = params.defaultResolutions;
    
    
    this.buildOptions(params.values);
    //this.addOption();
}

SliderResponsiveSettings.prototype.buildOptions = function (resolutionValues) {
    var obj = this;
    
    this.optionsNode = BX.create('div', {
        attrs: {
            'data-options': true
        },
        style: {
            display: 'inline-block'
        }
    });
    
    for (var resolution in resolutionValues) {
        if(resolutionValues.hasOwnProperty(resolution)) {
            this.addOption(resolution, resolutionValues[resolution]['items']);
        }
    }
    
    this.element.appendChild(this.optionsNode);
    
    this.element.appendChild(BX.create('input',{
		props: {
			"value": this.messages.newResolution,
			"type": "button"
		},
        style: {
            margin: '40px auto',
            display: 'block'
        },
        events: {
            click: function (e) {
                e.preventDefault();
                obj.addOption('', 3);
                obj.updateInputValue();
            }
        }
	}))
}

SliderResponsiveSettings.prototype.addOption = function(resolution, value) {
    
    var obj = this;
    
    var inputStyles = {
        minWidth: '0px',
        borderRadius: 0,
        maxWidth: '35px'
    };
    
    var inputResolution = BX.create('input', {        
        attrs: {
            type: "text",
            value: resolution,
            'data-resolution': true
        },
        style: inputStyles,
        events: {
            change: function () {
                obj.updateInputValue();
            }
        }
    }); 
    
    var select = this.buildSelect(resolution);
    if (select.value != 'custom') {
        inputResolution.readOnly = true;
    }

    BX.bind(select, 'change', function(){
		if (this.value == 'custom') {
            inputResolution.readOnly = false;
        } else {
            inputResolution.readOnly = true;
            inputResolution.value = this.value;
        }
        
        BX.fireEvent(inputResolution, 'change')
	});
    
    var resolutionTitle = BX.create('span', {
        text: this.messages.screenResolution,
        style: {
            margin: '0 10px'
        }
    });
    
    
    var elementsTitle = BX.create('span', {
        text: this.messages.elements,
        style: {
            margin: '0 10px'
        }
    });
    
    var inputValue = BX.create('input', {        
        attrs: {
            type: "number",
            value: value,
            'data-items': true
        },
        style: inputStyles,
        events: {
            change: function () {
                obj.updateInputValue();
            }
        }
    }); 
    
    var row = BX.create('div', {
        children: [resolutionTitle, select, inputResolution, elementsTitle, inputValue],
        style: {
            marginBottom: '25px',
            display: 'flex',
            alignItems: 'center'
        }
    });
    
    var deleteButton = BX.create('a', {
        text: this.messages.delete,
        attrs: {
            'href': "#"
        },
        style: {
            margin: '0 10px'
        },
        events: {
            click: function (e) {
                e.preventDefault();
                row.remove();
                obj.updateInputValue();
            }
        }
    });
    
    
    row.appendChild(deleteButton);
    this.optionsNode.appendChild(row);
    //this.element.appendChild(
    //    
    //);
}

SliderResponsiveSettings.prototype.buildSelect = function(resolution) {
    var options = [], isFindSelected = false, isSelected, retSelect;
    
    for (var optionName in this.defaultResolutions) {
        
        if(this.defaultResolutions.hasOwnProperty(optionName)) {
            
            isSelected = this.defaultResolutions[optionName] == resolution;
            
            options.push(
                BX.create('option', {
                    attrs: {
                        value: this.defaultResolutions[optionName],
                        selected: (isSelected ? 'selected' : '')
                    },
                    text: optionName
                })
            );
            
            if (isSelected) {
                isFindSelected = true;
            }
        }
    }
    
    options.push(
        BX.create('option', {
            attrs: {
                value: 'custom',
                selected: (isFindSelected ? '' : 'selected')
            },
            text: 'custom'
        })
    );
    
    retSelect = BX.create('select', {
        children: options,
        style: {
            borderRadius: 0,
            maxWidth: '100px',
            width: 'auto'
        },
        events: {
            change: function () {
                
            }
        }
    });
    
    return retSelect;
}

SliderResponsiveSettings.prototype.updateInputValue = function () {
    var optionsContainer = this.element.querySelector('[data-options]');
    var valuesObj = {};
    
    for (var i = 0; i < optionsContainer.childNodes.length; i++) {
        var optionNode = optionsContainer.childNodes[i];
        
        var resolutionInput = optionNode.querySelector('[data-resolution]');
        var itemsInput = optionNode.querySelector('[data-items]');
        
        if (
            (resolutionInput && resolutionInput.value != '') &&
            (itemsInput && itemsInput.value != '')
        ) {
            valuesObj[resolutionInput.value] = {
                items: itemsInput.value
            };
        }
    }
    console.log(JSON.stringify(valuesObj));
    this.input.value = JSON.stringify(valuesObj);
}