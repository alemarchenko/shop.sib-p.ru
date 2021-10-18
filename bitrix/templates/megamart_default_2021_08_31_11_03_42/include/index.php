<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use  Bitrix\Main\Loader;

$tuning = false;
if (Loader::includeModule('redsign.tuning'))
{
	$tuning = \Redsign\Tuning\TuningCore::getInstance();
}


$arBlocksId = [
	'MM_MAIN_PAGE_BLOCK_FEATURES' => 'features',
	'MM_MAIN_PAGE_BLOCK_PRODUCTS' => 'products',
	'MM_MAIN_PAGE_BLOCK_PROMO_BANNER_SHORT' => 'adv_full_banner',
	'MM_MAIN_PAGE_BLOCK_MOZAIC_BANNER_1' => 'mozaic_banner_1',
	'MM_MAIN_PAGE_BLOCK_MOZAIC_BANNER_2' => 'mozaic_banner_2',
	'MM_MAIN_PAGE_BLOCK_POPULAR_CATALOG_CATEGORIES' => 'popular_sections',
	'MM_MAIN_PAGE_BLOCK_CATEGORY_BANNERS' => 'adv_banner_grid',
	'MM_MAIN_PAGE_BLOCK_REVIEWS' => 'reviews',
	'MM_MAIN_PAGE_BLOCK_BRANDS' => 'brands',
	'MM_MAIN_PAGE_BLOCK_MAP_WITH_SHOPS' => 'shops',
	'MM_MAIN_PAGE_BLOCK_SUBSCRIBE' => 'sender',
	'MM_MAIN_PAGE_BLOCK_POPULAR_PRODUCTS' => 'viewed_items',
	'MM_MAIN_PAGE_BLOCK_NEWS_AND_REVIEWS' => 'helpful_information',
	'MM_MAIN_PAGE_BLOCK_ABOUT_COMPANY' => 'company',
];


if ($tuning)
{
	$arSortingBlocks = $tuning->getOptionValue('MM_MAIN_PAGE_BLOCKS_SORTING');
}
else
{
	$arSortingBlocks = array_keys($arBlocksFileName);
}

if (is_array($arSortingBlocks) && count($arSortingBlocks) > 0)
{
	?><div data-entity="index-page"><?
	foreach ($arSortingBlocks as $sBlockName)
	{
		if (!isset($arBlocksId[$sBlockName]))
		{
			continue;
		}


		$isEnabledBlock = $tuning->getOptionValue($sBlockName);
		if ($isEnabledBlock == 'Y')
		{
			$sBlockId = $arBlocksId[$sBlockName];

			?><div id="<?=$sBlockId?>" data-entity="index-page-block" data-block-name="<?=$sBlockName?>"><?
			$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_DIR.'include/index/'.$sBlockId.'.php'
				),
				array("HIDE_ICONS" => "Y")
			);
			?></div><?
		}
	}
	?></div><?
}
