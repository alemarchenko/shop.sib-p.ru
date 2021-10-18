<?
$IS_AJAX = false;
if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_REQUEST['AJAX_CALL']) && $_REQUEST['AJAX_CALL']=='Y') ) {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	$IS_AJAX = true;
} else {
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
	$APPLICATION->SetTitle("Р—Р°РґР°С‚СЊ РІРѕРїСЂРѕСЃ");
}
?>

<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	"product", 
	array(
		"IBLOCK_ID" => "#FORMS_JOB_IBLOCK_ID#",
		"USE_CAPTCHA" => "Y",
		"AJAX_MODE" => "Y",
		"SUCCESS_MESSAGE" => "CРїР°СЃРёР±Рѕ, РІР°С€Р° Р·Р°СЏРІРєР° РїСЂРёРЅСЏС‚Р°!",
		"EVENT_TYPE" => "RS_FORM_JOB",
		"EMAIL_TO" => "#SHOP_EMAIL#",
		"COMPONENT_TEMPLATE" => "product",
		"IBLOCK_TYPE" => "forms",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"DISABLED_FIELDS" => array(
			0 => "VACANCY",
		),
		"ITEMS_IBLOCK_ID" => "",
		"NAME_PROPERTY_CODE" => "VACANCY",
		"FIELD_PARAMS" => "{\"40\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"41\":{\"validate\":\"email\",\"validatePattern\":\"\",\"mask\":\"\"},\"42\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"43\":{\"validate\":\"url\",\"validatePattern\":\"\",\"mask\":\"\"},\"44\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"}}",
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
