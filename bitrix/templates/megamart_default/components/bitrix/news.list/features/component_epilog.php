<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/bodymovin/bodymovin.js');
