<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) 
{
    die();
}

use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\BrandTools;


$nBrandId = '';
if (isset($arResult['VARIABLES']['ELEMENT_ID']))
{
    $nBrandId = (int) $arResult['VARIABLES']['ELEMENT_ID'];
}
elseif (isset($arResult['VARIABLES']['ELEMENT_CODE']))
{
    $nBrandId = BrandTools::getIdByCode(
        $arResult['VARIABLES']['ELEMENT_CODE'],
        $arParams['IBLOCK_ID']
    );
}

$fnSet404 = function ($sMessage404) use ($APPLICATION, $arParams) {
    $APPLICATION->SetPageProperty("hide_section", "N");
    $APPLICATION->SetPageProperty('hide_inner_sidebar', 'Y');
    $APPLICATION->SetPageProperty('hide_outer_sidebar', 'Y');
    $APPLICATION->SetPageProperty('wide_page', 'N');

    \Bitrix\Iblock\Component\Tools::process404(
        $sMessage404,
        $arParams["SET_STATUS_404"] === "Y",
        $arParams["SET_STATUS_404"] === "Y",
        $arParams["SHOW_404"] === "Y",
        $arParams["FILE_404"]
    );
};

if (0 < $nBrandId)
{
    $arCatalogBrandPropFields = BrandTools::getCatalogBrandPropFields(
        $arParams['CATALOG_IBLOCK_ID'], 
        $arParams['CATALOG_BRAND_PROP']
    );
    
    $sBindingType = BrandTools::getBindingType($arCatalogBrandPropFields);

    $sBrandValue = BrandTools::getValue(
        $nBrandId,
        $arParams['IBLOCK_ID'],
        $sBindingType,
        $arParams['BRAND_PROP']
    );

    $request =  \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

    if ($request->get('section'))
    {
        $nSectionId = $request->get('section');

        $rsSection = CIBlockSection::GetById($nSectionId);
        $arSection = $rsSection->GetNext();

        if ($arSection)
        {
            include $_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder().'/include/section.php';
        }
        else
        {
           $fnSet404(Loc::getMessage('RS_N_SECTION_NOT_FOUND'));
        }

    }
    else
    {
        include $_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder().'/include/detail.php';
    }
}
else
{
    $fnSet404(Loc::getMessage('RS_N_BRAND_NOT_FOUND'));
}