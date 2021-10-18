<?php

namespace Redsign\MegaMart;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ElementListUtils
{

	private static $instance = array();

	public function __construct($component) {
        $this->setAction($component->getAction());

        $this->arParams = $component->arParams;
        $this->arResult = $component->arResult;
	}

	public function getAction()
	{
		return $this->action;
	}

	protected function setAction($action)
	{
		$this->action = $action;
	}

	// getting positions of enlarged elements
	protected function getEnlargedIndexMap($items)
	{
		$enlargedIndexMap = array();

		foreach ($this->arResult['ITEMS'] as $key => $item)
		{
			if ($item['ENLARGED'] === 'Y')
			{
				$enlargedIndexMap[] = $key;
			}
		}

		return $enlargedIndexMap;
	}

	public function getTemplateVariantsMap()
    {
        return array(
            array(
                'VARIANT' => 0,
                'TYPE' => 'CARD',
                'COLS' => 1,
                'CLASS' => 'product-item-list-col-1',
                'CODE' => '1',
                'ENLARGED_POS' => false,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 1,
                'DEFAULT' => 'N'
            ),
            array(
                'VARIANT' => 1,
                'TYPE' => 'CARD',
                'COLS' => 2,
                'CLASS' => 'product-item-list-col-2',
                'CODE' => '2',
                'ENLARGED_POS' => false,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 2,
                'DEFAULT' => 'N'
            ),
            array(
                'VARIANT' => 2,
                'TYPE' => 'CARD',
                'COLS' => 3,
                'CLASS' => 'product-item-list-col-3',
                'CODE' => '3',
                'ENLARGED_POS' => false,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 3,
                'DEFAULT' => 'Y'
            ),
            array(
                'VARIANT' => 3,
                'TYPE' => 'CARD',
                'COLS' => 4,
                'CLASS' => 'product-item-list-col-4',
                'CODE' => '4',
                'ENLARGED_POS' => false,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 4,
                'DEFAULT' => 'N'
            ),
            array(
                'VARIANT' => 4,
                'TYPE' => 'CARD',
                'COLS' => 4,
                'CLASS' => 'product-item-list-col-1-4',
                'CODE' => '1-4',
                'ENLARGED_POS' => 0,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 5,
                'DEFAULT' => 'N'
            ),
            array(
                'VARIANT' => 5,
                'TYPE' => 'CARD',
                'COLS' => 4,
                'CLASS' => 'product-item-list-col-4-1',
                'CODE' => '4-1',
                'ENLARGED_POS' => 4,
                'SHOW_ONLY_FULL' => true,
                'COUNT' => 5,
                'DEFAULT' => 'N'
            ),
            array(
                'VARIANT' => 6,
                'TYPE' => 'CARD',
                'COLS' => 6,
                'CLASS' => 'product-item-list-col-6',
                'CODE' => '6',
                'ENLARGED_POS' => false,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 6,
                'DEFAULT' => 'N'
            ),
            array(
                'VARIANT' => 7,
                'TYPE' => 'CARD',
                'COLS' => 6,
                'CLASS' => 'product-item-list-col-1-6',
                'CODE' => '1-6',
                'ENLARGED_POS' => 0,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 7,
                'DEFAULT' => 'N'
            ),
            array(
                'VARIANT' => 8,
                'TYPE' => 'CARD',
                'COLS' => 6,
                'CLASS' => 'product-item-list-col-6-1',
                'CODE' => '6-1',
                'ENLARGED_POS' => 6,
                'SHOW_ONLY_FULL' => true,
                'COUNT' => 7,
                'DEFAULT' => 'N'
            ),
            array(
                'VARIANT' => 9,
                'TYPE' => 'LINE',
                'COLS' => 1,
                'CLASS' => 'product-item-line-list',
                'CODE' => 'line',
                'ENLARGED_POS' => false,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 1,
                'DEFAULT' => 'N'
			),
            array(
                'VARIANT' => 10,
                'TYPE' => 'CARD',
                'COLS' => 5,
                'CLASS' => 'product-item-list-col-10',
                'CODE' => '10',
                'ENLARGED_POS' => false,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 10,
                'DEFAULT' => 'N'
			),
            array(
                'VARIANT' => 11,
                'TYPE' => 'CARD',
                'COLS' => 5,
                'CLASS' => 'product-item-list-col-5',
                'CODE' => '5',
                'ENLARGED_POS' => false,
                'SHOW_ONLY_FULL' => false,
                'COUNT' => 5,
                'DEFAULT' => 'N'
			),
        );
	}

	public function getDefaultVariantId()
    {
        $variantId = 0;
        $templateVariantsMap = self::getTemplateVariantsMap();

        if (!empty($templateVariantsMap))
        {
            foreach ($templateVariantsMap as $key => $variant)
            {
                if (isset($variant['DEFAULT']) && $variant['DEFAULT'] === 'Y')
                {
                    $variantId = $variant['VARIANT'];
                    break;
                }
            }
        }

        return $variantId;
    }

