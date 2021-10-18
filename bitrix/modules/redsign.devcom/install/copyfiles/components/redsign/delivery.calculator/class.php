<?php

use \Bitrix\Currency;
use Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Sale\Location;
use \Bitrix\Sale\Order;

Loc::loadMessages(__FILE__);

class RSDeliveryCalculator extends CBitrixComponent
{
	protected $cacheKey = [];
	protected $cacheAddon = [];

	protected $sCurrency;
	protected $sLocationFrom;
	protected $sLocationTo;
	protected $sLocationZip;

	protected $shipment;
	protected $product = [];

	protected $arDeliveryServices;

	public function onPrepareComponentParams($arParams)
	{
		$this->arResult['ORIGINAL_PARAMS'] = $arParams;
		$this->sLocationTo = $arParams['LOCATION_TO'];
		$this->sLocationFrom = $arParams['LOCATION_FROM'];
		$this->sLocationZip = $arParams['LOCATION_ZIP'];

		
		if (Loader::includeModule('catalog'))
		{
			$baseCurrency = Currency\CurrencyManager::getBaseCurrency();
		}

		$this->sCurrency = !empty($arParams['CURRENCY']) ? $arParams['CURRENCY'] : $baseCurrency;

		$this->elementId = $arParams['ELEMENT_ID'] = (int) $arParams['ELEMENT_ID'];
		$arParams['QUANTITY'] = (float) $arParams['QUANTITY'];
		$arParams['PREFIX'] = isset($arParams['PREFIX']) ? $arParams['PREFIX'] : '';

		return $arParams;
	}

	protected function getProductData()
	{
		global $USER;
		$arPrice = CCatalogProduct::GetOptimalPrice(
			$this->arParams['ELEMENT_ID'],
			$this->arParams['QUANTITY'],
			$USER->GetUserGroupArray()
		);

		if (isset($arPrice['RESULT_PRICE']))
		{
			$this->product['BASE_PRICE'] = $arPrice['RESULT_PRICE']['BASE_PRICE'];
			$this->product['DISCOUNT_PRICE'] = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
			$this->product['PRICE'] = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
			$this->product['CURRENCY'] = $arPrice['PRICE']['CURRENCY'];
		}

		$product = \Bitrix\Catalog\ProductTable::getRowById($this->arParams['ELEMENT_ID']);

		if (!$product)
		{
			throw new \Bitrix\Main\SystemException('ELEMENT NOT FOUND');
		}

		$this->product['WEIGHT'] = $product['WEIGHT'];
		$this->product['QUANTITY'] = $this->arParams['QUANTITY'];
		$this->product['DIMENSIONS'] = serialize([
			'HEIGHT' => $product['HEIGHT'],
			'WIDTH' => $product['WIDTH'],
			'LENGTH' => $product['LENGTH'],
		]);
	}

	protected function getShipment()
	{
		global $USER;

		$userId = $USER->GetID() ? $USER->GetID() : CSaleUser::GetAnonymousUserID();
		
		$registry =  \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);
		/** @var Order $orderClassName */
		$orderClassName = $registry->getOrderClassName();
		$order = $orderClassName::create($this->getSiteId(), $userId);
		
		$this->initProperties($order);
		$this->initBasket($order);
		$shipment = $this->initShipment($order);

