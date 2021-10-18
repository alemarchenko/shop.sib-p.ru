<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;

$request = Application::getInstance()->getContext()->getRequest();

if (Loader::includeModule('redsign.favorite'))
{
	CJSCore::Init('rs_favorite');
}

if ($request->isAjaxRequest() && $request->get('action') == 'add2wishlist')
{
    $APPLICATION->RestartBuffer();

    if (intval($request->get('element_id')) <= 0)
	{
        return false;
    }

    Loader::includeModule('iblock');

    $result = RSFavoriteAddDel($request->get('element_id'));

    $arParams['LIKES_COUNT_PROP'] = 'LIKES_COUNT'; //Option::get('redsign.activelife', 'propcode_likes', 'LIKES_COUNT');

    $res = CIBlockElement::GetList(
        array(),
        array(
            'ID' => $request->get('element_id')
        ),
        false,
        false,
        array(
            'ID',
            'PROPERTY_'.$arParams['LIKES_COUNT_PROP']
        )
    );

    if ($arElement = $res->GetNext())
	{
        $iElementCount = intval($arElement['PROPERTY_'.$arParams['LIKES_COUNT_PROP'].'_VALUE'] > 0 )
            ? $arElement['PROPERTY_'.$arParams['LIKES_COUNT_PROP'].'_VALUE']
            : 0 ;
    }

    switch ($result)
	{
        case 2:
            $arResult['COUNT']++;
            $iElementCount++;
            $JSON = array(
                'STATUS' => 'OK',
                'ACTION' => 'ADD',
                'TOTAL' => $arResult['COUNT'], //?????????????
                'LIKES_COUNT' => $iElementCount
            );
            if ($arElement) {

            }
            break;
        case 1:
            $arResult['COUNT'] = --$arResult['COUNT'] > 0
                ? $arResult['COUNT']
                : 0;
            $iElementCount = --$iElementCount > 0
                ? $iElementCount
                : 0;
            $JSON = array(
                'STATUS' => 'OK',
                'ACTION' => 'REMOVE',
                'TOTAL' => $arResult['COUNT'], //?????????????
                'LIKES_COUNT' => $iElementCount
            );
            break;
        default:
            $JSON = array(
                'STATUS' => 'ERROR'
            );
    }

    if ($arElement)
	{
        CIBlockElement::SetPropertyValueCode(
            $arElement['ID'],
            $arParams['LIKES_COUNT_PROP'],
            $iElementCount
        );
    }


    if ('UTF-8' != strtoupper(SITE_CHARSET))
	{
        echo $APPLICATION->ConvertCharset(
            json_encode(
                $APPLICATION->ConvertCharsetArray($JSON, strtoupper(SITE_CHARSET), 'UTF-8')
            )
        , 'UTF-8', strtoupper(SITE_CHARSET));
    }
	else
	{
        echo json_encode($JSON);
    }

    die();
}


global $rsFavoriteElements;
$rsFavoriteElements = array();
if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{
    foreach ($arResult['ITEMS'] as $arItem)
	{
        $rsFavoriteElements[] = $arItem['ELEMENT_ID'];
    }
}