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

if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
{
	if ($haveOffers)
	{
		?>
		<div class="mb-3" id="<?=$itemIds['QUANTITY_LIMIT']?>" style="display: none;">
			<div class="product-detail-info-container-title">
				<?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
			</div>
			<span class="product-cat-quantity" data-entity="quantity-limit-value"></span>
		</div>
		<?
	}
	else
	{
		if (
			$measureRatio
			&& (float)$actualItem['PRODUCT']['QUANTITY'] > 0
			&& $actualItem['CHECK_QUANTITY']
		)
		{
			?>
			<div class="mb-3" id="<?=$itemIds['QUANTITY_LIMIT']?>">
				<div class="product-detail-info-container-title">
					<?=$arParams['MESS_SHOW_MAX_QUANTITY']?>:
				</div>
				<span class="product-cat-quantity" data-entity="quantity-limit-value">
						<?
						if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
						{
							if ((float)$actualItem['PRODUCT']['QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
							{
								echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
							}
							else
							{
								echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
							}
						}
						else
						{
							echo $actualItem['PRODUCT']['QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
						}
						?>
					</span>
			</div>
			<?
		}
	}
}