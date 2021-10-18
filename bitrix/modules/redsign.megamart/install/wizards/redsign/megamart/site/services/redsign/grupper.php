<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Loader;

$module_id = 'redsign.grupper';

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
	WIZARD_INSTALL_DEMO_DATA &&
	CModule::IncludeModule('iblock') &&
	CModule::IncludeModule('redsign.grupper')
) {
	
	$dbSite = CSite::GetByID(WIZARD_SITE_ID);
	if($arSite = $dbSite -> Fetch())
		$lang = $arSite["LANGUAGE_ID"];
	if(strlen($lang) <= 0)
		$lang = "ru";

	WizardServices::IncludeServiceLang('grupper.php', $lang);
	
	// take some N iblock_properties
	$arrFilter1 = array(
		array(
			'IBLOCK_TYPE' => 'catalog',
			'IBLOCK_CODE' => 'catalog',
			'IBLOCK_XML_ID' => 'redsign_megamart_catalog_catalog_'.WIZARD_SITE_ID,
		),
	);
	
	foreach ($arrFilter1 as $filter1)
	{
		$rsIBlock = CIBlock::GetList(
			array(),
			array(
				'TYPE' => $filter1['IBLOCK_TYPE'],
				'CODE' => $filter1['IBLOCK_CODE'],
				'XML_ID' => $filter1['IBLOCK_XML_ID']
			)
		);

		if ($arIBlock = $rsIBlock->Fetch())
		{
			$code1 = $filter1['IBLOCK_CODE'];
			$arrIBlockIDs[$code1] = $arIBlock['ID'];
		}
		unset($rsIBlock, $arIBlock);
	}
	unset($filter1);
	
	$arGroups = array(
		array(
			'NAME' => GetMessage('GROUP_NAME_COMMON'),
			'CODE' => 'COMMON',
			'SORT' => 100,
			'BINDS' => array(
				'SMARTWATCH_SOFT_INSTALL',
				'SMARTWATCH_VIBRATION',
				'BASE_SUPPORTED_DEVICE',
				'SMARTWATCH_CONTROL',
				'BRAND_REF',
				'OPERATION_SYSTEM',
				'OPERATING_SYSTEM',
				'BASE_SUPPORTED_OS',
				'TV_PRISTAVKI_SUPP_HD',
				'TV_PRISTAVKI_TYPE',
				'TV_PRISTAVKI_OS',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_DESIGN'),
			'CODE' => 'DESIGN',
			'SORT' => 200,
			'BINDS' => array(
				'DESIGN_GLASS_TYPE',
				'WEIGHT',
				'PRINTER_SIZE',
				'SMARTWATCH_STRAP_SIZES',
				'SMARTWATCH_STRAP_REPLACED',
				'DESIGN_WATERRESIST',
				'DESIGN_IMPACT_PROTECTION',
				'SMARTWATCH_TIME',
				'MATERIAL',
				'SMARTWATCH_CASE',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_DISPLAY'),
			'CODE' => 'DISPLAY',
			'SORT' => 300,
			'BINDS' => array(
				'PIXELS_PER_INCH',
				'DISPLAY',
				'DIAGONAL',
				'MONITOR_DIAGONAL',
				'SCREEN_RESOLUTION',
				'MONITOR_SCREEN_RESOLUTION',
				'MONITOR_TYPE',
				'MONITOR_TYPE_MATRIX',
				'SCREEN_TYPE',
				'SMARTWATCH_SCREEN',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_MULTIMEDIA'),
			'CODE' => 'MULTIMEDIA',
			'SORT' => 400,
			'BINDS' => array(
				'MULTIMEDIA_VIDEO',
				'MULTIMEDIA_AUDIO',
				'MULTIMEDIA_HEADSET',
				'MULTIMEDIA_MICROPHONE',
				'MULTIMEDIA_SPEAKER',
				'MULTIMEDIA_BLUETOOTH_AUDIO',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_COMUNICATION'),
			'CODE' => 'COMUNICATION',
			'SORT' => 500,
			'BINDS' => array(
				'CONNECT_INTERFACES',
				'CONNECT_NAVIGATION',
				'SMARTWATCH_MOBILE_INTERNET',
				'CONNECT_PHONE_CALLS',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_HARDWARE'),
			'CODE' => 'HARDWARE',
			'SORT' => 600,
			'BINDS' => array(
				'PROCESSOR_ROM',
				'PRINTER_PROCESSOR_FREQ',
				'NOOTEBOOK_NUM_CORE',
				'PROCESSOR_CORES',
				'PROCESSOR',
				'NOTEBOOK_PROC',
				'PROCESSOR_PROCESSOR',
				'PRINTER_PROCESSOR',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_FUNCTIONS'),
			'CODE' => 'FUNCTIONS',
			'SORT' => 700,
			'BINDS' => array(
				'FUNCTIONALITY_STOPWATCH',
				'SLEEP_TIMER',
				'FUNCTIONALITY_TIMER',
				'FUNCTIONALITY_SENSORS',
				'FUNCTIONALITY_MONITORING',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_POWER'),
			'CODE' => 'POWER',
			'SORT' => '800',
			'BINDS' => array(
				'BATTERY_CHARGING_TIME',
				'BATTERY_LIFE_WAITING',
				'BATTERY_CHARGING_WIRELESS',
				'BATTERY_CHARGING_CONNECTOR',
				'BATTERY_LIFE_ACTIVE',
				'BATTERY_CAPACITY',
				'BATTERY_BATTERY',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_FORMAT_SUPPORT'),
			'CODE' => 'FORMAT_SUPPORT',
			'SORT' => 900,
			'BINDS' => array(
				'TV_PRISTAVKI_GRAHP_FILES',
				'TV_PRISTAVKI_AUDIO_FILES',
				'TV_PRISTAVKI_KODEC',
				'TV_PRISTAVKI_FILE_FORMAT',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_CONNECTION'),
			'CODE' => 'CONNECTION',
			'SORT' => 1000,
			'BINDS' => array(
				'TV_PRISTAVKI_SUPP_AIRPLAY',
				'TV_PRISTAVKI_SPEED_ETHER',
				'TV_PRISTAVKI_INTERF',
				'TV_PRISTAVKI_OUTPUT',
			),
		),
		array(
			'NAME' => GetMessage('GROUP_NAME_CONSTRUCTION'),
			'CODE' => 'CONSTRUCTION',
			'SORT' => '200',
			'BINDS' => array(
				'TV_PRISTAVKI_COLD',
				'TV_PRISTAVKI_BLOCK',
				'TV_PRISTAVKI_FLASH_MEM',
				'TV_PRISTAVKI_MEM_SIZE',
				'TV_PRISTAVKI_PROCESSOR',
				'TV_PRISTAVKI_PULT_DU',
			),
		),
	);
	
	foreach($arGroups as $arGroup)
	{
		$arFields = array(
			'NAME' => trim(htmlspecialcharsbx($arGroup['NAME'])),
			'CODE' => trim(htmlspecialcharsbx($arGroup['CODE'])),
			'SORT' => trim(htmlspecialcharsbx($arGroup['SORT'])),
		);

		$ID = CRSGGroups::Add($arFields);
		if (intval($ID) > 0)
		{
			foreach($arGroup['BINDS'] as $propCode)
			{
				$resProp = CIBlockProperty::GetList(
					array(
						'SORT' => 'ASC',
						'NAME' => 'ASC'
					),
					array(
						'ACTIVE' => 'Y',
						'IBLOCK_ID' => $arrIBlockIDs['catalog'],
						'CODE' => $propCode
					)
				);

				if ($arProperty = $resProp->GetNext())
				{
					//CRSGBinds::DeleteBindsForGroupID($ID);
					$BIND_ID = CRSGBinds::Add(
						array(
							'IBLOCK_PROPERTY_ID' => $arProperty['ID'],
							'GROUP_ID' => $ID,
						)
					);
				}
				unset($resProp, $arProperty);
			}
			unset($propCode);
		}
	}
	unset($arGroup);
}
