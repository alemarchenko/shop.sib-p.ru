<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock;
use \Redsign\MegaMart\IblockElementExt;
use \Redsign\MegaMart\MyTemplate;
use \Bitrix\Main\Config\Option;

$component = $this->getComponent();

$arResult['MODULES']['sale'] = Loader::includeModule('sale');
$arResult['MODULES']['redsign.megamart'] = Loader::includeModule('redsign.megamart');
$arResult['MODULES']['redsign.grupper'] = Loader::includeModule('redsign.grupper');

if (!is_array($arResult['CATALOGS'][$arResult['IBLOCK_ID']]) && $arResult['MODULES']['redsign.megamart'])
{
	$params = array(
		'PROP_PRICE' => $arParams['PRICE_PROP'],
		'PROP_DISCOUNT' => $arParams['DISCOUNT_PROP'],
		'PROP_CURRENCY' => $arParams['CURRENCY_PROP'],
		'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
	);

	$arResult['RS_PRICES'] = IblockElementExt::getPrice($arResult, $params);

	$component->arParams['IS_USE_CART'] = isset($arParams['IS_USE_CART']) && $arParams['IS_USE_CART'] === 'Y' ? 'Y' : 'N';
}
else
{
	$component->arParams['IS_USE_CART'] = 'N';

	if ($arResult['CATALOGS'][$arResult['IBLOCK_ID']]['CATALOG_TYPE'] == \CCatalogSku::TYPE_FULL)
	{
		$arResult['OFFERS_IBLOCK_ID'] = $arResult['CATALOGS'][$arResult['IBLOCK_ID']]['IBLOCK_ID'];
	}
}


if ($arParams['ARTNUMBER_PROP'] != '' && $arParams['ARTNUMBER_PROP'] != '-')
{
	$component->arParams['ARTNUMBER_PROP'] = array($arParams['IBLOCK_ID'] => $arParams['ARTNUMBER_PROP']);
}
else
{
	$component->arParams['ARTNUMBER_PROP'] = array();
}

if ($arParams['BRAND_PROP'] != '' && $arParams['BRAND_PROP'] != '-')
{
	$component->arParams['BRAND_PROP'] = array($arParams['IBLOCK_ID'] => $arParams['BRAND_PROP']);
}
else
{
	$component->arParams['BRAND_PROP'] = array();
}

if ($arResult['OFFERS_IBLOCK_ID'])
{
	if ($arParams['OFFER_ARTNUMBER_PROP'] != '' && $arParams['OFFER_ARTNUMBER_PROP'] != '-')
	{
		$component->arParams['ARTNUMBER_PROP'][$arResult['OFFERS_IBLOCK_ID']] = $arParams['OFFER_ARTNUMBER_PROP'];
	}
}

$component->arParams['USE_GIFTS'] = isset($component->arParams['USE_GIFTS']) && $component->arParams['USE_GIFTS'] === 'N' ? 'N' : 'Y';
$component->arParams['DISPLAY_PROPERTIES_MAX'] = (intval($arParams['DISPLAY_PROPERTIES_MAX']) > 0 ? intval($arParams['DISPLAY_PROPERTIES_MAX']) : false);
$component->arParams['OFFERS_PROPERTIES_MAX'] = (intval($arParams['OFFERS_PROPERTIES_MAX']) > 0 ? intval($arParams['OFFERS_PROPERTIES_MAX']) : false);
$component->arParams['SLIDER_SLIDE_COUNT'] = isset($arParams['SLIDER_SLIDE_COUNT']) ? (int)$arParams['SLIDER_SLIDE_COUNT'] : 5;

$component->arParams['SHOW_SLIDER'] = 'N';

if (empty($arParams['PRODUCT_INFO_BLOCK']) && strlen($arParams['PRODUCT_INFO_BLOCK_ORDER']) > 0)
{
	if (is_string($arParams['PRODUCT_INFO_BLOCK_ORDER']))
	{
		$component->arParams['PRODUCT_INFO_BLOCK'] = explode(',', $arParams['PRODUCT_INFO_BLOCK_ORDER']);
	}
}

if (!isset($component->arParams['TABS']) || !is_array($component->arParams['TABS']))
{
	$component->arParams['TABS'] = array();
}

