<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Loader,
	\Bitrix\Main\Config\Option;

$arParams['NEWS_VIEW_MODE'] = (!empty($arParams['NEWS_VIEW_MODE']) ? $arParams['NEWS_VIEW_MODE'] : 'tile');
$arParams['BINDED_ELEMENTS_PROP'] = (!empty($arParams['BINDED_ELEMENTS_PROP']) ? $arParams['BINDED_ELEMENTS_PROP'] : 'BINDED_ELEMENTS');
