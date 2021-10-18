<?php

use \Bitrix\Main\Loader;
use \Redsign\MegaMart\ParametersUtils;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'menu_vml',
	),
);

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array('CONFIRM' => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

if ($arResult["SECTIONS_COUNT"] < 1)
{
    return;
}

$layout = new \Redsign\MegaMart\Layouts\Section();
$layout
    ->addModifier('bg-white')
    ->addModifier('shadow')
    ->addModifier('outer-spacing');

$layout->start();

$sGridClass = 'col-6 col-xs-6 col-sm-6 col-md-6 col-lg-5ths col-xl-5ths';
if (Loader::includeModule('redsign.megamart') && !empty($arParams['GRID_RESPONSIVE_SETTINGS']))
    $sGridClass = ParametersUtils::gridToString($arParams['GRID_RESPONSIVE_SETTINGS']);

$sGridClass = str_replace('-2.4', '-5ths', $sGridClass);

?><div class="row row-borders">
<?php
foreach ($arResult['SECTIONS'] as $arItem): 
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strSectionEdit);
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
?>
<div class="<?=$sGridClass?>">
    <div class="bcsl-blocks-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        
        <div class="bcsl-blocks-item__image-wrapper">
            <a class="bcsl-blocks-item__image-canvas" href="<?=$arItem['SECTION_PAGE_URL']?>">
                <?php
                if (isset($arItem['PICTURE']['SRC'])): 

                    $sImagePath = $arItem['PICTURE']['SRC'];
                    $sAlt = $arItem['PICTURE']['ALT'];
                    $sTitle = $arItem['PICTURE']['TITLE'];

                ?>
                    <img src="<?=$sImagePath?>" alt="<?=$sAlt?>" title="<?=$sTitle?>" class="bcsl-blocks-item__image">
                <?php 
                else: 

                    $sImagePath = $templateFolder.'/images/no_photo.png';
                    $sAlt = $arItem['NAME'];
                    $sTitle = $arItem['TITLE'];

                ?>
                    <img src="<?=$sImagePath?>" alt="<?=$sAlt?>" title="<?=$sTitle?>" class="bcsl-blocks-item__image"> 
                <?php endif; ?>
            </a>
        </div>
        
        <div class="bcsl-blocks-item__head">
            <h6 class="bcsl-blocks-item__title">
                <a href="<?=$arItem['SECTION_PAGE_URL']?>" class="bcsl-blocks-item__link">
                    <?=$arItem['NAME']?>
                </a>
            </h6>
            
        </div>

    </div>
</div>
<?php endforeach; ?>
</div>
<?php

$layout->end();
