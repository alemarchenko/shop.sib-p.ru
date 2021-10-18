<?php

namespace Redsign\MegaMart\Sale;

use \Bitrix\Main;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

class CashBack
{

	const MODULE_ID = 'redsign.megamart';
	const EVENT_NAME = 'RS_MM_BONUS_ACCOUNT_TOP_UP';

	public static function OnAfterUserAccountUpdate($accountId, $arFields)
	{
		if (!isset($arFields['CASHBACK']) || $arFields['CASHBACK'] != 'Y')
			return;

		$userAccountId = +$accountId;
		$arNewAccountData = \CSaleUserAccount::GetByID($userAccountId);

		$userId = +$arNewAccountData['USER_ID'];
		$timestampX = $arNewAccountData['TIMESTAMP_X'];

		$arFilter = array(
			'USER_ID' => $userId,
			'TIMESTAMP_X' => $timestampX,
			'DEBIT' => 'Y',
		);

		$rsTransact = \CSaleUserTransact::GetList(
			array(),
			$arFilter
		);

		if ($arTransact = $rsTransact->Fetch())
		{
			$arEventFields = array(
				'EVENT_NAME' => self::EVENT_NAME,
			);

			$rsMess = \CEventMessage::GetList($by='id', $order='desc', ['EVENT_NAME' => self::EVENT_NAME]);
			while ($arMess = $rsMess->GetNext())
			{
				$arEventFields['LID'] = $arMess['SITE_ID'];
			}

			$rsUser = \CUser::GetByID($arTransact['USER_ID']);
			if ($arUser = $rsUser->Fetch())
			{
				$arEventFields['C_FIELDS']['USER_NAME'] = \CUser::FormatName(\CSite::GetNameFormat(true),
					array(
						'TITLE' => $arUser['TITLE'],
						'NAME' => $arUser['NAME'],
						'SECOND_NAME' => $arUser['SECOND_NAME'],
						'LAST_NAME' => $arUser['LAST_NAME'],
						'LOGIN' => $arUser['LOGIN'],
					),
					true,
					true
				);
				$arEventFields['C_FIELDS']['EMAIL_TO'] = $arUser['EMAIL'];
			}

			$arEventFields['C_FIELDS']['BONUS_DATE'] = $arTransact['TIMESTAMP_X'];
			$arEventFields['C_FIELDS']['PRINT_BONUS_AMOUNT'] = \CurrencyFormat($arTransact['AMOUNT'], $arTransact['CURRENCY']);
			$arEventFields['C_FIELDS']['ORDER_ID'] = ($arTransact['ORDER_ID'])? $arTransact['ORDER_ID'] : '' ;
			$arEventFields['C_FIELDS']['SALE_EMAIL'] = Option::get('sale', 'order_email', 'order@'.$_SERVER['SERVER_NAME']);

			try {
				$sendMess = \Bitrix\Main\Mail\Event::send($arEventFields);
			} catch (Exception $e) {
				echo 'ooops, some trouble '.$e->getMessage();
			}
		}
	}

