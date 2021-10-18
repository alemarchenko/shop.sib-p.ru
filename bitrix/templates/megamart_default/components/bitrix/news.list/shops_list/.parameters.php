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

if (Loader::includeModule('redsign.devfunc')) {
	$arProperties = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);
}


$arTemplateParameters['MAP_PROVIDER'] = array(
	'NAME' => Loc::getMessage('RS_NL_PARAMETERS_MAP_PROVIDER'),
	'TYPE' => 'LIST',
	'DEFAULT' => 'yandex',
	'VALUES' => array(
		'yandex' => Loc::getMessage('RS_NL_PARAMETERS_MAP_PROVIDER_YANDEX'),
		'google' => Loc::getMessage('RS_NL_PARAMETERS_MAP_PROVIDER_GOOGLE')
	)
);

if ($arCurrentValues['MAP_PROVIDER'] == 'google')
{
	$arTemplateParameters['GOOGLE_MAP_API_KEY'] = [
		'NAME' => Loc::getMessage('RS_N_PARAMETERS_GOOGLE_MAP_API_KEY'),
		'TYPE' => 'STRING',
		'DEFAULT' => ''
	];
}

$arTemplateParameters['ONLOAD_CALLBACK_FN_NAME'] = array(
	'NAME' => Loc::getMessage('RS_NL_PARAMETERS_ONLOAD_CALLBACK_FN_NAME'),
	'TYPE' => 'STRING',
	'DEFAULT' => 'shopsListMapReady'
);

$arTemplateParameters['MAP_AUTO_CENTERING'] = array(
	'NAME' => Loc::getMessage('RS_NL_PARAMETERS_MAP_AUTO_CENTERING'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y'
);

if ($arCurrentValues['MAP_AUTO_CENTERING'] != 'Y') {
	$arTemplateParameters['MAP_CENTER_COORDS'] = array(
		'NAME' => Loc::getMessage('RS_NL_PARAMETERS_MAP_CENTER_COORDS'),
		'TYPE' => 'STRING',
		'DEFAULT' => '55.753960, 37.620393'
	);
}


$arTemplateParameters['MAP_ZOOM'] = array(
	'NAME' => Loc::getMessage('RS_NL_PARAMETERS_MAP_ZOOM'),
	'TYPE' => 'STRING',
	'DEFAULT' => '5'
);
