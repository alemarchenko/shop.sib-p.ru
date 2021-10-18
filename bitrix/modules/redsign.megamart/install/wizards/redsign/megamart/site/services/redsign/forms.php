<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Localization\Loc;

global $DB, $DBType, $APPLICATION;

if (!function_exists('RSMegamart_addEV'))
{
	function RSMegamart_addEV($arEventFields=array())
	{
		global $DB;
		$EventTypeID = 0;
		$et = new CEventType;
		$EventTypeID = $et->Add($arEventFields);
		return $EventTypeID;
	}
}

$arData = array(
	'RS_FORM_ASK',
	'RS_FORM_FAQ',
	'RS_FORM_RECALL',
	'RS_FORM_ORDER_SERVICE',
	'RS_FORM_PRODUCT_ASK',
	'RS_FORM_REVIEW',
	'RS_FORM_STAFF_ASK',
	'RS_MM_BONUS_ACCOUNT_TOP_UP',
);

$arSites = array();
$rsSites = CSite::GetList($by="sort", $order="desc", array());
while ($arSite = $rsSites->Fetch())
{
	$arSites[] = $arSite['LID'];
}

if (is_array($arData) && count($arData) > 0)
{
	$ev = new CEventMessage;

	foreach($arData as $EVENT_TYPE)
	{
		$EventTypeID = 0;
		$arEventFields = array(
			'LID'		   => 'ru',
			'EVENT_NAME'	=> $EVENT_TYPE,
			'NAME'		  => GetMessage('RS_EVENT_NAME_'.$EVENT_TYPE),
			'DESCRIPTION'   => GetMessage('RS_EVENT_DESCRIPTION_'.$EVENT_TYPE),
		);
		$EventTypeID = RSMegamart_addEV($arEventFields);
		if ($EventTypeID > 0)
		{
			$arTemplate = array(
				'ACTIVE' 		=> 'Y',
				'EVENT_NAME' 	=> $EVENT_TYPE,
				'LID'			=> $arSites,
				'EMAIL_FROM'	=> '#DEFAULT_EMAIL_FROM#',
				'EMAIL_TO'		=> '#EMAIL_TO#',
				'BCC'			=> '',
				'SUBJECT'		=> GetMessage('RS_TEMPLATE_SUBJECT_'.$EVENT_TYPE),
				'BODY_TYPE'		=> 'text',
				'MESSAGE'		=> GetMessage('RS_TEMPLATE_MESSAGE_'.$EVENT_TYPE),
			);
			$EventTemplateID = $ev->Add($arTemplate);
		}
	}

}