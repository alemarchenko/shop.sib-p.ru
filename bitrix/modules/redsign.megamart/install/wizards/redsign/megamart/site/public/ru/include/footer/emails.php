<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"email", 
	array(
		"RS_TEMPLATE" => "compact",
		"AREA_FILE_SHOW" => "file",
		"PATH" => "#SITE_DIR#include/empty.php",
		"IGNORE_MULTIREGIONALITY" => "N",
		"GET_FROM" => "module",
		"EDIT_TEMPLATE" => ""
	),
	false
);?>