<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

if (!isset($arParams['AUTH_URL'])) {
    $arParams['AUTH_URL'] = '/auth/';
}

if (!isset($arParams['PROFILE_URL'])) {
    $arParams['PROFILE_URL'] = SITE_DIR.'/personal/';
}
$this->setFrameMode(true);
?>
<a href="<?=$arParams['PROFILE_URL']?>" class="c-button-control c-button-control__bottom-panel bottom-panel-control">
	<?php $frame = $this->createFrame()->begin(); ?>
		<?php if ($arResult['FORM_TYPE'] == 'login'): ?>
			<span class="c-button-control__icon">
				<svg class="icon-svg"><use xlink:href="#svg-user"></use></svg>
			</span>
			<span class="c-button-control__title">
				<?=Loc::getMessage('RS_MM_SAF_ENTER'); ?>
			</span>
		<?php else: ?>
			<span class="c-button-control__icon">
				<span class="c-letter">
					<?=(!empty($arResult['USER_NAME']) ? substr($arResult['USER_NAME'], 0, 1) : substr($arResult['USER_LOGIN'], 0, 1)); ?>
				</span>
			</span>
			<span class="c-button-control__title">
				<!-- <?=!empty($arResult['USER_NAME']) ? $arResult['USER_NAME'] : $arResult['USER_LOGIN'] ;?> -->
				<?=Loc::getMessage('RS_MM_SAF_PROFILE')?>
			</span>
		<?php endif; ?>
	<?php $frame->beginStub(); ?>
		<span class="c-button-control__icon">
			<svg class="icon-svg"><use xlink:href="#svg-user"></use></svg>
		</span>
		<span class="c-button-control__title">
			<?=Loc::getMessage('RS_MM_SAF_ENTER'); ?>
		</span>
	<?php $frame->end(); ?>
</a>
