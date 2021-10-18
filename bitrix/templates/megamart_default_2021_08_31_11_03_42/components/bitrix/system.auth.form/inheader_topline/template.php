<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

if (!isset($arParams['AUTH_URL'])) {
    $arParams['AUTH_URL'] = SITE_DIR.'auth/';
}

if (!isset($arParams['PROFILE_URL'])) {
    $arParams['PROFILE_URL'] = SITE_DIR.'personal/';
}
$this->setFrameMode(true);
?><div class="b-topline-user"><?
	$frame = $this->createFrame()->begin();
		if ($arResult['FORM_TYPE'] == 'login'):
			?>
			<a href="<?=$arParams['AUTH_URL']?>" class="b-topline-user__icon">
				<svg class="icon-svg"><use xlink:href="#svg-user"></use></svg>
			</a>
			<div class="b-topline-user__personal">
				<a class="text-primary" href="<?=$arParams['AUTH_URL']?>"><?=Loc::getMessage('RS_MM_SAF_ENTER');?></a>
				<svg class="icon-svg text-secondary lh-0"><use xlink:href="#svg-chevron-right"></use></svg>
			</div>
			<?php
		else:
			?>
			<a class="b-topline-user__letter" href="<?=$arParams['PROFILE_URL']?>">
				<span class="c-letter">
					<?=(!empty($arResult['USER_NAME']) ? substr($arResult['USER_NAME'], 0, 1) : substr($arResult['USER_LOGIN'], 0, 1)); ?>
				</span>
			</a>
			<div class="b-header-user__personal">
				<a class="text-primary" href="<?=$arParams['PROFILE_URL']?>"><?=!empty($arResult['USER_NAME']) ? $arResult['USER_NAME'] : $arResult['USER_LOGIN'] ;?></a>
			</div>
			<?php
		endif;
	$frame->beginStub();
		?>
		<a href="<?=$arParams['AUTH_URL']?>" class="b-topline-user__icon">
			<svg class="icon-svg"><use xlink:href="#svg-user"></use></svg>
		</a>
		<div class="b-topline-user__personal">
			<a class="text-primary" href="<?=$arParams['AUTH_URL']?>"><?=Loc::getMessage('RS_MM_SAF_ENTER');?></a>
		</div>
		<?php
	$frame->end();
?></div><?
