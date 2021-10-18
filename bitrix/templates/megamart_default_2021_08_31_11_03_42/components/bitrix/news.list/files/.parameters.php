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

$arTemplateParameters['RS_TEMPLATE'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => Loc::getMessage('RS_NL_FILES_TEMPLATE'),
	'TYPE' => 'LIST',
	'REFRESH' => 'Y',
	'VALUES' => array(
		'from_widget' => Loc::getMessage('RS_NL_FILES_TEMPLATE_FROM_WIDGET'),
		'tile' => Loc::getMessage('RS_NL_FILES_TEMPLATE_COL'),
		'line' => Loc::getMessage('RS_NL_FILES_TEMPLATE_LINE')
	),
	'DEFAULT' => 'from_widget',
);


ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('lazy-images'));
\Redsign\Megamart\Layouts\Builder::createTemplateParams($arTemplateParameters, $arCurrentValues);
