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

$arTemplateParameters['USE_VK_GROUPS'] = array(
    'NAME' => Loc::getMessage('USE_VK_GROUPS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['USE_VK_GROUPS'] == 'Y') {
    $arTemplateParameters['VK_GROUP_ID'] = array(
        'NAME' => Loc::getMessage('VK_GROUP_ID'),
        'TYPE' => 'STRING'
    );
}

$arTemplateParameters['USE_FB_GROUPS'] = array(
    'NAME' => Loc::getMessage('USE_FB_GROUPS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['USE_FB_GROUPS'] == 'Y') {
    $arTemplateParameters['FB_GROUP_HREF'] = array(
        'NAME' => Loc::getMessage('FB_GROUP_HREF'),
        'TYPE' => 'STRING'
    );
}

$arTemplateParameters['LANGE'] = array(
    'NAME' => Loc::getMessage('LANGE'),
    'TYPE' => 'STRING',
);
