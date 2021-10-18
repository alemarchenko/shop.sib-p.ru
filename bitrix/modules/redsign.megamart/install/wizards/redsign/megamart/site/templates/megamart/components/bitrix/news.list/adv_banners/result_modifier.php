<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;
use \Redsign\MegaMart\ParametersUtils;

$sDefaultTemplate = 'index_full';
if (!isset($arParams['RS_TEMPLATE'])) {
	$arParams['RS_TEMPLATE'] = $sDefaultTemplate;
}

if (count($arResult['ITEMS']) == 0) {
	$arParams['RS_TEMPLATE'] = 'items_not_found';
}

$arResult['TEMPLATE_PATH'] = $_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/templates/'.$arParams['RS_TEMPLATE'].'.php';


if (isset($arParams['~GRID_RESPONSIVE_SETTINGS'])){
	$arParams['GRID_RESPONSIVE_SETTINGS'] = CUtil::JsObjectToPhp($arParams['~GRID_RESPONSIVE_SETTINGS']);

	if (is_array($arParams['GRID_RESPONSIVE_SETTINGS']) && count($arParams['GRID_RESPONSIVE_SETTINGS']) > 0) {
		foreach ($arParams['GRID_RESPONSIVE_SETTINGS'] as $key => $val) {
			if (intval($val['items']) > 0) {
				$arParams['GRID_RESPONSIVE_SETTINGS'][$key] = (int) 12 / intval($val['items']);
			}
		}
		unset($key, $val);
	}
}
else
{
	$arParams['GRID_RESPONSIVE_SETTINGS'] = array();
}

if (in_array($arParams['RS_TEMPLATE'], ['mozaic_left', 'mozaic_right']))
{
	
	$arParams['RS_BACKGROUND_PROPERTY'] = (!empty($arParams['RS_BACKGROUND_PROPERTY'])) ? $arParams['RS_BACKGROUND_PROPERTY'] : 'BACKGROUND';
	$arParams['RS_LINK_PROPERTY'] = (!empty($arParams['RS_LINK_PROPERTY'])) ? $arParams['RS_LINK_PROPERTY'] : 'LINK';

	foreach($arResult['ITEMS'] as &$arItem)
	{
		$arItem['BACKGROUND'] = !empty($arItem['PROPERTIES'][$arParams['RS_BACKGROUND_PROPERTY']]['VALUE'])
			? CFile::GetPath($arItem['PROPERTIES'][$arParams['RS_BACKGROUND_PROPERTY']]['VALUE'])
			: null;

		$arItem['PRODUCT_LINK'] = $arItem['PROPERTIES'][$arParams['RS_LINK_PROPERTY']]['VALUE'];
	}
	unset($arItem);

	$arResult['SIDEBANNERS'] = [];
	
	if (!empty($arParams['RS_SIDEBANNERS_IBLOCK_ID']))
	{
		$arOrder = [
			$arParams['SORT_BY1'] => $arParams['SORT_ORDER1'],
			$arParams['SORT_BY2'] => $arParams['SORT_ORDER2'],
		];
		$arFilter = [
			'IBLOCK_LID' => SITE_ID,
			'ACTIVE' => 'Y',
			'IBLOCK_ID' => $arParams['RS_SIDEBANNERS_IBLOCK_ID'],
			'SECTION_CODE' => $arParams['PARENT_SECTION_CODE'],
			'INCLUDE_SUBSECTIONS' => $arParams['INCLUDE_SUBSECTIONS'],
		];
		$arGroupBy = false;
		$arNavStartParams = [
			'nTopCount' => 2
		];
		$arSelectFields = [
			'ID',
			'IBLOCK_ID',
			'NAME',
			'PROPERTY_BACKGROUND',
			'PROPERTY_LINK',
			'PROPERTY_LINK_TARGET_ID',
			'PROPERTY_LINK_TARGET_VALUE',
			'PROPERTY_LINK_TARGET_ENUM_ID',
			'PROPERTY_LINK_TITLE',
			'PROPERTY_PLACE'
		];

		$dbSidebanners = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);

		while($obSidebanner = $dbSidebanners->GetNextElement())
		{
			$arSideBanner = $obSidebanner->GetFields();
			$arSideBanner['PROPERTIES'] = $obSidebanner->GetProperties();

			$arButtons = CIBlock::GetPanelButtons(
				$arSideBanner["IBLOCK_ID"],
				$arSideBanner["ID"],
				0,
				array("SECTION_BUTTONS"=>false, "SESSID"=>false)
			);

			$arResult['SIDEBANNERS'][] = [
				'SRC' => \CFile::GetPath($arSideBanner['PROPERTIES']['BACKGROUND']['VALUE']),
				'LINK' => $arSideBanner['PROPERTIES']['LINK']['VALUE'],
				'LINK_TARGET' => isset($arSideBanner['PROPERTIES']['LINK_TITLE']['VALUE_XML_ID'])
					? $arItem['DISPLAY_PROPERTIES']['LINK_TARGET']['VALUE_XML_ID']
					: '_self',
				'LINK_TITLE' => isset($arSideBanner['PROPERTIES']['LINK_TITLE']['VALUE'])
					? $arSideBanner['PROPERTIES']['LINK_TITLE']['VALUE']
					: '',
				'EDIT_LINK' => $arButtons["edit"]["edit_element"]["ACTION_URL"],
				'DELETE_LINK' => $arButtons["edit"]["delete_element"]["ACTION_URL"]
			];
		}
	}
}
