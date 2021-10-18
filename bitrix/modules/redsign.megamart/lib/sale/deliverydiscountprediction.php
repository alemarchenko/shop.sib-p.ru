<?php

namespace Redsign\MegaMart\Sale;

use Bitrix\Sale\Discount\Context\BaseContext;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Order;

class DeliveryDiscountPrediction
{
	const PERC_UNIT = 'Perc';

	private $discountService;

	public function __construct($discountService)
	{
		$this->discountService = $discountService;
	}

	public function getFirstMatching($basket)
	{
		$discounts = $this->discountService->getDeliveryDiscounts();
		
		if (is_iterable($discounts) > 0)
		{
			$discountsData = $this->getDiscountsData($discounts);
			$basketValue = $this->getBasketPrice($basket);

			foreach ($discountsData as $data)
			{
				if ($data['VALUE'] >= $basketValue)
				{
					return $data;
				}
			}
		}

		return false;
	}

	private function getBasketPrice(Basket $basket)
	{
		$order = \Bitrix\Sale\Order::create(
			$basket->getSiteId(),
			$this->discountService->getContext()->getUserId()
		);

		$order->setBasket($basket);
		// $discount = $order->getDiscount();

		return $order->getField('PRICE');
	}


	private function getDiscountsData(array $discounts)
	{
		$data = [];

		foreach ($discounts as $discountId => $discount)
		{
			$discountData = [];
			$conditions = isset($discount['CONDITIONS']['CHILDREN']) ? $discount['CONDITIONS']['CHILDREN'] : [];
			$actions = isset($discount['ACTIONS']['CHILDREN']) ? $discount['ACTIONS']['CHILDREN'] : [];

			foreach ($conditions as $condition)
			{
				switch ($condition['CLASS_ID'])
				{
					case 'CondBsktAmtGroup':
						$discountData['VALUE'] = $condition['DATA']['Value'];
						break;

					case 'CondSaleDelivery':
						$discountData['DELIVERY_ID'] = $condition['DATA']['value'];
						break;
				}
			}

			foreach ($actions as $action)
			{
				switch ($action['CLASS_ID'])
				{
					case 'ActSaleDelivery':
						$discountData['DISCOUNT'] = $action['DATA']['Value'];
						$discountData['DISCOUNT_UNIT'] = $action['DATA']['Unit'];
						break;
				}
			}

			$data[$discountId] = $discountData;
		}

		return $data;
	}
}