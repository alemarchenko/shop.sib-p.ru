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


$itemClass =  'product-cat';
$showSlider = false;
// if (isset($arParams['TEMPLATE_VIEW']) && strlen($arParams['TEMPLATE_VIEW']) > 0)
// {
	// $itemClass .= ' product-cat-'.$arParams['TEMPLATE_VIEW'];
// }

$arParams['MESS_BTN_DETAIL'] = '<svg class="icon-cart icon-svg"><use xlink:href="#svg-dots-3"></use></svg>';
$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_ADD_TO_BASKET'] = '<svg class="icon-cart icon-svg"><use xlink:href="#svg-cart"></use></svg>';
$arParams['MESS_BTN_INCART'] = '<svg class="icon-cart icon-svg"><use xlink:href="#svg-check"></use></svg>';

$arParams['~MESS_BTN_SUBSCRIBE'] = '<svg class="icon-cart icon-svg"><use xlink:href="#svg-mail"></use></svg>';
?>

<article class="<?=$itemClass?>">
	<div class="row align-items-center">
		<div class="col-3 col-sm-2">
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
			</div>
		</div>

		<div class="col-9 col-sm-10">
			<div class="row h-100 align-items-baseline">

				<div class="col-12 col-sm">

	<div class="product-cat-content">
		<div class="product-cat-head">
<?php
/*
			<?php if (strlen($item['SECTION']['SECTION_PAGE_URL']) > 0): ?>
				<div class="product-cat-parent d-none d-sm-block">
					<a href="<?=$item['SECTION']['SECTION_PAGE_URL']?>"><?=$item['SECTION']['NAME']?></a>
				</div>
			<?php endif; ?>
*/
?>
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
				<div class="product-cat-info-container mb-2 small text-extra d-none d-sm-block">
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
							if ($arParams['USE_PRODUCT_QUANTITY'])
							{
								?>
								<div class="product-cat-info-container product-cat-hidden" data-entity="quantity-block">
									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/quantity.php')); ?>
								</div>
								<?php
							}
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
									<?php
									foreach ($arParams['SKU_PROPS'] as $skuProperty)
									{
										$propertyId = $skuProperty['ID'];
										$skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
										if (!isset($item['SKU_TREE_VALUES'][$propertyId]))
											continue;
										?>
										<div class="product-cat-info-container product-cat-scu-container product-cat-hidden" data-entity="sku-block">
											<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/card/sku.php')); ?>
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
								<div class="product-cat-info-container product-cat-hidden mb-5" data-entity="props-preview">
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

	</div>

				</div>
				<div class="col-12 col-sm-3">

	<div class="product-cat-price-container" data-entity="price-block">
		<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php')); ?>
	</div>

				</div>
				<div class="col-auto d-none d-sm-block">

	<div class="product-cat-buttons d-none d-sm-block" data-entity="buttons-block">
		<div class="product-cat-button-container">
			<div id="<?=$itemIds['BASKET_ACTIONS']?>">
				<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/table/actions.php')); ?>
			</div>
		</div>
	</div>

				</div>
			</div>
		</div>
	</div>
</article>
<?php
