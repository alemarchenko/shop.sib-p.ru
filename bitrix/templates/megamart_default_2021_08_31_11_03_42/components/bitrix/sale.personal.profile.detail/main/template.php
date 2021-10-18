<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
?>
<div class="mb-7">
		<a href="<?=$arParams["PATH_TO_LIST"]?>"><?=GetMessage("SPPD_RECORDS_LIST")?></a>
</div>
<?
if(strlen($arResult["ID"])>0)
{
	ShowError($arResult["ERROR_MESSAGE"]);
	?>
	<div>
		<form method="post"  class="sale-profile-detail-form" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data">

			<div class="mb-3">
				<?=bitrix_sessid_post()?>
				<input type="hidden" name="ID" value="<?=$arResult["ID"]?>">
				<h4><?= Loc::getMessage('SPPD_PROFILE_NO', array("#ID#" => $arResult["ID"]))?></h4>
			</div>

			<div class="form-group bmd-form-group row mb-3">
				<label class="col-sm-2 col-form-label"><?=Loc::getMessage('SALE_PERS_TYPE')?></label>
				<div class="col-sm-10 col-form-label"><?=$arResult["PERSON_TYPE"]["NAME"]?></div>
			</div>

			<div class="form-group bmd-form-group">
				<label class="bmd-label-floating" for="sale-personal-profile-detail-name"><?=Loc::getMessage('SALE_PNAME')?>:<span class="text-danger">*</span></label>
				<input class="bmd-form-control" type="text" name="NAME" maxlength="50" id="sale-personal-profile-detail-name" value="<?=$arResult["NAME"]?>" />
			</div>

			<?
			foreach($arResult["ORDER_PROPS"] as $block)
			{
				if (!empty($block["PROPS"]))
				{
					?>
					<div class="row mb-2 mt-6">
						<div class="col">
							<h4><?= $block["NAME"]?></h4>
						</div>
					</div>
					<?
					foreach($block["PROPS"] as $key => $property)
					{
						$name = "ORDER_PROP_".(int)$property["ID"];
						$currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
						$alignTop = ($property["TYPE"] === "LOCATION" && $arParams['USE_AJAX_LOCATIONS'] === 'Y') ? "vertical-align-top" : "";
						?>
							<?
							if ($property["TYPE"] == "CHECKBOX")
							{
								?>
								 <div class="checkbox bmd-custom-checkbox">
									<label for="sppd-property-<?=$key?>">
										<input class="form-check-input" id="sppd-property-<?=$key?>" type="checkbox" name="<?=$name?>" value="Y"
										<?if ($currentValue == "Y" || !isset($currentValue) && $property["DEFAULT_VALUE"] == "Y") echo " checked";?>/>
										<?= $property["NAME"]?>:
										<? if ($property["REQUIED"] == "Y")
										{
											?><span class="text-danger">*</span><?
										}
										?>
									</label>
								</div>
								<?
							}
							elseif ($property["TYPE"] == "TEXT")
							{
								?>
								<?php if ($property["MULTIPLE"] === 'Y'): ?>
									<div class="mb-5">
										<?php
										if ($property["MULTIPLE"] === 'Y'):
											if (empty($currentValue) || !is_array($currentValue))
												$currentValue = array('');
										?>
											<?php foreach ($currentValue as $elementValue): ?>
											<div class="form-group bmd-form-group">
												<label class="bmd-label-floating" for="sppd-property-<?=$key?>">
													<?= $property["NAME"]?>:
													<? if ($property["REQUIED"] == "Y")
													{
														?><span class="text-danger">*</span><?
													}
													?>
												</label>
												<input class="bmd-form-control" type="text" name="<?=$name?>[]" maxlength="50" id="sppd-property-<?=$key?>" value="<?=$elementValue?>"/>
											</div>
											<?php endforeach; ?>

											<span class="mt-5 btn btn-primary input-add-multiple" data-add-type=<?=$property["TYPE"]?> data-add-name="<?=$name?>[]"><?=Loc::getMessage('SPPD_ADD')?></span>
										<?php endif; ?>
									</div>
								<?php else: ?>
									<div class="form-group bmd-form-group">
										<label class="bmd-label-floating" for="sppd-property-<?=$key?>">
											<?= $property["NAME"]?>:
											<? if ($property["REQUIED"] == "Y")
											{
												?><span class="text-danger">*</span><?
											}
											?>
										</label>
										<input class="bmd-form-control" type="text" name="<?=$name?>" maxlength="50" id="sppd-property-<?=$key?>" value="<?=$currentValue?>"/>
									</div>
								<?php endif; ?>
								<?
							}
							elseif ($property["TYPE"] == "SELECT")
							{
								?>
								<div class="form-group bmd-form-group">
									<label for="sppd-property-<?=$key?>">
										<?= $property["NAME"]?>:
										<? if ($property["REQUIED"] == "Y")
										{
											?><span class="text-danger">*</span><?
										}
										?>
									</label>
									<select class="bmd-form-control" name="<?=$name?>" id="sppd-property-<?=$key?>" size="<?echo (intval($property["SIZE1"])>0)?$property["SIZE1"]:1; ?>">
										<?
										foreach ($property["VALUES"] as $value)
										{
											?>
											<option value="<?= $value["VALUE"]?>" <?if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"]==$property["DEFAULT_VALUE"]) echo " selected"?>>
												<?= $value["NAME"]?>
											</option>
											<?
										}
										?>
									</select>
								</div>
								<?
							}
							elseif ($property["TYPE"] == "MULTISELECT")
							{
								?>
								<div class="form-group bmd-form-group">
									<label for="sppd-property-<?=$key?>">
										<?= $property["NAME"]?>:
										<? if ($property["REQUIED"] == "Y")
										{
											?><span class="text-danger">*</span><?
										}
										?>
									</label>
									<select class="bmd-form-control" id="sppd-property-<?=$key?>" multiple name="<?=$name?>[]" size="<?echo (intval($property["SIZE1"])>0)?$property["SIZE1"]:5; ?>">
										<?
										$arCurVal = array();
										$arCurVal = explode(",", $currentValue);
										for ($i = 0, $cnt = count($arCurVal); $i < $cnt; $i++)
											$arCurVal[$i] = trim($arCurVal[$i]);
										$arDefVal = explode(",", $property["DEFAULT_VALUE"]);
										for ($i = 0, $cnt = count($arDefVal); $i < $cnt; $i++)
											$arDefVal[$i] = trim($arDefVal[$i]);
										foreach($property["VALUES"] as $value)
										{
											?>
											<option value="<?= $value["VALUE"]?>"<?if (in_array($value["VALUE"], $arCurVal) || !isset($currentValue) && in_array($value["VALUE"], $arDefVal)) echo" selected"?>>
												<?= $value["NAME"]?>
											</option>
											<?
										}
										?>
									</select>
								</div>
								<?
							}
							elseif ($property["TYPE"] == "TEXTAREA")
							{
								?>
								<div class="form-group bmd-form-group">
									<label class="bmd-label-floating" for="sppd-property-<?=$key?>">
										<?= $property["NAME"]?>:
										<? if ($property["REQUIED"] == "Y")
										{
											?><span class="text-danger">*</span><?
										}
										?>
									</label>
									<textarea
										class="bmd-form-control"
										id="sppd-property-<?=$key?>"
										rows="<?echo ((int)($property["SIZE2"])>0)?$property["SIZE2"]:4; ?>"
										cols="<?echo ((int)($property["SIZE1"])>0)?$property["SIZE1"]:40; ?>"
										name="<?=$name?>"><?= (isset($currentValue)) ? $currentValue : $property["DEFAULT_VALUE"];?></textarea>
								</div>
								<?
							}
							elseif ($property["TYPE"] == "LOCATION")
							{
								?>
								<div class="form-group bmd-form-group">
									<label class="bmd-label-static" for="sppd-property-<?=$key?>">
										<?= $property["NAME"]?>:
										<? if ($property["REQUIED"] == "Y")
										{
											?><span class="text-danger">*</span><?
										}
										?>
									</label>
									<div class="d-block">
										<?
											$locationTemplate = ($arParams['USE_AJAX_LOCATIONS'] !== 'Y') ? "popup" : "";
											$locationClassName = 'location-block-wrapper';
											if ($arParams['USE_AJAX_LOCATIONS'] === 'Y')
											{
												$locationClassName .= ' location-block-wrapper-delimeter';
											}
											if ($property["MULTIPLE"] === 'Y')
											{
												if (empty($currentValue) || !is_array($currentValue))
													$currentValue = array($property["DEFAULT_VALUE"]);

												foreach ($currentValue as $key => $elementValue)
												{
													$locationValue = intval($elementValue) ? $elementValue : $property["DEFAULT_VALUE"];


													$arParams = array(
														"CODE" => '',
														"INPUT_NAME" => $parameters["CITY_INPUT_NAME"],
														"CACHE_TYPE" => "A",
														"CACHE_TIME" => "36000000",
														"PROVIDE_LINK_BY" => "id",
														"SEARCH_BY_PRIMARY" => "N",
														"SHOW_DEFAULT_LOCATIONS" => "N",
														"ID" => "propertyLocation".$name."[$key]",
														"AJAX_CALL" => "N",
														'CITY_OUT_LOCATION' => 'Y',
														'COUNTRY_INPUT_NAME' => $name.'_COUNTRY',
														'CITY_INPUT_NAME' => $name."[$key]",
														'LOCATION_VALUE' => $locationValue,
													);


													$GLOBALS["APPLICATION"]->IncludeComponent(
														"bitrix:sale.location.selector.search",
														"md_dropdown",
														$arParams,
														null,
														array('HIDE_ICONS' => 'Y')
													);
												}
												?><span class="btn btn-primary btn-md input-add-multiple"
													  data-add-type="<?=$property["TYPE"]?>"
													  data-add-name="<?=$name?>"
													  data-add-last-key="<?=$key?>"
													  data-add-template="<?=$locationTemplate?>"><?=Loc::getMessage('SPPD_ADD')?></span><?
										}
										else
										{
											$locationValue = (int)($currentValue) ? (int)$currentValue : $property["DEFAULT_VALUE"];

											$arParams = array(
												"ID" => $locationValue,
												"CODE" => '',
												"INPUT_NAME" => $name,
												"CACHE_TYPE" => "A",
												"CACHE_TIME" => "36000000",
												"PROVIDE_LINK_BY" => "id",
												"SEARCH_BY_PRIMARY" => "N",
												"SHOW_DEFAULT_LOCATIONS" => "N",

												"AJAX_CALL" => "N",
												'CITY_OUT_LOCATION' => 'Y',
												'COUNTRY_INPUT_NAME' => $name.'_COUNTRY',
												'CITY_INPUT_NAME' => $name,
												'LOCATION_VALUE' => $locationValue,
											);

											$GLOBALS["APPLICATION"]->IncludeComponent(
												'bitrix:sale.location.selector.search',
												'md_dropdown',
												$arParams,
												null,
												array('HIDE_ICONS' => 'Y')
											);
										}
										?>
									</div>
								</div>
								<?
							}
							elseif ($property["TYPE"] == "RADIO")
							{
								?><div class="form-group bmd-form-group"><label class="bmd-label-static" for="sppd-property-<?=$key?>">
									<?= $property["NAME"]?>:
									<? if ($property["REQUIED"] == "Y")
									{
										?><span class="text-danger">*</span><?
									}
									?>
								</label> <?php
								foreach($property["VALUES"] as $value)
								{
									?>
									<div class="bmd-custom-radio radio">
										<label>
											<input type="radio" id="sppd-property-<?=$key?>" name="<?=$name?>" value="<?= $value["VALUE"]?>"
												<?if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"] == $property["DEFAULT_VALUE"]) echo " checked"?>>
												<?= $value["NAME"]?>
										</label>
									</div>
									<?
								}
								?></div><?
							}
							elseif ($property["TYPE"] == "FILE")
							{
								$multiple = ($property["MULTIPLE"] === "Y") ? "multiple" : '';
								$profileFiles = is_array($currentValue) ? $currentValue : array($currentValue);
								if (count($currentValue) > 0)
								{
									?>
									<input type="hidden" name="<?=$name?>_del" class="profile-property-input-delete-file">
									<?
									foreach ($profileFiles as $file)
									{
										?>
										<div class="sale-personal-profile-detail-form-file">
											<?
											$fileId = $file['ID'];
											if (CFile::IsImage($file['FILE_NAME']))
											{
												?>
												<div class="sale-personal-profile-detail-prop-img">
													<?=CFile::ShowImage($fileId, 150, 150, "border=0", "", true)?>
												</div>
												<?
											}
											else
											{
												?>
												<a download="<?=$file["ORIGINAL_NAME"]?>" href="<?=CFile::GetFileSRC($file)?>">
													<?=Loc::getMessage('SPPD_DOWNLOAD_FILE', array("#FILE_NAME#" => $file["ORIGINAL_NAME"]))?>
												</a>
												<?
											}
											?>
											<input type="checkbox" value="<?=$fileId?>" class="profile-property-check-file" id="profile-property-check-file-<?=$fileId?>">
											<label for="profile-property-check-file-<?=$fileId?>"><?=Loc::getMessage('SPPD_DELETE_FILE')?></label>
										</div>
										<?
									}
								}
								?>
								<div class="form-group row mb-2">
									<label class="col-sm-2 col-form-label <?=$alignTop?>" for="sppd-property-<?=$key?>">
										<?= $property["NAME"]?>:
										<? if ($property["REQUIED"] == "Y")
										{
											?><span class="text-danger">*</span><?
										}
										?>
									</label>
									<div class="col-sm-10">
										<label>
											<span class="btn btn-primary btn-md"><?=Loc::getMessage('SPPD_SELECT')?></span>
											<span class="sale-personal-profile-detail-load-file-info">
												<?=Loc::getMessage('SPPD_FILE_NOT_SELECTED')?>
											</span>
											<?=CFile::InputFile($name."[]", 20, null, false, 0, "IMAGE", "class='btn sale-personal-profile-detail-input-file' ".$multiple)?>
										</label>
										<span class="sale-personal-profile-detail-load-file-cancel sale-personal-profile-hide"></span>
									</div>
								</div>
								<?
							} else {
								\Bitrix\Main\Diag\Debug::dump($property['TYPE']);
							}
							if (strlen($property["DESCRIPTION"]) > 0)
							{
								?>
								<br /><small><?= $property["DESCRIPTION"] ?></small>
								<?
							}
							?>
						<?
					}
				}
			}
			?>
			<div class="row mb-3 mt-5">
				<div class="col">
					<input type="submit" class="btn btn-primary btn-md" name="save" value="<?echo GetMessage("SALE_SAVE") ?>">
					<input type="submit" class="btn btn-primary btn-md"  name="apply" value="<?=GetMessage("SALE_APPLY")?>">
					<input type="submit" class="btn btn-link btn-md"  name="reset" value="<?echo GetMessage("SALE_RESET")?>">
				</div>
			</div>
		</form>
	</div>
	<?
	$javascriptParams = array(
		"ajaxUrl" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
	);
	$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
	?>
	<script>
		BX.message({
			SPPD_FILE_COUNT: '<?=Loc::getMessage('SPPD_FILE_COUNT')?>',
			SPPD_FILE_NOT_SELECTED: '<?=Loc::getMessage('SPPD_FILE_NOT_SELECTED')?>'
		});
		BX.Sale.PersonalProfileComponent.PersonalProfileDetail.init(<?=$javascriptParams?>);
	</script>
	<?
}
else
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>
