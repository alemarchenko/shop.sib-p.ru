<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Application;

global $RS_FAVORITE_DATA;

if (!isset($RS_FAVORITE_DATA))
{
	$RS_FAVORITE_DATA = $arResult;
	$RS_FAVORITE_DATA['PATH_TO_FAVORITE'] = isset($params['FAVORITE_URL']) ? trim($params['FAVORITE_URL']) : SITE_DIR.'personal/wishlist/';
}

$arIDs = array();
if (is_array($arResult['ITEMS']) && 0 < count($arResult['ITEMS']))
{
	foreach ($arResult['ITEMS'] as $arItem)
	{
		$arIDs[] = $arItem['ELEMENT_ID'];
	}
}

$this->createFrame()->begin('');
?>
<script>
	RS.Favorite.init(<?=\Bitrix\Main\Web\Json::encode($arIDs)?>);
	BX.onCustomEvent('change.rs_favorite');
</script>
