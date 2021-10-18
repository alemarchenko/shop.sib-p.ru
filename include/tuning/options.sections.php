<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arParams['TITLE_TAB_SECTIONS'] =  array(
	'TAB' => 'TAB_SECTION_LIST',
	'TYPE' => 'TITLE',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_SECTIONS'),
);

$arParams['NEWS_TEMPLATE'] = array(
	'TAB' => 'TAB_SECTION_LIST',
	'TYPE' => 'RADIO',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_SECTIONS.NEWS_TEMPLATE'),
	'CONTROL_NAME' => 'news_template',
	'DEFAULT' => 'tile',
	'RELOAD' => 'Y',
	'VALUES' => array(
		'TILE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_TILE'),
			'CONTROL_ID' => 'news_template_tile',
			'HTML_VALUE' => 'tile',
			'IMAGE' => '/include/tuning/img/news_template_tile.png',
		),
		'LINE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_LINE'),
			'CONTROL_ID' => 'news_template_line',
			'HTML_VALUE' => 'line',
			'IMAGE' => '/include/tuning/img/news_template_line.png',
		)
	),
	'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 6,
);

$arParams['SALE_PROMOTIONS_TEMPLATE'] = array(
	'TAB' => 'TAB_SECTION_LIST',
	'TYPE' => 'RADIO',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_SECTIONS.SALE_PROMOTIONS_TEMPLATE'),
	'CONTROL_NAME' => 'sale_promotions_template',
	'DEFAULT' => 'tile',
	'RELOAD' => 'Y',
	'VALUES' => array(
		'TILE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_TILE'),
			'CONTROL_ID' => 'sale_promotions_template_tile',
			'HTML_VALUE' => 'tile',
			'IMAGE' => '/include/tuning/img/news_template_tile.png',
		),
		'LINE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_LINE'),
			'CONTROL_ID' => 'sale_promotions_template_line',
			'HTML_VALUE' => 'line',
			'IMAGE' => '/include/tuning/img/news_template_line.png',
		)
	),
	'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 6,
);

$arParams['ARTICLES_TEMPLATE'] = array(
	'TAB' => 'TAB_SECTION_LIST',
	'TYPE' => 'RADIO',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_SECTIONS.ARTICLES_TEMPLATE'),
	'CONTROL_NAME' => 'articles_template',
	'DEFAULT' => 'tile',
	'RELOAD' => 'Y',
	'VALUES' => array(
		'TILE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_TILE'),
			'CONTROL_ID' => 'articles_template_tile',
			'HTML_VALUE' => 'tile',
			'IMAGE' => '/include/tuning/img/news_template_tile.png',
		),
		'LINE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_LINE'),
			'CONTROL_ID' => 'articles_template_line',
			'HTML_VALUE' => 'line',
			'IMAGE' => '/include/tuning/img/news_template_line.png',
		)
	),
	'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 6,
);

$arParams['STAFF_TEMPLATE'] = array(
	'TAB' => 'TAB_SECTION_LIST',
	'TYPE' => 'RADIO',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_SECTIONS.STAFF_TEMPLATE'),
	'CONTROL_NAME' => 'staff_template',
	'DEFAULT' => 'type1',
	'RELOAD' => 'Y',
	'VALUES' => array(
		'TILE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_TILE'),
			'CONTROL_ID' => 'staff_template_tile',
			'HTML_VALUE' => 'type1',
			'IMAGE' => '/include/tuning/img/staff_template_tile.png',
		),
		'LINE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_LINE'),
			'CONTROL_ID' => 'staff_template_line',
			'HTML_VALUE' => 'type2',
			'IMAGE' => '/include/tuning/img/staff_template_line.png',
		)
	),
	'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 6,
);

$arParams['LICENSE_TEMPLATE'] = array(
	'TAB' => 'TAB_SECTION_LIST',
	'TYPE' => 'RADIO',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_SECTIONS.LICENSE_TEMPLATE'),
	'CONTROL_NAME' => 'license_template',
	'DEFAULT' => 'col',
	'RELOAD' => 'Y',
	'VALUES' => array(
		'COL' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_TILE'),
			'CONTROL_ID' => 'license_template_col',
			'HTML_VALUE' => 'col',
			'IMAGE' => '/include/tuning/img/files_template_col.png',
		),
		'LINE' => array(
			'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_BANNERS.TEMPLATE_LINE'),
			'CONTROL_ID' => 'license_template_line',
			'HTML_VALUE' => 'line',
			'IMAGE' => '/include/tuning/img/files_template_line.png',
		)
	),
	'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 6,
);