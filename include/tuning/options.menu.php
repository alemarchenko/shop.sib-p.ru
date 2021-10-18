<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arParams['TITLE_TAB_MENU'] =  array(
	'TAB' => 'TAB_MENU',
	'TYPE' => 'TITLE',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_MENU'),
);

$arParams['MENU_THEME'] = array(
	'TAB' => 'TAB_MENU',
	'TYPE' => 'RADIO',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MENU_STYLE'),
	'CONTROL_NAME' => 'menu_theme',
	'DEFAULT' => 'primary',
	'RELOAD' => 'Y',
	'VALUES' => array(
		'MENU_STYLE_CURRENTCOLOR' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MENU_THEME.PRIMARY'),
			'CONTROL_ID' => 'menu_theme_primary',
			'HTML_VALUE' => 'primary',
		),
		'MENU_STYLE_DARK' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MENU_THEME.DARK'),
			'CONTROL_ID' => 'menu_theme_dark',
			'HTML_VALUE' => 'dark',
		),
		'MENU_STYLE_LIGHT' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MENU_THEME.LIGHT'),
			'CONTROL_ID' => 'menu_theme_light',
			'HTML_VALUE' => 'light',
		),
		'MENU_THEME_TRANSPARENT' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MENU_THEME.TRANSPARENT'),
			'CONTROL_ID' => 'menu_theme_transparent',
			'HTML_VALUE' => 'transparent',
		),
	),
	'VIEW' => 'buttons',
);


$arParams['FIX_HEADER'] = array(
	'TAB' => 'TAB_MENU',
	'TYPE' => 'RADIO',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FIX_HEADER'),
	'CONTROL_NAME' => 'fix_header',
	'DEFAULT' => 'Y',
	'RELOAD' => 'Y',
	'VALUES' => array(
		'ENABLED' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FIX_HEADER.ENABLED'),
			'CONTROL_ID' => 'fix_header_enabled',
			'HTML_VALUE' => 'Y',
			'IMAGE' => '/include/tuning/img/fix_header_enabled.png',
		),
		'DISABLED' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FIX_HEADER.DISABLED'),
			'CONTROL_ID' => 'fix_header_disabled',
			'HTML_VALUE' => 'N',
			'IMAGE' => '/include/tuning/img/fix_header_disabled.png',
		)
	),
	'VIEW' => 'images',
	'GRID_SIZE_VALUE' => 4,
);

$arParams['HEADER_TYPE'] = array(
	'TAB' => 'TAB_MENU',
	'TYPE' => 'RADIO',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER'),
	'CONTROL_NAME' => 'header_type',
	'DEFAULT' => 'type1',
	'RELOAD' => 'Y',
	'VALUES' => array(
		'HEADER_1' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.1'),
			'CONTROL_ID' => 'header_1',
			'HTML_VALUE' => 'type1',
			'IMAGE' => '/include/tuning/img/header_1.png',
		),
		'HEADER_2' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.2'),
			'CONTROL_ID' => 'header_2',
			'HTML_VALUE' => 'type2',
			'IMAGE' => '/include/tuning/img/header_2.png',
		),
		'HEADER_3' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.3'),
			'CONTROL_ID' => 'header_3',
			'HTML_VALUE' => 'type3',
			'IMAGE' => '/include/tuning/img/header_3.png',
		),
		'HEADER_4' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.4'),
			'CONTROL_ID' => 'header_4',
			'HTML_VALUE' => 'type4',
			'IMAGE' => '/include/tuning/img/header_4.png',
		),
		'HEADER_5' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.5'),
			'CONTROL_ID' => 'header_5',
			'HTML_VALUE' => 'type5',
			'IMAGE' => '/include/tuning/img/header_5.png',
		),
		'HEADER_6' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.6'),
			'CONTROL_ID' => 'header_6',
			'HTML_VALUE' => 'type6',
			'IMAGE' => '/include/tuning/img/header_6.png',
		),
		'HEADER_7' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.7'),
			'CONTROL_ID' => 'header_7',
			'HTML_VALUE' => 'type7',
			'IMAGE' => '/include/tuning/img/header_7.png',
		),
		'HEADER_8' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.9'),
			'CONTROL_ID' => 'header_8',
			'HTML_VALUE' => 'type8',
			'IMAGE' => '/include/tuning/img/header_8.png',
		),
		'HEADER_9' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.9'),
			'CONTROL_ID' => 'header_9',
			'HTML_VALUE' => 'type9',
			'IMAGE' => '/include/tuning/img/header_9.png',
		),
		'HEADER_10' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_HEADER.10'),
			'CONTROL_ID' => 'header_10',
			'HTML_VALUE' => 'type10',
			'IMAGE' => '/include/tuning/img/header_10.png',
		),
	),
	'VIEW' => 'images',
	'NUMBERS' => 'Y',
	'HIDE_LABELS' => 'Y',
);
