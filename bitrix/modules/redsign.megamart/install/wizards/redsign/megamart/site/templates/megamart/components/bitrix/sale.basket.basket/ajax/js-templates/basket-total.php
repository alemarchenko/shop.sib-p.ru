<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)  {
	die();
}

use \Bitrix\Main\Localization\Loc;
?>
<script id="simple-basket-total-template" type="text/html">
	<div class="simple-basket-total" data-entity="basket-checkout-aligner">
		<div class="simple-basket-total__price">
			{{#WEIGHT_FORMATED}}
			<div class="d-block mr-4">
				<div class="d-inline-block"><?=Loc::getMessage('SBB_WEIGHT')?>: </div>
				<div class="d-inline-block ml-4">{{{WEIGHT_FORMATED}}}</div>
			</div>
			{{/WEIGHT_FORMATED}}

			{{#SHOW_VAT}}
			<div class="d-block mr-4">
				<div class="d-inline-block"><?=Loc::getMessage('SBB_VAT')?>: </div>
				<div class="d-inline-block ml-2">{{{VAT_SUM_FORMATED}}}</div>
			</div>
			{{/SHOW_VAT}}

			{{#DISCOUNT_PRICE_FORMATED}}
			<div class="d-block mr-4">
				<div class="d-inline-block"><?=Loc::getMessage('SBB_DISCOUNT')?>: </div>
				<div class="d-inline-block ml-2">{{{DISCOUNT_PRICE_FORMATED}}}</div>
			</div>
			{{/DISCOUNT_PRICE_FORMATED}}

			<div class="d-block">
				<div class="d-inline-block"><?=Loc::getMessage('SBB_TOTAL')?>: </div>
				<div class="d-inline-block font-weight-bold ml-2" data-entity="basket-total-price">{{{PRICE_FORMATED}}}</div>
			</div>
		</div>

		<div class="simple-basket-total__buttons">
			<div class="d-flex justify-content-between align-items-center">
				<div class="d-block">
					<a href="#" class="btn btn-link" onclick="event.preventDefault(); if (RS.Panel) RS.Panel.close();"><?=Loc::getMessage('SBB_BASKET_CONTINUE_SHOPPING');?></a>
					<a href="<?=$arParams['PATH_TO_CART']?>" class="btn btn-link"><?=Loc::getMessage('SBB_BASKET_GO_TO_CART');?></a>
				</div>

				<div class="text-right">
					{{#USE_BUY1CLICK}}
					<a href="<?=SITE_DIR?>buy1click/" class="btn btn-outline-secondary{{#DISABLE_CHECKOUT}} disabled{{/DISABLE_CHECKOUT}}" data-entity="basket-buy1click-button"><?=Loc::getMessage('SBB_BUY1CLICK')?></a>
					{{/USE_BUY1CLICK}}
					<a href="<?=$arParams['PATH_TO_ORDER']?>" class="btn btn-primary{{#DISABLE_CHECKOUT}} disabled{{/DISABLE_CHECKOUT}}" data-entity="basket-checkout-button"><?=Loc::getMessage('SBB_ORDER')?></a>
				</div>
			</div>
		</div>

	</div>
</script>
