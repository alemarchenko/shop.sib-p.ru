<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var string $picture
 * @var string $productAlt
 * @var string $productTitle
 */

use \Bitrix\Main\Localization\Loc;

if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y')
{
	?>
	<img class="product-cat-image lazy-anim-img" data-src="<?=$picture?>" src="<?=Redsign\MegaMart\LazyloadUtils::getEmptyImage(1, 1);?>" alt="<?=$productAlt?>" title="<?=$productTitle?>">
	<?php
}
else
{
	?>
	<img class="product-cat-image" src="<?=$picture?>" alt="<?=$productAlt?>" title="<?=$productTitle?>">
	<?php
}
