<template>
	<div>
		
		<template v-if="isMobile">
			<basket-select-mobile 
				:data="data"
				:useShare="useShare"
				@create="createBasket()"
				@select="selectAction"
				@edit="editBasket"
				@remove="removeAction"
				@share="shareAction"
			/>
		</template>

		<template v-else>
			<basket-select-desktop 
				:data="data"
				:useShare="useShare"
				@create="createBasket()"
				@select="selectAction"
				@edit="editBasket"
				@remove="removeAction"
				@share="shareAction"
			/>
		</template>

		<div style="display: none">
			<div :title="modalTitle" ref="form"> 
				<div class="vbasket-modal">
					<p class="mt-3"> {{ modalDescription }} </p>
					<div 
						class="form-group bmd-form-group" 
						:class="{
							'has-error': hasInputError,
							'is-filled': formData.name.length > 0
						}" 
						style="margin: 0; flex: 1;
					">
						<label for="FIELD_NAME" class="bmd-label-floating">{{messages.RS_VBASKET_SELECT_BASKET_NAME_PLACEHOLDER}}</label>
						<input type="text" class="bmd-form-control" v-model="formData.name" minlength="3" maxlength="20" required ref="input" @input="handleInput" @keyup.enter="saveAction">
						<span id="helpBlock2" v-if="hasInputError" class="help-block">{{ inputErrorMessage }}</span>
					</div>
					<div class="form-group bmd-form-group">
							<swatches 
								ref="swatches"
								style="margin: 0 -19px; line-height: 1; "
								v-model="formData.color" 
								popover-to="left"
								swatch-size="35.5" 
								:colors="colors"
								:swatch-style="{
									'border-radius': '4px',
									padding: 0, 
									margin: '0 5px 5px'
								}"
								inline
							></swatches>
					</div>
					<div class="d-block clearfix mt-5">
						<button type="button" class="btn btn-primary float-right" @click="saveAction"> {{ messages.RS_VBASKET_SELECT_SAVE }} </button>
					</div>
				</div>
			</div>
		</div>

		<div style="display: none" v-if="useShare">
			<div :title="'sharing'" ref="sharing">
				<div class="vbasket-modal">
					<div class="pt-5">
						<label>{{ messages.RS_VBASKET_SHARE_MODAL_LABEL }}</label>
						<div class="d-flex">
							<input type="text" class="form-control" v-model="shareLink"  ref="shareLinkInput">
							<button class="btn btn-primary ml-2" @click="copyShareLink()"> {{ messages.RS_VBASKET_SHARE_MODAL_COPY_LINK }} </button>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>

<script>
import BasketSelectDesktop from './BasketSelectDesktop.vue';
import BasketSelectMobile from './BasketSelectMobile.vue';
import Swatches from 'vue-swatches';
import "vue-swatches/dist/vue-swatches.min.css";

export default {

	components: { BasketSelectDesktop, BasketSelectMobile, Swatches }, 
	
	props: {
		data: Array,
		colors: Array,
		defaultColor: String,
		useShare: Boolean
	},

	data()
	{
		return {
			windowWidth: 0,
			hasInputError: false,
			inputErrorMessage: '',
			shareLink: '',
			formData: {
				code: '',
				originalName: '',
				name: '',
				color: this.defaultColor
			},
		}
	},

	computed: {

		messages()
		{
			return BX.Vue.getFilteredPhrases('RS_VBASKET_');
		},

		modalTitle()
		{
			if (this.formData.code.trim() == '')
			{
				return this.messages.RS_VBASKET_SELECT_NEW_BASKET;
			}
			else
			{
				return this.messages.RS_VBASKET_SELECT_EDIT_BASKET.replace('#NAME#', this.formData.originalName);
			}
		},

		modalDescription()
		{
			if (this.formData.code.trim() == '')
			{
				return this.messages.RS_VBASKET_SELECT_NEW_BASKET_DESCR;
			}
			else
			{
				return this.messages.RS_VBASKET_SELECT_EDIT_BASKET_DESCR;
			}
		},

		isMobile()
		{
			return this.windowWidth < 768;
		},

		selected()
		{
			return this.data.find(item => item.SELECTED);
		}

	},

	created()
	{
		window.addEventListener('resize', this.handleResize)
    	this.handleResize();
	},

	mounted()
	{
		RS.Init(['bmd'], this.$refs.form);
	},

	destroyed() 
	{
		window.removeEventListener('resize', this.handleResize)
	},

	methods: {

		handleResize() 
		{
			this.windowWidth = $(window).outerWidth();
		},

		createBasket()
		{
			this.formData = {
				code: '',
				name: this.messages.RS_VBASKET_SELECT_BASKET + ' #' +  (this.data.length + 1),
				originalName: '',
				color: this.defaultColor
			};

			RS.Utils.popup(this.$refs.form, 'popup', {
				title: this.modalTitle
			});
		},

		editBasket(basket)
		{
			
			this.formData = {
				code: basket.CODE,
				name: basket['~NAME'],
				originalName: basket['~NAME'],
				color: basket.COLOR
			};

			
			RS.Utils.popup(this.$refs.form, 'popup', {
				title: this.modalTitle
			});
		},

		handleInput()
		{
			if (this.hasInputError)
			{
				if (this.$refs.input.checkValidity())
				{
					this.hasInputError = false;
					this.inputErrorMessage = '';
				}
				else
				{
					this.inputErrorMessage = this.$refs.input.validationMessage;
				}
			}
		},

		runAction(actionName, data)
		{
			const action = 'redsign:vbasket.api.userbasket.' + actionName;
			return new Promise((resolve, reject) => {
				BX.ajax.runAction(action, { data }).then(result => result.data ? resolve() : reject(result), reject);
			});
		},

		async saveAction()
		{
			if (this.$refs.input.checkValidity())
			{
				try
				{
					if ($.fancybox.getInstance())
					{
						$.fancybox.close();
					}

					const result = await this.runAction('save', { 
						code: this.formData.code,
						name: this.formData.name,
						color: this.formData.color
					});
					
					BX.reload();
				}
				catch(e)
				{
					BX.UI.Notification.Center.notify({
						content: this.messages.RS_VBASKET_SELECT_SAVE_ERROR
					});
	
					console.error(e);
				}
			}
			else
			{
				this.hasInputError = true;
				this.inputErrorMessage = this.$refs.input.validationMessage;
			}
		},

		async removeAction(code)
		{
			try
			{
				if (confirm(this.messages.RS_VBASKET_SELECT_ARE_YOUR_SURE))
				{
					await this.runAction('remove', { code });
					BX.reload();
				}
			}
			catch(e)
			{
				BX.UI.Notification.Center.notify({
					content: this.messages.RS_VBASKET_SELECT_REMOVE_ERROR
				});

				console.error(e);
			}
		},

		async shareAction(code)
		{
			const result = await BX.ajax.runAction(
				'redsign:vbasket.controller.sharecontroller.getLink',
				{ data: { code } }
			);

			if ((result.data || {}).isSuccess)
			{
				this.shareLink = result.data.link;
			}

			RS.Utils.popup(this.$refs.sharing, 'popup', {
				title: this.messages.RS_VBASKET_SHARE_MODAL_TITLE
			});
		},

		async selectAction(code)
		{
			await this.runAction('select', { code });
			BX.reload();
		},

		copyShareLink()
		{
			this.$refs.shareLinkInput.select();
			this.$refs.shareLinkInput.focus();
			document.execCommand('copy');
		},

	}

}
</script>

<style>
.vue-swatches__wrapper {
	box-sizing: content-box;
}
</style>