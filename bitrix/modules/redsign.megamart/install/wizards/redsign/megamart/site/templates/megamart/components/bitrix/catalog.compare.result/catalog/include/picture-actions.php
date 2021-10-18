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

?>
<div class="product-cat-image-action">

<?php
if ($arParams['USE_FAVORITE'])
{
	?>
	<div class="product-cat-action-container">
		<label class="product-cat-action" data-entity="compare-item-favorite">
			<svg class="product-cat-action-icon icon-svg"><use xlink:href="#svg-heart"></use></svg>
		</label>
	</div>
	<?php
}
?>

<?php
if ($arParams['PRODUCT_PREVIEW'] == 'Y')
{
	$arElementPreviewOptions = array(
		'autoFocus' => false
	);
	?>
	<div class="product-cat-action-container product-cat-preview">
		<label class="product-cat-action" data-type="ajax" data-options="<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($arElementPreviewOptions))?>"<?/*data-fancybox="product<?=$item['ID']?>"*/?> data-src="<?=$item['DETAIL_PAGE_URL']?>" data-fancybox-title="false" title="<?=Loc::getMessage('RS_MM_BCI_CATALOG_PRODUCT_PREVIEW')?>">
			<svg class="product-cat-action-icon icon-svg"><use xlink:href="#svg-magnifier"></use></svg>
		</label>
	</div>
	<?php
}
?>

</div>