<?php

namespace Redsign\MegaMart\Sale;

use Bitrix\Sale\Discount\Context\BaseContext;

class DiscountService
{
	const EXECUTE_MODULE_FILTER = ['all', 'sale', 'catalog'];

	const DISCOUNT_PRESETS_DELIVERY = [
		'Sale\Handlers\DiscountPreset\Delivery',
		'Sale\Handlers\DiscountPreset\FreeDelivery'
	];

	protected $context;
	protected $siteId;
	
	public function __construct(BaseContext $context, $siteId)
	{
		$this->context = $context;
		$this->siteId = $siteId;
	}

	public function getContext()
	{
		return $this->context;
	}

	public function getDiscountIdsByPreset($preset)
	{
		$discountIds = [];

		$groupDiscountIterator = \Bitrix\Sale\Internals\DiscountGroupTable::getList(array(
			'select' => array('DISCOUNT_ID'),
			'filter' => [
				'@GROUP_ID' => $this->context->getUserGroups(),
				'=ACTIVE' => 'Y',
				'=DISCOUNT.PRESET_ID' => $preset
			],
			'order' => array('DISCOUNT_ID' => 'ASC')
		));
		while ($groupDiscount = $groupDiscountIterator->fetch())
		{
			$discountIds[] = $groupDiscount['DISCOUNT_ID'];
		}

		return $discountIds;
	}

	public function getDiscountsByPreset($preset)
	{
		$discountIds = $this->getDiscountIdsByPreset($preset);

		if (count($discountIds) > 0)
		{
			$discounts = \Bitrix\Sale\Discount\RuntimeCache\DiscountCache::getInstance()->getDiscounts(
				$discountIds,
				self::EXECUTE_MODULE_FILTER,
				$this->siteId,
				[]
			);
		}

		return $discounts;
	}

	public function getDeliveryDiscounts()
	{
		return $this->getDiscountsByPreset(self::DISCOUNT_PRESETS_DELIVERY);
	}
}