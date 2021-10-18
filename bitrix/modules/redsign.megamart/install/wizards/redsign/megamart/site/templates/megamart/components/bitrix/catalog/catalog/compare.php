<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
// $this->addExternalCss("/bitrix/css/main/bootstrap.css");

Loc::loadmessages(__FILE__);



$arCompNon = array();
$arCompNon = $_SESSION['CATALOG_COMPARE_LIST'][$arParams['IBLOCK_ID']]['ITEMS'];

if(!empty($arCompNon)){

$componentCompareParams = array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action")."_ccr",
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"FIELD_CODE" => $arParams["COMPARE_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["COMPARE_PROPERTY_CODE"],
		"NAME" => $arParams["COMPARE_NAME"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"PRICE_CODE" => $arParams["~PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"DISPLAY_ELEMENT_SELECT_BOX" => $arParams["DISPLAY_ELEMENT_SELECT_BOX"],
		"ELEMENT_SORT_FIELD_BOX" => $arParams["ELEMENT_SORT_FIELD_BOX"],
		"ELEMENT_SORT_ORDER_BOX" => $arParams["ELEMENT_SORT_ORDER_BOX"],
		"ELEMENT_SORT_FIELD_BOX2" => $arParams["ELEMENT_SORT_FIELD_BOX2"],
		"ELEMENT_SORT_ORDER_BOX2" => $arParams["ELEMENT_SORT_ORDER_BOX2"],
		"ELEMENT_SORT_FIELD" => $arParams["COMPARE_ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["COMPARE_ELEMENT_SORT_ORDER"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"OFFERS_FIELD_CODE" => $arParams["COMPARE_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["COMPARE_OFFERS_PROPERTY_CODE"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),

        'IS_USE_CART' => $arParams['IS_USE_CART'],
        'PRICE_PROP' => $arParams['PRICE_PROP'],
        'DISCOUNT_PROP' => $arParams['DISCOUNT_PROP'],
        'CURRENCY_PROP' => $arParams['CURRENCY_PROP'],
        'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
        
        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'USE_VOTE_RATING' => $arParams['LIST_USE_VOTE_RATING'],
		'VOTE_DISPLAY_AS_RATING' => $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'],
		'SHOW_RATING' => $arParams['SHOW_RATING'],
		
		'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
		'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
		'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
		'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
		'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
		'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
		
		'RS_LIST_SECTION' => 'l_section',

		// favorite
		'USE_FAVORITE' => $arParams['USE_FAVORITE'],
		'MESS_BTN_FAVORITE' => $arParams['MESS_BTN_FAVORITE'],
		'FAVORITE_COUNT_PROP' => $arParams['FAVORITE_COUNT_PROP'],
		'PRODUCT_PREVIEW' => 'N', // $arParams['PRODUCT_PREVIEW'],
);

if (isset($arParams['SHOW_MAX_QUANTITY']) && in_array($arParams['SHOW_MAX_QUANTITY'], array('Y', 'M')))
{
	$componentCompareParams['FIELD_CODE'][] = 'CATALOG_QUANTITY';
	$componentCompareParams['CAN_BUY'][] = 'CAN_BUY';
	$componentCompareParams['OFFERS_FIELD_CODE'][] = 'CATALOG_QUANTITY';
	$componentCompareParams['OFFERS_FIELD_CODE'][] = 'CATALOG_MEASURE';
	$componentCompareParams['OFFERS_FIELD_CODE'][] = 'CATALOG_QUANTITY_TRACE';
	$componentCompareParams['OFFERS_FIELD_CODE'][] = 'CATALOG_CAN_BUY_ZERO';
}

$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.result",
	"catalog",
	$componentCompareParams,
	$component,
	array("HIDE_ICONS" => "Y")
);
}
else{
	include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/include/compare/item_non.php';
}
$APPLICATION->AddChainItem(Loc::getMessage('RS_MM_BC_CATALOG_COMPARE_TITLE'), $APPLICATION->GetCurPage());
$APPLICATION->SetTitle(Loc::getMessage('RS_MM_BC_CATALOG_COMPARE_TITLE'));


$APPLICATION->SetPageProperty("hide_section", "Y");
$APPLICATION->SetPageProperty('hide_outer_sidebar', 'Y');
$APPLICATION->SetPageProperty('hide_inner_sidebar', 'Y');
$APPLICATION->SetPageProperty('wide_page', 'N');
