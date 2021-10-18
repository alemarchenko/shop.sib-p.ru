<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Loader;

$module_id = 'redsign.quickbuy';

if ($obModule = CModule::CreateModuleObject($module_id))
{
	if (!$obModule->IsInstalled())
	{
		$obModule->InstallDB();
		$obModule->InstallEvents();
		$obModule->InstallOptions();
		$obModule->InstallFiles();
		$obModule->InstallPublic();
	}
}

if (
	WIZARD_INSTALL_DEMO_DATA
	&& Loader::includeModule('iblock')
	&& Loader::includeModule($module_id)
) {
	// take some N iblock_elements
	$arFilterIBlocks = array(
		array(
			'IBLOCK_TYPE' => 'catalog',
			'IBLOCK_CODE' => 'catalog',
			'IBLOCK_XML_ID' => 'redsign_megamart_catalog_catalog_'.WIZARD_SITE_ID,
		),
		array(
			'IBLOCK_TYPE' => 'offers',
			'IBLOCK_CODE' => 'offers',
			'IBLOCK_XML_ID' => 'redsign_megamart_offers_offers_'.WIZARD_SITE_ID,
		),
	);

	foreach($arFilterIBlocks as $arFilterIBlock)
	{
		$rsIBlock = CIBlock::GetList(
			array(),
			array(
				'TYPE' => $arFilterIBlock['IBLOCK_TYPE'],
				'CODE' => $arFilterIBlock['IBLOCK_CODE'],
				'XML_ID' => $arFilterIBlock['IBLOCK_XML_ID']
			)
		);

		if ($arIBlock = $rsIBlock->Fetch())
		{
			$arrIBlockIDs[$arFilterIBlock['IBLOCK_CODE']] = $arIBlock['ID'];
		}
	}
	unset($arFilterIBlock, $rsIBlock, $arIBlock);

	$arFilterElements = array(
		'catalog' => array(
			'apple-iphone-8' => array(
				'ACTIVE' => 'Y',
				'DISCOUNT' => 20,
				'VALUE_TYPE' => 'P',
				'CURRENCY' => 'RUB',
				'QUANTITY' => 100,
				'AUTO_RENEWAL' => 'Y',
			),
			'smartfon-samsung-galaxy-s8' => array(
				'ACTIVE' => 'Y',
				'DISCOUNT' => 20,
				'VALUE_TYPE' => 'P',
				'CURRENCY' => 'RUB',
				'QUANTITY' => 100,
				'AUTO_RENEWAL' => 'Y',
			),
			'apple-macbook-air-13-mid-2017' => array(
				'ACTIVE' => 'Y',
				'DISCOUNT' => 20,
				'VALUE_TYPE' => 'P',
				'CURRENCY' => 'RUB',
				'QUANTITY' => 100,
				'AUTO_RENEWAL' => 'Y',
			),
		),
		'offers' => array(
			'apple-iphone-x-64-gb-serebristyy' => array(
				'ACTIVE' => 'Y',
				'DISCOUNT' => 20,
				'VALUE_TYPE' => 'P',
				'CURRENCY' => 'RUB',
				'QUANTITY' => 100,
				'AUTO_RENEWAL' => 'Y',
			),
		),
	);

	$index = 0;
	$time = time();

	foreach ($arFilterElements as $sIBlockCode => $arIBlock)
	{
		$arElementsCode = array();
		foreach ($arIBlock as $sElementCode => $arElement)
		{
			$arElementsCode[] = $sElementCode;
		}
		unset($sElementCode, $arElement);

		$arRes = CIBlockElement::GetList(
			array(
				'SORT' => 'ASC'
			),
			array(
				'IBLOCK_ID' => $arrIBlockIDs[$sIBlockCode],
				'CODE' => $arElementsCode
			)
		);

		while($arElement = $arRes->GetNext())
		{
			$insert = 24 * 60 * 60 * (15 + $index);
			$arIBlock[$arElement['CODE']]['ELEMENT_ID'] = $arElement['ID'];
			$arIBlock[$arElement['CODE']]['DATE_FROM'] = ConvertTimeStamp(($time), 'FULL', 'ru');;
			$arIBlock[$arElement['CODE']]['DATE_TO'] = ConvertTimeStamp(($time+$insert), 'FULL', 'ru');
			
			$qbe = new CRSQUICKBUYElements();
			$qbe->Add($arIBlock[$arElement['CODE']]);

			$index++;
		}
		unset($arRes, $arElement);
	}
	unset($sIBlockCode, $arIBlock);
}