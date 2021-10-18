<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Loader,
	\Bitrix\Main\Localization\Loc;


if (!Loader::includeModule("advertising")) {
	return;
}

$arTemplateParameters['PARAMETERS']['IMG'] = array(
	'NAME' => Loc::getMessage('ADV_RS_MM_INDEX_MINI_PARAMETER_IMG'),
	'TYPE' => 'IMAGE',
	'DEFAULT' => 'Y',
	'SORT' => 10
);

$arTemplateParameters["PARAMETERS"]["LINK_URL"] = array(
	"NAME" => Loc::getMessage("ADV_RS_MM_INDEX_MINI_PARAMETER_LINK_URL"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
	"SORT" => 20
);

$arTemplateParameters["PARAMETERS"]["LINK_TITLE"] = array(
	"NAME" => Loc::getMessage("ADV_RS_MM_INDEX_MINI_PARAMETER_LINK_TITLE"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
	"SORT" => 30
);

$arTemplateParameters["PARAMETERS"]["LINK_TARGET"] = array(
	"NAME" => Loc::getMessage("ADV_RS_MM_INDEX_MINI_PARAMETER_LINK_TARGET"),
	"TYPE" => "LIST",
	"VALUES" => array(
		'' => Loc::getMessage("ADV_RS_MM_INDEX_MINI_PARAMETER_NO"),
		'_self' => Loc::getMessage("ADV_RS_MM_INDEX_MINI_PARAMETER_LINK_TARGET_SELF"),
		'_blank' => Loc::getMessage("ADV_RS_MM_INDEX_MINI_PARAMETER_LINK_TARGET_BLANK"),
		'_parent' => Loc::getMessage("ADV_RS_MM_INDEX_MINI_PARAMETER_LINK_PARENT"),
		'_top' => Loc::getMessage("ADV_RS_MM_INDEX_MINI_PARAMETER_LINK_TARGET_TOP")
	),
	"DEFAULT" => "left",
	"SORT" => 40
);

$arTemplateParameters['SETTINGS']['MULTIPLE'] = 'Y';
