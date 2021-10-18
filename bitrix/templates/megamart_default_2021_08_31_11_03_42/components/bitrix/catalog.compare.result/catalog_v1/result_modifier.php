<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @global array $arParams */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Type\Collection;
use \Redsign\Megamart\IblockElementExt;

if (1 > strlen($arParams['TEMPLATE_AJAXID']))
{
	$arParams['TEMPLATE_AJAXID'] = 'compare';
}

if ('' != $arParams['ADD_PICT_PROP'] && '-' != $arParams['ADD_PICT_PROP'])
{
	$arParams['ADD_PICT_PROP'] = array($arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP']);
}
if ($arResult['OFFERS_IBLOCK_ID'])
{
	if ('' != $arParams['OFFER_ADD_PICT_PROP'] && '-' != $arParams['OFFER_ADD_PICT_PROP'])
	{
		$arParams['ADD_PICT_PROP'][$arResult['OFFERS_IBLOCK_ID']] = $arParams['OFFER_ADD_PICT_PROP'];
	}
}

$arResult['MODULES'] = array(
    'redsign.megamart' => Loader::includeModule('redsign.megamart'),
    'redsign.grupper' => Loader::includeModule('redsign.grupper'),
    'redsign.devfunc' => Loader::includeModule('redsign.devfunc'),
    'catalog' => Loader::includeModule('catalog'),
    'sale' => Loader::includeModule('sale'),
);

if (isset($arParams['SHOW_MAX_QUANTITY']) && in_array($arParams['SHOW_MAX_QUANTITY'], array('Y', 'M')))
{
	foreach (array('CATALOG_QUANTITY', 'CATALOG_MEASURE', 'CATALOG_QUANTITY_TRACE', 'CATALOG_CAN_BUY_ZERO') as $code)
	{
		if (isset($arResult['SHOW_FIELDS'][$code]))
		{
			unset($arResult['SHOW_FIELDS'][$code]);
		}
		if (isset($arResult['SHOW_OFFER_FIELDS'][$code]))
		{
			unset($arResult['SHOW_OFFER_FIELDS'][$code]);
		}
		if (isset($arResult['ALL_FIELDS'][$code]))
		{
			unset($arResult['ALL_FIELDS'][$code]);
		}
	}
}

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 1)
{
    $arSortItemsBy = array_keys($_SESSION[$arParams['NAME']][$arParams['IBLOCK_ID']]['ITEMS']);

    uasort($arResult['ITEMS'], function($a, $b) use ($arSortItemsBy) {
        $arSortItemsBy = array_flip($arSortItemsBy);
        return $arSortItemsBy[$a['ID']] > $arSortItemsBy[$b['ID']];
    });

    unset($arSortItemsBy);

    if ($arResult['MODULES']['catalog'])
	{
        foreach ($arResult['ITEMS'] as $item)
		{
            $arCatalog = CCatalog::GetByIDExt($item['IBLOCK_ID']);
            if (false !== $arCatalog)
			{
                $arResult['CATALOGS'][$item['IBLOCK_ID']] = $arCatalog;
            }
        }
		unset($item);
    }
}

$arSections = array();

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

		$arElements[$item['ID']] = &$arResult['ITEMS'][$key];
		$arSections[$item['IBLOCK_SECTION_ID']] = array();
	}
	unset($key, $item, $params);
}

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

