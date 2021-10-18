<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('redsign.megamart')) {
    return;
}

Loc::loadMessages(__FILE__);

$arTemplateParameters['TEMPLATE'] = array(
    'NAME' => Loc::getMessage('RS_MI_PARAMETERS_TEMPLATE_TYPE'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'LIST',
    'VALUES' => array(
      'line' => Loc::getMessage('RS_MI_PARAMETERS_TEMPLATE_LINE'),
    ),
    'DEFAULT' => 'wide'
);

$arTemplateParameters['BTN_LINK'] = array(
    'NAME' => Loc::getMessage('RS_MI_PARAMETERS_BTN_LINK'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('RS_MI_PARAMETERS_BTN_LINK_DEFAULT'),
);
$arTemplateParameters['BTN_TEXT'] = array(
    'NAME' => Loc::getMessage('RS_MI_PARAMETERS_BTN_TEXT'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('RS_MI_PARAMETERS_BTN_TEXT_DEFAULT'),
);
