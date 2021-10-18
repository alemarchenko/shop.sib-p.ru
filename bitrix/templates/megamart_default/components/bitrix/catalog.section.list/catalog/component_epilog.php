<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

$arParams['SET_TITLE'] = $arParams['SET_TITLE']!='N';
$arParams["SET_BROWSER_TITLE"] = (isset($arParams["SET_BROWSER_TITLE"]) && $arParams["SET_BROWSER_TITLE"] === 'Y' ? 'Y' : 'N');
$arParams["SET_META_KEYWORDS"] = (isset($arParams["SET_META_KEYWORDS"]) && $arParams["SET_META_KEYWORDS"] === 'Y' ? 'Y' : 'N');
$arParams["SET_META_DESCRIPTION"] = (isset($arParams["SET_META_DESCRIPTION"]) && $arParams["SET_META_DESCRIPTION"] === 'Y' ? 'Y' : 'N');

if ($arParams['SET_TITLE'])
{
	if ($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '')
	{
		$APPLICATION->SetTitle($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']);
	}
	elseif(isset($arResult['SECTION']['NAME']))
	{
		$APPLICATION->SetTitle($arResult['SECTION']['NAME']);
	}
}

if ($arParams["SET_BROWSER_TITLE"] === 'Y')
{
	$browserTitle = \Bitrix\Main\Type\Collection::firstNotEmpty(
		$arResult,
		$arParams["BROWSER_TITLE"],
		$arResult['SECTION']['IPROPERTY_VALUES'],
		"SECTION_META_TITLE"
	);

	if (is_array($browserTitle))
	{
		$APPLICATION->SetPageProperty("title", implode(" ", $browserTitle));
	}
	elseif ($browserTitle != "")
	{
		$APPLICATION->SetPageProperty("title", $browserTitle);
	}
}


if ($arParams["SET_META_KEYWORDS"] === 'Y')
{
	$metaKeywords = \Bitrix\Main\Type\Collection::firstNotEmpty(
		$arResult,
		$arParams["META_KEYWORDS"],
		$arResult['SECTION']['IPROPERTY_VALUES'],
		"SECTION_META_KEYWORDS"
	);

	if (is_array($metaKeywords))
	{
		$APPLICATION->SetPageProperty("keywords", implode(" ", $metaKeywords));
	}
	elseif ($metaKeywords != "")
	{
		$APPLICATION->SetPageProperty("keywords", $metaKeywords);
	}
}

if ($arParams["SET_META_DESCRIPTION"] === 'Y')
{
	$metaDescription = \Bitrix\Main\Type\Collection::firstNotEmpty(
		$arResult,
		$arParams["META_DESCRIPTION"],
		$arResult['SECTION']['IPROPERTY_VALUES'],
		"SECTION_META_DESCRIPTION"
	);

	if (is_array($metaDescription))
	{
		$APPLICATION->SetPageProperty("description", implode(" ", $metaDescription));
	}
	elseif ($metaDescription != "")
	{
		$APPLICATION->SetPageProperty("description", $metaDescription);
	}
}