	public function predictRowVariants($lineElementCount, $pageElementCount)
    {
        if ($pageElementCount <= 0)
        {
            return array();
        }

        $templateVariantsMap = static::getTemplateVariantsMap();

        if (empty($templateVariantsMap))
        {
            return array();
        }

        $variantId = self::getDefaultVariantId();

        foreach ($templateVariantsMap as $key => $variant)
        {
            if ($variant['COUNT'] == $lineElementCount && $variant['ENLARGED_POS'] === false)
            {
                $variantId = $key;
                break;
            }
        }

        return array_fill(
            0,
            ceil($pageElementCount / $templateVariantsMap[$variantId]['COUNT']),
            array('VARIANT' => $variantId, 'BIG_DATA' => false)
        );
    }

	/**
	 * Creating sequence of variants to show
	 */
	protected function sortItemsByTemplateVariants()
	{
		$rows = array();
		$variantsMap = static::getTemplateVariantsMap();
		$isBigData = $this->getAction() === 'bigDataLoad';

		if ($this->arParams['ENLARGE_PRODUCT'] === 'PROP')
		{
			$enlargedIndexMap = self::getEnlargedIndexMap();
		}

		if (!empty($this->arParams['PRODUCT_ROW_VARIANTS']))
		{
			$showItems = false;

			foreach ($this->arParams['PRODUCT_ROW_VARIANTS'] as $variant)
			{
				if (
					(!$isBigData && !$variant['BIG_DATA'])
					|| ($isBigData && $variant['BIG_DATA'])
				)
				{
					$showItems = true;
					break;
				}
			}
		}
		else
		{
			$showItems = true;
		}

		if ($showItems)
		{
			$variantParam = false;
			$itemsCounter = 0;
			$itemsLength = count($this->arResult['ITEMS']);

			while (($itemsRemaining = $itemsLength - $itemsCounter) > 0)
			{
				if ($variantParam === false)
				{
					$variantParam = reset($this->arParams['PRODUCT_ROW_VARIANTS']);
				}

				//	skip big_data rows on initial load and not_big_data rows on deferred load
				if (!empty($variantParam))
				{
					if (
						$isBigData && !$variantParam['BIG_DATA']
						|| !$isBigData && $variantParam['BIG_DATA']
					)
					{
						$variantParam = next($this->arParams['PRODUCT_ROW_VARIANTS']);
						// if last variant is not suitable - should reset again
						if ($variantParam === false)
						{
							$variantParam = reset($this->arParams['PRODUCT_ROW_VARIANTS']);
						}

						if ($variantParam === false)
							break;
						else
							continue;
					}
				}

				if (
					$variantParam === false
					|| !isset($variantsMap[$variantParam['VARIANT']])
					|| ($variantsMap[$variantParam['VARIANT']]['SHOW_ONLY_FULL'] && $variantsMap[$variantParam['VARIANT']]['COUNT'] > $itemsRemaining)
				)
				{
					// default variant
					$variant = $variantsMap[self::getDefaultVariantId()];
				}
				else
				{
					$variant = $variantsMap[$variantParam['VARIANT']];
				}

				// sorting by property $arResult['ITEMS'] for proper elements enlarge
				if ($this->arParams['ENLARGE_PRODUCT'] === 'PROP' && $variant['ENLARGED_POS'] !== false)
				{
					if (!empty($enlargedIndexMap))
					{
						$overallPos = $itemsCounter + $variant['ENLARGED_POS'];
						$overallPosKey = array_search($overallPos, $enlargedIndexMap);
						if ($overallPosKey === false)
						{
							$closestPos = false;
							$closestPosKey = false;
							$enlargedPosInRange = array_intersect($enlargedIndexMap , range($itemsCounter, $itemsCounter + $variant['COUNT']));

							if (!empty($enlargedPosInRange))
							{
								foreach ($enlargedPosInRange as $key => $posInRange)
								{
									if ($closestPos === false || abs($overallPos - $closestPos) > abs($posInRange - $overallPos))
									{
										$closestPos = $posInRange;
										$closestPosKey = $key;
									}
								}

								$temporary = array($this->arResult['ITEMS'][$closestPos]);
								unset($this->arResult['ITEMS'][$closestPos], $enlargedIndexMap[$closestPosKey]);
								array_splice($this->arResult['ITEMS'], $overallPos, 0, $temporary);
							}
						}
						else
						{
							unset($enlargedIndexMap[$overallPosKey]);
						}
					}
				}

				$rows[] = $variant;
				$itemsCounter += $variant['COUNT'];
				$variantParam = next($this->arParams['PRODUCT_ROW_VARIANTS']);
			}
		}

		$this->arResult['ITEM_ROWS'] = $rows;
	}

	public function applyTemplateModifications() {
		$this->sortItemsByTemplateVariants();
	}

	public function getItemRows() {
		return $this->arResult['ITEM_ROWS'];
	}

	public static function getInstance($component) {
		if (is_null(self::$instance[$id]))
		{
            $id = spl_object_hash($component);
			self::$instance[$id] = new ElementListUtils($component);
		}

		return self::$instance[$id];
	}

}
