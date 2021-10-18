<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arTemplateParameters['RS_SHOW_EMPTY_ERROR'] = array(
	'NAME' => Loc::getMessage('RS_CS_PARAMETERS_SHOW_EMPTY_ERROR'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y'
);

if ($arCurrentValues['RS_SHOW_EMPTY_ERROR'] == 'Y')
{
	$arTemplateParameters['RS_EMPTY_ERROR_TITLE'] = array(
		'NAME' => Loc::getMessage('RS_CS_PARAMETERS_EMPTY_ERROR_TITLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => ''
	);

	$arTemplateParameters['RS_EMPTY_ERROR_DESC'] = array(
		'NAME' => Loc::getMessage('RS_CS_PARAMETERS_EMPTY_ERROR_DESC'),
		'TYPE' => 'STRING',
		'DEFAULT' => ''
	);

	$arTemplateParameters['RS_EMPTY_ERROR_BUTTON_TITLE'] = array(
		'NAME' => Loc::getMessage('RS_CS_PARAMETERS_EMPTY_ERROR_BUTTON_TITLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => ''
	);

	$arTemplateParameters['RS_EMPTY_ERROR_BUTTON_LINK'] = array(
		'NAME' => Loc::getMessage('RS_CS_PARAMETERS_EMPTY_ERROR_BUTTON_LINK'),
		'TYPE' => 'STRING',
		'DEFAULT' => ''
	);
}
