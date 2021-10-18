<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

$APPLICATION->SetPageProperty("hide_section", "Y");
$APPLICATION->SetPageProperty('wide_page', 'N');
$APPLICATION->SetPageProperty('hide_inner_sidebar', $bSidebarInner ? 'N' : 'Y');
$APPLICATION->SetPageProperty('hide_outer_sidebar', $bSidebarOuter ? 'N' : 'Y');

$componentSectionParams = [
	"IBLOCK_TYPE" => $arParams["CATALOG_IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"],
	// "ELEMENT_SORT_FIELD" => isset($alfaCSortType) ? $alfaCSortType : $arParams["ELEMENT_SORT_FIELD"],
	// "ELEMENT_SORT_ORDER" => isset($alfaCSortToo) ? $alfaCSortToo : $arParams["ELEMENT_SORT_ORDER"],
	// "ELEMENT_SORT_FIELD2" => isset($alfaCSortType) ? $arParams["ELEMENT_SORT_FIELD"] : $arParams["ELEMENT_SORT_FIELD2"],
	// "ELEMENT_SORT_ORDER2" => isset($alfaCSortToo) ? $arParams["ELEMENT_SORT_ORDER"] : $arParams["ELEMENT_SORT_ORDER2"],
	"PROPERTY_CODE" => $arParams["CATALOG_PROPERTY_CODE"],
	"PROPERTY_CODE_MOBILE" => $arParams["CATALOG_PROPERTY_CODE_MOBILE"],
	// "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
	// "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
	// "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
	// "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
	"INCLUDE_SUBSECTIONS" => 'Y', // $arParams["INCLUDE_SUBSECTIONS"],
	"BASKET_URL" => $arParams["BASKET_URL"],
	"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
	"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
	"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
	"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
	"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
	"FILTER_NAME" => "arrFilter",
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_FILTER" => $arParams["CACHE_FILTER"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"SET_TITLE" => 'N', // $arParams["SET_TITLE"],
	// "MESSAGE_404" => $arParams["~MESSAGE_404"],
	// "SET_STATUS_404" => $arParams["SET_STATUS_404"],
	// "SHOW_404" => $arParams["SHOW_404"],
	// "FILE_404" => $arParams["FILE_404"],
	"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
	"PAGE_ELEMENT_COUNT" => isset($alfaCOutput) ? $alfaCOutput : 20,
	"PRICE_CODE" => $arParams["~PRICE_CODE"],
	"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
	"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

	"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
	"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
	"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
	"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
	"PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"]) ? $arParams["PRODUCT_PROPERTIES"] : []),

	"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
	"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
	"PAGER_TITLE" => $arParams["PAGER_TITLE"],
	"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
	"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
	"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
	"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
	"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
	"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
	"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
	"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
	"LAZY_LOAD" => $arParams["LAZY_LOAD"],
	"MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
	"LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

	"OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"]) ? $arParams["OFFERS_CART_PROPERTIES"] : []),
	"OFFERS_FIELD_CODE" => $arParams["CATALOG_OFFERS_FIELD_CODE"],
	"OFFERS_PROPERTY_CODE" => (isset($arParams["CATALOG_OFFERS_PROPERTY_CODE"]) ? $arParams["CATALOG_OFFERS_PROPERTY_CODE"] : []),
	"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
	"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
	"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
	"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
    "OFFERS_LIMIT" => (isset($arParams["CATALOG_OFFERS_LIMIT"]) ? $arParams["CATALOG_OFFERS_LIMIT"] : 0),
    
	"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
	'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
	'CURRENCY_ID' => $arParams['CURRENCY_ID'],
	'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
	'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

	'LABEL_PROP' => $arParams['CATALOG_LABEL_PROP'],
	'LABEL_PROP_MOBILE' => $arParams['CATALOG_LABEL_PROP_MOBILE'],
	'LABEL_PROP_POSITION' => $arParams['CATALOG_LABEL_PROP_POSITION'],
	'ADD_PICT_PROP' => $arParams['CATALOG_ADD_PICT_PROP'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	// 'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
	'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
	'PRODUCT_ROW_VARIANTS' => $alfaTemplateRows ? $alfaTemplateRows : $arParams['LIST_PRODUCT_ROW_VARIANTS'],
	'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
	'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
	'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
	'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
	'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

	'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
	'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
	'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
	'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
	'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
	'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
	'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
	'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
	'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
	'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
	'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
	'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

	'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
	'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
	'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

	'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
	"ADD_SECTIONS_CHAIN" => 'N',// (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ""),
	'ADD_TO_BASKET_ACTION' => $basketAction,
	'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
	'COMPARE_PATH' => '',// $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
	'COMPARE_NAME' => $arParams['COMPARE_NAME'],
	'USE_COMPARE_LIST' => 'Y',
	'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
	'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
	'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),

	'SITE_LOCATION_ID' => SITE_LOCATION_ID,
	"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
	"FILL_ITEM_ALL_PRICES" => (is_array($arParams['PRICE_CODE']) && count($arParams['PRICE_CODE']) > 1) ? 'Y' :  $arParams['FILL_ITEM_ALL_PRICES'],

	"AJAX_ID" => $arParams['AJAX_ID'],
	"DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
	"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
	'COMPOSITE_FRAME' => 'Y',

	'IS_USE_CART' => $arParams['IS_USE_CART'],
	'PRICE_PROP' => $arParams['CATALOG_PRICE_PROP'],
	'DISCOUNT_PROP' => $arParams['CATALOG_DISCOUNT_PROP'],
	'CURRENCY_PROP' => $arParams['CATALOG_CURRENCY_PROP'],
	'PRICE_DECIMALS' => $arParams['CATALOG_PRICE_DECIMALS'],
	'SHOW_PARENT_DESCR' => 'Y',

	'TEMPLATE_VIEW' => $arParams['TEMPLATE_VIEW'],
	'GRID_RESPONSIVE_SETTINGS' => $arParams['~GRID_RESPONSIVE_SETTINGS'],
	'USE_VOTE_RATING' => $arParams['LIST_USE_VOTE_RATING'],
	'VOTE_DISPLAY_AS_RATING' => $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'],
	'SHOW_RATING' => $arParams['SHOW_RATING'],

	'USE_GIFTS' => $arParams['USE_GIFTS'],
	'USE_FAVORITE' => $arParams['USE_FAVORITE'],
	'FAVORITE_COUNT_PROP' => $arParams['FAVORITE_COUNT_PROP'],
	'SHOW_ARTNUMBER' => $arParams['SHOW_ARTNUMBER'],
	'ARTNUMBER_PROP' => $arParams['ARTNUMBER_PROP'],
	'OFFER_ARTNUMBER_PROP' => $arParams['OFFER_ARTNUMBER_PROP'],
	'OFFER_TREE_DROPDOWN_PROPS' => $arParams['OFFER_TREE_DROPDOWN_PROPS'],
	'RS_LAZY_IMAGES_USE' => $arParams['RS_LAZY_IMAGES_USE'],
	'BACKGROUND_COLOR' => $arParams['BACKGROUND_COLOR'],
	'RS_LIST_SECTION' => 'l_section',
	"BRAND_PROP" => $arParams["CATALOG_BRAND_PROP"],
	"BRAND_IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"BRAND_IBLOCK_BRAND_PROP" => $arParams["BRAND_PROP"],

	"SHOW_ERROR_SECTION_EMPTY" => "Y",
	"MESS_ERROR_SECTION_EMPTY" => "",
];
if ($request->get('section'))
{
	$componentSectionParams['SECTION_ID'] = $request->get('section');
}
else
{
	$componentSectionParams['SHOW_ALL_WO_SECTION'] = 'Y'; // set smart.filter + INCLUDE_SUBSECTIONS=Y = bug
	$componentSectionParams['BY_LINK'] = 'Y';
}


$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	$arParams['LIST_TEMPLATE'],
	$componentSectionParams,
	$component
);