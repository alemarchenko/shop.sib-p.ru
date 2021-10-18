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

if (!empty($arResult['PROPERTIES'][$sPropCode]['VALUE']))
{
	global $elementsFilter;

	$elementsFilter = array(
		'=ID' => $arResult['PROPERTIES'][$sPropCode]['VALUE'],
	);
}

$productRowVariants = $arParams['LIST_PRODUCT_ROW_VARIANTS'];
if (Loader::includeModule('redsign.megamart'))
{
	$productRowVariants = \Redsign\MegaMart\ElementListUtils::predictRowVariants(
			$arParams['PROPERTY_ELEMENT_LINE_COUNT'],
			$arParams['PROPERTY_ELEMENT_LINE_COUNT']
	);
}
?>
<?$APPLICATION->IncludeComponent(
	'bitrix:catalog.section',
	'catalog',
	array(
		'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
		'IBLOCK_ID' => $arResult['PROPERTIES'][$sPropCode]['LINK_IBLOCK_ID'],

		'PRODUCT_ROW_VARIANTS' => $productRowVariants,
		'PAGE_ELEMENT_COUNT' => $arParams['PROPERTY_ELEMENT_LINE_COUNT'],
		'LINE_ELEMENT_COUNT' => $arParams['PROPERTY_ELEMENT_LINE_COUNT'],

		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'CACHE_FILTER' => $arParams['CACHE_FILTER'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],

		'BY_LINK' => 'N',
		'DISPLAY_TOP_PAGER' => 'N',
		'DISPLAY_BOTTOM_PAGER' => 'N',
		'HIDE_SECTION_DESCRIPTION' => 'Y',
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
		'SHOW_ALL_WO_SECTION' => 'Y',

		'ELEMENT_SORT_FIELD' => $arParams['ELEMENT_SORT_FIELD'],
		'ELEMENT_SORT_ORDER' => $arParams['ELEMENT_SORT_ORDER'],
		'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
		'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],

		'FILTER_NAME' => 'elementsFilter',
		'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
		'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
		'BASKET_URL' => $arParams['BASKET_URL'],
		'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
		'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
		'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

		'SET_TITLE' => 'N',
		'SET_BROWSER_TITLE' => 'N',
		'SET_META_KEYWORDS' => 'N',
		'SET_META_DESCRIPTION' => 'N',
		'SET_LAST_MODIFIED' => 'N',
		'ADD_SECTIONS_CHAIN' => 'N',
		'PRICE_CODE' => $arParams['PRICE_CODE'],
		'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
		'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],

		'PROPERTY_CODE' => $arParams['LIST_PROPERTY_CODE'],
		'PROPERTY_CODE_MOBILE' => $arParams['LIST_PROPERTY_CODE_MOBILE'],

		'OFFERS_FIELD_CODE' => $arParams['LIST_OFFERS_FIELD_CODE'],
		'OFFERS_PROPERTY_CODE' => $arParams['LIST_OFFERS_PROPERTY_CODE'],
		'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],

		'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
		'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
		'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],

		'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
		'SLIDER_INTERVAL' => isset($arParams['SLIDER_INTERVAL']) ? $arParams['SLIDER_INTERVAL'] : '',
		'SLIDER_PROGRESS' => isset($arParams['SLIDER_PROGRESS']) ? $arParams['SLIDER_PROGRESS'] : '',

		'LABEL_PROP' => (isset($arParams['LABEL_PROP_MULTIPLE']) ? $arParams['LABEL_PROP_MULTIPLE'] : ''),
		'LABEL_PROP_MOBILE' => (isset($arParams['LABEL_PROP_MOBILE']) ? $arParams['LABEL_PROP_MOBILE'] : ''),
		'LABEL_PROP_POSITION' => (isset($arParams['LABEL_PROP_POSITION']) ? $arParams['LABEL_PROP_POSITION'] : ''),
		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],

		'SHOW_DISCOUNT_PERCENT' => (isset($arParams['SHOW_DISCOUNT_PERCENT']) ? $arParams['SHOW_DISCOUNT_PERCENT'] : ''),
		'DISCOUNT_PERCENT_POSITION' => (isset($arParams['DISCOUNT_PERCENT_POSITION']) ? $arParams['DISCOUNT_PERCENT_POSITION'] : ''),
		'SHOW_OLD_PRICE' => (isset($arParams['SHOW_OLD_PRICE']) ? $arParams['SHOW_OLD_PRICE'] : ''),
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],

		'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
		'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
		'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
		'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),

		'ADD_TO_BASKET_ACTION' => (isset($arParams['LIST_ADD_TO_BASKET_ACTION']) ? $arParams['LIST_ADD_TO_BASKET_ACTION'] : ''),
		'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
		'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) && $arParams['DISPLAY_COMPARE'] ? 'Y' : 'N'),
		'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),


		'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
		'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
		'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
		

		'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
		'SITE_LOCATION_ID' => defined('SITE_LOCATION_ID')?SITE_LOCATION_ID:'',
		'FILL_ITEM_ALL_PRICES' => (is_array($arParams['PRICE_CODE']) && count($arParams['PRICE_CODE']) > 1) ? 'Y' :  $arParams['FILL_ITEM_ALL_PRICES'],

		// 'AJAX_ID' => $arParams['AJAX_ID'],
		'DISPLAY_PREVIEW_TEXT' => $arParams['LIST_DISPLAY_PREVIEW_TEXT'],
		'PREVIEW_TRUNCATE_LEN' => $arParams['PREVIEW_TRUNCATE_LEN'],
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
		'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
		'SLIDER_SLIDE_COUNT' => $arParams['LIST_SLIDER_SLIDE_COUNT'],
	),
	$component,
	array('HIDE_ICONS' => 'Y')
);?>
