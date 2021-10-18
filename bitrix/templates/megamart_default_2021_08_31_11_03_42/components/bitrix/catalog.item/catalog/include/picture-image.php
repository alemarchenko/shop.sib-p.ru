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

if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y')
{
	?>
	<img class="product-cat-image lazy-anim-img"<?=($showSlider ? ' style="display: none;"' : '')?> data-src="<?=$item['PREVIEW_PICTURE']['SRC']?>" src="<?=Redsign\MegaMart\LazyloadUtils::getEmptyImage(1, 1);?>" id="<?=$itemIds['PICT']?>" alt="<?=$item['PREVIEW_PICTURE']['ALT']?>" title="<?=$item['PREVIEW_PICTURE']['TITLE']?>">
	<?php
}
else
{
	?>
	<img class="product-cat-image" id="<?=$itemIds['PICT']?>"<?=($showSlider ? ' style="display: none;"' : '')?> src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="<?=$item['PREVIEW_PICTURE']['ALT']?>" title="<?=$item['PREVIEW_PICTURE']['TITLE']?>">
	<?php
}
