<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$locationId = "location_main".$this->randString();

$jsParams = [
    'ajaxUrl' => $componentPath.'/ajax.php',
    'siteId' => SITE_ID,
    'confirmPopupId' =>  'location_confirm'
];

$arParams['POPUP_URL'] = (isset($arParams['POPUP_URL']) ? $arParams['POPUP_URL'] : SITE_DIR.'city/');
?>
<div class="b-main-location">
	<div class="d-flex align-items-center">
		<a href="<?=$arParams['POPUP_URL']?>" class="c-icon-count d-lg-none">
			<svg class="icon-svg text-extra h4 mb-0 lh-0"><use xlink:href="#svg-navigation"></use></svg>
		</a>
		<div class="d-none d-lg-block mr-4">
			<svg class="icon-svg text-extra h4 mb-0 lh-0"><use xlink:href="#svg-navigation"></use></svg>
		</div>
		<div class="d-none d-lg-block">
			<div class="text-dark font-weight-bold lh-1"><?=Loc::getMessage('RS_LOCATION_SELECT');?></div>
			<div class="d-table small text-extra position-relative" id="<?=$locationId?>">
				<?php
				$frame = $this->createFrame($locationId, false)->begin();
				$frame->setBrowserStorage(true);
				?>
				<a href="<?=$arParams['POPUP_URL']?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" data-popup-type="<?=$arResult['LOCATION_POPUP_TYPE']?>" class="b-main-location__link text-primary text-nowrap">
					<?=(!empty($arResult['NAME']) ? $arResult['NAME'] : Loc::getMessage('RS_LOCATION_NOT_SELECT')); ?>
					<svg class="icon-svg text-extra lh-0"><use xlink:href="#svg-chevron-down"></use></svg>
		        </a>

				<?php if (!empty($arResult['NAME'])): ?>
				<div class="b-location-confirm anim-start" id="location_confirm">
					<a class="b-location-confirm__close" onclick="RS.Location.hideConfirm(); return false">
						<svg class="icon-svg text-extra"><use xlink:href="#svg-close"></use></svg>
					</a>
					<div class="b-location-confirm__detected"><?=Loc::getMessage('RS_LOCATION_YOUR_CITY', ['#CITY_NAME#' => $arResult['NAME']]); ?></div>
					<div class="b-location-confirm__controls">
  						<a onclick="RS.Location.hideConfirm(); return false;" href="" class="btn btn-primary text-nowrap"><?=Loc::getMessage('RS_LOCATION_CITY_RIGHT');?></a>
  						<a href="<?=$arParams['POPUP_URL']?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" data-popup-type="<?=$arResult['LOCATION_POPUP_TYPE']?>" class="btn btn-outline-secondary text-nowrap"><?=Loc::getMessage('RS_LOCATION_CITY_SEARCH');?></a>
                    </div>
				</div>
				<script>RS.Location.showConfirmPopup();</script>
				<?php endif; ?>
				<?php $frame->beginStub(); ?>
				<a href="<?=$arParams['POPUP_URL']?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" data-popup-type="<?=$arResult['LOCATION_POPUP_TYPE']?>" class="b-main-location__link text-primary text-nowrap">
			        <?=Loc::getMessage('RS_LOCATION_NOT_SELECT'); ?>
					<svg class="icon-svg text-extra lh-0"><use xlink:href="#svg-chevron-down"></use></svg>
			    </a>
                <?php $frame->end(); ?>
            </div>
		</div>
	</div>
</div>
