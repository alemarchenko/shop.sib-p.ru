<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}


if (count($arResult['SEARCH']) > 0) {
	$arIblocks = array();
	$arIblocksIds = array();
	$arIblocksItems = array();
	$arOtherItems = array();

	foreach ($arResult['SEARCH'] as $arItem) {
		if ($arItem['MODULE_ID'] == 'iblock' && strpos($arItem['ITEM_ID'], 'S') !== 0) {
			if (!in_array($arItem['PARAM2'], $arIblocksIds)) {
				$arIblocksIds[] = $arItem['PARAM2'];
				$arIblockItems[$arItem['PARAM2']] = array();
			}
			$arIblockItems[$arItem['PARAM2']][] = $arItem;
		} else {
			$arOtherItems[] = $arItem;
		}
	}



	if (count($arIblocksIds) > 0) {
		$iblockIterator = \Bitrix\Iblock\IblockTable::getList(array(
			'filter' => array(
				'=ID' => $arIblocksIds
			)
		));

		while ($arIblock = $iblockIterator->fetch()) {
			$arIblock['ITEMS'] = $arIblockItems[$arIblock['ID']];
			$arIblocks[] = $arIblock;
		}
	}

	$arResult['SEARCH_EXT'] = array(
		'IBLOCKS' => $arIblocks,
		'OTHER' => array(
			'ITEMS' => $arOtherItems
		)
	);

	unset($arIblocks);
	unset($arIblockIds);
	unset($arIblocksItems);
	unset($arOtherItems);
}
