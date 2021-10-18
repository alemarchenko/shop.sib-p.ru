<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

use \Bitrix\Main\Loader;

$arParams['PROP_NAME'] = isset($arParams['PROP_NAME']) ? $arParams['PROP_NAME'] : 'NAME';
$arParams['PROP_POSITION'] = isset($arParams['PROP_POSITION']) ? $arParams['PROP_POSITION'] : 'POSITION';
$arParams['PROP_DESCRIPTION'] = isset($arParams['PROP_DESCRIPTION']) ? $arParams['PROP_DESCRIPTION'] : 'DESCRIPTION';
$arParams['PROP_CONTACTS'] = isset($arParams['PROP_CONTACTS']) ? $arParams['PROP_CONTACTS'] : 'CONTACTS';
$arParams['PROP_IS_ASK'] = isset($arParams['PROP_IS_ASK']) ? $arParams['PROP_IS_ASK'] : 'IS_ASK';
$arParams['PROP_SOCIAL'] = isset($arParams['PROP_SOCIAL']) ? $arParams['PROP_SOCIAL'] : 'SOCIALS';

if (intval($arParams['PARENT_SECTION']) > 0) {
	if (is_array($arResult['SECTION']['PATH']) && count($arResult['SECTION']['PATH']) > 0) {
		foreach ($arResult['SECTION']['PATH'] as $iSectionKey => $arSection) {
			if ($arParams['PARENT_SECTION'] == $arSection['ID']) {
				$arResult['PARENT_SECTION'] = $arSection;
			}
		}
		unset($arSection);
	}
}

if (isset($arResult['PARENT_SECTION'])) {
	$this->__component->arResult['PARENT_SECTION'] = $arResult['PARENT_SECTION'];
	$this->__component->SetResultCacheKeys(array('PARENT_SECTION'));
}


if (isset($arParams['PARENT_TITLE']) && strlen($arParams['PARENT_TITLE']) > 0) {
	$arResult['PARENT_TITLE'] = $arParams['PARENT_TITLE'];
} elseif (isset($arResult['PARENT_SECTION'])) {
	$arResult['PARENT_TITLE'] = $arResult['PARENT_SECTION']['NAME'];
} else {
	$arResult['PARENT_TITLE'] = '';
}


if (!isset($arParams['RS_TEMPLATE'])) {
	$arParams['RS_TEMPLATE'] = 'type1';
}

if ($arParams['RS_TEMPLATE'] == 'from_widget') {
	if (isset($arParams['RS_TEMPLATE_FROM_WIDGET'])) {
		$arParams['RS_TEMPLATE'] = $arParams['RS_TEMPLATE_FROM_WIDGET'];
	} else {
		$arParams['RS_TEMPLATE'] = 'type1';
	}
}

$arResult['TEMPLATE_PATH'] = $_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/templates/'.$arParams['RS_TEMPLATE'].'.php';
