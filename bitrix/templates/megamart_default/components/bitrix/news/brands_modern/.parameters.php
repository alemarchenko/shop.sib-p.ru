<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arCurrentValues */

use \Bitrix\Iblock;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

global $USER_FIELD_MANAGER;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('iblock'))
	return;

$boolCatalog = Loader::includeModule('catalog');

$usePropertyFeatures = Iblock\Model\PropertyFeature::isEnabledFeatures();

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$fnIBlocks = function ($sIblockType) {
	$arIBlock = [];

	$iblockFilter = (
		!empty($sIblockType)
		? ['TYPE' => $sIblockType, 'ACTIVE' => 'Y']
		: ['ACTIVE' => 'Y']
	);

	$rsIBlock = CIBlock::GetList(['SORT' => 'ASC'], $iblockFilter);

	while ($arr = $rsIBlock->Fetch())
	{
		$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
	}

	return $arIBlock;
};

$arAllPropList = array();
$arListPropList = array();
$arHighloadPropList = array();
$arFilePropList = $defaultValue;

if (isset($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0)
{
	$rsProps = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsProps->Fetch())
	{
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE'])
		{
			$arProp['CODE'] = $arProp['ID'];
		}

		$arAllPropList[$arProp['CODE']] = $strPropName;

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE)
		{
			$arFilePropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
		{
			$arListPropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
		{
			$arHighloadPropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT)
		{
			$arLinkElementPropList[$arProp['CODE']] = $strPropName;
		}
	}
}

$defaultValue = array('-' => Loc::getMessage('CP_BC_TPL_PROP_EMPTY'));

// catalog
$arAllCatalogPropList = array();
$arCatalogListPropList = array();
$arCatalogHighloadPropList = array();
$arCatalogFilePropList = $defaultValue;
$arCatalogLinkPropList = array();

$arCatalogProperty = array();
$arCatalogProperty_N = array();
$arCatalogProperty_X = array();
$arCatalogProperty_F = array();

$iblockCatalogExists = (!empty($arCurrentValues['CATALOG_IBLOCK_ID']) && (int)$arCurrentValues['CATALOG_IBLOCK_ID'] > 0);
$boolIsCatalog = false;
$arSKU = false;
$boolSKU = false;

if ($boolCatalog && $iblockCatalogExists)
{
	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['CATALOG_IBLOCK_ID']);
	// $boolSKU = !empty($arSKU) && is_array($arSKU);

	$arCatalog = CCatalog::GetByIDExt($arCurrentValues['CATALOG_IBLOCK_ID']);
	if (false !== $arCatalog)
	{
		$boolIsCatalog = true;
	}
}

$rsProps = CIBlockProperty::GetList(
	array('SORT' => 'ASC', 'ID' => 'ASC'),
	array('IBLOCK_ID' => $arCurrentValues['CATALOG_IBLOCK_ID'], 'ACTIVE' => 'Y')
);

while ($arProp = $rsProps->Fetch())
{
	$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
	if ('' == $arProp['CODE'])
	{
		$arProp['CODE'] = $arProp['ID'];
	}

	$arAllCatalogPropList[$arProp['CODE']] = $strPropName;

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE)
	{
		$arCatalogFilePropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
	{
		$arCatalogListPropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
	{
		$arCatalogHighloadPropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_NUMBER)
	{
		$arCatalogNumPropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT)
	{
		$arCatalogLinkPropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] != Iblock\PropertyTable::TYPE_FILE)
	{
		if ($arProp['MULTIPLE'] == 'Y')
			$arCatalogProperty_X[$arProp['CODE']] = $strPropName;
		elseif ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
			$arCatalogProperty_X[$arProp['CODE']] = $strPropName;
		elseif ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT && (int)$property['LINK_IBLOCK_ID'] > 0)
			$arCatalogProperty_X[$arProp['CODE']] = $strPropName;
	}
}

$arSort = CIBlockParameters::GetElementSortFields(
	array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
	array('KEY_LOWERCASE' => 'Y')
);

$arPrice = array();
if ($boolIsCatalog)
{
	$arSort = array_merge($arSort, CCatalogIBlockParameters::GetCatalogSortFields());
	$arPrice = CCatalogIBlockParameters::getPriceTypesList();
}
else
{
	$arPrice = $arCatalogNumPropList;
}

//collection
$iblockCollectionExists = (!empty($arCurrentValues['COLLECTION_IBLOCK_ID']) && (int)$arCurrentValues['COLLECTION_IBLOCK_ID'] > 0);
$rsProps = CIBlockProperty::GetList(
	array('SORT' => 'ASC', 'ID' => 'ASC'),
	array('IBLOCK_ID' => $arCurrentValues['COLLECTION_IBLOCK_ID'], 'ACTIVE' => 'Y')
);
while ($arProp = $rsProps->Fetch())
{
	$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
	if ('' == $arProp['CODE'])
	{
		$arProp['CODE'] = $arProp['ID'];
	}

	$arAllCollectionPropList[$arProp['CODE']] = $strPropName;

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE)
	{
		$arCollectionFilePropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
	{
		$arCollectionListPropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
	{
		$arCollectionHighloadPropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_NUMBER)
	{
		$arCollectionNumPropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT)
	{
		$arCollectionLinkPropList[$arProp['CODE']] = $strPropName;
	}

	if ($arProp['PROPERTY_TYPE'] != Iblock\PropertyTable::TYPE_FILE)
	{
		if ($arProp['MULTIPLE'] == 'Y')
			$arCollectionProperty_X[$arProp['CODE']] = $strPropName;
		elseif ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
			$arCollectionProperty_X[$arProp['CODE']] = $strPropName;
		elseif ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT && (int) $property['LINK_IBLOCK_ID'] > 0)
			$arCollectionProperty_X[$arProp['CODE']] = $strPropName;
	}
}

