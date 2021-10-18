<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$arParams['PROP_SITE_DOMAIN'] = isset($arParams['PROP_SITE_DOMAIN']) ? $arParams['PROP_SITE_DOMAIN'] : 'SITE_DOMAIN';

$arResult['DISPLAY_SKIP_PROPERTIES'] = array(
	$arParams['PROP_SITE_DOMAIN']
);

$arResult['DISPLAY_FILE_PROPS'] = [];
$arResult['DISPLAY_BIND_PROPS'] = [];
foreach ($arResult['DISPLAY_PROPERTIES'] as $nKey => $arProperty) {
	$sPropCode = $arProperty['CODE'];

	if (
		$arProperty['PROPERTY_TYPE'] ==  \Bitrix\Iblock\PropertyTable::TYPE_FILE &&
		count($arProperty['VALUE']) > 0
	) {

		if (
			!isset($arParams['RS_FILE_PROP_'.$sPropCode.'_VIEW']) ||
			!in_array($arParams['RS_FILE_PROP_'.$sPropCode.'_VIEW'], ['photogallery', 'line'])
		) {
			continue;
		}


		$nFiles = count($arProperty['VALUE']);
		if ($nFiles == 0) {
			continue;
		} else if ($nFiles == 1) {
			$arProperty['FILE_VALUE'] = [$arProperty['FILE_VALUE']];
		}


		$arProperty['VIEW'] = $arParams['RS_FILE_PROP_'.$sPropCode.'_VIEW'];

		if ($arProperty['VIEW'] == 'line') {
			foreach ($arProperty['FILE_VALUE'] as &$arFile) {
				$arFile['FILE_EXT'] = end(explode('.', $arFile['FILE_NAME']));
				$arFile['FILE_SIZE'] = CFile::FormatSize($arFile['FILE_SIZE'], 1);


				switch($arFile['FILE_EXT']) {
					case 'docx':
					case 'doc':
						$arFile['FILE_ICON'] = 'doc';
						break;
					case 'xls':
					case 'xlsx':
						$arFile['FILE_ICON'] = 'xls';
						break;
					case 'pdf':
						$arFile['FILE_ICON'] = 'pdf';
						break;
					default:
						$arFile['FILE_ICON'] = 'txt';
						break;
				}
			}

			unset($arFile);
		}

		$arResult['DISPLAY_FILE_PROPS'][$sPropCode] = $arProperty;
		$arResult['DISPLAY_SKIP_PROPERTIES'][] = $sPropCode;
	} else if (
		$arProperty['PROPERTY_TYPE'] == \Bitrix\Iblock\PropertyTable::TYPE_ELEMENT &&
		count($arProperty['VALUE']) > 0
	) {
		$arResult['DISPLAY_BIND_PROPS'][$sPropCode] = $arProperty;
		$arResult['DISPLAY_SKIP_PROPERTIES'][] = $sPropCode;
	}
}

// add cache keys
$cp = $this->__component;
if (is_object($cp)) {
	$cp->SetResultCacheKeys(['DISPLAY_FILE_PROPS', 'DISPLAY_BIND_PROPS', 'TAGS_ARRAY']);
}
