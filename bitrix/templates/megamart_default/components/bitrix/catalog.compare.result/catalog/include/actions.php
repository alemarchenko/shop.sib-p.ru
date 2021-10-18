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

if ($bCanBuy)
{
	?>
	<div class="product-cat-button-container">
		<a class="btn btn-primary <?=$buttonSizeClass?>" href="<?=$item["BUY_URL"]?>" rel="nofollow" title="<?=strip_tags($arParams['MESS_BTN_BUY'])?>">
			<?=$arParams['MESS_BTN_BUY']?>
		</a>
	</div>
	<?
}
else
{
}
