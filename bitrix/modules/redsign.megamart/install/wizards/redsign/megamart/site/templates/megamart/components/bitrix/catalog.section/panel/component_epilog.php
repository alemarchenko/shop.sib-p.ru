<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

global $APPLICATION;

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if ($APPLICATION->GetShowIncludeAreas())
{
	echo $templateData;
}
else
{

	if ($request->isAjaxRequest()) {
		$arJson = array(
			'HTML' => $templateData,
			'SCRIPTS' => Bitrix\Main\Page\Asset::getInstance()->getJs()
		);

		$APPLICATION->RestartBuffer();
		echo \Bitrix\Main\Web\Json::encode($arJson);
		die();
	}
	else {
		LocalRedirect(SITE_DIR.'personal/wishlist/');
	}


}
