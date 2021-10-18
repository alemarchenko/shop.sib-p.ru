<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

$arFieldsParams = CUtil::JsObjectToPhp($arParams['~FIELD_PARAMS']);

foreach ($arResult['FIELDS'] as &$arField) {
	if ($arField['PROPERTY_TYPE'] != 'S') {
		continue;
	}

	$arField['INPUT_TYPE'] = 'text';

	if (isset($arFieldsParams[$arField['ID']])) {
		$arFieldParam = $arFieldsParams[$arField['ID']];

		if (!empty($arFieldParam['mask'])) {
			$arField['MASK'] = $arFieldParam['mask'];
		}

		if ($arFieldParam['validate'] == 'email') {
			$arField['INPUT_TYPE'] = 'email';
		} elseif ($arFieldParam['validate'] == 'url') {
			$arField['INPUT_TYPE'] = 'url';
		} elseif ($arFieldParam['validate'] == 'pattern' && !empty($arFieldParam['validatePattern'])) {
			$arField['PATTERN'] = $arFieldParam['validatePattern'];
		}
	}

}
unset($arField);


if (Loader::includeModule('iblock')) {
	$request = \Bitrix\Main\Context::getCurrent()->getRequest();
	$itemId = $request->getQuery('element_id');

	$res = CIBlockElement::GetList(
		array(),
		array(
			'ID' => $itemId,
			'IBLOCK_ID' => $arParams['ITEMS_IBLOCK_ID'],
		),
		array(
			'ID',
			'NAME'
		)
	);

	$arResult['ELEMENT'] = array();
	while ($arElement = $res->GetNext())
	{
		if (empty($arResult['ELEMENT']['ID'])) {
			$arResult['ELEMENT']['ID'] = $arElement['ID'];
		}
		if (empty($arResult['ELEMENT']['NAME'])) {
			$arResult['ELEMENT']['NAME'] = $arElement['NAME'];
		}
	}

	foreach ($arResult['FIELDS'] as &$arField) {
		if ($arField['CODE'] == $arParams['NAME_PROPERTY_CODE']) {
			$arField['CURRENT_VALUE'] = '['.$arResult['ELEMENT']['ID'].'] '.$arResult['ELEMENT']['NAME'];
		}
	}
	unset($arField);
}