$arTemplateParameters['CATALOG_IBLOCK_TYPE'] = array(
	'PARENT' => 'DATA_SOURCE',
	'NAME' => Loc::getMessage('RS_MM_CATALOG_IBLOCK_TYPE'),
	"TYPE" => "LIST",
	"VALUES" => $arIBlockType,
	"REFRESH" => "Y",
);

$arTemplateParameters['CATALOG_IBLOCK_ID'] = array(
	'PARENT' => 'DATA_SOURCE',
	'NAME' => Loc::getMessage('RS_MM_CATALOG_IBLOCK_ID'),
	'TYPE' => 'LIST',
	'ADDITIONAL_VALUES' => 'Y',
	'VALUES' => $fnIBlocks(isset($arCurrentValues['CATALOG_IBLOCK_TYPE']) ? $arCurrentValues['CATALOG_IBLOCK_TYPE'] : ''),
	'DEFAULT' => '',
	'REFRESH' => 'Y',
);

$arTemplateParameters['CATALOG_PRICE_CODE'] = array(
	'PARENT' => 'DATA_SOURCE',
	'NAME' => Loc::getMessage('RS_MM_CATALOG_PRICE_CODE'),
	"TYPE" => "LIST",
	"MULTIPLE" => "Y",
	"VALUES" => $arPrice,
);

if ($iblockCatalogExists)
{
	$arTemplateParameters['CATALOG_BRAND_PROP'] = array(
		'PARENT' => 'DATA_SOURCE',
		"NAME" => GetMessage('RS_MM_CATALOG_BRAND_PROP'),
		"TYPE" => "LIST",
		"VALUES" => $defaultValue + $arCatalogLinkPropList + $arCatalogHighloadPropList,
		"MULTIPLE" => "N",
		'REFRESH' => 'Y',
	);

	$arTemplateParameters['CATALOG_COLLECTION_PROP'] = array(
		'PARENT' => 'DATA_SOURCE',
		"NAME" => GetMessage('RS_MM_CATALOG_COLLECTION_PROP'),
		"TYPE" => "LIST",
		"VALUES" => $defaultValue + $arCatalogLinkPropList,
		"MULTIPLE" => "N",
		'REFRESH' => 'Y',
	);

	$arTemplateParameters['CATALOG_FILTER_NAME'] = array(
		'PARENT' => 'DATA_SOURCE',
		'NAME' => Loc::getMessage('RS_MM_CATALOG_FILTER_NAME'),
		'TYPE' => 'STRING',
		'DEFAULT' => 'arrCatalogFilter'
	);
}

if (isset($arCurrentValues['CATALOG_BRAND_PROP']) && $arCurrentValues['CATALOG_BRAND_PROP'] != '-')
{
	if (isset($arCatalogHighloadPropList[$arCurrentValues['CATALOG_BRAND_PROP']]))
	{
		$arTemplateParameters['BRAND_PROP'] = array(
			'PARENT' => 'DATA_SOURCE',
			'NAME' => GetMessage('RS_MM_BRAND_PROP'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arHighloadPropList,
			"MULTIPLE" => "N",
			'DEFAULT' => '-',
			"REFRESH" => "Y",
		);
	}
}

$arTemplateParameters['COLLECTION_IBLOCK_TYPE'] = array(
	'PARENT' => 'DATA_SOURCE',
	'NAME' => Loc::getMessage('RS_MM_COLLECTION_IBLOCK_TYPE'),
	"TYPE" => "LIST",
	"VALUES" => $arIBlockType,
	"REFRESH" => "Y",
);

$arTemplateParameters['COLLECTION_IBLOCK_ID'] = array(
	'PARENT' => 'DATA_SOURCE',
	'NAME' => Loc::getMessage('RS_MM_COLLECTION_IBLOCK_ID'),
	'TYPE' => 'LIST',
	'ADDITIONAL_VALUES' => 'Y',
	'VALUES' => $fnIBlocks(isset($arCurrentValues['COLLECTION_IBLOCK_TYPE']) ? $arCurrentValues['COLLECTION_IBLOCK_TYPE'] : ''),
	'DEFAULT' => '',
	'REFRESH' => 'Y',
);

if ($iblockCollectionExists)
{
	if (isset($arCatalogHighloadPropList[$arCurrentValues['CATALOG_BRAND_PROP']]))
	{
		$arTemplateParameters['COLLECTION_BRAND_PROP'] = array(
			'PARENT' => 'DATA_SOURCE',
			"NAME" => Loc::getMessage('RS_MM_COLLECTION_BRAND_PROP'),
			"TYPE" => "LIST",
			"VALUES" => $defaultValue + $arCollectionLinkPropList,
			"MULTIPLE" => "N",
			'REFRESH' => 'Y',
		);
	}

}

$arTemplateParameters['FILTER_USE_HIDE_NOT_AVAILABLE'] = array(
	'PARENT' => 'DATA_SOURCE',
	'NAME' => Loc::getMessage('RS_MM_FILTER_USE_HIDE_NOT_AVAILABLE'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);