<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.recommended.products",
	"catalog",
	array(
		"ID" => $arResult['ID'],
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
		'PROPERTY_LINK' => $sPropCode,
		// 'PROPERTY_LINK' => (!empty($recommendedData['IBLOCK_LINK']) ? $recommendedData['IBLOCK_LINK'] : $recommendedData['ALL_LINK']),
		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'CACHE_FILTER' => $arParams['CACHE_FILTER'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
		'BASKET_URL' => $arParams['BASKET_URL'],
		'ACTION_VARIABLE' => (!empty($arParams['ACTION_VARIABLE']) ? $arParams['ACTION_VARIABLE'] : 'action').'_crp',
		'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
		'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
		'ADD_PROPERTIES_TO_BASKET' => (isset($arParams['ADD_PROPERTIES_TO_BASKET']) ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
		'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
		'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES']) ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
		'PAGE_ELEMENT_COUNT' => $arParams['PROPERTY_ELEMENT_LINE_COUNT'],
		'LINE_ELEMENT_COUNT' => $arParams['PROPERTY_ELEMENT_LINE_COUNT'],
		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
		'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
		'PRICE_CODE' => $arParams['PRICE_CODE'],
		'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
		'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
		'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
		'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
		'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
		'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
		'ADD_TO_BASKET_ACTION' => $basketAction,
		'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',

		'ELEMENT_SORT_FIELD' => $arParams['ELEMENT_SORT_FIELD'],
		'ELEMENT_SORT_ORDER' => $arParams['ELEMENT_SORT_ORDER'],
		'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
		'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],

		'SET_TITLE' => 'N',
		'SET_BROWSER_TITLE' => 'N',
		'SET_META_KEYWORDS' => 'N',
		'SET_META_DESCRIPTION' => 'N',
		'SET_LAST_MODIFIED' => 'N',
		'ADD_SECTIONS_CHAIN' => 'N',

		'HIDE_BLOCK_TITLE' => 'Y',
		'SHOW_NAME' => 'Y',
		'SHOW_IMAGE' => 'Y',

		'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
		'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
		'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
		'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
		'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
		'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

		'LABEL_PROP_MULTIPLE' => $arParams['LABEL_PROP'],
		'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
		'LABEL_PROP_POSITION' => $arParams['LIST_LABEL_PROP_POSITION'],


		'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
		'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
		'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

		'SHOW_PRODUCTS_'.$arParams['IBLOCK_ID'] => 'Y',
		'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
		'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
		'OFFERS_FIELD_CODE' => $arParams['LIST_OFFERS_FIELD_CODE'],
		'PROPERTY_CODE_'.$arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE'],
		'PROPERTY_CODE_MOBILE' => $arParams['LIST_PROPERTY_CODE_MOBILE'],
		'PROPERTY_CODE_'.$arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams['LIST_OFFERS_PROPERTY_CODE'],
		'CART_PROPERTIES_'.$arParams['IBLOCK_ID'] => $arParams['PRODUCT_PROPERTIES'],
		'CART_PROPERTIES_'.$arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams['OFFERS_CART_PROPERTIES'],
		'OFFER_TREE_PROPS_'.$arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams['OFFER_TREE_PROPS'],
		'ADDITIONAL_PICT_PROP_'.$arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP'],
		'ADDITIONAL_PICT_PROP_'.$arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams['OFFER_ADD_PICT_PROP'],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],

		'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
		'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
		'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

		'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
		'SITE_LOCATION_ID' => SITE_LOCATION_ID,
		"FILL_ITEM_ALL_PRICES" => (is_array($arParams['PRICE_CODE']) && count($arParams['PRICE_CODE']) > 1) ? 'Y' :  $arParams['FILL_ITEM_ALL_PRICES'],
		'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) && $arParams['DISPLAY_COMPARE'] ? 'Y' : 'N'),
		'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),
							

		// "AJAX_ID" => $arParams['AJAX_ID'],
		"DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		// 'COMPOSITE_FRAME' => 'Y',

		'IS_USE_CART' => $arParams['IS_USE_CART'],
		'PRICE_PROP' => $arParams['PRICE_PROP'],
		'DISCOUNT_PROP' => $arParams['DISCOUNT_PROP'],
		'CURRENCY_PROP' => $arParams['CURRENCY_PROP'],
		'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
		'SHOW_PARENT_DESCR' => 'Y',

		'TEMPLATE_VIEW' => $arParams['LIST_TEMPLATE_VIEW'],
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

		'SHOW_PARENT_TITLE' => 'N',
		'PARENT_TITLE' => $arResult['PROPERTIES'][$sPropCode]['NAME'],

		'PRODUCT_ROW_VARIANTS' => "[{'VARIANT':'11','BIG_DATA':false}]",
		'PAGE_ELEMENT_COUNT' => 5,
	),
	$component,
	array('HIDE_ICONS' => 'Y')
);?>
