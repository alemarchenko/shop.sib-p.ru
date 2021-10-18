<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Задать вопрос");
$APPLICATION->SetTitle("Корзина");

$APPLICATION->SetPageProperty("hide_inner_sidebar", "Y");
$APPLICATION->SetPageProperty("hide_outer_sidebar", "Y");
$APPLICATION->SetPageProperty("hide_section", "Y");

?>

<?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket", 
	"main", 
	array(
		"COMPONENT_TEMPLATE" => "main",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "PROPS",
			2 => "DELETE",
			3 => "DELAY",
			5 => "QUANTITY",
			4 => "PRICE",
			6 => "SUM",
		),
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"PATH_TO_ORDER" => "#SITE_DIR#personal/order/make/",
		"HIDE_COUPON" => "N",
		"QUANTITY_FLOAT" => "N",
		"PRICE_VAT_SHOW_VALUE" => "Y",
		"TEMPLATE_THEME" => "site",
		"SET_TITLE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"OFFERS_PROPS" => array(
			0 => "MEMORY_CARD",
			1 => "SMARTWATCH_SIZE",
			2 => "SIZE_BOOTS",
			3 => "CLOTHES_SIZE",
			4 => "SIZE_PURSE",
			5 => "COLOR_REF",
		),
		"USE_PREPAYMENT" => "N",
		"CORRECT_RATIO" => "N",
		"AUTO_CALCULATION" => "Y",
		"ACTION_VARIABLE" => "basketAction",
		"USE_GIFTS" => "Y",
		"GIFTS_PLACE" => "BOTTOM",
		"GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
		"GIFTS_SHOW_OLD_PRICE" => "N",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MESS_BTN_DETAIL" => "Подробнее",
		"GIFTS_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_CONVERT_CURRENCY" => "N",
		"GIFTS_HIDE_NOT_AVAILABLE" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"DEFERRED_REFRESH" => "N",
		"USE_DYNAMIC_SCROLL" => "Y",
		"SHOW_FILTER" => "Y",
		"SHOW_RESTORE" => "Y",
		"EMPTY_BASKET_HINT_PATH" => "#SITE_DIR#catalog/",
		"COLUMNS_LIST_EXT" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DELETE",
			2 => "DELAY",
			3 => "SUM",
		),
		"COLUMNS_LIST_MOBILE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DELETE",
			2 => "DELAY",
		),
		"TOTAL_BLOCK_DISPLAY" => array(
			0 => "bottom",
		),
		"DISPLAY_MODE" => "extended",
		"PRICE_DISPLAY_MODE" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
		"USE_PRICE_ANIMATION" => "Y",
		"LABEL_PROP" => "",
		"COMPATIBLE_MODE" => "N",
		"LABEL_PROP_MOBILE" => "",
		"LABEL_PROP_POSITION" => "",
		"ADDITIONAL_PICT_PROP_1" => "-",
		"ADDITIONAL_PICT_PROP_2" => "-",
		"BASKET_IMAGES_SCALING" => "adaptive"
	),
	false
);?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>