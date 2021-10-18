<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

global $RS_BASKET_DATA; // from sale.basket.basket.line -> global

if (isset($RS_BASKET_DATA)) {
	$rs = new \Bitrix\Main\Type\RandomSequence();
	$id = $rs->randString();

	$sPathToCart = $RS_BASKET_DATA['PATH_TO_BASKET'];

	if ($arParams['AJAX_LOAD'] == 'Y') {
		$sCurrentPage = strtolower(\Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPage());
		$sPathToOrder = strtolower($RS_BASKET_DATA['PATH_TO_ORDER']);

		if (
			strncmp($sCurrentPage, $sPathToCart, strlen($sPathToCart)) == 0
			|| strncmp($sCurrentPage, $sPathToOrder, strlen($sPathToOrder)) == 0
		) {
			$arParams['AJAX_LOAD'] = 'N';
		}
	}

	$frame = new \Bitrix\Main\Page\FrameBuffered($id);

	$frame->begin();
	?><a href="<?=$sPathToCart?>" <?
	 	?>class="c-icon-count<?=($RS_BASKET_DATA['NUM_PRODUCTS'] > 0 ? ' has-items' : '')?> js-global-cart"<?
		if ($arParams['AJAX_LOAD'] == 'Y') {
			?> data-src="<?=SITE_DIR.'ajax/cart.php';?>" data-type="ajax" data-popup-type="side" data-need-cache="Y"<?php
		}
		?> title="<?=Loc::getMessage('RS_SIDE_PANEL_CART'); ?>">
		<svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg>
		<span class="c-icon-count__quantity js-global-cart__count"><?=$RS_BASKET_DATA['NUM_PRODUCTS']?></span>
	</a>
	<?php

	$frame->beginStub();
	?><a href="<?=$RS_BASKET_DATA['PATH_TO_BASKET'];?>" class="c-icon-count js-global-cart">
		<svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg>
		<span class="c-icon-count__quantity js-global-cart__count">0</span>
	</a>
	<?php $frame->end();
}
