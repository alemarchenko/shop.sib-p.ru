<?php

namespace Yandex\Market\Trading\Entity\Reference;

use Yandex\Market;
use Bitrix\Main;

abstract class Product
{
	protected $environment;

	public function __construct(Environment $environment)
	{
		$this->environment = $environment;
	}

	/**
	 * ����� ����� ��� ������������� ��������������� ������ � offerId �������
	 *
	 * @param $iblockId
	 *
	 * @return array{ID: string, VALUE: string}[]
	 */
	public function getFieldEnum($iblockId)
	{
		throw new Market\Exceptions\NotImplementedMethod(static::class, 'getFieldEnum');
	}

	/**
	 * ������������ offerId ������� � �������������� ������
	 *
	 * @param string[] $offerIds
	 * @param array{IBLOCK: string, FIELD: string}[] $skuMap
	 *
	 * @return array<string, int> serviceOfferId => bitrixProductId
	 */
	public function getOfferMap($offerIds, $skuMap)
	{
		throw new Market\Exceptions\NotImplementedMethod(static::class, 'getOfferMap');
	}

	/**
	 * ������ �� ������� ��� ������������ �������
	 *
	 * @param int[] $productIds
	 * @param array<int, float[]>|null $quantities
	 * @param array $context
	 *
	 * @return array<int|string, array>
	 */
	public function getBasketData($productIds, $quantities = null, array $context = [])
	{
		return [];
	}
}