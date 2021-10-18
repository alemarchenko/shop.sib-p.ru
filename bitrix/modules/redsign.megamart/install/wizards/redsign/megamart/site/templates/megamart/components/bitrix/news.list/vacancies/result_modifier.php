<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

use \Bitrix\Main\Loader;

if (!Loader::includeModule('iblock')) {
    return;
}

$arParams['RS_TYPE_PROPERTY'] = 'TYPE';

$arResult['FILTER'] = array();
if ($arParams['RS_TYPE_PROPERTY'] != '') {
    $arResult['FILTER']['VALUES'] = array();
    $propertyEnums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], "CODE" => $arParams['RS_TYPE_PROPERTY']));
    while ($arFields = $propertyEnums->GetNext()) {
        $arResult['FILTER']['VALUES'][] = array(
            'ID' => $arFields['ID'],
            'VALUE' => $arFields['VALUE'],
            'XML_ID' => $arFields['XML_ID'],
        );
    }
}
