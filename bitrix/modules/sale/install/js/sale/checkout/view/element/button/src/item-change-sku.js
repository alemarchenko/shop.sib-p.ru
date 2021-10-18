import { BitrixVue } from 'ui.vue';
import { Event } from 'main.core'
import { EventType } from 'sale.checkout.const';

BitrixVue.component('sale-checkout-view-element-button-item_change_sku', {
	props: ['index'],
	computed:
	{
		localize() {
			return Object.freeze(
				BitrixVue.getFilteredPhrases('CHECKOUT_VIEW_ITEM_BACKDROP_'))
		}
	},
	methods:
	{
		backdropOpen()
		{
			Event.EventEmitter.emit(EventType.basket.backdropOpenChangeSku, {index: this.index})
		}
	},
	// language=Vue
	template: `
        <div class="checkout-basket-mobile-only">
        	<span class="checkout-basket-item-change-btn" @click="backdropOpen">{{localize.CHECKOUT_VIEW_ITEM_BACKDROP_ITEM_EDIT_CHANGE}}</span>
        </div>
	`
});