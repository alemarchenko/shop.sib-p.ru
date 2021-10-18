<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) 
{
    die();
}

if (!empty($arResult['DETAIL_TEXT'])):

    $layout = new \Redsign\MegaMart\Layouts\Section();

    $layout
        ->addModifier('bg-white')
        ->addModifier('shadow')
        ->addModifier('outer-spacing')
        ->addModifier('inner-spacing');

    $layout->start();
?>
    <div class="collection-detail-block">
        
        <?php if (isset($arResult['DETAIL_PICTURE']['SRC'])): ?>
        <div class="collection-detail-block__picture">
            <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['NAME']?>" title="<?=$arResult['TITLE']?>">    
        </div>
        <?php endif; ?>
        
        <div class="collection-detail-block__desc">
            <h5 class="collection-detail-block__name">
                <?=$arResult['NAME']?>
            </h5>
            <div lass="collection-detail-block__desc-text">
                <?=$arResult['DETAIL_TEXT']?>
            </div>
        </div>

    </div>
<?php
    $layout->end();
endif;