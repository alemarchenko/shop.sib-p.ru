<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Application;

global $RS_COMPARE_DATA;
global $APPLICATION;

if (!isset($RS_COMPARE_DATA)) {
	$RS_COMPARE_DATA = [
		'ITEMS' => $arResult,
		'COUNT' => count($arResult),
		'COMPARE_URL' => $arParams['COMPARE_URL']
	];
}

$request = Application::getInstance()->getContext()->getRequest();
$sCompareId = 'compareList'.$this->randString();

$isAjax = (
	$request->get('ajax_id') == $sCompareId
	&& $request->get('compare_list_reload') == 'Y'
);

if ($isAjax) {
	$APPLICATION->RestartBuffer();
	echo CUtil::PhpToJSObject($RS_COMPARE_DATA);
	die();
}

$sCurrentPath = CHTTP::urlDeleteParams(
	$APPLICATION->GetCurPageParam(),
	array(
		$arParams['PRODUCT_ID_VARIABLE'],
		$arParams['ACTION_VARIABLE'],
		'ajax_action'
	),
	array("delete_system_params" => true)
);

$this->createFrame()->begin('');
?>
<script>
	if ((window.RS || {}).GlobalCompare) {
		RS.GlobalCompare.init(
			'<?=$sCurrentPath?>',
			'<?=$sCompareId?>'
		);
	}
</script>
<?
