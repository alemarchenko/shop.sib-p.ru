<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

$APPLICATION->IncludeComponent(
	'bitrix:catalog.store.amount',
	'catalog',
	array(
		'ELEMENT_ID' => $arResult['ID'],
		'STORE_PATH' => $arParams['STORE_PATH'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		'MAIN_TITLE' => $arParams['STOCK_MAIN_TITLE'],
		'USE_MIN_AMOUNT' => $arParams['USE_MIN_AMOUNT'],
		// "USE_MIN_AMOUNT" =>  'N',
		// "USE_MIN_AMOUNT_TMPL" =>  $arParams['USE_MIN_AMOUNT'],
		'MIN_AMOUNT' => $arParams['MIN_AMOUNT'],
		'STORES' => $arParams['STORES'],
		'SHOW_EMPTY_STORE' => $arParams['SHOW_EMPTY_STORE'],
		'SHOW_GENERAL_STORE_INFORMATION' => 'N', //$arParams['SHOW_GENERAL_STORE_INFORMATION'],
		'USER_FIELDS' => $arParams['USER_FIELDS'],
		'FIELDS' => $arParams['FIELDS'],
		"OFFER_ID" => $actualItem['ID'],
		"ADD_LAYOUT" => ($arParams['STORE_ADD_LAYOUT'] == 'Y' ? 'Y' : 'N'),
		"MAIN_ID" => $mainId,
		"BLOCK_NAME" => $blockName,
	),
	$component,
	array('HIDE_ICONS' => 'Y')
);
