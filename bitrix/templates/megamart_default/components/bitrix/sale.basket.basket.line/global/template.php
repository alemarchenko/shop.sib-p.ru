<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

global $RS_BASKET_DATA;

if (!isset($RS_BASKET_DATA)) {
	$this->addExternalJs($templateFolder.'/basket.js');

	$RS_BASKET_DATA = [
		'NUM_PRODUCTS' => $arResult['NUM_PRODUCTS'],
		'PATH_TO_ORDER' => $arParams['PATH_TO_ORDER'],
		'PATH_TO_BASKET' => $arParams['PATH_TO_BASKET']
	];
}

$arItemIDs = array();
foreach ($arResult['CATEGORIES'] as $category => $items) {
	if (empty($items)) {
		continue;
	}
	foreach ($items as $v) {
		$arItemIDs[] = $v['PRODUCT_ID'];
	}
}

$this->createFrame()->begin('');

?>
<script>
	Basket.inbasket(<?=json_encode($arItemIDs)?>, true);
	BX.onCustomEvent('OnBasketChange', [{}, 'ADD2CART']);

	if ((window.RS || {}).GlobalBasket) {
		RS.GlobalBasket.init(
			'<?=SITE_ID?>',
			'<?=$templateName?>',
			'<?=$componentPath?>/ajax.php',
			<?=CUtil::PhpToJSObject($arParams) ?>
		);
	}
</script>
<?
