<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;
use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('redsign.megamart')) {
	return;
}

$arParams['PROP_STICKER'] = isset($arParams['PROP_STICKER']) ? $arParams['PROP_STICKER'] : 'STICKER';
$arParams['PROP_SLOGAN'] = isset($arParams['PROP_SLOGAN']) ? $arParams['PROP_SLOGAN'] : 'SLOGAN';

if (empty($arParams['RS_LAZY_IMAGES_USE']) || $arParams['RS_LAZY_IMAGES_USE'] == 'FROM_MODULE')
{
	$arParams['RS_LAZY_IMAGES_USE'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_lazyload_images');
}
