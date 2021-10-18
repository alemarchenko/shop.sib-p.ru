<?php
namespace Redsign\MegaMart;

class IblockElementExt
{
	public static function getPrice($item, $params)
	{
		$price = array();
		$params['PRICE_DECIMALS'] = intval($params['PRICE_DECIMALS']) >= 0
			? intval($params['PRICE_DECIMALS'])
			: 0;

		if (
			$params['PROP_PRICE'] != ''
			&& $params['PROP_DISCOUNT'] != ''
			&& $params['PROP_CURRENCY'] != ''
		)
		{
			if ($item['PROPERTIES'][$params['PROP_CURRENCY']]['VALUE'] == '')
			{
				return false;
			}

			$price = self::formatPrices($item['PROPERTIES'], $params);
		}

		return $price;
	}

	public static function addPrices(&$arItems, $params)
	{
		$price = array();

		$params['PRICE_DECIMALS'] = intval($params['PRICE_DECIMALS']) >= 0
			? intval($params['PRICE_DECIMALS'])
			: 0;

		if (
			$params['PROP_PRICE'] != ''
			&& $params['PROP_DISCOUNT'] != ''
			&& $params['PROP_CURRENCY'] != ''
		)
		{
			foreach ($arItems as $key => $arItem)
			{
				if ($arItem['PROPERTIES'][$params['PROP_CURRENCY']]['VALUE'] == '')
				{
					continue;
				}

				$price = self::formatPrices($arItem['PROPERTIES'], $params);

				$arItems[$key]['RS_PRICES'] = $price;
			}
		}
	}

	public static function formatPrices($arItem, $params)
	{
		$price['CURRENCY'] = $arItem[$params['PROP_CURRENCY']]['VALUE'];

		$price['BASE_PRICE'] = floatval($arItem[$params['PROP_PRICE']]['VALUE']);
		$price['PRINT_BASE_PRICE'] = str_replace(
			'#',
			number_format($price['BASE_PRICE'], $params['PRICE_DECIMALS'], '.', ' '),
			$arItem[$params['PROP_CURRENCY']]['VALUE']
		);

		$price['DISCOUNT'] = floatval($arItem[$params['PROP_DISCOUNT']]['VALUE']);
		$price['PRINT_DISCOUNT'] = str_replace(
			'#',
			number_format($price['DISCOUNT'], $params['PRICE_DECIMALS'], '.', ' '),
			$arItem[$params['PROP_CURRENCY']]['VALUE']
		);

		$price['UNROUND_PRICE'] = $price['BASE_PRICE'] - $price['DISCOUNT'];

		$price['PRICE'] = $price['UNROUND_PRICE'];
		// $price['PRICE'] = floatval(
			// number_format($price['UNROUND_PRICE'], $params['PRICE_DECIMALS'], '.', '')
		// );
		$price['PRINT_PRICE'] = str_replace(
			'#',
			number_format($price['PRICE'], $params['PRICE_DECIMALS'], '.', ' '),
			$arItem[$params['PROP_CURRENCY']]['VALUE']
		);


		$price['PERCENT'] = ($price['DISCOUNT'] > 0 && $price['BASE_PRICE'] > 0)
			? round($price['DISCOUNT'] / $price['BASE_PRICE'] * 100)
			: 0;

		$price['RATIO_BASE_PRICE'] = $price['BASE_PRICE'];
		$price['PRINT_RATIO_BASE_PRICE'] = $price['PRINT_BASE_PRICE'];
		$price['RATIO_PRICE'] = $price['PRICE'];
		$price['PRINT_RATIO_PRICE'] = $price['PRINT_PRICE'];
		$price['RATIO_DISCOUNT'] = $price['DISCOUNT'];
		$price['PRINT_RATIO_DISCOUNT'] = $price['PRINT_DISCOUNT'];


		$price['VALUE'] = $price['RATIO_BASE_PRICE'];
		$price['PRINT_VALUE'] = $price['PRINT_RATIO_BASE_PRICE'];
		$price['DISCOUNT_VALUE'] = $price['RATIO_PRICE'];
		$price['PRINT_DISCOUNT_VALUE'] = $price['PRINT_RATIO_PRICE'];

		return $price;

	}

	public static function fixCatalogItemFillAllPrices(&$item)
	{
		if (is_array($item['ITEM_ALL_PRICES']) && count($item['ITEM_ALL_PRICES']) > 0)
		{
			foreach ($item['ITEM_ALL_PRICES'] as $iRangeKey => $arRange)
			{
				if (is_array($arRange['PRICES']) && count($arRange['PRICES']) > 0)
				{
					foreach ($arRange['PRICES'] as $iPriceKey => $arPrice)
					{
						$item['ITEM_ALL_PRICES'][$iRangeKey]['PRICES'][$iPriceKey]['PRINT_RATIO_PRICE'] = \CCurrencyLang::CurrencyFormat(
							$arPrice['RATIO_PRICE'],
							$arPrice['CURRENCY'],
							true
						);

						$item['ITEM_ALL_PRICES'][$iRangeKey]['PRICES'][$iPriceKey]['PRINT_RATIO_BASE_PRICE'] = \CCurrencyLang::CurrencyFormat(
							$arPrice['RATIO_BASE_PRICE'],
							$arPrice['CURRENCY'],
							true
						);

						$item['ITEM_ALL_PRICES'][$iRangeKey]['PRICES'][$iPriceKey]['PRINT_RATIO_DISCOUNT'] = \CCurrencyLang::CurrencyFormat(
							$arPrice['RATIO_DISCOUNT'],
							$arPrice['CURRENCY'],
							true
						);

						$item['ITEM_ALL_PRICES'][$iRangeKey]['PRICES'][$iPriceKey]['PERCENT'] = $arPrice['BASE_PRICE'] > 0
							? roundEx(100 * $arPrice['DISCOUNT'] / $arPrice['BASE_PRICE'], 0)
							: 0 ;
					}
					unset($iPriceKey, $arPrice);
				}
			}
			unset($iRangeKey, $arRange);
		}
	}


	public static function setGiftDiscountToAllPrice(&$item)
	{
		if (is_array($item['ITEM_ALL_PRICES']) && count($item['ITEM_ALL_PRICES']) > 0)
		{
			foreach ($item['ITEM_ALL_PRICES'] as $priceBlockIndex => $priceBlock)
			{
				foreach ($priceBlock['PRICES'] as $priceType => $arPrice)
				{
					$selectedPrice =& $item['ITEM_ALL_PRICES'][$priceBlockIndex]['PRICES'][$priceType];

					$selectedPrice['PRICE'] = $selectedPrice['DISCOUNT'];
					$selectedPrice['PRINT_PRICE'] = $selectedPrice['PRINT_DISCOUNT'];
					$selectedPrice['DISCOUNT'] = $selectedPrice['BASE_PRICE'];
					$selectedPrice['PRINT_DISCOUNT'] = $selectedPrice['PRINT_BASE_PRICE'];
					$selectedPrice['RATIO_PRICE'] = $selectedPrice['RATIO_DISCOUNT'];
					$selectedPrice['PRINT_RATIO_PRICE'] = $selectedPrice['PRINT_RATIO_DISCOUNT'];
					$selectedPrice['RATIO_DISCOUNT'] = $selectedPrice['RATIO_BASE_PRICE'];
					$selectedPrice['PRINT_RATIO_DISCOUNT'] = $selectedPrice['PRINT_RATIO_BASE_PRICE'];
					$selectedPrice['PERCENT'] = 100;
				}
			}
		}
	}
}
