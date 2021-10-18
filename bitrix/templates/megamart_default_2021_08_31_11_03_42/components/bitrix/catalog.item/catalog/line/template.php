<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\MyTemplate;

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


$itemClass =  'product-cat mw-768px m-auto';
// if (isset($arParams['TEMPLATE_VIEW']) && strlen($arParams['TEMPLATE_VIEW']) > 0)
// {
	// $itemClass .= ' product-cat-'.$arParams['TEMPLATE_VIEW'];
// }
if ($haveOffers)
{
	$showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
	$showProductProps = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $item['OFFERS_PROPS_DISPLAY'];
	$showPropsBlock = $showDisplayProps || $showProductProps;
	$showSkuBlock = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && !empty($item['OFFERS_PROP']);
}
else
{
	$showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
	$showProductProps = $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES']);
	$showPropsBlock = $showDisplayProps || $showProductProps;
	$showSkuBlock = false;
}

$arParams['MESS_BTN_BUY'] = '<svg class="icon-cart icon-svg mr-3"><use xlink:href="#svg-cart"></use></svg>'.$arParams['MESS_BTN_BUY'];
$arParams['MESS_BTN_ADD_TO_BASKET'] = '<svg class="icon-cart icon-svg mr-3"><use xlink:href="#svg-cart"></use></svg>'.$arParams['MESS_BTN_ADD_TO_BASKET'];
$arParams['MESS_BTN_INCART'] = '<svg class="icon-cart icon-svg mr-3"><use xlink:href="#svg-check"></use></svg>'.Loc::getMessage('RS_MM_BCI_CATALOG_MESS_BTN_INCART');
?>
<article class="<?=$itemClass?>">
	<div class="row">
		<div class="col-12 col-sm-4">
			<div class="product-cat-image-wrapper">
				<?php
				if ($itemHasDetailUrl)
				{
					?>
					<a class="product-cat-image-canvas" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$imgTitle?>"
							data-entity="image-wrapper">
					<?php
				}
				else
				{
					?>
					<span class="product-item-image-wrapper" data-entity="image-wrapper">
					<?php
				}

				include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-image.php'));
				include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-labels.php'));
				include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-deals.php'));
				include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-gift.php'));
				include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-slider.php'));

				if ($itemHasDetailUrl)
				{
					?>
					</a>
					<?php
				}
				else
				{
					?>
					</span>
					<?php
				}
				?>

				<?php/* if ($arParams['PRODUCT_PREVIEW'] == 'Y'): ?>
					<span class="product-cat-image-overlay">
						<span class="product-cat-image-overlay-link" data-entity="preview"><?=Loc::getMessage('RS_MM_BCI_CATALOG_PRODUCT_PREVIEW')?></span>
					</span>
				<?php endif; */?>

				<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-actions.php')); ?>
			</div>
		</div>
		<div class="col-12 col-sm-8">

	<div class="product-cat-content">

		<div class="product-cat-head">
