<?$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"popup",
	Array(
		"CATEGORY_0" => array("iblock_catalog"),
		"CATEGORY_0_TITLE" => "Каталог",
		"CATEGORY_0_iblock_catalog" => array(
			0 => "#CATALOG_CATALOG_IBLOCK_ID#"
		),
		"CATEGORY_0_iblock_content" => array("all"),
		"CATEGORY_1" => array("iblock_content"),
		"CATEGORY_1_TITLE" => "Бренды",
		"CATEGORY_1_iblock_content" => array(
			"#CONTENT_BRANDS_IBLOCK_ID#",
			"all"
		),
		"CATEGORY_2" => array("iblock_content"),
		"CATEGORY_2_TITLE" => "Коллекции",
		"CATEGORY_2_iblock_content" => array(
			"#CONTENT_COLLECTION_IBLOCK_ID#"
		),
		"CATEGORY_3" => array("iblock_content"),
		"CATEGORY_3_TITLE" => "Разное",
		"CATEGORY_3_iblock_content" => array(
			0 => "#CONTENT_HISTORY_IBLOCK_ID#",
			1 => "#CONTENT_FAQ_IBLOCK_ID#",
			2 => "#CONTENT_SHOPS_IBLOCK_ID#",
			3 => "#CONTENT_NEWS_IBLOCK_ID#",
			4 => "#CONTENT_ARTICLES_IBLOCK_ID#",
			5 => "#CONTENT_SALE_PROMOTIONS_IBLOCK_ID#",
			6 => "#CONTENT_PROJECTS_IBLOCK_ID#",
			7 => "#CONTENT_STAFF_IBLOCK_ID#",
		),
		"CHECK_DATES" => "N",
		"CONTAINER_ID" => "popup-title-search",
		"CONVERT_CURRENCY" => "N",
		"INPUT_ID" => "popup-title-search-input",
		"NUM_CATEGORIES" => "4",
		"ORDER" => "date",
		"PAGE" => "#SITE_DIR#search/index.php",
		"PREVIEW_HEIGHT" => "75",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PREVIEW_WIDTH" => "75",
		"PRICE_CODE" => array("BASE","RETAIL","WHOLE","EXTPRICE"),
		"PRICE_VAT_INCLUDE" => "Y",
		"SHOW_INPUT" => "Y",
		"SHOW_OTHERS" => "N",
		"SHOW_PREVIEW" => "Y",
		"TOP_COUNT" => "5",
		"USE_LANGUAGE_GUESS" => "Y"
	)
);?>