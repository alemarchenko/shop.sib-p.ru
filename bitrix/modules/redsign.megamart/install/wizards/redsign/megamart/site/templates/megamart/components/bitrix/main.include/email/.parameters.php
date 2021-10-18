<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;


$arTemplateParameters['IGNORE_MULTIREGIONALITY'] = [
	'NAME' => Loc::getMessage('RS_MI_IGNORE_MULTIREGIONALITY'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N'
];

$arTemplateParameters['GET_FROM'] = [
	'NAME' => Loc::getMessage('RS_MI_GET_FROM'),
	'TYPE' => 'LIST',
	'REFRESH' => 'Y',
	'VALUES' => [
		'module' => Loc::getMessage('RS_MI_GET_FROM_MODULE'),
		'component' => Loc::getMessage('RS_MI_GET_FROM_COMPONENT'),
		'file' => Loc::getMessage('RS_MI_GET_FROM_INCLUDE_AREA'),
	]
];

if ($arCurrentValues['GET_FROM'] == 'component') {
	$arTemplateParameters['EMAILS'] = [
		'NAME' => Loc::getMessage('RS_MI_EMAILS'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
		'MULTIPLE' => 'Y',
	];
}
