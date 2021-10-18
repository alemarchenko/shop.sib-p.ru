<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

use Bitrix\Main\Loader;
use Bitrix\Main\Type\Collection;
use Bitrix\Highloadblock as HL;

global $USER_FIELD_MANAGER;

if (!Loader::includeModule("highloadblock"))
	return;

if (!WIZARD_INSTALL_DEMO_DATA)
	return;

$COLOR_ID = (int)$_SESSION["ESHOP_HBLOCK_COLOR_ID"];
unset($_SESSION["ESHOP_HBLOCK_COLOR_ID"]);

$BRAND_ID = (int)$_SESSION["ESHOP_HBLOCK_BRAND_ID"];
unset($_SESSION["ESHOP_HBLOCK_BRAND_ID"]);

$BRAND_BRANDS_ID = (int)$_SESSION["ESHOP_HBLOCK_BRAND_BRANDS_ID"];
unset($_SESSION["ESHOP_HBLOCK_BRAND_BRANDS_ID"]);

$FEATURE_FILTER_ID = (int)$_SESSION["ESHOP_HBLOCK_FEATURE_FILTER_ID"];
unset($_SESSION["ESHOP_HBLOCK_FEATURE_FILTER_ID"]);

//adding rows
WizardServices::IncludeServiceLang("references.php", LANGUAGE_ID);

