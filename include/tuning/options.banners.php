<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arParams['TITLE_TAB_BANNERS'] =  array(
	'TAB' => 'TAB_BANNERS',
	'TYPE' => 'TITLE',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS'),
);

$arParams['MAIN_BANNER_TYPE'] = array(
	'TAB' => 'TAB_BANNERS',
	'TYPE' => 'RADIO',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TYPE'),
	'CONTROL_NAME' => 'banner_type',
	'DEFAULT' => 'underlay',
	'RELOAD' => 'Y',
	'VALUES' => array(
		'UNDERLAY' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TYPE_UNDERLAY'),
			'CONTROL_ID' => 'mb_underlay',
			'HTML_VALUE' => 'underlay',
			'IMAGE' => '/include/tuning/img/banner_type_1.png',
		),
		'WIDE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TYPE_WIDE'),
			'CONTROL_ID' => 'mb_wide',
			'HTML_VALUE' => 'wide',
			'IMAGE' => '/include/tuning/img/banner_type_2.png',
		),
		'CENTER' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TYPE_CENTER'),
			'CONTROL_ID' => 'mb_center',
			'HTML_VALUE' => 'center',
			'IMAGE' => '/include/tuning/img/banner_type_3.png',
		),
		'CENTER_SIDEBANNERS' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TYPE_SIDEBANNERS'),
			'CONTROL_ID' => 'mb_center_sidebanners',
			'HTML_VALUE' => 'center_sidebanners',
			'IMAGE' => '/include/tuning/img/banner_type_4.png',
		)
	),
	'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 6,
);