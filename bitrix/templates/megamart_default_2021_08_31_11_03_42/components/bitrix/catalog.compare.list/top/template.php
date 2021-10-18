<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

$itemCount = count($arResult);
$idCompareCount = 'compareList'.$this->randString();
$obCompare = 'ob'.$idCompareCount;
$idCompareTable = $idCompareCount.'_tbl';
$idCompareRow = $idCompareCount.'_row_';
$idCompareAll = $idCompareCount.'_count';
$needReload = (
    $request->get('ajax_id') == $idCompareCount
    && $request->get('compare_list_reload') == 'Y'
);
?>
<div id="<?=$idCompareCount?>" class="compare-top">
<?
if ($needReload)
{
	$APPLICATION->RestartBuffer();
}
?>
<a href="<?=$arParams["COMPARE_URL"]; ?>" class="c-icon-count<?=($itemCount > 0 ? ' has-items' : '')?>"><?
// $frame = $this->createFrame($idCompareCount)->begin('');
?>
<svg class="icon-svg"><use xlink:href="#svg-copy"></use></svg>
<span class="c-icon-count__quantity" id="<? echo $idCompareAll; ?>">
    <?=($itemCount > 0 ? $itemCount : 0);?>
</span>
</a><?
// $frame->end();
if ($needReload)
{
	die();
}
$currentPath = CHTTP::urlDeleteParams(
	$APPLICATION->GetCurPageParam(),
	array(
		$arParams['PRODUCT_ID_VARIABLE'],
		$arParams['ACTION_VARIABLE'],
		'ajax_action'
	),
	array("delete_system_params" => true)
);

$jsParams = array(
	'VISUAL' => array(
		'ID' => $idCompareCount,
	),
	'AJAX' => array(
		'url' => $currentPath,
		'params' => array(
			'ajax_action' => 'Y'
		),
		'reload' => array(
			'compare_list_reload' => 'Y',
            'ajax_id' => $idCompareCount,
		),
		'templates' => array(
			'delete' => (strpos($currentPath, '?') === false ? '?' : '&').$arParams['ACTION_VARIABLE'].'=DELETE_FROM_COMPARE_LIST&'.$arParams['PRODUCT_ID_VARIABLE'].'='
		)
	),
	'POSITION' => array(
		'fixed' => $arParams['POSITION_FIXED'] == 'Y',
		'align' => array(
			'vertical' => $arParams['POSITION'][0],
			'horizontal' => $arParams['POSITION'][1]
		)
	)
);
?>
<script type="text/javascript">
var <? echo $obCompare; ?> = new JCCatalogCompareList(<? echo CUtil::PhpToJSObject($jsParams, false, true); ?>)
</script>
</div>

<?php $this->SetViewTarget('compact-menu-compare'); ?>
<a href="<?=$arParams["COMPARE_URL"]; ?>" class="c-icon-count" id="<?=$idCompareCount?>">
	<svg class="icon-svg"><use xlink:href="#svg-copy"></use></svg>
	<span class="c-icon-count__quantity" id="<? echo $idCompareAll; ?>">
	    <?=($itemCount > 0 ? $itemCount : 0);?>
	</span>
</a>
<?php $this->EndViewTarget(); ?>
