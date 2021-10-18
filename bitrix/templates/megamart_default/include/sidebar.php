<?php
use \Bitrix\Main\Application;

if (empty($arParams['TYPE'])) {
	$arParams['TYPE'] = 'inner';
}

$arStickySidebarOptions = array();

if ($arParams['TYPE'] == 'inner') {
	$arStickySidebarOptions['minWidth'] = 1200;
}

?><div class="l-main__<?=$arParams['TYPE']?>-sidebar" data-sticky-sidebar='<?=\Bitrix\Main\Web\Json::encode($arStickySidebarOptions)?>'><?
if ($arParams['SIDEBAR_PATH'] != '' && file_exists(Application::getDocumentRoot().$sSidebarPath)) {
	$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		".default",
		array(
			"COMPONENT_TEMPLATE" => ".default",
			"AREA_FILE_SHOW" => "file",
			"PATH" => $arParams['SIDEBAR_PATH'],
			"EDIT_TEMPLATE" => ""
		),
		false,
		array('HIDE_ICONS' => 'Y')
	);
} else {
	$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		".default",
		array(
			"COMPONENT_TEMPLATE" => ".default",
			"AREA_FILE_SHOW" => "sect",
			"AREA_FILE_SUFFIX" => "sidebar_".$arParams['TYPE'],
			"AREA_FILE_RECURSIVE" => "Y",
			"EDIT_TEMPLATE" => ""
		),
		false,
		array('HIDE_ICONS' => 'Y')
	);
}
?></div><?
