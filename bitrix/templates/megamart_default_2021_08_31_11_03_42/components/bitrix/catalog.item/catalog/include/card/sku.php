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
use \Redsign\MegaMart\MyTemplate;
?>
<div data-entity="sku-line-block">
	<div class="product-cat-info-container-title mb-2 small text-extra"><?=htmlspecialcharsEx($skuProperty['NAME'])?>: <span class="text-body" data-entity="sku-current-value"></span></div>
	<div class="product-cat-scu-block">
		<div class="product-cat-scu-list">
		<?php
		if (in_array($skuProperty['CODE'], $arParams['OFFER_TREE_DROPDOWN_PROPS']))
		{
			include(MyTemplate::getTemplatePart($templateFolder.'/include/sku-dropdown.php'));
		}
		else
		{
			?>
			<ul class="product-cat-scu-item-list">
				<?
				foreach ($skuProperty['VALUES'] as $value)
				{
					if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
						continue;

					$value['NAME'] = htmlspecialcharsbx($value['NAME']);

					if ($skuProperty['SHOW_MODE'] === 'PICT')
					{
						include(MyTemplate::getTemplatePart($templateFolder.'/include/sku-picture.php'));
					}
					else
					{
						include(MyTemplate::getTemplatePart($templateFolder.'/include/sku-button.php'));
					}
				}
				?>
			</ul>
			<?php
		}
		?>
		</div>
	</div>
</div>