if (is_array($arElements) && count($arElements) > 0)
{
	$ratioIterator = \CCatalogMeasureRatio::getList(
		array(),
		array('PRODUCT_ID' => array_keys($arElements)),
		false,
		false,
		array()
	);
	
	while ($ratio = $ratioIterator->Fetch())
	{
		$intRatio = (int)$ratio['RATIO'];
		$floatRatio = (float)$ratio['RATIO'];
		$quantity = $floatRatio > $intRatio ? $floatRatio : $intRatio;

		if ($quantity <= 0)
		{
			$quantity = 1;
		}

		$arElements[$ratio['PRODUCT_ID']]['ITEM_MEASURE'] = array(
			'TITLE'	=> '',
		);
		$arElements[$ratio['PRODUCT_ID']]['ITEM_MEASURE_RATIO'] = $quantity;
	}
	unset($ratioIterator, $ratio);

	foreach (array_keys($arElements) as $itemId)
	{
		if ($arElements[$itemId]['PARENT_ID'] != $arElements[$itemId]['ID'])
		{
			$arElements[$itemId]['CATALOG_QUANTITY'] = $arElements[$itemId]['OFFER_FIELDS']['CATALOG_QUANTITY'];
			$arElements[$itemId]['CATALOG_MEASURE'] = $arElements[$itemId]['OFFER_FIELDS']['CATALOG_MEASURE'];
			$arElements[$itemId]['CATALOG_QUANTITY_TRACE'] = $arElements[$itemId]['OFFER_FIELDS']['CATALOG_QUANTITY_TRACE'];
			$arElements[$itemId]['CATALOG_CAN_BUY_ZERO'] = $arElements[$itemId]['OFFER_FIELDS']['CATALOG_CAN_BUY_ZERO'];
		}

		$measureId = (int)$arElements[$itemId]['CATALOG_MEASURE'];
		if ($measureId > 0)
			$arResult['MEASURES'][$measureId] = array(
				'ID' => $measureId,
				'NAME' => '',
			);
		unset($measureId);
	}
	unset($itemId);
	
	if (!empty($arResult['MEASURES']))
	{
		$measureIds = array_keys($arResult['MEASURES']);

		\Bitrix\Main\Type\Collection::normalizeArrayValuesByInt($measureIds, true);
		if (!empty($measureIds))
		{
			$measureIterator = \CCatalogMeasure::getList(
				array(),
				array('@ID' => $measureIds),
				false,
				false,
				array('ID', 'SYMBOL_RUS')
			);
			while ($measure = $measureIterator->GetNext())
			{
				$measure['ID'] = (int)$measure['ID'];
				$measure['TITLE'] = $measure['SYMBOL_RUS'];
				$measure['~TITLE'] = $measure['~SYMBOL_RUS'];
				unset($measure['SYMBOL_RUS'], $measure['~SYMBOL_RUS']);
				$arResult['MEASURES'][$measure['ID']] = $measure;
			}
			unset($measure, $measureIterator);
		}
	}

	foreach (array_keys($arElements) as $index)
	{
		$itemId = $arElements[$index]['ID'];
		// measure
		if (!empty($arElements[$index]['ITEM_MEASURE']))
		{
			$id = (int)$arElements[$index]['CATALOG_MEASURE'];
			if (isset($arResult['MEASURES'][$id]))
			{
				$arElements[$index]['ITEM_MEASURE']['TITLE'] = $arResult['MEASURES'][$id]['TITLE'];
				$arElements[$index]['ITEM_MEASURE']['~TITLE'] = $arResult['MEASURES'][$id]['~TITLE'];
			}
			unset($id);
		}
	}
}

// new catalog components fix
/*
if (!isset($arResult['PRICES'])) {
    $arResult['PRICES'] = CIBlockPriceTools::GetCatalogPrices($arParams['IBLOCK_ID'], $arParams['PRICE_CODE']);
}
*/

