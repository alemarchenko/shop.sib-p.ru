<?php

namespace Redsign\MegaMart;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BrandTools
{
    const CACHE_TIME = 36000000;
    const CACHE_DIR = '/redsign/brands';

    const BINDING_TYPE_HIGHLOAD = 1;
    const BINDING_TYPE_ELEMENTS = 2;

    public static function getIdByCode($sCode, $sIblockId)
    {
        $nId = null;

        $cache = \Bitrix\Main\Data\Cache::createInstance(); 
        $sCacheId = serialize([
            'IBLOCK_ID' => $sIblockId,
            'CODE' => $sCode
        ]);

        if ($cache->initCache(self::CACHE_TIME, $sCacheId, self::CACHE_DIR.'/ids'))
        {
            $nId = $cache->getVars();
        }
        elseif (\Bitrix\Main\Loader::includeModule('iblock') && $cache->startDataCache())
        {
            $rsElement = \CIBlockElement::getList(
                [],
                [
                    'IBLOCK_ID' => $sIblockId,
                    'CODE' => $sCode
                ],
                false,
                false,
                ['ID', 'CODE', 'NAME']
            );

            $arElement = $rsElement->Fetch();

            if ($arElement)
            {
                $nId = (int) $arElement['ID'];

                $cache->endDataCache($nId); 
            }
        }

        return $nId;
    }

    public static function getCatalogBrandPropFields($sCatalogIblockId, $sCatalogBrandPropCode)
    {
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        $sCacheId = serialize([
            'CATALOG_IBLOCK_ID' => $sCatalogIblockId,
            'CATALOG_BRAND_PROP_CODE' => $sCatalogBrandPropCode
        ]);

        if ($cache->initCache(self::CACHE_TIME, $sCacheId, self::CACHE_DIR.'/catalog_prop'))
        {
            $arFields = $cache->getVars();
        }
        elseif(\Bitrix\Main\Loader::includeModule('iblock') && $cache->startDataCache())
        {
            $rsProp = \CIBlockProperty::GetList(
                array(
                    'SORT' => 'ASC',
                    'ID' => 'DESC'
                ),
                array(
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $sCatalogIblockId,
                    'CODE' => $sCatalogBrandPropCode
                )
            );

            $arFields = $rsProp->GetNext();

            $cache->endDataCache($arFields); 
        }

        if (!$arFields)
        {
            $arFields = [];
        }

        return $arFields;
    }

    public static function getBindingType($arPropFields)
    {
        if (
            isset($arPropFields['USER_TYPE_SETTINGS']['TABLE_NAME']) &&
            strlen($arPropFields['USER_TYPE_SETTINGS']['TABLE_NAME']) > 0
        )
        {
            return self::BINDING_TYPE_HIGHLOAD;
        }
        else
        {
            return self::BINDING_TYPE_ELEMENTS;
        }
    }

    public static function getValue($nBrandId, $sIblockId, $sBindingType, $sPropCode = null)
    {
        $sBrandValue = null;

        if ($sBindingType == self::BINDING_TYPE_HIGHLOAD)
        {
            $cache = \Bitrix\Main\Data\Cache::createInstance();
            $sCacheId = serialize([
                'CATALOG_IBLOCK_ID' => $sCatalogIblockId,
                'CATALOG_BRAND_PROP_CODE' => $sCatalogBrandPropCode,
                'PROP_CODE' => $sPropCode,
                'BRAND_ID' => $nBrandId
            ]);

            if ($cache->initCache(self::CACHE_TIME, $sCacheId, self::CACHE_DIR.'/catalog_prop'))
            {
                $sBrandValue = $cache->getVars();
            }
            elseif(\Bitrix\Main\Loader::includeModule('iblock') && $cache->startDataCache() && !empty($sPropCode))
            {
                $rsProp = \CIBlockElement::GetProperty($sIblockId, $nBrandId, [], ['CODE' => $sPropCode]);
                $arProp = $rsProp->GetNext();
                
                if ($arProp)
                {
                    $sBrandValue = $arProp['VALUE'];
                }
                else
                {
                    $sBrandValue = $nBrandId;
                }

                
                $cache->endDataCache($sBrandValue); 
            }

        }
        elseif ($sBindingType == self::BINDING_TYPE_ELEMENTS)
        {
            $sBrandValue = $nBrandId;
        }

        return $sBrandValue;
    }

