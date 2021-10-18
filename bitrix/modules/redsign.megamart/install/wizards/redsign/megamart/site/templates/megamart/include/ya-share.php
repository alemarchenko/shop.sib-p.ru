<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}


if (
	$arParams['USE_SHARE'] != 'Y' &&
	!empty($arParams['SOCIAL_SERVICES']) &&
	!is_array($arParams['SOCIAL_SERVICES'])
) {
	return;
}

$this->addExternalJs('https://cdn.jsdelivr.net/npm/yandex-share2/share.js');
?>

<span class="c-ya-share">
	<span <?
		?>class="ya-share2" <?
		?>data-services="<?=implode(',', $arParams['SOCIAL_SERVICES'])?>" <?
		?>data-lang="<?=LANGUAGE_ID?>" <?
		?><?=($arParams['SOCIAL_COUNTER'] == 'Y' ? 'data-counter' : '')?> <?
		?>data-size="<?=$arParams['SOCIAL_SIZE']?>" <?
		?>data-copy="<?=$arParams['SOCIAL_COPY']?>" <?
		?><?php if (!empty($arResult['DETAIL_PICTURE'])): ?>data-image="<?=\CHTTP::URN2URI($arResult['DETAIL_PICTURE'])?>" <?php endif; ?><?
	?>></span>
</span>
