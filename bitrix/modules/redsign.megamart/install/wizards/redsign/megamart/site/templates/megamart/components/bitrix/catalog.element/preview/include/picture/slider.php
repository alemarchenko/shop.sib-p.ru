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
use \Redsign\MegaMart\MyTemplate;

$arSliderOptions = array(
	'margin' => 0,
	'nav' => true,
/*
	'navClass' => array('owl-prev', 'owl-next'),
	'navText' => array(
		'<svg class="icon-svg icon-svg-chevron-left"><use xlink:href="#svg-chevron-left"></use></svg>',
		'<svg class="icon-svg icon-svg-chevron-right"><use xlink:href="#svg-chevron-right"></use></svg>'
	),
*/
	'dots' => true,
	'dotsData' => true,
	'dotsContainer' => '#'.$itemIds['BIG_SLIDER_DOTS_ID'],
	'items' => 1,
	'responsive' => array(),
);

if (!empty($actualItem['MORE_PHOTO']))
{
	if (is_array($actualItem['MORE_PHOTO']) && count($actualItem['MORE_PHOTO']) > 0)
	{
		?>
		<div class="product-detail-slider-images-container product-cat-image-slider slide" data-entity="images-container">
			<?php
			foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
			{
				if ($key > $arParams['SLIDER_SLIDE_COUNT'] - 1)
					break;
				?>
				<span class="product-cat-image-slide item<?=($key == 0 ? ' active' : '')?>" data-entity="image">
					<?php
					if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y')
					{
						?>
						<img class="product-cat-image lazy-anim-img" data-src="<?=isset($photo['RESIZE']['big']['src']) ? $photo['RESIZE']['big']['src'] : $photo['SRC']?>" src="<?=\Redsign\MegaMart\LazyloadUtils::getEmptyImage(1, 1);?>"  alt="<?=$alt?>" title="<?=$title?>"<?=($key == 0 ? ' itemprop="image"' : '')?>>
						<?php
					}
					else
					{
						?>
						<img class="product-cat-image" src="<?=isset($photo['RESIZE']['big']['src']) ? $photo['RESIZE']['big']['src'] : $photo['SRC']?>" alt="<?=$alt?>" title="<?=$title?>"<?=($key == 0 ? ' itemprop="image"' : '')?>>
						<?php
					}
					
					if ($key > $arParams['SLIDER_SLIDE_COUNT'] - 2 && count($actualItem['MORE_PHOTO']) > $arParams['SLIDER_SLIDE_COUNT'])
					{
						?>
						<span class="product-cat-image-slider-more">
							<span class="product-cat-image-slider-more-wrapper">
								<span class="product-cat-image-slider-more-icon">
									<svg class="icon-svg"><use xlink:href="#svg-camera"></use></svg>
								</span>
								<span class="product-cat-image-slider-more-text">
									<?=Loc::getMessage('RS_MM_BCI_CATALOG_SLIDER_MORE_MESSAGE',  array('#NUMBER#' => count($actualItem['MORE_PHOTO']) - $arParams['SLIDER_SLIDE_COUNT']))?>
								</span>
							</span>
						</span>
						<?php
					}
					?>
				</span>
			<?php
			}
			?>
		</div>
		<?php
	}
}
