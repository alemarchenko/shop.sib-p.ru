<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

if (!function_exists('recursiveAlignItems'))
{
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
		)
		{
			$arItem = $arItems[$i];

			if ($arItem['DEPTH_LEVEL'] == $level)
			{
				$returnArray[$currentItemKey++] = $arItem;
			}
			elseif ($arItem['DEPTH_LEVEL'] > $level)
			{
				$returnArray[$currentItemKey - 1]['SUB_ITEMS'] = recursiveAlignItems(
					$arItems,
					$level + 1,
					$i
				);
			}
			elseif ($level > $arItem['DEPTH_LEVEL'])
			{
				--$i;
				break;
			}
		}

		return $returnArray;
	}
}


$arResult = recursiveAlignItems($arResult);

$arParams['OPEN_CATALOG'] = $arParams['HEAD_TYPE'] == 'type6' || $arParams['HEAD_TYPE'] == 'type7';
