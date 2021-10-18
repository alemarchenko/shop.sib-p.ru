<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

if (!isset($arParams['BRAND_PROP']))
{
    $arParams['BRAND_PROP'] = 'BRAND';
}

if (!isset($arParams['COLLECTION_BRAND_PROP']))
{
    $arParams['COLLECTION_BRAND_PROP'] = 'BRAND_REF';
}

if (!isset($arParams['CATALOG_BRAND_PROP']))
{
    $arParams['CATALOG_BRAND_PROP'] = 'BRAND_REF';
}

if (!isset($arParams['CATALOG_COLLECTION_PROP']))
{
    $arParams['CATALOG_COLLECTION_PROP'] = 'COLLECTION_REF';
}

if (strlen($arParams["CATALOG_FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["CATALOG_FILTER_NAME"]))
{
    $arParams["CATALOG_FILTER_NAME"] = "arrFilter";
}

if (empty($arParams['AJAX_ID']) || strlen($arParams['AJAX_ID']) < 1)
{
	$arParams['AJAX_ID'] = CAjax::GetComponentID(
        $component->componentName, 
        $component->componentTemplate,
        $arParams['AJAX_OPTION_ADDITIONAL']
    );
}