<template>
	<div class="basket-select-mobile d-flex align-items-center">
		<div class="flex-grow-1">
			<select class="form-control" v-model="selectIndex" @change="handleChange">
				<option v-for="(basket, index) in data" :key="basket.ID" :value="index">{{basket.NAME}}</option>
				<option value="new">{{ messages.RS_VBASKET_SELECT_NEW_BASKET_PLUS }}</option>
			</select>
		</div>
		<div class="flex-grow-0 d-flex ml-3">
			<a class="c-icon" href="#" rel="nofollow"  @click.prevent="$emit('edit', selected)">
				<svg class="icon icon-svg"><use xlink:href="#svg-edit-2"></use></svg>
			</a>
			<a class="c-icon" href="#" rel="nofollow"  @click.prevent="$emit('share', selected.CODE)" v-if="useShare && basket.SELECTED && basket.CNT">
				<svg class="icon icon-svg"><use xlink:href="#svg-link"></use></svg>
			</a>
			<a class="c-icon" href="#" rel="nofollow" @click.prevent="$emit('remove', selected.CODE)" v-if="data.length > 1">
				<svg class="icon icon-svg"><use xlink:href="#svg-cross"></use></svg>
			</a>
		</div>
	</div>
</template>
<script>
export default {
	
	props: {
		data: Array,
		useShare: Boolean
	},

	data()
	{
		return {
			selectIndex: 0
		};
	},

	computed: {

		messages()
		{
			return BX.Vue.getFilteredPhrases('RS_VBASKET_');
		},

		selectedIndex()
		{
			return this.data.findIndex(item => item.SELECTED);
		},

		selected()
		{
			return this.data[this.selectedIndex];
		}

	},

	created()
	{
		this.selectIndex = this.selectedIndex;
	},

	methods: {

		handleChange()
		{
			if (this.selectIndex === 'new')
			{
				this.$emit('create');
			}
			else
			{
				this.$emit('select', this.data[this.selectIndex].CODE);
			}
		}

	}
}
</script>