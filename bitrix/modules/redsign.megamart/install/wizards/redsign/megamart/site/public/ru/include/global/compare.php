<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.list",
	"global",
	Array(
		"ACTION_VARIABLE" => "action",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"COMPARE_URL" => "#SITE_DIR#catalog/compare/",
		"DETAIL_URL" => "",
		"IBLOCK_ID" => "#CATALOG_CATALOG_IBLOCK_ID#",
		"IBLOCK_TYPE" => "catalog",
		"NAME" => "CATALOG_COMPARE_LIST",
		"POSITION" => "top left",
		"POSITION_FIXED" => "N",
		"PRODUCT_ID_VARIABLE" => "id"
	)
);?>