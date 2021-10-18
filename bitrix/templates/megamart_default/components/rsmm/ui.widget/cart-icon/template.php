<?php
/**
 * @var CBitrixComponentTemplate $this
 */

use Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

$this->setFrameMode(true);

global $RS_BASKET_DATA;

if (!isset($RS_BASKET_DATA))
	return;

$widgetId = 'cart-icon-'.$this->randString();
$countItems = $RS_BASKET_DATA['NUM_PRODUCTS'];
$sWidgetPath = $RS_BASKET_DATA['PATH_TO_BASKET'];

if ($arParams['AJAX_LOAD'] == 'Y')
{
	$sCurrentPage = mb_strtolower(\Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPage());
	$sPathToOrder = mb_strtolower($RS_BASKET_DATA['PATH_TO_ORDER']);

	if (
		strncmp($sCurrentPage, $sWidgetPath, mb_strlen($sWidgetPath)) == 0
		|| strncmp($sCurrentPage, $sPathToOrder, mb_strlen($sPathToOrder)) == 0
	) {
		$arParams['AJAX_LOAD'] = 'N';
	}
}

$frame = $this->createFrame($widgetId.'-frame');
$frame->begin();
?>
	<a href="<?=$sWidgetPath?>" <?
	 	?>class="c-icon-count<?=($countItems ? ' has-items' : '')?> js-global-cart"<?
		if ($arParams['AJAX_LOAD'] == 'Y') {
			?> data-src="<?=SITE_DIR.'ajax/cart.php';?>" data-type="ajax" data-popup-type="side" data-need-cache="Y"<?php
		}
		?> title="<?=Loc::getMessage('RS_MM_UI_WIDGET_CART'); ?>" id="<?=$widgetId?>" aria-label="<?=Loc::getMessage('RS_MM_UI_WIDGET_CART')?>">
		<svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg>
		<span class="c-icon-count__quantity js-global-cart__count"><?=$countItems?></span>
	</a>
<?php $frame->beginStub(); ?>
	<a href="<?=$sWidgetPath?>" class="c-icon-count js-global-cart" id="<?=$widgetId?>" aria-label="<?=Loc::getMessage('RS_MM_UI_WIDGET_CART')?>">
		<svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg>
		<span class="c-icon-count__quantity js-global-cart__count">0</span>
	</a>
<?php $frame->end(); ?>