		$this->shipment = $shipment;
	}

	protected function getDeliveryServices()
	{
		$arDeliveryServicesAll = \Bitrix\Sale\Delivery\Services\Manager::getRestrictedObjectsList($this->shipment);

		if (is_array($this->arParams['DELIVERY']) && count($this->arParams['DELIVERY']) > 0)
		{
			foreach ($this->arParams['DELIVERY'] as $deliveryId)
			{
				if (!array_key_exists($deliveryId, $this->arParams['DELIVERY']))
				{
					unset($this->arParams['DELIVERY'][$deliveryId]);
				}
			}
		}

		$this->arDeliveryServices = $arDeliveryServicesAll;
	}

	protected function getLocationData($locationCode)
	{
		if (is_null($locationCode)) {
			return null;
		}

		$locationIterator = Location\LocationTable::getList([
			'filter' => [
				'=CODE' => $locationCode,
				'=NAME.LANGUAGE_ID' => LANGUAGE_ID,
				'=TYPE.NAME.LANGUAGE_ID' => LANGUAGE_ID,
			],
			'select' => [
				'ID',
				'CODE',
				'LATITUDE',
				'LONGITUDE',
				'LOCATION_TYPE' => 'TYPE.NAME.NAME',
				'LOCATION_NAME' => 'NAME.NAME',
			],
		]);
	   

		return $locationIterator->fetch();
	}

	protected function prepareData()
	{
		$this->getProductData();
		$this->getShipment();
		$this->getDeliveryServices();
	}

	protected function formatResult()
	{
		$this->arResult['DELIVERIES'] = [];
		foreach ($this->arDeliveryServices as $deliveryId => $arDeliveryObj)
		{
			$arDelivery = [];

			$arDelivery['NAME'] = $arDeliveryObj->getNameWithParent();
			$arDelivery['DESCRIPTION'] = $arDeliveryObj->getDescription();
			$arDelivery['SORT'] = $arDeliveryObj->getSort();
			$arDelivery['PICTURE_PATH'] = $arDeliveryObj->getLogotipPath();

			$calcResult = $arDeliveryObj->calculate($this->shipment);
			$arDelivery['CALCULATION'] = [];
			$arDelivery['CALCULATION']['IS_SUCCESS'] = $calcResult->isSuccess();
			$arDelivery['CALCULATION']['PRICE'] = $calcResult->getPrice();
			$arDelivery['CALCULATION']['FORMAT_PRICE'] = CurrencyFormat($calcResult->getPrice(), $this->sCurrency);
			$arDelivery['CALCULATION']['DESCRIPTION'] = $calcResult->getDescription();
			$arDelivery['CALCULATION']['PERIOD'] = $calcResult->getPeriodDescription();
			$arDelivery['CALCULATION']['PACKS'] = $calcResult->getPacksCount();

			$this->arResult['DELIVERIES'][] = $arDelivery;
		}

		$this->arResult['PRODUCT'] = $this->product;
		$this->arResult['PRODUCT']['FULL_PRICE'] = $this->product['PRICE'] * $this->product['QUANTITY'];
		$this->arResult['PRODUCT']['PRICE_FORMAT'] = CurrencyFormat($this->product['PRICE'], $this->product['CURRENCY']);
		$this->arResult['PRODUCT']['FULL_PRICE_FORMAT'] = CurrencyFormat($this->arResult['PRODUCT']['FULL_PRICE'], $this->product['CURRENCY']);

		$this->arResult['CURRENCY'] = $this->sCurrency;
		$this->arResult['LOCATION_FROM'] = $this->getLocationData($this->sLocationFrom);
		$this->arResult['LOCATION_TO'] = $this->getLocationData($this->sLocationTo);
	}

	protected function readDataFromCache()
	{
		return false;
	}

	protected function putDataToCache()
	{
		return;
	}

	protected function abortDataCache()
	{
		return;
	}

	protected function checkModules()
	{
		$needModules = ['iblock', 'sale', 'catalog', 'currency'];
		foreach ($needModules as $module)
		{
			if (!Loader::includeModule($module))
			{
				throw new LoaderException(Loc::getMessage('RSDC_'.strtoupper($module).'_MODULE_NOT_INSTALLED'));
			}
		}
	}

	public function executeComponent()
	{
		try
		{
			$this->checkModules();

			if (!$this->readDataFromCache())
			{
				$this->prepareData();
				$this->formatResult();

				$this->setResultCacheKeys([]);
				$this->includeComponentTemplate();
				$this->putDataToCache();
			}
		}
		catch (Exception $e)
		{
			$this->abortDataCache();
			$this->__showError($e->getMessage(), $e->getCode());
		}
	}


	/**
	 * @param Order $order
	 */
	protected function initProperties(Order $order)
	{
		$props = $order->getPropertyCollection();
		if ($loc = $props->getDeliveryLocation())
		{
			$loc->setValue($this->sLocationTo);
		}

		if ($loc = $props->getDeliveryLocationZip())
		{
			$loc->setValue($this->sLocationZip);
		}
	}

	/**
	 * @param Order $order
	 * @throws Main\ObjectNotFoundException
	 */
	protected function initBasket(Order $order)
	{
		$registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);

		/** @var \Bitrix\Sale\Basket $basketClassName */
		$basketClassName = $registry->getBasketClassName();
		$basket = $basketClassName::create(SITE_ID);
		
		$basketItemClassName = $registry->getBasketItemClassName();
		$basketItem = $basketItemClassName::create($basket, 'catalog', $this->arParams['ELEMENT_ID']);

		$basketItem->setFields($this->product);
		$basket->addItem($basketItem);

		$order->setBasket($basket);
	}

	/**
	 * @param Order $order
	 * @return Shipment
	 * @throws Main\ArgumentTypeException
	 * @throws Main\NotSupportedException
	 */
	public function initShipment(Order $order)
	{
		$shipmentCollection = $order->getShipmentCollection();
		$shipment = $shipmentCollection->createItem();
		$shipmentItemCollection = $shipment->getShipmentItemCollection();
		$shipment->setField('CURRENCY', $order->getCurrency());

		/** @var Sale\BasketItem $item */
		foreach ($order->getBasket() as $item)
		{
			/** @var Sale\ShipmentItem $shipmentItem */
			$shipmentItem = $shipmentItemCollection->createItem($item);
			$shipmentItem->setQuantity($item->getQuantity());
		}

		return $shipment;
	}
}
