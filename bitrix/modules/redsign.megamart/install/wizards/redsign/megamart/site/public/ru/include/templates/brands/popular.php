<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"catalog",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"ARTNUMBER_PROP" => "-",
		"BACKGROUND_COLOR" => "-",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "#SITE_DIR#personal/cart/",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_PATH" => "",
		"COMPATIBLE_MODE" => "N",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_PROP" => "-",
		"CUSTOM_FILTER" => "",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISCOUNT_PROP" => "-",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "shows",
		"ELEMENT_SORT_FIELD2" => "sort",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FAVORITE_COUNT_PROP" => "-",
		"FILE_404" => "",
		"FILL_ITEM_ALL_PRICES" => "N",
		"FILTER_NAME" => $arParams['FILTER_NAME'],
		"FILTER_PROPS" => array(),
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"INCLUDE_SUBSECTIONS" => "Y",
		"LABEL_PROP" => array("DOWNPRICE_CATALOG_ITEM","NEW_CATALOG_ITEM","SALELEADER"),
		"LABEL_PROP_MOBILE" => array(),
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "3",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_ERROR_SECTION_EMPTY" => "В данном разделе элементов не найдено",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_CART_PROPERTIES" => array(),
		"OFFERS_FIELD_CODE" => array("",""),
		"OFFERS_LIMIT" => "5",
		"OFFERS_PROPERTY_CODE" => array("",""),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_ARTNUMBER_PROP" => "-",
		"OFFER_TREE_DROPDOWN_PROPS" => array(),
		"OFFER_TREE_PROPS" => array("COLOR_STRAP_REF","SIZE_PURSE","STRAP_SIZE","FASOVKA","LENGHT_METIZ","SMARTWATCH_CASE","MEMORY_CARD","COLOR_REF","SIZE_BOOTS","CLOTHES_SIZE","SMARTWATCH_STRAP","SMARTWATCH_SIZE","WHEEL_W","WHEEL_R","TYPE_R","TYRE_W","TYRE_H"),
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "10",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PRICE_CODE" => $arParams['PRICE_CODE'],
		"PRICE_DECIMALS" => "0",
		"PRICE_PROP" => "-",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "preview,props,sku",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PREVIEW" => "Y",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'10','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array("DOWNPRICE_CATALOG_ITEM","NEW_CATALOG_ITEM",""),
		"PROPERTY_CODE_MOBILE" => array(),
		"RCM_PROD_ID" => "",
		"RCM_TYPE" => "personal",
		"RS_LAZY_IMAGES_USE" => "FROM_MODULE",
		"RS_LIST_SECTION" => "l_section",
		"RS_LIST_SECTION_ADD_CONTAINER" => "Y",
		"RS_LIST_SECTION_BUTTON_LINK" => "",
		"RS_LIST_SECTION_BUTTON_NAME" => "",
		"RS_LIST_SECTION_SHOW_BUTTON" => "N",
		"RS_LIST_SECTION_SHOW_TITLE" => "Y",
		"RS_LIST_SECTION_TITLE" => $arParams['BLOCK_NAME'],
		"SECTION_CODE" => "",
		"SECTION_ID" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array("",""),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "N",
		"SHOW_404" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_ARTNUMBER" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_ERROR_SECTION_EMPTY" => "N",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_MAX_QUANTITY" => "M",
		"MESS_SHOW_MAX_QUANTITY" => "",
		"RELATIVE_QUANTITY_FACTOR" => "10",
		"MESS_RELATIVE_QUANTITY_MANY" => "Много",
		"MESS_RELATIVE_QUANTITY_FEW" => "Мало",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "Y",
		"SITE_LOCATION_ID" => defined("SITE_LOCATION_ID")?SITE_LOCATION_ID:"",
		"TEMPLATE_VIEW" => "popup",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_FAVORITE" => "Y",
		"USE_GIFTS" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_OWL" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"USE_VOTE_RATING" => "Y",
	),
	false
);?>