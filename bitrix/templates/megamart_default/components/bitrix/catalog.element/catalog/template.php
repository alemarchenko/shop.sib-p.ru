<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Application;
use \Bitrix\Iblock;
use \Redsign\MegaMart\MyTemplate;

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

$this->setFrameMode(true);

$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$this->addExternalCss(SITE_TEMPLATE_PATH.'/assets/styles/catalog-item.css');

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'ITEM' => array(
		'ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
		'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
		'JS_OFFERS' => $arResult['JS_OFFERS']
	)
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'ID' => $mainId,
	'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
	'STICKER_ID' => $mainId.'_sticker',
	'BIG_SLIDER_ID' => $mainId.'_big_slider',
	'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
	'SLIDER_CONT_ID' => $mainId.'_slider_cont',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'QUANTITY_MENU' => $mainId.'_quiantity_menu',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $mainId.'_skudiv',
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
	'OFFER_GROUP' => $mainId.'_set_group_',
	'BASKET_PROP_DIV' => $mainId.'_basket_prop',
	'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	'TABS_ID' => $mainId.'_tabs',
	'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
	'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
	'TABS_PANEL_ID' => $mainId.'_tabs_panel',
	'PRODUCT_DEAL' => $areaId.'_deal',
	'FAVORITE_LINK' => $areaId.'_favorite',
	'BUY1CLICK_LINK' => $mainId.'_buy1click',
	// 'CHEAPER_LINK' => $mainId.'_cheaper',
	'ACTION_REQUEST_LINK' => $mainId.'_request',
	'BIG_SLIDER_DOTS_ID' => $mainId.'_big_slider_dots',
	'PRODUCT_NAV' => $mainId.'_nav',
	'SALE_BANNER' => $mainId.'_sale_banner',
	'PRICES_CONTAINER' => $mainId.'_prices',
	'ELEMENT_PHOTO' => $mainId.'_images',
	'ELEMENT_PROPS' => $mainId.'_props',
	'ELEMENT_DETAIL_TEXT' => $mainId.'_detail',
	'PRICE_BONUS' => $mainId.'_cashback',
	'SIZE_TABLE_LINK' => $mainId.'_size_table_link',
	'SIZE_TABLE' => $mainId.'_size_table',
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
	: $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
	: $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
	: $arResult['NAME'];

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers)
{
	$actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
		? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
		: reset($arResult['OFFERS']);
}
else
{
	$actualItem = $arResult;
}

$skuProps = array();

if (
	(!isset($arResult['CATALOGS'][$arResult['IBLOCK_ID']]) || empty($arResult['CATALOGS'][$arResult['IBLOCK_ID']]))
	&& $arResult['MODULES']['redsign.megamart'])
{
	$actualItem['ITEM_MEASURE']['TITLE'] = $arResult['ITEM_MEASURE']['TITLE'] = Loc::getMessage('RS_MM_BCE_CATALOG_ITEM_MEASURE');
	$actualItem['ITEM_MEASURE_RATIO_SELECTED'] = 0;
	$actualItem['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'] = 1;

	$price = $arResult['RS_PRICES'];

	$arResult['CAN_BUY'] = $actualItem['CAN_BUY'] = is_array($price);

	if ($actualItem['CAN_BUY'])
	{
		$measureRatio = $price['MIN_QUANTITY'] = 1;

		$arResult['ITEM_PRICES'][0] = $price;
		$arResult['ITEM_PRICE_SELECTED'] = 0;
	}

	$templateData['CURRENCIES'] = '';
	$templateData['TEMPLATE_LIBRARY'] = array_diff(
		$templateData['TEMPLATE_LIBRARY'],
		array('currency')
	);
}
else
{
	$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
	$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];

	// $itemIds['OLD_PRICE_ID'] .= '_'.$price['PRICE_TYPE_ID'];
	// $itemIds['PRICE_ID'] .= '_'.$price['PRICE_TYPE_ID'];
	// $itemIds['DISCOUNT_PRICE_ID'] .= '_'.$price['PRICE_TYPE_ID'];
}

$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-outline-secondary';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-outline-secondary';
$showRequestBtn = in_array('REQUEST', $arParams['ADD_TO_BASKET_ACTION']);
$requestButtonClassName = in_array('REQUEST', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-outline-secondary';
$showBuy1ClickBtn = in_array('BUY1CLICK', $arParams['ADD_TO_BASKET_ACTION']);
$buy1ClickButtonClassName = in_array('BUY1CLICK', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-outline-secondary';

$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['PRODUCT']['SUBSCRIBE'] === 'Y' || $haveOffers);


$arDisplayProperties = [];
if (is_array($arResult['DISPLAY_PROPERTIES']) && count($arResult['DISPLAY_PROPERTIES']) > 0)
{
	$arDisplayProperties = array_diff_key(
		$arResult['DISPLAY_PROPERTIES'],
		is_array($arParams['TAB_PROPERTIES']) ? array_fill_keys($arParams['TAB_PROPERTIES'], 0) : [],
		is_array($arParams['BLOCK_LINES_PROPERTIES']) ? array_fill_keys($arParams['BLOCK_LINES_PROPERTIES'], 0) : [],
		[
			'HTML_VIDEO' => true,
		]
	);
}

$showDisplayProperties = count($arDisplayProperties) > 0;
$showTabs = false;

$showMultiPrice = is_array($arResult['CAT_PRICES']) && count($arResult['CAT_PRICES']) > 1;

$showArtnumber = isset($arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]])
	&& $arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] != ''
	||
	isset($actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]])
	&& $actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['VALUE'] != '';

if (is_array($actualItem['DETAIL_PICTURE'])) {
	$templateData['PRODUCT_PHOTO'] = $actualItem['DETAIL_PICTURE'];
} elseif (is_array($actualItem['MORE_PHOTO'])) {
	$templateData['PRODUCT_PHOTO'] = reset($actualItem['MORE_PHOTO']);
}

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_BTN_FAVORITE'] = $arParams['MESS_BTN_FAVORITE'] ?: Loc::getMessage('RS_MM_BCE_CATALOG_FAVORITE_ADD');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');
$arParams['MESS_ECONOMY_INFO2'] = Loc::getMessage('RS_MM_BCE_CATALOG_ECONOMY_INFO2');
$arParams['MESS_ITEM_ARTNUMBER'] = Loc::getMessage('RS_MM_BCE_CATALOG_ITEM_ARTNUMBER_MESSAGE');

