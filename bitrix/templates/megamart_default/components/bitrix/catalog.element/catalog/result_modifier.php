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

$component->arParams['DISPLAY_PROPERTIES_MAX'] = (intval($arParams['DISPLAY_PROPERTIES_MAX']) > 0 ? intval($arParams['DISPLAY_PROPERTIES_MAX']) : false);
$component->arParams['OFFERS_PROPERTIES_MAX'] = (intval($arParams['OFFERS_PROPERTIES_MAX']) > 0 ? intval($arParams['OFFERS_PROPERTIES_MAX']) : false);

$component->arParams['SHOW_SLIDER'] = 'N';

if (empty($arParams['PRODUCT_INFO_BLOCK']) && $arParams['PRODUCT_INFO_BLOCK_ORDER'] != '')
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
		$component->arParams['TABS_ORDER'] = 'detail,props,comments,mods';
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
	&& (!isset($arParams['LINK_BTN_REQUEST']) || $arParams['LINK_BTN_REQUEST'] == '')
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

$component->arParams['SHOW_SIZE_TABLE'] = isset($arParams['SHOW_SIZE_TABLE']) && $arParams['SHOW_SIZE_TABLE'] === 'Y' ? 'Y' : 'N';

$component->arParams['SIZE_TABLE_USER_FIELDS'] = isset($arParams['SIZE_TABLE_USER_FIELDS']) ? trim($arParams['SIZE_TABLE_USER_FIELDS']) : '';
if ($component->arParams['SIZE_TABLE_USER_FIELDS'] === '-')
{
	$component->arParams['SIZE_TABLE_USER_FIELDS'] = '';
}

$component->arParams['SIZE_TABLE_PROP'] = isset($arParams['SIZE_TABLE_PROP']) ? trim($arParams['SIZE_TABLE_PROP']) : '';
if ($component->arParams['SIZE_TABLE_PROP'] === '-')
{
	$component->arParams['SIZE_TABLE_PROP'] = '';
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
	if (!empty($arParams['OFFERS_SELECTED']))
	{
		$intSelected = -1;
		foreach ($arResult['OFFERS'] as $keyOffer => $offer)
		{
			if ($arParams['OFFERS_SELECTED'] != '')
			{
				$foundOffer = $arParams['OFFERS_SELECTED'] == $offer['CODE'] || $arParams['OFFERS_SELECTED'] == $offer['ID'];
			}
			else
			{
				$foundOffer = $offer['CAN_BUY'];
			}

			if ($foundOffer && $intSelected == -1)
			{
				$intSelected = $keyOffer;
				break;
			}
			unset($foundOffer);
		}

		if ($intSelected == -1)
		{
			$intSelected = 0;
		}

		$arResult['OFFERS_SELECTED'] = $intSelected;
	}

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
	if (mb_substr($blockName, 0, 5) == 'prop_')
	{
		$sPropCode = mb_substr($blockName, 5);

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

if (is_array($arParams['TABS']) && count($arParams['TABS']) > 0)
{
	$arParams['TAB_PROPERTIES'] = (array) array_map(
		function($v){
			return mb_substr($v, 5);
		},
		array_filter(
			$arParams['TABS'],
			function($v){
				return mb_substr($v, 0, 5) == 'prop_';
			}
		)
	);
}

if (is_array($arParams['BLOCK_LINES']) && count($arParams['BLOCK_LINES']) > 0)
{
	$arParams['BLOCK_LINES_PROPERTIES'] = (array) array_map(
		function($v){
			return mb_substr($v, 5);
		},
		array_filter(
			$arParams['BLOCK_LINES'],
			function($v){
				return mb_substr($v, 0, 5) == 'prop_';
			}
		)
	);
}

if (is_array($arResult['PROPERTIES']) && count($arResult['PROPERTIES']) > 0)
{
	foreach ($arResult['PROPERTIES'] as $sPropCode => $arProp)
	{
		if (
			$sPropCode != '' && $arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE
			&& (
				in_array($sPropCode, $arParams['TAB_PROPERTIES'])
				|| in_array($sPropCode, $arParams['BLOCK_LINES_PROPERTIES'])
			)
		) {
			if (is_array($arProp['VALUE']))
			{
				foreach ($arProp['VALUE'] as $iPropValKey => $iPropVal)
				{
					$rsFile = CFile::GetByID($iPropVal);
					if ($arFile = $rsFile->Fetch())
					{
						$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey] = $arFile;
						$arFile['FULL_PATH'] = '/upload/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'];
						$tmp = explode('.', $arFile['FILE_NAME']);
						$arFile['FILE_EXT'] = end($tmp);
						switch($arFile['FILE_EXT'])
						{
							case 'docx':
							case 'doc':
								$arFile['FILE_ICON'] = 'doc';
								break;
							case 'xls':
							case 'xlsx':
								$arFile['FILE_ICON'] = 'xls';
								break;
							case 'pdf':
								$arFile['FILE_ICON'] = 'pdf';
								break;
							default:
								$arFile['FILE_ICON'] = 'txt';
								break;
						}
						$arFile['SIZE'] = CFile::FormatSize($arFile['FILE_SIZE'], 1);

						$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey] = $arFile;
					}
				}
			}
		}
	}
}

