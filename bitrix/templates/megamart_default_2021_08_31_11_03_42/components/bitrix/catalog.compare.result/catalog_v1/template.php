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

$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_ECONOMY_INFO2'] = Loc::getMessage('RS_MM_BCCR_CATALOG_ECONOMY_INFO2');
$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_ADD_TO_BASKET'] = '<svg class="icon-cart icon-svg"><use xlink:href="#svg-cart"></use></svg>';

$buttonSizeClass = 'btn-sm btn-rounded';

$mainId = $this->GetEditAreaId('compare');
$itemIds = array(
	'ID' => $mainId,
	'TABLE' => $mainId.'table',
);

$jsParams = array(
	'ITEMS' => array(
		// 'item' => 'item',
	),
	'CONFIG' => array(
		'NAME' => $arParams['NAME'],
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'TEMPLATE_FOLDER' => $templateFolder,
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

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);

$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addData('SECTION_MAIN_ATTRIBUTES', 'id="'.$itemIds['ID'].'"');

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
<div class="p-4">


<div class="mb-6 clearfix">
	<span class="btn px-0 my-2"><?=GetMessage("CATALOG_SHOWN_CHARACTERISTICS")?>:</span>
	<div class="d-inline-block">
		<?php if (!$arResult["DIFFERENT"]): ?>
			<span class="btn btn-primary my-2"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></span>
			<a class="btn btn-outline-secondary-primary my-2" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=Y'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a>
		<?php else: ?>
			<a class="btn btn-outline-secondary-primary my-2" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=N'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a>
			<span class="btn btn-primary my-2"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></span>
		<?php endif; ?>
	</div>
	<a class="btn btn-link px-0 my-2 float-md-right" href="<?=$arResult['COMPARE_URL_TEMPLATE'].$arParams['ACTION_VARIABLE'].'=COMPARE_CLEAR'?>" onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arResult['COMPARE_URL_TEMPLATE'].$arParams['ACTION_VARIABLE'].'=COMPARE_CLEAR')?>');"><?=GetMessage('RS_MM_BCCR_CATALOG_COMPARE_CLEAR')?></a>
</div>

