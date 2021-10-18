<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

use \Bitrix\Main\Page\Asset;

CJSCore::Init(array('fx'));
/*
if (is_array($arResult['SECTION']['UF_FEATURED_FILTER']) && count($arResult['SECTION']['UF_FEATURED_FILTER']) > 0)
{
    $asset = Asset::getInstance();
    $asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery.scrollbar/jquery.scrollbar.js');
    $asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery-mousewheel/jquery.mousewheel.js');
}
*/