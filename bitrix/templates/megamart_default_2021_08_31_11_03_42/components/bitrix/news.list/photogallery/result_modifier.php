<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

if (empty($arParams['RS_LAZY_IMAGES_USE']) || $arParams['RS_LAZY_IMAGES_USE'] == 'FROM_MODULE') {
	$arParams['RS_LAZY_IMAGES_USE'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_lazyload_images');
}

if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y') {
	$arResult['EMPTY_IMAGE_SRC'] = Redsign\MegaMart\LazyloadUtils::getEmptyImage(310, 232);
}
