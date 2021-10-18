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

if ($haveOffers)
{
	?>
	<span class="product-cat-deals" id="<?=$itemIds['PRODUCT_DEAL']?>" style="display:none">
		<svg class="product-cat-deals-icon icon-svg"><use xlink:href="#svg-alarm"></use></svg>
		<span class="product-cat-deals-name" data-entity="product-deal-name"></span>
	</span>
	<?
}
else
{
	if (isset($actualItem['DAYSARTICLE']))
	{
		?>
		<span class="product-cat-deals">
			<svg class="product-cat-deals-icon icon-svg"><use xlink:href="#svg-alarm"></use></svg>
			<span class="product-cat-deals-name"><?=Loc::getMessage('RS_MM_BCE_CATALOG_DAYSARTICLE_TITLE')?></span>
		</span>
		<?
	}
	elseif (isset($actualItem['QUICKBUY']))
	{
		?>
		<span class="product-cat-deals">
			<svg class="product-cat-deals-icon icon-svg"><use xlink:href="#svg-alarm"></use></svg>
			<span class="product-cat-deals-name"><?=Loc::getMessage('RS_MM_BCE_CATALOG_QUICKBUY_TITLE')?></span>
		</span>
		<?
	}
}