<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
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

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Redsign\MegaMart\MyTemplate;

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

CJSCore::Init(array('dnd'));
$this->addExternalCss(SITE_TEMPLATE_PATH.'/assets/styles/catalog-item.css');

$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('RS_MM_BCCR_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('RS_MM_BCCR_CATALOG_RELATIVE_QUANTITY_FEW');
$arParams['MESS_ECONOMY_INFO2'] = Loc::getMessage('RS_MM_BCCR_CATALOG_ECONOMY_INFO2');
$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_ADD_TO_BASKET'] = '<svg class="icon-cart icon-svg"><use xlink:href="#svg-cart"></use></svg>';

$buttonSizeClass = 'btn-sm btn-rounded';

$iMinColumsCount = 5;

$mainId = $this->GetEditAreaId('compare');
$itemIds = array(
	'ID' => $mainId,
	'TABLE' => $mainId.'table',
	'PROP_GROUP' => $mainId.'_group_',
);

$jsParams = array(
	'ITEMS' => array(
		// 'item' => 'item',
	),
	'CONFIG' => array(
		'NAME' => $arParams['NAME'],
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'TEMPLATE_FOLDER' => $templateFolder,
		'MIN_COLUMNS_COUNT' => $iMinColumsCount,
	),
	'VISUAL' => $itemIds,
);

$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);

$isAjax = (
	$request->get('ajax_id') == $itemIds['ID']
	&& $request->get('ajax_action') == 'Y'
);

if (isset($_REQUEST[$arParams['ACTION_VARIABLE']]))
{
	switch (ToUpper($_REQUEST[$arParams['ACTION_VARIABLE']]))
	{
		case "COMPARE_CLEAR":
			if (isset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"]))
			{
				$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]] = array();
				$arResult['ITEMS'] = array();
			}
			break;
	}
}

$iTableCol = count($arResult['ITEMS']);
$arFieldsHide = array(
	'NAME',
	'PREVIEW_PICTURE',
	'DETAIL_PICTURE',
	'CATALOG_QUANTITY',
	'CATALOG_MEASURE',
	'CATALOG_QUANTITY_TRACE',
	'CATALOG_CAN_BUY_ZERO',
	'CAN_BUY',
);

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);

$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addData('SECTION_MAIN_ATTRIBUTES', 'id="'.$itemIds['ID'].'"');
?>

<div class="container-compensator">

<?php
$layout->start();

if ($isAjax)
{
	$APPLICATION->restartBuffer();
}
else
{
	$frame = $this->createFrame($itemIds['ID'], false)->begin('');
}

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS'])):
?>


	<div class="p-4 mb-3 clearfix">
		<div class="d-none d-md-block">
			<span class="btn px-0 my-2 mx-3"><?=GetMessage("CATALOG_SHOWN_CHARACTERISTICS")?>:</span>
			<div class="d-md-inline-block">
				<?php if (!$arResult["DIFFERENT"]): ?>
					<span class="btn btn-primary my-2"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></span>
					<a class="btn btn-outline-secondary-primary my-2" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=Y'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a>
				<?php else: ?>
					<a class="btn btn-outline-secondary-primary my-2" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=N'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a>
					<span class="btn btn-primary my-2"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></span>
				<?php endif; ?>
			</div>
			<a class="btn btn-link px-0 my-2 float-right" href="<?=$arResult['COMPARE_URL_TEMPLATE'].$arParams['ACTION_VARIABLE'].'=COMPARE_CLEAR'?>" onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arResult['COMPARE_URL_TEMPLATE'].$arParams['ACTION_VARIABLE'].'=COMPARE_CLEAR')?>');"><?=GetMessage('RS_MM_BCCR_CATALOG_COMPARE_CLEAR')?></a>
		</div>
		<div class="d-block d-md-none small">
			<div>
			<?php if (!$arResult["DIFFERENT"]): ?>
				<span class="text-primary d-inline-block py-3 mx-3"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></span>
				<a class="text-secondary d-inline-block py-3 mx-3" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=Y'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a>
			<?php else: ?>
				<a class="text-secondary d-inline-block py-3 mx-3" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=N'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a>
				<span class="text-primary d-inline-block py-3 mx-3"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></span>
			<?php endif; ?>
			</div>
			<a class="px-0 py-3 mx-3" href="<?=$arResult['COMPARE_URL_TEMPLATE'].$arParams['ACTION_VARIABLE'].'=COMPARE_CLEAR'?>" onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arResult['COMPARE_URL_TEMPLATE'].$arParams['ACTION_VARIABLE'].'=COMPARE_CLEAR')?>');"><?=GetMessage('RS_MM_BCCR_CATALOG_COMPARE_CLEAR')?></a>
		</div>
	</div>

	<div class="position-relative" id="<?=$itemIds['TABLE']?>" style="opacity:0" data-entity="compare-page">

