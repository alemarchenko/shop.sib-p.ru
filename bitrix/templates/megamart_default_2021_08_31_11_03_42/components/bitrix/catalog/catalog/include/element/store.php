<?php

use \Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

if (empty($recommendedData))
    return;

/*
if ($arParams['USE_STORE'] == 'Y' && ModuleManager::isModuleInstalled('catalog'))
{
    $APPLICATION->IncludeComponent('bitrix:catalog.store.amount', '', array(
            'ELEMENT_ID' => $elementId,
            'STORE_PATH' => $arParams['STORE_PATH'],
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => '36000',
            'MAIN_TITLE' => $arParams['MAIN_TITLE'],
            'USE_MIN_AMOUNT' =>  $arParams['USE_MIN_AMOUNT'],
            'MIN_AMOUNT' => $arParams['MIN_AMOUNT'],
            'STORES' => $arParams['STORES'],
            'SHOW_EMPTY_STORE' => $arParams['SHOW_EMPTY_STORE'],
            'SHOW_GENERAL_STORE_INFORMATION' => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
            'USER_FIELDS' => $arParams['USER_FIELDS'],
            'FIELDS' => $arParams['FIELDS']
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );
}
*/