$arProperties = array();
if ($arResult['MODULES']['redsign.grupper'])
{
	$arGroups = array();
	$rsGroups = CRSGGroups::GetList(array('SORT' => 'ASC','ID' => 'ASC'), array());
	while ($arGroup = $rsGroups->Fetch())
	{
		$arGroups[$arGroup['ID']] = $arGroup;
		$arGroups[$arGroup['ID']]['IS_SHOW'] = false;
	}
	unset($rsGroups, $arGroup);

	if (!empty($arGroups))
	{
		$rsBinds = CRSGBinds::GetList(array('ID' => 'ASC'));
		while ($arBind = $rsBinds->Fetch())
		{
			$arGroups[$arBind['GROUP_ID']]['BINDS'][$arBind['IBLOCK_PROPERTY_ID']] = $arBind['IBLOCK_PROPERTY_ID'];
			$arProperties[$arBind['IBLOCK_PROPERTY_ID']] = $arBind['GROUP_ID'];
		}
		unset($rsBinds, $arBind);
		$arResult['PROPERTIES_GROUPS'] = $arGroups;
	}
}

if (is_array($arResult['ITEMS']))
{
	foreach ($arResult['ITEMS'] as $key => $item)
	{
		// parent section
		if (!is_array($item['SECTION']['PATH']) || count($item['SECTION']['PATH']) <= 0)
		{
			if (isset($item['IBLOCK_SECTION_ID']))
			{
				$item['SECTION'] = array_merge(
					(array)$item['SECTION'],
					$arSections[$item['IBLOCK_SECTION_ID']]
				);
				$arResult['ITEMS'][$key]['SECTION'] = $item['SECTION'];
			}
		}

		if (isset($item['OFFER_FIELDS']['PREVIEW_PICTURE']) && 0 < intval($item['OFFER_FIELDS']['PREVIEW_PICTURE']))
		{
			$arResult['ITEMS'][$key]['PREVIEW_PICTURE'] = $item['OFFER_FIELDS']['PREVIEW_PICTURE'];
		}
		else if (isset($item['FIELDS']['PREVIEW_PICTURE']) && 0 < intval($item['FIELDS']['PREVIEW_PICTURE']))
		{
			$arResult['ITEMS'][$key]['PREVIEW_PICTURE'] = $item['OFFER_FIELDS']['PREVIEW_PICTURE'];
		}

		if (isset($item['FIELDS']['DETAIL_PICTURE']) && 0 < intval($item['FIELDS']['DETAIL_PICTURE']))
		{
			$arResult['ITEMS'][$key]['DETAIL_PICTURE'] = $item['FIELDS']['DETAIL_PICTURE'];
		}
		else if (isset($item['FIELDS']['DETAIL_PICTURE']) && 0 < intval($item['FIELDS']['DETAIL_PICTURE']))
		{
			$arResult['ITEMS'][$key]['DETAIL_PICTURE'] = $item['FIELDS']['DETAIL_PICTURE'];
		}

		// #bitrixwtf
		if (isset($item['PRICE_MATRIX']['COLS']) && is_array($item['PRICE_MATRIX']['COLS']))
		{
			$arResult['ITEMS'][$key]['PRICE_MATRIX']['MIN_PRICES'] = array();
			$rows = $item['PRICE_MATRIX']['ROWS'];
			$matrix = $item['PRICE_MATRIX']['MATRIX'];

			foreach (array_keys($rows) as $index)
			{
				$minPrice = null;
				foreach (array_keys($matrix) as $priceType)
				{
					if (empty($matrix[$priceType][$index]))
						continue;
					if ($arParams['CONVERT_CURRENCY'] == 'Y')
					{
						if ($minPrice === null || $minPrice['PRICE_SCALE'] > $matrix[$priceType][$index]['PRICE'])
						{
							$minPrice = $matrix[$priceType][$index];
							$minPrice['PRICE_SCALE'] = $matrix[$priceType][$index]['PRICE'];
						}
					}
					else
					{
						$priceScale = ($matrix[$priceType][$index]['CURRENCY'] == $basePrice
							? $matrix[$priceType][$index]['PRICE']
							: \CCurrencyRates::ConvertCurrency(
								$matrix[$priceType][$index]['PRICE'],
								$matrix[$priceType][$index]['CURRENCY'],
								$basePrice
							)
						);
						if ($minPrice === null || $minPrice['PRICE_SCALE'] > $priceScale)
						{
							$minPrice = $matrix[$priceType][$index];
							$minPrice['PRICE_SCALE'] = $priceScale;
						}
					}
				}
				unset($priceType);
				if (is_array($minPrice))
				{
					$minPrice['VALUE'] = $minPrice['PRICE'];
					$minPrice['DISCOUNT_VALUE'] = $minPrice['DISCOUNT_PRICE'];
					
					if ($minPrice['DISCOUNT_VALUE'] < $minPrice['VALUE'])
					{
						$minPrice['DISCOUNT_DIFF'] = $minPrice['VALUE'] - $minPrice['DISCOUNT_VALUE'];
					}
					
					$minPrice['PRINT_VALUE'] = \CCurrencyLang::CurrencyFormat($minPrice['VALUE'], $minPrice['CURRENCY']);
					$minPrice['PRINT_DISCOUNT_VALUE'] = \CCurrencyLang::CurrencyFormat($minPrice['DISCOUNT_VALUE'], $minPrice['CURRENCY']);
					$minPrice['PRINT_DISCOUNT_DIFF'] = \CCurrencyLang::CurrencyFormat($minPrice['DISCOUNT_DIFF'], $minPrice['CURRENCY']);

					unset($minPrice['PRICE_SCALE']);

					$arResult['ITEMS'][$key]['PRICE_MATRIX']['MIN_PRICES'][$index] = $minPrice;
				}
				unset($minPrice);
			}
			unset($index);
			unset($matrix, $rows);
		}
	}
	unset($key, $item);
}

