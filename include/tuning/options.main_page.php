<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$mainPageBlocks = array();

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_FEATURES'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_FEATURES'),
    'CONTROL_ID' => 'mm_main_page_block_features',
    'CONTROL_NAME' => 'mm_main_page_block_features',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_PRODUCTS'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_PRODUCTS'),
    'CONTROL_ID' => 'mm_main_page_block_products',
    'CONTROL_NAME' => 'mm_main_page_block_products',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_PROMO_BANNER_SHORT'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_PROMO_BANNER_SHORT'),
    'CONTROL_ID' => 'mm_main_page_block_promo_banner_short',
    'CONTROL_NAME' => 'mm_main_page_block_promo_banner_short',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_MOZAIC_BANNER_1'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage(
        'RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_MOZAIC_BANNER',
        [
            '#NUM#' => ' 1'
        ]
    ),
    'CONTROL_ID' => 'mm_main_page_block_mozaic_banner_1',
    'CONTROL_NAME' => 'mm_main_page_block_mozaic_banner_1',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'N',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_MOZAIC_BANNER_2'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage(
        'RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_MOZAIC_BANNER',
        [
            '#NUM#' => ' 2'
        ]
    ),
    'CONTROL_ID' => 'mm_main_page_block_mozaic_banner_2',
    'CONTROL_NAME' => 'mm_main_page_block_mozaic_banner_2',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'N',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_POPULAR_CATALOG_CATEGORIES'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_POPULAR_CATALOG_CATEGORIES'),
    'CONTROL_ID' => 'mm_main_page_block_popular_catalog_categories',
    'CONTROL_NAME' => 'mm_main_page_block_popular_catalog_categories',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_CATEGORY_BANNERS'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_CATEGORY_BANNERS'),
    'CONTROL_ID' => 'mm_main_page_block_category_banners',
    'CONTROL_NAME' => 'mm_main_page_block_category_banners',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_REVIEWS'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_REVIEWS'),
    'CONTROL_ID' => 'mm_main_page_block_reviews',
    'CONTROL_NAME' => 'mm_main_page_block_reviews',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_BRANDS'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_BRANDS'),
    'CONTROL_ID' => 'mm_main_page_block_brands',
    'CONTROL_NAME' => 'mm_main_page_block_brands',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_MAP_WITH_SHOPS'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_MAP_WITH_SHOPS'),
    'CONTROL_ID' => 'mm_main_page_block_map_with_shops',
    'CONTROL_NAME' => 'mm_main_page_block_map_with_shops',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_SUBSCRIBE'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_SUBSCRIBE'),
    'CONTROL_ID' => 'mm_main_page_block_subscribe',
    'CONTROL_NAME' => 'mm_main_page_block_subscribe',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_POPULAR_PRODUCTS'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_POPULAR_PRODUCTS'),
    'CONTROL_ID' => 'mm_main_page_block_popular_products',
    'CONTROL_NAME' => 'mm_main_page_block_popular_products',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_NEWS_AND_REVIEWS'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_NEWS_AND_REVIEWS'),
    'CONTROL_ID' => 'mm_main_page_block_news_and_reviews',
    'CONTROL_NAME' => 'mm_main_page_block_news_and_reviews',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'Y',
    'RELOAD' => 'Y',
);

$mainPageBlocks['MM_MAIN_PAGE_BLOCK_ABOUT_COMPANY'] = array(
    'TYPE' => 'SWITCH',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCK_ABOUT_COMPANY'),
    'CONTROL_ID' => 'mm_main_page_block_about_company',
    'CONTROL_NAME' => 'mm_main_page_block_about_company',
    'HTML_VALUE' => 'Y',
    'DEFAULT' => 'N',
    'RELOAD' => 'Y',
);

$arParams['MM_TITLE_TAB_MAIN_PAGE'] =  array(
    'TAB' => 'TAB_MAIN_PAGE',
    'TYPE' => 'TITLE',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_TITLE_TAB_MAIN_PAGE'),
);

$arParams['MM_MAIN_PAGE_BLOCKS_SORTING'] = array(
    'TAB' => 'TAB_MAIN_PAGE',
    'TYPE' => 'DD',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_MM_MAIN_PAGE_BLOCKS_SORTING'),
    'CONTROL_ID' => 'main_page_blocks_sorting',
    'CONTROL_NAME' => 'main_page_blocks_sorting',
    'DEFAULT' => array(
        'MM_MAIN_PAGE_BLOCK_FEATURES',
        'MM_MAIN_PAGE_BLOCK_PRODUCTS',
        'MM_MAIN_PAGE_BLOCK_PROMO_BANNER_SHORT',
        'MM_MAIN_PAGE_BLOCK_MOZAIC_BANNER_1',
        'MM_MAIN_PAGE_BLOCK_MOZAIC_BANNER_2',
        'MM_MAIN_PAGE_BLOCK_POPULAR_CATALOG_CATEGORIES',
        'MM_MAIN_PAGE_BLOCK_CATEGORY_BANNERS',
        'MM_MAIN_PAGE_BLOCK_REVIEWS',
        'MM_MAIN_PAGE_BLOCK_BRANDS',
        'MM_MAIN_PAGE_BLOCK_MAP_WITH_SHOPS',
        'MM_MAIN_PAGE_BLOCK_SUBSCRIBE',
        'MM_MAIN_PAGE_BLOCK_POPULAR_PRODUCTS',
        'MM_MAIN_PAGE_BLOCK_NEWS_AND_REVIEWS',
        'MM_MAIN_PAGE_BLOCK_ABOUT_COMPANY',
    ),
    'RELOAD' => 'Y',
    'CHILDREN' => $mainPageBlocks,
    'NUMBERS' => 'Y',
);
