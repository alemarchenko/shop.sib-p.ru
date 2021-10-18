<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}


if (isset($arParams['~GRID_RESPONSIVE_SETTINGS'])){
	$arParams['GRID_RESPONSIVE_SETTINGS'] = CUtil::JsObjectToPhp($arParams['~GRID_RESPONSIVE_SETTINGS']);

	if (is_array($arParams['GRID_RESPONSIVE_SETTINGS']) && count($arParams['GRID_RESPONSIVE_SETTINGS']) > 0) {
		foreach ($arParams['GRID_RESPONSIVE_SETTINGS'] as $key => $val) {
			if (intval($val['items']) > 0) {
				$arParams['GRID_RESPONSIVE_SETTINGS'][$key] = (int) 12 / intval($val['items']);
			}
		}
		unset($key, $val);
	}
} else {
	$arParams['GRID_RESPONSIVE_SETTINGS'] = array();
}
