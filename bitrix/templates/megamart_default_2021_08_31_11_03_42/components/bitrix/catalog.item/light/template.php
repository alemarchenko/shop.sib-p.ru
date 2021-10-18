<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
use \Bitrix\main\Localization\Loc;
use \Redsign\MegaMart\MyTemplate;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

Loc::loadMessages(__FILE__);

$this->setFrameMode(true);

if (isset($arResult['ITEM']))
{
	$item = $arResult['ITEM'];
	$areaId = $arResult['AREA_ID'];
	$itemIds = array(
		'ID' => $areaId,
		'PICT' => $areaId.'_pict',
		'SECOND_PICT' => $areaId.'_secondpict',
		'PICT_SLIDER' => $areaId.'_pict_slider',
		'STICKER_ID' => $areaId.'_sticker',
		'SECOND_STICKER_ID' => $areaId.'_secondsticker',
		'QUANTITY' => $areaId.'_quantity',
		'QUANTITY_MENU' => $areaId.'_quiantity_menu',
		'QUANTITY_DOWN' => $areaId.'_quant_down',
		'QUANTITY_UP' => $areaId.'_quant_up',
		'QUANTITY_MEASURE' => $areaId.'_quant_measure',
		'QUANTITY_LIMIT' => $areaId.'_quant_limit',
		'BUY_LINK' => $areaId.'_buy_link',
		'BASKET_ACTIONS' => $areaId.'_basket_actions',
		'NOT_AVAILABLE_MESS' => $areaId.'_not_avail',
		'SUBSCRIBE_LINK' => $areaId.'_subscribe',
		'COMPARE_LINK' => $areaId.'_compare_link',
		'PRICE' => $areaId.'_price',
		'PRICE_OLD' => $areaId.'_price_old',
		'PRICE_TOTAL' => $areaId.'_price_total',
		'DSC_PERC' => $areaId.'_dsc_perc',
		'SECOND_DSC_PERC' => $areaId.'_second_dsc_perc',
		'PROP_DIV' => $areaId.'_sku_tree',
		'PROP' => $areaId.'_prop_',
		'DISPLAY_PROP_DIV' => $areaId.'_sku_prop',
		'BASKET_PROP_DIV' => $areaId.'_basket_prop',
	);
	$obName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $areaId);
	$isBig = isset($arResult['BIG']) && $arResult['BIG'] === 'Y';

	$productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
		? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
		: $item['NAME'];

	$imgTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
		? $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
		: $item['NAME'];

	$skuProps = array();

	$haveOffers = !empty($item['OFFERS']);
	if ($haveOffers)
	{
		$actualItem = isset($item['OFFERS'][$item['OFFERS_SELECTED']])
			? $item['OFFERS'][$item['OFFERS_SELECTED']]
			: reset($item['OFFERS']);
	}
	else
	{
		$actualItem = $item;
	}

