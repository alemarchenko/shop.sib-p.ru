<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Redsign\MegaMart\IblockElementExt;
use \Redsign\MegaMart\ParametersUtils;
use \Redsign\MegaMart\ElementListUtils;

$component = $this->getComponent();

$arResult['MODULES']['sale'] = Loader::includeModule('sale');
$arResult['MODULES']['redsign.devfunc'] = Loader::includeModule('redsign.devfunc');
$arResult['MODULES']['redsign.megamart'] = Loader::includeModule('redsign.megamart');
$arResult['MODULES']['redsign.favorite'] = Loader::includeModule('redsign.favorite');

$component->arParams['USE_GIFTS'] = isset($component->arParams['USE_GIFTS']) && $component->arParams['USE_GIFTS'] === 'N' ? 'N' : 'Y';
$component->arParams['USE_FAVORITE'] = $arResult['MODULES']['redsign.favorite'] && isset($arParams['USE_FAVORITE']) && $arParams['USE_FAVORITE'] === 'Y';
$component->arParams['PRODUCT_PREVIEW'] = isset($component->arParams['PRODUCT_PREVIEW']) && $component->arParams['PRODUCT_PREVIEW'] === 'N' ? 'N' : 'Y';
$component->arParams['SLIDER_SLIDE_COUNT'] = isset($arParams['SLIDER_SLIDE_COUNT']) ? (int)$arParams['SLIDER_SLIDE_COUNT'] : 5;

$component->arParams['SHOW_ERROR_SECTION_EMPTY'] = isset($component->arParams['SHOW_ERROR_SECTION_EMPTY']) && $component->arParams['SHOW_ERROR_SECTION_EMPTY'] === 'Y' ? 'Y' : 'N';
$component->arParams['MESS_ERROR_SECTION_EMPTY'] = $component->arParams['MESS_ERROR_SECTION_EMPTY'] ?: Loc::getMessage('RS_MM_BCS_CATALOG_ERROR_EMPTY_ITEMS');

$component->arParams['SHOW_RATING'] = isset($component->arParams['SHOW_RATING']) && $component->arParams['SHOW_RATING'] === 'Y' ? 'Y' : 'N';

$component->arParams['HIDE_DETAIL_LINKS'] = isset($component->arParams['HIDE_DETAIL_LINKS']) && $component->arParams['HIDE_DETAIL_LINKS'] === 'Y' ? 'Y' : 'N';

$component->arParams['DISPLAY_PREVIEW_TEXT'] = isset($component->arParams['DISPLAY_PREVIEW_TEXT']) && $component->arParams['DISPLAY_PREVIEW_TEXT'] === 'Y' ? 'Y' : 'N';
$component->arParams['PREVIEW_TRUNCATE_LEN'] = intval($component->arParams['PREVIEW_TRUNCATE_LEN']);

$arViewModeList = array('DEFAULT', 'POPUP', 'SCROLL');
if (!in_array($arParams['VIEW_MODE'], $arViewModeList))
{
	$arParams['VIEW_MODE'] = 'DEFAULT';
}

$component->arParams['FILTER_PROPS'] = is_array($component->arParams['FILTER_PROPS']) && count($component->arParams['FILTER_PROPS']) > 0
	? $component->arParams['FILTER_PROPS']
	: array();

if (!empty($arResult['CATALOGS']))
{
	if (isset($component->arParams['ARTNUMBER_PROP']) && !is_array($component->arParams['ARTNUMBER_PROP']))
	{
		$component->arParams['ARTNUMBER_PROP'] = array();
	}

	foreach ($arResult['CATALOGS'] as $arCatalog)
	{
		$IBLOCK_ID = $OFFER_IBLOCK_ID = 0;
		if ($arCatalog['PRODUCT_IBLOCK_ID'] == '0')
		{
			$IBLOCK_ID = $arCatalog['IBLOCK_ID'];
		}
		else
		{
			$IBLOCK_ID = $arCatalog['PRODUCT_IBLOCK_ID'];
			$OFFER_IBLOCK_ID = $arCatalog['IBLOCK_ID'];
		}

		if ($arParams['ARTNUMBER_PROP'] != '' && $arParams['ARTNUMBER_PROP'] != '-')
		{
			$component->arParams['ARTNUMBER_PROP'][$IBLOCK_ID] = $arParams['OFFER_ARTNUMBER_PROP'];
		}

		if ($OFFER_IBLOCK_ID > 0)
		{
			if ($arParams['OFFER_ARTNUMBER_PROP'] != '' && $arParams['OFFER_ARTNUMBER_PROP'] != '-')
			{
				$component->arParams['ARTNUMBER_PROP'][$OFFER_IBLOCK_ID] = $arParams['OFFER_ARTNUMBER_PROP'];
			}
		}
	}
	unset($arCatalog);
}

