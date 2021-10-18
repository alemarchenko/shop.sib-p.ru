<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Localization\Loc;

$arParams['POPUP_URL'] = (isset($arParams['POPUP_URL']) ? $arParams['POPUP_URL'] : SITE_DIR.'city/');
?>
<div class="b-main-location">
	<div class="d-flex align-items-center">
	    <div class="d-block mr-2 lh-0">
	        <svg class="icon-svg text-secondary"><use xlink:href="#svg-navigation"></use></svg>
	    </div>
	    <div class="d-block">
			<div class="d-block font-size-sm text-secondary">
				<?php
				$frame = $this->createFrame()->begin();
				$frame->setBrowserStorage(true);
				?>
				<a href="<?=$arParams['POPUP_URL']?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" data-popup-type="<?=$arResult['LOCATION_POPUP_TYPE']?>" class="b-main-location__link text-primary">
			        <?=(!empty($arResult['NAME']) ? $arResult['NAME'] : Loc::getMessage('RS_LOCATION_NOT_SELECT')); ?>
					<svg class="icon-svg text-secondary lh-0"><use xlink:href="#svg-chevron-down"></use></svg>
			    </a>
				<?php $frame->beginStub(); ?>
				<a href="<?=$arParams['POPUP_URL']?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" data-popup-type="<?=$arResult['LOCATION_POPUP_TYPE']?>" class="b-main-location__link text-primary">
			        <?=(!empty($arResult['NAME']) ? $arResult['NAME'] : Loc::getMessage('RS_LOCATION_NOT_SELECT')); ?>
					<svg class="icon-svg text-secondary lh-0"><use xlink:href="#svg-chevron-down"></use></svg>
			    </a>
				<?php $frame->end(); ?>
		</div>
	</div>
</div>
</div>
