<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

// use \Bitrix\Main\Page\Asset;

// $asset = Asset::getInstance();

if ($arParams['SET_TITLE'] == 'Y' && intval($arParams['PARENT_SECTION']) > 0)
{
    $arParentSection = array();
    if (is_array($arResult['SECTION']['PATH']) && count($arResult['SECTION']['PATH']) > 0) {
        foreach ($arResult['SECTION']['PATH'] as $arSection) {
            if ($arParams['PARENT_SECTION'] == $arSection['ID']) {
                $arParentSection = $arSection;
            }
        }
        unset($arSection);
    }

    if ($arResult['PARENT_SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '')
    {
        $APPLICATION->SetTitle($arResult['PARENT_SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']);
        $component->arParams["SET_TITLE"] = false;
    }
    elseif (isset($arResult['PARENT_SECTION']['NAME']))
    {
        $APPLICATION->SetTitle($arResult['PARENT_SECTION']['NAME']);
        $component->arParams["SET_TITLE"] = false;
    }
}