if ($COLOR_ID > 0)
{
	$hldata = HL\HighloadBlockTable::getById($COLOR_ID)->fetch();
	if (is_array($hldata))
	{
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();

		$colors = [];
		$colors['BLACK'] = [
			'XML_ID' => 'black',
			'PATH' => 'references_files/000000.png',
			'FILE_NAME' => '000000.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['WHITE'] = [
			'XML_ID' => 'black',
			'PATH' => 'references_files/FFFFFF.png',
			'FILE_NAME' => 'FFFFFF.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['GOLD'] = [
			'XML_ID' => 'gold',
			'PATH' => 'references_files/FFD700.png',
			'FILE_NAME' => 'FFD700.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['GREEN'] = [
			'XML_ID' => 'green',
			'PATH' => 'references_files/008000.png',
			'FILE_NAME' => '008000.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['RED'] = [
			'XML_ID' => 'red',
			'PATH' => 'references_files/FF0000.png',
			'FILE_NAME' => 'FF0000.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['RED'] = [
			'XML_ID' => 'red',
			'PATH' => 'references_files/FF0000.png',
			'FILE_NAME' => 'FF0000.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['FREE_SPEECH_BLUE'] = [
			'XML_ID' => 'free_speech_blue',
			'PATH' => 'references_files/3F47CC.png',
			'FILE_NAME' => '3F47CC.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['CARNATION_PINK'] = [
			'XML_ID' => 'carnation_pink',
			'PATH' => 'references_files/FEAEC9.png',
			'FILE_NAME' => 'FEAEC9.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['PURPLE_HEART'] = [
			'XML_ID' => 'purple_heart',
			'PATH' => 'references_files/4B3ABE.png',
			'FILE_NAME' => '4B3ABE.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['BURGUNDY'] = [
			'XML_ID' => 'burgundy',
			'PATH' => 'references_files/790013.png',
			'FILE_NAME' => '790013.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['GRAY'] = [
			'XML_ID' => 'gray',
			'PATH' => 'references_files/7F7F7F.png',
			'FILE_NAME' => '7F7F7F.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['ELECTRIC_LIME'] = [
			'XML_ID' => 'electric_lime',
			'PATH' => 'references_files/C6FC06.png',
			'FILE_NAME' => 'C6FC06.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['PUMPKIN'] = [
			'XML_ID' => 'pumpkin',
			'PATH' => 'references_files/FF7F26.png',
			'FILE_NAME' => 'FF7F26.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['IVORY'] = [
			'XML_ID' => 'ivory',
			'PATH' => 'references_files/FEFFF1.png',
			'FILE_NAME' => 'FEFFF1.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['DEEP_SKY_BLUE'] = [
			'XML_ID' => 'deep_sky_blue',
			'PATH' => 'references_files/00A3E8.png',
			'FILE_NAME' => '00A3E8.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['YELLOW'] = [
			'XML_ID' => 'yellow',
			'PATH' => 'references_files/FEF200.png',
			'FILE_NAME' => 'FEF200.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['SANTE_FE'] = [
			'XML_ID' => 'santa_fe',
			'PATH' => 'references_files/B97A57.png',
			'FILE_NAME' => 'B97A57.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['SILVER'] = [
			'XML_ID' => 'silver',
			'PATH' => 'references_files/C0C0C0.png',
			'FILE_NAME' => 'C0C0C0.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['TANGERINE_YELLOW'] = [
			'XML_ID' => 'tangerine_yellow',
			'PATH' => 'references_files/FFC704.png',
			'FILE_NAME' => 'FFC704.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['LIME'] = [
			'XML_ID' => 'lime',
			'PATH' => 'references_files/00FF01.png',
			'FILE_NAME' => '00FF01.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['ALIZARIN'] = [
			'XML_ID' => 'alizarin',
			'PATH' => 'references_files/ED1B24.png',
			'FILE_NAME' => 'ED1B24.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['DIM_GRAY'] = [
			'XML_ID' => 'dim_gray',
			'PATH' => 'references_files/666666.png',
			'FILE_NAME' => '666666.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['FRENCH_LILAC'] = [
			'XML_ID' => 'french_lilac',
			'PATH' => 'references_files/DFB6D8.png',
			'FILE_NAME' => 'DFB6D8.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['BLACK_WHITE'] = [
			'XML_ID' => 'black_white',
			'PATH' => 'references_files/E6E4D8.png',
			'FILE_NAME' => 'E6E4D8.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['BEIGE'] = [
			'XML_ID' => 'beige',
			'PATH' => 'references_files/F5F5DC.png',
			'FILE_NAME' => 'F5F5DC.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['PRUSSIAN_BLUE'] = [
			'XML_ID' => 'prussian_blue',
			'PATH' => 'references_files/003366.png',
			'FILE_NAME' => '003366.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['COSMOS'] = [
			'XML_ID' => 'cosmos',
			'PATH' => 'references_files/FFCCCB.png',
			'FILE_NAME' => 'FFCCCB.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['PEACH'] = [
			'XML_ID' => 'peach',
			'PATH' => 'references_files/FFCBA4.png',
			'FILE_NAME' => 'FFCBA4.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['HAITI'] = [
			'XML_ID' => 'haiti',
			'PATH' => 'references_files/364042.png',
			'FILE_NAME' => '364042.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['INCH_WORM'] = [
			'XML_ID' => 'inch_worm',
			'PATH' => 'references_files/B6DB19.png',
			'FILE_NAME' => 'B6DB19.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['HAITI_BLACK'] = [
			'XML_ID' => 'heiti_black',
			'PATH' => 'references_files/364042_000000.png',
			'FILE_NAME' => '364042_000000.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['LIME_GREEN_BLACK'] = [
			'XML_ID' => 'lime_green_black',
			'PATH' => 'references_files/B6DB19_000000.png',
			'FILE_NAME' => 'B6DB19_000000.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['BLACK_COLD_GRAY'] = [
			'XML_ID' => 'black_cold_gray',
			'PATH' => 'references_files/666666_000000.png',
			'FILE_NAME' => '666666_000000.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['SILVER_WHITE'] = [
			'XML_ID' => 'silver_white',
			'PATH' => 'references_files/C0C0C0_FFFFFF.png',
			'FILE_NAME' => 'C0C0C0_FFFFFF.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];
		$colors['SILVER_GREEN_LIME'] = [
			'XML_ID' => 'silver_green_lime',
			'PATH' => 'references_files/C0C0C0_364042.png',
			'FILE_NAME' => 'C0C0C0_364042.png',
			'FILE_TYPE' => 'image/png',
			'TITLE' => ''
		];

		foreach (array_keys($colors) as $index)
		{
			$colors[$index]['TITLE'] = GetMessage('WZD_REF_COLOR_'.$index);
		}

		Collection::sortByColumn($colors, ['TITLE' => SORT_ASC]);

		$picturePath = WIZARD_ABSOLUTE_PATH.'/site/services/iblock/';
		$sort = 0;
		foreach($colors as $row)
		{
			$sort+= 100;
			$data = [
				'UF_NAME' => $row['TITLE'],
				'UF_FILE' => [
					'name' => $row['FILE_NAME'],
					'type' => $row['FILE_TYPE'],
					'tmp_name' => $picturePath.$row['PATH']
				],
				'UF_SORT' => $sort,
				'UF_DEF' => '0',
				'UF_XML_ID' => $row['XML_ID']
			];
			$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$COLOR_ID, $data);
			$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$COLOR_ID, null, $data);

			$result = $entity_data_class::add($data);
		}
	}
}

