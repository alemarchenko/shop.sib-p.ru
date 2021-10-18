<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Купить в 1 клик");
?>
<?$APPLICATION->IncludeComponent(
	"redsign:buy1click", 
	"catalog", 
	array(
		"COMPONENT_TEMPLATE" => "catalog",
		"AJAX_MODE" => "Y",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "3600",
		"ALFA_EMAIL_TO" => "#SHOP_EMAIL#",
		"ALFA_MESSAGE_AGREE" => "Спасибо, ваша заявка принята!",
		"DATA" => "",
		"SHOW_FIELDS" => array(
			0 => "1",
			1 => "3",
		),
		"REQUIRED_FIELDS" => array(
			0 => "3",
		),
		"ALFA_USE_CAPTCHA" => "Y",
		"EVENT_TYPE" => "REDSIGN_BUY1CLICK",
		"USER_ORDER" => "",
		"PHONE_ORDER" => "2",
		"EMAIL_ORDER" => "2",
		"NAME_ORDER" => "2",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"ALFA_SALE_PERSON" => "1",
		"ALFA_SITE_ID" => "s1",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"ALLOW_AUTO_REGISTER" => "Y",
		"ALLOW_APPEND_ORDER" => "Y",
		"SEND_NEW_USER_NOTIFY" => "Y"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>