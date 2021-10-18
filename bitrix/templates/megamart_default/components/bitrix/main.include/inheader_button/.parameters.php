<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$arTemplateParameters['TITLE'] = [
	'NAME' => Loc::getMessage('RS_MI_IB_PARAMETERS_TITLE'),
	'TYPE' => 'STRING',
	'DEFAULT' => Loc::getMessage('RS_MI_IB_PARAMETERS_TITLE_DEFAULT'),
];

$arTemplateParameters['LINK'] = [
	'NAME' => Loc::getMessage('RS_MI_IB_PARAMETERS_LINK'),
	'TYPE' => 'STRING',
	'DEFAULT' => Loc::getMessage('RS_MI_IB_PARAMETERS_LINK_DEFAULT'),
];

$arTemplateParameters['IS_POPUP'] = [
	'NAME' => Loc::getMessage('RS_MI_IB_PARAMETERS_IS_POPUP'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
];

$arTemplateParameters['COLOR'] = [
	'NAME' => Loc::getMessage('RS_MI_IB_PARAMETERS_COLOR'),
	'TYPE' => 'LIST',
	'VALUES' => [
		'primary' => 'primary',
		'outline-primary' => 'outline-primary',
		'outline-secondary' => 'outline-secondary'
	],
	'DEFAULT' => 'primary'
];
