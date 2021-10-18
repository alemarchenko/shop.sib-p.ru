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

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);


$arTemplateParameters['RS_TEMPLATE'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => Loc::getMessage('RS_NEWSLIST_TEMPLATE'),
	'TYPE' => 'LIST',
	'REFRESH' => 'Y',
	'VALUES' => array(
		'from_widget' => Loc::getMessage('RS_NEWSLIST_TEMPLATE_FROM_WIDGET'),
		'tile' => Loc::getMessage('RS_NEWSLIST_TEMPLATE_TILE'),
		'line' => Loc::getMessage('RS_NEWSLIST_TEMPLATE_LINE')
	),
	'DEFAULT' => 'from_widget',
);


if (
	in_array($arCurrentValues['RS_TEMPLATE'], ['tile']) ||
	$arCurrentValues['RS_TEMPLATE'] == 'from_widget' && in_array($arCurrentValues['RS_TEMPLATE_FROM_WIDGET'], ['tile']) ||
	$arCurrentValues['RS_TEMPLATE'] == ''
) {
	$arTemplateParameters['GRID_RESPONSIVE_SETTINGS'] = ParametersUtils::getGridParameters(array(
		'PARENT' => 'LIST_SETTINGS'
	));
}




$arTemplateParameters['USE_ARCHIVE'] = array(
	'TYPE' => 'CHECKBOX',
	'NAME' => Loc::getMessage('USE_ARCHIVE'),
	'PARENT' => 'LIST_SETTINGS',
	'DEFAULT' => 'N',
	'REFRESH' => 'Y'
);

if (Loader::includeModule('iblock')) {
	$detailLinkedPropsIterator = \Bitrix\Iblock\PropertyTable::getList(array(
		'filter' => array(
			'=IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"],
			'=CODE' => $arCurrentValues["DETAIL_PROPERTY_CODE"],
			'=PROPERTY_TYPE' => \Bitrix\Iblock\PropertyTable::TYPE_ELEMENT
		)
	));

	$detailFilePropsIterator = \Bitrix\Iblock\PropertyTable::getList(array(
		'filter' => array(
			'=IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"],
			'=CODE' => $arCurrentValues["DETAIL_PROPERTY_CODE"],
			'=PROPERTY_TYPE' => \Bitrix\Iblock\PropertyTable::TYPE_FILE
		)
	));

	$arDetailLinkProps = $detailLinkedPropsIterator->fetchAll();
	$arDetailFileProps = $detailFilePropsIterator->fetchAll();

	if ($arDetailLinkProps) {
		foreach ($arDetailLinkProps as $arProperty) {
			$arTemplateParameters['RS_BIND_PROP_'.$arProperty['CODE'].'_INCLUDE_FILE'] = array(
				'NAME' => Loc::getMessage('BIND_PROP_INCLUDE_FILE', array('#PROPERTY_NAME#' => $arProperty['NAME'])),
				'PARENT' => 'DETAIL_SETTINGS',
				'TYPE' => 'STRING',
				'DEFAULT' => '/include/templates/news/catalog_items.php',
			);
		}
	}


	if ($arDetailFileProps) {
		foreach ($arDetailFileProps as $arProperty) {
			$arTemplateParameters['RS_FILE_PROP_'.$arProperty['CODE'].'_VIEW'] = array(
				'NAME' => Loc::getMessage('FILE_PROP_VIEW', array('#PROPERTY_NAME#' => $arProperty['NAME'])),
				'PARENT' => 'DETAIL_SETTINGS',
				'TYPE' => 'LIST',
				'VALUES' => array(
					'photogallery' => Loc::getMessage('FILE_PROP_VIEW_PHOTOGALLERY'),
					'line' => Loc::getMessage('FILE_PROP_VIEW_LINE'),
				),
				'DEFAULT' => 'line'
			);
		}
	}
}

$arTemplateParameters['USE_COMMENTS'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => Loc::getMessage('USE_COMMENTS'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y'
);

if ($arCurrentValues['USE_COMMENTS'] == 'Y') {
	$arTemplateParameters['COMMENTS_BLOG_CODE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => Loc::getMessage('COMMENTS_BLOG_CODE'),
		'TYPE' => 'string',
		'DEFAULT' => 'comments'
	);

	$arTemplateParameters['COMMENTS_USE_CONSENT'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => Loc::getMessage('COMMENTS_USE_CONSENT'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'REFRESH' => 'Y'
	);

	if ($arCurrentValues['COMMENTS_USE_CONSENT'] == 'Y') {
		$arAgreements = \Bitrix\Main\UserConsent\Agreement::getActiveList();
		$arTemplateParameters['COMMENTS_CONSENT_ID'] = [
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => Loc::getMessage('COMMENTS_CONSENT'),
			'TYPE' => 'LIST',
			'DEFAULT' => '-',
			'VALUES' => $arAgreements
		];
	}
}

$publisherTypes = array(
	'person' => Loc::getMessage('PUBLISHER_TYPE_PERSON'),
	'organization' => Loc::getMessage('PUBLISHER_TYPE_ORGANIZATION')
);
$arTemplateParameters['PUBLISHER_TYPE'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => Loc::getMessage('PUBLISHER_TYPE'),
	'TYPE' => 'LIST',
	'VALUES' => $publisherTypes
);

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('share'));
