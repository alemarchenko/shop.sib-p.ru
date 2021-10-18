<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) 
{
    die();
}

if (!empty($arResult['DETAIL_TEXT'])):
?>
<div class="brand-detail-block">
    
    <?php if (isset($arResult['DETAIL_PICTURE']['SRC'])): ?>
    <div class="brand-detail-block__picture">
        <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['NAME']?>" title="<?=$arResult['TITLE']?>">    
    </div>
    <?php endif; ?>
    
    <div class="brand-detail-block__desc">
        <h5 class="brand-detail-block__name">
            <?=$arResult['NAME']?>
        </h5>
        <div lass="brand-detail-block__desc-text">
            <?=$arResult['DETAIL_TEXT']?>
        </div>
    </div>

</div>
<?php
endif;