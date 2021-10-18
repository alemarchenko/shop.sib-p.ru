<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('redsign.megamart') || !Loader::includeModule('redsign.devfunc')) {
	return;
}

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('gridSettings'));
\Redsign\Megamart\Layouts\Builder::createTemplateParams($arTemplateParameters, $arCurrentValues);