if (is_array($component->arParams['TABS']) && count($component->arParams['TABS']) > 0)
{
	$arResult['TABS'] = $component->arParams['TABS'];

	if (empty($arParams['TABS_ORDER']))
	{
		$component->arParams['TABS_ORDER'] = 'detail,props,comments';
	}

	if (is_string($arParams['TABS_ORDER']))
	{
		$component->arParams['TABS_ORDER'] = explode(',', $arParams['TABS_ORDER']);
	}
}

if (is_array($arParams['BLOCK_LINES']) && count($arParams['BLOCK_LINES']) > 0)
{
	$arResult['BLOCK_LINES'] = $arParams['BLOCK_LINES'];

	if (empty($arParams['BLOCK_LINES_ORDER']))
	{
		$component->arParams['BLOCK_LINES_ORDER'] = '';
	}

	if (is_string($arParams['BLOCK_LINES_ORDER']))
	{
		$component->arParams['BLOCK_LINES_ORDER'] = explode(',', $arParams['BLOCK_LINES_ORDER']);
	}
}

foreach ($arParams as $name => $prop)
{
	if (preg_match('/^PROPERTY_FILE_VIEW_(.+)$/', $name, $arMatches))
	{
		$sPropCode = $arMatches[1];
		if ('' != $arParams[$name] && '-' != $arParams[$name])
		{
			$component->arParams['PROPERTY_FILE_VIEW'][$sPropCode] = $arParams[$name];
		}
		unset($arParams[$arMatches[0]]);
	}
}
$component->arParams['ADD_TO_BASKET_ACTION'] = $arResult['ORIGINAL_PARAMETERS']['ADD_TO_BASKET_ACTION'];
$component->arParams['LINK_BUY1CLICK'] = SITE_DIR.'buy1click/';
if (
	in_array('REQUEST', $arParams['ADD_TO_BASKET_ACTION'])
	&& (!isset($arParams['LINK_BTN_REQUEST']) || strlen($arParams['LINK_BTN_REQUEST']) <= 0)
)
{
	$component->arParams['LINK_BTN_REQUEST'] = SITE_DIR.'product-ask/?element_id=#ELEMENT_ID#';
}

$component->arParams['PRODUCT_DEALS_SHOW'] = isset($arParams['PRODUCT_DEALS_SHOW']) && $arParams['PRODUCT_DEALS_SHOW'] === 'Y' ? 'Y' : 'N';

$component->arParams['SHOW_CASHBACK'] = isset($arParams['SHOW_CASHBACK']) && $arParams['SHOW_CASHBACK'] === 'Y' ? 'Y' : 'N';
if (!$arResult['MODULES']['redsign.megamart'] || Option::get('redsign.megamart', 'use_sale_order_bonus', 'N', SITE_ID) != 'Y')
{
	$component->arParams['SHOW_CASHBACK'] = 'N';
}

$component->arParams['BACKGROUND_COLOR'] = isset($arParams['BACKGROUND_COLOR']) ? trim($arParams['BACKGROUND_COLOR']) : '';
if ($component->arParams['BACKGROUND_COLOR'] === '-')
{
	$component->arParams['BACKGROUND_COLOR'] = '';
}

$arParams = $component->applyTemplateModifications();

$arParams['ADD_TO_BASKET_ACTION'] = $arResult['ORIGINAL_PARAMETERS']['ADD_TO_BASKET_ACTION'];

// OFFERS_SELECTED applyTemplateModifications
$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers)
{
	if (isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]))
	{
		$actualItem = &$arResult['OFFERS'][$arResult['OFFERS_SELECTED']];
	}
	else
	{
		$actualItem = &$arResult['OFFERS'][key(reset($arResult['OFFERS']))];
	}
}
else
{
	$actualItem = &$arResult;
}

