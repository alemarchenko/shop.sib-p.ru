<div class="bg-light mb-1 py-4 px-2">
<?$APPLICATION->IncludeComponent(
	"redsign:catalog.sorter",
	"catalog",
	Array(
		"ALFA_ACTION_PARAM_NAME" => "alfaction",
		"ALFA_ACTION_PARAM_VALUE" => "alfavalue",
		"ALFA_CHOSE_TEMPLATES_SHOW" => "Y",
		
		"ALFA_OUTPUT_OF" => array("12", "20", "32", '48'),
		"ALFA_OUTPUT_OF_DEFAULT" => "20",
		"ALFA_OUTPUT_OF_SHOW" => "Y",
		"ALFA_OUTPUT_OF_SHOW_ALL" => "N",
		
		"ALFA_SHORT_SORTER" => "N",
		"ALFA_SORT_BY_DEFAULT" => "sort_asc",
		"ALFA_SORT_BY_NAME" => $arParams['SORT_BY_NAME'],
		"ALFA_SORT_BY_SHOW" => "Y",
		"TARGET_ID" => $arParams['AJAX_ID'],
		"TEMPLATE_AJAXID" => "catalog",
		"ALFA_CNT_TEMPLATES" => "3",
		"ALFA_CNT_TEMPLATES_0" => "Список",
		"ALFA_CNT_TEMPLATES_1" => "Галерея",
		"ALFA_CNT_TEMPLATES_2" => "Подробно",
		"ALFA_CNT_TEMPLATES_NAME_0" => "view-line",
		"ALFA_CNT_TEMPLATES_NAME_1" => "view-card",
		"ALFA_CNT_TEMPLATES_NAME_2" => "view-list",
		"ALFA_DEFAULT_TEMPLATE" => "view-card",
	)
);?>
</div>