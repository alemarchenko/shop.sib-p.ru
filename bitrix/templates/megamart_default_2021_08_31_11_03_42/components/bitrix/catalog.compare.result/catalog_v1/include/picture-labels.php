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

if ($item['LABEL'] || $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y')
{
	?>
	<span class="product-cat-label-text <?=$labelPositionClass?>" id="<?=$itemIds['STICKER_ID']?>">
		<?
		if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y')
		{
			?>
			<span class="product-cat-label-text-item" id="<?=$itemIds['DSC_PERC']?>"
				style="display:<?=($price['PERCENT'] > 0 ? '' : 'none')?>;">
				<?=-$price['PERCENT']?>%
			</span>
			<?
		}
		if (!empty($item['LABEL_ARRAY_VALUE']))
		{
			foreach ($item['LABEL_ARRAY_VALUE'] as $code => $value)
			{
				$sLabelStyle = '';
				if (substr($item['PROPERTIES'][$code]['VALUE_XML_ID'], 0, 1) == '#') {
					$sLabelStyle = ' style="background:'.$item['PROPERTIES'][$code]['VALUE_XML_ID'].'"';
				}
				?>
				<span class="product-cat-label-text-item<?=(!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' hidden-xs' : '')?>"<?if (strlen($sLabelStyle) > 0){ echo $sLabelStyle; }?> title=" <?=$value?>">
					<?=$value?>
				</span>
				<?
			}
		}
		?>
	</span>
	<?
}
