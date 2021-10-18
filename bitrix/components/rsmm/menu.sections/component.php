<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["ID"] = intval($arParams["ID"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

$arParams["DEPTH_LEVEL"] = intval($arParams["DEPTH_LEVEL"]);
if($arParams["DEPTH_LEVEL"]<=0)
	$arParams["DEPTH_LEVEL"]=1;

$arResult["SECTIONS"] = array();
$arResult["ELEMENT_LINKS"] = array();
$arResult['MENU_TYPES'] = array();

if($this->StartResultCache())
{
	if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
	}
	else
	{
		$arMenuTypeIds = array();

		$arFilter = array(
			"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
			"GLOBAL_ACTIVE"=>"Y",
			"IBLOCK_ACTIVE"=>"Y",
			"<="."DEPTH_LEVEL" => $arParams["DEPTH_LEVEL"],
		);
		$arOrder = array(
			"left_margin"=>"asc",
		);

		$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, array(
			"ID",
			"DEPTH_LEVEL",
			"NAME",
			"SECTION_PAGE_URL",
			'PICTURE',
			'UF_MENU_TYPE',
			'UF_SECTION_COLOR',
			'UF_BANNER_IMAGE',
			'UF_BANNER_LINK',
			'UF_SECTION_ICON',
			'UF_SECTOPN_ICON',

		));
		if($arParams["IS_SEF"] !== "Y")
			$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		else
			$rsSections->SetUrlTemplates("", $arParams["SEF_BASE_URL"].$arParams["SECTION_PAGE_URL"]);
		while($arSection = $rsSections->GetNext())
		{
			$nImageId = $arSection['UF_BANNER_IMAGE'];
			if (!empty($arSection['UF_SECTION_ICON']))
			{
				$nIconId = $arSection['UF_SECTION_ICON'];
			}
			elseif (!empty($arSection['UF_SECTOPN_ICON']))
			{
				$nIconId = $arSection['UF_SECTOPN_ICON'];
			}

			$sBannerImageSrc = null;
			$sIconSrc = null;

			if ($nImageId)
			{
				$sBannerImageSrc = CFile::GetPath($nImageId);
			}

			if ($nIconId)
			{
				$sIconSrc = CFile::GetPath($nIconId);
			}

			$arResult['SECTIONS'][$arSection['ID']] = array(
				"ID" => $arSection["ID"],
				"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
				"~NAME" => $arSection["~NAME"],
				"~SECTION_PAGE_URL" => $arSection["~SECTION_PAGE_URL"],
				'PICTURE' => $arSection['PICTURE'],
				'MENU_TYPE' => !empty($arSection['UF_MENU_TYPE']) ? $arSection['UF_MENU_TYPE'] : '-',
				'SECTION_COLOR'  => $arSection['UF_SECTION_COLOR'],
				'BANNER_IMAGE_SRC' => $sBannerImageSrc,
				'BANNER_LINK' => $arSection['UF_BANNER_LINK'],
				'ICON' => $sIconSrc
			);

			if (isset($arSection['UF_MENU_TYPE']) && !in_array($arSection['UF_MENU_TYPE'], $arMenuTypeIds))
			{
				$arMenuTypeIds[] = $arSection['UF_MENU_TYPE'];
			}

			$arResult["ELEMENT_LINKS"][$arSection["ID"]] = array();
		}

		$rsMenuType = CUserFieldEnum::GetList(array(), array("ID" => $arMenuTypeIds));
		while ($arMenuType = $rsMenuType->GetNext())
		{
			$arResult['MENU_TYPES'][$arMenuType['ID']] = $arMenuType;
		}

		$this->EndResultCache();
	}
}

//In "SEF" mode we'll try to parse URL and get ELEMENT_ID from it
if($arParams["IS_SEF"] === "Y")
{
	$engine = new CComponentEngine($this);
	if (CModule::IncludeModule('iblock'))
	{
		$engine->addGreedyPart("#SECTION_CODE_PATH#");
		$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
	}
	$componentPage = $engine->guessComponentPath(
		$arParams["SEF_BASE_URL"],
		array(
			"section" => $arParams["SECTION_PAGE_URL"],
			"detail" => $arParams["DETAIL_PAGE_URL"],
		),
		$arVariables
	);
	if($componentPage === "detail")
	{
		CComponentEngine::InitComponentVariables(
			$componentPage,
			array("SECTION_ID", "ELEMENT_ID"),
			array(
				"section" => array("SECTION_ID" => "SECTION_ID"),
				"detail" => array("SECTION_ID" => "SECTION_ID", "ELEMENT_ID" => "ELEMENT_ID"),
			),
			$arVariables
		);
		$arParams["ID"] = intval($arVariables["ELEMENT_ID"]);
	}
}

if(($arParams["ID"] > 0) && (intval($arVariables["SECTION_ID"]) <= 0) && CModule::IncludeModule("iblock"))
{
	$arSelect = array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID");
	$arFilter = array(
		"ID" => $arParams["ID"],
		"ACTIVE" => "Y",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	);
	$rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	if(($arParams["IS_SEF"] === "Y") && ($arParams["DETAIL_PAGE_URL"] <> ''))
		$rsElements->SetUrlTemplates($arParams["SEF_BASE_URL"].$arParams["DETAIL_PAGE_URL"]);
	while($arElement = $rsElements->GetNext())
	{
		$arResult["ELEMENT_LINKS"][$arElement["IBLOCK_SECTION_ID"]][] = $arElement["~DETAIL_PAGE_URL"];
	}
}

$aMenuLinksNew = array();
$menuIndex = 0;
$previousDepthLevel = 1;
foreach($arResult["SECTIONS"] as $arSection)
{
	if ($menuIndex > 0)
		$aMenuLinksNew[$menuIndex - 1][3]["IS_PARENT"] = $arSection["DEPTH_LEVEL"] > $previousDepthLevel;
	$previousDepthLevel = $arSection["DEPTH_LEVEL"];

	$arResult["ELEMENT_LINKS"][$arSection["ID"]][] = urldecode($arSection["~SECTION_PAGE_URL"]);
	$aMenuLinksNew[$menuIndex++] = array(
		htmlspecialcharsbx($arSection["~NAME"]),
		$arSection["~SECTION_PAGE_URL"],
		$arResult["ELEMENT_LINKS"][$arSection["ID"]],
		array(
			"FROM_IBLOCK" => true,
			"IS_PARENT" => false,
			"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
			'SECTION_ID' => $arSection['ID'],
			'OPEN_TYPE' => isset($arResult['MENU_TYPES'][$arSection['MENU_TYPE']]) ? $arResult['MENU_TYPES'][$arSection['MENU_TYPE']]['XML_ID'] : 'dropdown',
			'SECTION_COLOR' => $arSection['SECTION_COLOR'],
			'BANNER_IMAGE_SRC' => $arSection['BANNER_IMAGE_SRC'],
			'BANNER_LINK' => $arSection['BANNER_LINK'],
			'ICON' => $arSection['ICON']
		),
	);
}

return $aMenuLinksNew;
?>
