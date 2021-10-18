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

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

if (empty($arResult['ID']))
	return;

$obCache = new CPHPCache();
if ($obCache->InitCache(36000, serialize($arFilter) ,'/iblock/catalog'))
{
	$arCurIBlock = $obCache->GetVars();
}
elseif ($obCache->StartDataCache())
{
	$arCurIBlock = CIBlockPriceTools::GetOffersIBlock($arParams['IBLOCK_ID']);
	if(defined('BX_COMP_MANAGED_CACHE'))
	{
		global $CACHE_MANAGER;
		$CACHE_MANAGER->StartTagCache('/iblock/catalog');
		if ($arCurIBlock)
		{
			$CACHE_MANAGER->RegisterTag('iblock_id_'.$arParams['IBLOCK_ID']);
		}
		$CACHE_MANAGER->EndTagCache();
	}
	else
	{
		if (!$arCurIBlock)
		{
			$arCurIBlock = array();
		}
	}
	$obCache->EndDataCache($arCurIBlock);
}

if (empty($arCurIBlock) || !is_array($arCurIBlock) || empty($arCurIBlock['OFFERS_PROPERTY_ID']) || empty($arCurIBlock['OFFERS_IBLOCK_ID']))
	return;

global $modsFilter;
$modsFilter = [
	'=PROPERTY_'.$arCurIBlock['OFFERS_PROPERTY_ID'] => $arResult['ID']
];
$modsCount = count($arResult['OFFERS']);

$productRowVariants = \Bitrix\Main\Web\Json::encode(array_fill(
	0,
	$modsCount,
	[
		'VARIANT' => '9',
		'BIG_DATA' => false
	]
));
?>
<?$APPLICATION->IncludeComponent(
	'bitrix:catalog.section',
	'catalog',
	array(
		'IBLOCK_TYPE' => 'offers',
		'IBLOCK_ID' => $arCurIBlock['OFFERS_IBLOCK_ID'],

		'PRODUCT_ROW_VARIANTS' => $productRowVariants,
		'PRODUCT_ROWS_USE_COLLAPSE' => true,
		'PRODUCT_ROWS_COLLAPSE_SHOWED' => 5,
		'PAGE_ELEMENT_COUNT' => $modsCount,
		'LINE_ELEMENT_COUNT' => $modsCount < 5 ? $modsCount : 5,

		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'CACHE_FILTER' => $arParams['CACHE_FILTER'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],

		'BY_LINK' => 'Y',
		'DISPLAY_TOP_PAGER' => 'N',
		'DISPLAY_BOTTOM_PAGER' => 'N',
		'HIDE_SECTION_DESCRIPTION' => 'Y',
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
		// 'SHOW_ALL_WO_SECTION' => 'Y',

		'ELEMENT_SORT_FIELD' => $arParams['ELEMENT_SORT_FIELD'],
		'ELEMENT_SORT_ORDER' => $arParams['ELEMENT_SORT_ORDER'],
		'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
		'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],

		'FILTER_NAME' => 'modsFilter',
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

		'SHOW_SLIDER' => 'N',
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
		'SITE_LOCATION_ID' => SITE_LOCATION_ID,
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
		'SHOW_PARENT_DESCR' => 'N',

		'TEMPLATE_VIEW' => $arParams['LIST_TEMPLATE_VIEW'],
		'GRID_RESPONSIVE_SETTINGS' => $arParams['~GRID_RESPONSIVE_SETTINGS'],
		'USE_VOTE_RATING' => 'N', // $arParams['USE_VOTE_RATING'],
		'VOTE_DISPLAY_AS_RATING' => $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'],
		'SHOW_RATING' => 'N', // $arParams['SHOW_RATING'],

		'USE_GIFTS' => 'N', // $arParams['USE_GIFTS'],
		'USE_FAVORITE' => 'N', // $arParams['USE_FAVORITE'],
		'FAVORITE_COUNT_PROP' => 'N', // $arParams['FAVORITE_COUNT_PROP'],
		'SHOW_ARTNUMBER' => 'N', // $arParams['SHOW_ARTNUMBER'],
		'ARTNUMBER_PROP' => 'N', // $arParams['ARTNUMBER_PROP'],
		'OFFER_ARTNUMBER_PROP' => 'N', // $arParams['OFFER_ARTNUMBER_PROP'],
		'OFFER_TREE_DROPDOWN_PROPS' => 'N', // $arParams['OFFER_TREE_DROPDOWN_PROPS'],
		'RS_LAZY_IMAGES_USE' => $arParams['RS_LAZY_IMAGES_USE'],
		'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
		'SLIDER_SLIDE_COUNT' => $arParams['LIST_SLIDER_SLIDE_COUNT'],

		// 'HIDE_DETAIL_LINKS' => 'Y',
		// 'PRODUCT_PREVIEW' => 'N',
		// 'DISPLAY_COMPARE' => 'N',
	),
	$component,
	array('HIDE_ICONS' => 'Y')
);?>
