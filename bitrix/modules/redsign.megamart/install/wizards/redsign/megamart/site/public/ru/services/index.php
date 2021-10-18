<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Услуги");
?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog",
	"catalog",
	array(
		"COMPONENT_TEMPLATE" => "catalog",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "#CATALOG_SERVICES_IBLOCK_ID#",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "L",
		"USE_BRANDS" => "Y",
		"BRAND_PROP" => "-",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => array(
			0 => "NEW_CATALOG_ITEM",
			1 => "SPECIALOFFER",
		),
		"LABEL_PROP_MOBILE" => array(
		),
		"PRICE_PROP" => "PRICE",
		"DISCOUNT_PROP" => "DISCOUNT",
		"CURRENCY_PROP" => "CURRENCY",
		"PRICE_DECIMALS" => "0",
		"SHOW_OLD_PRICE" => "Y",
		"PRODUCT_PREVIEW" => "Y",
		"SHOW_ARTNUMBER" => "Y",
		"ARTNUMBER_PROP" => "-",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"SIDEBAR_OUTER_PATH" => "",
		"SIDEBAR_INNER_PATH" => "",
		"LIST_BACKGROUND_COLOR" => "-",
		"RS_LAZY_IMAGES_USE" => "FROM_MODULE",
		"PRODUCT_DEALS_SHOW" => "Y",
		"PRODUCT_DEALS_USER_FIELDS" => "-",
		"PRODUCT_DEALS_PROP" => "-",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "#SITE_DIR#services/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"DETAIL_STRICT_SECTION_CHECK" => "Y",
		"SET_LAST_MODIFIED" => "Y",
		"SET_TITLE" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"USE_SALE_BESTSELLERS" => "Y",
		"USE_SHARE" => "Y",
		"SOCIAL_COUNTER" => "N",
		"SOCIAL_COPY" => "first",
		"SOCIAL_LIMIT" => "",
		"SOCIAL_SIZE" => "m",
		"USE_FAVORITE" => "N",
		"USE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_COMPARE" => "N",
		"PRICE_CODE" => array(
			0 => "RETAIL",
			1 => "WHOLE",
			2 => "EXTPRICE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "N",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "#SITE_DIR##CART_PATH#",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"PRODUCT_PROPERTIES" => array(
		),
		"SECTION_ADD_TO_BASKET_ACTION" => "REQUEST",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "REQUEST",
		),
		"LINK_BTN_REQUEST" => "#SITE_DIR#forms/service-order/?element_id=#ELEMENT_ID#",
		"MESS_BTN_REQUEST" => "Заказать",
		"DETAIL_ADD_TO_BASKET_ACTION_PRIMARY" => array(
			0 => "REQUEST",
		),
		"SEARCH_PAGE_RESULT_COUNT" => "50",
		"SEARCH_RESTART" => "N",
		"SEARCH_NO_WORD_LOGIC" => "Y",
		"SEARCH_USE_LANGUAGE_GUESS" => "Y",
		"SEARCH_CHECK_DATES" => "Y",
		"SHOW_TOP_ELEMENTS" => "N",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "2",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"CATALOG_VIEW_MODE" => "VIEW_ELEMENTS",
		"SIDEBAR_OUTER_SECTIONS_SHOW" => "N",
		"SIDEBAR_INNER_SECTIONS_SHOW" => "N",
		"PAGE_ELEMENT_COUNT" => "30",
		"LINE_ELEMENT_COUNT" => "3",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"LIST_PROPERTY_CODE" => array(
			0 => "NEW_CATALOG_ITEM",
			1 => "SPECIALOFFER",
			2 => "",
		),
		"LIST_PROPERTY_CODE_MOBILE" => array(
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"SHOW_SECTION_DESCRIPTION" => "top",
		"LIST_PRODUCT_BLOCKS_ORDER" => "props",
		"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"LIST_ENLARGE_PRODUCT" => "STRICT",
		"LIST_USE_VOTE_RATING" => "N",
		"TEMPLATE_VIEW" => "default",
		"SIDEBAR_OUTER_SECTION_SHOW" => "Y",
		"SIDEBAR_INNER_SECTION_SHOW" => "N",
		"LIST_SHOW_VIEWED" => "N",
		"LIST_TEMPLATE" => "catalog",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_SET_CANONICAL_URL" => "Y",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"SHOW_DEACTIVATED" => "N",
		"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => array(
		),
		"DETAIL_USE_VOTE_RATING" => "N",
		"DETAIL_USE_COMMENTS" => "Y",
		"DETAIL_BLOG_USE" => "Y",
		"DETAIL_BLOG_URL" => "catalog_comments",
		"DETAIL_BLOG_EMAIL_NOTIFY" => "N",
		"DETAIL_VK_USE" => "Y",
		"DETAIL_VK_API_ID" => "API_ID",
		"DETAIL_FB_USE" => "Y",
		"DETAIL_FB_APP_ID" => "",
		"DETAIL_BRAND_USE" => "N",
		"DETAIL_PRODUCT_INFO_BLOCK_ORDER" => "id-rate-stock-brand,preview,props,price,buttons,deals,delivery",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "S",
		"DETAIL_SHOW_POPULAR" => "Y",
		"DETAIL_SHOW_VIEWED" => "Y",
		"DETAIL_TAB_PROPERTIES" => array(
			0 => "PRICE_TABLE",
		),
		"DETAIL_TABS" => array(
			0 => "detail",
			1 => "props",
			2 => "prop_PRICE_TABLE",
		),
		"DETAIL_TABS_ORDER" => "detail,props,prop_PRICE_TABLE",
		"DETAIL_BLOCK_LINES_PROPERTIES" => array(
			0 => "HTML_VIDEO",
		),
		"DETAIL_BLOCK_LINES" => array(
			0 => "prop_HTML_VIDEO",
		),
		"DETAIL_BLOCK_LINES_ORDER" => "prop_HTML_VIDEO",
		"MESS_DESCRIPTION_TAB" => "Описание",
		"MESS_PROPERTIES_TAB" => "Характеристики",
		"DETAIL_TEMPLATE" => "catalog",
		"DETAIL_SOCIAL_SERVICES" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_DELIVERY_PAYMENT_INFO" => "N",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_GIFTS_DETAIL" => "N",
		"USE_GIFTS_SECTION" => "N",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "N",
		"USE_STORE" => "N",
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "personal",
		"USE_ENHANCED_ECOMMERCE" => "Y",
		"DATA_LAYER_NAME" => "dataLayer",
		"BRAND_PROPERTY" => "-",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"LAZY_LOAD" => "Y",
		"MESS_BTN_LAZY_LOAD" => "Показать ещё",
		"LOAD_ON_SCROLL" => "N",
		"USE_SORTER" => "Y",
		"SORTER_TEMPLATE" => ".default",
		"SORTER_ACTION_PARAM_NAME" => "alfaction",
		"SORTER_ACTION_PARAM_VALUE" => "alfavalue",
		"SORTER_CHOSE_TEMPLATES_SHOW" => "Y",
		"SORTER_CNT_TEMPLATES" => "2",
		"SORTER_CNT_TEMPLATES_0" => "Список",
		"SORTER_CNT_TEMPLATES_NAME_0" => "view-line",
		"SORTER_CNT_TEMPLATES_1" => "Галерея",
		"SORTER_CNT_TEMPLATES_NAME_1" => "view-card",
		"SORTER_DEFAULT_TEMPLATE" => "view-line",
		"SORTER_SORT_BY_SHOW" => "Y",
		"SORTER_SORT_BY_NAME" => array(
			0 => "sort",
			1 => "name",
			2 => "PROPERTY_PRICE",
			3 => "",
		),
		"SORTER_SORT_BY_DEFAULT" => "sort_asc",
		"SORTER_OUTPUT_OF_SHOW" => "Y",
		"SORTER_OUTPUT_OF" => array(
			0 => "30",
			1 => "60",
			2 => "90",
			3 => "",
		),
		"SORTER_OUTPUT_OF_DEFAULT" => "30",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"COMPATIBLE_MODE" => "N",
		"USE_ELEMENT_COUNTER" => "Y",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"DETAIL_BACKGROUND_COLOR" => "-",
		"SHOW_SECTIONS_LIST" => "N",
		"BUY_ON_CAN_BUY" => "Y",
		"USE_WIDGET_PARAMETERS" => "N",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"compare" => "compare/",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>