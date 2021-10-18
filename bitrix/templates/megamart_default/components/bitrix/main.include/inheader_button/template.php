<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$sBtnClass = 'btn btn-head btn-rounded btn-'.$arParams['COLOR'];
?>
<a href="<?=$arParams['LINK']?>" class="<?=$sBtnClass?>"<?php if ($arParams['IS_POPUP'] == 'Y') { echo ' data-type="ajax"';}?>">
	<?=$arParams['TITLE']?>
	<svg class="icon-svg"><use xlink:href="#svg-chevron-right"></use></svg>
</a>