$arResult['ALL_FIELDS'] = array();
$existShow = !empty($arResult['SHOW_FIELDS']);
$existDelete = !empty($arResult['DELETED_FIELDS']);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_FIELDS'] as $propCode)
		{
			$arResult['SHOW_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'N',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode);
		$arResult['ALL_FIELDS'] = $arResult['SHOW_FIELDS'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_FIELDS'] as $propCode)
		{
			$arResult['ALL_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'Y',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode, $arResult['DELETED_FIELDS']);
	}
	Collection::sortByColumn($arResult['ALL_FIELDS'], array('SORT' => SORT_ASC));
}

$arResult['ALL_PROPERTIES'] = array();
$existShow = !empty($arResult['SHOW_PROPERTIES']);
$existDelete = !empty($arResult['DELETED_PROPERTIES']);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult['SHOW_PROPERTIES'][$propCode]['IS_DELETED'] = 'N';
			$arResult['SHOW_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_PROPERTY_TEMPLATE']);

			$arResult['SHOW_PROPERTIES'][$propCode]['IS_SHOW'] = true;
			if ($arResult['DIFFERENT'])
			{
				$arCompare = array();
				foreach($arResult['ITEMS'] as &$arElement)
				{
					$arPropertyValue = $arElement['DISPLAY_PROPERTIES'][$propCode]['VALUE'];
					if (is_array($arPropertyValue))
					{
						sort($arPropertyValue);
						$arPropertyValue = implode(' / ', $arPropertyValue);
					}
					$arCompare[] = $arPropertyValue;
				}
				unset($arElement);
				$arResult['SHOW_PROPERTIES'][$propCode]['IS_SHOW'] = (count(array_unique($arCompare)) > 1);
			}
			$groupCode = isset($arProperties[$arProp['ID']]) ? $arProperties[$arProp['ID']] : 'NOT_GRUPED_PROPS';

			if ($arResult['SHOW_PROPERTIES'][$propCode]['IS_SHOW'])
			{
				$arResult['PROPERTIES_GROUPS'][$groupCode]['IS_SHOW'] = true;
			}
			$arResult['PROPERTIES_GROUPS'][$groupCode]['BINDS'][$arProp['ID']] = $propCode;
		}
		$arResult['ALL_PROPERTIES'] = $arResult['SHOW_PROPERTIES'];
	}
	unset($arProp, $propCode);

	if ($existDelete)
	{
		foreach ($arResult['DELETED_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult['DELETED_PROPERTIES'][$propCode]['IS_DELETED'] = 'Y';
			$arResult['DELETED_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_PROPERTY_TEMPLATE']);
			$arResult['ALL_PROPERTIES'][$propCode] = $arResult['DELETED_PROPERTIES'][$propCode];
		}
		unset($arProp, $propCode, $arResult['DELETED_PROPERTIES']);
	}
	Collection::sortByColumn($arResult['ALL_PROPERTIES'], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
}

