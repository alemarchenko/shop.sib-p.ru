<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Application;
use	\Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$APPLICATION->SetPageProperty('hide_section', 'Y');
$APPLICATION->SetPageProperty('hide_title', 'Y');
$APPLICATION->SetPageProperty('hide_inner_sidebar', 'N');
$APPLICATION->SetPageProperty('hide_outer_sidebar', 'Y');

$layout = new \Redsign\MegaMart\Layouts\Section();
$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing');

$layout->start();
?>

<article class="bx-article-detail" itemscope itemtype="http://schema.org/NewsArticle">
	<div class="b-article-detail">
		<?php include $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/include/header.php'; ?>

		<div class="b-article-detail__content">
			<?php
			$arDetailParams = array(
				"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
				"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
				"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
				"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
				"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
				"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"META_KEYWORDS" => $arParams["META_KEYWORDS"],
				"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
				"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
				"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
				"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
				"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
				"SET_TITLE" => $arParams["SET_TITLE"],
				"MESSAGE_404" => $arParams["MESSAGE_404"],
				"SET_STATUS_404" => $arParams["SET_STATUS_404"],
				"SHOW_404" => $arParams["SHOW_404"],
				"FILE_404" => $arParams["FILE_404"],
				"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
				"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
				"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
				"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
				"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
				"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
				"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
				"PAGER_SHOW_ALWAYS" => "N",
				"USE_COMMENTS" => $arParams["USE_COMMENTS"],
				"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
				"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
				"CHECK_DATES" => $arParams["CHECK_DATES"],
				"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
				"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
				"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
				"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
				"USE_SHARE" => $arParams["USE_SHARE"],
				"SHARE_HIDE" => $arParams["SHARE_HIDE"],
				"SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
				"SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
				"SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
				"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
				"ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
				'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
				"USE_RSS" => $arParams["USE_RSS"],
				'RSS_URL' => (isset($arParams['SEF_URL_TEMPLATES']['rss']) ? $arParams['SEF_URL_TEMPLATES']['rss'] : SITE_DIR.'news/rss/'),
				// megamart
				// megamart - news
				"NEWS_VIEW_MODE" => $arParams["NEWS_VIEW_MODE"],
				"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
				"BINDED_ELEMENTS_PROP" => $arParams["BINDED_ELEMENTS_PROP"],
				// megamart - news - detail
				'NEWS_DETAIL_BACK_URL' => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
				// megamart - grid
				"GRID_RESPONSIVE_SETTINGS" => $arParams["~GRID_RESPONSIVE_SETTINGS"],
				// megamart - share
				"USE_SHARE" => $arParams["USE_SHARE"],
				"SOCIAL_SERVICES" => $arParams["SOCIAL_SERVICES"],
				"SOCIAL_COUNTER" => $arParams["SOCIAL_COUNTER"],
				"SOCIAL_COPY" => $arParams["SOCIAL_COPY"],
				"SOCIAL_LIMIT" => $arParams["SOCIAL_LIMIT"],
				"SOCIAL_SIZE" => $arParams["SOCIAL_SIZE"],
				// megamart - lazy
				"RS_LAZY_IMAGES_USE" => $arParams["RS_LAZY_IMAGES_USE"],
				// megamart - microdata
				"PUBLISHER_TYPE" => $arParams["PUBLISHER_TYPE"],
				// /megamart
			);

			if (count($arParams['DETAIL_PROPERTY_CODE']) > 0) {
				foreach ($arParams['DETAIL_PROPERTY_CODE'] as $sPropCode) {
					$sFileViewParamKey = 'RS_FILE_PROP_'.$sPropCode.'_VIEW';
					$sBindFileParamKey = 'RS_BIND_PROP_'.$sPropCode.'_INCLUDE_FILE';

					if (isset($arParams[$sFileViewParamKey])) {
						$arDetailParams[$sFileViewParamKey] = $arParams[$sFileViewParamKey];
					} else if (isset($arParams[$sBindFileParamKey])) {
						$arDetailParams[$sBindFileParamKey] = $arParams[$sBindFileParamKey];
					}
				}
			}

			$elementId = $APPLICATION->IncludeComponent(
				"bitrix:news.detail",
				"article",
				$arDetailParams,
				$component
			);
			?>
		</div>

		<?php include $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/include/tags.php'; ?>
		<?php include $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/include/footer.php'; ?>
	</div>
</article>
<?php

$layout->end();

include $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/include/files.php';
include $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/include/binds.php';
include $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/include/comments.php';
