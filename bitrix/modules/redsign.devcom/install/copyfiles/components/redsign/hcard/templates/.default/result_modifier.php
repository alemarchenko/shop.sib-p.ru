<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

if (!empty($arParams['ADR_POSTAL_CODE']))
	$arResult['ADR'][] = array('VALUE' => $arParams['ADR_POSTAL_CODE'], 'CLASS' => 'postal-code');

if (!empty($arParams['ADR_COUNTRY_NAME']))
	$arResult['ADR'][] = array('VALUE' => $arParams['ADR_COUNTRY_NAME'], 'CLASS' => 'country-name');

if (!empty($arParams['ADR_REGION']))
	$arResult['ADR'][] = array('VALUE' => $arParams['ADR_REGION'], 'CLASS' => 'region');

if (!empty($arParams['ADR_LOCALITY']))
	$arResult['ADR'][] = array('VALUE' => $arParams['ADR_LOCALITY'], 'CLASS' => 'locality');

if (!empty($arParams['ADR_STREET_ADDRESS']))
	$arResult['ADR'][] = array('VALUE' => $arParams['ADR_STREET_ADDRESS'], 'CLASS' => 'street-adress');

if (!empty($arParams['ADR_EXT_ADDRESS']))
	$arResult['ADR'][] = array('VALUE' => $arParams['ADR_EXT_ADDRESS'], 'CLASS' => 'extended-address');