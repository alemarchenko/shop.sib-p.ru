<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (count($arResult['ITEMS']) > 0):
	$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
	$layout->addModifier('bg-lg');

	$layout->start();

		if (file_exists($arResult['TEMPLATE_PATH'])) {
			include($arResult['TEMPLATE_PATH']);
		}

	$layout->end();
endif;
