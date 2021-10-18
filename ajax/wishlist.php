<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
	"redsign:favorite.list",
	"global",
	array(
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"ACTION_VARIABLE" => "favaction",
		"PRODUCT_ID_VARIABLE" => "id",
		"FAVORITE_URL" => "/personal/wishlist/"
	),
	false
);

global $RS_FAVORITE_DATA; // from redsign:favorite.list -> global
global $arFavoriteFilter;
$arFavoriteFilter = array();
if (is_array($RS_FAVORITE_DATA['ITEMS']) && 0 < count($RS_FAVORITE_DATA['ITEMS']))
{
	foreach ($RS_FAVORITE_DATA['ITEMS'] as $item)
	{
		$arFavoriteFilter['=ID'][] = $item['ELEMENT_ID'];
	}
}
else
{
	$arFavoriteFilter['=ID'] = false;
}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"panel", 
	array(
		"COMPONENT_TEMPLATE" => "panel",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "8",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "arFavoriteFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"CUSTOM_FILTER" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"PAGE_ELEMENT_COUNT" => "16",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => "",
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_STRAP_REF",
			1 => "LENGHT_METIZ",
			2 => "STRAP_SIZE",
			3 => "SMARTWATCH_STRAP",
			4 => "SMARTWATCH_CASE",
			5 => "MEMORY_CARD",
			6 => "SMARTWATCH_SIZE",
			7 => "SIZE_BOOTS",
			8 => "CLOTHES_SIZE",
			9 => "SIZE_PURSE",
			10 => "FASOVKA",
			11 => "COLOR_REF",
			12 => "",
		),
		"BACKGROUND_IMAGE" => "-",
		"RS_LAZY_IMAGES_USE" => "FROM_MODULE",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
		"ENLARGE_PRODUCT" => "STRICT",
		"PRODUCT_BLOCKS" => array(
			0 => "sku",
		),
		"PRODUCT_BLOCKS_ORDER" => "sku,preview,props",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"LABEL_PROP" => array(
			0 => "DOWNPRICE_CATALOG_ITEM",
			1 => "NEW_CATALOG_ITEM",
			2 => "HIT_CATALOG_ITEM",
			3 => "SALELEADER",
			4 => "BASE",
		),
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_TREE_PROPS" => "",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_MAX_QUANTITY" => "M",
		"MESS_SHOW_MAX_QUANTITY" => "",
		"RELATIVE_QUANTITY_FACTOR" => "5",
		"MESS_RELATIVE_QUANTITY_MANY" => "Много",
		"MESS_RELATIVE_QUANTITY_FEW" => "Мало",
		"SHOW_ARTNUMBER" => "N",
		"ARTNUMBER_PROP" => "ARTNUMBER",
		"SHOW_ERROR_SECTION_EMPTY" => "Y",
		"MESS_ERROR_SECTION_EMPTY" => "Список избранного пуст",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SHOW_OLD_PRICE" => "Y",
		"TEMPLATE_VIEW" => "popup",
		"PRODUCT_PREVIEW" => "Y",
		"BACKGROUND_COLOR" => "-",
		"USE_VOTE_RATING" => "Y",
		"VOTE_DISPLAY_AS_RATING" => "rating",
		"RCM_TYPE" => "personal",
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"SHOW_FROM_SECTION" => "N",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"USE_OWL" => "N",
		"GRID_RESPONSIVE_SETTINGS" => "",
		"OFFER_TREE_DROPDOWN_PROPS" => array(
			0 => "MEMORY_CARD",
			1 => "SMARTWATCH_STRAP",
		),
		"OFFER_ARTNUMBER_PROP" => "ARTNUMBER",
		"FILL_ITEM_ALL_PRICES" => "N",
		"USE_GIFTS" => "Y",
		"SHOW_RATING" => "N",
		"USE_FAVORITE" => "Y",
		"FAVORITE_COUNT_PROP" => "FAVORITE_COUNT",
		"RS_LIST_SECTION" => "l_section",
		"RS_LIST_SECTION_SHOW_TITLE" => "N",
		"RS_LIST_SECTION_TITLE" => "",
		"RS_LIST_SECTION_SHOW_BUTTON" => "N",
		"RS_LIST_SECTION_BUTTON_NAME" => "",
		"RS_LIST_SECTION_BUTTON_LINK" => "",
		"RS_LIST_SECTION_ADD_CONTAINER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
			1 => "RETAIL",
			2 => "WHOLE",
			3 => "EXTPRICE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"BASKET_URL" => "/personal/cart/",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"DISPLAY_COMPARE" => "Y",
		"COMPARE_PATH" => "/catalog/compare/",
		"MESS_BTN_COMPARE" => "Сравнить",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"USE_ENHANCED_ECOMMERCE" => "Y",
		"DATA_LAYER_NAME" => "dataLayer",
		"BRAND_PROPERTY" => "BRAND_REF",
		"PAGER_TEMPLATE" => "bootstrap",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"FILTER_PROPS" => "",
		"LAZY_LOAD" => "Y",
		"MESS_BTN_LAZY_LOAD" => "Показать ещё",
		"LOAD_ON_SCROLL" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"COMPATIBLE_MODE" => "N",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"RS_SHOW_EMPTY_ERROR" => "Y",
		"RS_EMPTY_ERROR_TITLE" => "У вас нет товаров в избранном",
		"RS_EMPTY_ERROR_DESC" => "исправить это просто - посетите наш каталог продукции и наполните избранное нужными товарами :)",
		"RS_EMPTY_ERROR_BUTTON_TITLE" => "Каталог товаров",
		"RS_EMPTY_ERROR_BUTTON_LINK" => "/catalog/",
		"SITE_LOCATION_ID" => defined("SITE_LOCATION_ID")?SITE_LOCATION_ID:"",
	),
	false
);?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>