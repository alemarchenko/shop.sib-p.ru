<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;


if(!empty($arResult["CATEGORIES"])): ?>
<div class="w-100">
    <?php foreach($arResult["CATEGORIES"] as $nCategoryId => $arCategory): ?>
        <div class="title-search-cat">

            <?php if (strlen($arCategory['TITLE']) > 0): ?>
            <div class="title-search-item">
                <b><?=$arCategory['TITLE']?></b>
            </div>
            <?php endif; ?>

            <?php 
            foreach ($arCategory["ITEMS"] as  $arItem): 

                if ($arItem['TYPE'] == 'all') continue;

                if (isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
                    $arCatalogItem = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];
                ?>
                    <div class="title-search-item">
                        <a href="<?=$arItem['URL']?>" class="title-search-item__picture">
                        <?php if (is_array($arCatalogItem['PICTURE'])): ?>
                            <img src="<?=$arCatalogItem["PICTURE"]["src"]?>">
                        <?php else: ?>
                            <img src="<?=$templateFolder?>/images/no_photo.png">
                        <?php endif; ?>
                        </a>
            
                        <div class="title-search-item__data">
                            <a href="<?=$arItem['URL']?>" class="title-search-item__name"><?=$arItem["NAME"]?></a>
            
                            <?php if (!empty($arCatalogItem['MIN_PRICE'])): ?>
                                <div class="title-search-item__price">
                                    <?=Loc::getMessage('RS_ST_PRICE_FROM');?>
                                    <span class="title-search-item__price-current"><?=$arCatalogItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span>
                                    <?php if (isset($arCatalogItem["MIN_PRICE"]['DISCOUNT_VALUE']) && $arCatalogItem["MIN_PRICE"]["DISCOUNT_VALUE"] < $arCatalogItem["MIN_PRICE"]["VALUE"]): ?>
                                        <span class="title-search-item__price-discount"><?=$arCatalogItem["MIN_PRICE"]["PRINT_VALUE"]?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="title-search-item"><a href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a></div>
                <?php endif;	?>

            <?php endforeach; ?>
        </div>
        

    <?php endforeach; ?>
</div>
<?php
endif;