$arResult['ALL_OFFER_FIELDS'] = array();
$existShow = !empty($arResult['SHOW_OFFER_FIELDS']);
$existDelete = !empty($arResult['DELETED_OFFER_FIELDS']);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_OFFER_FIELDS'] as $propCode)
		{
			$arResult['SHOW_OFFER_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'N',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode);
		$arResult['ALL_OFFER_FIELDS'] = $arResult['SHOW_OFFER_FIELDS'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_OFFER_FIELDS'] as $propCode)
		{
			$arResult['ALL_OFFER_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'Y',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode, $arResult['DELETED_OFFER_FIELDS']);
	}
	Collection::sortByColumn($arResult['ALL_OFFER_FIELDS'], array('SORT' => SORT_ASC));
}

$arResult['ALL_OFFER_PROPERTIES'] = array();
$existShow = !empty($arResult['SHOW_OFFER_PROPERTIES']);
$existDelete = !empty($arResult['DELETED_OFFER_PROPERTIES']);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_OFFER_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult['SHOW_OFFER_PROPERTIES'][$propCode]['IS_DELETED'] = 'N';
			$arResult['SHOW_OFFER_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_PROPERTY_TEMPLATE']);

			$arResult['SHOW_OFFER_PROPERTIES'][$propCode]['IS_SHOW'] = true;
			if ($arResult['DIFFERENT'])
			{
				$arCompare = array();
				foreach($arResult['ITEMS'] as &$arElement)
				{
					$arPropertyValue = $arElement['OFFER_DISPLAY_PROPERTIES'][$propCode]['VALUE'];
					if(is_array($arPropertyValue))
					{
						sort($arPropertyValue);
						$arPropertyValue = implode(' / ', $arPropertyValue);
					}
					$arCompare[] = $arPropertyValue;
				}
				unset($arElement);
				$arResult['SHOW_OFFER_PROPERTIES'][$propCode]['IS_SHOW'] = (count(array_unique($arCompare)) > 1);
			}

			$groupCode = isset($arProperties[$arProp['ID']]) ? $arProperties[$arProp['ID']] : 'NOT_GRUPED_PROPS';
			if ($arResult['SHOW_OFFER_PROPERTIES'][$propCode]['IS_SHOW'])
			{
				$arResult['PROPERTIES_GROUPS'][$groupCode]['IS_SHOW'] = true;
			}
			$arResult['PROPERTIES_GROUPS'][$groupCode]['BINDS'][$arProp['ID']] = $propCode;
		}
		unset($arProp, $propCode);
		$arResult['ALL_OFFER_PROPERTIES'] = $arResult['SHOW_OFFER_PROPERTIES'];
	}

	if ($existDelete)
	{
		foreach ($arResult['DELETED_OFFER_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult['DELETED_OFFER_PROPERTIES'][$propCode]['IS_DELETED'] = 'Y';
			$arResult['DELETED_OFFER_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_PROPERTY_TEMPLATE']);
			$arResult['ALL_OFFER_PROPERTIES'][$propCode] = $arResult['DELETED_OFFER_PROPERTIES'][$propCode];
		}
		unset($arProp, $propCode, $arResult['DELETED_OFFER_PROPERTIES']);
	}
	Collection::sortByColumn($arResult['ALL_OFFER_PROPERTIES'], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
}