<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Loader;

$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "right";


if (Loader::includeModule('redsign.devfunc'))
{
	\Redsign\DevFunc\Sale\Location\Region::editSmartFilterResult($arResult);
}


/*************************************/
/************ HORIZONTAL *************/
/*************************************/


if ($arParams['FILTER_VIEW_MODE'] != 'HORIZONTAL')
    return;

/*
$arParams["SEARCH_PROPS"] = array();
$arParams["OFFER_SEARCH_PROPS"] = array();
$arParams["SCROLL_PROPS"] = array();
$arParams["OFFER_SCROLL_PROPS"] = array();
*/

foreach ($arResult['ITEMS'] as $key1 => $arItem)
{
    $count = 0;

    $arResult['ITEMS'][$key1]['USING'] = 'N';

    if (isset($arItem['PRICE']) || $arItem["DISPLAY_TYPE"] == 'A')
    {
        if (!empty($arItem["VALUES"]["MIN"]["HTML_VALUE"]) || !empty($arItem["VALUES"]["MAX"]["HTML_VALUE"]))
            $arResult['ITEMS'][$key1]['USING'] = 'Y';
    }
    else
    {
        if (!empty($arItem['VALUES']))
        {
            foreach ($arItem['VALUES'] as $val => $ar)
            {
                if (!$ar['CHECKED'])
                    continue;
                $count++;
            }
            $arResult['ITEMS'][$key1]['VALUES_COUNT'] = $count;
            if ($count > 0)
                $arResult['ITEMS'][$key1]['USING'] = 'Y';
        }

        switch ($arItem['DISPLAY_TYPE'])
        {
            case 'P'://DROPDOWN
                $arResult['ITEMS'][$key1]['DISPLAY_TYPE'] = 'K';
                break;
            case 'R'://DROPDOWN_WITH_PICTURES_AND_LABELS
                $arResult['ITEMS'][$key1]['DISPLAY_TYPE'] = 'H';
                break;
        }
    }
}
