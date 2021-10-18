<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Loader;


$arResult['BASKET_ITEMS_ID'] = array_map(function ($arItem) {
	return $arItem['PRODUCT_ID'];
}, $arResult['BASKET_ITEM_RENDER_DATA']);

$arResult['USE_VBASKET'] = Loader::includeModule('redsign.vbasket') && \Redsign\VBasket\Core::isEnabled();