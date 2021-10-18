export class FilterConditions
{
	static get PROPERTY_PREFIX()
	{
		return 'CondIBProp';
	}

	static get ALLOWED_FIELDS()
	{
		return [
			'CondIBXmlID', 'CondIBSection', 'CondIBDateActiveFrom', 'CondIBDateActiveTo',
			'CondIBSort', 'CondIBDateCreate', 'CondIBCreatedBy', 'CondIBTimestampX', 'CondIBModifiedBy',
			'CondIBTags', 'CondCatQuantity', 'CondCatWeight'
		];
	}

	constructor(options = {})
	{
		this.contId = options.contId;

		BX.addCustomEvent(
			'onTreeConditionsInit', 
			this.initTree.bind(this)
		);
	}

	initTree(arParams, obTree, obControls)
	{
		if (this.contId !== arParams.parentContainer)
			return;

		this.arParams = arParams;
		this.obTree = obTree;
		this.obControls = obControls;

		this.modifyTreeParams();
	}

	modifyTreeParams()
	{
		this.obControls
			.filter(control => control.group)
			.forEach(this.modifyCondGroup)

		this.obControls
			.filter(control => !control.group)
			.forEach(this.modifyCondValueGroup)
	}

	modifyCondGroup({ visual, control })
	{
		if (visual)
		{
			visual.values.filter(v => v.True === 'False')
				.forEach((v, k) => {
					visual.values.splice(k, 1);
					visual.logic.splice(k, 1)
				});
		}

		if (control)
		{
			control.forEach((v, k) => {
				v.dontShowFirstOption = true;

				if (v.id === 'True')
					delete v.values.False;
			})
		}
	}

	modifyCondValueGroup(ctrl)
	{
		if (!((ctrl || {}).children || []).length)
			return;

		ctrl.children = ctrl.children.filter(({ controlId }) => {
			const [ controlName, iblockId, propertyId ] = controlId.split(':');
			return (
				FilterConditions.ALLOWED_FIELDS.includes(controlId) ||
				(controlName === FilterConditions.PROPERTY_PREFIX && propertyId)
			);
		});
	}
}