<div class="position-relative" id="<?=$itemIds['TABLE']?>" style="opacity:0">
<?php
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

	$iMinColumsCount = 3;
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
	$sTableHTML = '';
	?>
	<div class="row">
		<div class="col col-6 col-sm-4 col-lg-3<?/*pr-0*/?>" data-entity="column-names">
			<table class="table w-100 text-right compare__names">
				<thead>
				<tr>
					<td class="border-0"></td>

					<?php ob_start(); ?>
					<thead>
					<tr>
					<?php foreach ($arResult['ITEMS'] as $item): ?>
						<?php
						// $bHaveOffer = $item['ID'] != $item['PARENT_ID'];

						//$this->AddEditAction($item['ID'], $item['EDIT_LINK'], $strEdit);
						//$this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], $strDelete, $arDeleteParams);
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
						<td class="p-0 text-left border border-body-bg">
							<div class="product-cat-container" data-entity="compare-item">
								<div class="product-cat">
									<div class="product-cat-image-wrapper">
										<a class="product-cat-image-canvas" href="<?=$item['DETAIL_PAGE_URL']?>">
											<?php
											include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-image.php'));
											include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-labels.php'));
											?>
										</a>
										<div class="product-cat-image-action">
											<a class="product-cat-del" onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($item['~DELETE_URL'])?>', event);" title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>" href="javascript:void(0)">
												<svg class="product-cat-del-icon icon icon-svg"><use xlink:href="#svg-close"></use></svg>
											</a>
										</div>
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
											<div class="product-cat-info-container product-cat-actions-container">
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
						</td>
					<?php endforeach; ?>
					<?php
					if ($iMinColumsCount > $iTableCol)
					{
						echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
					}
					?>
					</tr>
					</thead>
					<?php $sTableHTML .= ob_get_clean() ?>
				</tr>
				</thead>
				<tbody>
					<tr><td class="border-top-0"></td></tr>
					<?php ob_start(); ?>
					<tr>
						<td colspan="<?=($iMinColumsCount > $iTableCol ? $iMinColumsCount : $iTableCol)?>">
							<div class="compare__scroll" data-entity="scroll"></div>
						</td>
					</tr>
					<?php $sTableHTML .= ob_get_clean() ?>
				<?php
				if (!empty($arResult['SHOW_FIELDS']))
				{
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

							?><tr><?
								?><td><?=getMessage('IBLOCK_FIELD_'.$sPropCode)?></td><?
								ob_start();
									?><tr><?
									foreach ($arResult['ITEMS'] as &$item)
									{
										?><td><?echo $item['FIELDS'][$sPropCode]?></td><?
									}
									if ($iMinColumsCount > $iTableCol)
									{
										echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
									}
									?></tr><?
								$sTableHTML .= ob_get_clean();
								unset($item);
							?></tr><?
						}
					}
				}

				if (!empty($arResult['SHOW_OFFER_FIELDS']))
				{
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
							?><tr><?
								?><td><?=getMessage('IBLOCK_OFFER_FIELD_'.$sPropCode)?></td><?
								ob_start();
									?><tr><?
									foreach ($arResult['ITEMS'] as &$item)
									{
										?><td><?
											echo (is_array($item['OFFER_FIELDS'][$sPropCode])? implode('/ ', $item['OFFER_FIELDS'][$sPropCode]): $item['OFFER_FIELDS'][$sPropCode]);
										?></td><?
									}
									if ($iMinColumsCount > $iTableCol)
									{
										echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
									}
									?></tr><?
								$sTableHTML .= ob_get_clean();
								unset($item);
							?></tr><?
						}
					}
				}

				if (!empty($arResult['PROPERTIES_GROUPS']) && (!empty($arResult['SHOW_PROPERTIES']) || !empty($arResult['SHOW_OFFER_PROPERTIES'])))
				{
					$first = true;
					foreach ($arResult['PROPERTIES_GROUPS'] as $key => $arGroup)
					{
						if ($arGroup['IS_SHOW'])
						{
							?><tr><th class="pt-6 text-left<?=($first ? ' border-top-0' : '')?>"><?= isset($arGroup['NAME']) ? $arGroup['NAME'] : getMessage('RS_MM_BCCR_CATALOG_NOT_GRUPED_PROPS')?></th></tr><?
							$sTableHTML .= '<tr><th class="pt-6'.($first ? ' border-top-0' : '').'" colspan="'.($iMinColumsCount > $iTableCol ? $iMinColumsCount : $iTableCol).'"></th></tr>';
							$first = false;

							if (!empty($arGroup['BINDS']))
							{
								foreach ($arGroup['BINDS'] as $iPropId => $sPropCode)
								{
									if (
										isset($arResult['SHOW_PROPERTIES'][$sPropCode])
										&& $arResult['SHOW_PROPERTIES'][$sPropCode]['ID'] == $iPropId
										&& $arResult['SHOW_PROPERTIES'][$sPropCode]['IS_SHOW']
									) {
										?><tr><?
											?><td><?=$arResult['SHOW_PROPERTIES'][$sPropCode]['NAME']?></td><?
											ob_start();
												?><tr><?
												foreach($arResult['ITEMS'] as &$item)
												{
													?><td><?
														echo (is_array($item['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])? implode('/ ', $item['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']): $item['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
													?></td><?
												}
												if ($iMinColumsCount > $iTableCol)
												{
													echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
												}
												?></tr><?
											$sTableHTML .= ob_get_clean();
											unset($item);
										?></tr><?
									}

									if (
										isset($arResult['SHOW_OFFER_PROPERTIES'][$sPropCode])
										&& $arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['ID'] == $iPropId
										&& $arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['IS_SHOW']
									) {
										?><tr><?
											?><td><?=$arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['NAME']?></td><?
											ob_start();
												?><tr><?
												foreach ($arResult['ITEMS'] as &$item)
												{
													?><td><?
														echo (is_array($item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])? implode('/ ', $item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']): $item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
													?></td><?
												}
												if ($iMinColumsCount > $iTableCol)
												{
													echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
												}
												?></tr><?
											$sTableHTML .= ob_get_clean();
											unset($item);
										?></tr><?
									}
								}
							}
						}
					}
					unset($key, $arGroup);
				}
				?>
				</tbody>
			</table>
		</div>
		<div class="col col-6 col-sm-8 col-lg-9<?/*pl-0*/?>" data-entity="column-items">
			<div class="scrollbar-inner">
				<table class="table text-left compare__table" data-entity="items-table"><?=$sTableHTML?></table>
			</div>
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
?>
</div>
</div>

<?php
if ($isAjax)
{
	?><script>BX.onCustomEvent('OnCompareChange');</script><?
	die();
}
else
{
	$frame->end();
}
?>
<script>var <?=$obName?> = new BX.Iblock.Catalog.CompareClass(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);</script>
<?php
$layout->end();
unset($arResult['ITEMS']);
