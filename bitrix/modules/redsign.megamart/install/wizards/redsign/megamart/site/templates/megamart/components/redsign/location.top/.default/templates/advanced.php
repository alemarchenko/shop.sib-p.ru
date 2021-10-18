<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;
?>
<div id="advanced-location-<?=$this->randString(5);?>">
	<div class="location-top-advanced" >
		<div class="location-top-advanced__container"  style="background-image: url('<?=$templateFolder?>/images/map.png');">
			<div class="location-top-advanced__list">
				<h4 class="mb-5"><?=Loc::getMessage('RS_SELECT_CITY');?></h4>
				<div class="row location-top-list">
					<?php if (!empty($arResult['SELECT_CITY'])): ?>
					<div class="location-top-list__item col-6 col-sm-4">
						<input class="d-none" type="radio" name="SELECT_CITY" id="SELECT_CITY_<?=$arResult['SELECT_CITY']['ID']?>" value="text" checked>
						<label class="location-top-list__link" for="SELECT_CITY_<?=$arResult['SELECT_CITY']['ID']?>"><?=$arResult['SELECT_CITY']['NAME']?></label>
					</div>
					<?php else: ?>
						<input class="d-none" type="radio" name="SELECT_CITY"  value="text" checked>
					<?php endif; ?>
					<?php
					foreach ($arResult['ITEMS'] as $index => $arItem):
					?>
					<div class="location-top-list__item col-6 col-sm-4">
						<input class="d-none" type="radio" name="SELECT_CITY" id="SELECT_CITY_<?=$arItem['ID']?>" value="text" onclick="RSLocationChange('<?=$arItem['ID']?>')">
						<label class="location-top-list__link" for="SELECT_CITY_<?=$arItem['ID']?>"><?=$arItem['LNAME']?></label>
					</div>
					<?php endforeach; ?>
				</div>

				<div class="mt-5">
					<?$APPLICATION->IncludeComponent(
						"bitrix:sale.location.selector.search",
						"md_dropdown",
						Array(
							"COMPONENT_TEMPLATE" => ".default",
							"ID" => $arResult['SELECT_CITY']['ID'],
							"CODE" => $arResult['SELECT_CITY']['CODE'],
							"INPUT_NAME" => "LOCATION",
							"PROVIDE_LINK_BY" => "id",
							"JS_CONTROL_GLOBAL_ID" => "",
							"JS_CALLBACK" => "RSLocationChange",
							"FILTER_BY_SITE" => "Y",
							"SHOW_DEFAULT_LOCATIONS" => "Y",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "36000000",
							"FILTER_SITE_ID" => SITE_ID,
							"INITIALIZE_BY_GLOBAL_EVENT" => "",
							"SUPPRESS_ERRORS" => "N"
						)
					);?>
				</div>
			</div>
		</div>

	</div>
</div>
