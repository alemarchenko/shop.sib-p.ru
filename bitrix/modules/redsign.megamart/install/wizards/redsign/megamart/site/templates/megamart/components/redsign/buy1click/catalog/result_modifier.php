<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

$arFieldsParams = CUtil::JsObjectToPhp($arParams['~FIELD_PARAMS']);

if (Loader::includeModule('iblock'))
{
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    $itemId = $request->getQuery('element_id');

    $res = CIBlockElement::GetList(
        array(),
        array(
            'ID' => $itemId,
            // 'IBLOCK_ID' => $arParams['ITEMS_IBLOCK_ID'],
        ),
        array(
            'ID',
            'NAME'
        )
    );

    $arResult['ELEMENT'] = array();
    while ($arElement = $res->GetNext())
	{
        if (empty($arResult['ELEMENT']['ID']))
		{
            $arResult['ELEMENT']['ID'] = $arElement['ID'];
        }
        
		if (empty($arResult['ELEMENT']['NAME']))
		{
            $arResult['ELEMENT']['NAME'] = $arElement['NAME'];
        }
    }
}
