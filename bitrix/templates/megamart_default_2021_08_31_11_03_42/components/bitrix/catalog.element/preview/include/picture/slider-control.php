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
		if ($showSliderControls)
		{
			?>
			<a href="<?=$arResult['DETAIL_PAGE_URL']?>">
				<?php
				if ($haveOffers)
				{
					?>
					<span class="product-cat-image-slider-control-container" id="<?=$itemIds['SLIDER_CONT_ID']?>">
						<?
						foreach ($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['MORE_PHOTO'] as $key => $photo)
						{
							if ($key > $arParams['SLIDER_SLIDE_COUNT'] - 1)
								break;
							?>
							<span class="product-cat-image-slider-control<?=($key == 0 ? ' active' : '')?>" data-entity="slider-control" data-go-to="<?=$key?>"></span>
							<?
						}
						?>
					</span>
					<?
				}
				else
				{
					?>
					<span class="product-cat-image-slider-control-container" id="<?=$itemIds['SLIDER_CONT_ID']?>">
						<?
						if (!empty($actualItem['MORE_PHOTO']))
						{
							foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
							{
								if ($key > $arParams['SLIDER_SLIDE_COUNT'] - 1)
									break;
								?>
								<span class="product-cat-image-slider-control<?=($key == 0 ? ' active' : '')?>" data-entity="slider-control" data-go-to="<?=$key?>"></span>
								<?
							}
						}
						?>
					</span>
					<?
				}
				?>
			</a>
			<?php
		}
	}
}
