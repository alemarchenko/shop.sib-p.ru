<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
    die();
}

use \Bitrix\Main\Localization\Loc;


$this->setFrameMode(true);

if ($arResult['SECTIONS_COUNT'] > 0):

    $layout = new \Redsign\MegaMart\Layouts\Section();
	$layout
		->addModifier('bg-white')
		->addModifier('shadow');

	$layout->start();

    ?><div class="d-block p-4"> <?=Loc::getMessage('RS_CSL_LINKS_TITLE');?><?php 

        $nRemain = $arResult['SECTIONS_COUNT'];

        foreach($arResult['SECTIONS'] as $nIndex => $arSection):
            $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
            $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

            ?><a href="<?=$arSection["SECTION_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>"><?=$arSection["NAME"]?></a><?php

            if (--$nRemain > 0)
            {
                echo ', ';
            }

        endforeach;
    ?></div><?php

    $layout->end();
    unset($layout);
endif;
