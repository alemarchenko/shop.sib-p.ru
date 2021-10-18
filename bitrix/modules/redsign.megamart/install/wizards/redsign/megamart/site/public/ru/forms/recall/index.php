<?
$IS_AJAX = false;
if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_REQUEST['AJAX_CALL']) && $_REQUEST['AJAX_CALL']=='Y') ) {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	$IS_AJAX = true;
} else {
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
	$APPLICATION->SetTitle("Задать вопрос");
}
?>
<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	"popup", 
	array(
		"COMPONENT_TEMPLATE" => "popup",
		"IBLOCK_ID" => "#FORMS_RECALL_IBLOCK_ID#",
		"USE_CAPTCHA" => "Y",
		"AJAX_MODE" => "Y",
		"SUCCESS_MESSAGE" => "Cпасибо, ваша заявка принята!",
		"EVENT_TYPE" => "RS_FORM_RECALL",
		"EMAIL_TO" => "#SHOP_EMAIL#",
		"IBLOCK_TYPE" => "forms",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"FIELD_PARAMS" => "{\"382\":{\"validate\":\"None\",\"validatePattern\":\"\",\"mask\":\"\"},\"383\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"+{7} (000) 000-00-00\"}}",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_B24_CRM_FORM" => "Y",
		"B24_CRM_FORM_ID" => "3",
		"B24_CRM_FORM_SEC" => "cl5lo1"
	),
	false
);?>
<?if(!$IS_AJAX):?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
<?endif;?>