<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

if (defined('RS_MM_DEFINED_CONSTANTS') && RS_MM_DEFINED_CONSTANTS === true) {
	return;
}

use \Bitrix\Main\Loader;

$tuning = false;
if (Loader::includeModule('redsign.tuning'))
{
	$tuning = \Redsign\Tuning\TuningCore::getInstance();
}

define('RS_MM_DEFINED_CONSTANTS', true);
define('RS_MM_USE_UP_BUTTON', true);

if ($tuning)
{
	define('RS_MM_CONTAINER_MAX_WIDTH', $tuning->getOptionValue('CONTAINER_MAX_WIDTH'));
	define('RS_MM_USE_FONT_FAMILY', $tuning->getOptionValue('FONT_FAMILY'));
	define('RS_MM_FONT_SIZE', $tuning->getOptionValue('FONT_SIZE'));
	define('RS_MM_POPUP_TYPE', $tuning->getOptionValue('POPUP_TYPE'));
	define('RS_MM_CONTROL_PANEL', $tuning->getOptionValue('CONTROL_PANEL'));
	define('RS_MM_FOOTER_THEME', $tuning->getOptionValue('FOOTER_THEME'));
	define('RS_MM_SEARCH_POPUP', $tuning->getOptionValue('SEARCH_POPUP'));
	define('RS_MM_MENU_THEME', $tuning->getOptionValue('MENU_THEME'));
	define('RS_MM_HEADER_TYPE', $tuning->getOptionValue('HEADER_TYPE'));
	define('RS_MM_BANNER_TYPE', $tuning->getOptionValue('MAIN_BANNER_TYPE'));
	define('RS_MM_FIX_HEADER', $tuning->getOptionValue('FIX_HEADER'));

	define('RS_MM_NEWS_TEMPLATE', $tuning->getOptionValue('NEWS_TEMPLATE'));
	define('RS_MM_SALE_PROMOTIONS_TEMPLATE', $tuning->getOptionValue('SALE_PROMOTIONS_TEMPLATE'));
	define('RS_MM_ARTICLES_TEMPLATE', $tuning->getOptionValue('ARTICLES_TEMPLATE'));
	define('RS_MM_STAFF_TEMPLATE', $tuning->getOptionValue('STAFF_TEMPLATE'));
	define('RS_MM_LICENSE_TEMPLATE', $tuning->getOptionValue('LICENSE_TEMPLATE'));
    //define('RS_MM_FILTER_TEMPLATE', $tuning->getOptionValue('FILTER_TEMPLATE'));
    
    define('RS_MM_CATALOG_SECTION_TYPE', $tuning->getOptionValue('CATALOG_SECTION_TYPE'));
}
else
{
	define('RS_MM_CONTAINER_MAX_WIDTH', 'cw_normal');
	define('RS_MM_USE_FONT_FAMILY', 'system');
	define('RS_MM_FONT_SIZE', 'normal');
	define('RS_MM_POPUP_TYPE', 'window');
	define('RS_MM_CONTROL_PANEL', 'none');
	define('RS_MM_FOOTER_THEME', 'dark');
	define('RS_MM_SEARCH_POPUP', 'top');
	define('RS_MM_MENU_THEME', 'dark');
	define('RS_MM_HEADER_TYPE', 'type1');
	define('RS_MM_BANNER_TYPE', 'underlay');
	define('RS_MM_FIX_HEADER', 'Y');

	define('RS_MM_NEWS_TEMPLATE', 'tile');
	define('RS_MM_SALE_PROMOTIONS_TEMPLATE', "tile");
	define('RS_MM_ARTICLES_TEMPLATE', 'type1');
    define('RS_MM_LICENSE_TEMPLATE', 'col');
    define('RS_MM_CATALOG_SECTION_TYPE', 'type2');
	//define('RS_MM_FILTER_TEMPLATE', 'vertical');
}

if (!defined('SITE_LOCATION_ID'))
{
	define('SITE_LOCATION_ID', null);
}