<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;
use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('redsign.megamart')) {
	return;
}

$arParams['PROP_SITE_URL'] = isset($arParams['PROP_SITE_URL']) ? $arParams['PROP_SITE_URL'] : 'SITE_URL';;
$arParams['PROP_SITE_DOMAIN'] = isset($arParams['PROP_SITE_DOMAIN']) ? $arParams['PROP_SITE_DOMAIN'] : 'SITE_DOMAIN';

$arResult['DISPLAY_SKIP_PROPERTIES'] = [
	$arParams['PROP_SITE_URL']
];


$arParams['RS_TEMPLATE'] = 'type1';
$arResult['TEMPLATE_PATH'] = $_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/templates/'.$arParams['RS_TEMPLATE'].'.php';

$arGridPreparedSettings = ParametersUtils::prepareGridSettings($arParams['~GRID_RESPONSIVE_SETTINGS']);
$sGridClass = ParametersUtils::gridToString($arGridPreparedSettings);
if (empty($sGridClass)) {
	$sGridClass = 'col-xs-12 col-sm-6 col-md-6 col-lg-4';
}

$arParams['RS_GRID_CLASS'] = $sGridClass;

unset($arGridPreparedSettings, $sGridClass);

if (empty($arParams['RS_LAZY_IMAGES_USE']) || $arParams['RS_LAZY_IMAGES_USE'] == 'FROM_MODULE')
{
	$arParams['RS_LAZY_IMAGES_USE'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_lazyload_images');
}
