import BasketSelect from './BasketSelect.vue';

export class Select
{
	constructor(el, data, params)
	{
		this.$el = el;
		this.data = data;
		this.params = params;

		this.attachTemplate();
	}

	attachTemplate()
	{
		const data = this.data;
		const colors = this.params.colors;
		const defaultColor = this.params.defaultColor;
		const useShare = this.params.useShare;

		this.template = BX.Vue.create({
			el: this.$el,

			components: { BasketSelect },

			data()
			{
				return { data, colors, defaultColor, useShare };
			},

			template: `<basket-select v-bind="{ data, colors, defaultColor, useShare }"/>`
		});
	}
}