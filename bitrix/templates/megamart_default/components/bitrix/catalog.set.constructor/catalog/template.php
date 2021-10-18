<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$templateData = array(
	// 'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	// 'TEMPLATE_CLASS' => 'bx-'.$arParams['TEMPLATE_THEME'],
	'CURRENCIES' => CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)
);
$curJsId = $this->randString();
?>
<!---->
<div class="row">
	<div class="col">
		<div id="bx-set-const-<?=$curJsId?>" class="catalog-set-constructor">
			<div class="row">

				<div class="col-lg-3 col-sm-6 order-sm-1 order-lg-1">
					<div class="catalog-set-constructor-product-item-container">
						<div class="catalog-set-constructor-product-item-canvas">
						<?if ($arResult["ELEMENT"]["DETAIL_PICTURE"]["src"]):?>
							<img src="<?=$arResult["ELEMENT"]["DETAIL_PICTURE"]["src"]?>" class="catalog-set-constructor-product-item-image" alt="">
						<?else:?>
							<img src="<?=$this->GetFolder().'/images/no_foto.png'?>" class="catalog-set-constructor-product-item-image mb-2" alt="">
						<?endif?>
						</div>

						<div class="catalog-set-constructor-product-info">
							<h6 class="catalog-set-constructor-product-name"><?=$arResult["ELEMENT"]["NAME"]?></h6>
							<?if (!($arResult["ELEMENT"]["PRICE_VALUE"] == $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"])):?>
								<div class="catalog-set-constructor-product-old-price"><?=$arResult["ELEMENT"]["PRICE_PRINT_VALUE"]?></div>
							<?endif?>
							<div class="catalog-set-constructor-product-new-price">
								<?=$arResult["ELEMENT"]["PRICE_PRINT_DISCOUNT_VALUE"]?>
								* <?=$arResult["ELEMENT"]["BASKET_QUANTITY"];?> <?=$arResult["ELEMENT"]["MEASURE"]["SYMBOL_RUS"];?>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6 order-sm-3 order-lg-2">
					<div class="catalog-set-constructor-items-list" data-role="set-items">

						<?foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem):?>
							<div class="catalog-set-constructor-item product-cat-table-card"
								data-id="<?=$arItem["ID"]?>"
								data-img="<?=$arItem["DETAIL_PICTURE"]["src"]?>"
								data-url="<?=$arItem["DETAIL_PAGE_URL"]?>"
								data-name="<?=$arItem["NAME"]?>"
								data-price="<?=$arItem["PRICE_DISCOUNT_VALUE"]?>"
								data-print-price="<?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?>"
								data-old-price="<?=$arItem["PRICE_VALUE"]?>"
								data-print-old-price="<?=$arItem["PRICE_PRINT_VALUE"]?>"
								data-diff-price="<?=$arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]?>"
								data-measure="<?=$arItem["MEASURE"]["SYMBOL_RUS"];?>"
								data-quantity="<?=$arItem["BASKET_QUANTITY"];?>"
								data-role="set-item"
							>
								<div class="product-cat-container">
									<div class="product-cat">
										<div class="row align-items-center">
											<div class="col-3 col-sm-2">
												<div class="product-cat-image-wrapper">
													<a class="product-cat-image-canvas" target="_blank" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
														<?if ($arItem["DETAIL_PICTURE"]["src"]):?>
															<img src="<?=$arItem["DETAIL_PICTURE"]["src"]?>" class="product-cat-image" alt="">
														<?else:?>
															<img src="<?=$this->GetFolder().'/images/no_foto.png'?>" class="product-cat-image" alt="">
														<?endif?>
													</a>
												</div>
											</div>
											<div class="col-9 col-sm-10">
												<div class="row h-100 align-items-center">
													<div class="col-12 col-sm">
														<div class="product-cat-content">
															<div class="product-cat-head">
																<h6><a class="" target="_blank" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></h6>
															</div>
														</div>
													</div>
													<div class="col-8 col-sm-5 col-lg-4">
														<div class="product-cat-price-container">
															<?if ($arItem["PRICE_VALUE"] != $arItem["PRICE_DISCOUNT_VALUE"]):?>
																<div class="product-cat-price-old"><?=$arItem["PRICE_PRINT_VALUE"]?></div>
															<?endif?>
															<div class="product-cat-price-current">
																<?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?> * <?=$arItem["BASKET_QUANTITY"];?> <?=$arItem["MEASURE"]["SYMBOL_RUS"];?>
															</div>
														</div>
													</div>
													<div class="col-4 col-sm-auto text-right">
														<div class="product-cat-button-container">
															<div class="catalog-set-constructor-items-list-delete-btn" data-role="set-delete-btn">
																<svg class="icon-svg trash-anim-icon" data-entity="basket-item-delete" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">
																	<path d="M29,13H25V12a3,3,0,0,0-3-3H18a3,3,0,0,0-3,3v1H11a1,1,0,0,0,0,2H29a1,1,0,0,0,0-2ZM17,13V12a1,1,0,0,1,1-1h4a1,1,0,0,1,1,1v1Z"/>
																	<path d="M25,31H15a3,3,0,0,1-3-3V15a1,1,0,0,1,2,0V28a1,1,0,0,0,1,1H25a1,1,0,0,0,1-1V15a1,1,0,0,1,2,0V28A3,3,0,0,1,25,31Zm-6-6V19a1,1,0,0,0-2,0v6a1,1,0,0,0,2,0Zm4,0V19a1,1,0,0,0-2,0v6a1,1,0,0,0,2,0Z"/>
																</svg></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?endforeach?>
						<div style="display: none;margin:20px;" data-set-message="empty-set"></div>

					</div>
				</div>
				<div class="col-lg-3 col-sm-6 order-sm-2 order-lg-3">
					<div class="catalog-set-constructor-result">
						<img src="<?=$this->GetFolder().'/images/flame.png'?>" class="mb-2" alt="">
						<div class="catalog-set-constructor-result-title"><?=GetMessage("CATALOG_SET_ECONOMY")?></div>
						<div class="mb-3"><?=GetMessage("CATALOG_SET_SET_PRICE")?>:</div>
						<div class="mb--2" style="display: <?=($arResult['SHOW_DEFAULT_SET_DISCOUNT'] ? 'block' : 'none'); ?>;">
							<del data-role="set-old-price"><?=$arResult["SET_ITEMS"]["OLD_PRICE"]?></del>
						</div>
						<div class="font-weight-bold font-size-lg mb-4" data-role="set-price"><?=$arResult["SET_ITEMS"]["PRICE"]?></div>
<?/*
						<div data-role="set-diff-price" style="display: <?=($arResult['SHOW_DEFAULT_SET_DISCOUNT'] ? 'block' : 'none'); ?>;">
							<td class="catalog-set-constructor-result-table-title"><?=GetMessage("CATALOG_SET_ECONOMY_PRICE")?>:</td>
							<td class="catalog-set-constructor-result-table-value">
								<strong><?=$arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"]?></strong>
							</td>
						</div>
						<div class="catalog-set-constructor-result-btn-container">
							<span class="catalog-set-constructor-result-price border-primary" data-role="set-price-duplicate">
								<?=$arResult["SET_ITEMS"]["PRICE"]?>
							</span>
						</div>
*/?>
						<div class="catalog-set-constructor-result-btn-container">
							<a href="javascript:void(0)" data-role="set-buy-btn" class="btn btn-primary btn-rounded w-100"
								<?=($arResult["ELEMENT"]["CAN_BUY"] ? '' : 'style="display: none;"')?>>
								<?=GetMessage("CATALOG_SET_BUY")?>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="row" data-role="slider-parent-container"<?=(empty($arResult["SET_ITEMS"]["OTHER"]) ? 'style="display:none;"' : '')?>>
				<div class="col">
					<div class="catalog-set-constructor-slider">
						<div class="catalog-set-constructor-slider-box">
							<div class="catalog-set-constructor-slider-container scrollbar-inner" data-role="slider-container">
								<div class="catalog-set-constructor-slider-slide catalog-set-constructor-slider-slide-<?=$curJsId?> clearfix" data-role="set-other-items">
									<?
									$first = true;
									foreach($arResult["SET_ITEMS"]["OTHER"] as $key => $arItem):?>
										<div class="catalog-set-constructor-slider-item-container catalog-set-constructor-slider-item-container-<?=$curJsId?>"
											data-id="<?=$arItem["ID"]?>"
											data-img="<?=$arItem["DETAIL_PICTURE"]["src"]?>"
											data-url="<?=$arItem["DETAIL_PAGE_URL"]?>"
											data-name="<?=$arItem["NAME"]?>"
											data-price="<?=$arItem["PRICE_DISCOUNT_VALUE"]?>"
											data-print-price="<?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?>"
											data-old-price="<?=$arItem["PRICE_VALUE"]?>"
											data-print-old-price="<?=$arItem["PRICE_PRINT_VALUE"]?>"
											data-diff-price="<?=$arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]?>"
											data-measure="<?=$arItem["MEASURE"]["SYMBOL_RUS"];?>"
											data-quantity="<?=$arItem["BASKET_QUANTITY"];?>"<?
										if (!$arItem['CAN_BUY'] && $first)
										{
											echo 'data-not-avail="yes"';
											$first = false;
										}
										?>
										>
											<div class="catalog-set-constructor-slider-item">
												<a class="catalog-set-constructor-slider-item-img" target="_blank" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
													<div class="catalog-set-constructor-slider-item-img-container">
														<?if ($arItem["DETAIL_PICTURE"]["src"]):?>
															<img src="<?=$arItem["DETAIL_PICTURE"]["src"]?>" class="img-responsive" alt=""/>
														<?else:?>
															<img src="<?=$this->GetFolder().'/images/no_foto.png'?>" class="img-responsive"/>
														<?endif?>
													</div>
												</a>
												<div class="catalog-set-constructor-slider-item-title">
													<a target="_blank" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
												</div>
												<div class="catalog-set-constructor-slider-item-price">
													<?if ($arItem["PRICE_VALUE"] != $arItem["PRICE_DISCOUNT_VALUE"]):?>
														<div class="catalog-set-constructor-product-old-price"><?=$arItem["PRICE_PRINT_VALUE"]?></div>
													<?endif?>
													<div class="catalog-set-constructor-product-new-price"><?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?> * <?=$arItem["BASKET_QUANTITY"];?> <?=$arItem["MEASURE"]["SYMBOL_RUS"];?></div>
												</div>
												<div class="catalog-set-constructor-slider-item-add-btn">
													<?
													if ($arItem['CAN_BUY'])
													{
														?><a href="javascript:void(0)" data-role="set-add-btn" class="btn btn-primary btn-rounded"><?=GetMessage("CATALOG_SET_BUTTON_ADD")?></a><?
													}
													else
													{
														?><span class="btn btn-secondary btn-rounded"><?=GetMessage('CATALOG_SET_MESS_NOT_AVAILABLE');?></span><?
													}
													?>
												</div>
											</div>
										</div>
									<?endforeach?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?
		$arJsParams = array(
			"numSliderItems" => count($arResult["SET_ITEMS"]["OTHER"]),
			"numSetItems" => count($arResult["SET_ITEMS"]["DEFAULT"]),
			"jsId" => $curJsId,
			"parentContId" => "bx-set-const-".$curJsId,
			"ajaxPath" => $this->GetFolder().'/ajax.php',
			"canBuy" => $arResult["ELEMENT"]["CAN_BUY"],
			"currency" => $arResult["ELEMENT"]["PRICE_CURRENCY"],
			"mainElementPrice" => $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"],
			"mainElementOldPrice" => $arResult["ELEMENT"]["PRICE_VALUE"],
			"mainElementDiffPrice" => $arResult["ELEMENT"]["PRICE_DISCOUNT_DIFFERENCE_VALUE"],
			"mainElementBasketQuantity" => $arResult["ELEMENT"]["BASKET_QUANTITY"],
			"lid" => SITE_ID,
			"iblockId" => $arParams["IBLOCK_ID"],
			"basketUrl" => $arParams["BASKET_URL"],
			"setIds" => $arResult["DEFAULT_SET_IDS"],
			"offersCartProps" => $arParams["OFFERS_CART_PROPERTIES"],
			"itemsRatio" => $arResult["BASKET_QUANTITY"],
			"noFotoSrc" => $this->GetFolder().'/images/no_foto.png',
			"messages" => array(
				"EMPTY_SET" => GetMessage('CT_BCE_CATALOG_MESS_EMPTY_SET'),
				"ADD_BUTTON" => GetMessage("CATALOG_SET_BUTTON_ADD")
			)
		);
		?>
		<script type="text/javascript">
			BX.ready(function(){
				new BX.Catalog.SetConstructor(<?=CUtil::PhpToJSObject($arJsParams, false, true, true)?>);
			});
		</script>
	</div>
</div>
<!--/-->