<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('redsign.megamart') || !Loader::includeModule('redsign.devfunc')) {
	return;
}

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);


$arTemplateParameters['NOTE_PROP'] = array(
	'NAME' => Loc::getMessage('RS.NOTE_PROP'),
	'TYPE' => 'LIST',
	'VALUES' => $listProp['SNL'],
);

$arTemplateParameters['MESS_BTN_FILTER_ALL'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('RS_MM_MESS_BTN_FILTER_ALL'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('RS_MM_MESS_BTN_FILTER_ALL_DEFAULT')
);