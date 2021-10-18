<?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket.line", 
	"top", 
	array(
		"COMPONENT_TEMPLATE" => "top",
		"PATH_TO_BASKET" => "/personal/cart/",
		"PATH_TO_ORDER" => "/personal/order/make/",
		"SHOW_NUM_PRODUCTS" => "Y",
		"SHOW_TOTAL_PRICE" => "Y",
		"SHOW_EMPTY_VALUES" => "N",
		"SHOW_PERSONAL_LINK" => "N",
		"PATH_TO_PERSONAL" => "/personal/",
		"SHOW_AUTHOR" => "N",
		"PATH_TO_REGISTER" => "/login/",
		"PATH_TO_PROFILE" => "/personal/",
		"SHOW_PRODUCTS" => "Y",
		"POSITION_FIXED" => "N",
		"HIDE_ON_BASKET_PAGES" => "N",
		"SHOW_DELAY" => "N",
		"SHOW_NOTAVAIL" => "N",
		"SHOW_SUBSCRIBE" => "N",
		"SHOW_IMAGE" => "Y",
		"SHOW_PRICE" => "Y",
		"SHOW_SUMMARY" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>