if (empty($arParams['AJAX_ID']) || strlen($arParams['AJAX_ID']) < 1)
{
	$component->arParams['AJAX_ID'] = CAjax::GetComponentID($component->componentName, $component->componentTemplate, $component->arParams['AJAX_OPTION_ADDITIONAL']);
}

$component->arResult['AJAX_ID'] = $arResult['AJAX_ID'] = $component->arParams['AJAX_ID'];

/*
if (empty($arParams['PRODUCT_BLOCKS']))
{
		$component->arParams['PRODUCT_BLOCKS'] = (is_string($arParams['PRODUCT_BLOCKS_ORDER']) && strlen($arParams['PRODUCT_BLOCKS_ORDER']) > 0)
			? explode(',', $arParams['PRODUCT_BLOCKS_ORDER'])
			: array();
}
*/

if (isset($component->arParams['~SLIDER_RESPONSIVE_SETTINGS']))
{
	$component->arParams['SLIDER_RESPONSIVE_SETTINGS'] = CUtil::JsObjectToPhp($component->arParams['~SLIDER_RESPONSIVE_SETTINGS']);
}
else
{
	$component->arParams['GRID_RESPONSIVE_SETTINGS'] = null;
}

if (isset($component->arParams['~GRID_RESPONSIVE_SETTINGS']))
{
	if ($arResult['MODULES']['redsign.megamart'])
	{
		$component->arParams['GRID_RESPONSIVE_SETTINGS'] = ParametersUtils::prepareGridSettings($component->arParams['~GRID_RESPONSIVE_SETTINGS']);
	}
}

if (empty($arParams['RS_LAZY_IMAGES_USE']) || $arParams['RS_LAZY_IMAGES_USE'] == 'FROM_MODULE')
{
	$component->arParams['RS_LAZY_IMAGES_USE'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_lazyload_images');
}

$component->arParams['BACKGROUND_COLOR'] = isset($arParams['BACKGROUND_COLOR']) ? trim($arParams['BACKGROUND_COLOR']) : '';
if ($component->arParams['BACKGROUND_COLOR'] === '-')
{
	$component->arParams['BACKGROUND_COLOR'] = '';
}

if (!is_array($arParams['ADD_TO_BASKET_ACTION']))
{
	$arResult['ORIGINAL_PARAMETERS']['ADD_TO_BASKET_ACTION'] = $arParams['ADD_TO_BASKET_ACTION'] = array($arParams['ADD_TO_BASKET_ACTION']);
}

if (
	in_array('REQUEST', $arParams['ADD_TO_BASKET_ACTION'])
	&& (!isset($arParams['LINK_BTN_REQUEST']) || strlen($arParams['LINK_BTN_REQUEST']) <= 0)
)
{
	$component->arParams['LINK_BTN_REQUEST'] = SITE_DIR.'product-ask/?element_id=#ELEMENT_ID#';
}

$arParams = $component->applyTemplateModifications();

$elementListUtils = ElementListUtils::getInstance($component);
$elementListUtils->applyTemplateModifications();
$arResult['ITEM_ROWS'] = $elementListUtils->getItemRows();
$arParams['ADD_TO_BASKET_ACTION'] = $arResult['ORIGINAL_PARAMETERS']['ADD_TO_BASKET_ACTION'];

// place after applyTemplateModifications (process with JS_OFFERS)
if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{
	foreach ($arResult['ITEMS'] as $key => $item)
	{
		$haveOffers = !empty($item['OFFERS']);

		if ($haveOffers)
		{
			if ($arParams['FILL_ITEM_ALL_PRICES'])
			{
				$bOfferCnt = 0;
				foreach ($item['OFFERS'] as $arOffer)
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
					foreach ($item['OFFERS'] as $offerKey => $offer)
					{
						IblockElementExt::fixCatalogItemFillAllPrices($arResult['ITEMS'][$key]['OFFERS'][$offerKey]);
						$arResult['ITEMS'][$key]['JS_OFFERS'][$offerKey]['ITEM_ALL_PRICES'] = $arResult['ITEMS'][$key]['OFFERS'][$offerKey]['ITEM_ALL_PRICES'];
					}
					unset($offerKey, $offer);
				}
			}
		}
		else
		{
			if ($arParams['FILL_ITEM_ALL_PRICES'])
			{
				if (
					(!is_array($item['PRICES']) || count($item['PRICES']) < 2)
					&& (
						!is_array($item['ITEM_ALL_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICES'])
						|| count($item['ITEM_ALL_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICES']) < 2
					)
				) {
					$component->arParams['FILL_ITEM_ALL_PRICES'] = false;
				}

				// #bitrixwtf
				if ($arResult['MODULES']['redsign.megamart'])
				{
					IblockElementExt::fixCatalogItemFillAllPrices($arResult['ITEMS'][$key]);
				}
			}
		}
	}
	unset($key, $item);
}

