<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

if (!isset($arParams['COUNT_SHOW_SUBSECTIONS']) || count($arParams['COUNT_SHOW_SUBSECTIONS']) < 0) {
	$arParams['COUNT_SHOW_SUBSECTIONS'] = 7;
}

if (\Bitrix\Main\Loader::includeModule('iblock') && count($arResult) > 0) {
	$arSectionIds = array();
	$arSectionItems = array();
	foreach ($arResult as &$arItem) {
		if (isset($arItem['PARAMS']['FROM_IBLOCK']) && isset($arItem['PARAMS']['SECTION_ID'])) {
			$arItem['PICTURE'] = null;

			$iSectionId = $arItem['PARAMS']['SECTION_ID'];
			$arSectionIds[] = $iSectionId;
			$arSectionItems[$iSectionId] = &$arItem;

		}
	}
	unset($arItem);


	$sectionIterator = \Bitrix\Iblock\SectionTable::getList(array(
		'select' => array(
			'ID', 'PICTURE'
		),
		'filter' => array(
			'=ID' => $arSectionIds,
			'=DEPTH_LEVEL' => 2
		),
		'cache' => array(
			'ttl' => $arParams['CACHE_TIME'],
		)
	));

	$arPictureIds = array();
	while ($arSection = $sectionIterator->fetch()) {
		if (!empty($arSection['PICTURE'])) {
			$arPictureIds[$arSection['PICTURE']] = $arSection['ID'];
		}
	}

	$filesIterator = \Bitrix\Main\FileTable::getList(array(
		'filter' => array(
			'ID' => array_keys($arPictureIds)
		),
		'cache' => array(
			'ttl' => $arParams['CACHE_TIME'],
		)
	));

	$uploadDir = \Bitrix\Main\Config\Option::get('main', 'upload_dir', 'upload');
	while($arFile = $filesIterator->fetch()) {
		if (isset($arPictureIds[$arFile['ID']])) {
			$iSectionId = $arPictureIds[$arFile['ID']];
			if (isset($arSectionItems[$iSectionId])) {
				$arFile['SRC'] = '/'.$uploadDir.'/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'];
				$arSectionItems[$iSectionId]['PICTURE'] = $arFile;
			}
		}
	}

	unset($arSectionItems);
}

if (!function_exists('recursiveAlignItems')) {
	function recursiveAlignItems(&$arItems, $level = 1, &$i = 0)
	{
		$returnArray = array();

		if (!is_array($arItems)) {
			return $returnArray;
		}

		for (
			$currentItemKey = 0, $countItems = count($arItems);
			$i < $countItems;
			++$i
		) {
			$arItem = $arItems[$i];

			if ($arItem['DEPTH_LEVEL'] == $level) {
				$returnArray[$currentItemKey++] = $arItem;
			} elseif ($arItem['DEPTH_LEVEL'] > $level) {
				$returnArray[$currentItemKey - 1]['SUB_ITEMS'] = recursiveAlignItems(
					$arItems,
					$level + 1,
					$i
				);
			} elseif ($level > $arItem['DEPTH_LEVEL']) {
				--$i;
				break;
			}
		}

		return $returnArray;
	}
}

$arResult = recursiveAlignItems($arResult);
