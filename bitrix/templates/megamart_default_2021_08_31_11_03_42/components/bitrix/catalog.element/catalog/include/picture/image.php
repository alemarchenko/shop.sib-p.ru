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
		<div class="product-detail-slider-images-container show-items-1"
			data-entity="images-container" data-slider="<?=$itemIds['BIG_SLIDER_ID']?>" data-slider-options="<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($arSliderOptions))?>"
		>
			<?php
			$iSlideIndex = 0;

			foreach ($actualItem['MORE_PHOTO'] as $key => $arPhoto)
			{
				?>
				<span class="product-detail-slider-image"
					data-fancybox="gallery"
					data-caption="<?=$strTitle?>"
					data-src="<?=$arPhoto['SRC']?>"
					data-entity="image"
					data-index="<?=$iSlideIndex++?>"
					data-options='{"slideShow" : false}'
					data-dot="<?=htmlspecialcharsbx('<button class="owl-preview" style="background-image:url(\''.(isset($arPhoto['RESIZE']['small']['src']) ? $arPhoto['RESIZE']['small']['src'] : $arPhoto['SRC']).'\')"></button>')?>"
				><?php
					?><img src="<?=isset($arPhoto['RESIZE']['big']['src']) ? $arPhoto['RESIZE']['big']['src'] : $arPhoto['SRC']?>" alt="<?=$alt?>" title="<?=$title?>"<?=($key == 0 ? ' itemprop="image"' : '')?>><?php
				?></span>
				<?
			}
			if(!empty($arResult["DISPLAY_PROPERTIES"]["HTML_VIDEO"]["VALUE"]["TEXT"])){
			?>
			
				<span class="product-detail-slider-image"
					data-fancybox="gallery" 
					data-caption="<?=$strTitle?>"
					data-src="<?=$arResult["DISPLAY_PROPERTIES"]["HTML_VIDEO"]["VALUE"]["TEXT"]?>"
					data-entity="image" 
					data-index="<?=$iSlideIndex++?>"
					data-options='{"slideShow" : false}'
					data-dot="<?=htmlspecialcharsbx('<button class="owl-preview" style="background-image:url(\''.SITE_TEMPLATE_PATH.'/components/bitrix/catalog.element/catalog/images/play_icon.png"\')"></button>')?>"
				>
					<img src="<?=SITE_TEMPLATE_PATH?>/components/bitrix/catalog.element/catalog/images/play_icon_652.png" alt="<?=$alt?>" >
					<?php
				?></span>
			<?}
			
			?>
		</div>
		<?php
	}
}
