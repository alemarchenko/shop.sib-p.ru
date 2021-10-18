<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Loader;
use \Redsign\DevFunc\Sale\Location\Region;

$arResult['EMAILS'] = [];

if (
	$arParams['IGNORE_MULTIREGIONALITY'] !== 'Y' &&
	Loader::includeModule('redsign.devfunc') &&
	Region::isUseRegionality()
) {
	$arRegion = Region::getCurrentRegion();
	if (isset($arRegion['REGION_MACROS']['#REGION_EMAIL#'])) {
		if (is_array($arRegion['REGION_MACROS']['#REGION_EMAIL#'])) {
			$arResult['EMAILS'] = $arRegion['REGION_MACROS']['#REGION_EMAIL#'];
		} else {
			$arResult['EMAILS'][] = $arRegion['REGION_MACROS']['#REGION_EMAIL#'];
		}
	}
} else {
	if ($arParams['GET_FROM'] == 'module') {
		$sEmails = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_emails');

		$arResult['EMAILS'] = unserialize($sEmails);
	} else {
		$arResult['EMAILS'] = $arParams['EMAILS'];
	}
}
