<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Localization\Loc;
?>
<div class="footer-subscribe">
    <?php $frame = $this->createFrame()->begin(); ?>
    <form class="form-inline align-items-stretch flex-nowrap" action="<?=$arResult['FORM_ACTION']?>">
        <?php foreach ($arResult['RUBRICS'] as $itemID => $itemValue): ?>
            <input class="d-none" type="checkbox" name="sf_RUB_ID[]" value="<?=$itemValue["ID"]?>"<?php if($itemValue["CHECKED"]) { echo ' checked'; }?> title="<?=$itemValue['NAME']?>">
        <?php endforeach; ?>
    	<input type="text" name="sf_EMAIL" size="20" value="<?=$arResult['EMAIL']?>" placeholder="<?=Loc::getMessage('RS_SF_EMAIL_PLACEHOLDER');?>" class="form-control flex-grow-1 w-auto footer-subscribe__input">
		<label class="footer-subscribe__button">
			<svg class="icon-svg"><use xlink:href="#svg-mail"></use></svg>
			<input name="OK" class="d-none " type="submit" value="" data-type="ajax" data-src="<?=$arParams['PAGE']?>" data-popup-type="fullscreen">
		</label>
    </form>
    <?php $frame->beginStub(); ?>

    <form class="form-inline align-items-stretch flex-nowrap" action="<?=$arResult['FORM_ACTION']?>">
        <?php foreach ($arResult['RUBRICS'] as $itemID => $itemValue): ?>
            <input class="d-none" type="checkbox" name="sf_RUB_ID[]" value="<?=$itemValue["ID"]?>"<?php if($itemValue["CHECKED"]) { echo ' checked'; }?> title="<?=$itemValue['NAME']?>">
        <?php endforeach; ?>
		<input type="text" name="sf_EMAIL" size="20" value="" placeholder="<?=Loc::getMessage('RS_SF_EMAIL_PLACEHOLDER');?>" class="form-control flex-grow-1  w-auto footer-subscribe__input">
        <label class="footer-subscribe__button" >
            <svg class="icon-svg"><use xlink:href="#svg-mail"></use></svg>
            <input name="OK" type="submit" class="d-none" value="" data-src="<?=$arParams['PAGE']?>" data-popup-type="fullscreen">
        </label>
    </form>
    <?php $frame->end(); ?>

	<?php if (!empty($arParams['RS_NOTE_TEXT'])): ?>
        <div class="footer-subscribe__note">
            <?=$arParams['RS_NOTE_TEXT']?>
        </div>
    <?php endif; ?>
</div>
