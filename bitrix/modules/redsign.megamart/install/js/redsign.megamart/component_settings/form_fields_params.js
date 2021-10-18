function FormFieldsParamSettingsInit(params) {;
    var jsOptions = JSON.parse(params.data);
    
    var params = {
        element: params.oCont,
        fields: jsOptions.fields,
        labels: jsOptions.labels,
        input: params.oInput
    }
    
    new FormFieldsParamSettings(params);
}

function FormFieldsParamSettings(params)
{
    this.element = params.element;
    this.fields = params.fields;
    this.labels = params.labels;
    this.input = params.input;
    var currentValues = BX.parseJSON(this.input.value);
    
    this.fieldsParams = {};

    this.fields.forEach(BX.delegate(function (field) {
        if (currentValues && currentValues[field.ID]) {
            this.fieldsParams[field.ID] = currentValues[field.ID];
        } else {            
            this.fieldsParams[field.ID] = {
                validate: '',
                validatePattern: '',
                mask: '',
            };
        }
        
        this.addField(field);
    }, this));
    
    this.updateFieldParams();
}
FormFieldsParamSettings.prototype.addField = function (field) {
    if (field['PROPERTY_TYPE'] != 'S') {
        return;
    }
    
    var that = this;
    
    var block = this.element.appendChild(BX.create('div', {
        props : {
			"className" : "sps-params-input-block"
		},
        children: [
            BX.create('h5', {
                text: field.NAME
            }),
            BX.create('div', {
                children: [
                    BX.create('label', {text: this.labels.validate}),
                    BX.create('br'),
                    BX.create('select', {
                        children: [
                            BX.create('option', {
                                attrs: {
                                    value: '',
                                    selected: this.fieldsParams[field.ID].validate == ''
                                },
                                text: 'None'
                            }),
                            BX.create('option', {
                                attrs: {
                                    value: 'email',
                                    selected: this.fieldsParams[field.ID].validate == 'email'
                                },
                                text: 'Email'
                            }),
                            BX.create('option', {
                                attrs: {
                                    value: 'url',
                                    selected: this.fieldsParams[field.ID].validate == 'url'
                                },
                                text: 'Url'
                            }),
                            BX.create('option', {
                                attrs: {
                                    value: 'pattern',
                                    selected: this.fieldsParams[field.ID].validate == 'pattern'
                                },
                                text: 'Pattern'
                            })
                        ],
                        events: {
                            change: function () {
                                if (this.value == 'pattern') {
                                    BX.style(BX("rs_field_params_validate_pattern_" + field.ID), 'display', 'block')
                                } else {
                                    BX.style(BX("rs_field_params_validate_pattern_" + field.ID), 'display', 'none')
                                }
                                
                                that.fieldsParams[field.ID]['validate'] = this.value;
                                that.updateFieldParams();
                            },
                        }
                    })
                ]
            }),
            BX.create('div', {
                children: [  
                    BX.create('label', {text: 'Regex: '}),
                    BX.create('br'),
                    BX.create('input', {
                        props: {
                            className: "sps-params-input-values",
                        },
                        attrs: {
                            type: "text",
                            value: this.fieldsParams[field.ID].validatePattern
                        },
                        events: {
                            change: function () {
                                that.fieldsParams[field.ID]['validatePattern'] = this.value;
                                that.updateFieldParams();
                            }
                        }
                    })
                ],
                attrs: {
                    id: "rs_field_params_validate_pattern_" + field.ID
                },
                style: {
                    display: this.fieldsParams[field.ID].validate == 'pattern' ? 'block' : 'none'
                }
            }),
            BX.create('div', {
                children: [
                    BX.create('label', {text: this.labels.mask}),
                    BX.create('br'),
                    BX.create('input', {
                        props: {
                            className: "sps-params-input-values",
                        },
                        attrs: {
                            type: "text",
                            value: this.fieldsParams[field.ID].mask
                        },
                         events: {
                            change: function () {
                                that.fieldsParams[field.ID]['mask'] = this.value;
                                that.updateFieldParams();
                            }
                        }
                    })
                ],
            }),
            BX.create('hr')
        ]
    }));
}

FormFieldsParamSettings.prototype.updateFieldParams = function () {
    this.input.value = JSON.stringify(this.fieldsParams);
}