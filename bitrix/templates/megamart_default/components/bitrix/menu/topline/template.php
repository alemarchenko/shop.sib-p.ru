<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$menuBlockId = !empty($arParams['RS_MENU_ID']) ? $arParams['RS_MENU_ID'] : 'topline_'.$this->randString();

if (!empty($arResult)):
?>
<div class="topline-menu" id="<?=$menuBlockId?>" style="opacity: 0;">
    <?php
    foreach($arResult as $arItem):   ?>
    <div class="topline-menu-item">
        <a href="<?=$arItem['LINK']?>" class="topline-menu-item__link"><?=$arItem['TEXT']?></a>
    </div>
    <?php endforeach; ?>
    <div class="topline-menu-item is-more has-children">
		<a href="#" class="topline-menu-item__link">
        	<svg class="icon-svg"><use xlink:href="#svg-points"></use></svg>
        </a>
        <div class="topline-menu-item__dropdown"></div>
    </div>
</div>
<script>
  var <?=$menuBlockId.'Obj'?> = new RS.ToplineMenu('<?=$menuBlockId?>');
</script>
<?php endif;
