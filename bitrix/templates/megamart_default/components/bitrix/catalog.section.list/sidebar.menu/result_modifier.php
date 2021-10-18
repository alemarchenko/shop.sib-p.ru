<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (is_array($arResult['SECTIONS']) && count($arResult['SECTIONS']))
{
	foreach ($arResult['SECTIONS'] as $iSectionKey => $arSection)
	{
		$arResult['SECTIONS'][$iSectionKey]['IS_PARENT'] = false;
		$arResult['SECTIONS'][$iSectionKey]['HAVE_SUBSECTIONS'] = false;
		if (
			intval($arSection['RIGHT_MARGIN']) - IntVal($arSection['LEFT_MARGIN']) > 1
			&& $arSection['RELATIVE_DEPTH_LEVEL'] < $arParams['TOP_DEPTH']
		)
		{
			$arResult['SECTIONS'][$iSectionKey]['HAVE_SUBSECTIONS'] = true;
			if ($arResult['SECTIONS'][$iSectionKey]['DEPTH_LEVEL'] == 1)
			{
				$arResult['SECTIONS'][$iSectionKey]['IS_PARENT'] = true;
			}
		}
	}
}

if (is_array($arParams['FILTER_IDS']) && count($arParams['FILTER_IDS']) > 0)
{
	$prevLevel = -1;
	for ($i = $arResult['SECTIONS_COUNT'] - 1; $i >= 0; --$i)
	{
		if (in_array($arResult['SECTIONS'][$i]['ID'], $arParams['FILTER_IDS']))
		{
			$prevLevel = $arResult['SECTIONS'][$i]['DEPTH_LEVEL'];
		}
		else
		{
			if ($prevLevel != -1 && $prevLevel > $arResult['SECTIONS'][$i]['DEPTH_LEVEL'])
			{
				$prevLevel = $arResult['SECTIONS'][$i]['DEPTH_LEVEL'];
			}
			elseif ($prevLevel == $arResult['SECTIONS'][$i]['DEPTH_LEVEL'])
			{
				$prevLevel = $arResult['SECTIONS'][$i]['DEPTH_LEVEL'];
				unset($arResult['SECTIONS'][$i]);
			}
			else
			{
				unset($arResult['SECTIONS'][$i]);
				if ($arResult['SECTIONS'][$i]['DEPTH_LEVEL'] == $arResult['SECTION']['DEPTH_LEVEL'] + 1)
				{
					$prevLevel = -1;
				}
			}

		}
	}
	
	$arResult['SECTIONS'] = array_values($arResult['SECTIONS']);
}


