<?php

global $arCustomElementFilter;
$arCustomElementFilter = array(
	'=PROPERTY_'.$arParams["BRAND_PROP"] => $arParams['BRAND_VALUE']
);

$APPLICATION->IncludeComponent(
	"rsmm:catalog.smart.filter",
	"catalog",
	Array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CONVERT_CURRENCY" => "N",
		"DISPLAY_ELEMENT_COUNT" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"FILTER_NAME" => $arParams['FILTER_NAME'],
		"PRICE_CODE" => $arParams['PRICE_CODE'],
		"SECTION_ID" => $_REQUEST["section"],
		"TARGET_ID" => $arParams['AJAX_ID'],
		"SAVE_IN_SESSION" => "N",
		"SECTION_CODE" => "",
		"SECTION_DESCRIPTION" => "-",
		"SECTION_TITLE" => "-",
		"SEF_MODE" => "N",
		"TEMPLATE_THEME" => "site",
		"XML_EXPORT" => "N",
		"INSTANT_RELOAD" => "Y",
		"BRAND_PROP" => $arParams["BRAND_PROP"],
		"SHOW_ALL_WO_SECTION" => "Y",
		"CUSTOM_ELEMENT_FILTER" => "arCustomElementFilter",
		"FILTER_USE_HIDE_NOT_AVAILABLE" => $arParams["FILTER_USE_HIDE_NOT_AVAILABLE"],
		"FILTER_HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE']
	)
);?>