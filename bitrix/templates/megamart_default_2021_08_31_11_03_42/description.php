<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arTemplate = [
	'SORT' => '1',
	'TYPE' => '',
	'NAME' => Loc::getMessage('RS_MM_TEMPLATE_NAME'),
	'DESCRIPTION' => Loc::getMessage('RS_MM_TEMPLATE_DESC'),
	"EDITOR_STYLES" => array(
		getLocalPath('templates/megamart_default/assets/styles').'/editor.css'
	)
];
