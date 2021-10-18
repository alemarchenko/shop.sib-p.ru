<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

foreach ($arResult['ITEMS'] as $arItem) {
	if (count($arItem["VALUES"]) == 0)
		continue;
	
	if (isset($arItem["PRICE"])) {
		if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
			continue;
	}
	$arResult['HAS_ITEMS'] = true;
	break;
}

if (!isset($arResult['HAS_ITEMS'])) return;

if (
    is_array($arParams['SCROLL_PROPS']) && count($arParams['SCROLL_PROPS']) > 0
    || is_array($arParams['OFFER_SCROLL_PROPS']) && count($arParams['OFFER_SCROLL_PROPS']) > 0
) {
	$this->addExternalJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery.scrollbar/jquery.scrollbar.js');
	$this->addExternalJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery-mousewheel/jquery.mousewheel.js');
}
$arPropHaveChecked = array();

$arHiddenProps = array($arParams['BRAND_PROP']);

$layout = new \Redsign\MegaMart\Layouts\Section();
$layout
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addData('SECTION_ATTRIBUTES', 'id="'.$arParams['TARGET_ID'].'-filter"')
	->addModifier('block');

$colPropClasses = 'bx-filter-parameters-box js-filter-box ';
if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL")
{
	$layout
		->addModifier('bg-white');

	$colPropClasses.= ' ';
}
else
{
	$layout
		->addModifier('white');

	$colPropClasses.= 'col-12 ';
}

$layout->start();
?>
<div class="bx-filter <?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL") echo "bx-filter-horizontal"?>">
	<?php if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"): ?>
		<h6 class="bx-filter-title js-filter-title"><?=Loc::getMessage('RS_MM_BCSF_TITLE')?></h6>
	<?php endif; ?>
	<div class="bx-filter-section">
		<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
			<?php 
			foreach ($arResult["HIDDEN"] as $arItem):
				if ($arItem['CONTROL_NAME'] === 'hide_not_available') continue;
			?>
				<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
			<?php endforeach; ?>
			<?php if ($arParams['FILTER_VIEW_MODE'] == 'VERTICAL'): ?>
				<div class="row">
			<?php else: ?>
				<div class="bx-filter-params">
			<?php endif; ?>

			<?php if ($arParams['FILTER_USE_HIDE_NOT_AVAILABLE'] === 'Y'): ?>
				<div class="<?=$colPropClasses?><?=$boxSubClasses?><?=($arParams['FILTER_VIEW_MODE'] == 'VERTICAL' ? 'bx-active' : '')?>">
					<span class="bx-filter-container-modef"></span>
					<div class="bx-filter-parameters-box-title" onclick="smartFilter.hideFilterProps(this)">
						<span>
							<span class="js-filter__prop-name"><?=Loc::getMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE')?></span>
							<span class="bx-filter-parameters-box-angle"><?
								?><svg class="icon-svg"><use data-role="prop_angle" xlink:href="#svg-chevron-<?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL"):?>up<?else:?>down<?endif;?>"></use></svg><?
							?></span>
						</span>
					</div>
					
					<input type="hidden" name="hide_not_available">
					<div class="bx-filter-block" data-role="bx_filter_block">
						<div class="row bx-filter-parameters-box-container">
							<div class="col">
								<div class="bx-filter-input-checkbox pt-0" data-entity="filter-value">
									<div class="checkbox bmd-custom-checkbox">
										<label class="bx-filter-param-label">
											<input 
												type="checkbox" 
												onclick="smartFilter.hideNotAvailable(this)" 
												value="<?=Loc::getMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE_YES')?>"
												data-text-value="<?=Loc::getMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE_YES')?>"
												<?php if ($arParams['FILTER_HIDE_NOT_AVAILABLE'] === 'Y'):?> checked<?php endif; ?>
											/>
											<span class="bx-filter-param-text" title="<?=Loc::getMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE_YES')?>">
											<?=Loc::getMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE_YES')?>
											</span>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>	
				</div>
			<?php endif; ?>

				<?php foreach ($arResult["ITEMS"] as $key=>$arItem) //prices
				{
					$key = $arItem["ENCODED_ID"];
					if (isset($arItem["PRICE"])):
						if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
							continue;

						$step_num = 4;
						$step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / $step_num;
						$prices = array();
						if (Bitrix\Main\Loader::includeModule("currency"))
						{
							for ($i = 0; $i < $step_num; $i++)
							{
								$prices[$i] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $arItem["VALUES"]["MIN"]["CURRENCY"], false);
							}
							$prices[$step_num] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MAX"]["VALUE"], $arItem["VALUES"]["MAX"]["CURRENCY"], false);
						}
						else
						{
							$precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
							for ($i = 0; $i < $step_num; $i++)
							{
								$prices[$i] = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $precision, ".", "");
							}
							$prices[$step_num] = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
						}
						$id = 'bx-filter-id-'.$arItem["ID"];

						$boxSubClasses = ' ';
						if ($arItem['USING'] == 'Y')
							$boxSubClasses.= 'bx-filter-using ';
						?>
						<div class="<?=$colPropClasses?><?=$boxSubClasses?><?=($arParams['FILTER_VIEW_MODE'] == 'VERTICAL' ? 'bx-active' : '')?>">
							<span class="bx-filter-container-modef"></span>
							<div class="bx-filter-parameters-box-title" onclick="smartFilter.hideFilterProps(this)">
								<span>
									<span class="js-filter__prop-name"><?=$arItem["NAME"]?></span>
									<span class="bx-filter-parameters-box-angle"><?
										?><svg class="icon-svg"><use data-role="prop_angle" xlink:href="#svg-chevron-<?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL"):?>up<?else:?>down<?endif;?>"></use></svg><?
									?></span>
								</span>
							</div>
							<?php if ($arParams['FILTER_VIEW_MODE'] == 'HORIZONTAL'): ?>
								<span class="bx-filter-parameters-box-drop-all js-filter-box-drop-all"><svg class="icon-svg"><use xlink:href="#svg-close"></use></svg></span>
							<?php endif; ?>
							<div
							 class="bx-filter-block" <?
							 ?>data-role="bx_filter_block">
								<div class="row bx-filter-parameters-box-container">
									<div class="col-6 bx-filter-parameters-box-container-block">
										<div class="bx-filter-input-container">
											<input
												class="min-price form-control"
												type="number"
												name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
												data-chosed-prefix="RS_MM_BCSF_WE_CHOSE_NUM_FROM"
												data-chosed-postfix=""
