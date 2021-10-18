<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;
use	\Bitrix\Main\Config\Option;
use \Redsign\MegaMart\TextUtils;

$arParams['PROP_STICKER'] = isset($arParams['PROP_STICKER']) ? $arParams['PROP_STICKER'] : 'STICKER';

// Tags
$arResult['TAGS_ARRAY'] = [];
if (!empty($arResult['TAGS'])) {
	$arTags = array_map('trim', explode(',', $arResult['TAGS']));

	foreach ($arTags as $sTag) {
		$sTagTranslit = CUtil::translit($sTag, "ru");

		$arr = $arResult['TAGS_ARRAY'][] = [
			'NAME' => $sTag,
			'LINK' => SITE_DIR.'search/?q='.urlencode($sTag)
		];
	}
}

$arResult['DISPLAY_SKIP_PROPERTIES'] = [$arParams['PROP_STICKER']];
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


// Get Reading time
if (!empty($arResult['DETAIL_TEXT'])) {
	$arResult['READING_TIME'] = TextUtils::getReadingTime($arResult['DETAIL_TEXT']);
}

//Schema.org additional
$arResult['MICRODATA']['ORGANIZATION']['NAME'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'microdata_organization_name');
$arResult['MICRODATA']['ORGANIZATION']['IMAGE_URL'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'microdata_organization_image_url');
$arResult['MICRODATA']['ORGANIZATION']['ADDRESS'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'microdata_organization_address');
$arResult['MICRODATA']['ORGANIZATION']['PHONE'] = \Bitrix\Main\Config\Option::get('redsign.megamart', 'microdata_organization_phone');

if (!isset($arParams['PUBLISHER_TYPE']))
	$arParams['PUBLISHER_TYPE'] = 'organization';

if ($arParams['PUBLISHER_TYPE'] == 'person') {
	if (isset($arResult['CREATED_BY'])) {
		$arUser = CUser::GetByID($arResult['CREATED_BY']);
		$user = $arUser->Fetch();
		$arResult['MICRODATA']['AUTHOR']["NAME"] = $user["NAME"];
		$arResult['MICRODATA']['AUTHOR']["LAST_NAME"] = $user["LAST_NAME"];
		$arResult['MICRODATA']['AUTHOR']['FULL_NAME'] = $user["NAME"].' '.$user["LAST_NAME"];
	}
}
else if ($arParams['PUBLISHER_TYPE'] == 'organization') {
	if (isset($arResult['MICRODATA']['ORGANIZATION']['NAME']) && strlen($arResult['MICRODATA']['ORGANIZATION']['NAME']) > 0) {
		$arResult['MICRODATA']['AUTHOR']['FULL_NAME'] = $arResult['MICRODATA']['ORGANIZATION']['NAME'];
	}
	else {
		$rsSites = CSite::GetByID(SITE_ID);
		if ($arSite = $rsSites->Fetch()) {
			$arResult['MICRODATA']['AUTHOR']['FULL_NAME'] = $arSite['SITE_NAME'];
		}
	}
}
?>
