<template>
	<div class="basket-select">
		<div 
			class="basket-select__item" 
			v-for="basket in data" 
			:key="basket.ID" 
			:class="{'basket-select__item--active': basket.SELECTED}"
			:style=" basket.COLOR ? 'color: ' + basket.COLOR : ''"
		>

			<button 
				type="button" 
				class="basket-select__button"
				@click="$emit('select', basket.CODE)"
			>
				<span class="basket-select__text">{{ basket['~NAME'] }}</span>
				<span class="basket-select__count" :style=" basket.COLOR ? 'background-color: ' + basket.COLOR : ''">{{ basket.CNT }}</span>
			</button>
			<button v-if="basket.SELECTED" type="button" class="basket-select__icon" @click="$emit('edit', basket)" :title="messages.RS_VBASKET_SELECT_EDIT">
				<svg class="icon-svg"><use xlink:href="#svg-edit-2"></use></svg>
			</button>
			<button v-if="useShare && basket.SELECTED && basket.CNT" type="button" class="basket-select__icon" @click="$emit('share', basket.CODE)" :title="messages.RS_VBASKET_SHARE_MODAL_TITLE">
				<svg class="icon-svg"><use xlink:href="#svg-link"></use></svg>
			</button>
			<button v-if="basket.SELECTED && data.length > 1" type="button" class="basket-select__icon" @click="$emit('remove', basket.CODE)" :title="messages.RS_VBASKET_SELECT_REMOVE">
				<svg class="icon-svg"><use xlink:href="#svg-cross"></use></svg>
			</button>
		</div>
	
		<div class="basket-select__item basket-select__item--edit">
			<button class="basket-select__edit-button" @click="$emit('create')"></button>
		</div>
	</div>
</template>

<script>

export default {
	
	props: {
		data: Array,
		useShare: Boolean
	},
	
	computed: {

		messages()
		{
			return BX.Vue.getFilteredPhrases('RS_VBASKET_');
		},
	},
}
</script>