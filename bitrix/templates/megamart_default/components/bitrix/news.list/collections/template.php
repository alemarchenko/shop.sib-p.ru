<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) 
{
    die();
}
?> <div class="d-block">
    <?php foreach ($arResult['ITEMS'] as $arItem): ?>
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn btn-outline-primary m-2"><?=$arItem['NAME']?></a>
    <?php endforeach; ?>
</div>