if (isset($item['PRODUCT']['TYPE']) && $item['PRODUCT']['TYPE'] != 0) {

	if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
	{
		$price = $item['ITEM_START_PRICE'];
		$minOffer = $item['OFFERS'][$item['ITEM_START_PRICE_SELECTED']];
		$measureRatio = $minOffer['ITEM_MEASURE_RATIOS'][$minOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
		$morePhoto = $item['MORE_PHOTO'];
	}
	else
	{
		$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
		$measureRatio = $price['MIN_QUANTITY'];
		$morePhoto = $actualItem['MORE_PHOTO'];
	}

} else {

    $measureRatio = 1;
    $actualItem['ITEM_MEASURE']['TITLE'] = Loc::getMessage('RS_MM_BCI_LIGHT_ITEM_MEASURE');
    $item['ITEM_MEASURE_RATIO_SELECTED'] = 0;
    $item['ITEM_MEASURE_RATIOS'][$item['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'] = 1;

    $price = $item['RS_PRICES'];
    $actualItem['CAN_BUY'] = is_array($price);
    $item['CAN_BUY'] = $actualItem['CAN_BUY'];
    $item['PRODUCT']['TYPE'] = 0;

}
	$showSlider = is_array($morePhoto) && count($morePhoto) > 1;
	$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($item['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

    $discountPositionClass = '';
/*
	$discountPositionClass = isset($arResult['BIG_DISCOUNT_PERCENT']) && $arResult['BIG_DISCOUNT_PERCENT'] === 'Y'
		? 'product-item-label-big'
		: 'product-item-label-small';
*/
	$discountPositionClass .= $arParams['DISCOUNT_POSITION_CLASS'];

/*
	$labelPositionClass = isset($arResult['BIG_LABEL']) && $arResult['BIG_LABEL'] === 'Y'
		? 'product-item-label-big'
		: 'product-item-label-small';
*/
	$labelPositionClass .= $arParams['LABEL_POSITION_CLASS'];

/*
	$buttonSizeClass = isset($arResult['BIG_BUTTONS']) && $arResult['BIG_BUTTONS'] === 'Y' ? 'btn-md' : 'btn-sm';
*/
	$itemHasDetailUrl = isset($item['DETAIL_PAGE_URL']) && $item['DETAIL_PAGE_URL'] != '';
	?>

	<div class="product-light-container w-100"
		id="<?=$areaId?>" data-entity="item">
		<article class="product-light h-100">
			<div class="row">
				<div class="col-3 col-lg-4">
					<?php
					if ($itemHasDetailUrl)
					{
						?>
						<a class="product-light-image-wrapper" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$imgTitle?>"
								data-entity="image-wrapper">
						<?php
					}
					else
					{
						?>
						<span class="product-item-image-wrapper" data-entity="image-wrapper">
						<?php
					}

					$item['PICTURE'] = array(
						'SRC' => $item['PREVIEW_PICTURE']['ID'] > 0
							? $item['PREVIEW_PICTURE']['RESIZE']['src']
							: $item['PREVIEW_PICTURE']['SRC'],
						'ALT' => (
							'' != $item['IPROPERTY_VALUES']['ELEMENT_PICTURE_FILE_ALT']
							? $item['IPROPERTY_VALUES']['ELEMENT_PICTURE_FILE_ALT']
							: $item['NAME']
						),
						'TITLE' => (
							'' != $item['IPROPERTY_VALUES']['ELEMENT_PICTURE_FILE_TITLE']
							? $item['IPROPERTY_VALUES']['ELEMENT_PICTURE_FILE_TITLE']
							: $item['NAME']
						)
					);

					if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y')
					{
						?>
						<img class="product-light-image lazy-anim-img" data-lazy-img data-src="<?=$item['PICTURE']['SRC']?>" src="<?=Redsign\MegaMart\LazyloadUtils::getEmptyImage(1, 1);?>" id="<?=$itemIds['PICT']?>" alt="<?=$item['PICTURE']['ALT']?>" title="<?=$item['PICTURE']['TITLE']?>">
						<?php
					}
					else
					{
						?>
						<img class="product-light-image" id="<?=$itemIds['PICT']?>" src="<?=$item['PICTURE']['SRC']?>" alt="<?=$item['PICTURE']['ALT']?>" title="<?=$item['PICTURE']['TITLE']?>">
						<?php
					}
			/*
					if ($item['LABEL'])
					{
						?>
						<span class="product-light-label-text <?=$labelPositionClass?>" id="<?=$itemIds['STICKER_ID']?>">
							<?
							if (!empty($item['LABEL_ARRAY_VALUE']))
							{
								foreach ($item['LABEL_ARRAY_VALUE'] as $code => $value)
								{
									$sLabelStyle = '';
									if (substr($item['PROPERTIES'][$code]['VALUE_XML_ID'], 0, 1) == '#') {
										$sLabelStyle = ' style="background:'.$item['PROPERTIES'][$code]['VALUE_XML_ID'].'"';
									}
									?>
									<span class="product-light-label-text-item<?=(!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' hidden-xs' : '')?>">
										<span title="<?=$value?>"<?if (strlen($sLabelStyle) > 0){ echo $sLabelStyle; }?>><?=$value?></span>
									</span>
									<?
								}
							}
							?>
						</span>
						<?
					}
			*/
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

				<div class="col-9 col-lg-8 pl-0">

					<h6 class="product-light-title">
						<? if ($itemHasDetailUrl): ?>
							<a href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$productTitle?>">
						<? endif; ?>

						<?=$productTitle?>

						<? if ($itemHasDetailUrl): ?>
							</a>
						<? endif; ?>
					</h6>

					<div class="product-light-info-container product-light-price-container" data-entity="price-block">
						<div class="product-light-info-container-title">
						<?php
						if (/*$arParams['PRODUCT_DISPLAY_MODE'] === 'N' && */$haveOffers)
						{
							echo Loc::getMessage(
								'RS_MM_BCI_LIGHT_PRICE_FROM_SIMPLE_MODE',
								array(
									'#VALUE#' => $measureRatio,
									'#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
								)
							).':';
						}
						else
						{
							?><br><?//echo Loc::getMessage('RS_MM_BCI_LIGHT_PRICE').':';
						}
						?>
						</div>
						<span
							class="product-light-price-current<?=($price['RATIO_PRICE'] < $price['RATIO_BASE_PRICE'] ? ' discount' : '')?>"
							id="<?=$itemIds['PRICE']?>">
							<?
							if (!empty($price))
							{
								// if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
								// {
									// echo Loc::getMessage(
										// 'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
										// array(
											// '#PRICE#' => $price['PRINT_RATIO_PRICE'],
											// '#VALUE#' => $measureRatio,
											// '#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
										// )
									// );
								// }
								// else
								// {
									echo $price['PRINT_RATIO_PRICE'];
								// }
							}
							else
							{
								echo Loc::getMessage('RS_MM_BCI_LIGHT_NO_PRICE');
							}
							?>
						</span>
						<?
						if ($arParams['SHOW_OLD_PRICE'] === 'Y')
						{
							?>
							&nbsp;<span class="product-light-price-old" id="<?=$itemIds['PRICE_OLD']?>"
								<?=($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '')?>
								><?=$price['PRINT_RATIO_BASE_PRICE']?></span>
							<?
						}
						?>
					</div>
				</div>
			</div>
		</article>
		<?
		if (!$haveOffers)
		{
			$jsParams = array(
				'PRODUCT_TYPE' => $item['PRODUCT']['TYPE'],
				'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
				'SHOW_ADD_BASKET_BTN' => false,
				'SHOW_BUY_BTN' => true,
				'SHOW_ABSENT' => true,
				'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
				'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
				'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
				'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
				'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
				'BIG_DATA' => $item['BIG_DATA'],
				'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
				'VIEW_MODE' => $arResult['TYPE'],
				'USE_SUBSCRIBE' => $showSubscribe,
				'PRODUCT' => array(
					'ID' => $item['ID'],
					'NAME' => $productTitle,
					'DETAIL_PAGE_URL' => $item['DETAIL_PAGE_URL'],
					'PICT' => $item['SECOND_PICT'] ? $item['PREVIEW_PICTURE_SECOND'] : $item['PREVIEW_PICTURE'],
					'CAN_BUY' => $item['CAN_BUY'],
					'CHECK_QUANTITY' => $item['CHECK_QUANTITY'],
					'MAX_QUANTITY' => $item['CATALOG_QUANTITY'],
					'STEP_QUANTITY' => $item['ITEM_MEASURE_RATIOS'][$item['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
					'QUANTITY_FLOAT' => is_float($item['ITEM_MEASURE_RATIOS'][$item['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
					'ITEM_PRICE_MODE' => $item['ITEM_PRICE_MODE'],
					'ITEM_PRICES' => $item['ITEM_PRICES'],
					'ITEM_PRICE_SELECTED' => $item['ITEM_PRICE_SELECTED'],
					'ITEM_QUANTITY_RANGES' => $item['ITEM_QUANTITY_RANGES'],
					'ITEM_QUANTITY_RANGE_SELECTED' => $item['ITEM_QUANTITY_RANGE_SELECTED'],
					'ITEM_MEASURE_RATIOS' => $item['ITEM_MEASURE_RATIOS'],
					'ITEM_MEASURE_RATIO_SELECTED' => $item['ITEM_MEASURE_RATIO_SELECTED'],
					'MORE_PHOTO' => $item['MORE_PHOTO'],
					'MORE_PHOTO_COUNT' => $item['MORE_PHOTO_COUNT']
				),
				'BASKET' => array(
					'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
					'EMPTY_PROPS' => empty($item['PRODUCT_PROPERTIES']),
					'BASKET_URL' => $arParams['~BASKET_URL'],
					'ADD_URL_TEMPLATE' => $arParams['~ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arParams['~BUY_URL_TEMPLATE']
				),
				'VISUAL' => array(
					'ID' => $itemIds['ID'],
					'PICT_ID' => $itemIds['PICT'],
					'PICT_SLIDER_ID' => $itemIds['PICT_SLIDER'],
					'QUANTITY_ID' => $itemIds['QUANTITY'],
					'QUANTITY_UP_ID' => $itemIds['QUANTITY_UP'],
					'QUANTITY_DOWN_ID' => $itemIds['QUANTITY_DOWN'],
					'PRICE_ID' => $itemIds['PRICE'],
					'PRICE_OLD_ID' => $itemIds['PRICE_OLD'],
					'PRICE_TOTAL_ID' => $itemIds['PRICE_TOTAL'],
					'BUY_ID' => $itemIds['BUY_LINK'],
					'BASKET_PROP_DIV' => $itemIds['BASKET_PROP_DIV'],
					'BASKET_ACTIONS_ID' => $itemIds['BASKET_ACTIONS'],
					'NOT_AVAILABLE_MESS' => $itemIds['NOT_AVAILABLE_MESS'],
					'COMPARE_LINK_ID' => $itemIds['COMPARE_LINK'],
					'SUBSCRIBE_ID' => $itemIds['SUBSCRIBE_LINK']
				)
			);
		}
		else
		{
			$jsParams = array(
				'PRODUCT_TYPE' => $item['PRODUCT']['TYPE'],
				'SHOW_QUANTITY' => false,
				'SHOW_ADD_BASKET_BTN' => false,
				'SHOW_BUY_BTN' => true,
				'SHOW_ABSENT' => true,
				'SHOW_SKU_PROPS' => false,
				'SECOND_PICT' => $item['SECOND_PICT'],
				'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
				'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
				'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
				'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
				'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
				'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
				'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
				'BIG_DATA' => $item['BIG_DATA'],
				'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
				'VIEW_MODE' => $arResult['TYPE'],
				'USE_SUBSCRIBE' => $showSubscribe,
				'DEFAULT_PICTURE' => array(
					'PICTURE' => $item['PRODUCT_PREVIEW'],
					'PICTURE_SECOND' => $item['PRODUCT_PREVIEW_SECOND']
				),
				'VISUAL' => array(
					'ID' => $itemIds['ID'],
					'PICT_ID' => $itemIds['PICT'],
					'SECOND_PICT_ID' => $itemIds['SECOND_PICT'],
					'PICT_SLIDER_ID' => $itemIds['PICT_SLIDER'],
					'QUANTITY_ID' => $itemIds['QUANTITY'],
					'QUANTITY_UP_ID' => $itemIds['QUANTITY_UP'],
					'QUANTITY_DOWN_ID' => $itemIds['QUANTITY_DOWN'],
					'QUANTITY_MEASURE' => $itemIds['QUANTITY_MEASURE'],
					'QUANTITY_LIMIT' => $itemIds['QUANTITY_LIMIT'],
					'PRICE_ID' => $itemIds['PRICE'],
					'PRICE_OLD_ID' => $itemIds['PRICE_OLD'],
					'PRICE_TOTAL_ID' => $itemIds['PRICE_TOTAL'],
					'TREE_ID' => $itemIds['PROP_DIV'],
					'TREE_ITEM_ID' => $itemIds['PROP'],
					'BUY_ID' => $itemIds['BUY_LINK'],
					'DSC_PERC' => $itemIds['DSC_PERC'],
					'SECOND_DSC_PERC' => $itemIds['SECOND_DSC_PERC'],
					'DISPLAY_PROP_DIV' => $itemIds['DISPLAY_PROP_DIV'],
					'BASKET_ACTIONS_ID' => $itemIds['BASKET_ACTIONS'],
					'NOT_AVAILABLE_MESS' => $itemIds['NOT_AVAILABLE_MESS'],
					'COMPARE_LINK_ID' => $itemIds['COMPARE_LINK'],
					'SUBSCRIBE_ID' => $itemIds['SUBSCRIBE_LINK']
				),
				'BASKET' => array(
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
					'SKU_PROPS' => $item['OFFERS_PROP_CODES'],
					'BASKET_URL' => $arParams['~BASKET_URL'],
					'ADD_URL_TEMPLATE' => $arParams['~ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arParams['~BUY_URL_TEMPLATE']
				),
				'PRODUCT' => array(
					'ID' => $item['ID'],
					'NAME' => $productTitle,
					'DETAIL_PAGE_URL' => $item['DETAIL_PAGE_URL'],
					'MORE_PHOTO' => $item['MORE_PHOTO'],
					'MORE_PHOTO_COUNT' => $item['MORE_PHOTO_COUNT']
				),
				'OFFERS' => array(),
				'OFFER_SELECTED' => 0,
				'TREE_PROPS' => array()
			);

			if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && !empty($item['OFFERS_PROP']))
			{
				$jsParams['SHOW_QUANTITY'] = $arParams['USE_PRODUCT_QUANTITY'];
				$jsParams['SHOW_SKU_PROPS'] = $item['OFFERS_PROPS_DISPLAY'];
				$jsParams['OFFERS'] = $item['JS_OFFERS'];
				$jsParams['OFFER_SELECTED'] = $item['OFFERS_SELECTED'];
				$jsParams['TREE_PROPS'] = $skuProps;
			}
		}

		if ($arParams['DISPLAY_COMPARE'])
		{
			$jsParams['COMPARE'] = array(
				'COMPARE_URL_TEMPLATE' => $arParams['~COMPARE_URL_TEMPLATE'],
				'COMPARE_DELETE_URL_TEMPLATE' => $arParams['~COMPARE_DELETE_URL_TEMPLATE'],
				'COMPARE_PATH' => $arParams['COMPARE_PATH']
			);
		}

		if ($item['BIG_DATA'])
		{
			$jsParams['PRODUCT']['RCM_ID'] = $item['RCM_ID'];
		}

		$jsParams['PRODUCT_DISPLAY_MODE'] = $arParams['PRODUCT_DISPLAY_MODE'];
		$jsParams['USE_ENHANCED_ECOMMERCE'] = $arParams['USE_ENHANCED_ECOMMERCE'];
		$jsParams['DATA_LAYER_NAME'] = $arParams['DATA_LAYER_NAME'];
		$jsParams['BRAND_PROPERTY'] = !empty($item['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
			? $item['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
			: null;

		$templateData = array(
			'JS_OBJ' => $obName,
			'ITEM' => array(
				'ID' => $item['ID'],
				'IBLOCK_ID' => $item['IBLOCK_ID'],
				'OFFERS_SELECTED' => $item['OFFERS_SELECTED'],
				'JS_OFFERS' => $item['JS_OFFERS']
			)
		);
		?>
<?/*
		<script>
		  var <?=$obName?> = new JCCatalogItem(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
		</script>
*/?>
	</div>
	<?
	unset($item, $actualItem, $minOffer, $itemIds, $jsParams);
}
