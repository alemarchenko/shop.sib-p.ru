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

if ($arParams['USE_GIFTS'] == 'Y' && is_array($item['GIFT_ITEMS']) && count($item['GIFT_ITEMS']) > 0)
{
	?>
	<span class="product-cat-gift">
		<svg class="product-cat-gift-icon icon-svg"><use xlink:href="#svg-gift"></use></svg>
	</span>
	<?
}