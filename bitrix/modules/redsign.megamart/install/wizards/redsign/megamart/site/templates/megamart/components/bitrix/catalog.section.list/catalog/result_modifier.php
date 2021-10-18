<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader;
use \Redsign\MegaMart\ParametersUtils;
use \Redsign\MegaMart\LazyloadUtils;

$arViewModeList = array(/*'LIST',*/ 'LINE', /*'TEXT',*/'TILE', 'BANNER', 'THUMB');

// $arDefaultParams = array(
	// 'SECTIONS_VIEW_MODE' => 'LINE',
	// 'SHOW_PARENT_NAME' => 'Y',
	// 'HIDE_SECTION_NAME' => 'N'
// );

// $arParams = array_merge($arDefaultParams, $arParams);

if (Loader::includeModule('redsign.megamart'))
{
	$arParams['GRID_RESPONSIVE_SETTINGS'] = ParametersUtils::prepareGridSettings($arParams['~GRID_RESPONSIVE_SETTINGS']);
}


$arParams['PREVIEW_TRUNCATE_LEN'] = isset($arParams['PREVIEW_TRUNCATE_LEN']) ? (int)$arParams['PREVIEW_TRUNCATE_LEN'] : 0;
$arParams['LVL1_SECTION_COUNT'] = isset($arParams['LVL1_SECTION_COUNT']) ? (int)$arParams['LVL1_SECTION_COUNT'] : 0;

$arParams['SECTIONS_VIEW_MODE'] = in_array($arParams['SECTIONS_VIEW_MODE'], $arViewModeList)
	? $arParams['SECTIONS_VIEW_MODE']
	: 'TILE';

$arParams['SHOW_PARENT_NAME'] = isset($arParams['SHOW_PARENT_NAME']) && $arParams['SHOW_PARENT_NAME'] === 'N' ? 'N' : 'Y';
$arParams['HIDE_SECTION_NAME'] = isset($arParams['HIDE_SECTION_NAME']) && $arParams['HIDE_SECTION_NAME'] === 'N' ? 'N' : 'Y';

$arParams['PARENT_TITLE'] = isset($arParams['PARENT_TITLE']) ? trim($arParams['PARENT_TITLE']) : '';

$arResult['VIEW_MODE_LIST'] = $arViewModeList;

if (0 < $arResult['SECTIONS_COUNT'])
{
	if (!in_array($arParams['SECTIONS_VIEW_MODE'], array('TILE'))) {
		$boolClear = false;
		$arNewSections = array();
		foreach ($arResult['SECTIONS'] as &$arOneSection)
		{
			if (1 < $arOneSection['RELATIVE_DEPTH_LEVEL'])
			{
				$boolClear = true;
				continue;
			}
			$arNewSections[] = $arOneSection;
		}
		unset($arOneSection);
		if ($boolClear)
		{
			$arResult['SECTIONS'] = $arNewSections;
			$arResult['SECTIONS_COUNT'] = count($arNewSections);
		}
		unset($arNewSections);
	}
	else
	{
		$iSectionTopCount = 0;
		foreach ($arResult['SECTIONS'] as $key => $arOneSection)
		{
			if ($arOneSection['RELATIVE_DEPTH_LEVEL'] == 1)
			{
				if (isset($arSectionTop))
				{
					$arSectionTop['SECTIONS_COUNT'] = $iSectionTopCount;
				}

				$iSectionTopCount = 0;
				$arSectionTop = &$arResult['SECTIONS'][$key];
			}
			else
			{
				$iSectionTopCount++;
			}
		}
		unset($key, $arOneSection);
	}
}

