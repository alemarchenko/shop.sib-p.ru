<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$arParams['PROP_FILE'] = isset($arParams['PROP_FILE']) ? $arParams['PROP_FILE'] : 'FILE';

if (!empty($arResult['ITEMS'])) {
	foreach ($arResult['ITEMS'] as $nIndex => $arItem) {
		if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_FILE']])) {
			$arFile = &$arResult['ITEMS'][$nIndex]['DISPLAY_PROPERTIES'][$arParams['PROP_FILE']]['FILE_VALUE'];

			$arFile['FILE_EXT'] = end(explode('.', $arFile['FILE_NAME']));
			$arFile['FILE_SIZE'] = CFile::FormatSize($arFile['FILE_SIZE'], 1);
		}
	}
}

$sDefaultTemplate = 'line';
if (!isset($arParams['RS_TEMPLATE'])) {
	$arParams['RS_TEMPLATE'] = $sDefaultTemplate;
} else if ($arParams['RS_TEMPLATE'] == 'from_widget') {
	$arParams['RS_TEMPLATE'] = isset($arParams['RS_TEMPLATE_FROM_WIDGET']) ? $arParams['RS_TEMPLATE_FROM_WIDGET'] : $sDefaultTemplate;
}

if (empty($arParams['RS_LAZY_IMAGES_USE']) || $arParams['RS_LAZY_IMAGES_USE'] == 'FROM_MODULE') {
	$arParams['RS_LAZY_IMAGES_USE'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_lazyload_images');
}



if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y') {
	$arResult['EMPTY_IMAGE_SRC'] = Redsign\MegaMart\LazyloadUtils::getEmptyImage(220, 146);
}
