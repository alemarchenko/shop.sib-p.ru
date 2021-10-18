<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

if ($arParams['CASUAL_PROPERTIES']['TYPE'] == 'template')
{
	$sImgSrc = $arParams['FILES']['IMG']['SRC'];
	$bShowUrl = $arParams['PROPS']['LINK_URL'] != '' && !isset($arParams['PREVIEW']);
	$sUrl = $arParams['PROPS']['LINK_URL'];
	$sAlt = $arParams['PROPS']['LINK_TITLE'];
	$sUrlTarget = $arParams['PROPS']['LINK_TARGET'];
}
else
{
	$sImgSrc = $arParams['FILES']['CASUAL_IMG']['SRC'];
	$bShowUrl = $arParams['CASUAL_PROPERTIES']['URL'] != '' && !isset($arParams['PREVIEW']);
	$sUrl = $arParams['CASUAL_PROPERTIES']['URL'];
	$sAlt = $arParams['CASUAL_PROPERTIES']['ALT'];
	$sUrlTarget = $arParams['CASUAL_PROPERTIES']['URL_TARGET'];
}

?><div class="l-section l-section--outer-spacing">
	<?php if ($bShowUrl): ?><a href="<?=$sUrl?>" title="<?=$sAlt?>" target="<?=$sUrlTarget?>"><?php endif; ?>
		<div class="b-adv-index-full" style="background-image:url('<?=$sImgSrc?>');"></div>
	<?php if ($bShowUrl): ?></a><?php endif; ?>
</div>
