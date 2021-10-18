<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}


$sId = $this->randString(5);
$arParams['CONTAINER_ID'] = 'shop-list-'.$this->randString(5);
$arParams['MAP_ID'] = 'shop-list-map-'.$sId;


$arParams['PROP_TYPE'] = isset($arParams['PROP_TYPE']) ? $arParams['PROP_TYPE'] : 'TYPE';
$arParams['PROP_COORDS'] = isset($arParams['PROP_COORDS']) ? $arParams['PROP_COORDS'] : 'COORDS';
$arParams['PROP_STORE_ID'] = isset($arParams['PROP_STORE_ID']) ? $arParams['PROP_STORE_ID'] : 'STORE_ID';
$arParams['PROP_CITY'] = isset($arParams['PROP_CITY']) ? $arParams['PROP_CITY'] : 'CITY';

$arParams['PROP_ADDRESS'] = isset($arParams['PROP_ADDRESS']) ? $arParams['PROP_ADDRESS'] : 'ADDRES';
$arParams['PROP_SCHEDULE'] = isset($arParams['PROP_SCHEDULE']) ? $arParams['PROP_SCHEDULE'] : 'SCHEDULE';
$arParams['PROP_PHONE'] = isset($arParams['PROP_PHONE']) ? $arParams['PROP_PHONE'] : 'PHONE_NUMBER';
$arParams['PROP_EMAIL'] = isset($arParams['PROP_EMAIL']) ? $arParams['PROP_EMAIL'] : 'EMAIL';

$arParams['SKIP_DISPLAY_PROPERTIES'] = [
	$arParams['PROP_TYPE'],
	$arParams['PROP_COORDS'],
	$arParams['PROP_STORE_ID']
 ];

$arJsParams = [
	'CONTAINER_ID' => $arParams['CONTAINER_ID'],
	'MAP_ID' => $arParams['MAP_ID'],
	'MAP_PROVIDER' => $arParams['MAP_PROVIDER'],
	'MAP_ZOOM' => $arParams['MAP_ZOOM'],
	'ITEMS' => [],
];

$arFilterKeys = ['CITY', 'TYPE'];
$arResult['FILTER_LIST'] = [];

foreach ($arFilterKeys as $sFilterKey) {
	$arResult['FILTER_LIST'][$sFilterKey] = [];
}

if (!empty($arResult['ITEMS'])) {

	$arSumCoords = [0, 0];

	foreach ($arResult['ITEMS'] as &$arItem) {
		$arJsItem = [
			'ITEM_ID' => $arItem['ID'],
			'NAME' => $arItem['NAME'],
			'PREVIEW_TEXT' => $arItem['PREVIEW_TEXT'],
			'FILTERS' => [
				'CITY' => '',
				'TYPE' => ''
			],
			'COORDS' => [0, 0]
		];

		foreach ($arFilterKeys as $sFilterKey) {
			$sArrayKey = 'PROP_'.$sFilterKey;

			if (isset($arItem['PROPERTIES'][$arParams[$sArrayKey]])) {

				$arProp = $arItem['PROPERTIES'][$arParams[$sArrayKey]];

				$sFilterValue = $arProp['VALUE'];
				$sFilterValueXmlId = $arProp['VALUE_XML_ID'];

				$arJsItem['FILTERS'][$sFilterKey] = $sFilterValueXmlId;

				if (!isset($arResult['FILTER_LIST'][$sFilterKey][$sFilterValueXmlId])) {
					$arResult['FILTER_LIST'][$sFilterKey][$sFilterValueXmlId] = $sFilterValue;
				}
			}
		}

		if (isset($arItem['PROPERTIES'][$arParams['PROP_COORDS']])) {
			$sCoords = $arItem['PROPERTIES'][$arParams['PROP_COORDS']]['VALUE'];
			$arCoords = explode(',', $sCoords);

			$arJsItem['COORDS'] = $arCoords;

			$arSumCoords[0] += $arCoords[0];
			$arSumCoords[1] += $arCoords[1];

			unset($sCoords, $arCoords);
		}

		$arJsParams['ITEMS'][] = $arJsItem;
	}

	$nCountItems = count($arResult['ITEMS']);

	if ($arParams['MAP_AUTO_CENTERING'] == 'Y') {
		$arJsParams['MAP_CENTER_COORDS'] = [
			$arSumCoords[0] / $nCountItems,
			$arSumCoords[1] / $nCountItems
		];
	} else if (!empty($arParams['MAP_CENTER_COORDS'])) {
		$arJsParams['MAP_CENTER_COORDS'] = explode(',', $arParams['MAP_CENTER_COORDS']);
	}

	unset($arItem, $arSumCoords);
}

$arResult['JS_PARAMS'] = $arJsParams;

unset(
	$arCities,
	$arJsParams
);

if (!isset($arPaarams['ONLOAD_CALLBACK_FN_NAME'])) {
	$arParams['ONLOAD_CALLBACK_FN_NAME'] = 'shopsListMapReady';
}
