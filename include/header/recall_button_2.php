<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"inheader_button", 
	array(
		"RS_TEMPLATE" => "header",
		"AREA_FILE_SHOW" => "file",
		"PATH" => "/include/empty.php",
		"COMPONENT_TEMPLATE" => "inheader_button",
		"TITLE" => "Заказать звонок",
		"LINK" => "/forms/recall/",
		"IS_POPUP" => "Y",
		"EDIT_TEMPLATE" => "",
		"COLOR" => "outline-secondary"
	),
	false
);?>