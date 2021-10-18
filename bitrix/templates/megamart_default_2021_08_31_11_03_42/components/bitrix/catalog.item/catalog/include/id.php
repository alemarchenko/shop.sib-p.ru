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
<?php
if (!empty($actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['VALUE']))
{
	?>
	<span class="product-cat-artnumber" data-entity="sku-prop-<?=$actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['ID']?>">
		<?php
		echo str_replace(
			'#NUMBER#',
			$actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['VALUE'],
			$arParams['MESS_ITEM_ARTNUMBER']
		);
		?>
	</span>
	<?php
}
elseif (!empty($arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE']))
{
	?>
	<span class="product-cat-artnumber">
		<?php
		echo str_replace(
			'#NUMBER#',
			$item['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$item['IBLOCK_ID']]]['VALUE'],
			$arParams['MESS_ITEM_ARTNUMBER']
		);
		?>
	</span>
	<?php
}
?>