<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('redsign.megamart')) {
	return;
}

$arTemplateParameters['RS_TEMPLATE'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => Loc::getMessage('RS_NEWSLIST_TEMPLATE'),
	'TYPE' => 'LIST',
	'REFRESH' => 'Y',
	'VALUES' => array(
		'FROM_WIDGET' => Loc::getMessage('RS_NEWSLIST_TEMPLATE_FROM_WIDGET'),
		'SLIDER' => Loc::getMessage('RS_NEWSLIST_TEMPLATE_SLIDER'),
		'ABC' => Loc::getMessage('RS_NEWSLIST_TEMPLATE_ABC')
	),
	'DEFAULT' => 'FROM_WIDGET',
);

// ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSupport'));
\Redsign\Megamart\Layouts\Builder::createTemplateParams($arTemplateParameters, $arCurrentValues);