<?/*
			<?php if (strlen($item['SECTION']['SECTION_PAGE_URL']) > 0): ?>
				<div class="product-cat-parent d-none d-sm-block">
					<a href="<?=$item['SECTION']['SECTION_PAGE_URL']?>"><?=$item['SECTION']['NAME']?></a>
				</div>
			<?php endif; ?>
*/?>

			<h6 class="product-cat-title">
				<? if ($itemHasDetailUrl): ?>
					<a href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$productTitle?>">
				<? endif; ?>

				<?=$productTitle?>

				<? if ($itemHasDetailUrl): ?>
					</a>
				<? endif; ?>
			</h6>

			<?php
			if ($arParams['USE_VOTE_RATING'] === 'Y' || $arParams['SHOW_MAX_QUANTITY'] !== 'N' || $arParams['SHOW_ARTNUMBER'] === 'Y')
			{
				?>
				<div class="product-cat-info-container mb-2 small text-extra ">
					<?php
					if ($arParams['SHOW_ARTNUMBER'] === 'Y')
					{
						?>
						<span class="mr-3">
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/id.php')); ?>
						</span>
						<?php
					}
					if ($arParams['USE_VOTE_RATING'] === 'Y')
					{
						?>
						<span class="align-middle mr-3">
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/rate.php')); ?>
						</span>
						<?php
					}
					if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
					{
						?>
						<span>
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/limit.php')); ?>
						</span>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>

		<div class="product-cat-info-container mb-0 mb-sm-5">
			<div class="product-cat-info-container-title small text-extra w-100 mb--2">
				<?php
				if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
				{
					echo Loc::getMessage(
						'RS_MM_BCI_CATALOG_PRICE_FROM_SIMPLE_MODE',
						array(
							'#VALUE#' => $measureRatio,
							'#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
						)
					);
				}
				else
				{
					if (strlen($arResult['CAT_PRICES'][$priceCode]['TITLE']) > 0)
					{
						echo $arResult['CAT_PRICES'][$priceCode]['TITLE'];
					}
					else
					{
						echo Loc::getMessage('RS_MM_BCI_CATALOG_PRICE');
					}
				}
				?>:
			</div>
			<div class="product-cat-price-container" data-entity="price-block">
				<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php')); ?>
			</div>
		</div>

		<div class="product-cat-body">
			<?php
			if (!empty($arParams['PRODUCT_BLOCKS_ORDER']))
			{
				foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName)
				{
/*
					if (!in_array($blockName, $arParams['PRODUCT_BLOCKS'])) {
						continue;
					}
*/
					switch ($blockName)
					{
						case 'price':
							break;

						case 'quantityLimit':
							break;

						case 'quantity':
							break;

						case 'buttons':
							break;

						case 'props':
							include(MyTemplate::getTemplatePart($templateFolder.'/include/props.php'));
							break;

						case 'sku':
							if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $haveOffers && !empty($item['OFFERS_PROP']))
							{
								?>
								<div id="<?=$itemIds['PROP_DIV']?>">
									<?
									foreach ($arParams['SKU_PROPS'] as $skuProperty)
									{
										$propertyId = $skuProperty['ID'];
										$skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
										if (!isset($item['SKU_TREE_VALUES'][$propertyId]))
											continue;
										?>
										<div class="product-cat-info-container product-cat-scu-container mb-4" data-entity="sku-block">
											<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/line/sku.php')); ?>
										</div>
										<?
									}
									?>
								</div>
								<?
								foreach ($arParams['SKU_PROPS'] as $skuProperty)
								{
									if (!isset($item['OFFERS_PROP'][$skuProperty['CODE']]))
										continue;

									$skuProps[] = array(
										'ID' => $skuProperty['ID'],
										'SHOW_MODE' => $skuProperty['SHOW_MODE'],
										'VALUES' => $skuProperty['VALUES'],
										'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
									);
								}

								unset($skuProperty, $value);


								if ($item['OFFERS_PROPS_DISPLAY'])
								{
									foreach ($item['JS_OFFERS'] as $keyOffer => $jsOffer)
									{
										$strProps = '';

										if (!empty($jsOffer['DISPLAY_PROPERTIES']))
										{
											foreach ($jsOffer['DISPLAY_PROPERTIES'] as $displayProperty)
											{
												$strProps .= '<dt>'.$displayProperty['NAME'].'</dt><dd>'
													.(is_array($displayProperty['VALUE'])
														? implode(' / ', $displayProperty['VALUE'])
														: $displayProperty['VALUE'])
													.'</dd>';
											}
										}

										$item['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
									}
									unset($jsOffer, $strProps);
								}
							}
							break;

						case 'compare':
							break;

						case 'preview';
							if ($arParams['DISPLAY_PREVIEW_TEXT'] === 'Y' && $item['PREVIEW_TEXT'])
							{
								?>
								<div class="product-cat-info-container mb-5" data-entity="props-preview">
									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/preview.php')); ?>
								</div>
								<?php
							}
							break;
					}
				}
			}
			?>
		</div>

		<div class="product-cat-button-container">
			<?php
			if ($arParams['ADD_TO_BASKET_ACTION'] == 'REQUEST')
			{
				include(MyTemplate::getTemplatePart($templateFolder.'/include/line/actions-request.php'));
			}
			else
			{
				if (!$haveOffers)
				{
					if ($actualItem['CAN_BUY'] || $arParams['BUY_ON_CAN_BUY'] == 'Y')
					{
						?>
						<div id="<?=$itemIds['BASKET_ACTIONS']?>">
							<div class="d-inline-block align-middle mr-sm-3">
								<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/line/actions.php')); ?>
							</div>
							<?php
							if ($arParams['USE_PRODUCT_QUANTITY'])
							{
								include(MyTemplate::getTemplatePart($templateFolder.'/include/quantity.php'));
							}
							?>
							<div class="product-cat-amount-description-container" id="<?=$itemIds['PRICE_TOTAL']?>"></div>
						</div>
						<?
					}
					else
					{
						include(MyTemplate::getTemplatePart($templateFolder.'/include/line/subscribe.php'));
					}
				}
				else
				{
					if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
					{
						include(MyTemplate::getTemplatePart($templateFolder.'/include/line/subscribe.php'));
						?>

						<div id="<?=$itemIds['BASKET_ACTIONS']?>"<?/* style="display: <?=($actualItem['CAN_BUY'] ? '' : 'none')?>;"*/?>>
							<div class="d-inline-block align-middle mr-sm-3">
								<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/line/actions.php')); ?>
							</div>
							<?php
							if ($arParams['USE_PRODUCT_QUANTITY'])
							{
								include(MyTemplate::getTemplatePart($templateFolder.'/include/quantity.php'));
							}
							?>
							<div class="product-cat-amount-description-container" id="<?=$itemIds['PRICE_TOTAL']?>"></div>
						</div>
						<?
					}
				}
			}
			?>
		</div>
		</div>
	</div>
</article>