if ($arResult['MODULES']['redsign.devfunc'])
{
	\Redsign\DevFunc\Sale\Location\Region::editCatalogResult($arResult);

	if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
	{
		foreach ($arResult['ITEMS'] as $key => $item)
		{
			$haveOffers = !empty($item['OFFERS']);

			\Redsign\DevFunc\Sale\Location\Region::editCatalogItem($arResult['ITEMS'][$key]);

			if ($haveOffers)
			{
				if ($arParams['FILL_ITEM_ALL_PRICES'])
				{
					foreach ($arResult['ITEMS'][$key]['OFFERS'] as $offerKey => $arOffer)
					{
						$arResult['ITEMS'][$key]['JS_OFFERS'][$offerKey]['ITEM_PRICES'] = $arOffer['ITEM_PRICES'];
						$arResult['ITEMS'][$key]['JS_OFFERS'][$offerKey]['ITEM_ALL_PRICES'] = $arOffer['ITEM_ALL_PRICES'];
					}
				}
			}
		}
		unset($key, $item);
	}
}

$picParams = array(
	'PREVIEW_PICTURE' => true,
	'DETAIL_PICTURE' => true,
	'ADDITIONAL_PICT_PROP' => $arParams['ADDITIONAL_PICT_PROP'],
	'RESIZE' => array(
		0 => array(
			'MAX_WIDTH' => 210,
			'MAX_HEIGHT' => 210,
		)
	)
);

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{
	$arSections = array();
	$arElements = array();

	foreach ($arResult['ITEMS'] as $key => $item)
	{
		// sections
		if (is_array($item['SECTION']['PATH']) && count($item['SECTION']['PATH']) > 0)
		{
			$arResult['ITEMS'][$key]['SECTION'] = array_merge(
				$item['SECTION'],
				end($item['SECTION']['PATH'])
			);
		}
		else
		{
			$arSections[$item['~IBLOCK_SECTION_ID']] = array();
		}

		// elements
		$arElements[$item['ID']] = &$arResult['ITEMS'][$key];
		if (!empty($item['OFFERS']) && is_array($item['OFFERS']))
		{
			foreach ($item['OFFERS'] as $offerKey => $offer)
			{
				$arElements[$offer['ID']] = &$arResult['ITEMS'][$key]['OFFERS'][$offerKey];
			}
			unset($offerKey, $offer);
		}
	}
	unset($key, $item);

	if (is_array($arSections) && count($arSections) > 0)
	{
		// sections
		$sectionIterator = CIBlockSection::GetList(
			array(),
			array(
				'ACTIVE' => 'Y',
				'=ID' => array_keys($arSections),
			),
			false,
			array(
				'ID',
				'NAME',
				'SECTION_PAGE_URL',
			)
		);
		$sectionIterator->SetUrlTemplates('', $arParams['SECTION_URL']);

		while ($section = $sectionIterator->GetNext())
		{
			$arSections[$section['ID']] = $section;
		}
		unset($sectionIterator, $section);
	}

	// quickbuy & daysarticle
	if (is_array($arElements) && count($arElements) > 0)
	{
		if ($arResult['MODULES']['catalog'])
		{
			$default_quantity_trace = Option::get('catalog', 'default_quantity_trace', 'N');
		}

		$iTime = ConvertTimeStamp(time(), 'FULL');

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

	foreach ($arResult['ITEMS'] as $key => $item)
	{
		$haveOffers = !empty($item['OFFERS']);

		// parent section
		if (!is_array($item['SECTION']['PATH']) || count($item['SECTION']['PATH']) <= 0)
		{
			if (isset($item['~IBLOCK_SECTION_ID']))
			{
				$item['SECTION'] = array_merge(
					(array) $item['SECTION'],
					$arSections[$item['~IBLOCK_SECTION_ID']]
				);
				$arResult['ITEMS'][$key]['SECTION'] = $item['SECTION'];
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
				$price = $item['ITEM_START_PRICE'];
				$minOffer = $item['OFFERS'][$item['ITEM_START_PRICE_SELECTED']];
				$measureRatio = $minOffer['ITEM_MEASURE_RATIOS'][$minOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
			}
			else
			{
				$price = $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']];
				$measureRatio = $price['MIN_QUANTITY'];
			}

			$potentialBuy = array(
				'ID' => isset($item['ID']) ? $item['ID'] : null,
				'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
				'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
				'QUANTITY' => isset($price['MIN_QUANTITY']) ? $price['MIN_QUANTITY'] : 1,
			);

			$collections = $giftManager->getCollectionsByProduct(
				\Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), SITE_ID), $potentialBuy
			);

			if (is_array($collections) && count($collections) > 0)
			{
				$item['GIFT_ITEMS'] = array();
				foreach ($collections as $collection)
				{
					foreach ($collection as $gift)
					{
						$productId = $gift->getProductId();
						$item['GIFT_ITEMS'][$productId] = $productId;
					}
					unset($gift);
				}
				unset($collection);

				$arResult['ITEMS'][$key]['GIFT_ITEMS'] = $item['GIFT_ITEMS'];
			}
		}


		if ($arParams['DISPLAY_PREVIEW_TEXT'] == 'Y' && $arParams['PREVIEW_TRUNCATE_LEN'] > 0)
		{
			$obParser = new CTextParser;
			$arResult['ITEMS'][$key]['PREVIEW_TEXT'] = $obParser->html_cut($item['PREVIEW_TEXT'], $arParams['PREVIEW_TRUNCATE_LEN']);
		}

		if ($arParams['PRODUCT_DISPLAY_MODE'] == 'Y' && $haveOffers)
		{
			foreach ($item['OFFERS'] as $offerKey => $offer)
			{
				if (isset($offer['DAYSARTICLE']))
				{
					$arProductDeal = array(
						'NAME' => Loc::getMessage('RS_MM_BCS_CATALOG_DAYSARTICLE_TITLE'),
						'QUANTITY' => $offer['DAYSARTICLE']['QUANTITY'],
					);

					if (isset($offer['DAYSARTICLE']['TIMER']))
					{
						$arProductDeal['DATE_TO'] = $offer['DAYSARTICLE']['TIMER']['DATE_TO'];
						$arProductDeal['DATE_FROM'] = $offer['DAYSARTICLE']['TIMER']['DATE_FROM'];
					}

					$arResult['ITEMS'][$key]['JS_OFFERS'][$offerKey]['PRODUCT_DEAL'] = $arProductDeal;

					unset($arProductDeal);
				}


				if (isset($offer['QUICKBUY']))
				{
					$arProductDeal = array(
						'NAME' => Loc::getMessage('RS_MM_BCS_CATALOG_QUICKBUY_TITLE'),
						'QUANTITY' => $offer['QUICKBUY']['QUANTITY'],
					);

					if (isset($offer['QUICKBUY']['TIMER']))
					{
						$arProductDeal['DATE_TO'] = $offer['QUICKBUY']['TIMER']['DATE_TO'];
						$arProductDeal['DATE_FROM'] = $offer['QUICKBUY']['TIMER']['DATE_FROM'];
						$arProductDeal['DATE_NOW'] = $offer['QUICKBUY']['TIMER']['DATE_NOW'];
					}

					$arResult['ITEMS'][$key]['JS_OFFERS'][$offerKey]['PRODUCT_DEAL'] = $arProductDeal;

					unset($arProductDeal);
				}
			}
			unset($offerKey, $offer);
		}
	}
	unset($key, $item);
}

