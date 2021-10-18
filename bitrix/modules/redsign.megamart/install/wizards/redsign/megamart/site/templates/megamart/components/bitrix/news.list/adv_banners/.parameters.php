<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('iblock'))
	return;

$arIBlockTypes = array();
$dbIBlockType = CIBlockType::GetList(
   array("sort" => "asc"),
   array("ACTIVE" => "Y")
);
while ($arIBlockType = $dbIBlockType->Fetch()) {
    $arIBlockLangName = CIBlockType::GetByIDLang($arIBlockType["ID"], LANGUAGE_ID);
    if($arIBlockLangName) {
        $arIBlockTypes[$arIBlockType["ID"]] = "[".$arIBlockType["ID"]."] ".$arIBlockTypeLang["NAME"];
    }
}

$arIBlocksSideBanners = array();
$iblockFilter = (
	!empty($arCurrentValues['RS_SIDEBANNERS_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['RS_SIDEBANNERS_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$dbIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $dbIBlock->Fetch()) {
	$arIBlocksSideBanners[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}

if (Loader::includeModule('redsign.megamart'))
{
	ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('gridSettings'));
}

$arTemplateParameters['RS_TEMPLATE'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => Loc::getMessage('RS_ADV_BANNERS_TEMPLATE'),
	'TYPE' => 'LIST',
	'REFRESH' => 'Y',
	'VALUES' => array(
		'index_full' => Loc::getMessage('RS_ADV_BANNERS_TEMPLATE_INDEX_FULL'),
		'index_mini' => Loc::getMessage('RS_ADV_BANNERS_TEMPLATE_INDEX_MINI'),
		'outer_sidebar' => Loc::getMessage('RS_ADV_BANNERS_TEMPLATE_OUTER_SIDEBAR'),
		'mozaic_left' => Loc::getMessage('RS_ADV_BANNERS_TEMPLATE_MOZAIC_LEFT'),
		'mozaic_right' => Loc::getMessage('RS_ADV_BANNERS_TEMPLATE_MOZAIC_RIGHT'),
	),
	'DEFAULT' => 'index_full',
);

if (in_array($arCurrentValues['RS_TEMPLATE'], ['mozaic_left', 'mozaic_right']))
{
	
	$arTemplateParameters['RS_SIDEBANNERS_IBLOCK_TYPE'] = [
        "NAME" => Loc::getMessage("RS_ADV_BANNERS_SIDEBANNERS_IBLOCK_TYPE"),
        "TYPE" => "LIST",
        "VALUES" => $arIBlockTypes,
        "DEFUALT" => "services",
        "REFRESH" => "Y"
	];

	if (!empty($arCurrentValues['RS_SIDEBANNERS_IBLOCK_TYPE']))
	{

		$arTemplateParameters['RS_SIDEBANNERS_IBLOCK_ID'] = array(
			"NAME" => Loc::getMessage("RS_ADV_BANNERS_SIDEBANNERS_IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocksSideBanners,
		);
	}
}