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

/*
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/colors.css',
	'TEMPLATE_CLASS' => 'bx-'.$arParams['TEMPLATE_THEME']
);

if (isset($templateData['TEMPLATE_THEME']))
{
	$this->addExternalCss($templateData['TEMPLATE_THEME']);
}
*/

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
	->addModifier('white')
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addModifier('block')
	->addData('SECTION_ATTRIBUTES', 'id="'.$arParams['TARGET_ID'].'-filter"');

$layout->start();
?>
<div class="bx-filter <?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL") echo "bx-filter-horizontal"?>">
	<div class="bx-filter-section">
		<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
			<?foreach($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
			<?endforeach;?>
			<div class="row">

				<?php if ($arParams['FILTER_USE_HIDE_NOT_AVAILABLE'] === 'Y'): ?>
					<div class="<?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"):?>col-sm-6 col-md-4<?else:?>col-12<?endif?> bx-filter-parameters-box bx-active">
						<span class="bx-filter-container-modef"></span>
						<div class="bx-filter-parameters-box-title" onclick="smartFilter.hideFilterProps(this)">
							<span>
								<span class="js-filter__prop-name"><?=GetMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE')?></span>
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
													value="<?=GetMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE_YES')?>"
													data-text-value="<?=GetMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE_YES')?>"
													<?php if ($arParams['FILTER_HIDE_NOT_AVAILABLE'] === 'Y'):?> checked<?php endif; ?>
												/>
												<span class="bx-filter-param-text" title="<?=GetMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE_YES')?>">
												<?=GetMessage('RS_MM_BCSF_FILTER_NOT_AVAILABLE_YES')?>
												</span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>	
					</div>
				<?php endif; ?>

				<?foreach($arResult["ITEMS"] as $key=>$arItem)//prices
				{
					$key = $arItem["ENCODED_ID"];
					if(isset($arItem["PRICE"])):
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
						?>
						<div class="<?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"):?>col-sm-6 col-md-4<?else:?>col-12<?endif?> bx-filter-parameters-box bx-active">
							<span class="bx-filter-container-modef"></span>
							<div class="bx-filter-parameters-box-title" onclick="smartFilter.hideFilterProps(this)">
								<span><?=$arItem["NAME"]?><span class="bx-filter-parameters-box-angle"><svg class="icon-svg"><use data-role="prop_angle" xlink:href="#svg-chevron-up"></use></svg></span></span>
							</div>
							<div class="bx-filter-block" data-role="bx_filter_block">
								<div class="row bx-filter-parameters-box-container">
									<div class="col-6 bx-filter-parameters-box-container-block">
										<div class="bx-filter-input-container">
											<input
												class="min-price form-control"
												type="number"
												name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
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
						?>
						<script type="text/javascript">
							BX.ready(function(){
								window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
							});
						</script>
						<?php
						if ($arItem["VALUES"]["MIN"]["HTML_VALUE"]){
							$arPropHaveChecked[$arrayKey]["MIN"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
						}
						if ($arItem["VALUES"]["MAX"]["HTML_VALUE"]){
							$arPropHaveChecked[$arrayKey]["MAX"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
						}
						?>
					<?endif;
				}

				//not prices
				foreach($arResult["ITEMS"] as $key=>$arItem)
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
					?>
					<div class="<?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"):?>col-sm-6 col-md-4<?else:?>col-12<?endif?> bx-filter-parameters-box <?if ($arItem["DISPLAY_EXPANDED"]== "Y"):?>bx-active<?endif?>"<?if(in_array($arItem['CODE'], $arHiddenProps)):?> style="display:none"<?endif?>>
						<span class="bx-filter-container-modef"></span>
						<div class="bx-filter-parameters-box-title" onclick="smartFilter.hideFilterProps(this)">
							<span class="bx-filter-parameters-box-hint">
								<?=$arItem["NAME"]?>
								<?if ($arItem["FILTER_HINT"] <> ""):?>
									<i id="item_title_hint_<?echo $arItem["ID"]?>" class="hint">?</i>
									<script type="text/javascript">
										new top.BX.CHint({
											parent: top.BX("item_title_hint_<?echo $arItem["ID"]?>"),
											show_timeout: 10,
											hide_timeout: 200,
											dx: 2,
											preventHide: true,
											min_width: 250,
											hint: '<?= CUtil::JSEscape($arItem["FILTER_HINT"])?>'
										});
									</script>
								<?endif?>
								<span class="bx-filter-parameters-box-angle"><svg class="icon-svg"><use data-role="prop_angle" xlink:href="#svg-chevron-<?if ($arItem["DISPLAY_EXPANDED"]== "Y"):?>up<?else:?>down<?endif?>"></use></svg></span>
							</span>
						</div>
						<div class="bx-filter-block" data-role="bx_filter_block">
							<?
							$IS_SEARCHABLE = $IS_SCROLABLE = $IS_COLOR = $IS_BUTTON = false;

							if (!in_array($arItem['DISPLAY_TYPE'], array('A', 'B', 'G', 'P', 'R', 'U')))
							{
								if (
									is_array($arParams["SEARCH_PROPS"]) && in_array($arItem["CODE"], $arParams["SEARCH_PROPS"]) ||
									is_array($arParams["OFFER_SEARCH_PROPS"]) && in_array($arItem["CODE"], $arParams["OFFER_SEARCH_PROPS"])
								) {
									$IS_SEARCHABLE = $IS_SCROLABLE = true;
								} elseif (
									is_array($arParams["SCROLL_PROPS"]) && in_array($arItem["CODE"], $arParams["SCROLL_PROPS"]) ||
									is_array($arParams["OFFER_SCROLL_PROPS"]) && in_array($arItem["CODE"], $arParams["OFFER_SCROLL_PROPS"])
								) {
									$IS_SCROLABLE = true;
								}

								if (
									is_array($arParams["OFFER_TREE_BTN_PROPS"]) &&
									in_array($arItem["CODE"], $arParams["OFFER_TREE_BTN_PROPS"])
								) {
									$IS_BUTTON = true;
								}
							}
							?>

							<div class="row bx-filter-parameters-box-container">

							<?php
							if (count($arItem["VALUES"]) > 5)
							{
								if ($IS_SEARCHABLE)
								{
									?>
                                    <div class="bx-filter-search form-group col-12">
                                        <input type="text" class="form-control" placeholder="<?=GetMessage('RS_MM_BCSF_CATALOG_SEARCH')?>">
                                    </div>
									<?php
								}

								if ($IS_SCROLABLE)
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
									if (!in_array($arItem["CODE"], $arHiddenProps)) {
										if ($arItem["VALUES"]["MIN"]["HTML_VALUE"]){
											$arPropHaveChecked[$key]["MIN"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
										}
										if ($arItem["VALUES"]["MAX"]["HTML_VALUE"]){
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
															<span class="bx-filter-param-text">
																<?=$ar["VALUE"]?>
															</span>
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
															<span class="bx-filter-param-text">
																<?=$ar["VALUE"]?>
															</span>
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
															/>
															<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
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
														/>
														<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>">
														<?=$ar["VALUE"];?><?
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

							if ($IS_SCROLABLE && count($arItem["VALUES"]) > 5)
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
			</div><!--//row-->

			<div class="row">
				<div class="col bx-filter-button-box">
					<div class="bx-filter-block mt-1">
						<div class="bx-filter-parameters-box-container py-5 px-7 text-center">
							<div>
							<input
								class="btn btn-primary mb-3 w-100"
								type="submit"
								id="set_filter"
								name="set_filter"
								value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"
							/>
							</div>
							<div>
							<input
								class="btn btn-link w-100"
								type="submit"
								id="del_filter"
								name="del_filter"
								value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>"
							/>
							</div>
							<div class="bx-filter-popup-result <?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?>>
								<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
                                <a <?php if ($arParams['INSTANT_RELOAD'] == 'Y') echo 'style="display:none"'; ?> href="<?echo $arResult["FILTER_URL"]?>" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>

<?php
$layout->end();
/*
$this->SetViewTarget('catalog__filter-top');

if (is_array($arParams['TOP_PROPS']) && count($arParams['TOP_PROPS']) > 0)
{
    foreach($arResult["ITEMS"] as $key=>$arItem)
    {
        if (in_array($arItem['CODE'], $arParams['TOP_PROPS']) && !in_array($arItem['CODE'], $arHiddenProps))
		{
            if(
                empty($arItem["VALUES"])
                || isset($arItem["PRICE"])
                || in_array($arItem["DISPLAY_TYPE"], array('A', 'B', 'U'))
            )
                continue;

            if (is_array($arItem["VALUES"]) && count($arItem["VALUES"]) > 0)
			{
                $checkedItemExist = false;
                foreach ($arItem["VALUES"] as $val => $ar)
				{
                    if ($ar["CHECKED"])
                    {
                        $checkedItemExist = true;
                    }
                }
            ?>
                <div class="filter_top__box<? if ($checkedItemExist) echo ' checked'; ?>">
                <?php
                if ($arItem['PROPERTY_TYPE'] == 'S' && $arItem['USER_TYPE'] == 'directory')
				{
                    $sNavId = $this->getEditAreaId($arItem['ID'].'_nav');

                    $arSliderOptions = array(
                        'dots' => false,
                        'loop' => true,
                        'nav' => true,
                        'navContainer' => '#'.$sNavId,
                        'navText' => array(
                            '<svg class="icon-svg icon-svg-chevron-left"><use xlink:href="#svg-chevron-left"></use></svg>',
                            '<svg class="icon-svg icon-svg-chevron-right"><use xlink:href="#svg-chevron-right"></use></svg>',
                        ),
                        'margin' => 0,
                        'responsive' => array(
                            0 => array(
                                'items' => 2,
                            ),
                            480 => array(
                                'items' => 3,
                            ),
                            768 => array(
                                'items' => 4,
                            ),
                            991 => array(
                                'items' => 5,
                            ),
                            1280 => array(
                                'items' => 6,
                            ),
                        ),
                    );
                    ?>
                    <div class="filter_top__carousel" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arSliderOptions)?>'>
                        <?php foreach ($arItem["VALUES"] as $val => $ar): ?>
                            <?
                            $class = "filter_top__item";
                            if ($ar["CHECKED"])
                                $class.= " checked";
                            if ($ar["DISABLED"])
                                $class.= " disabled";
                            ?>
                            <label class="<?=$class?>" for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.setTopFilter(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>'), event); BX.toggleClass(this, 'checked');">

                                <span class="filter_top__canvas">
                                    <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                        <img class="filter_top__icon" src="<?=$ar["FILE"]["SRC"]?>" alt="<?=$ar["VALUE"];?>">
                                    <?endif?>
                                </span>

                                <span class="filter_top__name"><?=$ar["VALUE"];?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div id="<?=$sNavId?>" class="carousel__nav"></div>
                    <?php
                }
				else
				{
                    foreach ($arItem["VALUES"] as $val => $ar)
					{
                        $class = "filter_top__item";
                        if ($ar["CHECKED"])
                            $class.= " checked";
                        if ($ar["DISABLED"])
                            $class.= " disabled";
                        ?>
                        <label class="<?=$class?>" for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.setTopFilter(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>'), event); BX.toggleClass(this, 'checked');">
                            <span class="filter_top__btn"><?=$ar["VALUE"];?></span>
                        </label>
                        <?php
                    }
                }
                ?>
                </div>
            <?php
            }
        }
    }
}
$this->EndViewTarget();
*/
