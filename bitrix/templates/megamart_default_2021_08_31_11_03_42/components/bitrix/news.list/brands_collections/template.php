<?php 
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) 
{
    die();
}

$this->setFrameMode(true);

if (count($arResult['ITEMS']) > 0): 

    $layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
    $layout
        ->addModifier('bg-white')
        ->addModifier('shadow')
        ->addModifier('outer-spacing');

    $layout->start();

        ?><div class="row row-borders">
            <?php foreach ($arResult['ITEMS'] as $arItem): ?>
            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-5ths col-xl-5ths">
                <div class="brand-collection-item">
                    
                    <div class="brand-collection-item__image-wrapper">
                        <a class="brand-collection-item__image-canvas" href="<?=$arItem['DETAIL_PAGE_URL']?>">
                            <?php
                            if (isset($arItem['PREVIEW_PICTURE']['SRC'])): 

                                $sImagePath = $arItem['PREVIEW_PICTURE']['SRC'];
                                $sAlt = $arItem['PREVIEW_PICTURE']['ALT'];
                                $sTitle = $arItem['PREVIEW_PICTURE']['TITLE'];

                            ?>
                                <img src="<?=$sImagePath?>" alt="<?=$sAlt?>" title="<?=$sTitle?>" class="brand-collection-item__image">
                            <?php 
                            else: 

                                $sImagePath = $templateFolder.'/images/no_photo.png';
                                $sAlt = $arItem['NAME'];
                                $sTitle = $arItem['TITLE'];

                            ?>
                                <img src="<?=$sImagePath?>" alt="<?=$sAlt?>" title="<?=$sTitle?>" class="brand-collection-item__image"> 
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <div class="brand-collection-item__head">
                        <?php 
                        if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_BRAND']])):
                        ?>
                        <div class="brand-collection-item__parent d-none d-sm-block">
                            <?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_BRAND']]['DISPLAY_VALUE']?>
                        </div>
                        <?php endif; ?>
                        
                        <h6 class="brand-collection-item__title">
                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="brand-collection-item__link">
                                <?=$arItem['NAME']?>
                            </a>
                        </h6>
                        
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div><?php

    $layout->end();

endif;