<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;


$arTemplateParameters['RS_USE_LOCATION'] = array(
	'NAME' => Loc::getMessage('RS_NL_PARAMETERS_USE_LOCATION'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y'
);

if ($arCurrentValues['RS_USE_LOCATION'] == 'Y') {
	$arTemplateParameters['RS_LOCATION_URL'] = array(
		'NAME' => Loc::getMessage('RS_NL_PARAMETERS_LOCATION_URL'),
		'TYPE' => 'STRING',
		'DEFAULT' => '/city/'
	);
}

$arTemplateParameters['RS_SHOW_RECALL_BUTTON'] = array(
	'NAME' => Loc::getMessage('RS_NL_PARAMETERS_SHOW_RECALL_BUTTON'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);
if ($arCurrentValues['RS_SHOW_RECALL_BUTTON'] == 'Y') {
	$arTemplateParameters['RS_RECALL_LINK'] = array(
		'NAME' => Loc::getMessage('RS_NL_PARAMETERS_RECALL_LINK'),
		'TYPE' => 'STRING',
		'DEFAULT' => '/forms/recall/'
	);

	$arTemplateParameters['RS_RECALL_TITLE'] = array(
		'NAME' => Loc::getMessage('RS_NL_PARAMETERS_RECALL_TITLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => Loc::getMessage('RS_NL_PARAMETERS_RECALL_TITLE_DEFAULT')
	);
}

$arTemplateParameters['RS_SHOW_STORES_LINK'] = array(
	'NAME' => Loc::getMessage('RS_NL_PARAMETERS_SHOW_STORES_LINK'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);
$arTemplateParameters['RS_STORES_LINK'] = array(
	'NAME' => Loc::getMessage('RS_NL_PARAMETERS_STORES_LINK'),
	'TYPE' => 'STRING',
	'DEFAULT' => '/contacts/shops/'
);

$arTemplateParameters['RS_BACKGROUND_PATH'] = array(
	'NAME' => Loc::getMessage('RS_NL_PARAMETERS_BACKGROUND_PATH'),
	'TYPE' => 'STRING',
	'DEFAULT' => '/include/images/shop-card-background.jpg'
);
