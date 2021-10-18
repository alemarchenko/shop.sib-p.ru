<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;


Loc::loadMessages($templateFolder.'/template.php');

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
$layout
	->addModifier('bg-white')
	->addModifier('inner-spacing')
	->addModifier('shadow');

$layout->start();

	echo Loc::getMessage('RS_NL_FILES_NO_ITEMS');

$layout->end();
