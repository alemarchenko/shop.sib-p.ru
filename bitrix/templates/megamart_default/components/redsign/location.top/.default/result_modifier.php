<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult['SELECT_CITY'] = \Redsign\DevFunc\Sale\Location\Location::getMyCity();

if (!empty($arResult['SELECT_CITY'])) {
	$isFind = false;
	foreach ($arResult['ITEMS'] as $index => $arItem) {
		if ($arItem['ID'] == $arResult['SELECT_CITY']['ID']) {
			unset($arResult['ITEMS'][$index]);
			$isFind = true;
		}
	}
	if (!$isFind && count($arResult['ITEMS']) >= $arParams['COUNT_ITEMS']) {
		array_pop($arResult['ITEMS']);
	}
}

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$isAjax = $arResult['IS_AJAX'] = $request->isAjaxRequest();

$arResult['TEMPLATE'] = isset($arParams['TEMPLATE']) ? $arParams['TEMPLATE'] : 'simple';
if ($request->get('fancyboxFullscreen') && $request->get('fancyboxFullscreen') == 'true') {
	$arResult['TEMPLATE'] = 'advanced';
}
