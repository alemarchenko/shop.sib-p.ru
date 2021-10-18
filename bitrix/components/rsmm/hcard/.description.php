<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
	"NAME" => Loc::getMessage("RS_MM_COMP_HCARD_TITLE"),
	"DESCRIPTION" => Loc::getMessage("RS_MM_COMP_HCARD_DESCR"),
	"ICON" => "",
	"PATH" => array(
		"ID" => Loc::getMessage("RS_MM_COMP_TITLE"),
        "NAME" => Loc::getMessage("RS_MM_COMP_TITLE"),
	),
);