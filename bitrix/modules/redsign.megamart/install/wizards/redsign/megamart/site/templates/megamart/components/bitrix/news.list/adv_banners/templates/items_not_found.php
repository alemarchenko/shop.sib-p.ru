<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
$layout
	->addModifier('shadow')
	->addModifier('bg-white')
	->addModifier('inner-spacing')
	->addModifier('outer-spacing');


$layout->start();

echo Loc::getMessage('RS_ADV_BANNERS_ITEMS_NOT_FOUND');

$layout->end();