if (!empty($arResult['ITEMS']))
{
	$params = array(
		'PROP_PRICE' => $arParams['PRICE_PROP'],
		'PROP_DISCOUNT' => $arParams['DISCOUNT_PROP'],
		'PROP_CURRENCY' => $arParams['CURRENCY_PROP'],
		'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
	);

	foreach ($arResult['ITEMS'] as $key => $item)
	{
		if (!isset($arResult['CATALOGS'][$item['IBLOCK_ID']]) && $arResult['MODULES']['redsign.megamart'])
		{
			$arResult['ITEMS'][$key]['RS_PRICES'] = IblockElementExt::getPrice($item, $params);
		}
	}
	unset($key, $item, $params);
}

if ($arParams['SHOW_PARENT_DESCR'] == 'Y' && $arResult['ID'] == 0)
{
	$arOrder = array();
	$arFilter = array(
		'TYPE' => $arParams['IBLOCK_TYPE'],
		'ID' => $arParams['IBLOCK_ID'],
	);
	$bIncCnt = false;

	$dbIblock = CIBlock::getList($arOrder, $arFilter, $bIncCnt);

	if ($arIblock = $dbIblock->getNext())
	{
		$arResult['NAME'] = $arIblock['NAME'];
		$arResult['DESCRIPTION'] = $arIblock['DESCRIPTION'];
	}
	unset($arOrder, $arFilter, $bIncCnt);
}

