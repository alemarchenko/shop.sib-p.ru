<?
use Bitrix\Main\Localization\Loc,
		Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

if (!Bitrix\Main\Loader::includeModule('sale'))
	return;

if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$arShowFieldsList = array(
	"NONE" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_NONE"),
	"RS_AUTHOR_NAME" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_AUTHOR_NAME"),
	"RS_AUTHOR_EMAIL" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_AUTHOR_EMAIL"),
	"RS_AUTHOR_PHONE" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_AUTHOR_PHONE"),
);



if ($_REQUEST['src_site'] && is_string($_REQUEST['src_site']))
{
	$siteId = $_REQUEST['src_site'];
}
else
{
	$siteId = \CSite::GetDefSite();
}

if (!$siteId)
	$siteId = $arCurrentValues["ALFA_SITE_ID"];

$dbPerson = CSalePersonType::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array('ACTIVE' => 'Y', 'LID' => $siteId));
while ($arPerson = $dbPerson->GetNext())
{
	$arPers2Prop[$arPerson["ID"]] = $arPerson["NAME"];

	$dbProp = CSaleOrderProps::GetList(
		array("SORT" => "ASC", "NAME" => "ASC"),
		array("PERSON_TYPE_ID" => $arPerson["ID"])
	);
	while ($arProp = $dbProp->Fetch())
	{
		if ($arProp["IS_LOCATION"] == 'Y')
			continue;

		//$arPers2Prop[$arProp["CODE"]] = $arProp["NAME"];
		$allProps[$arPerson["ID"]][$arProp["ID"]] = $arProp["NAME"];
	}

}

if (!empty($arCurrentValues["ALFA_SALE_PERSON"]))
	$persProp = $arCurrentValues["ALFA_SALE_PERSON"];
else
	$persProp = is_array($arPers2Prop) ? key($arPers2Prop) : '';


$arComponentParameters = array(
	"GROUPS" => array(
		"FIELDS" => array(
			"NAME" =>Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_GROUP_FIELDS")
		),
		"ADDITIONAL_SETTINGS" => array(
			"NAME" =>Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_GROUP_ADDITIONAL")
		),
		/*"ORDER" => array(
			"NAME" =>Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_GROUP_ORDER")
		)*/
	),
	"PARAMETERS" => array(
		"ALFA_SALE_PERSON" => Array(
			"NAME" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_SALE_PERSON"), 
			"TYPE" => "LIST", 
			"VALUES" => $arPers2Prop,
			"PARENT" => "FIELDS",
			"REFRESH" => "Y",
		),
		"SHOW_FIELDS" => Array(
			"NAME" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_SHOW_FIELDS"), 
			"TYPE" => "LIST", 
			"MULTIPLE" => "Y", 
			"VALUES" => $allProps[$persProp],
			"PARENT" => "FIELDS",
		),
		"REQUIRED_FIELDS" => Array(
			"NAME" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_REQUIRED_FIELDS"), 
			"TYPE" => "LIST", 
			"MULTIPLE" => "Y", 
			"VALUES" => $allProps[$persProp], 
			"PARENT" => "FIELDS",
		),
		"ALFA_USE_CAPTCHA" => array(
			"NAME" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_USE_CAPTCHA"),
			"TYPE" => "CHECKBOX",
			"PARENT" => "FIELDS",
			"VALUE" => "Y",
		),
		"ALFA_MESSAGE_AGREE" => array(
			"NAME" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_MESSAGE_AGREE"),
			"TYPE" => "STRING",
			"PARENT" => "ADDITIONAL_SETTINGS",
			"DEFAULT" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_MESSAGE_AGREE_DEFAULT"),
		),
		"CACHE_TIME"  =>  Array(
			"PARENT" => "CACHE_SETTINGS",
			"DEFAULT" => 3600
		),
		"AJAX_MODE" => array(),
		"DATA" => array(
			"NAME" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_DATA"),
			"PARENT" => "ADDITIONAL_SETTINGS",
			"TYPE" => "STRING",
		),
/*		"EVENT_TYPE" => array(
		  "NAME" => Loc::getMessage("RS_DEVCOM_CRB1C_PARAMETERS_EVENT_TYPE"),
		  "PARENT" => "ORDER",
		  "TYPE" => "STRING",
		  "DEFAULT" => "REDSIGN_BUY_ONE_CLICK",
		),*/
		"ALFA_SITE_ID" => Array(
			"PARENT" => "FIELDS",
			"HIDDEN" => 'Y',
			"DEFAULT" => $siteId,
        ),
        "USER_CONSENT" => array(),
		"ALLOW_AUTO_REGISTER" => array(
			"NAME" => GetMessage("RS_DEVCOM_CRB1C_PARAMETERS_ALLOW_AUTO_REGISTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"PARENT" => "BASE",
		),
		"ALLOW_APPEND_ORDER" => array(
			"NAME" => GetMessage("RS_DEVCOM_CRB1C_PARAMETERS_ALLOW_APPEND_ORDER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "BASE",
		),
		"SEND_NEW_USER_NOTIFY" => array(
			"NAME" => GetMessage("RS_DEVCOM_CRB1C_PARAMETERS_SEND_NEW_USER_NOTIFY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "BASE",
		),
	)
);

?>
