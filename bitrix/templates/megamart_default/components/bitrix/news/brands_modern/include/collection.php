<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Redsign\MegaMart\BrandTools;

$APPLICATION->SetPageProperty("hide_section", "Y");
$APPLICATION->SetPageProperty('hide_inner_sidebar', 'Y');
$APPLICATION->SetPageProperty('hide_outer_sidebar', 'Y');
$APPLICATION->SetPageProperty('wide_page', 'N');


if (!Loader::includeModule('iblock'))
{
    return;
}

$nCollectionId = (int) $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "collection",
    array(
        "DISPLAY_DATE" => 'Y',
        "DISPLAY_NAME" => 'Y',
        "DISPLAY_PICTURE" => 'Y',
        "DISPLAY_PREVIEW_TEXT" => 'Y',
        "IBLOCK_TYPE" => $arParams["COLLECTION_IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["COLLECTION_IBLOCK_ID"],
        "FIELD_CODE" => array(),
        "PROPERTY_CODE" => array(),
        "DETAIL_URL" => '',
        "SECTION_URL" => '',
        "SET_CANONICAL_URL" => 'N',
        "SET_LAST_MODIFIED" => 'N',//$arParams["SET_LAST_MODIFIED"],
        // on filter ajax request
        // Warning: Cannot modify header information - headers already sent by (output started at \bitrix\components\bitrix\catalog.smart.filter\component.php:902)
        // in \bitrix\modules\main\lib\httpresponse.php on line 99
        "SET_TITLE" => 'Y',
        "MESSAGE_404" => $arParams["MESSAGE_404"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "SHOW_404" => $arParams["SHOW_404"],
        "FILE_404" => $arParams["FILE_404"],
        "INCLUDE_IBLOCK_INTO_CHAIN" => 'N',
        "ADD_SECTIONS_CHAIN" => 'N',
        "ADD_ELEMENT_CHAIN" => "Y", 
        "ACTIVE_DATE_FORMAT" => '',
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
        "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
        "DISPLAY_TOP_PAGER" => 'N',
        "DISPLAY_BOTTOM_PAGER" => 'N',
        "PAGER_TITLE" => 'N',
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => 'N',
        "PAGER_SHOW_ALL" => 'N',
        "CHECK_DATES" => 'Y',
        "ELEMENT_ID" => $nCollectionId,
        "ELEMENT_CODE" => '',
        "IBLOCK_URL" => ''
    ),
    $component
);

if ($nCollectionId)
{

    $cache = \Bitrix\Main\Data\Cache::createInstance();
    $sCacheId = serialize([
        'ID' => $nCollectionId
    ]);
    $sCacheDir = 'redsign/collection/brands';

    if ($cache->initCache($arParams['CACHE_TIME'], $sCacheId, $sCacheDir))
    {
        $arCollection = $cache->getVars();
    }
    elseif (\Bitrix\Main\Loader::includeModule('iblock') && $cache->startDataCache())
    {
        $rsCollection = CIBlockElement::GetList(
            [],
            [
                'ID' => $nCollectionId
            ],
            false,
            false,
            ['ID', 'NAME', 'DETAIL_TEXT', 'PROPERTY_'.$arParams['COLLECTION_BRAND_PROP']]
        );

        $arCollection = $rsCollection->GetNext();

        $cache->endDataCache($arCollection);
    }

	if (!empty($arCollection['PROPERTY_'.$arParams['COLLECTION_BRAND_PROP'].'_VALUE']))
	{
		$nBrandId = (int) $arCollection['PROPERTY_'.$arParams['COLLECTION_BRAND_PROP'].'_VALUE'];
		$arBrandInfo = BrandTools::getInfo($nBrandId);

		if ($arBrandInfo)
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
	
			global ${$arParams['CATALOG_FILTER_NAME']};
			${$arParams['CATALOG_FILTER_NAME']} = [];
			${$arParams['CATALOG_FILTER_NAME']}['=PROPERTY_'.$arParams['CATALOG_COLLECTION_PROP']] = $nCollectionId;
		
			$APPLICATION->IncludeFile(
				SITE_DIR.'include/templates/brands/popular.php',
				[
					'IBLOCK_TYPE' => $arParams['CATALOG_IBLOCK_TYPE'],
					'IBLOCK_ID' => $arParams['CATALOG_IBLOCK_ID'],
					'PRICE_CODE' => $arParams['CATALOG_PRICE_CODE'],
					'FILTER_NAME' => $arParams['CATALOG_FILTER_NAME'],
					'BLOCK_NAME' => Loc::getMessage('RS_N_COLLECTION_ITEMS', ["#COLLECTION_NAME#" => $arCollection['NAME']])
				]
			);

			global $arCollectionsFilter;
			$arCollectionsFilter = [];
			$arCollectionsFilter['=PROPERTY_'.$arParams['COLLECTION_BRAND_PROP']] = $sBrandValue;
			$arCollectionsFilter['!ID'] = $nCollectionId;

			$APPLICATION->IncludeFile(
				SITE_DIR.'include/templates/brands/collections.php',
				[
					'IBLOCK_TYPE' => $arParams['COLLECTION_IBLOCK_TYPE'],
					'IBLOCK_ID' => $arParams['COLLECTION_IBLOCK_ID'],
					'PRICE_CODE' => $arParams['CATALOG_PRICE_CODE'],
					'BLOCK_NAME' => Loc::getMessage('RS_N_COLLECTION_OTHER_ITEMS', ["#BRAND_NAME#" => $arBrandInfo['NAME']])
				]
			);
		}
	}
}