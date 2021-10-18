<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

use \Bitrix\Main\Loader;
use \Bitrix\Iblock;

if (!Loader::includeModule('iblock'))
{
    return;
}

$arParams['CATALOG_IBLOCK_ID'] = intval($arParams['CATALOG_IBLOCK_ID']);

if (!isset($arParams['CATALOG_BRAND_PROP']) || strlen($arParams['CATALOG_BRAND_PROP']) <= 0)
{
    $arParams['CATALOG_BRAND_PROP'] = false;
}

if (intval($arParams['CATALOG_IBLOCK_ID']) > 0 && strlen($arParams['CATALOG_BRAND_PROP']) > 0)
{
    $propRes = CIBlockProperty::GetList(
        array(
            'SORT' => 'ASC',
            'ID' => 'DESC'
        ),
        array(
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $arParams['CATALOG_IBLOCK_ID'],
            'CODE' => $arParams['CATALOG_BRAND_PROP']
        )
    );
    if ($arFields = $propRes->GetNext())
	{
        $arResult['CATALOG_BRAND_PROP'] = $arFields;
    }

    $arSectionFilter = array();

    if (
        isset($arResult['CATALOG_BRAND_PROP']['USER_TYPE_SETTINGS']['TABLE_NAME']) &&
        strlen($arResult['CATALOG_BRAND_PROP']['USER_TYPE_SETTINGS']['TABLE_NAME']) > 0 &&
        !empty($arResult['PROPERTIES'][$arParams['BRAND_PROP']]['VALUE'])
    )
	{
        $arOrder = array();
        $arFilter = array(
            'ACTIVE' => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'IBLOCK_ID' => $arParams['CATALOG_IBLOCK_ID'],
            'PROPERTY_'.$arResult['CATALOG_BRAND_PROP']['ID'].'_VALUE' => $arResult['PROPERTIES'][$arParams['BRAND_PROP']]['VALUE']
        );
        $arGroupBy = array(
            'IBLOCK_SECTION_ID'
        );
        $arNavStartParams = false;
        $arSelect = array(
            'PROPERTY_'.$arResult['CATALOG_BRAND_PROP']['ID'],
        );

        $dbElement = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelect);

        $arResult['CATALOG_FILTER'] = array();
        $filterKey = '=PROPERTY_'.$arResult['CATALOG_BRAND_PROP']['ID'];
        $arResult['CATALOG_FILTER'][$filterKey] = $arResult['PROPERTIES'][$arParams['BRAND_PROP']]['VALUE'];
    }
	elseif (
        $arResult['CATALOG_BRAND_PROP']['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT
		&& intval($arResult['CATALOG_BRAND_PROP']['LINK_IBLOCK_ID']) > 0
    )
	{
        $arOrder = array();
        $arFilter = array(
            'ACTIVE' => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'IBLOCK_ID' => $arParams['CATALOG_IBLOCK_ID'],
            'PROPERTY_'.$arResult['CATALOG_BRAND_PROP']['ID'].'_VALUE' => $arResult['ID']
        );
        $arGroupBy = array(
            'IBLOCK_SECTION_ID'
        );
        $arNavStartParams = false;
        $arSelect = array(
            'NAME',
            'PROPERTY_'.$arResult['CATALOG_BRAND_PROP']['ID'],
            'IBLOCK_SECTION_ID',
        );

        $dbElement = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect);

        $arResult['CATALOG_FILTER'] = array();
        $filterKey = '=PROPERTY_'.$arResult['CATALOG_BRAND_PROP']['ID'];
        $arResult['CATALOG_FILTER'][$filterKey] = $arResult['ID'];
    }

    if (isset($dbElement) && $dbElement)
	{
        while ($arElement = $dbElement->GetNext())
		{
            if (!in_array($arElement['IBLOCK_SECTION_ID'], $arSectionFilter))
			{
                $arSectionFilter[] = $arElement['IBLOCK_SECTION_ID'];
            }
        }
    }

    if (is_array($arSectionFilter) && count($arSectionFilter) > 0)
	{
        $this->__component->arResult['CATALOG_SECTION_FILTER'] = $arSectionFilter;

        $this->__component->arResult['CATALOG_FILTER'] = $arResult['CATALOG_FILTER'];

        if ($arResult['CATALOG_BRAND_PROP']['CODE'])
		{
			if (isset($arResult['PROPERTIES'][$arParams['BRAND_PROP']]['VALUE']))
			{
				$this->__component->arResult['SMART_FILTER_PATH'] = toLower($arResult['CATALOG_BRAND_PROP']['CODE']).'-is-'.toLower($arResult['PROPERTIES'][$arParams['BRAND_PROP']]['VALUE']);
			}
			else
			{
				$this->__component->arResult['SMART_FILTER_PATH'] = toLower($arResult['CATALOG_BRAND_PROP']['CODE']).'-is-'.toLower($arResult['CODE']);
			}
        }
		else
		{
            $this->__component->arResult['SMART_FILTER_PATH'] = $arResult['PROPERTIES'][$arParams['BRAND_PROP']]['VALUE'].'-is-'.toLower($arResult['CATALOG_BRAND_PROP']['ID']);
        }
    }
}

$this->__component->SetResultCacheKeys(array('CATALOG_FILTER', 'SMART_FILTER_PATH', 'CATALOG_SECTION_FILTER'));
