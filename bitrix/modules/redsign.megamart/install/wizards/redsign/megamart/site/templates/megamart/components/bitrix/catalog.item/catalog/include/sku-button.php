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
<li class="product-cat-scu-item-text-container btn btn-outline-secondary btn-sm" title="<?=$value['NAME']?>"
	data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
	<div class="product-cat-scu-item-text-block">
		<div class="product-cat-scu-item-text"><?=$value['NAME']?></div>
	</div>
</li>