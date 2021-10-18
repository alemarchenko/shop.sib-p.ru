<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
	"NAME" => Loc::getMessage("ALFA_COM_HCARD_NAME"),
	"DESCRIPTION" => Loc::getMessage("ALFA_COM_HCARD_DESCR"),
	"ICON" => "",
	"PATH" => array(
		"ID" => "alfa_com",
		"SORT" => 2000,
	),
);