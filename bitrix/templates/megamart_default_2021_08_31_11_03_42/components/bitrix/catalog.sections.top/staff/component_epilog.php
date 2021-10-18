<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH.'/components/bitrix/news.list/staff/style.css');

if ($arParams['SET_TITLE'] == 'Y')
{
    if ($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '')
    {
        $APPLICATION->SetTitle($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']);
    }
    elseif (isset($arResult['SECTION']['NAME']))
    {
        $APPLICATION->SetTitle($arResult['SECTION']['NAME']);
    }
}

if ($arParams['ADD_SECTIONS_CHAIN'] == 'Y' && is_array($arResult['PATH']))
{
    foreach ($arResult['PATH'] as $path)
    {
        if ($path['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '')
        {
            $APPLICATION->AddChainItem($path['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $path['~SECTION_PAGE_URL']);
        }
        else
        {
            $APPLICATION->AddChainItem($path['NAME'], $path['~SECTION_PAGE_URL']);
        }
    }
}
