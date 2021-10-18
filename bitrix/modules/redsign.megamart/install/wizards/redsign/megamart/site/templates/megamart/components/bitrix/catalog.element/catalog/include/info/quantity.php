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

if ($arParams['USE_PRODUCT_QUANTITY'])
{
	?>
	<div class="product-cat-amount d-inline-block align-middle mb-4" style="<?=(!$actualItem['CAN_BUY'] ? 'display: none;' : '')?>"
		data-entity="quantity-block">
		<div class="product-cat-amount-field-container">
			<span class="product-cat-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN_ID']?>"></span>
			<span class="input-group d-inline-flex flex-nowrap w-auto">
				<input class="product-cat-amount-field form-control" id="<?=$itemIds['QUANTITY_ID']?>" type="number"
					name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>"
					value="<?=$measureRatio?>">
				<span class="product-cat-amount-measure input-group-append">
					<span id="<?=$itemIds['QUANTITY_MEASURE']?>" class="input-group-text"><?=$actualItem['ITEM_MEASURE']['TITLE']?></span>
				</span>
			</span>
			<span class="product-cat-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP_ID']?>"></span>
		</div>
	</div>
	<?
}