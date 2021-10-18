<?php

use \Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if ($arResult['SECTIONS_COUNT'] < 1)
    return;

$layout = new \Redsign\MegaMart\Layouts\Section();
$layout
    ->addModifier('bg-white')
    ->addModifier('outer-spacing')
    ->addModifier('shadow');

$layout->start();

?><div class="d-block p-4"><h6><?=Loc::getMessage('RS_MM_CSL_VIEW_BUTTONS_TITLE');?></h6><?php

    ?><div><?

    $nRemain = $arResult['SECTIONS_COUNT'];

    foreach ($arResult['SECTIONS'] as $nIndex => $arSection)
    {
        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

        ?><a
            class="btn btn-primary mr-3 mb-3"
            href="<?=$arSection["SECTION_PAGE_URL"]?>"
            id="<?=$this->GetEditAreaId($arSection['ID']);?>"
        ><?=$arSection["NAME"]?></a><?php
    }

    ?></div><?

?></div><?php

$layout->end();

unset($layout);
