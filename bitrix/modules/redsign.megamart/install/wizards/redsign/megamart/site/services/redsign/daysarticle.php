<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Loader;

$module_id = 'redsign.daysarticle2';

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
			'apple-macbook-air-13-mid-2017' => array(
				'DISCOUNT' => 50,
				'VALUE_TYPE' => 'P',
				'CURRENCY' => 'RUB',
				'QUANTITY' => 18,
				'AUTO_RENEWAL' => 'Y',
				'DINAMICA' => "evenly",
				'DINAMICA_DATA' => array(),
			),
		),
		'offers' => array(
			'apple-iphone-x-64-gb-seryy-kosmos' => array(
				'DISCOUNT' => 50,
				'VALUE_TYPE' => 'P',
				'CURRENCY' => 'RUB',
				'QUANTITY' => 100,
				'DINAMICA' => "evenly",
				'DINAMICA_DATA' => array(),
			),
			'krossovki-dyneckt-s-naptik-krasnyy-37' => array(
				'DISCOUNT' => 50,
				'VALUE_TYPE' => 'P',
				'CURRENCY' => 'RUB',
				'QUANTITY' => 18,
				'DINAMICA' => "evenly",
				'DINAMICA_DATA' => array(),
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
			$insert = 24 * 60 * 60;
			$arFields = array(
				'ELEMENT_ID' => $arElement['ID'],
				'ACTIVE' => 'Y',
				'AUTO_RENEWAL' => 'Y',
				'DATE_FROM' => ConvertTimeStamp($time, 'FULL', 'ru'),
				'DATE_TO' => ConvertTimeStamp(($time + $insert), 'FULL', 'ru'),
				'DISCOUNT' => $arIBlock[$arElement['CODE']]['DISCOUNT'],
				'QUANTITY' => $arIBlock[$arElement['CODE']]['QUANTITY'],
				'DINAMICA' => $arIBlock[$arElement['CODE']]['DINAMICA'],
				'DINAMICA_DATA' => serialize($arIBlock[$arElement['CODE']]['DINAMICA_DATA']),
			);

			$da2e = new CRSDA2Elements();
			$da2e->Add($arFields);

			$index++;
		}
		unset($arRes, $arElement);
	}
	unset($sIBlockCode, $arIBlock);
}