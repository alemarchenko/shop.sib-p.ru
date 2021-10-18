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

$defaultValue = array('-' => Loc::getMessage('RS_MM_UNDEFINED'));
$arProperties = array(
	'F' => array(),
	'SNL' => array(),
);
if (Loader::includeModule('redsign.devfunc')) {
	$arProperties = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);
}

$arTemplateParameters['PROP_AUTHOR_PHOTO'] = array(
	'TYPE' => 'LIST',
	'NAME' => Loc::getMessage('RS_MM_PARAMETERS_PROP_AUTHOR_PHOTO'),
	'TYPE' => 'LIST',
	'VALUES' => $defaultValue + $arProperties['F'],
	'DEFAULT' => '-',
);
$arTemplateParameters['PROP_AUTHOR_NAME'] = array(
	'TYPE' => 'LIST',
	'NAME' => Loc::getMessage('RS_MM_PARAMETERS_PROP_AUTHOR_NAME'),
	'TYPE' => 'LIST',
	'VALUES' => $defaultValue + $arProperties['SNL'],
	'DEFAULT' => '-',
);
$arTemplateParameters['PROP_AUTHOR_POSITION'] = array(
	'TYPE' => 'LIST',
	'NAME' => Loc::getMessage('RS_MM_PARAMETERS_PROP_AUTHOR_POSITION'),
	'TYPE' => 'LIST',
	'VALUES' => $defaultValue + $arProperties['SNL'],
	'DEFAULT' => '-',
);
$arTemplateParameters['PROP_REVIEW'] = array(
	'TYPE' => 'LIST',
	'NAME' => Loc::getMessage('RS_MM_PARAMETERS_PROP_REVIEW'),
	'TYPE' => 'LIST',
	'VALUES' => $defaultValue + $arProperties['SNL'],
	'DEFAULT' => '-',
);
$arTemplateParameters['SHOW_IBLOCK_DESCRIPTION'] = array(
	'TYPE' => 'LIST',
	'NAME' => Loc::getMessage('RS_MM_PARAMETERS_SHOW_IBLOCK_DESCRIPTION'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N',
);

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSupport'));
\Redsign\Megamart\Layouts\Builder::createTemplateParams($arTemplateParameters, $arCurrentValues);