<?php
/*
		<?
		if (!empty($arResult["ALL_FIELDS"]) || !empty($arResult["ALL_PROPERTIES"]) || !empty($arResult["ALL_OFFER_FIELDS"]) || !empty($arResult["ALL_OFFER_PROPERTIES"]))
		{
		?>
		<div class="bx_filtren_container">
			<h5><?=GetMessage("CATALOG_COMPARE_PARAMS")?></h5>
			<ul><?
			if (!empty($arResult["ALL_FIELDS"]))
			{
				foreach ($arResult["ALL_FIELDS"] as $propCode => $arProp)
				{
					if (!isset($arResult['FIELDS_REQUIRED'][$propCode]))
					{
				?>
				<li><span onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
					<span><input type="checkbox" id="PF_<?=$propCode?>"<? echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : ''); ?>></span>
					<label for="PF_<?=$propCode?>"><?=GetMessage("IBLOCK_FIELD_".$propCode)?></label>
				</span></li>
				<?
					}
				}
			}
			if (!empty($arResult["ALL_OFFER_FIELDS"]))
			{
				foreach($arResult["ALL_OFFER_FIELDS"] as $propCode => $arProp)
				{
					?>
					<li><span onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
				<span><input type="checkbox" id="OF_<?=$propCode?>"<? echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : ''); ?>></span>
				<label for="OF_<?=$propCode?>"><?=GetMessage("IBLOCK_OFFER_FIELD_".$propCode)?></label>
			</span></li>
				<?
				}
			}
			if (!empty($arResult["ALL_PROPERTIES"]))
			{
				foreach($arResult["ALL_PROPERTIES"] as $propCode => $arProp)
				{
			?>
				<li><span onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
					<span><input type="checkbox" id="PP_<?=$propCode?>"<?echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : '');?>></span>
					<label for="PP_<?=$propCode?>"><?=$arProp["NAME"]?></label>
				</span></li>
			<?
				}
			}
			if (!empty($arResult["ALL_OFFER_PROPERTIES"]))
			{
				foreach($arResult["ALL_OFFER_PROPERTIES"] as $propCode => $arProp)
				{
			?>
				<li><span onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
					<span><input type="checkbox" id="OP_<?=$propCode?>"<? echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : ''); ?>></span>
					<label for="OP_<?=$propCode?>"><?=$arProp["NAME"]?></label>
				</span></li>
			<?
				}
			}
			?>
			</ul>
		</div>
		<?
		}
		?>
*/
?>
		<div class="compare-page__top-panel" data-entity="top-panel">
			<div class="container">
			<div class="container-compensator">
				<div class="compare-page__items">
				<div class="scrollbar-outer bg-white" data-entity="scroll-items-top">
					<div style="width:<?=(100 * ($iMinColumsCount > $iTableCol ? $iMinColumsCount : $iTableCol) / $iMinColumsCount)?>%" data-entity="compare-content">
						<div class="row row-borders flex-nowrap" data-entity="compare-items">
							<?php
							foreach ($arResult['ITEMS'] as $item)
							{
								if ($arResult['MODULES']['catalog'] && $arResult['MODULES']['sale'])
								{
								}
								else
								{
									$item['MIN_PRICE'] = $item['RS_PRICES'];
									$item['CAN_BUY'] = false; //$item['MIN_PRICE']['RATIO_PRICE'] > 0 && $item['MIN_PRICE']['RATIO_BASE_PRICE'] > 0;
								}

								$productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
									? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
									: $item['NAME'];

								$productAlt = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_ALT']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_ALT'] != ''
									? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_ALT']
									: $item['NAME'];

								$picture = (!empty($item['PREVIEW_PICTURE']['SRC']) && is_array($item['PREVIEW_PICTURE']))
									? $item['PREVIEW_PICTURE']['SRC']
									: (
										!empty($item['DETAIL_PICTURE']['SRC']) && is_array($item['DETAIL_PICTURE'])
											? $item['DETAIL_PICTURE']['SRC']
											: $this->GetFolder().'/images/no_photo.png'
									);

								$itemHasDetailUrl = isset($item['DETAIL_PAGE_URL']) && $item['DETAIL_PAGE_URL'] != '';
								?>
								<div class="col">
									<div class="product-light-container text-center text-md-left" data-entity="compare-item" data-product-id="<?=$item['PARENT_ID']?>">
										<article class="product-light h-100">
											<div class="row">
												<div class="col-3 col-md-4 d-none d-md-block">
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

												<div class="col-12 col-md-8 pl-md-0">

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
														<?php
														$arOriginalParams['SHOW_DISCOUNT_PERCENT'] = $arParams['SHOW_DISCOUNT_PERCENT'];
														$arParams['SHOW_DISCOUNT_PERCENT'] = 'Y';
														if (isset($item['MIN_PRICE']) && is_array($item['MIN_PRICE']))
														{
															$price = $item['MIN_PRICE'];
															$bCanBuy = $item['CAN_BUY'];
															include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php'));
														}
														elseif (!empty($item['PRICE_MATRIX']) && is_array($item['PRICE_MATRIX']))
														{

															$matrix = $item['PRICE_MATRIX'];
															$rows = $matrix['ROWS'];
															$rowsCount = count($rows);
															if ($rowsCount > 0)
															{
																if (count($rows) > 1)
																{
																	foreach ($rows as $index => $rowData)
																	{
																		if (empty($matrix['MIN_PRICES'][$index]))
																			continue;
																		if ($rowData['QUANTITY_FROM'] == 0)
																			$rowTitle = GetMessage('CP_TPL_CCR_RANGE_TO', array('#TO#' => $rowData['QUANTITY_TO']));
																		elseif ($rowData['QUANTITY_TO'] == 0)
																			$rowTitle = GetMessage('CP_TPL_CCR_RANGE_FROM', array('#FROM#' => $rowData['QUANTITY_FROM']));
																		else
																			$rowTitle = GetMessage(
																				'CP_TPL_CCR_RANGE_FULL',
																				array('#FROM#' => $rowData['QUANTITY_FROM'], '#TO#' => $rowData['QUANTITY_TO'])
																			);
																		$price = $matrix['MIN_PRICES'][$index];
																		$bCanBuy = $matrix['CAN_BUY'][$index];

																		include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php'));

																		unset($rowTitle);
																		break;
																	}
																	unset($index, $rowData);
																}
																else
																{
																	$price = current($matrix['MIN_PRICES']);
																	$bCanBuy = current($matrix['CAN_BUY']);

																	include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php'));
																}
															}
															unset($rowsCount, $rows, $matrix);
														}
														$arParams['SHOW_DISCOUNT_PERCENT'] = $arOriginalParams['SHOW_DISCOUNT_PERCENT'];
														?>
													</div>
												</div>
											</div>

											<a class="product-cat-del" onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($item['~DELETE_URL'])?>', event);" title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>" href="javascript:void(0)">
												<svg class="product-cat-del-icon icon icon-svg d-block"><use xlink:href="#svg-close"></use></svg>
											</a>

										</article>
									</div>
								</div>
								<?php
							}

							if ($iMinColumsCount > $iTableCol)
							{
								echo str_repeat('<div class="col compare-page__placeholder"></div>', $iMinColumsCount - $iTableCol);
							}
							?>
						</div>
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>

		<div class="compare-page__items">
			<div class="scrollbar-outer" data-entity="scroll-items">
			<div style="width:<?=(100 * ($iMinColumsCount > $iTableCol ? $iMinColumsCount : $iTableCol) / $iMinColumsCount)?>%" data-entity="compare-content">
				<div class="row row-borders flex-nowrap mt-0" data-entity="compare-items">
					<?php
					foreach ($arResult['ITEMS'] as $item)
					{
						// $bHaveOffer = false;
						if ($item['ID'] == $item['PARENT_ID'])
						{
							//$bHaveOffer = true;
						}

						$jsParams['ITEMS'][] = $item['ID'];

						if ($arResult['MODULES']['catalog'] && $arResult['MODULES']['sale'])
						{
						}
						else
						{
							$item['MIN_PRICE'] = $item['RS_PRICES'];
							$item['CAN_BUY'] = false; //$item['MIN_PRICE']['RATIO_PRICE'] > 0 && $item['MIN_PRICE']['RATIO_BASE_PRICE'] > 0;
						}

						$productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
							? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
							: $item['NAME'];

						$productAlt = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_ALT']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_ALT'] != ''
							? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_ALT']
							: $item['NAME'];

						$picture = (!empty($item['PREVIEW_PICTURE']['SRC']) && is_array($item['PREVIEW_PICTURE']))
							? $item['PREVIEW_PICTURE']['SRC']
							: (
								!empty($item['DETAIL_PICTURE']['SRC']) && is_array($item['DETAIL_PICTURE'])
									? $item['DETAIL_PICTURE']['SRC']
									: $this->GetFolder().'/images/no_photo.png'
							);

						$itemHasDetailUrl = isset($item['DETAIL_PAGE_URL']) && $item['DETAIL_PAGE_URL'] != '';
						?>
						
						<div class="col">
							<div class="product-cat-container text-center text-sm-left" data-entity="compare-item" data-product-id="<?=$item['PARENT_ID']?>">
								<div class="product-cat">
									<div class="product-cat-image-wrapper">
										<a class="product-cat-image-canvas" href="<?=$item['DETAIL_PAGE_URL']?>">
											<?php
											include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-image.php'));
											include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-labels.php'));
											?>
										</a>
										
										<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-actions.php')); ?>

										<a class="product-cat-del" onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($item['~DELETE_URL'])?>', event);" title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>" href="javascript:void(0)">
											<svg class="product-cat-del-icon icon icon-svg d-block"><use xlink:href="#svg-close"></use></svg>
										</a>
										
									</div>

									<div class="product-cat-content">

										<div class="product-cat-head">

											<?php if (strlen($item['SECTION']['SECTION_PAGE_URL']) > 0): ?>
												<div class="product-cat-parent d-none d-sm-block">
													<a href="<?=$item['SECTION']['SECTION_PAGE_URL']?>"><?=$item['SECTION']['NAME']?></a>
												</div>
											<?php endif; ?>

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
											if ($arParams['USE_VOTE_RATING'] === 'Y')
											{
												?>
												<div class="product-cat-info-container mb-2 small text-extra">
													<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/rate.php')); ?>
												</div>
												<?php
											}

											if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
											{
												?>
												<div class="product-cat-info-container d-none d-sm-block mb-0 small text-extra">
													<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/limit.php')); ?>
												</div>
												<?php
											}
											?>
										</div>

										<div class="product-cat-info-container mb-0 mb-sm-5">
											<div class="d-flex justify-content-between align-items-center">
												<div class="product-cat-price-container">
													<?php
													if (isset($item['MIN_PRICE']) && is_array($item['MIN_PRICE']))
													{
														$price = $item['MIN_PRICE'];
														$bCanBuy = $item['CAN_BUY'];
														include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php'));
													}
													elseif (!empty($item['PRICE_MATRIX']) && is_array($item['PRICE_MATRIX']))
													{

														$matrix = $item['PRICE_MATRIX'];
														$rows = $matrix['ROWS'];
														$rowsCount = count($rows);
														if ($rowsCount > 0)
														{
															if (count($rows) > 1)
															{
																foreach ($rows as $index => $rowData)
																{
																	if (empty($matrix['MIN_PRICES'][$index]))
																		continue;
																	if ($rowData['QUANTITY_FROM'] == 0)
																		$rowTitle = GetMessage('CP_TPL_CCR_RANGE_TO', array('#TO#' => $rowData['QUANTITY_TO']));
																	elseif ($rowData['QUANTITY_TO'] == 0)
																		$rowTitle = GetMessage('CP_TPL_CCR_RANGE_FROM', array('#FROM#' => $rowData['QUANTITY_FROM']));
																	else
																		$rowTitle = GetMessage(
																			'CP_TPL_CCR_RANGE_FULL',
																			array('#FROM#' => $rowData['QUANTITY_FROM'], '#TO#' => $rowData['QUANTITY_TO'])
																		);
																	$price = $matrix['MIN_PRICES'][$index];
																	$bCanBuy = $matrix['CAN_BUY'][$index];

																	include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php'));

																	unset($rowTitle);
																	break;
																}
																unset($index, $rowData);
															}
															else
															{
																$price = current($matrix['MIN_PRICES']);
																$bCanBuy = current($matrix['CAN_BUY']);

																include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php'));
															}
														}
														unset($rowsCount, $rows, $matrix);

													}
													?>
												</div>
												<div class="product-cat-buttons d-none d-sm-block">
													<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/actions.php')); ?>
												</div>
											</div>
										</div>


										<div class="product-cat-body">


										</div>
									</div>
								</div>
							</div>
						</div>
					<?php
					}

					if ($iMinColumsCount > $iTableCol)
					{
						echo str_repeat('<div class="col compare-page__placeholder"></div>', $iMinColumsCount - $iTableCol);
					}
					?>
				</div>
			</div>
			</div>
		</div>

		<div class="compare-page__props">
