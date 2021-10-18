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
	if (!isset($arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE']) || $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y')
	{
		?>
		<div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
			<?=($arParams['GIFTS_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_GIFT_BLOCK_TITLE_DEFAULT'))?>
		</div>
		<?
	}
*/
	CBitrixComponent::includeComponentClass('bitrix:sale.products.gift');
	$APPLICATION->IncludeComponent(
		'bitrix:sale.products.gift',
		'catalog',
		array(
			'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
			'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
			'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],

			'PRODUCT_ROW_VARIANTS' => "",
			'PAGE_ELEMENT_COUNT' => 0,
			'DEFERRED_PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
				SaleProductsGiftComponent::predictRowVariants(
					$arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
					$arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT']
				)
			),
			'DEFERRED_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],

			'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
			'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
			'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
			'PRODUCT_DISPLAY_MODE' => 'Y',
			'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],
			'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
			'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
			'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

			'TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],

			'LABEL_PROP_'.$arParams['IBLOCK_ID'] => array(),
			'LABEL_PROP_MOBILE_'.$arParams['IBLOCK_ID'] => array(),
			'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],

			'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
			'MESS_BTN_BUY' => $arParams['~GIFTS_MESS_BTN_BUY'],
			'MESS_BTN_ADD_TO_BASKET' => $arParams['~GIFTS_MESS_BTN_BUY'],
			'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
			'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],

			'SHOW_PRODUCTS_'.$arParams['IBLOCK_ID'] => 'Y',
			'PROPERTY_CODE_'.$arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE'],
			'PROPERTY_CODE_MOBILE'.$arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE_MOBILE'],
			'PROPERTY_CODE_'.$arResult['OFFERS_IBLOCK_ID'] => $arParams['OFFER_TREE_PROPS'],
			'OFFER_TREE_PROPS_'.$arResult['OFFERS_IBLOCK_ID'] => $arParams['OFFER_TREE_PROPS'],
			'CART_PROPERTIES_'.$arResult['OFFERS_IBLOCK_ID'] => $arParams['OFFERS_CART_PROPERTIES'],
			'ADDITIONAL_PICT_PROP_'.$arParams['IBLOCK_ID'] => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),
			'ADDITIONAL_PICT_PROP_'.$arResult['OFFERS_IBLOCK_ID'] => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),

			'HIDE_NOT_AVAILABLE' => 'Y',
			'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
			'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
			'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
			'PRICE_CODE' => $arParams['PRICE_CODE'],
			'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
			'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
			'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
			'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
			'USE_PRODUCT_QUANTITY' => 'N',
			'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
			'POTENTIAL_PRODUCT_TO_BUY' => array(
				'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
				'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
				'PRODUCT_PROVIDER_CLASS' => isset($arResult['~PRODUCT_PROVIDER_CLASS']) ? $arResult['~PRODUCT_PROVIDER_CLASS'] : '\Bitrix\Catalog\Product\CatalogProvider',
				'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
				'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

				'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
					? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID']
					: null,
				'SECTION' => array(
					'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
					'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
					'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
					'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
				),
			),

			'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],

			'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) ? $arParams['DISPLAY_COMPARE'] : ''),
			'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),

			// 'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
			"DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
			"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],

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
			'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
			
			'RS_LIST_SECTION' => 'l_section',
			'RS_LIST_SECTION_SHOW_TITLE' => 'Y',
			'RS_LIST_SECTION_TITLE' => ($arParams['GIFTS_DETAIL_BLOCK_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_GIFT_BLOCK_TITLE_DEFAULT')),
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
/*
	?>
</div>
<?
*/