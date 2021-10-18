<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;
use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('iblock')) {
	return;
}

$arParams['RS_TEMPLATE'] = 'tile';

$arParams['PROP_STICKER'] = isset($arParams['PROP_STICKER']) ? $arParams['PROP_STICKER'] : 'STICKER';

$arIBlockIDs = array();
foreach ($arResult['ITEMS'] as $key => $arItem) {
	if (!in_array($arItem['IBLOCK_ID'], $arIBlockIDs)) {
		$arIBlockIDs[] = $arItem['IBLOCK_ID'];
	}
}

if ($arParams['RS_SHOW_IBLOCK'] == 'Y') {
	$arResult['IBLOCKS'] = array();
	$arFilter = array(
		'SITE_ID' => SITE_ID,
		'ACTIVE' => 'Y',
		'ID' => $arIBlockIDs,
	);
	$dbRes = CIBlock::GetList(array(), $arFilter, false);
	while ($arFields = $dbRes->GetNext()) {
		$arResult['IBLOCKS'][$arFields['ID']] = array(
			'ID' => $arFields['ID'],
			'NAME' => $arFields['NAME'],
			'LIST_PAGE_URL' => str_replace(array('/', '//'), '/', str_replace('#SITE_DIR#', SITE_DIR, $arFields['LIST_PAGE_URL'])),
		);
	}
}

$arGridPreparedSettings = ParametersUtils::prepareGridSettings($arParams['~GRID_RESPONSIVE_SETTINGS']);
$sGridClass = ParametersUtils::gridToString($arGridPreparedSettings);

if (empty($sGridClass)) {
	$sGridClass = 'col-xs-12 col-sm-6 col-md-6 col-lg-4';
}

$arParams['RS_GRID_CLASS'] = $sGridClass;

$arResult['TEMPLATE_PATH'] = $_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/templates/'.$arParams['RS_TEMPLATE'].'.php';
