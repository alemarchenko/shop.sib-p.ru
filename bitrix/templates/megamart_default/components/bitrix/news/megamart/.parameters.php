<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Loader,
	\Bitrix\Main\Localization\Loc;

use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('redsign.megamart') || !Loader::includeModule('redsign.devfunc'))
	return;


$arNLTemplates = ParametersUtils::getComponentTemplateList('bitrix:news.list');
$arNDTemplates = ParametersUtils::getComponentTemplateList('bitrix:news.detail');
$arCSTTemplates = ParametersUtils::getComponentTemplateList('bitrix:catalog.sections.top');

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

$sTemplateId = '';
if (isset($_REQUEST['siteTemplateId'])) {
	$sTemplateId = $_REQUEST['siteTemplateId'];
} elseif (isset($_REQUEST['site_template'])) {
	$sTemplateId = $_REQUEST['site_template'];
} else {
	$sTemplateId = 'megamart_default';
}


$arIblockViewMode = array(
	'-' => Loc::getMessage('RS_N_P_UNDEFINED'),
	'VIEW_SECTIONS' => Loc::getMessage('RS_N_P_IBLOCK_VIEW_MODE_SECTIONS'),
	'VIEW_ELEMENTS' => Loc::getMessage('RS_N_P_IBLOCK_VIEW_MODE_ELEMENTS')
);

$arTemplateParameters = array(
	'IBLOCK_VIEW_MODE' => array(
		'PARENT' => 'SECTIONS_SETTINGS',
		'NAME' => getMessage('RS_N_P_IBLOCK_VIEW_MODE'),
		'TYPE' => 'LIST',
		'VALUES' => $arIblockViewMode,
		'MULTIPLE' => 'N',
		'DEFAULT' => 'VIEW_ELEMENTS',
		'REFRESH' => 'Y',
	),
);


/* SECTIONS OPTIONS */
if ($arCurrentValues['IBLOCK_VIEW_MODE'] == 'VIEW_SECTIONS') {
	$arTemplateParameters['SECTIONS_TEMPLATE'] = array(
		'NAME' => Loc::getMessage('RS_N_P_SECTIONS_TEMPLATE'),
		'TYPE' => 'LIST',
		'VALUES' => $arCSTTemplates,
		'DEFAULT' => '',
		'REFRESH' => 'Y',
	);

	$arCurrentSectionValues = ParametersUtils::getTemplateParametersValue('SECTIONS', $arCurrentValues);
	$arCurrentSectionValues['IBLOCK_ID'] = $arCurrentValues['IBLOCK_ID'];

	$arTemplateParameters += ParametersUtils::getTemplateParameters(
		'SECTIONS',
		$sTemplateId,
		'catalog.sections.top',
		$arCurrentValues['SECTIONS_TEMPLATE'],
		$arCurrentSectionValues,
		function (&$arParameter) {
			$arParameter['PARENT'] = 'SECTIONS_SETTINGS';
		}
	);
}

/* LIST OPTIONS */
$arTemplateParameters['LIST_TEMPLATE'] = array(
	'NAME' => Loc::getMessage('RS_N_P_LIST_TEMPLATE'),
	'TYPE' => 'LIST',
	'VALUES' => $arNLTemplates,
	'DEFAULT' => '',
	'REFRESH' => 'Y',
	'PARENT' => 'LIST_SETTINGS',
);

if (isset($arCurrentValues['LIST_TEMPLATE'])) {
	$arCurrentListValues = ParametersUtils::getTemplateParametersValue('LIST', $arCurrentValues);

	$arCurrentListValues['IBLOCK_ID'] = $arCurrentValues['IBLOCK_ID'];
	$arCurrentListValues['PROPERTY_CODE'] = $arCurrentValues['LIST_PROPERTY_CODE'];

	$arTemplateParameters += ParametersUtils::getTemplateParameters(
		'LIST',
		$sTemplateId,
		'news.list',
		$arCurrentValues['LIST_TEMPLATE'],
		$arCurrentListValues,
		function (&$arParameter) {
			$arParameter['PARENT'] = 'LIST_SETTINGS';
		}
	);
}


$arTemplateParameters['DETAIL_TEMPLATE'] = array(
	'NAME' => Loc::getMessage('RS_N_P_DETAIL_TEMPLATES'),
	'TYPE' => 'LIST',
	'VALUES' => $arNDTemplates,
	'DEFAULT' => '',
	'REFRESH' => 'Y',
	'PARENT' => 'DETAIL_SETTINGS',
);

$arCurrentDetailValues = array(
	'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
	'PROPERTY_CODE' => $arCurrentValues['DETAIL_PROPERTY_CODE'],
);

$arTemplateParameters += ParametersUtils::getTemplateParameters(
	'DETAIL',
	$sTemplateId,
	'news.detail',
	$arCurrentValues['DETAIL_TEMPLATE'],
	$arCurrentDetailValues,
	function (&$arParameter) {
		$arParameter['PARENT'] = 'DETAIL_SETTINGS';
	}
);

// ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('share', 'lazy-images'));