// product deals
if ($arParams['PRODUCT_DEALS_SHOW'] == 'Y')
{
	$arResult['PRODUCT_DEALS'] = array();
	if ($arResult['SECTION']['ID'] > 0 && $arParams['PRODUCT_DEALS_USER_FIELDS'] != '')
	{
		$sectionIterator = \CIBlockSection::GetList(
			array(),
			array(
				'IBLOCK_ID' => $arResult['SECTION']['IBLOCK_ID'],
				'ID' => $arResult['SECTION']['ID'],
			),
			false,
			array(
				'ID',
				$arParams['PRODUCT_DEALS_USER_FIELDS'],
			)
		);

		if ($arSection = $sectionIterator->GetNext())
		{
			if (is_array($arSection[$arParams['PRODUCT_DEALS_USER_FIELDS']]) && count($arSection[$arParams['PRODUCT_DEALS_USER_FIELDS']]) > 0)
			{
				foreach ($arSection[$arParams['PRODUCT_DEALS_USER_FIELDS']] as $arValue)
				{
					$arResult['PRODUCT_DEALS'][$arValue] = array();
				}
				unset($arValue);
			}
		}
		unset($sectionIterator, $arSection);
	}

	if ($arParams['PRODUCT_DEALS_PROP'] != '' && $arParams['PRODUCT_DEALS_PROP'] != '-')
	{
		if (is_array($arResult['PROPERTIES'][$arParams['PRODUCT_DEALS_PROP']]['VALUE']) && count($arResult['PROPERTIES'][$arParams['PRODUCT_DEALS_PROP']]['VALUE']) > 0)
		{
			foreach ($arResult['PROPERTIES'][$arParams['PRODUCT_DEALS_PROP']]['VALUE'] as $arValue)
			{
				$arResult['PRODUCT_DEALS'][$arValue] = array();
			}
			unset($arValue);
		}
	}

	if (is_array($arResult['PRODUCT_DEALS']) && count($arResult['PRODUCT_DEALS']) > 0)
	{
		$elementIterator = \CIBlockElement::GetList(
			array(),
			array(
				// 'IBLOCK_ID' => $this->arParams['LINK_IBLOCK_ID'],
				'IBLOCK_ACTIVE' => 'Y',
				'ACTIVE_DATE' => 'Y',
				'ACTIVE' => 'Y',
				'CHECK_PERMISSIONS' => 'Y',
				// 'IBLOCK_TYPE' => $this->arParams['LINK_IBLOCK_TYPE'],
				'=ID' => array_keys($arResult['PRODUCT_DEALS']),
			),
			false,
			false,
			array(
				'ID',
				'IBLOCK_ID',
				'NAME',
				'PREVIEW_TEXT',
				'DETAIL_PAGE_URL',
			)
		);

		while ($arElement = $elementIterator->GetNext())
		{
			if (isset($arResult['PRODUCT_DEALS'][$arElement['ID']]))
			{
				$arResult['PRODUCT_DEALS'][$arElement['ID']] = $arElement;
			}
		}
		unset($elementIterator, $arElement);
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
				'TITLE' => Loc::getMessage('RS_MM_BCE_CATALOG_DAYSARTICLE_TITLE'),
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
				'TITLE' => Loc::getMessage('RS_MM_BCE_CATALOG_QUICKBUY_TITLE'),
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

// product brands
if ($arParams['USE_BRANDS'] == 'Y')
{
	$arResult['BRANDS'] = array();

	$sBrandPropCode = $arParams['BRAND_PROP'][$arResult['IBLOCK_ID']];

	if (!empty($arResult['PROPERTIES'][$sBrandPropCode]))
	{
		if (is_array($arResult['PROPERTIES'][$sBrandPropCode]['VALUE']))
		{
			foreach ($arResult['PROPERTIES'][$sBrandPropCode]['VALUE'] as $iPropValue => $sPropValue)
			{
				if ($sPropValue != '')
				{
					$arResult['BRANDS'][$sPropValue] = array();
				}
			}
		}
		elseif ($arResult['PROPERTIES'][$sBrandPropCode]['VALUE'] != '')
		{
			$arResult['BRANDS'][$arResult['PROPERTIES'][$sBrandPropCode]['VALUE']] = array();
		}

		$sBrandPropType = $arResult['PROPERTIES'][$sBrandPropCode]['PROPERTY_TYPE'];
	}

	if (is_array($arResult['BRANDS']) && count($arResult['BRANDS']) > 0)
	{
		if ($sBrandPropType == Iblock\PropertyTable::TYPE_ELEMENT)
		{

			$dbBrands = CIBlockElement::GetList(
				array(),
				$arFilter = array(
					'IBLOCK_ACTIVE' => 'Y',
					'ACTIVE_DATE' => 'Y',
					'ACTIVE' => 'Y',
					'CHECK_PERMISSIONS' => 'Y',
					'=ID' => array_keys($arResult['BRANDS']),
				),
				false,
				false,
				array(
					'ID',
					'IBLOCK_ID',
					'NAME',
					'DETAIL_PAGE_URL',
					'PREVIEW_PICTURE',
				)
			);

			while ($arBrand = $dbBrands->GetNext())
			{
				if (intval($arBrand['PREVIEW_PICTURE']) > 0)
				{
					$arBrand['PREVIEW_PICTURE'] = CFile::GetFileArray($arBrand['PREVIEW_PICTURE']);
					$arBrand['PREVIEW_PICTURE']['RESIZE'] = CFile::ResizeImageGet(
						$arBrand['PREVIEW_PICTURE'],
						array('width' => 300, 'height' => 34),
						BX_RESIZE_IMAGE_PROPORTIONAL,
						true
					);
				}
				$arResult['BRANDS'][$arBrand['ID']] = $arBrand;
			}
			unset($dbBrands, $arBrand);
		}
		elseif (
			$sBrandPropType == Iblock\PropertyTable::TYPE_STRING
			&& intval($arParams['BRAND_IBLOCK_ID']) > 0 && $arParams['BRAND_IBLOCK_BRAND_PROP'] != ''
		)
		{
			$dbBrands = CIBlockElement::GetList(
				array(),
				$arFilter = array(
					'IBLOCK_ID' => $arParams['BRAND_IBLOCK_ID'],
					'PROPERTY_'.$arParams['BRAND_IBLOCK_BRAND_PROP'] => array_keys($arResult['BRANDS']),
				),
				false,
				false,
				array(
					'ID',
					'IBLOCK_ID',
					'NAME',
					'DETAIL_PAGE_URL',
					'PREVIEW_PICTURE',
					'PROPERTY_'.$arParams['BRAND_IBLOCK_BRAND_PROP'],
				)
			);

			while ($arBrand = $dbBrands->GetNext())
			{
				if (intval($arBrand['PREVIEW_PICTURE']) > 0)
				{
					$arBrand['PREVIEW_PICTURE'] = CFile::GetFileArray($arBrand['PREVIEW_PICTURE']);
					$arBrand['PREVIEW_PICTURE']['RESIZE'] = CFile::ResizeImageGet(
						$arBrand['PREVIEW_PICTURE'],
						array('width' => 34, 'height' => 34),
						BX_RESIZE_IMAGE_PROPORTIONAL,
						true
					);
				}
				$arResult['BRANDS'][$arBrand['PROPERTY_'.$arParams['BRAND_IBLOCK_BRAND_PROP'].'_VALUE']] = $arBrand;
			}
		}
	}
	else
	{
		$arFilterProps = array(
			$arParams['BRAND_PROP'],
		);

		foreach ($arFilterProps as $arProps)
		{
			$sPropCode = $arProps[$arResult['IBLOCK_ID']];

			if (isset($arResult['PROPERTIES'][$sPropCode]))
			{
				if (is_array($arResult['PROPERTIES'][$sPropCode]['VALUE']))
				{
					foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $iPropValue => $sPropValue)
					{
						$arResult['PROPERTIES'][$sPropCode]['FILTER_URL'][] = $arResult['LIST_PAGE_URL']
							.(mb_strpos($arResult['LIST_PAGE_URL'], '?') === false ? '?' : '').$arParams['FILTER_NAME'].'_'
							.$arResult['PROPERTIES'][$sPropCode]['ID'].'_'
							.abs(crc32($arResult['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID'][$iPropValue]
								? $arResult['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID'][$iPropValue]
								: htmlspecialcharsbx($sPropValue))
							).'=Y&set_filter=Y';
					}
				}
				else
				{
					$arResult['PROPERTIES'][$sPropCode]['FILTER_URL'] = $arResult['LIST_PAGE_URL']
						.(mb_strpos($arResult['LIST_PAGE_URL'], '?') === false ? '?' : '').$arParams['FILTER_NAME'].'_'
						.$arResult['PROPERTIES'][$sPropCode]['ID'].'_'
						.abs(crc32($arResult['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID']
							? $arResult['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID']
							: htmlspecialcharsbx($arResult['PROPERTIES'][$sPropCode]['VALUE']))
						).'=Y&set_filter=Y';
				}
			}
		}
	}
}

if ($arParams['USE_SHARE'] == 'Y')
{
	if (is_array($arParams['SOCIAL_SERVICES'])&& count($arParams['SOCIAL_SERVICES']) > 0)
	{
		$arParams['SOCIAL_SERVICES'] = array_filter(
			$arParams['SOCIAL_SERVICES'],
			function ($val)
			{
				return $val != '' && $val != '-';
			}
		);
	}
}

$arResult['BACKGROUND_COLOR'] = false;
if ($arParams['BACKGROUND_COLOR'] != '' && !empty($arResult['PROPERTIES'][$arParams['BACKGROUND_COLOR']]['VALUE']))
{
	$arResult['BACKGROUND_COLOR'] = $arResult['PROPERTIES'][$arParams['BACKGROUND_COLOR']]['VALUE'];
}
elseif ($arParams['LIST_BACKGROUND_COLOR'] != '' && !empty($arResult['SECTION']['PATH']))
{
	$arSections = array();
	foreach ($arResult['SECTION']['PATH'] as $key => $arSection)
	{
		$arSections[$arSection['ID']] = &$arResult['SECTION']['PATH'][$key];
	}
	unset($key, $arSection);

	if (count($arSections) > 0)
	{
		$sectionIterator = \CIBlockSection::GetList(
			array(
				'DEPTH_LEVEL' => 'DESC',
			),
			array(
				'IBLOCK_ID' => $arResult['SECTION']['IBLOCK_ID'],
				'ID' => array_keys($arSections),
			),
			false,
			array(
				'ID',
				'DEPTH_LEVEL',
				$arParams['LIST_BACKGROUND_COLOR'],
			)
		);

		while ($arSection = $sectionIterator->GetNext())
		{
			if (!empty($arSection[$arParams['LIST_BACKGROUND_COLOR']]))
			{
				$arResult['BACKGROUND_COLOR'] = $arSection[$arParams['LIST_BACKGROUND_COLOR']];
				break;
			}
		}
		unset($sectionIterator, $arSection);
	}
}

if ($arResult['BACKGROUND_COLOR'])
{
	$component->arResult['BACKGROUND_COLOR'] = $arResult['BACKGROUND_COLOR'];
}

// size table
if ($arParams['SHOW_SIZE_TABLE'] == 'Y')
{
	if ($arParams['SIZE_TABLE_PROP'] != '' && !empty($arResult['PROPERTIES'][$arParams['SIZE_TABLE_PROP']]['VALUE']))
	{
		$arResult['SIZE_TABLE'] = (int)$arResult['PROPERTIES'][$arParams['SIZE_TABLE_PROP']]['VALUE'];
	}
	elseif ($arParams['SIZE_TABLE_USER_FIELDS'] != '' && !empty($arResult['SECTION']['PATH']))
	{
		$arSections = array();
		foreach ($arResult['SECTION']['PATH'] as $key => $arSection)
		{
			$arSections[$arSection['ID']] = &$arResult['SECTION']['PATH'][$key];
		}
		unset($key, $arSection);

		if (count($arSections) > 0)
		{
			$sectionIterator = \CIBlockSection::GetList(
				array(
					'DEPTH_LEVEL' => 'DESC',
				),
				array(
					'IBLOCK_ID' => $arResult['SECTION']['IBLOCK_ID'],
					'ID' => array_keys($arSections),
				),
				false,
				array(
					'ID',
					'NAME	',
					'DEPTH_LEVEL',
					$arParams['SIZE_TABLE_USER_FIELDS'],
				)
			);

			while ($arSection = $sectionIterator->GetNext())
			{
				if (!empty($arSection[$arParams['SIZE_TABLE_USER_FIELDS']]))
				{
					$arResult['SIZE_TABLE'] = (int)$arSection[$arParams['SIZE_TABLE_USER_FIELDS']];
					break;
				}
			}
			unset($sectionIterator, $arSection);
		}
	}

	if ($arResult['SIZE_TABLE'] > 0)
	{
		$elementIterator = \CIBlockElement::GetList(
			array(),
			array(
				// 'IBLOCK_ID' => $this->arParams['LINK_IBLOCK_ID'],
				'IBLOCK_ACTIVE' => 'Y',
				'ACTIVE_DATE' => 'Y',
				'ACTIVE' => 'Y',
				'CHECK_PERMISSIONS' => 'Y',
				// 'IBLOCK_TYPE' => $this->arParams['LINK_IBLOCK_TYPE'],
				'=ID' => $arResult['SIZE_TABLE'],
				'!PREVIEW_TEXT' => '',
			),
			false,
			false,
			array(
				'ID',
				'IBLOCK_ID',
				'NAME',
				'PREVIEW_TEXT',
				'DETAIL_PAGE_URL',
			)
		);

		if ($arElement = $elementIterator->GetNext())
		{
			$arResult['SIZE_TABLE'] = $arElement;
		}
		unset($elementIterator, $arElement);
	}

	if (empty($arResult['SIZE_TABLE']['PREVIEW_TEXT']))
	{
		unset($arResult['SIZE_TABLE']);
	}
}

$component->SetResultCacheKeys(
	array(
		'BACKGROUND_COLOR',
		'DISPLAY_PROPERTIES'
	)
);

$arResult['IS_WIDE_SLIDER'] = false;

if (isset($arParams['SHOW_WIDE_VIEW']) && is_array($arParams['SHOW_WIDE_VIEW']))
{
	foreach ($arParams['SHOW_WIDE_VIEW'] as $section)
	{
		if (mb_stripos($arResult['DETAIL_PAGE_URL'], $section))
		{
			$arResult['IS_WIDE_SLIDER'] = true;
		};
	}

}
