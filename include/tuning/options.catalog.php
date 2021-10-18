<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arParams['TITLE_TAB_CATALOG'] =  array(
	'TAB' => 'TAB_CATALOG',
	'TYPE' => 'TITLE',
	'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_CATALOG'),
);

$arParams['CATALOG_SECTION_TYPE'] = array(
    'TAB' => 'TAB_CATALOG',
    'TYPE' => 'RADIO',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_CATALOG_SECTION_TYPE'),
    'CONTROL_NAME' => 'catalog_section_type',
    'DEFAULT' => 'type2',
    'RELOAD' => 'Y',
    'VALUES' => array(
        'TYPE_1' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_CATALOG_SECTION_TYPE_1'),
            'CONTROL_ID' => 'type1',
            'HTML_VALUE' => 'type1',
			'IMAGE' => '/include/tuning/img/catalog_section_type1.png'
        ),
        'TYPE_2' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_CATALOG_SECTION_TYPE_2'),
            'CONTROL_ID' => 'type2',
            'HTML_VALUE' => 'type2',
			'IMAGE' => '/include/tuning/img/catalog_section_type2.png'
        ),
        'TYPE_3' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_CATALOG_SECTION_TYPE_3'),
            'CONTROL_ID' => 'type3',
            'HTML_VALUE' => 'type3',
			'IMAGE' => '/include/tuning/img/catalog_section_type3.png'
        ),
    ),
    'VIEW' => 'images',
	'GRID_SIZE_VALUE' => 4,
);
