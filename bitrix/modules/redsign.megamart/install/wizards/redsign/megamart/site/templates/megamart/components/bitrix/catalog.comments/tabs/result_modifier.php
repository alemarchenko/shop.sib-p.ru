<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

$arParams['EXTERNAL_TABS'] = (isset($arParams['EXTERNAL_TABS']) && $arParams['EXTERNAL_TABS'] === 'Y' ? 'Y' : 'N');
$arParams['EXTERNAL_TABS_ACTIVE'] = (isset($arParams['EXTERNAL_TABS_ACTIVE']) && $arParams['EXTERNAL_TABS_ACTIVE'] === 'Y' ? 'Y' : 'N');