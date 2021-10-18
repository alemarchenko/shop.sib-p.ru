<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;

$useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';
$showPrice = !empty($price);
?>
<div class="card font-size-sm mb--1" data-entity="price" data-price-id="<?=$arCatPrice['ID']?>"<?=$showPrice ? '' : ' style="display: none;"'?>>
	<div class="card-header py-3 px-4 mb--1">
		<?php
		if (strlen($arCatPrice['TITLE']) > 0)
		{
			echo $arCatPrice['TITLE'];
		}
		?>

		<?php
		// if (!$arParams['USE_PRICE_COUNT'] || count($actualItem['ITEM_QUANTITY_RANGES']) <= 1)
		// {
			?>
			<span data-entity="price-ranges-ratio-header">
				<?php
				if (count($actualItem['ITEM_QUANTITY_RANGES']) <= 1)
				{
					echo '('.Loc::getMessage(
						'CT_BCE_CATALOG_RATIO_PRICE',
						array('#RATIO#' => ($useRatio ? $measureRatio.' '.$actualItem['ITEM_MEASURE']['TITLE'] : ''))
						).')';
				}
				?>
			</span>
			<span class="float-right text-nowrap"<?/*id="<?=$mainId.'_old_price_'.$arCatPrice['ID']?>"*/?> data-entity="price-current">
				<?php
				if (!empty($price) && count($actualItem['ITEM_QUANTITY_RANGES']) <= 1)
				{
					echo $price['PRINT_RATIO_PRICE'];
				}
				?>
			</span>
			<?php
		// }
		?>
	</div>

	<?php
	if ($arParams['USE_PRICE_COUNT'])
	{
		$showRanges = !$haveOffers && count($actualItem['ITEM_QUANTITY_RANGES']) > 1;
		?>
		<div class="card-body py-3 px-4"
			<?=$showRanges ? '' : 'style="display: none;"'?>
			data-entity="price-ranges-block" data-price-id="<?=$arCatPrice['ID']?>">
			<dl class="product-detail-price-ranges list-justified" data-entity="price-ranges-body">

				<?php
				if ($showRanges)
				{
					foreach ($actualItem['ITEM_QUANTITY_RANGES'] as $range)
					{
						if ($range['HASH'] !== 'ZERO-INF')
						{
							$itemPrice = false;

							if ($showMultiPrice)
							{
								foreach ($arResult['ITEM_ALL_PRICES'] as $itemPrice)
								{
									if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
									{
										$itemPrice = $itemPrice['PRICES'][$price['PRICE_TYPE_ID']];
										break;
									}
								}
							}
							else
							{
								foreach ($arResult['ITEM_PRICES'] as $itemPrice)
								{
									if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
									{
										break;
									}
								}
							}

							if ($itemPrice)
							{
								?>
								<dt>
									<?
									echo Loc::getMessage(
											'CT_BCE_CATALOG_RANGE_FROM',
											array('#FROM#' => $range['SORT_FROM'].' '.$actualItem['ITEM_MEASURE']['TITLE'])
										).' ';

									if (is_infinite($range['SORT_TO']))
									{
										echo Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
									}
									else
									{
										echo Loc::getMessage(
											'CT_BCE_CATALOG_RANGE_TO',
											array('#TO#' => $range['SORT_TO'].' '.$actualItem['ITEM_MEASURE']['TITLE'])
										);
									}
									?>
								</dt>
								<dd><?=($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE'])?></dd>
								<?
							}
						}
					}
				}
				?>
			</dl>
		</div>
		<?
		unset($showRanges, $useRatio, $itemPrice, $range);
	}
	?>
</div>
<?