<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

$component = $this->getComponent();

if (strlen($arParams["CATALOG_FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["CATALOG_FILTER_NAME"]))
{
    $arParams["CATALOG_FILTER_NAME"] = "arrFilter";
}

$arParams['DETAIL_TEMPLATE'] = !empty($arParams['DETAIL_TEMPLATE']) ?  $arParams['DETAIL_TEMPLATE'] : 'catalog';
$arParams['LIST_TEMPLATE'] = !empty($arParams['LIST_TEMPLATE']) ?  $arParams['LIST_TEMPLATE'] : 'catalog';
$arParams['USE_SORTER'] = $arParams['USE_SORTER'] === 'N' ?  $arParams['USE_SORTER'] : 'Y';

if (empty($arParams['AJAX_ID']) || strlen($arParams['AJAX_ID']) < 1)
{
	 $arParams['AJAX_ID'] = CAjax::GetComponentID($component->componentName, $component->componentTemplate, $arParams['AJAX_OPTION_ADDITIONAL']);
}