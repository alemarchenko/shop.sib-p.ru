<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

use \Bitrix\Main\Localization\Loc;

$showDiscount = $price['DISCOUNT_VALUE'] < $price['VALUE'];
?>
<?php
if ($arParams['SHOW_OLD_PRICE'] === 'Y')
{
	?>
	<span class="product-cat-price-old">
		<?=($price['DISCOUNT_VALUE'] >= $price['VALUE'] ? '' : $price['PRINT_VALUE'])?>
	</span>
	<?php
}
?>

<div class="product-cat-price-current<?=($price['DISCOUNT_VALUE'] < $price['VALUE'] ? ' discount' : '')?>">
	<?
	if (!empty($price))
	{
		// if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
		// {

			// echo Loc::getMessage(
				// 'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
				// array(
					// '#PRICE#' => $price['PRINT_RATIO_PRICE'],
					// '#VALUE#' => $measureRatio,
					// '#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
				// )
			// );
		// }
		// else
		// {
			echo $price['PRINT_DISCOUNT_VALUE'];
		// }
	}
	else
	{
		echo Loc::getMessage('RS_MM_BCI_CATALOG_NO_PRICE');

	}
	?>
</div>

<?php
if ($arParams['SHOW_OLD_PRICE'] === 'Y' && $arParams['SHOW_DISCOUNT_PERCENT'] != 'Y')
{
	?>
	<span class="product-cat-price-economy">
		<?php
		if ($showDiscount)
		{
			echo str_replace('#ECONOMY#', $price['PRINT_DISCOUNT_DIFF'], $arParams['MESS_ECONOMY_INFO2']);
		}
		?>
	</span>
	<?php
}
?>