<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;

/*
?>
<div data-entity="parent-container">
	<?
	if (!isset($arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE']) || $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y')
	{
		?>
		<div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
			<?=($arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_GIFTS_MAIN_BLOCK_TITLE_DEFAULT'))?>
		</div>
		<?
	}
*/
	$APPLICATION->IncludeComponent(
		'bitrix:sale.gift.main.products',
		'catalog',
		array(
			'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
			'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
			'LINE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
			'HIDE_BLOCK_TITLE' => 'Y',
			'BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

			'OFFERS_FIELD_CODE' => $arParams['OFFERS_FIELD_CODE'],
			'OFFERS_PROPERTY_CODE' => $arParams['OFFERS_PROPERTY_CODE'],

			'AJAX_MODE' => $arParams['AJAX_MODE'],
			'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
			'IBLOCK_ID' => $arParams['IBLOCK_ID'],

			'ELEMENT_SORT_FIELD' => 'ID',
			'ELEMENT_SORT_ORDER' => 'DESC',
			//'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
			//'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],
			'FILTER_NAME' => 'searchFilter',
			'SECTION_URL' => $arParams['SECTION_URL'],
			'DETAIL_URL' => $arParams['DETAIL_URL'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
			'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
			'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

			'CACHE_TYPE' => $arParams['CACHE_TYPE'],
			'CACHE_TIME' => $arParams['CACHE_TIME'],

			'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
			'SET_TITLE' => $arParams['SET_TITLE'],
			'PROPERTY_CODE' => $arParams['PROPERTY_CODE'],
			'PRICE_CODE' => $arParams['PRICE_CODE'],
			'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
			'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],

			'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
			'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
			'CURRENCY_ID' => $arParams['CURRENCY_ID'],
			'HIDE_NOT_AVAILABLE' => 'Y',
			'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
			'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
			'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],

			'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
			'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
			'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

			'ADD_PICT_PROP' => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),
			'LABEL_PROP' => (isset($arParams['LABEL_PROP']) ? $arParams['LABEL_PROP'] : ''),
			'LABEL_PROP_MOBILE' => (isset($arParams['LABEL_PROP_MOBILE']) ? $arParams['LABEL_PROP_MOBILE'] : ''),
			'LABEL_PROP_POSITION' => (isset($arParams['LABEL_PROP_POSITION']) ? $arParams['LABEL_PROP_POSITION'] : ''),
			'OFFER_ADD_PICT_PROP' => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),
			'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : ''),
			'SHOW_DISCOUNT_PERCENT' => (isset($arParams['SHOW_DISCOUNT_PERCENT']) ? $arParams['SHOW_DISCOUNT_PERCENT'] : ''),
			'DISCOUNT_PERCENT_POSITION' => (isset($arParams['DISCOUNT_PERCENT_POSITION']) ? $arParams['DISCOUNT_PERCENT_POSITION'] : ''),
			'SHOW_OLD_PRICE' => (isset($arParams['SHOW_OLD_PRICE']) ? $arParams['SHOW_OLD_PRICE'] : ''),
			'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
			'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
			'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
			'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
			'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
			'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
			'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) ? $arParams['DISPLAY_COMPARE'] : ''),
			'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),

			'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
			"DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
			"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
			'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],

			'IS_USE_CART' => $arParams['IS_USE_CART'],
			'PRICE_PROP' => $arParams['PRICE_PROP'],
			'DISCOUNT_PROP' => $arParams['DISCOUNT_PROP'],
			'CURRENCY_PROP' => $arParams['CURRENCY_PROP'],
			'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
			'SHOW_PARENT_DESCR' => 'Y',

			'TEMPLATE_VIEW' => $arParams['LIST_TEMPLATE_VIEW'],
			'GRID_RESPONSIVE_SETTINGS' => $arParams['~GRID_RESPONSIVE_SETTINGS'],
			'USE_VOTE_RATING' => $arParams['LIST_USE_VOTE_RATING'],
			'VOTE_DISPLAY_AS_RATING' => $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'],
			'SHOW_RATING' => $arParams['SHOW_RATING'],

			'USE_GIFTS' => $arParams['USE_GIFTS'],
			'USE_FAVORITE' => $arParams['USE_FAVORITE'],
			'FAVORITE_COUNT_PROP' => $arParams['FAVORITE_COUNT_PROP'],
			'SHOW_ARTNUMBER' => $arParams['SHOW_ARTNUMBER'],
			'ARTNUMBER_PROP' => $arParams['ARTNUMBER_PROP'],
			'OFFER_ARTNUMBER_PROP' => $arParams['OFFER_ARTNUMBER_PROP'],
			'OFFER_TREE_DROPDOWN_PROPS' => $arParams['OFFER_TREE_DROPDOWN_PROPS'],
			'RS_LAZY_IMAGES_USE' => $arParams['RS_LAZY_IMAGES_USE'],
			
			
			'RS_LIST_SECTION' => 'l_section',
			'RS_LIST_SECTION_SHOW_TITLE' => 'Y',
			'RS_LIST_SECTION_TITLE' => ($arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_GIFTS_MAIN_BLOCK_TITLE_DEFAULT')),

		)
		+ array(
			'OFFER_ID' => empty($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
				? $arResult['ID']
				: $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'],
			'SECTION_ID' => $arResult['SECTION']['ID'],
			'ELEMENT_ID' => $arResult['ID'],

			'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
/*
	?>
</div>
<?
*/