<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arTemplateDescription = Array(
	"NAME" => 'rs_mm_index_multiple',
	"DESCRIPTION" => Loc::getMessage("ADV_RS_MM_INDEX_MINI_DESCRIPTION")
);
