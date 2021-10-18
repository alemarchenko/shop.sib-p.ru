<?
$IS_AJAX = false;
if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_REQUEST['AJAX_CALL']) && $_REQUEST['AJAX_CALL']=='Y') ) {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	$IS_AJAX = true;
} else {
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
	$APPLICATION->SetTitle("Заказать услугу");
}
?>
<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	"product", 
	array(
		"COMPONENT_TEMPLATE" => "product",
		"IBLOCK_ID" => "#FORMS_SERVICE_ORDER_IBLOCK_ID#",
		"USE_CAPTCHA" => "Y",
		"AJAX_MODE" => "Y",
		"SUCCESS_MESSAGE" => "Cпасибо, ваша заявка принята!",
		"EVENT_TYPE" => "RS_FORM_ORDER_SERVICE",
		"EMAIL_TO" => "#SHOP_EMAIL#",
		"IBLOCK_TYPE" => "forms",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"DISABLED_FIELDS" => array(
			0 => "SERVICE",
		),
		"ITEMS_IBLOCK_ID" => "",
		"NAME_PROPERTY_CODE" => "SERVICE",
		"FIELD_PARAMS" => "{\"34\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"35\":{\"validate\":\"email\",\"validatePattern\":\"\",\"mask\":\"\"},\"36\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"37\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"}}",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N"
	),
	false
);?>
<?if(!$IS_AJAX):?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
<?endif;?>