    public static function getSections($sBrandValue, $sPropCode, $sCatalogIblockId, $sSectionUrl)
    {
        $arSections = [];

        $cache = \Bitrix\Main\Data\Cache::createInstance(); 
        $sCacheId = serialize([
            'BRAND_VALUE' => $sBrandValue,
            'CATALOG_IBLOCK_ID' => $sCatalogIblockId,
            'BRAND_PROP_CODE' => $sPropCode,
			'_GET' => $_GET
        ]);

        if ($cache->initCache(self::CACHE_TIME, $sCacheId, self::CACHE_DIR.'/sections'))
        {
            $arSections = $cache->getVars();
        }
        elseif (\Bitrix\Main\Loader::includeModule('iblock') && $cache->startDataCache())
        {
            $rsElements = \CIBlockElement::GetList(
                ['SORT' => 'ASC'], 
                [
                    '=PROPERTY_'.$sPropCode => $sBrandValue,
                    '=IBLOCK_ID' => $sCatalogIblockId
                ],
                ['IBLOCK_SECTION_ID'],
                false,
                ['ID', 'NAME', 'SECTION_ID', 'SORT']
            );

            $arSectionIds = array();

            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache(self::CACHE_DIR.'/sections');

            while($arItem = $rsElements->GetNext())
            {
                if ((int)$arItem['IBLOCK_SECTION_ID'] > 0)
                {
                    $nSectionId = $arItem['IBLOCK_SECTION_ID'];
                    $arSectionIds[] = $nSectionId;

                    $arSections[$nSectionId] = [
                        'ID' => $nSectionId,
                        'CNT' => $arItem['CNT'],
                        'SORT' => $arItem['SORT']
                    ];
                }
            }

            if (count($arSectionIds) > 0)
            {
                $rsSections = \CIBlockSection::GetList(
                    [],
                    [
                        '=ID' => $arSectionIds
                    ],
                    false
                );

                $rsSections->SetUrlTemplates("", $sSectionUrl);

                while ($arSection = $rsSections->GetNext())
                {
                    $nSectionId = $arSection['ID'];

                    if (isset($arSections[$nSectionId]))
                    {

                        $arSections[$nSectionId] = array(
                            'NAME' => $arSection['NAME'],
                            'PAGE_URL' => $arSection['SECTION_PAGE_URL'],
                            'CODE' => $arSection['CODE']
                        );
                    }
                }
            }
			
			$CACHE_MANAGER->RegisterTag("iblock_id_".$sCatalogIblockId);
            $CACHE_MANAGER->RegisterTag("iblock_id_new");
            $CACHE_MANAGER->EndTagCache();

            $cache->endDataCache($arSections); 
        }

        return $arSections;
    }

    public static function getInfo($nBrandId)
    {
        $arData = [];

        $cache = \Bitrix\Main\Data\Cache::createInstance(); 
        $sCacheId = serialize([
            'BRAND_ID' => $nBrandId,
        ]);

        if ($cache->initCache(self::CACHE_TIME, $sCacheId, self::CACHE_DIR.'/brand_info'))
        {
            $arData = $cache->getVars();
        }
        elseif (\Bitrix\Main\Loader::includeModule('iblock') && $cache->startDataCache())
        {
            $rsElement = \CIBlockElement::GetList(
                [],
                [
                    '=ID' => $nBrandId
                ],
                false,
                false,
                ['ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PAGE', 'DETAIL_PAGE_URL']
            );
    
            $arElement = $rsElement->GetNext();
    
            if ($arElement)
            {
                $arData['NAME'] = $arElement['NAME'];
                $arData['PREVIEW_TEXT'] = $arElement['PREVIEW_TEXT'];
                $arData['DETAIL_PAGE_URL'] = $arElement['DETAIL_PAGE_URL'];
                
                if ($arElement['PREVIEW_PICTURE'])
                {
                    $arData['LOGO'] = \CFile::GetFileArray($arElement['PREVIEW_PICTURE']);
                }
            }

            $cache->endDataCache($arData);
        }

        return $arData;
    }
}