<?php

namespace Redsign\Megamart;

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

class OrderUtils {

	/**
	 * @param  string  $siteId SITE_ID
	 * @return boolean
	 */
	public static function isUseMinPrice($siteId = SITE_ID)
	{
		return Option::get('redsign.megamart', 'sale_use_order_min_price', "N", $siteId) == 'Y';
	}

	/**
	 * @param  number $nSum   order price
	 * @param  string $siteid SITE_ID
	 * @return boolean
	 */
	public static function checkMinPrice($nSum, $siteId = SITE_ID)
	{
		$nOrderMinPrice = self::getMinPrice($siteId);
		return $nSum >= $nOrderMinPrice;
	}

	/**
	 * @param  string $siteId SITE_ID
	 * @return boolean
	 */
	public static function getMinPrice($siteId = SITE_ID)
	{
		return (float) Option::get('redsign.megamart', 'sale_order_min_price', 0, $siteId);
	}

	public static function OnSaleComponentOrderCreated($order, &$arUserResult, $request, &$arParams, &$arResult, &$arDeliveryServiceAll, &$arPaySystemServiceAll)
	{
		$isUseOrderMinPrice = \Redsign\Megamart\OrderUtils::isUseMinPrice();

		if (!$isUseOrderMinPrice)
			return;

		$basket  = $order->getBasket();

		$arResult['JS_DATA']['ALLOW_ORDER'] = $arResult['ALLOW_ORDER'] = \Redsign\Megamart\OrderUtils::checkMinPrice($basket->getPrice());

		if ($arResult['ALLOW_ORDER'])
			return;

		$sErrorMessage = \Bitrix\Main\Config\Option::get(
			'redsign.megamart',
			'sale_order_min_price_error_text',
			''
		);

		$nMinPrice = \Redsign\MegaMart\OrderUtils::getMinPrice();

		$arSearch = ['#MIN_PRICE#', '#PRICE#', '#DIFF_PRICE#'];
		$arReplacements = [
			$nMinPrice,
			$basket->getPrice(),
			abs($basket->getPrice() - $nMinPrice)
		];

		$arResult['ERROR_SORTED']['MAIN'][] = str_replace($arSearch, $arReplacements, $sErrorMessage);
	}
}
