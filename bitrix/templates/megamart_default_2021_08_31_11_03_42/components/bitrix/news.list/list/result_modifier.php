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

$sDefaultTemplate = 'tile';
if (!isset($arParams['RS_TEMPLATE'])) {
	$arParams['RS_TEMPLATE'] = $sDefaultTemplate;
} else if ($arParams['RS_TEMPLATE'] == 'from_widget') {
	$arParams['RS_TEMPLATE'] = isset($arParams['RS_TEMPLATE_FROM_WIDGET']) ? $arParams['RS_TEMPLATE_FROM_WIDGET'] : $sDefaultTemplate;
}

if (count($arResult['ITEMS']) == 0) {
	$arParams['RS_TEMPLATE'] = 'items_not_found';
}

switch($arParams['RS_TEMPLATE']) {
	case 'tile':

		$arGridPreparedSettings = ParametersUtils::prepareGridSettings($arParams['~GRID_RESPONSIVE_SETTINGS']);
		$sGridClass = ParametersUtils::gridToString($arGridPreparedSettings);
		if (empty($sGridClass)) {
			$sGridClass = 'col-xs-12 col-sm-6 col-md-6 col-lg-4';
		}

		$arParams['RS_GRID_CLASS'] = $sGridClass;

		unset($arGridPreparedSettings, $sGridClass);
		break;

	case 'line':
	default:

		$arResult['EMPTY_IMAGE_SRC'] = Redsign\MegaMart\LazyloadUtils::getEmptyImage(260, 174);
		break;
}

$arResult['TEMPLATE_PATH'] = $_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/templates/'.$arParams['RS_TEMPLATE'].'.php';

if (empty($arParams['RS_LAZY_IMAGES_USE']) || $arParams['RS_LAZY_IMAGES_USE'] == 'FROM_MODULE')
{
	$arParams['RS_LAZY_IMAGES_USE'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_lazyload_images');
}
