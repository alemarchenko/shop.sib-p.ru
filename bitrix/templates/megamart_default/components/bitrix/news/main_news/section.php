<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Application;
use	\Bitrix\Main\Localization\Loc;
use	\Bitrix\Main\Loader;;

$this->setFrameMode(true);

$arFilter = array(
	'IBLOCK_ID' => $arParams['IBLOCK_ID'],
	'ACTIVE' => 'Y',
	'GLOBAL_ACTIVE' => 'Y',
);
if (0 < intval($arResult['VARIABLES']['SECTION_ID']))
	$arFilter['ID'] = $arResult['VARIABLES']['SECTION_ID'];
elseif ('' != $arResult['VARIABLES']['SECTION_CODE'])
	$arFilter['=CODE'] = $arResult['VARIABLES']['SECTION_CODE'];

$arCurSection = array();
$obCache = \Bitrix\Main\Data\Cache::createInstance();
if ($obCache->initCache(36000, serialize($arFilter), '/iblock/catalog')) {
	$arCurSection = $obCache->getVars();
} elseif ($obCache->startDataCache()) {
	$arCurSection = array();
	if (Loader::includeModule('iblock'))
	{
		$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array('ID', 'LEFT_MARGIN', 'RIGHT_MARGIN'));

		if(defined('BX_COMP_MANAGED_CACHE'))
		{
			global $CACHE_MANAGER;
			$CACHE_MANAGER->StartTagCache('/iblock/news');

			if ($arCurSection = $dbRes->Fetch())
				$CACHE_MANAGER->RegisterTag('iblock_id_'.$arParams['IBLOCK_ID']);

			$CACHE_MANAGER->EndTagCache();
		}
		else
		{
			if(!$arCurSection = $dbRes->Fetch())
				$arCurSection = array();
		}
	}
	$obCache->endDataCache($arCurSection);
}


if ($arParams['USE_ARCHIVE'] == 'Y') {
	$arParams["FILTER_NAME"] = trim($arParams["FILTER_NAME"]);
	if ($arParams["FILTER_NAME"] === '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])) {
		$arParams["FILTER_NAME"] = "arrFilter";
	}
	?>
	<?php $APPLICATION->IncludeComponent(
		"redsign:news.archive",
		"tabs",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"CHECK_DATES" => $arParams["CHECK_DATES"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_FILTER" => $arParams["CACHE_FILTER"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
			"SHOW_YEARS" => 'Y', //$arParams["ARCHIVE_SHOW_YEARS"],
			"SHOW_MONTHS" => 'N', //$arParams["ARCHIVE_SHOW_MONTHS"],
			"SEF_FOLDER" => $arResult["FOLDER"],
			"ARCHIVE_URL" => '',
			"SEF_MODE" => "N",
		),
		$component
	); ?><?php
}


$arNewsListParameters = array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"NEWS_COUNT" => $arParams["NEWS_COUNT"],
	"SORT_BY1" => $arParams["SORT_BY1"],
	"SORT_ORDER1" => $arParams["SORT_ORDER1"],
	"SORT_BY2" => $arParams["SORT_BY2"],
	"SORT_ORDER2" => $arParams["SORT_ORDER2"],
	"FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
	"PROPERTY_CODE" => array_merge($arParams["LIST_PROPERTY_CODE"], array($arParams["SLOGAN_CODE"]), array($arParams["STICKER_CODE"])),
	"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
	"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
	"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
	"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
	"SET_TITLE" => $arParams["SET_TITLE"],
	"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
	"MESSAGE_404" => $arParams["MESSAGE_404"],
	"SET_STATUS_404" => $arParams["SET_STATUS_404"],
	"SHOW_404" => $arParams["SHOW_404"],
	"FILE_404" => $arParams["FILE_404"],
	"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_FILTER" => $arParams["CACHE_FILTER"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
	"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
	"PAGER_TITLE" => $arParams["PAGER_TITLE"],
	"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
	"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
	"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
	"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
	"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
	"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
	"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
	"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
	"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
	"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
	"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
	"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
	"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
	"FILTER_NAME" => $arParams["FILTER_NAME"],
	"HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
	"CHECK_DATES" => $arParams["CHECK_DATES"],
	"USE_HEADER" => $arParams['USE_ARCHIVE'],
	// MegaMart
	"RS_LIST_SECTION" => 'l_section',
	// megamart - news
	"PROP_STICKER" => $arParams["PROP_STICKER"],
	"PROP_SLOGAN" => $arParams["PROP_SLOGAN"],
	"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],

	"RS_TEMPLATE" => $arParams['RS_TEMPLATE'],
	"RS_TEMPLATE_FROM_WIDGET" => $arParams['RS_TEMPLATE_FROM_WIDGET'],

	// megamart - grid
	"GRID_RESPONSIVE_SETTINGS" => $arParams["~GRID_RESPONSIVE_SETTINGS"],
	// megamart - share
	"USE_SHARE" => $arParams["USE_SHARE"],
	"SOCIAL_SERVICES" => $arParams["SOCIAL_SERVICES"],
	"SOCIAL_COUNTER" => $arParams["SOCIAL_COUNTER"],
	"SOCIAL_COPY" => $arParams["SOCIAL_COPY"],
	"SOCIAL_LIMIT" => $arParams["SOCIAL_LIMIT"],
	"SOCIAL_SIZE" => $arParams["SOCIAL_SIZE"],
	// megamart - lazy
	"RS_LAZY_IMAGES_USE" => $arParams["RS_LAZY_IMAGES_USE"],
	// /megamart
);

$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	'list',
	$arNewsListParameters,
	$component
);