// need FILL_ITEM_ALL_PRICES after applyTemplateModifications
if ($haveOffers)
{
	if ($arParams['FILL_ITEM_ALL_PRICES'])
	{
		$bOfferCnt = 0;
		foreach ($arResult['OFFERS'] as $arOffer)
		{
			if (!is_array($arOffer['PRICES']) || count($arOffer['PRICES']) < 2)
			{
				$bOfferCnt++;
			}
		}

		if (is_array($arOffer['PRICES']) && $bOfferCnt == count($arOffer['PRICES']))
		{
			$component->arParams['FILL_ITEM_ALL_PRICES'] = false;
		}
		unset($arOffer, $bOfferCnt);

		// #bitrixwtf
		if ($arResult['MODULES']['redsign.megamart'])
		{
			foreach ($arResult['OFFERS'] as $iOfferKey => $arOffer)
			{
				IblockElementExt::fixCatalogItemFillAllPrices($arResult['OFFERS'][$iOfferKey]);
			}
			unset($iOfferKey, $arOffer);
		}
	}
}
else
{
	if ($arParams['FILL_ITEM_ALL_PRICES'])
	{
		if (
			(!is_array($arResult['PRICES']) || count($arResult['PRICES']) < 2)
			&& (!is_array($arResult['ITEM_ALL_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRICES']) || count($arResult['ITEM_ALL_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRICES']) < 2)
		)
		{
			$component->arParams['FILL_ITEM_ALL_PRICES'] = false;
		}

		// #bitrixwtf
		if ($arResult['MODULES']['redsign.megamart'])
		{
			IblockElementExt::fixCatalogItemFillAllPrices($arResult);
		}
	}
}

if (Loader::includeModule('redsign.devfunc'))
{
	\Redsign\DevFunc\Sale\Location\Region::editCatalogResult($arResult);
	\Redsign\DevFunc\Sale\Location\Region::editCatalogItem($arResult);
}

// $component->arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];
// $arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];

$arElements = array();

// elements
$arElements[$arResult['ID']] = &$arResult;
if (!empty($arResult['OFFERS']) && is_array($arResult['OFFERS']))
{
	foreach ($arResult['OFFERS'] as $offerKey => $offer)
	{
		$arElements[$offer['ID']] = &$arResult['OFFERS'][$offerKey];
	}
	unset($offerKey, $offer);
}

// quickbuy & daysarticle
if (is_array($arElements) && count($arElements) > 0)
{
	$iTime = ConvertTimeStamp(time(), 'FULL');

	if ($arResult['MODULES']['catalog'])
	{
		$default_quantity_trace = Option::get('catalog', 'default_quantity_trace', 'N');
	}

	if (Loader::includeModule('redsign.quickbuy'))
	{
		$arFilter = array(
			'DATE_FROM' => $iTime,
			'DATE_TO' => $iTime,
			'QUANTITY' => 0,
			'ELEMENT_ID' => array_keys($arElements),
		);
		$dbRes = CRSQUICKBUYElements::GetList(array('ID' => 'SORT'), $arFilter);
		while ($arData = $dbRes->Fetch())
		{
			if (
				array_key_exists($arData['ELEMENT_ID'], $arElements)
				&& (
					$default_quantity_trace == 'Y' && $arData['QUANTITY'] > 0
					|| $default_quantity_trace != 'Y'
				)
			)
			{
				$arData['TIMER'] = CRSQUICKBUYMain::GetTimeLimit($arData);

				if (is_array($arElements[$arData['ELEMENT_ID']]['OFFERS']) && count($arElements[$arData['ELEMENT_ID']]['OFFERS']) > 0)
				{
					foreach ($arElements[$arData['ELEMENT_ID']]['OFFERS'] as $offerKey => $offer)
					{
						$arElements[$arData['ELEMENT_ID']]['OFFERS'][$offerKey]['QUICKBUY'] = $arData;
					}
					unset($offerKey, $offer);
				}
				else
				{
					$arElements[$arData['ELEMENT_ID']]['QUICKBUY'] = $arData;
				}
			}
		}
	}

	if (Loader::includeModule('redsign.daysarticle2'))
	{
		$arFilter = array(
			'DATE_FROM' => $iTime,
			'DATE_TO' => $iTime,
			'QUANTITY' => 0,
			'ELEMENT_ID' => array_keys($arElements),
		);
		$dbRes = CRSDA2Elements::GetList(array('ID' => 'SORT'), $arFilter);
		while ($arData = $dbRes->Fetch())
		{
			if (
				array_key_exists($arData['ELEMENT_ID'], $arElements)
				&& (
					$default_quantity_trace == 'Y' && $arData['QUANTITY'] > 0
					|| $default_quantity_trace != 'Y'
				)
			)
			{
				$arData['DINAMICA_EX'] = CRSDA2Elements::GetDinamica($arData);

				if (is_array($arElements[$arData['ELEMENT_ID']]['OFFERS']) && count($arElements[$arData['ELEMENT_ID']]['OFFERS']) > 0)
				{
					foreach ($arElements[$arData['ELEMENT_ID']]['OFFERS'] as $offerKey => $offer)
					{
						$arElements[$arData['ELEMENT_ID']]['OFFERS'][$offerKey]['DAYSARTICLE'] = $arData;
					}
					unset($offerKey, $offer);
				}
				else
				{
					$arElements[$arData['ELEMENT_ID']]['DAYSARTICLE'] = $arData;
				}
			}
		}
	}
}

