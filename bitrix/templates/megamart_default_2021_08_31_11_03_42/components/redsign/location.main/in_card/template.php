<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Localization\Loc;

$arParams['POPUP_URL'] = (isset($arParams['POPUP_URL']) ? $arParams['POPUP_URL'] : SITE_DIR.'city/');
?>
<div class="shop-card-inner-location text-center">
	<img src="<?=$templateFolder?>/images/pin-54.png" alt="pin" title="pin" class="mb-3">
	<h4><?=Loc::getMessage('RS_LOCATION_SELECT')?></h4>
	<?php
	$frame = $this->createFrame()->begin();
	$frame->setBrowserStorage(true);
	?>
		<a href="<?=$arParams['POPUP_URL']?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" data-popup-type="<?=$arResult['LOCATION_POPUP_TYPE']?>" class="btn btn-outline-secondary mt-3">
			<?=(!empty($arResult['NAME']) ? $arResult['NAME'] : Loc::getMessage('RS_LOCATION_NOT_SELECT')); ?>
			<svg class="icon-svg lh-0"><use xlink:href="#svg-chevron-down"></use></svg>
		</a>
	<?php $frame->beginStub(); ?>
		<a href="<?=$arParams['POPUP_URL']?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" data-popup-type="<?=$arResult['LOCATION_POPUP_TYPE']?>" class="btn btn-outline-secondary mt-3">
			<?=(!empty($arResult['NAME']) ? $arResult['NAME'] : Loc::getMessage('RS_LOCATION_NOT_SELECT')); ?>
			<svg class="icon-svg lh-0"><use xlink:href="#svg-chevron-down"></use></svg>
		</a>
	<?php $frame->end(); ?>

</div>
