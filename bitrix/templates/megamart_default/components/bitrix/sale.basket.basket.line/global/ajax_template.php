<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
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

$arBasketData = [
	'numProducts' => $arResult['NUM_PRODUCTS'],
	'itemsIds' => $arItemIDs
];

echo CUtil::PhpToJSObject($arBasketData);
