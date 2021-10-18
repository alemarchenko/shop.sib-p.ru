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
?><div class="b-header-user"><?
	$frame = $this->createFrame()->begin();
		if ($arResult['FORM_TYPE'] == 'login'):
			?>
			<a href="<?=$arParams['AUTH_URL']?>" class="b-header-user__icon">
				<svg class="icon-svg"><use xlink:href="#svg-user"></use></svg>
			</a>
			<div class="b-header-user__personal">
				<div class="d-block"><span class="font-weight-bold"><?=Loc::getMessage('RS_MM_SAF_PERSONAL');?></span></div>
				<div class="d-block small text-primary text-nowrap">
					<a class="text-primary" href="<?=$arParams['AUTH_URL']?>"><?=Loc::getMessage('RS_MM_SAF_ENTER');?></a>
					|
					<a class="text-primary" href="<?=$arParams['AUTH_URL']?>?register=yes"><?=Loc::getMessage('RS_MM_SAF_REGISTRATION');?></a>
					<svg class="icon-svg text-secondary lh-0"><use xlink:href="#svg-chevron-right"></use></svg>
				</div>
			</div>
			<?php
		else:
			?>
			<a class="b-header-user__letter" href="<?=$arParams['PROFILE_URL']?>">
				<span class="c-letter">
					<?=(!empty($arResult['USER_NAME']) ? mb_substr($arResult['USER_NAME'], 0, 1) : mb_substr($arResult['USER_LOGIN'], 0, 1)); ?>
				</span>
			</a>
			<div class="b-header-user__personal">
				<div class="d-block"><span class="font-weight-bold"><?=Loc::getMessage('RS_MM_SAF_PERSONAL');?></span></div>
				<div class="d-block small text-primary">
					<a class="text-primary" href="<?=$arParams['PROFILE_URL']?>"><?=!empty($arResult['USER_NAME']) ? $arResult['USER_NAME'] : $arResult['USER_LOGIN'] ;?></a>
				</div>
			</div>
			<?php
		endif;
	$frame->beginStub();
		?>
		<a href="<?=$arParams['AUTH_URL']?>" class="b-header-user__icon">
			<svg class="icon-svg"><use xlink:href="#svg-user"></use></svg>
		</a>
		<div class="b-header-user__personal">
			<div class="d-block"><span class="font-weight-bold"><?=Loc::getMessage('RS_MM_SAF_PERSONAL');?></span></div>
			<div class="d-block small text-primary text-nowrap">
				<a class="text-primary" href="<?=$arParams['AUTH_URL']?>"><?=Loc::getMessage('RS_MM_SAF_ENTER');?></a>
				|
				<a class="text-primary" href="#"><?=Loc::getMessage('RS_MM_SAF_REGISTRATION');?></a>
				<svg class="icon-svg text-secondary lh-0"><use xlink:href="#svg-chevron-right"></use></svg>
			</div>
		</div>
		<?php
	$frame->end();
?></div><?