if ($BRAND_ID > 0)
{
	$hldata = HL\HighloadBlockTable::getById($BRAND_ID)->fetch();
	if (is_array($hldata))
	{
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();
		$arBrands = array(
			"HEIGHT-20CM" => "brands_files/height-20cm.png",
			"WEIGHT-140KG" => "brands_files/weight-140kg.png",
			"RIGIDITY-MIDDLE" => "brands_files/rigidity-middle.png",
			"WARRANTY-5YEARS" => "brands_files/warranty-5years.png",
			"WARRANTY" => "brands_files/warranty.png",
			"PRICE" => "brands_files/price.png",
			"CONVENIENCE" => "brands_files/convenience.png",
			"DELIVERY" => "brands_files/delivery.png",
			"QUALITY" => "brands_files/quality.png",
		);
		$sort = 0;
		foreach($arBrands as $brandName=>$brandFile)
		{
			$sort+= 100;
			$arData = array(
				'UF_NAME' => GetMessage("WZD_REF_BRAND_".$brandName),
				'UF_FILE' =>
					array (
						'name' => ToLower($brandName).".png",
						'type' => 'image/png',
						'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$brandFile
					),
				'UF_SORT' => $sort,
				'UF_DESCRIPTION' => GetMessage("WZD_REF_BRAND_DESCR_".$brandName),
				'UF_FULL_DESCRIPTION' => GetMessage("WZD_REF_BRAND_FULL_DESCR_".$brandName),
				'UF_XML_ID' => ToLower($brandName)
			);
			$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$BRAND_ID, $arData);
			$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$BRAND_ID, null, $arData);

			$result = $entity_data_class::add($arData);
		}
	}
}

