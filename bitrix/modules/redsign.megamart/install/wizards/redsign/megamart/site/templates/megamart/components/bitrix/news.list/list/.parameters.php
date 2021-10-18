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
	'NAME' => Loc::getMessage('RS_NEWSLIST_TEMPLATE'),
	'TYPE' => 'LIST',
	'REFRESH' => 'Y',
	'VALUES' => array(
		'from_widget' => Loc::getMessage('RS_NEWSLIST_TEMPLATE_FROM_WIDGET'),
		'tile' => Loc::getMessage('RS_NEWSLIST_TEMPLATE_TILE'),
		'line' => Loc::getMessage('RS_NEWSLIST_TEMPLATE_LINE')
	),
	'DEFAULT' => 'from_widget',
);


if (
	in_array($arCurrentValues['RS_TEMPLATE'], ['tile']) ||
	$arCurrentValues['RS_TEMPLATE'] == 'from_widget' && in_array($arCurrentValues['RS_TEMPLATE_FROM_WIDGET'], ['tile'])
) {
	ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('gridSettings'));
}

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('lazy-images'));
\Redsign\Megamart\Layouts\Builder::createTemplateParams($arTemplateParameters, $arCurrentValues);