<?/*
												min="<?=(float)$arItem["VALUES"]["MIN"]["VALUE"];?>"
												max="<?=(float)$arItem["VALUES"]["MAX"]["VALUE"];?>"
*/?>
												size="5"
												onkeyup="smartFilter.keyup(this)"
												<?php
												if (Bitrix\Main\Loader::includeModule("currency"))
												{
													$placeholderValue = CCurrencyLang::CurrencyFormat($arItem['VALUES']['MIN']['VALUE'], $arItem["VALUES"]["MIN"]["CURRENCY"], false);
												}
												else
												{
													$placeholderValue = $arItem['VALUES']['MIN']['VALUE'];
												}
												?>
												placeholder="<?=$placeholderValue?>"
											/>
										</div>
									</div>
									<div class="col-6 bx-filter-parameters-box-container-block">
										<div class="bx-filter-input-container">
											<input
												class="max-price form-control"
												type="number"
												name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
												data-chosed-prefix="RS_MM_BCSF_WE_CHOSE_NUM_TO"
												data-chosed-postfix=""
<?/*
												min="<?=(float)$arItem["VALUES"]["MIN"]["VALUE"];?>"
												max="<?=(float)$arItem["VALUES"]["MAX"]["VALUE"];?>"
*/?>
												size="5"
												onkeyup="smartFilter.keyup(this)"
												<?php
												if (Bitrix\Main\Loader::includeModule("currency"))
												{
													$placeholderValue = CCurrencyLang::CurrencyFormat($arItem['VALUES']['MAX']['VALUE'], $arItem["VALUES"]["MAX"]["CURRENCY"], false);
												}
												else
												{
													$placeholderValue = $arItem['VALUES']['MAX']['VALUE'];
												}
												?>
												placeholder="<?=$placeholderValue?>"
											/>
										</div>
									</div>

									<div class="col">
										<div class="bx-ui-slider-track-container">
											<div class="bx-ui-slider-track" id="drag_track_<?=$key?>">
												<?for($i = 0; $i <= $step_num; $i++):?>
												<div class="bx-ui-slider-part p<?=$i+1?>"><span><?=$prices[$i]?></span></div>
												<?endfor;?>
												<div class="bx-ui-slider-pricebar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
												<div class="bx-ui-slider-pricebar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
												<div class="bx-ui-slider-pricebar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
												<div class="bx-ui-slider-range" id="drag_tracker_<?=$key?>"  style="left: 0; right: 0;">
													<a class="bx-ui-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
													<a class="bx-ui-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?
						$arJsParams = array(
							"leftSlider" => 'left_slider_'.$key,
							"rightSlider" => 'right_slider_'.$key,
							"tracker" => "drag_tracker_".$key,
							"trackerWrap" => "drag_track_".$key,
							"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
							"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
							"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
							"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
							"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
							"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
							"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
							"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
							"precision" => $precision,
							"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
							"colorAvailableActive" => 'colorAvailableActive_'.$key,
							"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
						);
						?><script type="text/javascript">
							BX.ready(function(){
								window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
							});
						</script>
						<?php
						if ($arItem["VALUES"]["MIN"]["HTML_VALUE"])
						{
							$arPropHaveChecked[$arrayKey]["MIN"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
						}
						if ($arItem["VALUES"]["MAX"]["HTML_VALUE"])
						{
							$arPropHaveChecked[$arrayKey]["MAX"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
						}
						?>
					<?endif;
				}

				//not prices
				foreach ($arResult["ITEMS"] as $key=>$arItem)
				{
					if(
						empty($arItem["VALUES"])
						|| isset($arItem["PRICE"])
					)
						continue;

					if (
						$arItem["DISPLAY_TYPE"] == "A"
						&& (
							$arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
						)
					)
						continue;

					$id = 'bx-filter-id-'.$arItem["ID"];

					$boxSubClasses = ' ';
					if ($arItem['USING'] == 'Y')
						$boxSubClasses.= 'bx-filter-using ';
					?>
					<div class="<?=$colPropClasses?><?=$boxSubClasses?><?if ($arItem["DISPLAY_EXPANDED"] == "Y" && $arParams['FILTER_VIEW_MODE'] == 'VERTICAL'):?>bx-active<?endif?>"<?if(!empty($arItem['CODE']) && in_array($arItem['CODE'], $arHiddenProps)):?> style="display:none"<?endif?>>
						<span class="bx-filter-container-modef"></span>
						<div class="bx-filter-parameters-box-title" onclick="smartFilter.hideFilterProps(this)">
							<span class="bx-filter-parameters-box-hint">
								<span class="js-filter__prop-name"><?=$arItem["NAME"]?></span>
								<?if ($arItem["FILTER_HINT"] <> ""):?>
									<?php
									$arPopoverOptions = array(
										'placement' => 'top',
										'trigger' => 'focus',
										'html' => true,
										'content' => $arItem["FILTER_HINT"],
									);
									?>
									<button class="hint" id="item_title_hint_<?=$arItem['ID']?>" data-popover="item_title_hint_<?=$arItem['ID']?>" data-popover-options="<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($arPopoverOptions))?>">?</button>
								<?endif?>
								<?php
								if ($arParams['FILTER_VIEW_MODE'] == 'HORIZONTAL')
								{
									echo '<span class="bx-filter-parameters-box-finded-count js-filter-finded-count">'.((float)$arItem['VALUES_COUNT'] > 0 ? '('.$arItem['VALUES_COUNT'].')' : '').'</span>';
								}
								?>
							<span class="bx-filter-parameters-box-angle"><?
								?><svg class="icon-svg"><use data-role="prop_angle" xlink:href="#svg-chevron-<?if ($arItem["DISPLAY_EXPANDED"]== "Y" && $arParams["FILTER_VIEW_MODE"] == "VERTICAL"):?>up<?else:?>down<?endif;?>"></use></svg><?
							?></span>
							</span>
						</div>
						<?php if ($arParams['FILTER_VIEW_MODE'] == 'HORIZONTAL'): ?>
							<span class="bx-filter-parameters-box-drop-all js-filter-box-drop-all"><svg class="icon-svg"><use xlink:href="#svg-close"></use></svg></span>
						<?php endif; ?>
						<div <?
							?>class="bx-filter-block" <?
							?>data-role="bx_filter_block">
							<?
							$isSearchable = $isScrolable = $isColor = $isButton = false;

							if (!in_array($arItem['DISPLAY_TYPE'], array('A', 'B', 'G', 'P', 'R', 'U')))
							{
								// search and scroll
								if (
									is_array($arParams["SEARCH_PROPS"]) && in_array($arItem["CODE"], $arParams["SEARCH_PROPS"]) ||
									is_array($arParams["OFFER_SEARCH_PROPS"]) && in_array($arItem["CODE"], $arParams["OFFER_SEARCH_PROPS"])
								) {
									$isSearchable = $isScrolable = true;
								} elseif (
									is_array($arParams["SCROLL_PROPS"]) && in_array($arItem["CODE"], $arParams["SCROLL_PROPS"]) ||
									is_array($arParams["OFFER_SCROLL_PROPS"]) && in_array($arItem["CODE"], $arParams["OFFER_SCROLL_PROPS"])
								) {
									$isScrolable = true;
								}

								// button
								if (
									is_array($arParams["OFFER_TREE_BTN_PROPS"]) &&
									in_array($arItem["CODE"], $arParams["OFFER_TREE_BTN_PROPS"])
								) {
									$isButton = true;
								}
							}
							?>

							<div class="row bx-filter-parameters-box-container">

							<?php
							if (count($arItem["VALUES"]) > 5)
							{
								if ($isSearchable)
								{
									?>
                                    <div class="bx-filter-search form-group col-12">
                                        <input type="text" class="form-control" placeholder="<?=GetMessage('RS_MM_BCSF_CATALOG_SEARCH')?>">
                                    </div>
									<?php
								}

								if ($isScrolable)
								{
									?>
                                    <div class="col pl-0">
                                        <div class="bx-filter-scroll scrollbar-inner">
									<?php
								}
							}

							$arCur = current($arItem["VALUES"]);
							switch ($arItem["DISPLAY_TYPE"])
							{
								case "A"://NUMBERS_WITH_SLIDER
									?>
									<div class="col-6 bx-filter-parameters-box-container-block">
										<div class="bx-filter-input-container">
											<input
												class="min-price form-control"
												type="number"
												name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
												data-chosed-prefix="RS_MM_BCSF_WE_CHOSE_NUM_FROM"
												data-chosed-postfix=""
<?/*
												min="<?=(float)$arItem["VALUES"]["MIN"]["VALUE"];?>"
												max="<?=(float)$arItem["VALUES"]["MAX"]["VALUE"];?>"
*/?>
												size="5"
												onkeyup="smartFilter.keyup(this)"
												placeholder="<?=$arItem['VALUES']['MIN']['VALUE']?>"
											/>
										</div>
									</div>
									<div class="col-6 bx-filter-parameters-box-container-block">
										<div class="bx-filter-input-container">
											<input
												class="max-price form-control"
												type="number"
												name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
												data-chosed-prefix="RS_MM_BCSF_WE_CHOSE_NUM_TO"
												data-chosed-postfix=""
<?/*
												min="<?=(float)$arItem["VALUES"]["MIN"]["VALUE"];?>"
												max="<?=(float)$arItem["VALUES"]["MAX"]["VALUE"];?>"
												step="1"
*/?>
												size="5"
												onkeyup="smartFilter.keyup(this)"
												placeholder="<?=$arItem['VALUES']['MAX']['VALUE']?>"
											/>
										</div>
									</div>

									<div class="col">
										<div class="bx-ui-slider-track-container">
											<div class="bx-ui-slider-track" id="drag_track_<?=$key?>">
												<?
												$precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
												$step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4;
												$value1 = number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", "");
												$value2 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step, $precision, ".", "");
												$value3 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 2, $precision, ".", "");
												$value4 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 3, $precision, ".", "");
												$value5 = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
												?>
												<div class="bx-ui-slider-part p1"><span><?=$value1?></span></div>
												<div class="bx-ui-slider-part p2"><span><?=$value2?></span></div>
												<div class="bx-ui-slider-part p3"><span><?=$value3?></span></div>
												<div class="bx-ui-slider-part p4"><span><?=$value4?></span></div>
												<div class="bx-ui-slider-part p5"><span><?=$value5?></span></div>

												<div class="bx-ui-slider-pricebar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
												<div class="bx-ui-slider-pricebar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
												<div class="bx-ui-slider-pricebar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
												<div class="bx-ui-slider-range" 	id="drag_tracker_<?=$key?>"  style="left: 0;right: 0;">
													<a class="bx-ui-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
													<a class="bx-ui-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
												</div>
											</div>
										</div>
									</div>
									<?
									$arJsParams = array(
										"leftSlider" => 'left_slider_'.$key,
										"rightSlider" => 'right_slider_'.$key,
										"tracker" => "drag_tracker_".$key,
										"trackerWrap" => "drag_track_".$key,
										"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
										"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
										"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
										"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
										"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
										"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
										"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
										"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
										"precision" => $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0,
										"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
										"colorAvailableActive" => 'colorAvailableActive_'.$key,
										"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
									);
									?>
									<script type="text/javascript">
										BX.ready(function(){
											window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
										});
									</script>
									<?
									if (!in_array($arItem["CODE"], $arHiddenProps))
									{
										if ($arItem["VALUES"]["MIN"]["HTML_VALUE"])
										{
											$arPropHaveChecked[$key]["MIN"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
										}
										if ($arItem["VALUES"]["MAX"]["HTML_VALUE"])
										{
											$arPropHaveChecked[$key]["MAX"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
										}
									}
									break;
								case "B"://NUMBERS
									?>
									<div class="col-xs-6 bx-filter-parameters-box-container-block">
										<div class="bx-filter-input-container">
											<input
												class="min-price form-control"
												type="number"
												name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
												data-chosed-prefix="RS_MM_BCSF_WE_CHOSE_NUM_FROM"
												data-chosed-postfix=""
<?/*
												min="<?=(float)$arItem["VALUES"]["MIN"]["VALUE"];?>"
												max="<?=(float)$arItem["VALUES"]["MAX"]["VALUE"];?>"
*/?>
												size="5"
												onkeyup="smartFilter.keyup(this)"
												placeholder="<?=$arItem['VALUES']['MIN']['VALUE']?>"
												/>
										</div>
									</div>
									<div class="col-xs-6 bx-filter-parameters-box-container-block">
										<div class="bx-filter-input-container">
											<input
												class="max-price form-control"
												type="number"
												name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
												data-chosed-prefix="RS_MM_BCSF_WE_CHOSE_NUM_TO"
												data-chosed-postfix=""
<?/*
												min="<?=(float)$arItem["VALUES"]["MIN"]["VALUE"];?>"
												max="<?=(float)$arItem["VALUES"]["MAX"]["VALUE"];?>"
*/?>
												size="5"
												onkeyup="smartFilter.keyup(this)"
												placeholder="<?=$arItem['VALUES']['MAX']['VALUE']?>"
												/>
										</div>
									</div>
									<?
									if (!in_array($arItem["CODE"], $arHiddenProps)) {
										if ($arItem["VALUES"]["MIN"]["HTML_VALUE"]){
											$arPropHaveChecked[$key]["MIN"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
										}
										if ($arItem["VALUES"]["MAX"]["HTML_VALUE"]){
											$arPropHaveChecked[$key]["MAX"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
										}
									}
									break;
								case "G"://CHECKBOXES_WITH_PICTURES
									?>
									<div class="col">
										<div class="bx-filter-param-btn-inline">
										<?foreach ($arItem["VALUES"] as $val => $ar):?>
											<input
												style="display: none"
												type="checkbox"
												name="<?=$ar["CONTROL_NAME"]?>"
												id="<?=$ar["CONTROL_ID"]?>"
												value="<?=$ar["HTML_VALUE"]?>"
												<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
												onclick="smartFilter.click(this)"
												data-text-value="<?=$ar["VALUE"]?>"
											/>
											<?
											$class = "";
											if ($ar["CHECKED"])
												$class.= " checked";
											if ($ar["DISABLED"])
												$class.= " disabled";
											?>
											<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'checked');" data-entity="filter-value">
												<span class="bx-filter-param-btn bx-color-sl">
													<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
													<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
													<?endif?>
												</span>
											</label>
										<?endforeach?>
										</div>
									</div>
									<?
									if (!in_array($arItem["CODE"], $arHiddenProps)) {
										if ($arItem["VALUES"]["MIN"]["HTML_VALUE"]){
											$arPropHaveChecked[$key]["MIN"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
										}
										if ($arItem["VALUES"]["MAX"]["HTML_VALUE"]){
											$arPropHaveChecked[$key]["MAX"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
										}
									}
									break;
								case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
									?>
									<div class="col">
										<?/* <div class="bx-filter-param-btn-block"> */?>
										<?foreach ($arItem["VALUES"] as $val => $ar):?>
											<input
												style="display: none"
												type="checkbox"
												name="<?=$ar["CONTROL_NAME"]?>"
												id="<?=$ar["CONTROL_ID"]?>"
												value="<?=$ar["HTML_VALUE"]?>"
												<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
												onclick="smartFilter.click(this)"
												data-text-value="<?=$ar["VALUE"]?>"
											/>
											<?
											$class = "";
											if ($ar["CHECKED"])
												$class.= " checked";
											if ($ar["DISABLED"])
												$class.= " disabled";
											?>
											<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'checked');" data-entity="filter-value">
												<span class="bx-filter-param-btn bx-color-sl">
													<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
														<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
													<?endif?>
												</span>
												<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
												if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
													?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
												endif;?></span>
											</label>
											<?php
											if (!in_array($arItem["CODE"], $arHiddenProps) && $ar["CHECKED"]) {
												$arPropHaveChecked[$key][$val] = $ar["VALUE"];
											}
											?>
										<?endforeach?>
										<?/* </div> */?>
									</div>
									<?
									break;
								case "P"://DROPDOWN
									$checkedItemExist = false;
									$dropdownId = $this->getEditAreaId($arItem['ID'].'_menu');
									?>
									<div class="col">
										<div class="bx-filter-select-container">
											<div class="dropdown">
												<div class="btn btn-outline-secondary dropdown-toggle" id="<?=$dropdownId;?>" data-toggle="dropdown">
													<span data-role="currentOption">
													<?
													foreach ($arItem["VALUES"] as $val => $ar)
													{
														if ($ar["CHECKED"])
														{
															echo $ar["VALUE"];
															$checkedItemExist = true;
														}
													}
													if (!$checkedItemExist)
													{
														echo GetMessage("CT_BCSF_FILTER_ALL");
													}
													?>
													</span>
												</div>
												<input
													style="display: none"
													type="radio"
													name="<?=$arCur["CONTROL_NAME_ALT"]?>"
													id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
													value=""
												/>
												<?foreach ($arItem["VALUES"] as $val => $ar):?>
													<input
														style="display: none"
														type="radio"
														name="<?=$ar["CONTROL_NAME_ALT"]?>"
														id="<?=$ar["CONTROL_ID"]?>"
														value="<? echo $ar["HTML_VALUE_ALT"] ?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
														data-text-value="<?=$ar["VALUE"]?>"
													/>
													<?php
													if (!in_array($arItem["CODE"], $arHiddenProps) && !$checkedItemExist && $ar["CHECKED"]) {
														$checkedItemExist = $ar;
														$arPropHaveChecked[$key][$val] = $ar["VALUE"];
													}
													?>
												<?endforeach?>
													<div class="dropdown-menu" aria-labelledby="<?=$dropdownId;?>"<?/*" data-role="dropdownContent" style="display: none;"*/?>>
														<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label dropdown-item" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')" data-entity="filter-value">
															<span class="bx-filter-param-text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
														</label>
													<?
													foreach ($arItem["VALUES"] as $val => $ar):
														$class = "";
														if ($ar["CHECKED"])
															$class.= " active";
														if ($ar["DISABLED"])
															$class.= " disabled";
													?>
															<label for="<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label dropdown-item<?=$class?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')" data-entity="filter-value">
																<span class="bx-filter-param-text"><?=$ar["VALUE"]?></span>
															</label>
													<?endforeach?>
													</div>
											</div>
										</div>
									</div>
									<?
									break;
								case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
									$dropdownId = $this->getEditAreaId($arItem['ID'].'_menu');
									?>
									<div class="col">
										<div class="bx-filter-select-container">
											<div class="dropdown"<?/*onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')"*/?>>
												<div class="btn btn-outline-secondary dropdown-toggle" id="<?=$dropdownId;?>" data-toggle="dropdown">
													<span data-role="currentOption">
													<?
													$checkedItemExist = false;
													foreach ($arItem["VALUES"] as $val => $ar):
														if ($ar["CHECKED"])
														{
														?>
															<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
																<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
															<?endif?>
															<span class="bx-filter-param-text"><?=$ar["VALUE"]?></span>
														<?
															$checkedItemExist = true;

															if (!in_array($arItem["CODE"], $arHiddenProps) && !$checkedItemExist && $ar["CHECKED"]){
																$checkedItemExist = $ar;
																$arPropHaveChecked[$key][$val] = $ar["VALUE"];
															}
														}
													endforeach;
													if (!$checkedItemExist)
													{
														?>
														<svg class="bx-filter-btn-color-icon all icon-svg icon-layers"><use xlink:href="#svg-layers"></use></svg>
														<span class="bx-filter-param-text"><?=GetMessage("CT_BCSF_FILTER_ALL")?></span>
														<?php
													}
													?>
													</span>
												</div>
												<input
													style="display: none"
													type="radio"
													name="<?=$arCur["CONTROL_NAME_ALT"]?>"
													id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
													value=""
												/>
												<?foreach ($arItem["VALUES"] as $val => $ar):?>
													<input
														style="display: none"
														type="radio"
														name="<?=$ar["CONTROL_NAME_ALT"]?>"
														id="<?=$ar["CONTROL_ID"]?>"
														value="<?=$ar["HTML_VALUE_ALT"]?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
														data-text-value="<?=$ar["VALUE"]?>"
													/>
												<?endforeach?>
													<div class="dropdown-menu" aria-labelledby="<?=$dropdownId;?>">
														<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label dropdown-item" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')" data-entity="filter-value">
															<svg class="bx-filter-btn-color-icon all icon-svg icon-layers"><use xlink:href="#svg-layers"></use></svg>
															<span class="bx-filter-param-text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
														</label>
													<?
													foreach ($arItem["VALUES"] as $val => $ar):
														$class = "";
														if ($ar["CHECKED"])
															$class.= " active";
														if ($ar["DISABLED"])
															$class.= " disabled";
													?>
														<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label dropdown-item<?=$class?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')" data-entity="filter-value">
															<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
																<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
															<?endif?>
															<span class="bx-filter-param-text"><?=$ar["VALUE"]?></span>
														</label>
													<?endforeach?>
													</div>
											</div>
										</div>
									</div>
									<?
									break;
								case "K"://RADIO_BUTTONS
									?>
									<div class="col">
										<div class="bx-filter-input-checkbox">
											<div class="radio bmd-custom-radio">
												<label class="bx-filter-param-label" for="<? echo "all_".$arCur["CONTROL_ID"] ?>">
														<input
															type="radio"
															value=""
															name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
															id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
															<?php
															if (
																count(
																	array_filter(
																		$arItem['VALUES'],
																		function($v){
																			return $v['CHECKED'] === true;
																		}
																	)
																) < 1
															)
															{
																echo 'checked="checked"';
															}
															?>
															onclick="smartFilter.click(this)"
														/>
														<span class="bx-filter-param-text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
												</label>
											</div>
										</div>
										<?foreach($arItem["VALUES"] as $val => $ar):?>
											<div class="bx-filter-input-checkbox" data-entity="filter-value">
												<div class="radio bmd-custom-radio">
													<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <? echo $ar["DISABLED"] ? 'disabled': '' ?>" for="<? echo $ar["CONTROL_ID"] ?>">
															<input
																type="radio"
																value="<? echo $ar["HTML_VALUE_ALT"] ?>"
																name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
																id="<? echo $ar["CONTROL_ID"] ?>"
																<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
																onclick="smartFilter.click(this)"
																<? echo $ar["DISABLED"] ? 'disabled': '' ?>
																data-text-value="<?=$ar["VALUE"]?>"
															/>
															<span class="bx-filter-param-text" title="<?=$ar["VALUE"]?>"><?=$ar["VALUE"]?><?
															if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
																?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
															endif;?></span>
													</label>
												</div>
											</div>
											<?php
											if (!in_array($arItem["CODE"], $arHiddenProps) && $ar["CHECKED"]) {
												$arPropHaveChecked[$key][$val] = $ar["VALUE"];
											}
											?>
										<?endforeach;?>
									</div>
									<?
									break;
								case "U"://CALENDAR
									?>
									<div class="col">
										<div class="row"><div class="col-6">
											<?$APPLICATION->IncludeComponent(
												'bitrix:main.calendar',
												'',
												array(
													'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
													'SHOW_INPUT' => 'Y',
													'INPUT_ADDITIONAL_ATTR' => 'class="form-control calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
													'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
													'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
													'SHOW_TIME' => 'N',
													'HIDE_TIMEBAR' => 'Y',
												),
												null,
												array('HIDE_ICONS' => 'Y')
											);?>
										</div>
										<div class="col-6">
											<?$APPLICATION->IncludeComponent(
												'bitrix:main.calendar',
												'',
												array(
													'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
													'SHOW_INPUT' => 'Y',
													'INPUT_ADDITIONAL_ATTR' => 'class="form-control calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
													'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
													'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
													'SHOW_TIME' => 'N',
													'HIDE_TIMEBAR' => 'Y',
												),
												null,
												array('HIDE_ICONS' => 'Y')
											);?>
										</div></div>
									</div>
									<?
									break;
								default://CHECKBOXES
									?>
									<div class="col">
										<?foreach($arItem["VALUES"] as $val => $ar):?>
											<div class="bx-filter-input-checkbox" data-entity="filter-value">
												<div class="checkbox bmd-custom-checkbox">
													<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <? echo $ar["DISABLED"] ? 'disabled': '' ?>" for="<? echo $ar["CONTROL_ID"] ?>">
														<input
															type="checkbox"
															value="<? echo $ar["HTML_VALUE"] ?>"
															name="<? echo $ar["CONTROL_NAME"] ?>"
															id="<? echo $ar["CONTROL_ID"] ?>"
															<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
															onclick="smartFilter.click(this)"
															<? echo $ar["DISABLED"] ? 'disabled': '' ?>
															data-text-value="<?=$ar["VALUE"]?>"
														/>
														<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?
														?><?=$ar["VALUE"];?><?
														if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
															?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
														endif;?>
														</span>
													</label>
												</div>
											</div>
											<?php
											if (!in_array($arItem["CODE"], $arHiddenProps) && $ar['CHECKED']) {
												$arPropHaveChecked[$key][$val] = $ar["VALUE"];
											}
											?>
										<?endforeach;?>
									</div>
							<?
							}

							if ($isScrolable && count($arItem["VALUES"]) > 5)
							{
								?>
                                    </div>
								</div>
								<?php
							}
							?>
							</div>
						</div>
					</div>
				<?
				}
				?>
			<?php if ($arParams['FILTER_VIEW_MODE'] == 'VERTICAL'): ?>
				</div><!--/.row-->
			<?php else: ?>
				</div><!--/.params-->
			<?php endif; ?>

			<?php if ($arParams['FILTER_VIEW_MODE'] == 'HORIZONTAL'): ?>
				<div class="bx-filter-chosed-box js-filter-chosed-box" style="display: none;">
					<span class="bx-filter-chosed-box__title"><?=Loc::getMessage('RS_MM_BCSF_WE_CHOSE')?></span>
					<span class="bx-filter-chosed-box__values js-filter-chosed-box__values">
					<?php
					foreach ($arResult['ITEMS'] as $key => $arItem):
						if ($arItem['USING'] != 'Y' || !isset($arItem['PRICE']))
							continue;

						$id = 'bx-filter-id-'.$arItem["ID"];

						?><span class="bx-filter-chosed-box__name"><?=$arItem['NAME']?></span><?

						if (!empty($arItem['VALUES']['MIN']['HTML_VALUE']))
						{
							?><span class="bx-filter-chosed-box__value"><?
								?><?=Loc::getMessage('RS_MM_BCSF_WE_CHOSE_NUM_FROM')?><?=$arItem['VALUES']['MIN']['HTML_VALUE']?><?
							?></span><?
						}

						if (!empty($arItem['VALUES']['MAX']['HTML_VALUE']))
						{
							?><span class="bx-filter-chosed-box__value"><?
								?><?=Loc::getMessage('RS_MM_BCSF_WE_CHOSE_NUM_TO')?><?=$arItem['VALUES']['MAX']['HTML_VALUE']?><?
							?></span><?
						}

						?><span class="bx-filter-chosed-box__reset js-filter-chosed-box__reset" data-property-id="<?=$id?>"><svg class="icon-svg bx-filter-chosed-box__reset-icon"><use xlink:href="#svg-close"></use></svg></span><?
					endforeach;

					foreach ($arResult['ITEMS'] as $key => $arItem):
						if ($arItem['USING'] != 'Y' || isset($arItem['PRICE']))
							continue;

						?><span class="bx-filter-chosed-box__name"><?=$arItem['NAME']?></span><?

						$arCur = current($arItem["VALUES"]);
						switch ($arItem['DISPLAY_TYPE'])
						{
							case 'A'://NUMBERS_WITH_SLIDER
							case 'B'://NUMBERS
								if (!empty($arItem['VALUES']['MIN']['HTML_VALUE']))
								{
									?><span class="bx-filter-chosed-box__value"><?
										?><?=Loc::getMessage('RS_MM_BCSF_WE_CHOSE_NUM_FROM')?><?=$arItem['VALUES']['MIN']['HTML_VALUE']?><?
									?></span><?
								}

								if (!empty($arItem['VALUES']['MAX']['HTML_VALUE']))
								{
									?><span class="bx-filter-chosed-box__value"><?
										?><?=Loc::getMessage('RS_MM_BCSF_WE_CHOSE_NUM_TO')?><?=$arItem['VALUES']['MAX']['HTML_VALUE']?><?
									?></span><?
								}

								?><span class="bx-filter-chosed-box__reset js-filter-chosed-box__reset" data-property-id="<?=$id?>"><svg class="icon-svg bx-filter-chosed-box__reset-icon"><use xlink:href="#svg-close"></use></svg></span><?
								break;
							case 'P'://DROPDOWN
							case 'K'://RADIO_BUTTONS
								foreach ($arItem['VALUES'] as $val => $ar):
									if (!$ar['CHECKED'])
										continue;
								
									?><span class="bx-filter-chosed-box__value"><?=$ar['VALUE']?></span><?
									?><label <?
										?>class="bx-filter-chosed-box__reset" <?
										?>for="<?=('all_'.$arCur['CONTROL_ID'])?>" <?
										?>><svg class="icon-svg bx-filter-chosed-box__reset-icon"><use xlink:href="#svg-close"></use></svg></label><?
								endforeach;
								break;
							case 'U'://CALENDAR
								?><span class="bx-filter-chosed-box__value"><?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?></span><?
								break;
							case 'R'://DROPDOWN_WITH_PICTURES_AND_LABELS
							case 'G'://CHECKBOXES_WITH_PICTURES
							case 'H'://CHECKBOXES_WITH_PICTURES_AND_LABELS
							default://CHECKBOXES
								foreach ($arItem['VALUES'] as $val => $ar):
									if (!$ar['CHECKED'])
										continue;
								
									?><span class="bx-filter-chosed-box__value"><?=$ar['VALUE']?></span><?
									?><label <?
										?>class="bx-filter-chosed-box__reset" <?
										?>for="<?=$ar['CONTROL_ID']?>" <?
										?>><svg class="icon-svg bx-filter-chosed-box__reset-icon"><use xlink:href="#svg-close"></use></svg></label><?
								endforeach;
						}
					endforeach;
					?></span><?
					?><span class="bx-filter-chosed-box__modef" id="modef"<?if(!isset($arResult["ELEMENT_COUNT"])) echo ' style="display:none;"';?>><?
						?><span class="bx-filter-chosed-box__name"><?=Loc::getMessage('RS_MM_BCSF_WE_CHOSE_MODEF')?></span><?
						?><span class="bx-filter-chosed-box__value bx-filter-chosed-box__modef-value" id="modef_num"><?=$arResult['ELEMENT_COUNT']?></span><?
						?><span class="bx-filter-chosed-box__modef-products js-filter-chosed-box__modef-products"></span><?
						?><button
							class="bx-filter-chosed-box__reset bx-filter-chosed-box__modef-reset"
							type="submit"
							id="del_filter"
							name="del_filter"
							value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>"
						/><svg class="icon-svg bx-filter-chosed-box__reset-icon"><use xlink:href="#svg-close"></use></svg></button><?
						?><a <?php if ($arParams['INSTANT_RELOAD'] == 'Y') echo 'style="display:none"'; ?> href="<?echo $arResult["FILTER_URL"]?>" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a><?
					?></span><?
				?></div>
			<?php endif; ?>

			<?php if ($arParams['FILTER_VIEW_MODE'] == 'VERTICAL'): ?>
			<div class="row">
				<div class="col bx-filter-button-box">
			<?php else: ?>
				<div class="bx-filter-button-box">
			<?php endif; ?>
					<div class="bx-filter-block mt-1<?if($arParams['FILTER_VIEW_MODE'] == 'HORIZONTAL') echo ' d-md-none';?>">
						<div class="bx-filter-parameters-box-container bx-filter-button-box-inner<?=$arParams['FILTER_VIEW_MODE'] == 'VERTICAL' ? ' text-center' : ''?>">
							<div>
							<input
								class="btn btn-primary mb-3<?=$arParams['FILTER_VIEW_MODE'] == 'VERTICAL' ? ' w-100' : ''?><?=($arParams['INSTANT_RELOAD'] == 'Y' ? ' d-md-none' : '')?>"
								type="submit"
								id="set_filter"
								name="set_filter"
								value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"
							/>
							</div>
							<?php //if ($arParams['FILTER_VIEW_MODE'] == 'VERTICAL'): ?>
								<div>
								<input
									class="btn btn-link<?=$arParams['FILTER_VIEW_MODE'] == 'VERTICAL' ? ' w-100' : ''?><?=$arParams['FILTER_VIEW_MODE'] == 'HORIZONTAL' ? ' d-block d-md-none' : ''?>"
									type="submit"
									id="del_filter"
									name="del_filter"
									value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>"
								/>
								</div>
							<?php //endif; ?>
							<?php if ($arParams['FILTER_VIEW_MODE'] == 'VERTICAL'): ?>
							<div class="bx-filter-popup-result <?=$arParams['POPUP_POSITION']?>" id="modef">
								<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
								<a <?php if ($arParams['INSTANT_RELOAD'] == 'Y') echo 'style="display:none"'; ?> href="<?echo $arResult["FILTER_URL"]?>" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
							</div>
							<?php endif; ?>
						</div>
					</div>
			<?php if ($arParams['FILTER_VIEW_MODE'] == 'VERTICAL'): ?>
				</div>
			</div>
			<?php else: ?>
				</div>
			<?php endif; ?>

		</form>
	</div>
</div>
<script type="text/javascript">
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult['FORM_ACTION'])?>', '<?=CUtil::JSEscape($arParams['FILTER_VIEW_MODE'])?>', <?=CUtil::PhpToJSObject($arResult['JS_FILTER_PARAMS'])?>);
</script>

<?php
$layout->end();

// add templates
$jsTemplates = new Bitrix\Main\IO\Directory(Bitrix\Main\Application::getDocumentRoot().$templateFolder.'/js-templates');
foreach ($jsTemplates->getChildren() as $jsTemplate)
{
	include($jsTemplate->getPath());
}
// $this->addExternalJs($templateFolder.'/js/mustache.js');
$this->addExternalJs('/bitrix/js/ui/mustache/mustache.js');
$this->addExternalJs($templateFolder.'/js/component.js');

// add lang messages to js
$messages = Loc::loadLanguageFile(__FILE__);
?>
<script>
	BX.message(<?=CUtil::PhpToJSObject($messages)?>);
</script>