if (intval($BRAND_BRANDS_ID) > 0)
{
	$hldata = HL\HighloadBlockTable::getById($BRAND_BRANDS_ID)->fetch();
	if (is_array($hldata))
	{
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();
		$arBrands = array(
			"APPLE" => "",
			"GOOGLE" => "",
			"LG" => "",
			"SAMSUNG" => "",
			"CANON" => "",
			"HP" => "",
			"EPSON" => "",
			"BROTHER" => "",
			"HUAMI" => "",
			"MYSTERY" => "",
			"STARWIND" => "",
			"TEFAL" => "",
			"BOSCH" => "",
			"INDESIT" => "",
			"HOTPOINT_ARISTON" => "",
			"XIAOMI" => "",
			"CASO" => "",
			"SONY" => "",
			"NOKIA" => "",
			"SPIGEN" => "",
			"LENOVO" => "",
			"ACER" => "",
			"GAME_PC" => "",
			"DELL" => "",
			"ASUS" => "",
			"HUAWEI" => "",
			"LEAGOO" => "",
			"BENQ" => "",
			"AOC" => "",
			"PANTUM" => "",
			"MICHAEL_KORS" => "",
			"TRAVELPRO" => "",
			"TAG" => "",
			"SKIL" => "",
			"DEWALT" => "",
			"RYOBI" => "",
			"BLACK_DECKER" => "",
			"ESTIMA" => "",
			"DULUX" => "",
			"TIKKURILA" => "",
			"NIKON" => "",
			"GARMIN" => "",
			"REDMOND" => "",
			"LEXAND" => "",
			"PROLOGY" => "",
			"MAKITA" => "",
			"DIESEL" => "",
			"GLOBUSGPS" => "",
			"INC_INTERNATIONAL_CONCEPTS" => "",
			"ANNE_KLEIN" => "",
			"ATLANTIC" => "",
			"QUICK_STEP" => "",
			"STROYBAT" => "",
			"MOTOROLA" => "",
			"PANASONIC" => "",
			"MEGAJET" => "",
			"MICROSOFT" => "",
			"REPLICA" => "",
			"BBS" => "",
			"KK" => "",
			"OZ" => "",
			"NOKIAN" => "",
			"BRIDGESTONE" => "",
			"YOKOHAMA" => "",
			"BDGOODRICH" => "",
			"PIONEER" => "",
			"ALPINE" => "",
			"JVC" => "",
		);
		$sort = 0;
		foreach($arBrands as $brandName=>$brandFile)
		{
			$sort+= 100;
			$arData = array(
				'UF_NAME' => GetMessage("WZD_REF_BRAND_BRANDS_".$brandName),
				'UF_FILE' =>
					array (
						/*
						'name' => ToLower($brandName).".png",
						'type' => 'image/png',
						'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$brandFile
						*/
					),
				'UF_SORT' => $sort,
				//'UF_DESCRIPTION' => GetMessage("WZD_REF_BRAND__BRANDS_DESCR_".$brandName),
				//'UF_FULL_DESCRIPTION' => GetMessage("WZD_REF_BRAND_BRANDS__FULL_DESCR_".$brandName),
				'UF_XML_ID' => ToLower($brandName)
			);
			$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$BRAND_BRANDS_ID, $arData);
			$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$BRAND_BRANDS_ID, null, $arData);

			$result = $entity_data_class::add($arData);
		}
	}
}

if (intval($FEATURE_FILTER_ID) > 0)
{
	$hldata = HL\HighloadBlockTable::getById($FEATURE_FILTER_ID)->fetch();
	if (is_array($hldata))
	{
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();
		$arFeatureFilters = array(
			'SMARTFONY-128GB' => 'telefony/filter/memory_card-is-128gb-or-256gb/apply/',
			'SMARTFONY-DO-10-TYS-RUB' => 'filter/price-base-to-10000/phone_type-is-7d760dcce0622cfc6d7a64b66a97d041/apply/',
			'UMNYE-CHASY-APPLE' => 'umnye-chasy/filter/brand_ref-is-apple/apply/',
		);
		$sort = 0;
		foreach($arFeatureFilters as $featureFilterName=>$featureFilterLink)
		{
			$sort+= 100;
			$arData = array(
				'UF_NAME' => GetMessage("WZD_REF_FEATURE_FILTER_".$featureFilterName),
				'UF_FILE' =>
					array (
						/*
						'name' => ToLower($featureFilterName).".png",
						'type' => 'image/png',
						'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$featureFilterFile
						*/
					),
				'UF_LINK' => $featureFilterLink,
				//'UF_DESCRIPTION' => GetMessage("WZD_REF_FEATURE_FILTER_DESCR_".$featureFilterName),
				//'UF_FULL_DESCRIPTION' => GetMessage("WZD_REF_FEATURE_FILTER_FULL_DESCR_".$featureFilterName),
				'UF_XML_ID' => ToLower($featureFilterName)
			);
			$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$FEATURE_FILTER_ID, $arData);
			$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$FEATURE_FILTER_ID, null, $arData);

			$result = $entity_data_class::add($arData);
		}
	}
}
?>