if (0 < $arResult['SECTIONS_COUNT'])
{
	$boolPicture = false;
	$boolDescr = false;
	$boolBanner = false;
	$arSelect = array('ID');
	$arMap = array();
	if (in_array($arParams['SECTIONS_VIEW_MODE'], array('LINE', 'TILE', 'BANNER', 'THUMB')))
	{
		reset($arResult['SECTIONS']);
		$arCurrent = current($arResult['SECTIONS']);
		if (!isset($arCurrent['PICTURE']))
		{
			$boolPicture = true;
			$arSelect[] = 'PICTURE';
		}
		if ('LINE' == $arParams['SECTIONS_VIEW_MODE'] && !array_key_exists('DESCRIPTION', $arCurrent))
		{
			$boolDescr = true;
			$arSelect[] = 'DESCRIPTION';
			$arSelect[] = 'DESCRIPTION_TYPE';
		}

        // if (in_array($arParams['SECTIONS_VIEW_MODE'], array('BANNER', 'THUMB')) && isset($arParams['BANNER_TYPE'][$arParams['IBLOCK_ID']])) {
            // $boolBanner = true;
            // $arSelect[] = 'IBLOCK_ID';
            // $arSelect[] = $arParams['BANNER_TYPE'][$arParams['IBLOCK_ID']];
        // }
	}

	// if ($boolBanner) {
        // $arUserFieldValues = array();
        // $dbUserField = CUserFieldEnum::GetList(array(), array('USER_FIELD_NAME' => $arParams['BANNER_TYPE']));
        // while ($arUserField = $dbUserField->Fetch())
        // {
            // $arUserFieldValues[$arUserField['ID']] = $arUserField;
        // }
    // }

	if ($boolPicture || $boolDescr || $boolBanner)
	{
		foreach ($arResult['SECTIONS'] as $key => $arSection)
		{
			$arMap[$arSection['ID']] = $key;
		}
        unset($key, $arSection);

		$rsSections = CIBlockSection::GetList(array(), array('ID' => array_keys($arMap), 'IBLOCK_ID' => $arParams['IBLOCK_ID']), false, $arSelect);
		while ($arSection = $rsSections->GetNext())
		{
			if (!isset($arMap[$arSection['ID']]))
				continue;

			$key = $arMap[$arSection['ID']];
			if ($boolPicture)
			{
				$arSection['PICTURE'] = intval($arSection['PICTURE']);
				$arSection['PICTURE'] = (0 < $arSection['PICTURE'] ? CFile::GetFileArray($arSection['PICTURE']) : false);
				$arResult['SECTIONS'][$key]['PICTURE'] = $arSection['PICTURE'];
				$arResult['SECTIONS'][$key]['~PICTURE'] = $arSection['~PICTURE'];
			}
			if ($boolDescr)
			{
                $arResult['SECTIONS'][$key]['DESCRIPTION'] = $arSection['DESCRIPTION'];
				$arResult['SECTIONS'][$key]['DESCRIPTION_TYPE'] = $arSection['DESCRIPTION_TYPE'];
			}

			if ($boolBanner) {
                if ($arSection[$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]]) {
                    $arResult['SECTIONS'][$key][$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]] = $arUserFieldValues[$arSection[$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]]];
                }
            }

		}
        unset($arSection);
	}
}

if($arParams['PREVIEW_TRUNCATE_LEN'] > 0)
{
    $obParser = new CTextParser;
    foreach ($arResult['SECTIONS'] as $key => $arSection)
	{
        $arResult['SECTIONS'][$key]['DESCRIPTION'] = $obParser->html_cut($arSection['DESCRIPTION'], $arParams['PREVIEW_TRUNCATE_LEN']);
    }
    unset($key, $arSection);
}

if (
    ($arParams['SHOW_PARENT_NAME'] == 'Y' || $arParams['SHOW_PARENT_DESCR'] == 'Y')
    && $arResult['SECTION']['ID'] == 0
)
{

    $arOrder = array();
    $arFilter = array(
        'TYPE' => $arParams['IBLOCK_TYPE'],
        'ID' => $arParams['IBLOCK_ID'],
    );
    $bIncCnt = false;

    $dbIblock = CIBlock::getList($arOrder, $arFilter, $bIncCnt);

    if ($arIblock = $dbIblock->getNext())
	{
        $arResult['SECTION']['NAME'] = $arIblock['NAME'];
        $arResult['SECTION']['DESCRIPTION'] = $arIblock['DESCRIPTION'];
    }
    unset($arOrder, $arFilter, $bIncCnt);
}

if (empty($arParams['RS_LAZY_IMAGES_USE']) || $arParams['RS_LAZY_IMAGES_USE'] == 'FROM_MODULE')
{
	$arParams['RS_LAZY_IMAGES_USE'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_lazyload_images');
}