if ($arResult['DETAIL_TEXT'] != '')
{
	if ($arResult['DETAIL_TEXT_TYPE'] === 'html')
	{
		if (preg_match_all('#<table.*?>.*</table>#is', $arResult['DETAIL_TEXT'], $arMatches))
		{
			$arResult['DETAIL_TEXT'] = preg_replace('#<table.*?>.*</table>#is', '<div class="table-responsive">$0</div>', $arResult['DETAIL_TEXT']);
		}
		if (preg_match_all('#<iframe.*?>.*</iframe>#is', $arResult['DETAIL_TEXT'], $arMatches))
		{
			$arResult['DETAIL_TEXT'] = preg_replace('#<iframe.*?>.*</iframe>#is', '<div class="table-responsive">$0</div>', $arResult['DETAIL_TEXT']);
		}
	}
}

foreach (array_merge($arParams['TABS'], $arParams['BLOCK_LINES']) as $blockName)
{
	if (substr($blockName, 0, 5) == 'prop_')
	{
		$sPropCode = substr($blockName, 5);

		if (!empty($arResult['PROPERTIES'][$sPropCode]['VALUE']))
		{
			if ($arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == 'S' && isset($arResult['DISPLAY_PROPERTIES'][$sPropCode]))
			{
				if (preg_match_all('#<table.*?>.*</table>#is', $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'], $arMatches))
				{
					$arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'] = preg_replace('#<table.*?>.*</table>#is', '<div class="table-responsive">$0</div>', $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
				}

				if (preg_match_all('#<iframe.*?>.*</iframe>#is', $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'], $arMatches))
				{
					$arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'] = preg_replace('#<iframe.*?>.*</iframe>#is', '<div class="table-responsive">$0</div>', $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
				}
			}
		}
	}
}

// gifts
if ($arResult['MODULES']['sale'] && $arParams['USE_GIFTS'] == 'Y')
{
	global $USER;
	$userId = $USER instanceof CAllUser? $USER->getId() : null;
	$giftManager = \Bitrix\Sale\Discount\Gift\Manager::getInstance()->setUserId($userId);

	if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
	{
		$price = $arResult['ITEM_START_PRICE'];
		$minOffer = $arResult['OFFERS'][$arResult['ITEM_START_PRICE_SELECTED']];
		$measureRatio = $minOffer['ITEM_MEASURE_RATIOS'][$minOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
	}
	else
	{
		$price = $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']];
		$measureRatio = $price['MIN_QUANTITY'];
	}

	$potentialBuy = array(
		'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
		'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
		'PRODUCT_PROVIDER_CLASS' => isset($arResult['~PRODUCT_PROVIDER_CLASS']) ? $arResult['~PRODUCT_PROVIDER_CLASS'] : '\Bitrix\Catalog\Product\CatalogProvider',
		'QUANTITY' => isset($price['MIN_QUANTITY']) ? $price['MIN_QUANTITY'] : 1,
	);

	$collections = $giftManager->getCollectionsByProduct(
		\Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), SITE_ID), $potentialBuy
	);

	if (is_array($collections) && count($collections) > 0)
	{
		$arResult['GIFT_ITEMS'] = array();
		foreach ($collections as $collection)
		{
			foreach ($collection as $gift)
			{
				$productId = $gift->getProductId();
				$arResult['GIFT_ITEMS'][$productId] = $productId;
			}
			unset($gift);
		}
		unset($collection);

		$arResult['GIFT_ITEMS'] = $arResult['GIFT_ITEMS'];
	}
}

// product images resize
$arProductImages = array();
if (!empty($actualItem['DETAIL_PICTURE']))
{
	$arProductImages[] = &$actualItem['DETAIL_PICTURE'];
}

if (is_array($actualItem['MORE_PHOTO']) && count($actualItem['MORE_PHOTO']) > 0)
{
	foreach ($actualItem['MORE_PHOTO'] as $key => $arPhoto)
	{
		$arProductImages[] = &$actualItem['MORE_PHOTO'][$key];
	}
}

// bonus calc
if ($arParams['SHOW_CASHBACK'] == 'Y')
{
	if ($haveOffers)
	{
		foreach ($arResult['OFFERS'] as $iOfferKey => $arOffer)
		{
			$arBonus = [];

			if (!empty($arOffer['PROPERTIES']['BONUS']['VALUE']))
			{
				$arBonus['TYPE'] = 'F';
				$arBonus['VALUE'] = $arOffer['PROPERTIES']['BONUS']['VALUE'];
				$arBonus['NAME'] = $arOffer['PROPERTIES']['BONUS']['NAME'];
			}
			elseif (!empty($arResult['PROPERTIES']['BONUS']['VALUE']))
			{
				$arBonus['TYPE'] = 'F';
				$arBonus['VALUE'] = $arResult['PROPERTIES']['BONUS']['VALUE'];
				$arBonus['NAME'] = $arResult['PROPERTIES']['BONUS']['NAME'];
			}
			else
			{
				$arBonus['TYPE'] = Option::get('redsign.megamart', 'sale_order_bonus_type', 'F', SITE_ID);
				$arBonus['VALUE'] = Option::get('redsign.megamart', 'sale_order_bonus', 'F', SITE_ID);
			}

			if ($arBonus['VALUE'] > 0)
			{
				foreach ($arOffer['ITEM_PRICES'] as $key => $offerPrice)
				{
					$offerBonusValue = 0;

					if ($arBonus['TYPE'] == 'P')
					{
						$offerBonusValue = $offerPrice['RATIO_PRICE'] * $arBonus['VALUE'] / 100;
					}
					else
					{
						$offerBonusValue = $arBonus['VALUE'];
					}

					if ($offerBonusValue > 0)
					{
						$arResult['OFFERS'][$iOfferKey]['ITEM_PRICES'][$key]['UNROUND_BONUS'] = $offerBonusValue;
						$arResult['OFFERS'][$iOfferKey]['ITEM_PRICES'][$key]['RATIO_BONUS'] = \Bitrix\Catalog\Product\Price::roundPrice(
							$offerPrice['PRICE_TYPE_ID'],
							$offerBonusValue,
							$offerPrice['CURRENCY']
						);
						$arResult['OFFERS'][$iOfferKey]['ITEM_PRICES'][$key]['PRINT_RATIO_BONUS'] = \CCurrencyLang::CurrencyFormat(
							$arResult['OFFERS'][$iOfferKey]['ITEM_PRICES'][$key]['RATIO_BONUS'],
							$offerPrice['CURRENCY'],
							true
						);
					}
				}

				if ($arParams['FILL_ITEM_ALL_PRICES'])
				{
					foreach ($arResult['OFFERS'][$iOfferKey]['ITEM_ALL_PRICES'] as $rangeKey => $itemPriceRange)
					{
						foreach ($itemPriceRange['PRICES'] as $priceKey => $itemPrice)
						{
							$offerBonusValue = 0;

							if ($arBonus['TYPE'] == 'P')
							{
								$offerBonusValue = $itemPrice['RATIO_PRICE'] * $arBonus['VALUE'] / 100;
							}
							else
							{
								$offerBonusValue = $arBonus['VALUE'];
							}

							if ($offerBonusValue > 0)
							{
								$itemPrice['UNROUND_BONUS'] = $offerBonusValue;
								$itemPrice['RATIO_BONUS'] = \Bitrix\Catalog\Product\Price::roundPrice(
									$itemPrice['PRICE_TYPE_ID'],
									$offerBonusValue,
									$itemPrice['CURRENCY']
								);
								$itemPrice['PRINT_RATIO_BONUS'] = \CCurrencyLang::CurrencyFormat(
									$itemPrice['RATIO_BONUS'],
									$itemPrice['CURRENCY'],
									true
								);
								$arResult['OFFERS'][$iOfferKey]['ITEM_ALL_PRICES'][$rangeKey]['PRICES'][$priceKey] = $itemPrice;
							}
						}
					}
				}
			}
		}
	}
	else
	{
		$arBonus = [];

		if (!empty($arResult['PROPERTIES']['BONUS']['VALUE']))
		{
			$arBonus['TYPE'] = 'F';
			$arBonus['VALUE'] = $arResult['PROPERTIES']['BONUS']['VALUE'];
			$arBonus['NAME'] = $arResult['PROPERTIES']['BONUS']['NAME'];
		}
		else
		{
			$arBonus['TYPE'] = Option::get('redsign.megamart', 'sale_order_bonus_type', 'F', SITE_ID);
			$arBonus['VALUE'] = Option::get('redsign.megamart', 'sale_order_bonus', 'F', SITE_ID);
		}

		if ($arBonus['VALUE'] > 0)
		{
			foreach ($arResult['ITEM_PRICES'] as $key => $itemPrice)
			{
				$itemBonusValue = 0;

				if ($arBonus['TYPE'] == 'P')
				{
					$itemBonusValue = $itemPrice['RATIO_PRICE'] * $arBonus['VALUE'] / 100;
				}
				else
				{
					$itemBonusValue = $arBonus['VALUE'];
				}

				if ($itemBonusValue > 0)
				{
					$arResult['ITEM_PRICES'][$key]['UNROUND_BONUS'] = $itemBonusValue;
					$arResult['ITEM_PRICES'][$key]['RATIO_BONUS'] = \Bitrix\Catalog\Product\Price::roundPrice(
						$itemPrice['PRICE_TYPE_ID'],
						$itemBonusValue,
						$itemPrice['CURRENCY']
					);
					$arResult['ITEM_PRICES'][$key]['PRINT_RATIO_BONUS'] = \CCurrencyLang::CurrencyFormat(
						$arResult['ITEM_PRICES'][$key]['RATIO_BONUS'],
						$itemPrice['CURRENCY'],
						true
					);
				}
			}

			if ($arParams['FILL_ITEM_ALL_PRICES'])
			{
				foreach ($arResult['ITEM_ALL_PRICES'] as $rangeKey => $itemPriceRange)
				{
					foreach ($itemPriceRange['PRICES'] as $priceKey => $itemPrice)
					{
						$itemBonusValue = 0;

						if ($arBonus['TYPE'] == 'P')
						{
							$itemBonusValue = $itemPrice['RATIO_PRICE'] * $arBonus['VALUE'] / 100;
						}
						else
						{
							$itemBonusValue = $arBonus['VALUE'];
						}

						if ($itemBonusValue > 0)
						{
							$itemPrice['UNROUND_BONUS'] = $itemBonusValue;
							$itemPrice['RATIO_BONUS'] = \Bitrix\Catalog\Product\Price::roundPrice(
								$itemPrice['PRICE_TYPE_ID'],
								$itemBonusValue,
								$itemPrice['CURRENCY']
							);
							$itemPrice['PRINT_RATIO_BONUS'] = \CCurrencyLang::CurrencyFormat(
								$itemPrice['RATIO_BONUS'],
								$itemPrice['CURRENCY'],
								true
							);
							$arResult['ITEM_ALL_PRICES'][$rangeKey]['PRICES'][$priceKey] = $itemPrice;
						}
					}
				}
			}
		}
	}
}

// JS_OFFERS modify
if ($haveOffers)
{
	foreach ($arResult['JS_OFFERS'] as $ind => $jsOffer)
	{
		if (!empty($arResult['OFFERS'][$ind]['PREVIEW_PICTURE']))
		{
			$arResult['JS_OFFERS'][$ind]['PREVIEW_PICTURE'] = array(
				'ID' => $arResult['OFFERS'][$ind]['PREVIEW_PICTURE']['ID'],
				'SRC' => $arResult['OFFERS'][$ind]['PREVIEW_PICTURE']['SRC'],
				'WIDTH' => $arResult['OFFERS'][$ind]['PREVIEW_PICTURE']['WIDTH'],
				'HEIGHT' => $arResult['OFFERS'][$ind]['PREVIEW_PICTURE']['HEIGHT'],
			);
		}

		if (!empty($arResult['OFFERS'][$ind]['DETAIL_PICTURE']))
		{
			$arResult['JS_OFFERS'][$ind]['DETAIL_PICTURE'] = array(
				'ID' => $arResult['OFFERS'][$ind]['DETAIL_PICTURE']['ID'],
				'SRC' => $arResult['OFFERS'][$ind]['DETAIL_PICTURE']['SRC'],
				'WIDTH' => $arResult['OFFERS'][$ind]['DETAIL_PICTURE']['WIDTH'],
				'HEIGHT' => $arResult['OFFERS'][$ind]['DETAIL_PICTURE']['HEIGHT'],
			);
		}

		if (!empty($jsOffer['DETAIL_PICTURE']))
		{
			$arProductImages[] = &$arResult['JS_OFFERS'][$ind]['DETAIL_PICTURE'];
		}

		if (is_array($jsOffer['SLIDER']) && count($jsOffer['SLIDER']) > 0)
		{
			foreach ($jsOffer['SLIDER'] as $iSlideKey => $arSlide)
			{
				$arProductImages[] = &$arResult['JS_OFFERS'][$ind]['SLIDER'][$iSlideKey];
			}
		}

		if ($arParams['FILL_ITEM_ALL_PRICES'])
		{
			$arResult['JS_OFFERS'][$ind]['ITEM_ALL_PRICES'] = $arResult['OFFERS'][$ind]['ITEM_ALL_PRICES'];
		}


		if ($arResult['OFFERS'][$ind]['DAYSARTICLE'])
		{
			$arTimer = $arResult['OFFERS'][$ind]['DAYSARTICLE'];

			$arResult['JS_OFFERS'][$ind]['TIMER'] = array(
				'TITLE' => Loc::getMessage('RS_MM_BCE_PREVIEW_DAYSARTICLE_TITLE'),
				'DATE_FROM' => $arTimer['DINAMICA_EX']['DATE_FROM'],
				'DATE_TO' => $arTimer['DINAMICA_EX']['DATE_TO'],
				'QUANTITY' => $arTimer['QUANTITY'],
				'AUTO_RENEWAL' => $arTimer['AUTO_RENEWAL'],
			);

			if (isset($arTimer['DINAMICA']))
			{
				$arResult['JS_OFFERS'][$ind]['TIMER']['DINAMICA_DATA'] = $arTimer['DINAMICA'] == 'custom'
					? array_flip(unserialize($arTimer['DINAMICA_DATA']))
					: $arTimer['DINAMICA'];
			}
			unset($arTimer);
		}
		elseif ($arResult['OFFERS'][$ind]['QUICKBUY'])
		{
			$arTimer = $arResult['OFFERS'][$ind]['QUICKBUY'];

			$arResult['JS_OFFERS'][$ind]['TIMER'] = array(
				'TITLE' => Loc::getMessage('RS_MM_BCE_PREVIEW_QUICKBUY_TITLE'),
				'DATE_FROM' => $arTimer['TIMER']['DATE_FROM'],
				'DATE_TO' => $arTimer['TIMER']['DATE_TO'],
				'QUANTITY' => $arTimer['QUANTITY'],
				'AUTO_RENEWAL' => $arTimer['AUTO_RENEWAL'],
			);
			unset($arTimer);
		}

		foreach ($arResult['OFFERS'][$ind]['ITEM_PRICES'] as $key => $offerPrice)
		{
			$arResult['JS_OFFERS'][$ind]['ITEM_PRICES'][$key]['UNROUND_BONUS'] = $offerPrice['UNROUND_BONUS'];
			$arResult['JS_OFFERS'][$ind]['ITEM_PRICES'][$key]['RATIO_BONUS'] = $offerPrice['RATIO_BONUS'];
			$arResult['JS_OFFERS'][$ind]['ITEM_PRICES'][$key]['PRINT_RATIO_BONUS'] = $offerPrice['PRINT_RATIO_BONUS'];
		}
	}
	unset($ind, $jsOffer);
}

if (is_array($arProductImages) && count($arProductImages) > 0)
{
	foreach ($arProductImages as $key => $arPhoto)
	{
		if ($arPhoto['ID'] > 0)
		{
			$arProductImages[$key]['RESIZE']['big'] = CFile::ResizeImageGet(
				$arPhoto['ID'],
				array('width' => 450, 'height' => 450),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);

			$arProductImages[$key]['RESIZE']['small'] = CFile::ResizeImageGet(
				$arPhoto['ID'],
				array('width' => 70, 'height' => 70),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);
		}
	}
	unset($key, $arPhoto);
}
