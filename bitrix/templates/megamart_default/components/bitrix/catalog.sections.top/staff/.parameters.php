<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('redsign.megamart') || !Loader::includeModule('redsign.devfunc')) {
	return;
}

Loc::loadMessages(__FILE__);

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

$arTemplateParameters['RS_TEMPLATE'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => Loc::getMessage('RS_STAFF_TEMPLATE'),
	'TYPE' => 'LIST',
	'VALUES' => array(
		'from_widget' => Loc::getMessage('RS_STAFF_TEMPLATE_FROM_WIDGET'),
		'type1' => Loc::getMessage('RS_STAFF_TEMPLATE_TYPE1'),
		'type2' => Loc::getMessage('RS_STAFF_TEMPLATE_TYPE2')
	),
	'DEFAULT' => 'from_widget',
);

$arTemplateParameters['SHOW_DESCRIPTION'] = array(
	'PARENT' => 'VISUAL',
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'NAME' => Loc::getMessage('RS_SHOW_DESCRIPTION')
);

$arTemplateParameters['ASK_LINK'] = array(
	'NAME' => Loc::getMessage('RS_ASK_LINK'),
	'TYPE' => 'STRING',
	'DEFAULT' => '/include/forms/ask_staff/?element_id=#ELEMENT_ID#',
);
