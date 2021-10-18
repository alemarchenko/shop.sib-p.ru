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

if (!$haveOffers)
{
	if ($actualItem['CAN_BUY'] && $arParams['USE_PRODUCT_QUANTITY'])
	{
		?>
		<div class="product-cat-amount d-inline-block align-middle mb-4" data-entity="quantity-block">
			<div class="product-cat-amount-field-container">
				<span class="product-cat-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>"></span>
				<span class="input-group d-inline-flex flex-nowrap w-auto">
					<input class="product-cat-amount-field form-control" id="<?=$itemIds['QUANTITY']?>" type="number"
						name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>"
						value="<?=$measureRatio?>">
					<span class="product-cat-amount-measure input-group-append">
						<span id="<?=$itemIds['QUANTITY_MEASURE']?>" class="input-group-text"><?=$actualItem['ITEM_MEASURE']['TITLE']?></span>
					</span>
				</span>
				<span class="product-cat-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>"></span>
			</div>
		</div>
		<?
	}
}
elseif ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
{
	if ($arParams['USE_PRODUCT_QUANTITY'])
	{
		?>
		<div class="product-cat-amount d-inline-block align-middle mb-4" data-entity="quantity-block">
			<div class="product-cat-amount-field-container">
				<span class="product-cat-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>"></span>
				<span class="input-group d-inline-flex flex-nowrap w-auto">
					<input class="product-cat-amount-field form-control" id="<?=$itemIds['QUANTITY']?>" type="number"
						name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>"
						value="<?=$measureRatio?>">
					<span class="product-cat-amount-measure input-group-append">
						<span id="<?=$itemIds['QUANTITY_MEASURE']?>" class="input-group-text"><?=$actualItem['ITEM_MEASURE']['TITLE']?></span>
					</span>
				</span>
				<span class="product-cat-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>"></span>
			</div>
		</div>
		<?
	}
}