	public static function addBonus($entity)
	{
		global $DB;

		if (!empty($entity))
		{
			if ($entity instanceof \Bitrix\Main\Event)
			{
				$parameters = $entity->getParameters();
				$order = $parameters['ENTITY'];
			}
			elseif ($entity instanceof \Bitrix\Sale\Order)
			{
				$order = $entity;
			}


			if (!$order instanceof \Bitrix\Sale\Order)
			{
				return false;
			}

			$paymentCollection = $order->getPaymentCollection();

			if (!$order->isPaid() && !$paymentCollection->isPaid())
			{
				return false;
			}

			if ($paymentCollection->isExistsInnerPayment())
			{
				return false;
			}

			$bonusValue = 0;
			$userId = $order->getUserId();
			$siteId = $order->getSiteId();

			if (!empty($userId))
			{
				if (Loader::includeModule('sale'))
				{
					if (Loader::includeModule('currency'))
					{
						$currencyCode = \Bitrix\Currency\CurrencyManager::getBaseCurrency();

						if (!$arUserAccount = \CSaleUserAccount::GetByUserID($userId, $currencyCode))
						{
							$arNewAccountFields = array('USER_ID' => $userId, 'CURRENCY' => $currencyCode, 'CURRENT_BUDGET' => 0);
							$accountID = \CSaleUserAccount::Add($arNewAccountFields);
							if (!empty($accountID))
							{
								$arUserAccount = array_merge($arNewAccountFields, array(
									'ID' => $accountID,
									'NOTES' => '',
									'LOCKED' => '',
									'DATE_LOCKED' => ''
								));
							}
						}
					}

					if (!empty($arUserAccount) && $arUserAccount['LOCKED'] != 'Y')
					{
						$basket = $order->getBasket();
						$basketItems = $basket->getBasketItems();

						if (Loader::includeModule('iblock'))
						{
							foreach ($basketItems as $basketItem)
							{
								if ($basketItem->getFinalPrice() <= 0)
								{
									continue;
								}

								$productId = $basketItem->getProductId();

								$dbProduct = \CIBlockElement::GetByID($productId);

								if ($arProduct = $dbProduct->GetNext())
								{
									$productBonusType = Option::get(self::MODULE_ID, 'sale_order_bonus_type', 0, $siteId);
									$productBonusValue = 0;

									$productPrice = $basketItem->getFinalPrice();
									$productQuantity = $basketItem->getQuantity();
									$priceTypeId = $basketItem->getField('PRICE_TYPE_ID');
									$priceCurrency = $basketItem->getCurrency();

									$dbBonus = \CIBlockElement::GetProperty($arProduct['IBLOCK_ID'], $arProduct['ID'], array(), array('CODE' => 'BONUS'));
									$arBonus = $dbBonus->Fetch();

									if (!empty($arBonus['VALUE']))
									{
										$productBonusValue = ($arBonus['VALUE'] * $productQuantity);
									}
									else
									{
										$arParentSkuProduct = \CCatalogSku::GetProductInfo($arProduct['ID']);

										if (is_array($arParentSkuProduct))
										{
											$dbBonusParentProduct = \CIBlockElement::GetProperty($arParentSkuProduct['IBLOCK_ID'], $arParentSkuProduct['ID'], array(), array('CODE' => 'BONUS'));
											if ($arBonusParentProduct = $dbBonusParentProduct->Fetch())
											{
												if (!empty($arBonusParentProduct['VALUE']))
												{
													$productBonusValue = ($arBonusParentProduct['VALUE'] * $productQuantity);
												}
												else
												{
													if ($productBonusType == 'P')
													{
														$productBonusValue = $productPrice * Option::get(self::MODULE_ID, 'sale_order_bonus', 0, $siteId) / 100;
													}
													else
													{
														$productBonusValue = Option::get(self::MODULE_ID, 'sale_order_bonus', 0, $siteId) * $productQuantity;
													}
												}
											}
											else
											{
												if ($productBonusType == 'P')
												{
													$productBonusValue = $productPrice * Option::get(self::MODULE_ID, 'sale_order_bonus', 0, $siteId) / 100;
												}
												else
												{
													$productBonusValue = Option::get(self::MODULE_ID, 'sale_order_bonus', 0, $siteId) * $productQuantity;
												}
											}
										}
										else
										{
											if ($productBonusType == 'P')
											{
												$productBonusValue = $productPrice * Option::get(self::MODULE_ID, 'sale_order_bonus', 0, $siteId) / 100;
											}
											else
											{
												$productBonusValue = Option::get(self::MODULE_ID, 'sale_order_bonus', 0, $siteId) * $productQuantity;
											}
										}
									}

									if ($productBonusValue > 0)
									{
										$productBonusValue = \Bitrix\Catalog\Product\Price::roundPrice(
											$priceTypeId,
											$productBonusValue,
											$priceCurrency
										);

										$bonusValue += $productBonusValue;
									}
								}
							}
						}

						// if (Option::get(self::MODULE_ID, 'sale_order_total_bonus_type', 'F', $siteId) == 'P')
						// {
						// 	$bonusValue += $order->getPrice() * Option::get(self::MODULE_ID, 'sale_order_total_bonus', 0, $siteId) / 100;
						// }
						// else
						// {
						// 	$bonusValue += Option::get(self::MODULE_ID, 'sale_order_total_bonus', 0, $siteId);
						// }

						if (!empty($bonusValue))
						{
							$transactDate = date($DB->DateFormatToPHP(\CSite::GetDateFormat('FULL', $siteId)));

							\CSaleUserTransact::Add(
								array(
									'USER_ID' => $arUserAccount['USER_ID'],
									'AMOUNT' => $bonusValue,
									'CURRENCY' => $arUserAccount['CURRENCY'],
									'DEBIT' => 'Y',
									'DESCRIPTION' => Loc::getMessage(
										'REDSIGN_REDSIGN_HANDLERS_TRANSACT_BONUS_DESCRIPTION',
										array(
											'#ORDER_ID#' => $order->getId(),
										)
									),
									'ORDER_ID' => $order->getId(),
									'TRANSACT_DATE' => $transactDate,
								)
							);

							\CSaleUserAccount::Update(
								$arUserAccount['ID'],
								array(
									'USER_ID' => $arUserAccount['USER_ID'],
									'CURRENT_BUDGET' => ($arUserAccount['CURRENT_BUDGET'] + $bonusValue),
									'CURRENCY' => $arUserAccount['CURRENCY'],
									'NOTES' => $arUserAccount['NOTES'],
									'LOCKED' => $arUserAccount['LOCKED'],
									'DATE_LOCKED' => $arUserAccount['DATE_LOCKED'],
									'TIMESTAMP_X' => $transactDate,

									// to event
									'CASHBACK' => 'Y',
								)
							);
						}

					}
				}

			}

		}

		return $order;
	}
}
