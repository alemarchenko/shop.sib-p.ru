<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

global $RS_FAVORITE_DATA;
global $RS_BASKET_DATA;
global $RS_COMPARE_DATA;

// global basket
$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket.line",
	"global",
	array(
		"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
		"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
		"SHOW_DELAY" => "N",
		"SHOW_PRODUCTS"=>"Y",
		"SHOW_EMPTY_VALUES" => "N",
		"SHOW_NOTAVAIL" => "N",
		"SHOW_SUBSCRIBE" => "N",
		"SHOW_IMAGE" => "N",
		"SHOW_PRICE" => "N",
		"SHOW_SUMMARY" => "N",
		"SHOW_NUM_PRODUCTS" => "Y",
		"SHOW_TOTAL_PRICE" => "N",
		"HIDE_ON_BASKET_PAGES" => "N"
	),
	false,
	array(
		'HIDE_ICONS' => 'Y'
	)
);

$APPLICATION->IncludeComponent(
	"redsign:favorite.list",
	"global",
	array(
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"ACTION_VARIABLE" => "favaction",
		"PRODUCT_ID_VARIABLE" => "id",
		"FAVORITE_URL" => SITE_DIR."/personal/wishlist/"
	),
	false
);

// global compare
$sGlobalComparePath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/global/compare.php';
if (file_exists($sGlobalComparePath)) {
	include $sGlobalComparePath;
}

// global tuning
$APPLICATION->IncludeFile(
	SITE_DIR."include/tuning/component.php",
	Array(),
	Array("MODE"=>"html")
);

// global location
$APPLICATION->IncludeComponent(
	'redsign:location.main',
	'global',
	array()
);