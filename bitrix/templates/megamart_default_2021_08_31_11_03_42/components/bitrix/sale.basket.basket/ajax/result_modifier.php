<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) {
	die();
}


$arResult['BASKET_ITEMS_ID'] = array_map(function ($arItem) {
	return $arItem['PRODUCT_ID'];
}, $arResult['BASKET_ITEM_RENDER_DATA']);