$arParams['MESS_BTN_BUY'] = '<svg class="icon-cart icon-svg mr-3"><use xlink:href="#svg-cart"></use></svg>'.$arParams['MESS_BTN_BUY'];
$arParams['MESS_BTN_ADD_TO_BASKET'] = '<svg class="icon-cart icon-svg mr-3"><use xlink:href="#svg-cart"></use></svg>'.$arParams['MESS_BTN_ADD_TO_BASKET'];
$arParams['MESS_BTN_INCART'] = '<svg class="icon-cart icon-svg mr-3"><use xlink:href="#svg-check"></use></svg>'.Loc::getMessage('RS_MM_BCE_CATALOG_MESS_BTN_INCART');
/*
$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}
*/
?>
<article class="bx-catalog-element" id="<?=$itemIds['ID']?>" itemscope itemtype="http://schema.org/Product">
	<?php $this->SetViewTarget('catalog-element-sale-banner'); ?>
	<div class="sale-banner mb-5" id="<?=$itemIds['SALE_BANNER']?>" data-entity="sale-banner" style="display:none">
		<div class="sale-banner-bg-1"></div>
		<div class="sale-banner-bg-2"></div>
		<div class="sale-banner-bg-3"></div>
		<div class="sale-banner-bg-4"></div>
		<div class="sale-banner-head">
			<svg class="sale-banner-icon icon-svg"><use xlink:href="#svg-alarm"></use></svg>
			<div>
				<div class="sale-banner-title" data-entity="sale-banner-title"></div>
				<div><?=Loc::getMessage('RS_MM_BCE_CATALOG_SALE_END')?></div>
			</div>
		</div>
		<div class="sale-banner-timer" data-timer="test">
			<div class="sale-banner-timer-item" data-entity="timer-days">
				<div class="sale-banner-timer-item-cell" data-entity="timer-value">00</div>
				<div><?=Loc::getMessage('RS_MM_BCE_CATALOG_TIMER_DAY')?></div>
			</div>
			<div class="sale-banner-timer-item" data-entity="timer-hours">
				<div class="sale-banner-timer-item-cell" data-entity="timer-value">00</div>
				<div><?=Loc::getMessage('RS_MM_BCE_CATALOG_TIMER_HOUR')?></div>
			</div>
			<div class="sale-banner-timer-item" data-entity="timer-minutes">
				<div class="sale-banner-timer-item-cell" data-entity="timer-value">00</div>
				<div><?=Loc::getMessage('RS_MM_BCE_CATALOG_TIMER_MIN')?></div>
			</div>
			<div class="sale-banner-timer-item" data-entity="timer-seconds">
				<div class="sale-banner-timer-item-cell" data-entity="timer-value">00</div>
				<div><?=Loc::getMessage('RS_MM_BCE_CATALOG_TIMER_SEC')?></div>
			</div>
			<div class="sale-banner-timer-item" data-entity="timer-quantity">
				<div class="sale-banner-timer-item-cell" data-entity="timer-quantity-value">00</div>
				<div><?=Loc::getMessage('RS_MM_BCE_CATALOG_TIMER_MEASURE')?></div>
			</div>
		</div>
	</div>
	<?php $this->EndViewTarget(); ?>

	<?php
	$layout = new \Redsign\MegaMart\Layouts\Section();

	$layout
		->addModifier('bg-white')
		->addModifier('shadow')
		->addModifier('outer-spacing')
		->addModifier('inner-spacing')
		->addData('SECTION_ATTRIBUTES', ' id="'.$mainId.'_main" data-spy="item" data-target="#l-main__nav" data-title="'.Loc::getMessage('RS_MM_BCE_CATALOG_PRODUCT_ABOUT').'"');

	$layout->start();
	?>
	<div class="product-detail">
		<div class="row">

			<div class="col-12 d-lg-none">
				<?
				if ($arParams['DISPLAY_NAME'] === 'Y')
				{
					?>
					<div class="l-main__title mb-5 h4 font-weight-light"><?=$name?></div>
					<?
				}
				?>
			</div>
			<div class="col-xs-12 col-lg-<?=$arResult['IS_WIDE_SLIDER'] ? "7" : "5"; ?>">
				<div class="product-detail-slider-container <?=$arResult['IS_WIDE_SLIDER'] ? "product-detail-slider-container--wide" : ""; ?>""
					id="<?=$itemIds['BIG_SLIDER_ID']?>">
					<div class="<?=$arResult['IS_WIDE_SLIDER'] ? "product-detail-slider-wrap" : ""; ?>">
						<div class="product-detail-slider-block
							<?/*<?=($arParams['IMAGE_RESOLUTION'] === '1by1' ? 'product-detail-slider-block-square' : '')?>*/?>"
							data-entity="images-slider-block">
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/picture/image.php')); ?>
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/picture/labels.php')); ?>
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/picture/deals.php')); ?>
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/picture/gift.php')); ?>
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/picture/actions.php')); ?>
						</div>
					</div>
					<div class="product-detail-slider-dots <?=$arResult['IS_WIDE_SLIDER'] ? "product-detail-slider-dots--wide" : "";?> owl-carousel owl-loaded">
						<div class="owl-dots" id="<?=$itemIds['BIG_SLIDER_DOTS_ID']?>" data-slider-dots="<?=$itemIds['BIG_SLIDER_ID']?>"></div>
					</div>
					<?php
					if (
						$arParams['USE_SHARE'] == 'Y'
						&& is_array($arParams['SOCIAL_SERVICES'])&& count($arParams['SOCIAL_SERVICES']) > 0
					) {
						$this->SetViewTarget('catalog-element-share');
						include(MyTemplate::getTemplatePart($templateFolder.'/include/share.php'));
						$this->EndViewTarget();
						?>
						<div class="d-none d-lg-block text-center mb-4" data-entity="share">
							<?php echo $APPLICATION->GetViewContent('catalog-element-share'); ?>
						</div>
						<?php

					}
					?>
				</div>
			</div>

			<div class="col-xs-12 col-lg-<?= ($arResult['IS_WIDE_SLIDER']) ? "5" : "7";?>">
				<?
				if ($arParams['DISPLAY_NAME'] === 'Y')
				{
					?>
					<h1 class="l-main__title mb-5 display-4 d-none d-lg-block"><?=$name?></h1>
					<?
				}

				foreach ($arParams['PRODUCT_INFO_BLOCK_ORDER'] as $blockName)
				{
					switch ($blockName)
					{
						case 'props':
							?>
							<div class="mb-6">
								<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/info/props.php')); ?>
							</div>
							<?php
							break;

						case 'price':
							?>
							<div class="d-flex flex-column mb-6">
							<a class="print-link align-self-end" style="font-size: 14px;" href='javascript:window.print()'><?=Loc::getMessage('RS_MM_PRINT_PAGE')?></a>
								<?php
								/*
								if ($arParams['FILL_ITEM_ALL_PRICES'])
								{
									$basePrice = $price;
									$baseShowDiscount = $showDiscount;

									foreach ($actualItem['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]['PRICES'] as $price)
									{
										?>
										<div class="product-detail-price" data-entity="price" data-price-id="<?=$price['PRICE_TYPE_ID']?>">
											<?php
											include(MyTemplate::getTemplatePart($templateFolder.'/include/info/price.php'));
											include(MyTemplate::getTemplatePart($templateFolder.'/include/info/price-bonus.php'));
											?>
										</div>
										<?php
									}

									$price = $basePrice;
									$showDiscount = $baseShowDiscount;
								}
								else
								{
									?>
									<div class="product-detail-price" data-entity="price" data-price-id="<?=$price['PRICE_TYPE_ID']?>">
										<?php
										include(MyTemplate::getTemplatePart($templateFolder.'/include/info/price.php'));
										include(MyTemplate::getTemplatePart($templateFolder.'/include/info/price-bonus.php'));
										?>
									</div>
									<?php
								}
								*/
								?>
								<div class="product-detail-price" data-entity="price" data-price-id="<?=$price['PRICE_TYPE_ID']?>">
									<?php
									include(MyTemplate::getTemplatePart($templateFolder.'/include/info/price.php'));
									include(MyTemplate::getTemplatePart($templateFolder.'/include/info/price-bonus.php'));
									?>
								</div>
								<?php

								if ($showMultiPrice)
								{
									$sPriceStyle = count($actualItem['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]['PRICES']) > 1
										|| count($actualItem['ITEM_QUANTITY_RANGES']) > 1
										? ''
										: 'style="display:none"';

									?>
									<div class="pt-2" id="<?=$itemIds['PRICES_CONTAINER']?>"<?=$sPriceStyle?>>
										<div class="mb-3">
											<a class="font-size-sm collapsed" data-toggle="collapse" href="#prices" aria-expanded="false" aria-controls="prices">
												<span class="collapsed__in"><?=Loc::getMessage('RS_MM_BCE_CATALOG_ITEM_ALL_PRICES')?></span>
												<span class="collapsed__out"><?=Loc::getMessage('RS_MM_BCE_CATALOG_ITEM_ALL_PRICES_COLLAPSED')?></span>
												<svg class="collapsed__icon icon-svg"><use xlink:href="#svg-chevron-up"></use></svg>
											</a>
										</div>
										<div class="collapse" id="prices">
											<div class="mb-1">
												<?php
												$basePrice = $price;
												$baseShowDiscount = $showDiscount;

												foreach ($arResult['CAT_PRICES'] as $arCatPrice)
												{
													$price = $actualItem['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]['PRICES'][$arCatPrice['ID']];

													include(MyTemplate::getTemplatePart($templateFolder.'/include/info/price-ranges.php'));
												}

												$price = $basePrice;
												$showDiscount = $baseShowDiscount;
												unset($basePrice, $baseShowDiscount);
												?>
											</div>
										</div>
									</div>
									<?php
								}
								elseif ($arParams['USE_PRICE_COUNT'] && count($actualItem['ITEM_QUANTITY_RANGES']) > 1)
								{
									?>
									<div class="pt-2" id="<?=$itemIds['PRICES_CONTAINER']?>">
										<div class="mb-3">
											<a class="font-size-sm collapsed" data-toggle="collapse" href="#prices" aria-expanded="false" aria-controls="prices">
												<span class="collapsed__in"><?=Loc::getMessage('RS_MM_BCE_CATALOG_ITEM_ALL_PRICES')?></span>
												<span class="collapsed__out"><?=Loc::getMessage('RS_MM_BCE_CATALOG_ITEM_ALL_PRICES_COLLAPSED')?></span>
												<svg class="collapsed__icon icon-svg"><use xlink:href="#svg-chevron-up"></use></svg>
											</a>
										</div>
										<div class="collapse" id="prices">
											<div class="mb-1">
												<?php
												$priceCode = array_search($price['PRICE_TYPE_ID'], array_column($arResult['CAT_PRICES'], 'ID', 'CODE'));

												$arCatPrice = $arResult['CAT_PRICES'][$priceCode];

												include(MyTemplate::getTemplatePart($templateFolder.'/include/info/price-ranges.php'));
												?>
											</div>
										</div>
									</div>
									<?php
								}
								?>
							</div>
							<?
							break;

						case 'buttons':
							?>
							<div class="mb-6">
								<div class="product-cat-button-container clearfix" data-entity="main-button-container">
									<div id="<?=$itemIds['BASKET_ACTIONS_ID']?>" style="<?=(($actualItem['CAN_BUY'] || $arParams['BUY_ON_CAN_BUY'] == 'Y') ? '' : 'display: none;')?>" class="float-left">
										<div>
											<div class="d-inline-block align-middle mr-sm-3">
												<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/info/actions.php')); ?>
											</div>
											<?php
											if ($arParams['USE_PRODUCT_QUANTITY'])
											{
												include(MyTemplate::getTemplatePart($templateFolder.'/include/info/quantity.php'));
											}
											?>
										</div>
										<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/info/buy1click.php')); ?>
									</div>
									<div class="product-cat-amount-description-container" id="<?=$itemIds['PRICE_TOTAL']?>"></div>

									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/info/subscribe.php')); ?>
								</div>
							</div>
							<?
							break;

						case 'preview':
							?>
							<div class="mb-6">
								<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/info/preview.php')); ?>
							</div>
							<?php
							break;

						case 'sku':
							if ($haveOffers && !empty($arResult['OFFERS_PROP']))
							{
								$iPropsSizeTablesCount = 0;
								if ($arParams['SHOW_SIZE_TABLE'] == 'Y')
								{
									$iPropsSizeTablesCount = count(
										array_intersect_key(
											array_flip($arParams['OFFER_TREE_SIZE_PROPS']),
											$arResult['OFFERS_PROP']
										)
									);
								}
								?>
								<div class="d-inline-block mb-6" id="<?=$itemIds['TREE_ID']?>">
									<?
									foreach ($arResult['SKU_PROPS'] as $skuProperty)
									{
										if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
											continue;

										$propertyId = $skuProperty['ID'];
										$skuProps[] = array(
											'ID' => $propertyId,
											'SHOW_MODE' => $skuProperty['SHOW_MODE'],
											'VALUES' => $skuProperty['VALUES'],
											'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
										);
										?>
										<div data-entity="sku-line-block">
											<div class="mb-2 small text-extra">
												<?=htmlspecialcharsEx($skuProperty['NAME'])?>: <span class="text-body" data-entity="sku-current-value"></span>
												<?php
												if ($arParams['SHOW_SIZE_TABLE'] == 'Y' && $iPropsSizeTablesCount == 1 && !empty($arResult['SIZE_TABLE']))
												{
													if (in_array($skuProperty['CODE'], $arParams['OFFER_TREE_SIZE_PROPS']))
													{
														?>
														<a class="product-detail-size-table-link ml-5" href="#<?=$itemIds['SIZE_TABLE']?>" data-fancybox="size-table" data-type="inline" data-popup-type="window" id="<?=$itemIds['SIZE_TABLE_LINK']?>" data-title="<?=$arResult['SIZE_TABLE']['NAME']?>">
															<svg class="icon-svg product-detail-size-table-icon mr-2 text-extra align-bottom"><use xlink:href="#svg-ruler"></use></svg><span><?=$arResult['SIZE_TABLE']['NAME'] ?: Loc::getMessage('RS_MM_BCE_CATALOG_SIZE_TABLE')?></span>
														</a>
														<?php
													}
												}
												?>
											</div>
											<div class="product-cat-scu-block">
												<div class="product-cat-scu-list">
												<?php
												if (in_array($skuProperty['CODE'], $arParams['OFFER_TREE_DROPDOWN_PROPS']))
												{
													include(MyTemplate::getTemplatePart($templateFolder.'/include/info/sku-dropdown.php'));
												}
												else
												{
													?>
													<ul class="product-cat-scu-item-list">
														<?
														foreach ($skuProperty['VALUES'] as $value)
														{
															$value['NAME'] = htmlspecialcharsbx($value['NAME']);

															if ($skuProperty['SHOW_MODE'] === 'PICT')
															{
																include(MyTemplate::getTemplatePart($templateFolder.'/include/info/sku-picture.php'));
															}
															else
															{
																include(MyTemplate::getTemplatePart($templateFolder.'/include/info/sku-button.php'));
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
										<?
									}

									include(MyTemplate::getTemplatePart($templateFolder.'/include/info/size-table.php'));
									?>
								</div>
								<?
							}
							break;

						case 'id-rate-stock-brand':
							if (
								isset($actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['VALUE'])
								|| !empty($arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE'])
								|| $arParams['USE_VOTE_RATING'] === 'Y'
								|| $arParams['SHOW_MAX_QUANTITY'] !== 'N'
								|| isset($arResult['BRANDS'][$sBrandCode])
								)
							{
								?>
								<div class="product-detail-info-container font-size-sm text-extra mb-6">
									<div class="row justify-content-between align-items-center">
										<div class="col-12 col-md-auto">
											<?php
											include(MyTemplate::getTemplatePart($templateFolder.'/include/info/id.php'));
											include(MyTemplate::getTemplatePart($templateFolder.'/include/info/rate.php'));

											if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
											{
												include(MyTemplate::getTemplatePart($templateFolder.'/include/info/limit.php'));
											}
											?>
										</div>
										<div class="col-12 col-md-auto">
											<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/info/brand.php')); ?>
										</div>
									</div>
								</div>
								<?php
							}
							break;

						case 'deals':
							if ($arParams['PRODUCT_DEALS_SHOW'] == 'Y')
							{
								?>
								<div class="mb-6">
									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/info/deals.php')); ?>
								</div>
								<?php
							}
							break;

						case 'delivery':
							if ($arParams['DETAIL_DELIVERY_PAYMENT_INFO'] === 'Y')
							{
								?>
								<div class="mb-6">
									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/info/delivery.php')); ?>
								</div>
								<?php
							}
							break;

						case 'brandblock':
							if ($arParams['BRAND_USE'] === 'Y')
							{
								?>
								<div class="mb-6">
									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/info/brandblock.php')); ?>
								</div>
								<?php
							}
							break;
					}
				}
				?>
				<?php
				if ($APPLICATION->GetViewContent('catalog-element-share') != '')
				{
					?>
					<div class="d-lg-none text-center" data-entity="share">
						<?=$APPLICATION->GetViewContent('catalog-element-share')?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php $layout->end(); ?>

	<?php
	$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderCustom();
	$layoutHeader->defineShowFn(function () use ($arResult, $arParams, $showDisplayProperties, $itemIds, $haveOffers) {
		?>
		<div class="nav-wrap">
			<ul class="nav nav-slide" role="tablist">
				<?php
				$bActiveTab = false;
				foreach ($arParams['TABS_ORDER'] as $blockName)
				{
					//fix arParams saving
					if (!in_array($blockName, $arResult['TABS']))
					{
						continue;
					}

					switch ($blockName)
					{
						case 'detail':

							if ($arResult['DETAIL_TEXT'] != '')
							{
								?>
								 <li class="nav-item">
									<a class="nav-link<?php if (!$bActiveTab): ?> active<?php endif; ?>" rel="nofollow" href="#tab_<?=$itemIds['ELEMENT_DETAIL_TEXT']?>" id="<?=$itemIds['ELEMENT_DETAIL_TEXT']?>" data-toggle="tab">
										<span><?=$arParams['MESS_DESCRIPTION_TAB']?></span>
									</a>
								</li>
								<?php
								$bActiveTab = true;
							}
							break;

						case 'props':
							if ($showDisplayProperties || $arResult['SHOW_OFFERS_PROPS'])
							{
								?>
								 <li class="nav-item">
									<a class="nav-link<?php if (!$bActiveTab): ?> active<?php endif; ?>" rel="nofollow" href="#tab_<?=$itemIds['ELEMENT_PROPS']?>" id="<?=$itemIds['ELEMENT_PROPS']?>" data-toggle="tab">
										<span><?=$arParams['MESS_PROPERTIES_TAB']?></span>
									</a>
								</li>
								<?php
								$bActiveTab = true;
							}
							break;

						case 'comments':
							if (in_array($blockName, $arResult['BLOCK_LINES']))
							{
								continue 2;
							}

							if ($arParams['USE_COMMENTS'] === 'Y')
							{
								$tabsId = 'soc_comments_'.$arResult['ID'];
								if ($arParams['BLOG_USE'] == 'Y' && Loader::includeModule('blog')) {
									?>
									 <li class="nav-item">
										<a class="nav-link<?php if (!$bActiveTab): ?> active<?php endif; ?>" rel="nofollow" href="#<?=$tabsId?>BLOG_cont" data-toggle="tab">
											<span><?=$arParams['MESS_COMMENTS_TAB']?></span>
										</a>
									</li>
									<?php
									$bActiveTab = true;
								}
								if ($arParams['FB_USE'] == 'Y') {
									?>
									 <li class="nav-item">
										<a class="nav-link<?php if (!$bActiveTab): ?> active<?php endif; ?>" rel="nofollow" href="#<?=$tabsId?>FB_cont" data-toggle="tab">
											<span><?=isset($arParams["FB_TITLE"]) && trim($arParams["FB_TITLE"]) != "" ? $arParams["FB_TITLE"] : "Facebook"?></span>
										</a>
									</li>
									<?php
									$bActiveTab = true;
								}
								if ($arParams['VK_USE'] == 'Y') {
									?>
									 <li class="nav-item">
										<a class="nav-link<?php if (!$bActiveTab): ?> active<?php endif; ?>" rel="nofollow" href="#<?=$tabsId?>VK_cont" data-toggle="tab">
											<span><?=isset($arParams["VK_TITLE"]) && trim($arParams["VK_TITLE"]) != "" ? $arParams["VK_TITLE"] : Loc::getMessage("IBLOCK_CSC_TAB_VK")?></span>
										</a>
									</li>
									<?php
									$bActiveTab = true;
								}
							}
							break;

						case 'stock':
							if ($arParams['USE_STORE'] == 'Y' && $arResult['MODULES']['catalog'])
							{
								?>
								 <li class="nav-item">
									<a class="nav-link<?php if (!$bActiveTab): ?> active<?php endif; ?>" rel="nofollow" href="#<?=$blockName?>" id="tab_<?=$blockName?>" data-toggle="tab">
										<span><?=$arParams['STOCK_MAIN_TITLE']?></span>
									</a>
								</li>
								<?php
								$bActiveTab = true;
							}
							break;

						case 'set':
							if (in_array($blockName, $arResult['BLOCK_LINES']))
							{
								continue 2;
							}

							if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP'])
							{
								?>
								 <li class="nav-item">
									<a class="nav-link<?php if (!$bActiveTab): ?> active<?php endif; ?>" rel="nofollow" href="#<?=$blockName?>" id="tab_<?=$blockName?>" data-toggle="tab">
										<span><?=Loc::getMessage('RS_MM_BCE_CATALOG_SET')?></span>
									</a>
								</li>
								<?php
								$bActiveTab = true;
							}
							break;

						case 'mods':
							if ($haveOffers)
							{
								$blockName = 'modifications';
								?>
								<li class="nav-item">
									<a class="nav-link<?php if (!$bActiveTab): ?> active<?php endif; ?>" rel="nofollow" href="#<?=$blockName?>" id="tab_<?=$blockName?>" data-toggle="tab">
										<span><?=Loc::getMessage('RS_MM_BLOCK_MODIFICATIONS_TITLE')?></span>
									</a>
								</li>
								<?php
								$bActiveTab = true;
							}
							break;

						default:
							if (mb_substr($blockName, 0, 5) == 'prop_')
							{
								$sPropCode = mb_substr($blockName, 5);
								if (
									(
										$arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE
										|| $arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT
										|| $arResult['PROPERTIES'][$sPropCode]['MULTIPLE'] == 'Y'
									) && !empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])
									||
									(
										$arResult['PROPERTIES'][$sPropCode]['VALUE']['TYPE'] != 'HTML' && !empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])
										|| $arResult['PROPERTIES'][$sPropCode]['VALUE']['TYPE'] == 'HTML' && isset($arResult['DISPLAY_PROPERTIES'][$sPropCode])
									)
								)
								{
									?>
									 <li class="nav-item">
										<a class="nav-link<?php if (!$bActiveTab): ?> active<?php endif; ?>" rel="nofollow" href="#<?=$blockName?>" data-toggle="tab">
											<span><?=$arResult['PROPERTIES'][$sPropCode]['NAME']?></span>
										</a>
									</li>
									<?php
									$bActiveTab = true;
								}
							}
							break;
					}
				}
				?>
			</ul>
		</div>
		<?php
	});

	$this->SetViewTarget($itemIds['TAB_CONTAINERS_ID']);
	?>
	<div class="tab-content" id="<?=$itemIds['TAB_CONTAINERS_ID']?>">
		<?php
		$bActiveTab = false;
		foreach ($arParams['TABS_ORDER'] as $blockName)
		{
			//fix arParams saving
			if (!in_array($blockName, $arResult['TABS']))
			{
				continue;
			}

			switch ($blockName)
			{
				case 'detail':
					if ($arResult['DETAIL_TEXT'] != '')
					{
						?>
							<div class="bg-light block-spacing tab-pane<?php if (!$bActiveTab): ?> show active<?php endif; ?>"
								id="tab_<?=$itemIds['ELEMENT_DETAIL_TEXT']?>"
								data-value="description"
								itemprop="description">
								<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/detail.php')); ?>
							</div>
						<?php
						$bActiveTab = true;
					}
					break;

				case 'props':
					if ($showDisplayProperties || $arResult['SHOW_OFFERS_PROPS'])
					{
						?>
						<div class="bg-light block-spacing tab-pane<?php if (!$bActiveTab): ?> show active<?php endif; ?>"
							id="tab_<?=$itemIds['ELEMENT_PROPS']?>"
							data-value="properties"
						>
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/props/display.php')); ?>
						</div>
						<?php
						$bActiveTab = true;
					}
					break;

				case 'comments':

					if (in_array($blockName, $arResult['BLOCK_LINES']))
					{
						continue 2;
					}

					if ($arParams['USE_COMMENTS'] === 'Y')
					{
						$componentCommentsParams = array(
							'ELEMENT_ID' => $arResult['ID'],
							'ELEMENT_CODE' => '',
							'IBLOCK_ID' => $arParams['IBLOCK_ID'],
							'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
							'URL_TO_COMMENT' => '',
							'WIDTH' => '',
							'COMMENTS_COUNT' => '5',
							'BLOG_USE' => $arParams['BLOG_USE'],
							'FB_USE' => $arParams['FB_USE'],
							'FB_APP_ID' => $arParams['FB_APP_ID'],
							'VK_USE' => $arParams['VK_USE'],
							'VK_API_ID' => $arParams['VK_API_ID'],
							'CACHE_TYPE' => $arParams['CACHE_TYPE'],
							'CACHE_TIME' => $arParams['CACHE_TIME'],
							'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
							'BLOG_TITLE' => '',
							'BLOG_URL' => $arParams['BLOG_URL'],
							'PATH_TO_SMILE' => '',
							'EMAIL_NOTIFY' => $arParams['BLOG_EMAIL_NOTIFY'],
							'AJAX_POST' => 'Y',
							'SHOW_SPAM' => 'Y',
							'SHOW_RATING' => 'N',
							'FB_TITLE' => '',
							'FB_USER_ADMIN_ID' => '',
							'FB_COLORSCHEME' => 'light',
							'FB_ORDER_BY' => 'reverse_time',
							'VK_TITLE' => '',
							'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
							'EXTERNAL_TABS' => 'Y',
							'EXTERNAL_TABS_ACTIVE' => $bActiveTab ? 'Y' : 'N',
							'EXTERNAL_TABS_ID' => $itemIds['TABS_ID'],
						);
						if(isset($arParams["USER_CONSENT"]))
							$componentCommentsParams["USER_CONSENT"] = $arParams["USER_CONSENT"];
						if(isset($arParams["USER_CONSENT_ID"]))
							$componentCommentsParams["USER_CONSENT_ID"] = $arParams["USER_CONSENT_ID"];
						if(isset($arParams["USER_CONSENT_IS_CHECKED"]))
							$componentCommentsParams["USER_CONSENT_IS_CHECKED"] = $arParams["USER_CONSENT_IS_CHECKED"];
						if(isset($arParams["USER_CONSENT_IS_LOADED"]))
							$componentCommentsParams["USER_CONSENT_IS_LOADED"] = $arParams["USER_CONSENT_IS_LOADED"];

						include(MyTemplate::getTemplatePart($templateFolder.'/include/comments/tabs.php'));
						$bActiveTab = true;
					}
					break;

				case 'stock':
					if ($arParams['USE_STORE'] == 'Y' && $arResult['MODULES']['catalog'])
					{
						?>
						<div class="bg-light block-spacing tab-pane<?php if (!$bActiveTab): ?> show active<?php endif; ?>"
							id="<?=$blockName?>"
						>
							<?php
							include(MyTemplate::getTemplatePart($templateFolder.'/include/stock.php'));
							?>
						</div>
						<?php
						$bActiveTab = true;
					}
					break;

				case 'set':

					if (in_array($blockName, $arResult['BLOCK_LINES']))
					{
						continue 2;
					}

					if ($haveOffers)
					{
						if ($arResult['OFFER_GROUP'])
						{
							?>
							<div class="bg-light block-spacing tab-pane<?php if (!$bActiveTab): ?> show active<?php endif; ?>"
								id="<?=$blockName?>"
							>
								<?php
								foreach ($arResult['OFFER_GROUP_VALUES'] as $offerId)
								{
									?>
									<div id="<?=$itemIds['OFFER_GROUP'].$offerId?>" style="display: none;">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.set.constructor',
											'catalog',
											array(
												'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
												'IBLOCK_ID' => $arResult['OFFERS_IBLOCK'],
												'ELEMENT_ID' => $offerId,
												'PRICE_CODE' => $arParams['PRICE_CODE'],
												'BASKET_URL' => $arParams['BASKET_URL'],
												'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
												'CACHE_TYPE' => $arParams['CACHE_TYPE'],
												'CACHE_TIME' => $arParams['CACHE_TIME'],
												'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
												'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
												'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
												'CURRENCY_ID' => $arParams['CURRENCY_ID']
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
							<?php
						}
					}
					else
					{
						if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP'])
						{
							?>
							<div class="bg-light block-spacing tab-pane<?php if (!$bActiveTab): ?> show active<?php endif; ?>"
								id="<?=$blockName?>"
							>
								<?php
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.set.constructor',
									'catalog',
									array(
										'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
										'IBLOCK_ID' => $arParams['IBLOCK_ID'],
										'ELEMENT_ID' => $arResult['ID'],
										'PRICE_CODE' => $arParams['PRICE_CODE'],
										'BASKET_URL' => $arParams['BASKET_URL'],
										'CACHE_TYPE' => $arParams['CACHE_TYPE'],
										'CACHE_TIME' => $arParams['CACHE_TIME'],
										'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
										'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
										'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
										'CURRENCY_ID' => $arParams['CURRENCY_ID']
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);
								?>
							</div>
							<?php
						}
					}
					break;

				case 'mods':
					if ($haveOffers)
					{
						$blockName = 'modifications';
						?>
						<div class="bg-light tab-pane<?php if (!$bActiveTab): ?> show active<?php endif; ?>"
							id="<?=$blockName?>"
							data-value="<?=$blockName?>">
						<?php
						include(MyTemplate::getTemplatePart($templateFolder.'/include/props/mods.php'));
						?>
						</div>
						<?php
						break;
					}

				default:
					if (mb_substr($blockName, 0, 5) == 'prop_')
					{
						$sPropCode = mb_substr($blockName, 5);
						if (
							$arResult['PROPERTIES'][$sPropCode]['VALUE']['TYPE'] != 'HTML' && !empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])
							|| $arResult['PROPERTIES'][$sPropCode]['VALUE']['TYPE'] == 'HTML' && isset($arResult['DISPLAY_PROPERTIES'][$sPropCode])
							)
						{
							?>
							<div class="bg-light tab-pane<?php if (!$bActiveTab): ?> show active<?php endif; ?>"
								id="<?=$blockName?>"
								data-value="<?=$blockName?>">
							<?php
							if ($arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE)
							{
								switch ($arParams['PROPERTY_FILE_VIEW'][$sPropCode])
								{
									case 'LINE':
										include(MyTemplate::getTemplatePart($templateFolder.'/include/props/file/line.php'));
										break;

									case 'IMAGE_COL':
										include(MyTemplate::getTemplatePart($templateFolder.'/include/props/file/image-col.php'));
										break;

									case 'IMAGE_LINE':
										include(MyTemplate::getTemplatePart($templateFolder.'/include/props/file/image-line.php'));
										break;

									case 'COL':
									default:
										include(MyTemplate::getTemplatePart($templateFolder.'/include/props/file/col.php'));
										break;
								}
							}
							elseif (
								$arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT &&
								count($arResult['PROPERTIES'][$sPropCode]['VALUE']) > 0
							)
							{
								$IBLOCK_ID = $arResult['PROPERTIES'][$sPropCode]['IBLOCK_ID'];

								if ($arResult['MODULES']['catalog'])
								{
									if (!isset($arSKU[$IBLOCK_ID]))
									{
										$arSKU[$IBLOCK_ID] = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);
									}
									include(MyTemplate::getTemplatePart($templateFolder.'/include/props/catalog-catalog.php'));
								}
								else
								{
									include(MyTemplate::getTemplatePart($templateFolder.'/include/props/catalog-section.php'));
								}
							}
							elseif ($arResult['PROPERTIES'][$sPropCode]['MULTIPLE'] == 'Y')
							{
								?>
								<div class="block-spacing">
									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/props/list.php')); ?>
								</div>
								<?php
							}
							else
							{
								?>
								<div class="block-spacing">
									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/props/default.php')); ?>
								</div>
								<?php
							}
							?>
							</div>
							<?php
							$bActiveTab = true;
						}
					}
					break;
			}
		}
		unset($blockName);
		?>
	</div>
	<?
	$this->EndViewTarget();

	$showTabs = $bActiveTab;

	if ($showTabs)
	{
		$layout = new \Redsign\MegaMart\Layouts\Section();

		$layout
			->useHeader($layoutHeader)
			->addModifier('bg-white')
			->addModifier('shadow')
			->addModifier('outer-spacing')
			// ->addModifier('inner-spacing')
			->addData('SECTION_ATTRIBUTES', ' id="'.$itemIds['TABS_ID'].'" data-spy="item" data-target="#l-main__nav" data-title="'.Loc::getMessage('RS_MM_BCE_CATALOG_PRODUCT_INFO').'"');

		$layout->start();

		echo $APPLICATION->GetViewContent($itemIds['TAB_CONTAINERS_ID']);

		$layout->end();
	}

	if (is_array($arResult['BLOCK_LINES']) && count($arResult['BLOCK_LINES']) > 0)
	{
		foreach ($arParams['BLOCK_LINES_ORDER'] as $blockName)
		{
			//fix arParams saving
			if (!in_array($blockName, $arResult['BLOCK_LINES']))
			{
				continue;
			}

			switch ($blockName)
			{
				case 'detail':

					if ($arResult['DETAIL_TEXT'] != '')
					{
						$layout = new \Redsign\MegaMart\Layouts\Section();

						$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
						$layoutHeader->addData('TITLE', $arParams['MESS_DESCRIPTION_TAB']);

						$layout
							->useHeader($layoutHeader)
							->addModifier('bg-white')
							->addModifier('shadow')
							->addModifier('outer-spacing')
							->addModifier('inner-spacing')
							->addData('SECTION_ATTRIBUTES', ' id="'.$mainId.'_'.$blockName.'" data-spy="item" data-target="#l-main__nav" data-title="'.$arParams['MESS_DESCRIPTION_TAB'].'"');

						$layout->start();
						?>
						<div data-value="description" itemprop="description">
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/detail.php')); ?>
						</div>
						<?php
						$layout->end();
					}
					break;

				case 'props':

					if ($showDisplayProperties)
					{
						$layout = new \Redsign\MegaMart\Layouts\Section();

						$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
						$layoutHeader->addData('TITLE', $arParams['MESS_PROPERTIES_TAB']);

						$layout
							->useHeader($layoutHeader)
							->addModifier('bg-white')
							->addModifier('shadow')
							->addModifier('outer-spacing')
							->addModifier('inner-spacing')
							->addData('SECTION_ATTRIBUTES', ' id="'.$itemIds['ELEMENT_PROPS'].'" data-spy="item" data-target="#l-main__nav" data-title="'.$arParams['MESS_PROPERTIES_TAB'].'"');

						$layout->start();
						?>
						<div data-value="properties">
							<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/props/display.php')); ?>
						</div>
						<?php
						$layout->end();
					}
					break;

				case 'comments':

					if ($arParams['USE_COMMENTS'] === 'Y')
					{
						?><div id="<?=$mainId.'_'.$blockName?>"><?

						$componentCommentsParams = array(
							'ELEMENT_ID' => $arResult['ID'],
							'ELEMENT_CODE' => '',
							'IBLOCK_ID' => $arParams['IBLOCK_ID'],
							'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
							'URL_TO_COMMENT' => '',
							'WIDTH' => '',
							'COMMENTS_COUNT' => '5',
							'BLOG_USE' => $arParams['BLOG_USE'],
							'FB_USE' => $arParams['FB_USE'],
							'FB_APP_ID' => $arParams['FB_APP_ID'],
							'VK_USE' => $arParams['VK_USE'],
							'VK_API_ID' => $arParams['VK_API_ID'],
							'CACHE_TYPE' => $arParams['CACHE_TYPE'],
							'CACHE_TIME' => $arParams['CACHE_TIME'],
							'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
							'BLOG_TITLE' => '',
							'BLOG_URL' => $arParams['BLOG_URL'],
							'PATH_TO_SMILE' => '',
							'EMAIL_NOTIFY' => $arParams['BLOG_EMAIL_NOTIFY'],
							'AJAX_POST' => 'Y',
							'SHOW_SPAM' => 'Y',
							'SHOW_RATING' => 'N',
							'FB_TITLE' => '',
							'FB_USER_ADMIN_ID' => '',
							'FB_COLORSCHEME' => 'light',
							'FB_ORDER_BY' => 'reverse_time',
							'VK_TITLE' => '',
							'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME']
						);
						if(isset($arParams["USER_CONSENT"]))
							$componentCommentsParams["USER_CONSENT"] = $arParams["USER_CONSENT"];
						if(isset($arParams["USER_CONSENT_ID"]))
							$componentCommentsParams["USER_CONSENT_ID"] = $arParams["USER_CONSENT_ID"];
						if(isset($arParams["USER_CONSENT_IS_CHECKED"]))
							$componentCommentsParams["USER_CONSENT_IS_CHECKED"] = $arParams["USER_CONSENT_IS_CHECKED"];
						if(isset($arParams["USER_CONSENT_IS_LOADED"]))
							$componentCommentsParams["USER_CONSENT_IS_LOADED"] = $arParams["USER_CONSENT_IS_LOADED"];

						include(MyTemplate::getTemplatePart($templateFolder.'/include/comments/sections.php'));
						?></div><?
					}
					break;

				case 'stock':
					if ($arParams['USE_STORE'] == 'Y' && $arResult['MODULES']['catalog'])
					{
						$arParams['STORE_ADD_LAYOUT'] = 'Y';
						include(MyTemplate::getTemplatePart($templateFolder.'/include/stock.php'));
					}
					break;

				case 'set':

					if ($haveOffers)
					{
						if ($arResult['OFFER_GROUP'])
						{
							?><div id="<?=$mainId.'_'.$blockName?>" data-spy="item" data-target="#l-main__nav" data-title="<?=Loc::getMessage('RS_MM_BCE_CATALOG_SET')?>"><?

							foreach ($arResult['OFFER_GROUP_VALUES'] as $offerId)
							{
								$layout = new \Redsign\MegaMart\Layouts\Section();

								$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
								$layoutHeader->addData('TITLE', Loc::getMessage('RS_MM_BCE_CATALOG_SET'));

								$layout
									->useHeader($layoutHeader)
									->addModifier('bg-white')
									->addModifier('shadow')
									->addModifier('outer-spacing')
									->addModifier('inner-spacing')
									->addData('SECTION_ATTRIBUTES', ' id="'.$itemIds['OFFER_GROUP'].$offerId.'" style="display: none;"');


								$layout->start();

								$APPLICATION->IncludeComponent(
									'bitrix:catalog.set.constructor',
									'catalog',
									array(
										'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
										'IBLOCK_ID' => $arResult['OFFERS_IBLOCK'],
										'ELEMENT_ID' => $offerId,
										'PRICE_CODE' => $arParams['PRICE_CODE'],
										'BASKET_URL' => $arParams['BASKET_URL'],
										'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
										'CACHE_TYPE' => $arParams['CACHE_TYPE'],
										'CACHE_TIME' => $arParams['CACHE_TIME'],
										'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
										'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
										'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
										'CURRENCY_ID' => $arParams['CURRENCY_ID']
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);

								$layout->end();
							}
							?></div><?
						}
					}
					else
					{
						if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP'])
						{
							$layout = new \Redsign\MegaMart\Layouts\Section();

							$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
							$layoutHeader->addData('TITLE', Loc::getMessage('RS_MM_BCE_CATALOG_SET'));

							$layout
								->useHeader($layoutHeader)
								->addModifier('bg-white')
								->addModifier('shadow')
								->addModifier('outer-spacing')
								->addData('SECTION_ATTRIBUTES', ' id="'.$mainId.'_'.$blockName.'" data-spy="item" data-target="#l-main__nav" data-title="'.Loc::getMessage('RS_MM_BCE_CATALOG_SET').'"');

							$layout->start();

							$APPLICATION->IncludeComponent(
								'bitrix:catalog.set.constructor',
								'catalog',
								array(
									'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
									'IBLOCK_ID' => $arParams['IBLOCK_ID'],
									'ELEMENT_ID' => $arResult['ID'],
									'PRICE_CODE' => $arParams['PRICE_CODE'],
									'BASKET_URL' => $arParams['BASKET_URL'],
									'CACHE_TYPE' => $arParams['CACHE_TYPE'],
									'CACHE_TIME' => $arParams['CACHE_TIME'],
									'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
									'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
									'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
									'CURRENCY_ID' => $arParams['CURRENCY_ID']
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);

							$layout->end();
						}
					}
					break;

				case 'gift':
					if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
					{
						include(MyTemplate::getTemplatePart($templateFolder.'/include/gift.php'));
					}
					break;

				case 'gift-main':
					if ($arResult['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
					{
						include(MyTemplate::getTemplatePart($templateFolder.'/include/gift-main.php'));
					}
					break;

				case 'mods':
					if ($haveOffers)
					{
						$blockName = 'modifications';

						$layout = new \Redsign\MegaMart\Layouts\Section();

						$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
						$layoutHeader->addData('TITLE', Loc::getMessage('RS_MM_BLOCK_MODIFICATIONS_TITLE'));

						$layout
							->useHeader($layoutHeader)
							->addModifier('bg-white')
							->addModifier('outer-spacing')
							->addModifier('shadow')
							->addData('SECTION_ATTRIBUTES', ' id="'.$mainId.'_'.$blockName.'" data-spy="item" data-target="#l-main__nav" data-title="'.Loc::getMessage('RS_MM_BLOCK_MODIFICATIONS_TITLE').'"');

						$layout->start();

						include(MyTemplate::getTemplatePart($templateFolder.'/include/props/mods.php'));

						$layout->end();
					}

					break;

				default:
					if (mb_substr($blockName, 0, 5) == 'prop_')
					{
						$sPropCode = mb_substr($blockName, 5);
						if (
							(
								$arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE
								|| $arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT
								|| $arResult['PROPERTIES'][$sPropCode]['MULTIPLE'] == 'Y'
							) && !empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])
							||
							(
								$arResult['PROPERTIES'][$sPropCode]['VALUE']['TYPE'] == 'TEXT' && !empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])
								|| $arResult['PROPERTIES'][$sPropCode]['VALUE']['TYPE'] == 'HTML' && !empty($arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])
							)
						)
						{
							$layout = new \Redsign\MegaMart\Layouts\Section();

							$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
							$layoutHeader->addData('TITLE', $arResult['PROPERTIES'][$sPropCode]['NAME']);

							$layout
								->useHeader($layoutHeader)
								->addModifier('bg-white')
								->addModifier('outer-spacing')
								->addModifier('shadow')
								->addData('SECTION_ATTRIBUTES', ' id="'.$mainId.'_'.$blockName.'" data-spy="item" data-target="#l-main__nav" data-title="'.$arResult['PROPERTIES'][$sPropCode]['NAME'].'"');

							$layout->start();

							if ($arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE)
							{
								switch ($arParams['PROPERTY_FILE_VIEW'][$sPropCode])
								{
									case 'LINE':
										include(MyTemplate::getTemplatePart($templateFolder.'/include/props/file/line.php'));
										break;

									case 'IMAGE_COL':
										include(MyTemplate::getTemplatePart($templateFolder.'/include/props/file/image-col.php'));
										break;

									case 'IMAGE_LINE':
										include(MyTemplate::getTemplatePart($templateFolder.'/include/props/file/image-line.php'));
										break;

									case 'COL':
									default:
										include(MyTemplate::getTemplatePart($templateFolder.'/include/props/file/col.php'));
										break;
								}
							}
							elseif (
								$arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT &&
								count($arResult['PROPERTIES'][$sPropCode]['VALUE']) > 0
							)
							{
								$IBLOCK_ID = $arResult['PROPERTIES'][$sPropCode]['IBLOCK_ID'];

								if ($arResult['MODULES']['catalog'])
								{
									if (!isset($arSKU[$IBLOCK_ID]))
									{
										$arSKU[$IBLOCK_ID] = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);
									}
									include(MyTemplate::getTemplatePart($templateFolder.'/include/props/catalog-catalog.php'));
								}
								else
								{
									include(MyTemplate::getTemplatePart($templateFolder.'/include/props/catalog-section.php'));
								}
							}
							elseif ($arResult['PROPERTIES'][$sPropCode]['MULTIPLE'] == 'Y')
							{
								?>
								<div class="block-spacing">
									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/props/list.php')); ?>
								</div>
								<?php
							}
							else
							{
								?>
								<div class="block-spacing">
									<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/props/default.php')); ?>
								</div>
								<?php
							}

							$layout->end();
						}
					}
					break;
			}
		}
		unset($blockName);
	}

	if ($arResult['CATALOG'] && $actualItem['CAN_BUY'] && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
	{
		$APPLICATION->IncludeComponent(
			'bitrix:sale.prediction.product.detail',
			'catalog',
			array(
				'BUTTON_ID' => $showBuyBtn ? $itemIds['BUY_LINK'] : $itemIds['ADD_BASKET_LINK'],
				'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
				'POTENTIAL_PRODUCT_TO_BUY' => array(
					'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
					'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
					'PRODUCT_PROVIDER_CLASS' => isset($arResult['~PRODUCT_PROVIDER_CLASS']) ? $arResult['~PRODUCT_PROVIDER_CLASS'] : '\Bitrix\Catalog\Product\CatalogProvider',
					'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
					'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

					'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
					'SECTION' => array(
						'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
						'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
						'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
						'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
					),
				)
			),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
	}
	?>


	<meta itemprop="name" content="<?=$name?>" />
	<meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />
	<?
	if ($haveOffers)
	{
		foreach ($arResult['JS_OFFERS'] as $offer)
		{
			$currentOffersList = array();

			if (!empty($offer['TREE']) && is_array($offer['TREE']))
			{
				foreach ($offer['TREE'] as $propName => $skuId)
				{
					$propId = (int)mb_substr($propName, 5);

					foreach ($skuProps as $prop)
					{
						if ($prop['ID'] == $propId)
						{
							foreach ($prop['VALUES'] as $propId => $propValue)
							{
								if ($propId == $skuId)
								{
									$currentOffersList[] = $propValue['NAME'];
									break;
								}
							}
						}
					}
				}
			}

			$offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
			?>
			<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="sku" content="<?=htmlspecialcharsbx(implode('/', $currentOffersList))?>" />
				<meta itemprop="price" content="<?=$offerPrice['RATIO_PRICE']?>" />
				<meta itemprop="priceCurrency" content="<?=$offerPrice['CURRENCY']?>" />
				<link itemprop="availability" href="http://schema.org/<?=($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
			</span>
			<?
		}

		unset($offerPrice, $currentOffersList);
	}
	else
	{
		?>
		<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?=$price['RATIO_PRICE']?>" />
			<meta itemprop="priceCurrency" content="<?=$price['CURRENCY']?>" />
			<link itemprop="availability" href="http://schema.org/<?=($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
		</span>
		<?
	}
	?>
</article>
	<?
	if ($haveOffers)
	{
		$offerIds = array();
		$offerCodes = array();

		$useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

		foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer)
		{
			$offerIds[] = (int)$jsOffer['ID'];
			$offerCodes[] = $jsOffer['CODE'];

			$fullOffer = $arResult['OFFERS'][$ind];
			$measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

			$strAllProps = '';
			$strMainProps = '';
			$strPriceRangesRatio = '';
			$arPriceRanges = array();
			$strPriceRanges = '';

			if ($arResult['SHOW_OFFERS_PROPS'])
			{
				if (!empty($jsOffer['DISPLAY_PROPERTIES']))
				{
					foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property)
					{
						$current = '<dt>'.$property['NAME'].':</dt> <dd>'.(
							is_array($property['VALUE'])
								? implode(' / ', $property['VALUE'])
								: $property['VALUE']
							).'</dd>';
						$strAllProps .= $current;

						if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']]))
						{
							$strMainProps .= $current;
						}
					}

					unset($current);
				}
			}

			if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1)
			{
				$strPriceRangesRatio = '('.Loc::getMessage(
						'CT_BCE_CATALOG_RATIO_PRICE',
						array('#RATIO#' => ($useRatio
								? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
								: '1'
							).' '.$measureName)
					).')';

		if ($showMultiPrice)
		{
			foreach ($arResult['CAT_PRICES'] as $arCatPrice)
			// foreach ($fullOffer['ITEM_ALL_PRICES'][$fullOffer['ITEM_PRICE_SELECTED']]['PRICES'] as $key => $arPrice)
			{
				$strPriceRanges = '';
				foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range)
				{
					if ($range['HASH'] !== 'ZERO-INF')
					{
						$itemPrice = false;

						foreach ($fullOffer['ITEM_ALL_PRICES'] as $itemPrice)
						{
							if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
							{
								$itemPrice = $itemPrice['PRICES'][$arCatPrice['ID']];
								break;
							}
						}

						if ($itemPrice)
						{
							$strPriceRanges .= '<dt>'.Loc::getMessage(
									'CT_BCE_CATALOG_RANGE_FROM',
									array('#FROM#' => $range['SORT_FROM'].' '.$measureName)
								).' ';

							if (is_infinite($range['SORT_TO']))
							{
								$strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
							}
							else
							{
								$strPriceRanges .= Loc::getMessage(
									'CT_BCE_CATALOG_RANGE_TO',
									array('#TO#' => $range['SORT_TO'].' '.$measureName)
								);
							}

							$strPriceRanges .= '</dt><dd>'.($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']).'</dd>';
						}
						$arPriceRanges[$arCatPrice['ID']] = $strPriceRanges;
					}
				}
			}
		}
		else
		{
				foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range)
				{
					if ($range['HASH'] !== 'ZERO-INF')
					{
						$itemPrice = false;

						foreach ($jsOffer['ITEM_PRICES'] as $itemPrice)
						{
							if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
							{
								break;
							}
						}

						if ($itemPrice)
						{
							$strPriceRanges .= '<dt>'.Loc::getMessage(
									'CT_BCE_CATALOG_RANGE_FROM',
									array('#FROM#' => $range['SORT_FROM'].' '.$measureName)
								).' ';

							if (is_infinite($range['SORT_TO']))
							{
								$strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
							}
							else
							{
								$strPriceRanges .= Loc::getMessage(
									'CT_BCE_CATALOG_RANGE_TO',
									array('#TO#' => $range['SORT_TO'].' '.$measureName)
								);
							}

							$strPriceRanges .= '</dt><dd>'.($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']).'</dd>';

							$arPriceRanges[$itemPrice['PRICE_TYPE_ID']] = $strPriceRanges;
						}
					}
				}

				unset($range, $itemPrice);
			}
			}

			$arArtnum = $fullOffer['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$fullOffer['IBLOCK_ID']]];
			if (isset($arArtnum))
			{
				$jsOffer['PROPERTIES'][$arArtnum['CODE']] = array(
					'ID' => $arArtnum['ID'],
					'VALUE' => $arArtnum['VALUE'] != ''
						? str_replace('#NUMBER#', $arArtnum['VALUE'],$arParams['MESS_ITEM_ARTNUMBER'])
						: '',
				);

				unset($arArtnum);
			}

			$jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
			$jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
			$jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
			$jsOffer['PRICE_RANGES_HTML'] = $arPriceRanges;
		}

		$templateData['OFFER_IDS'] = $offerIds;
		$templateData['OFFER_CODES'] = $offerCodes;
		unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio, $arPriceRanges);

		$jsParams = array(
			'CONFIG' => array(
				'USE_CATALOG' => $arResult['CATALOG'],
				'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
				'SHOW_PRICE' => true,
				'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
				'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
				'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
				'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
				'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
				'OFFER_GROUP' => $arResult['OFFER_GROUP'],
				'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
				'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
				'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
				'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
				'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
				'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
				'USE_STICKERS' => true,
				'USE_SUBSCRIBE' => $showSubscribe,
				'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
				'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
				'ALT' => $alt,
				'TITLE' => $title,
				'MAGNIFIER_ZOOM_PERCENT' => 200,
				'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
				'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
				'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
					? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
					: null,
				'USE_FAVORITE' => $arParams['USE_FAVORITE'] === 'Y',
				'FILL_ITEM_ALL_PRICES' => $arParams['FILL_ITEM_ALL_PRICES'],
				'LINK_BUY1CLICK' => $arParams['LINK_BUY1CLICK'],
				'LINK_BTN_REQUEST' => $arParams['LINK_BTN_REQUEST'],
/*
				'CHEAPER_FORM_URL' => $arParams['CHEAPER_FORM_URL'],
*/
			),
			'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
			'VISUAL' => $itemIds,
			'DEFAULT_PICTURE' => array(
				'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
				'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
			),
			'PRODUCT' => array(
				'ID' => $arResult['ID'],
				'ACTIVE' => $arResult['ACTIVE'],
				'NAME' => $arResult['~NAME'],
				'CATEGORY' => $arResult['CATEGORY_PATH'],
			),
			'BASKET' => array(
				'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
				'BASKET_URL' => $arParams['BASKET_URL'],
				'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
				'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
				'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
			),
			'OFFERS' => $arResult['JS_OFFERS'],
			'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
			'TREE_PROPS' => $skuProps,
			'MESS' => array(
				'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
				'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
				'MESS_BTN_INCART' => $arParams['MESS_BTN_INCART'],
			),
		);
	}
	else
	{
		$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
		if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties)
		{
			?>
			<div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
				<?
				if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
				{
					foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo)
					{
						?>
						<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
						<?
						unset($arResult['PRODUCT_PROPERTIES'][$propId]);
					}
				}

				$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
				if (!$emptyProductProperties)
				{
					?>
					<table>
						<?
						foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo)
						{
							?>
							<tr>
								<td><?=$arResult['PROPERTIES'][$propId]['NAME']?></td>
								<td>
									<?
									if (
										$arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === Iblock\PropertyTable::TYPE_LIST
										&& $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
									)
									{
										foreach ($propInfo['VALUES'] as $valueId => $value)
										{
											?>
											<label>
												<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]"
													value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"checked"' : '')?>>
												<?=$value?>
											</label>
											<br>
											<?
										}
									}
									else
									{
										?>
										<select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]">
											<?
											foreach ($propInfo['VALUES'] as $valueId => $value)
											{
												?>
												<option value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"selected"' : '')?>>
													<?=$value?>
												</option>
												<?
											}
											?>
										</select>
										<?
									}
									?>
								</td>
							</tr>
							<?
						}
						?>
					</table>
					<?
				}
				?>
			</div>
			<?
		}

		$jsParams = array(
			'CONFIG' => array(
				'USE_CATALOG' => $arResult['CATALOG'],
				'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
				'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
				'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
				'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
				'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
				'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
				'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
				'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
				'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
				'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
				'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
				'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
				'USE_STICKERS' => true,
				'USE_SUBSCRIBE' => $showSubscribe,
				// 'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
				// 'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
				'ALT' => $alt,
				'TITLE' => $title,
				'MAGNIFIER_ZOOM_PERCENT' => 200,
				'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
				'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
				'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
					? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
					: null,
				'USE_FAVORITE' => $arParams['USE_FAVORITE'] === 'Y',
				'FILL_ITEM_ALL_PRICES' => $arParams['FILL_ITEM_ALL_PRICES'],
				'LINK_BUY1CLICK' => $arParams['LINK_BUY1CLICK'],

				'LINK_BTN_REQUEST' => $arParams['LINK_BTN_REQUEST'],
/*
				'CHEAPER_FORM_URL' => $arParams['CHEAPER_FORM_URL'],
*/
			),
			'VISUAL' => $itemIds,
			'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
			'PRODUCT' => array(
				'ID' => $arResult['ID'],
				'ACTIVE' => $arResult['ACTIVE'],
				'PICT' => reset($arResult['MORE_PHOTO']),
				'NAME' => $arResult['~NAME'],
				'SUBSCRIPTION' => true,
				'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
				'ITEM_PRICES' => $arResult['ITEM_PRICES'],
				'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
				'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
				'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
				'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
				'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
				'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
				'SLIDER' => $arResult['MORE_PHOTO'],
				'CAN_BUY' => $arResult['CAN_BUY'],
				'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
				'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
				'MAX_QUANTITY' => $arResult['PRODUCT']['QUANTITY'],
				'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
				'CATEGORY' => $arResult['CATEGORY_PATH']
			),
			'BASKET' => array(
				'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
				'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
				'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
				'EMPTY_PROPS' => $emptyProductProperties,
				'BASKET_URL' => $arParams['BASKET_URL'],
				'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
				'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
			),
			'MESS' => array(
				'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
				'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
				'MESS_BTN_INCART' => $arParams['MESS_BTN_INCART'],
			),
		);

		if (
			$arParams['FILL_ITEM_ALL_PRICES']
			&& is_array($arResult['ITEM_ALL_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRICES']) && count($arResult['ITEM_ALL_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRICES']) > 1
		) {
			$jsParams['PRODUCT']['ITEM_ALL_PRICES'] = $arResult['ITEM_ALL_PRICES'];
		}

		if ($arResult['DAYSARTICLE'])
		{
			$arTimer = $arResult['DAYSARTICLE'];

			$jsParams['PRODUCT']['TIMER'] = array(
				'TITLE' => Loc::getMessage('RS_MM_BCE_CATALOG_DAYSARTICLE_TITLE'),
				'DATE_FROM' => $arTimer['DINAMICA_EX']['DATE_FROM'],
				'DATE_TO' => $arTimer['DINAMICA_EX']['DATE_TO'],
				'AUTO_RENEWAL' => $arTimer['AUTO_RENEWAL'],
				'QUANTITY' => $arTimer['QUANTITY']
			);

			if (isset($arTimer['DINAMICA']))
			{
				$jsParams['PRODUCT']['TIMER']['DINAMICA_DATA'] = $arTimer['DINAMICA'] == 'custom'
					? array_flip(unserialize($arTimer['DINAMICA_DATA']))
					: $arTimer['DINAMICA'];
			}
			unset($arTimer);
		}
		elseif ($arResult['QUICKBUY'])
		{
			$arTimer = $arResult['QUICKBUY'];

			$jsParams['PRODUCT']['TIMER'] = array(
				'TITLE' => Loc::getMessage('RS_MM_BCE_CATALOG_QUICKBUY_TITLE'),
				'DATE_FROM' => $arTimer['TIMER']['DATE_FROM'],
				'DATE_TO' => $arTimer['TIMER']['DATE_TO'],
				'AUTO_RENEWAL' => $arTimer['AUTO_RENEWAL'],
				'QUANTITY' => $arTimer['QUANTITY']
			);
			unset($arTimer);
		}


		unset($emptyProductProperties);
	}

	if ($arParams['DISPLAY_COMPARE'])
	{
		$jsParams['COMPARE'] = array(
			'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
			'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
			'COMPARE_PATH' => $arParams['COMPARE_PATH']
		);
	}

	$jsParams['CONFIG']['SLIDER_SLIDE_COUNT'] = $arParams['SLIDER_SLIDE_COUNT'];
	?>
<script>
	BX.message({
		ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>',
		BTN_COMPARE_ADD: '<?=CUtil::JSEscape($arParams['MESS_BTN_COMPARE'])?>',
		BTN_COMPARE_DEL: '<?=GetMessageJS('RS_MM_BCE_CATALOG_COMPARE_DEL')?>',
		BTN_FAVORITE_ADD: '<?=GetMessageJS('RS_MM_BCE_CATALOG_FAVORITE_ADD')?>',
		BTN_FAVORITE_DEL: '<?=GetMessageJS('RS_MM_BCE_CATALOG_FAVORITE_DEL')?>',
		LOWER_PRICE: '<?=GetMessageJS('RS_MM_BCE_CATALOG_LOWER_PRICE')?>',
	});

	var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>
<?php
unset($actualItem, $itemIds, $jsParams);