<?/*
			<div class="scroll-wrapper scrollbar-hidden">
*/?>
			<div class="scroll-content scrollbar-hidden" data-entity="scroll-props">
				<div style="width:<?=(100 * ($iMinColumsCount > $iTableCol ? $iMinColumsCount : $iTableCol) / $iMinColumsCount)?>%" data-entity="compare-content">
					<?php
					if (!empty($arResult['SHOW_FIELDS']) || !empty($arResult['SHOW_OFFER_FIELDS']))
					{
						$index = 0;
						?>
						<table class="table table-sm table-fixed w-100 compare-page__table" data-entity="compare-table">
							<tbody>
							<?php
							foreach ($arResult['SHOW_FIELDS'] as $sPropCode => $arProp)
							{
								if (in_array($sPropCode, $arFieldsHide))
								{
									continue;
								}
								$showRow = true;
								if (!isset($arResult['FIELDS_REQUIRED'][$sPropCode]) || $arResult['DIFFERENT'])
								{
									$arCompare = array();
									foreach($arResult['ITEMS'] as &$item)
									{
										$arPropertyValue = $item['FIELDS'][$sPropCode];
										if (is_array($arPropertyValue))
										{
											sort($arPropertyValue);
											$arPropertyValue = implode(' / ', $arPropertyValue);
										}
										$arCompare[] = $arPropertyValue;
									}
									unset($item);
									$showRow = (count(array_unique($arCompare)) > 1);
								}
								if ($showRow)
								{

									?>
									<tr class="small text-extra<?=($index %2 == 0 ? ' table-active' : '')?>" data-entity="compare-prop-name">
										<td>><?=getMessage('IBLOCK_FIELD_'.$sPropCode)?></td>
										<td></td>
									</tr>
									<tr>
										<?php
										foreach ($arResult['ITEMS'] as &$item)
										{
											?>
											<td><?echo $item['FIELDS'][$sPropCode]?></td>
											<?php
										}

										if ($iMinColumsCount > $iTableCol)
										{
											echo str_repeat('<td class="compare-page__placeholder"></td>', $iMinColumsCount - $iTableCol);
										}
										?>
									</tr>
									<?php
									$index++;
								}
							}

							foreach ($arResult['SHOW_OFFER_FIELDS'] as $sPropCode => $arProp)
							{
								if (in_array($sPropCode, $arFieldsHide))
								{
									continue;
								}
								$showRow = true;
								if ($arResult['DIFFERENT'])
								{
									$arCompare = array();
									foreach ($arResult['ITEMS'] as &$item)
									{
										$Value = $item['OFFER_FIELDS'][$sPropCode];
										if (is_array($Value))
										{
											sort($Value);
											$Value = implode(' / ', $Value);
										}
										$arCompare[] = $Value;
									}
									unset($item);
									$showRow = (count(array_unique($arCompare)) > 1);
								}
								if ($showRow)
								{
									?>
									<tr class="small text-extra<?=($index %2 == 0 ? ' table-active' : '')?>" data-entity="compare-prop-name">
										<td><?=getMessage('IBLOCK_OFFER_FIELD_'.$sPropCode)?></td>
										<td></td>
									</tr>
									<tr>
										<?php
										foreach ($arResult['ITEMS'] as &$item)
										{
											?>
											<td>
												<?php
												echo (is_array($item['OFFER_FIELDS'][$sPropCode])
													? implode('/ ', $item['OFFER_FIELDS'][$sPropCode])
													: $item['OFFER_FIELDS'][$sPropCode]);
												?>
											</td>
											<?php
										}

										if ($iMinColumsCount > $iTableCol)
										{
											echo str_repeat('<td class="compare-page__placeholder"></td>', $iMinColumsCount - $iTableCol);
										}
										?>
									</tr>
									<?php
									$index++;
								}
							}
							?>
							</tbody>
						</table>
						<?php
					}

					if (!empty($arResult['PROPERTIES_GROUPS']) && (!empty($arResult['SHOW_PROPERTIES']) || !empty($arResult['SHOW_OFFER_PROPERTIES'])))
					{
						foreach ($arResult['PROPERTIES_GROUPS'] as $key => $arGroup)
						{
							if ($arGroup['IS_SHOW'])
							{
								?>
								<div class="compare-page__group">
<?/*
									<div class="compare-page__group-head position-absolute" data-toggle="collapse" data-target="#<?=$itemIds['PROP_GROUP'].$arGroup['ID']?>" aria-expanded="true" aria-controls="<?=$itemIds['PROP_GROUP'].$arGroup['ID']?>">
										<div class="compare-page__group-name">
*/?>
									<div class="compare-page__group-name position-absolute" data-toggle="collapse" data-target="#<?=$itemIds['PROP_GROUP'].$arGroup['ID']?>" aria-expanded="true" aria-controls="<?=$itemIds['PROP_GROUP'].$arGroup['ID']?>">
										<?=isset($arGroup['NAME']) ? $arGroup['NAME'] : getMessage('RS_MM_BCCR_CATALOG_NOT_GRUPED_PROPS')?><?php
										?><svg class="collapsed__icon icon-svg"><use xlink:href="#svg-chevron-up"></use></svg>
									</div>
									<div class="compare-page__group-name invisible">
											<?=isset($arGroup['NAME']) ? $arGroup['NAME'] : getMessage('RS_MM_BCCR_CATALOG_NOT_GRUPED_PROPS')?><?php
											?><svg class="collapsed__icon icon-svg"><use xlink:href="#svg-chevron-up"></use></svg>
									</div>
									<div class="collapse show" id="<?=$itemIds['PROP_GROUP'].$arGroup['ID']?>">
										<table class="table table-sm table-fixed w-100 compare-page__table" data-entity="compare-table">
											<tbody>
												<?php
												if (!empty($arGroup['BINDS']))
												{
													$index = 0;
													foreach ($arGroup['BINDS'] as $iPropId => $sPropCode)
													{
														if (
															isset($arResult['SHOW_PROPERTIES'][$sPropCode])
															&& $arResult['SHOW_PROPERTIES'][$sPropCode]['ID'] == $iPropId
															&& $arResult['SHOW_PROPERTIES'][$sPropCode]['IS_SHOW']
														) {
															?>
															<tr class="small text-extra<?=($index %2 == 0 ? ' table-active' : '')?>" data-entity="compare-prop-name">
																<td>
																	<span class="compare-page__prop-name position-absolute">
																	<?=$arResult['SHOW_PROPERTIES'][$sPropCode]['NAME']?>
																	<?php if ($arResult['SHOW_PROPERTIES'][$sPropCode]['FILTER_HINT'] <> ''): ?>
																		<?php
																		$arPopoverOptions = array(
																			'trigger' => 'focus',
																			'html' => true,
																			'title' => $arResult['SHOW_PROPERTIES'][$sPropCode]['NAME'],
																			'content' => $arResult['SHOW_PROPERTIES'][$sPropCode]['FILTER_HINT'],
																		);
																		?>
																		<button class="compare-page__prop-hint hint" id="item_title_hint_<?=$arResult['SHOW_PROPERTIES'][$sPropCode]['ID']?>" data-popover="item_title_hint_<?=$arResult['SHOW_PROPERTIES'][$sPropCode]['ID']?>?>" data-popover-options="<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($arPopoverOptions))?>">?</button>
																	<?php endif ?>
																	</span>
																	<span class="compare-page__prop-name invisible d-block">
																		<?=$arResult['SHOW_PROPERTIES'][$sPropCode]['NAME']?>
																		<i class="compare-page__prop-hint hint">?</i>
																	</span>
																</td>
																<?php
																echo str_repeat('<td></td>', $iTableCol - 1);
																if ($iMinColumsCount > $iTableCol)
																{
																	echo str_repeat('<td class="compare-page__placeholder"></td>', $iMinColumsCount - $iTableCol);
																}
																?>
															</tr>
															<tr class="font-size-sm<?=($index %2 == 0 ? ' table-active' : '')?>">
																<?php
																foreach($arResult['ITEMS'] as &$item)
																{
																	?>
																	<td>
																		<?php
																		if (
																			$arResult['SHOW_PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == \Bitrix\Iblock\PropertyTable::TYPE_LIST
																			&& $arResult['SHOW_PROPERTIES'][$sPropCode]['LIST_TYPE'] == \Bitrix\Iblock\PropertyTable::CHECKBOX
																		)
																		{
																			if ($item['DISPLAY_PROPERTIES'][$sPropCode]['VALUE'])
																			{
																				?><svg class="text-success icon-svg"><use xlink:href="#svg-check"></use></svg><?php
																			}
																			else
																			{
																				?><svg class="text-danger icon-svg"><use xlink:href="#svg-times-solid"></use></svg><?php
																			}
																		}
																		else
																		{
																			if ($item['DISPLAY_PROPERTIES'][$sPropCode]['VALUE'])
																			{
																				echo (is_array($item['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])
																					? implode('/ ', $item['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])
																					: $item['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
																			}
																			else
																			{
																				echo '&mdash;';
																			}
																		}
																		?>
																	</td>
																	<?php
																}
																unset($item);

																if ($iMinColumsCount > $iTableCol)
																{
																	echo str_repeat('<td class="compare-page__placeholder"></td>', $iMinColumsCount - $iTableCol);
																}
																?>
															</tr>
															<?php
															$index++;
														}

														if (
															isset($arResult['SHOW_OFFER_PROPERTIES'][$sPropCode])
															&& $arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['ID'] == $iPropId
															&& $arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['IS_SHOW']
														) {
															?>
															<tr class="small text-extra<?=($index %2 == 0 ? ' table-active' : '')?>" data-entity="compare-prop-name">
																<td>
																	<span class="compare-page__prop-name position-absolute">
																	<?=$arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['NAME']?>
																	<?php if ($arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['FILTER_HINT'] <> ''): ?>
																		<?php
																		$arPopoverOptions = array(
																			'trigger' => 'focus',
																			'html' => true,
																			'title' => $arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['NAME'],
																			'content' => $arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['FILTER_HINT'],
																		);
																		?>
																		<button class="compare-page__prop-hint hint" id="item_title_hint_<?=$arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['ID']?>" data-popover="item_title_hint_<?=$arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['ID']?>?>" data-popover-options="<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($arPopoverOptions))?>">?</button>
																	<?php endif ?>
																	</span>
																	<span class="compare-page__prop-name invisible d-block">
																		<?=$arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['NAME']?>
																		<i class="compare-page__prop-hint hint">?</i>
																	</span>
																</td>
																<?php
																echo str_repeat('<td></td>', $iTableCol - 1);
																if ($iMinColumsCount > $iTableCol)
																{
																	echo str_repeat('<td class="compare-page__placeholder"></td>', $iMinColumsCount - $iTableCol);
																}
																?>
															</tr>
															<tr  class="font-size-sm<?=($index %2 == 0 ? ' table-active' : '')?>">
																<?php
																foreach ($arResult['ITEMS'] as &$item)
																{
																	?>
																	<td>
																		<?php
																		if (
																			$arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == \Bitrix\Iblock\PropertyTable::TYPE_LIST
																			&& $arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['LIST_TYPE'] == \Bitrix\Iblock\PropertyTable::CHECKBOX
																		)
																		{
																			if ($item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['VALUE'])
																			{
																				?><svg class="text-success icon-svg"><use xlink:href="#svg-check"></use></svg><?php
																			}
																			else
																			{
																				?><svg class="text-danger icon-svg"><use xlink:href="#svg-times-solid"></use></svg><?php
																			}
																		}
																		else
																		{
																			if ($item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['VALUE'])
																			{
																				echo (is_array($item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])
																					? implode('/ ', $item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])
																					: $item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
																			}
																			else
																			{
																				echo '&mdash;';
																			}
																		}
																		?>
																	</td>
																	<?php
																}

																if ($iMinColumsCount > $iTableCol)
																{
																	echo str_repeat('<td class="compare-page__placeholder"></td>', $iMinColumsCount - $iTableCol);
																}
																?>
															</tr>
															<?php
															$index++;
														}
													}
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
								<?php
							}
						}
						unset($key, $arGroup);
					}
					?>
				</div>
			</div>
<?/*
			</div>
*/?>
		</div>
	</div>

	<?php
else:
	ShowNote(GetMessage("CATALOG_COMPARE_LIST_EMPTY"));
endif;

	$APPLICATION->IncludeComponent(
		'bitrix:catalog.item',
		'catalog',
		array(),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
	$APPLICATION->IncludeComponent(
		'bitrix:catalog.item',
		'light',
		array(),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
	?>


<?php
if ($isAjax)
{
	die();
}
else
{
	$frame->end();
}

if ($arParams['USE_FAVORITE'])
{
	$jsParams['USE_FAVORITE'] = $arParams['USE_FAVORITE'];
}
?>
<script>var <?=$obName?> = new BX.Iblock.Catalog.CompareClass(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);</script>
<?php
$layout->end();
unset($arResult['ITEMS']);
?>
</div>
