<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

use \Bitrix\Main\Page\Asset;

CJSCore::Init(array('fx', 'popup'));

if (
    is_array($arParams['SCROLL_PROPS']) && count($arParams['SCROLL_PROPS']) > 0
    || is_array($arParams['OFFER_SCROLL_PROPS']) && count($arParams['OFFER_SCROLL_PROPS']) > 0
) {
    $asset = Asset::getInstance();
    $asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery.scrollbar/jquery.scrollbar.js');
    $asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery-mousewheel/jquery.mousewheel.js');
}
