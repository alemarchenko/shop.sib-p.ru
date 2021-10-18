<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$arParams['PROP_SITE_URL'] = isset($arParams['PROP_SITE_URL']) ? $arParams['PROP_SITE_URL'] : 'SITE_URL';
$arParams['PROP_SITE_DOMAIN'] = isset($arParams['PROP_SITE_DOMAIN']) ? $arParams['PROP_SITE_DOMAIN'] : 'SITE_DOMAIN';

$arResult['DISPLAY_SKIP_PROPERTIES'] = [
	$arParams['PROP_SITE_URL']
];


if (empty($arParams['RS_LAZY_IMAGES_USE']) || $arParams['RS_LAZY_IMAGES_USE'] == 'FROM_MODULE')
{
	$arParams['RS_LAZY_IMAGES_USE'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_lazyload_images');
}
