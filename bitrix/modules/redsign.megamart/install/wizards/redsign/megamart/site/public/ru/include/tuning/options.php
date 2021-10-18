<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arTabs = array(
    'TAB_GENERAL' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_TAB_GENERAL'),
    ),
    'TAB_MENU' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_TAB_MENU'),
    ),
    'TAB_MAIN_PAGE' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_TAB_MAIN_PAGE'),
    ),
    'TAB_BANNERS' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_TAB_BANNERS'),
    ),
    /** 'TAB_TEMPLATES' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_TAB_TEMPLATES'),
    ), **/
   'TAB_CATALOG' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_TAB_CATALOG'),
    ),
    /** 'TAB_PRODUCT_PAGE' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_TAB_PRODUCT_PAGE'),
    ),
    'TAB_MOBILE' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_TAB_MOBILE'),
    ), **/
    'TAB_SECTION_LIST' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_TAB_SECTION_LIST'),
    ),
);

$arParams = array();
@include ('options.general.php');
@include ('options.menu.php');
@include ('options.main_page.php');
@include ('options.banners.php');
@include ('options.catalog.php');
@include ('options.sections.php');

$arParameters = array(
    'TABS' => $arTabs,
    'PARAMETERS' => $arParams,
);

return $arParameters;
