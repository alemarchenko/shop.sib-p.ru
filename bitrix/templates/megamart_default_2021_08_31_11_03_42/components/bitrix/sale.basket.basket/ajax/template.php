<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

$arParams['USE_PRICE_ANIMATION'] = isset($arParams['USE_PRICE_ANIMATION']) && $arParams['USE_PRICE_ANIMATION'] === 'N' ? 'N' : 'Y';
$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

$documentRoot = Main\Application::getDocumentRoot();

\CJSCore::Init(array('fx', 'ajax'));
$this->addExternalJs(SITE_TEMPLATE_PATH.'/components/bitrix/sale.basket.basket/main/js/mustache.js');
$this->addExternalJs(SITE_TEMPLATE_PATH.'/components/bitrix/sale.basket.basket/main/js/action-pool.js');
$this->addExternalJs(SITE_TEMPLATE_PATH.'/components/bitrix/sale.basket.basket/main/js/filter.js');
$this->addExternalJs(SITE_TEMPLATE_PATH.'/components/bitrix/sale.basket.basket/main/js/component.js');
$this->addExternalJs($templateFolder.'/js/component.js');

ob_start();

if (empty($arResult['ERROR_MESSAGE'])) {


	$jsTemplates = new Main\IO\Directory($documentRoot.$templateFolder.'/js-templates');
	foreach ($jsTemplates->getChildren() as $jsTemplate) {
		include($jsTemplate->getPath());
	}

	?>
	
	<div class="simple-basket" id="simple-basket-root" style="opacity: 0;">
		<div data-entity="basket-custom-errors" style="display: none">
			<div class="alert alert-danger">
				<span data-entity="basket-custom-errors-content"></span>
			</div>
		</div>

		<div data-entity="basket-custom-info" style="display: none">
			<div class="alert alert-info">
				<span data-entity="basket-custom-info-content"></span>
			</div>
		</div>

		<div class="simple-basket__items scrollbar-inner">
			<table class="simple-basket__table table">
				<thead>
					<th></th>
					<th><?=Loc::getMessage('SBB_TABLE_TH_NAME')?></th>
					<th><?=Loc::getMessage('SBB_TABLE_TH_AMOUNT')?></th>
					<th><?=Loc::getMessage('SBB_TABLE_TH_PRICE')?></th>
					<th><?=Loc::getMessage('SBB_TABLE_TH_DISCOUNT')?></th>
					<th><?=Loc::getMessage('SBB_TABLE_TH_SUM')?></th>
					<th></th>
				</thead>
				<tbody id="simple-basket-item-table"></tbody>
			</table>
		</div>
		<div class="simple-basket__total" data-entity="basket-total-block"></div>
	</div>

	<?php
	if (!empty($arResult['CURRENCIES']) && Main\Loader::includeModule('currency'))
	{
		CJSCore::Init('currency');

		?>
		<script>
			BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
		</script>
		<?php
	}

	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedTemplate = $signer->sign($templateName, 'sale.basket.basket');
	$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');
	$messages = Loc::loadLanguageFile(__FILE__);
	?>
	<script>
	BX.Sale.SimpleBasketComponent.init({
		result: <?=CUtil::PhpToJSObject($arResult, false, false, true)?>,
		params: <?=CUtil::PhpToJSObject($arParams)?>,
		template: '<?=CUtil::JSEscape($signedTemplate)?>',
		signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
		siteId: '<?=$component->getSiteId()?>',
		ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
		templateFolder: '<?=CUtil::JSEscape($templateFolder)?>'
	});

	if (window.$ && $.fn.scrollbar) {
		$('.simple-basket__items').scrollbar();
	}
	</script>
	<?php

} else {
	if ($arResult['BASKET_ITEMS_COUNT'] < 1) {
		// Cart is empty
		?>
				<div class="basket-empty mt-7 pt-7">
					<div class="basket-empty__icon">
						<svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg>
					</div>
					<h2 class="basket-empty__title"><?=Loc::getMessage('SBB_BASKET_EMPTY_TITLE');?></h2>
					<div class="basket-empty__descr"><?=Loc::getMessage('SBB_BASKET_EMPTY_DESCR');?></div>
					<div class="basket-empty__buttons">
						<a href="<?=$arParams['EMPTY_BASKET_HINT_PATH']?>" class="btn btn-primary"><?=Loc::getMessage('SBB_BASKET_EMPTY_CATALOG');?></a>
					</div>
				</div>
		<?php
	} else {
		ShowError($arResult['ERROR_MESSAGE']);
	}
}

$templateData = ob_get_clean();
