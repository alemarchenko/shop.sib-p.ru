<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

if (Loader::includeModule('iblock')) {
	$detailLinkedPropsIterator = \Bitrix\Iblock\PropertyTable::getList(array(
		'filter' => array(
			'=IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"],
			'=CODE' => $arCurrentValues["PROPERTY_CODE"],
			'=PROPERTY_TYPE' => \Bitrix\Iblock\PropertyTable::TYPE_ELEMENT
		)
	));

	$detailFilePropsIterator = \Bitrix\Iblock\PropertyTable::getList(array(
		'filter' => array(
			'=IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"],
			'=CODE' => $arCurrentValues["PROPERTY_CODE"],
			'=PROPERTY_TYPE' => \Bitrix\Iblock\PropertyTable::TYPE_FILE
		)
	));

	$arDetailLinkProps = $detailLinkedPropsIterator->fetchAll();
	$arDetailFileProps = $detailFilePropsIterator->fetchAll();

	if ($arDetailLinkProps) {
		foreach ($arDetailLinkProps as $arProperty) {
			$arTemplateParameters['RS_BIND_PROP_'.$arProperty['CODE'].'_INCLUDE_FILE'] = array(
				'NAME' => Loc::getMessage('BIND_PROP_INCLUDE_FILE', array('#PROPERTY_NAME#' => $arProperty['NAME'])),
				'TYPE' => 'STRING',
				'DEFAULT' => '/include/templates/news/catalog_items.php',
			);
		}
	}


	if ($arDetailFileProps) {
		foreach ($arDetailFileProps as $arProperty) {
			$arTemplateParameters['RS_FILE_PROP_'.$arProperty['CODE'].'_VIEW'] = array(
				'NAME' => Loc::getMessage('FILE_PROP_VIEW', array('#PROPERTY_NAME#' => $arProperty['NAME'])),
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
