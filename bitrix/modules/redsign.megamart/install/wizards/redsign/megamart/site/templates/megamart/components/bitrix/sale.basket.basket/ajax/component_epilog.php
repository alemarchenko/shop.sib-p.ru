<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arJson = array(
	'HTML' => $templateData,
	'SCRIPTS' => Bitrix\Main\Page\Asset::getInstance()->getJs(),
	'STYLES' => Bitrix\Main\Page\Asset::getInstance()->getCss()
);

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if ($request->isAjaxRequest())
{
	$APPLICATION->RestartBuffer();
	echo \Bitrix\Main\Web\Json::encode($arJson);
	die();
}
else {
	LocalRedirect(SITE_DIR.'personal/cart/');
}
