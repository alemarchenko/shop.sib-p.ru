<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Megamart\ParametersUtils;

if (!Loader::includeModule('redsign.megamart')) {
    return;
}

Loc::loadMessages(__FILE__);

if (Loader::includeModule('iblock')) {
	$rsIblocks = CIBlock::GetList(
		array(),
		array(
			'TYPE' => $arCurrentValues['IBLOCK_TYPE'],
			'ID' => $arCurrentValues['IBLOCKS'],
		),
		false,
		array('ID', 'NAME')
	);

	while($arIblock = $rsIblocks->GetNext()) {
		$arTemplateParameters['RS_TAG_'.$arIblock['ID'].'_COLOR'] = array(
			'PARENT' => 'VISUAL',
			'TYPE' => 'SRTING',
			'NAME' => Loc::getMessage('RS.TAG_COLOR', array('#IBLOCK_NAME#' => $arIblock['NAME'])),
			'DEFAULT' => '#000'
		);
	}
}

$arTemplateParameters['RS_USE_SUMMARY_PAGE'] = array(
	'PARENT' => 'VISUAL',
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y',
	'NAME' => Loc::getMessage('RS_NLINE_PARAMETERS_USE_SUMMARY_PAGE')
);
if($arCurrentValues['RS_USE_SUMMARY_PAGE'] == 'Y') {
	$arTemplateParameters['RS_SUMMARY_PAGE_TITLE'] = array(
		'PARENT' => 'VISUAL',
		'TYPE' => 'STRING',
		'DEFAULT' => Loc::getMessage('RS_NLINE_PARAMETERS_SUMMARY_PAGE_TITLE_DEFAULT'),
		'NAME' => Loc::getMessage('RS_NLINE_PARAMETERS_SUMMARY_PAGE_TITLE')
	);

	$arTemplateParameters['RS_SUMMARY_PAGE_LINK'] = array(
		'PARENT' => 'VISUAL',
		'TYPE' => 'STRING',
		'NAME' => Loc::getMessage('RS_NLINE_PARAMETERS_SUMMARY_PAGE_LINK'),
		'DEFAULT' => '/info/',
	);
}

$arTemplateParameters['RS_SHOW_IBLOCK'] = array(
	'PARENT' => 'VISUAL',
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y',
	'NAME' => Loc::getMessage('RS_NLINE_PARAMETERS_SHOW_IBLOCK')
);

$arTemplateParameters['GRID_RESPONSIVE_SETTINGS'] = ParametersUtils::getGridParameters(array(
	'PARENT' => 'VISUAL'
));

\Redsign\Megamart\Layouts\Builder::createTemplateParams($arTemplateParameters, $arCurrentValues);