if (!isset($arParams['PRODUCT_ROWS_USE_COLLAPSE']))
{
	$arParams['PRODUCT_ROWS_USE_COLLAPSE'] = false;
}
else if (!isset($arParams['PRODUCT_ROWS_COLLAPSE_SHOWED']))
{
	$arParams['PRODUCT_ROWS_COLLAPSE_SHOWED'] = 5;
}

$arResult['BACKGROUND_COLOR'] = false;
if ($arParams['BACKGROUND_COLOR'] != '')
{
	if (!empty($arResult[$arParams['BACKGROUND_COLOR']]))
	{
		$arResult['BACKGROUND_COLOR'] = $arResult[$arParams['BACKGROUND_COLOR']];
	}
	elseif (!empty($arResult['PATH']))
	{
		$arSectionsPath = array();
		foreach ($arResult['PATH'] as $key => $arSection)
		{
			$arSectionsPath[$arSection['ID']] = $arResult['PATH'][$key];
		}
		unset($key, $arSection);

		if (count($arSectionsPath) > 0)
		{
			$sectionIterator = \CIBlockSection::GetList(
				array(
					'DEPTH_LEVEL' => 'DESC',
				),
				array(
					'IBLOCK_ID' => $arResult['IBLOCK_ID'],
					'=ID' => array_keys($arSectionsPath),
				),
				false,
				array(
					'ID',
					'IBLOCK_ID',
					'NAME',
					'DEPTH_LEVEL',
					'UF_*',
					$arParams['BACKGROUND_COLOR'],
				)
			);

			while ($arSection = $sectionIterator->GetNext())
			{
				if (!empty($arSection[$arParams['BACKGROUND_COLOR']]))
				{
					$arResult['BACKGROUND_COLOR'] = $arSection[$arParams['BACKGROUND_COLOR']];
					break;
				}
			}
			unset($sectionIterator, $arSection);
		}
	}
}

if ($arResult['BACKGROUND_COLOR'])
{
	$component->arResult['BACKGROUND_COLOR'] = $arResult['BACKGROUND_COLOR'];
}

$component->SetResultCacheKeys(
	array(
		'AJAX_ID',
		'BACKGROUND_COLOR',
	)
);
