<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Полезная информация");
?><?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"list", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_CONTAINER" => "Y",
		"ADD_SECTION" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"GRID_RESPONSIVE_SETTINGS" => "{\"xxs\":{\"items\":\"1\"},\"xs\":{\"items\":\"2\"},\"sm\":{\"items\":\"2\"},\"md\":{\"items\":\"2\"},\"lg\":{\"items\":\"3\"},\"xl\":{\"items\":\"3\"}}",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#CONTENT_NEWS_IBLOCK_ID#",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "6",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "bootstrap",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "150",
		"PROPERTY_CODE" => array(
			0 => "STICKER",
			1 => "SLOGAN",
			2 => "",
		),
		"RS_TEMPLATE" => "tile",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_PARENT_TITLE" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "list",
		"PARENT_TITLE" => "Новости",
		"BLOCK_NAME_IS_LINK" => "N",
		"RS_LIST_SECTION" => "l_section",
		"RS_LIST_SECTION_ADD_CONTAINER" => "N",
		"RS_LIST_SECTION_SHOW_TITLE" => "Y",
		"RS_LIST_SECTION_TITLE" => "Новости",
		"RS_LIST_SECTION_SHOW_BUTTON" => "Y",
		"RS_LIST_SECTION_BUTTON_NAME" => "Посмотреть все",
		"RS_LIST_SECTION_BUTTON_LINK" => "#SITE_DIR#news/",
		"USE_HEADER" => "Y"
	),
	false
);?><?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"list", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_CONTAINER" => "Y",
		"ADD_SECTION" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"GRID_RESPONSIVE_SETTINGS" => "{\"xxs\":{\"items\":\"1\"},\"xs\":{\"items\":\"2\"},\"sm\":{\"items\":\"2\"},\"md\":{\"items\":\"2\"},\"lg\":{\"items\":\"3\"},\"xl\":{\"items\":\"3\"}}",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#CONTENT_ARTICLES_IBLOCK_ID#",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "6",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "bootstrap",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "150",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "STICKER",
			2 => "SLOGAN",
			3 => "",
		),
		"RS_TEMPLATE" => "tile",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_PARENT_TITLE" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "list",
		"PARENT_TITLE" => "Акции",
		"BLOCK_NAME_IS_LINK" => "N",
		"RS_LIST_SECTION" => "l_section",
		"RS_LIST_SECTION_ADD_CONTAINER" => "Y",
		"RS_LIST_SECTION_SHOW_TITLE" => "Y",
		"RS_LIST_SECTION_TITLE" => "Статьи",
		"RS_LIST_SECTION_SHOW_BUTTON" => "Y",
		"RS_LIST_SECTION_BUTTON_NAME" => "Посмотреть все",
		"RS_LIST_SECTION_BUTTON_LINK" => "#SITE_DIR#articles/",
		"USE_HEADER" => "Y"
	),
	false
);?><?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"list", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_CONTAINER" => "Y",
		"ADD_SECTION" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"GRID_RESPONSIVE_SETTINGS" => "{\"xxs\":{\"items\":\"1\"},\"xs\":{\"items\":\"2\"},\"sm\":{\"items\":\"2\"},\"md\":{\"items\":\"2\"},\"lg\":{\"items\":\"3\"},\"xl\":{\"items\":\"3\"}}",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#CONTENT_SALE_PROMOTIONS_IBLOCK_ID#",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "6",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "bootstrap",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "150",
		"PROPERTY_CODE" => array(
			0 => "STICKER",
			1 => "SLOGAN",
			2 => "",
		),
		"RS_TEMPLATE" => "tile",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_PARENT_TITLE" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "list",
		"PARENT_TITLE" => "Новости",
		"BLOCK_NAME_IS_LINK" => "N",
		"RS_LIST_SECTION" => "l_section",
		"RS_LIST_SECTION_ADD_CONTAINER" => "Y",
		"RS_LIST_SECTION_SHOW_TITLE" => "Y",
		"RS_LIST_SECTION_TITLE" => "Акции",
		"RS_LIST_SECTION_SHOW_BUTTON" => "Y",
		"RS_LIST_SECTION_BUTTON_NAME" => "Посмотреть все",
		"RS_LIST_SECTION_BUTTON_LINK" => "#SITE_DIR#sale-promotions/",
		"USE_HEADER" => "Y"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>