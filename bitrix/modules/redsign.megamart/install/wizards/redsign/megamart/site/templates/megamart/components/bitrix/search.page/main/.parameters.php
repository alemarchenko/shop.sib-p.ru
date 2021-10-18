<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arCurrentValues */


use \Bitrix\Iblock;
use \Bitrix\Main\Loader;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Web\Json;
use \Bitrix\Currency;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;
use \Redsign\MegaMart\ElementListUtils;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('iblock'))
	return;

$boolCatalog = Loader::includeModule('catalog');
$boolLightBasket = Loader::includeModule('redsign.lightbasket');

CBitrixComponent::includeComponentClass('bitrix:catalog.section');

$usePropertyFeatures = Iblock\Model\PropertyFeature::isEnabledFeatures();

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$iblockFilter = (!empty($arCurrentValues['CATALOG_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['CATALOG_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y'));
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch())
	$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
unset($arr, $rsIBlock, $iblockFilter);

$defaultValue = array('-' => GetMessage('CP_BC_TPL_PROP_EMPTY'));

$documentRoot = Loader::getDocumentRoot();

$arTemplateParameters['CATALOG_IBLOCK_TYPE'] = array(
	"NAME" => GetMessage('RS_MM_CATALOG_IBLOCK_TYPE'),
	"TYPE" => "LIST",
	"VALUES" => $arIBlockType,
	"REFRESH" => "Y",
);

$arTemplateParameters['CATALOG_IBLOCK_ID'] = array(
	'NAME' => Loc::getMessage('RS_MM_CATALOG_IBLOCK_ID'),
	'TYPE' => 'LIST',
	'ADDITIONAL_VALUES' => 'Y',
	'VALUES' => $arIBlock,
	'DEFAULT' => '',
	'REFRESH' => 'Y',
);

if (intval($arCurrentValues['CATALOG_IBLOCK_ID']) > 0)
{
	$arAllCatalogPropList = array();
	$arCatalogListPropList = array();
	$arCatalogHighloadPropList = array();
	$arCatalogFilePropList = $defaultValue;
	$arCatalogLinkPropList = array();

	$arCatalogProperty = array();
	$arCatalogProperty_N = array();
	$arCatalogProperty_X = array();
	$arCatalogProperty_F = array();

	$arCSTemplates = $arCETemplates = $arRCSTemplates = array();

	if (Loader::includeModule('redsign.megamart'))
	{
		$arCSTemplates = ParametersUtils::getComponentTemplateList('bitrix:catalog.section');
		$arRCSTemplates = ParametersUtils::getComponentTemplateList('redsign:catalog.sorter');
	}

	$addToBasketActions = array(
		'BUY' => GetMessage('ADD_TO_BASKET_ACTION_BUY'),
		'ADD' => GetMessage('ADD_TO_BASKET_ACTION_ADD'),
		'ASK' => GetMessage('RS_MM_ASK_QUESTION'),
	);

	$iblockExists = (!empty($arCurrentValues['CATALOG_IBLOCK_ID']) && (int) $arCurrentValues['CATALOG_IBLOCK_ID'] > 0);
	$boolIsCatalog = false;
	$arSKU = false;
	$boolSKU = false;

	if ($boolCatalog && $iblockExists)
	{
		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['CATALOG_IBLOCK_ID']);
		$boolSKU = !empty($arSKU) && is_array($arSKU);

		$arCatalog = CCatalog::GetByIDExt($arCurrentValues['CATALOG_IBLOCK_ID']);
		if (false !== $arCatalog)
		{
			$boolIsCatalog = true;
		}
	}

	$rsProps = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arCurrentValues['CATALOG_IBLOCK_ID'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsProps->Fetch())
	{
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE'])
		{
			$arProp['CODE'] = $arProp['ID'];
		}

		$arAllCatalogPropList[$arProp['CODE']] = $strPropName;

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE)
		{
			$arCatalogFilePropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
		{
			$arCatalogListPropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
		{
			$arCatalogHighloadPropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_NUMBER)
		{
			$arCatalogNumPropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT)
		{
			$arCatalogLinkPropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] != Iblock\PropertyTable::TYPE_FILE)
		{
			if ($arProp['MULTIPLE'] == 'Y')
				$arCatalogProperty_X[$arProp['CODE']] = $strPropName;
			elseif ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
				$arCatalogProperty_X[$arProp['CODE']] = $strPropName;
			elseif ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT && (int) $property['LINK_IBLOCK_ID'] > 0)
				$arCatalogProperty_X[$arProp['CODE']] = $strPropName;
		}
	}

	$arSort = CIBlockParameters::GetElementSortFields(
		array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
		array('KEY_LOWERCASE' => 'Y')
	);

	$arPrice = array();
	if ($boolIsCatalog) 
	{
		$arSort = array_merge($arSort, CCatalogIBlockParameters::GetCatalogSortFields());
		$arPrice = CCatalogIBlockParameters::getPriceTypesList();
	}
	else
	{
		$arPrice = $arCatalogNumPropList;
	}

	$arAscDesc = array(
		"asc" => GetMessage("IBLOCK_SORT_ASC"),
		"desc" => GetMessage("IBLOCK_SORT_DESC"),
	);

	$arTemplateParameters["PRICE_CODE"] = array(
		"PARENT" => "PRICES",
		"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arPrice,
	);

	if ($boolIsCatalog)
	{
		$arTemplateParameters["USE_PRICE_COUNT"] = array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_USE_PRICE_COUNT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		);
		$arTemplateParameters["SHOW_PRICE_COUNT"] = array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_SHOW_PRICE_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "1"
		);
		$arTemplateParameters["PRICE_VAT_INCLUDE"] = array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_VAT_INCLUDE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		);
		$arTemplateParameters["PRICE_VAT_SHOW_VALUE"] = array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_VAT_SHOW_VALUE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		);
	}
	$arTemplateParameters["BASKET_URL"] = array(
		"PARENT" => "BASKET",
		"NAME" => GetMessage("IBLOCK_BASKET_URL"),
		"TYPE" => "STRING",
		"DEFAULT" => "/personal/cart/",
	);
	$arTemplateParameters["ACTION_VARIABLE"] = array(
		"PARENT" => "ACTION_SETTINGS",
		"NAME"		=> GetMessage("IBLOCK_ACTION_VARIABLE"),
		"TYPE"		=> "STRING",
		"DEFAULT"	=> "action"
	);
	$arTemplateParameters["PRODUCT_ID_VARIABLE"] = array(
		"PARENT" => "ACTION_SETTINGS",
		"NAME"		=> GetMessage("IBLOCK_PRODUCT_ID_VARIABLE"),
		"TYPE"		=> "STRING",
		"DEFAULT"	=> "id"
	);
	$arTemplateParameters["USE_PRODUCT_QUANTITY"] = array(
		"PARENT" => "BASKET",
		"NAME" => GetMessage("CP_BC_USE_PRODUCT_QUANTITY"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	);
	$arTemplateParameters["PRODUCT_QUANTITY_VARIABLE"] = array(
		"PARENT" => "BASKET",
		"NAME" => GetMessage("CP_BC_PRODUCT_QUANTITY_VARIABLE"),
		"TYPE" => "STRING",
		"DEFAULT" => "quantity",
		"HIDDEN" => (isset($arCurrentValues['USE_PRODUCT_QUANTITY']) && $arCurrentValues['USE_PRODUCT_QUANTITY'] == 'Y' ? 'N' : 'Y')
	);
	$arTemplateParameters["USE_COMPARE"] = array(
		"PARENT" => "COMPARE_SETTINGS",
		"NAME" => GetMessage("T_IBLOCK_DESC_USE_COMPARE_EXT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	);
	$arTemplateParameters["SHOW_DEACTIVATED"] = array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage('CP_BC_SHOW_DEACTIVATED'),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N"
	);

	if ($arCurrentValues["USE_COMPARE"] == "Y")
	{
		$arTemplateParameters["COMPARE_NAME"] = array(
			"PARENT" => "COMPARE_SETTINGS",
			"NAME" => GetMessage("IBLOCK_COMPARE_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "CATALOG_COMPARE_LIST"
		);
	}

	if ($boolIsCatalog)
	{
		$arTemplateParameters["ADD_PROPERTIES_TO_BASKET"] = array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BC_ADD_PROPERTIES_TO_BASKET"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y"
		);
		$arTemplateParameters["PRODUCT_PROPS_VARIABLE"] = array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BC_PRODUCT_PROPS_VARIABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "prop",
			"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
		);
		$arTemplateParameters["PARTIAL_PRODUCT_PROPERTIES"] = array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BC_PARTIAL_PRODUCT_PROPERTIES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
		);

		if (!$usePropertyFeatures) 
		{
			$arTemplateParameters["PRODUCT_PROPERTIES"] = array(
				"PARENT" => "BASKET",
				"NAME" => GetMessage("CP_BC_PRODUCT_PROPERTIES"),
				"TYPE" => "LIST",
				"MULTIPLE" => "Y",
				"VALUES" => $arCatalogProperty_X,
				"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
			);
		}

		$arTemplateParameters['HIDE_NOT_AVAILABLE'] = array(
			'PARENT' => 'DATA_SOURCE',
			'NAME' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE'),
			'TYPE' => 'LIST',
			'DEFAULT' => 'N',
			'VALUES' => array(
				'Y' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_HIDE'),
				'L' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_LAST'),
				'N' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_SHOW')
			),
			'ADDITIONAL_VALUES' => 'N'
		);
		$arTemplateParameters['HIDE_NOT_AVAILABLE_OFFERS'] = array(
			'PARENT' => 'DATA_SOURCE',
			'NAME' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_OFFERS'),
			'TYPE' => 'LIST',
			'DEFAULT' => 'N',
			'VALUES' => array(
				'Y' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_OFFERS_HIDE'),
				'L' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_OFFERS_SUBSCRIBE'),
				'N' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_OFFERS_SHOW')
			)
		);
		$arTemplateParameters['CONVERT_CURRENCY'] = array(
			'PARENT' => 'PRICES',
			'NAME' => GetMessage('CP_BC_CONVERT_CURRENCY'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y',
		);

		if (isset($arCurrentValues['CONVERT_CURRENCY']) && $arCurrentValues['CONVERT_CURRENCY'] == 'Y')
		 {
			$arTemplateParameters['CURRENCY_ID'] = array(
				'PARENT' => 'PRICES',
				'NAME' => GetMessage('CP_BC_CURRENCY_ID'),
				'TYPE' => 'LIST',
				'VALUES' => Currency\CurrencyManager::getCurrencyList(),
				'DEFAULT' => Currency\CurrencyManager::getBaseCurrency(),
				"ADDITIONAL_VALUES" => "Y",
			);
		}
	}

	$showedProperties = [];
	if ($usePropertyFeatures)
	{
		if ($iblockExists)
		{
			$showedProperties = Iblock\Model\PropertyFeature::getListPageShowPropertyCodes(
				$arCurrentValues['CATALOG_IBLOCK_ID'],
				['CODE' => 'Y']
			);
			if ($showedProperties === null)
				$showedProperties = [];
		}
	}
	else
	{
		$arTemplateParameters['CATALOG_PROPERTY_CODE'] = array(
			"PARENT" => "LIST_SETTINGS",
			"NAME" => GetMessage("IBLOCK_PROPERTY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			'REFRESH' => isset($arTemplateParameters['CATALOG_PROPERTY_CODE_MOBILE']) ? 'Y' : 'N',
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arAllCatalogPropList,
			'DEFAULT' => '',
		);

		if (!empty($arCurrentValues['CATALOG_PROPERTY_CODE']) && is_array($arCurrentValues['CATALOG_PROPERTY_CODE']))
		{
			$showedProperties = $arCurrentValues['CATALOG_PROPERTY_CODE'];
		}
	}
	if (!empty($showedProperties))
	{
		$selected = array();

		foreach ($showedProperties as $code)
		{
			if (isset($arAllCatalogPropList[$code]))
			{
				$selected[$code] = $arAllCatalogPropList[$code];
			}
		}

		$arTemplateParameters['CATALOG_PROPERTY_CODE_MOBILE'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_PROPERTY_CODE_MOBILE'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'VALUES' => $selected
		);
	}
	unset($showedProperties);

	$arListProductBlocks = array(
		// 'price' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_PRICE'),
		// 'quantity' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_QUANTITY'),
		// 'buttons' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_BUTTONS'),
		'props' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_PROPS'),
		// 'compare' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_COMPARE'),
		'preview' => GetMessage('RS_MM_PRODUCT_BLOCK_PREVIEW'),
	);

	// if ($arCurrentValues['USE_VOTE_RATING'] == 'Y')
	// {
	// $arListProductBlocks['rate'] = GetMessage('RS_MM_PRODUCT_BLOCK_RATE');
	// }

	if ($boolIsCatalog)
	{
		$arListProductBlocks['sku'] = GetMessage('CP_BC_TPL_PRODUCT_BLOCK_SKU');
		// $arListProductBlocks['quantityLimit'] = GetMessage('CP_BC_TPL_PRODUCT_BLOCK_QUANTITY_LIMIT');
	}

	$arTemplateParameters['CATALOG_PRODUCT_BLOCKS_ORDER'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_PRODUCT_BLOCKS_ORDER'),
		'TYPE' => 'CUSTOM',
		'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'dragdrop_order'),
		'JS_EVENT' => 'initDraggableOrderControl',
		'JS_DATA' => Json::encode($arListProductBlocks),
		'DEFAULT' => 'preview,props,sku'
	);

	$lineElementCount = (int) $arCurrentValues['CATALOG_LINE_ELEMENT_COUNT'] ?: 3;
	$pageElementCount = (int) $arCurrentValues['CATALOG_PAGE_ELEMENT_COUNT'] ?: 30;

	$arTemplateParameters['LIST_PRODUCT_ROW_VARIANTS'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_PRODUCT_ROW_VARIANTS'),
		'TYPE' => 'CUSTOM',
		'BIG_DATA' => 'N',
		'COUNT_PARAM_NAME' => 'PAGE_ELEMENT_COUNT',
		'JS_FILE' => ParametersUtils::getSettingsScript('dragdrop_add'),
		'JS_EVENT' => 'initDraggableAddControl',
		'JS_MESSAGES' => Json::encode(array(
			'variant' => GetMessage('CP_BC_TPL_SETTINGS_VARIANT'),
			'delete' => GetMessage('CP_BC_TPL_SETTINGS_DELETE'),
			'quantity' => GetMessage('CP_BC_TPL_SETTINGS_QUANTITY'),
			'quantityBigData' => GetMessage('CP_BC_TPL_SETTINGS_QUANTITY_BIG_DATA')
		)),
		'JS_DATA' => Json::encode(ElementListUtils::getTemplateVariantsMap()),
		'DEFAULT' => Json::encode(ElementListUtils::predictRowVariants($lineElementCount, $pageElementCount))
	);

	$arTemplateParameters['LIST_ENLARGE_PRODUCT'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_ENLARGE_PRODUCT'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'Y',
		'DEFAULT' => 'N',
		'VALUES' => array(
			'STRICT' => GetMessage('CP_BC_TPL_ENLARGE_PRODUCT_STRICT'),
			'PROP' => GetMessage('CP_BC_TPL_ENLARGE_PRODUCT_PROP')
		)
	);
/*
	if (isset($arCurrentValues['LIST_ENLARGE_PRODUCT']) && $arCurrentValues['LIST_ENLARGE_PRODUCT'] === 'PROP')
	{
		$arTemplateParameters['LIST_ENLARGE_PROP'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_ENLARGE_PROP'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $defaultValue + $arCatalogListPropList
		);
	}

	$arTemplateParameters['LIST_SHOW_SLIDER'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_SHOW_SLIDER'),
		'TYPE' => 'CHECKBOX',
		'MULTIPLE' => 'N',
		'REFRESH' => 'Y',
		'DEFAULT' => 'Y'
	);

	if (!isset($arCurrentValues['LIST_SHOW_SLIDER']) || $arCurrentValues['LIST_SHOW_SLIDER'] === 'Y')
	{
		$arTemplateParameters['SLIDER_SLIDE_COUNT'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('RS_MM_SLIDER_SLIDE_COUNT'),
			'TYPE' => 'TEXT',
			'MULTIPLE' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '5'
		);
		$arTemplateParameters['LIST_SLIDER_INTERVAL'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_SLIDER_INTERVAL'),
			'TYPE' => 'TEXT',
			'MULTIPLE' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '3000'
		);
		$arTemplateParameters['LIST_SLIDER_PROGRESS'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_SLIDER_PROGRESS'),
			'TYPE' => 'CHECKBOX',
			'MULTIPLE' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => 'N'
		);
	}
*/
	$arTemplateParameters['CATALOG_ADD_PICT_PROP'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_ADD_PICT_PROP'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'N',
		'DEFAULT' => '-',
		'VALUES' => $arCatalogFilePropList
	);
	$arTemplateParameters['CATALOG_LABEL_PROP'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_LABEL_PROP'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'Y',
		'VALUES' => $arCatalogListPropList
	);

	if (!empty($arCurrentValues['CATALOG_LABEL_PROP']))
	{
		if (!is_array($arCurrentValues['CATALOG_LABEL_PROP']))
		{
			$arCurrentValues['CATALOG_LABEL_PROP'] = array($arCurrentValues['CATALOG_LABEL_PROP']);
		}

		$selected = array();
		foreach ($arCurrentValues['CATALOG_LABEL_PROP'] as $name)
		{
			if (isset($arCatalogListPropList[$name]))
			{
				$selected[$name] = $arCatalogListPropList[$name];
			}
		}

		$arTemplateParameters['CATALOG_LABEL_PROP_MOBILE'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_LABEL_PROP_MOBILE'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'VALUES' => $selected
		);
		unset($selected);
/*
		$arTemplateParameters['CATALOG_LABEL_PROP_POSITION'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_LABEL_PROP_POSITION'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'position'),
			'JS_EVENT' => 'initPositionControl',
			'JS_DATA' => Json::encode(
				array(
					'positions' => array(
						'top-left', 'top-center', 'top-right',
						'middle-left', 'middle-center', 'middle-right',
						'bottom-left', 'bottom-center', 'bottom-right'
					),
					'className' => ''
				)
			),
			'DEFAULT' => 'top-left'
		);
*/
	}

	if ($boolSKU)
	{
		$arTemplateParameters['PRODUCT_DISPLAY_MODE'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_PRODUCT_DISPLAY_MODE'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'Y',
			'DEFAULT' => 'N',
			'VALUES' => array(
				'N' => GetMessage('CP_BC_TPL_DML_SIMPLE'),
				'Y' => GetMessage('CP_BC_TPL_DML_EXT')
			)
		);
		$arAllOfferPropList = array();
		$arFileOfferPropList = array(
			'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
		);
		$arTreeOfferPropList = array(
			'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
		);
		$arProperty_OffersWithoutFile = array(
			'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
		);
		$rsProps = CIBlockProperty::GetList(
			array('SORT' => 'ASC', 'ID' => 'ASC'),
			array('IBLOCK_ID' => $arSKU['IBLOCK_ID'], 'ACTIVE' => 'Y')
		);
		while ($arProp = $rsProps->Fetch())
		{
			if ($arProp['ID'] == $arSKU['SKU_PROPERTY_ID'])
				continue;
			$arProp['USER_TYPE'] = (string)$arProp['USER_TYPE'];
			$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
			if ('' == $arProp['CODE'])
				$arProp['CODE'] = $arProp['ID'];
			$arAllOfferPropList[$arProp['CODE']] = $strPropName;
			if ('F' == $arProp['PROPERTY_TYPE'])
				$arFileOfferPropList[$arProp['CODE']] = $strPropName;
			if ($arProp['PROPERTY_TYPE'] != Iblock\PropertyTable::TYPE_FILE)
				$arProperty_OffersWithoutFile[$arProp['CODE']] = $strPropName;
			if ('N' != $arProp['MULTIPLE'])
				continue;
			if (
				'L' == $arProp['PROPERTY_TYPE']
				|| 'E' == $arProp['PROPERTY_TYPE']
				|| ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
			)
				$arTreeOfferPropList[$arProp['CODE']] = $strPropName;
		}

		if (!$usePropertyFeatures) 
		{
			if (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && 'Y' == $arCurrentValues['ADD_PROPERTIES_TO_BASKET'])
			{
				$arTemplateParameters['OFFERS_CART_PROPERTIES'] = array(
					"PARENT" => "BASKET",
					"NAME" => GetMessage("CP_BC_OFFERS_CART_PROPERTIES"),
					"TYPE" => "LIST",
					"MULTIPLE" => "Y",
					"VALUES" => $arProperty_OffersWithoutFile,
				);
			}
		}
		$arTemplateParameters['OFFERS_SORT_FIELD'] = array(
			"PARENT" => "OFFERS_SETTINGS",
			"NAME" => GetMessage("CP_BC_OFFERS_SORT_FIELD"),
			"TYPE" => "LIST",
			"VALUES" => $arSort,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "sort",
		);
		$arTemplateParameters['OFFERS_SORT_ORDER'] = array(
			"PARENT" => "OFFERS_SETTINGS",
			"NAME" => GetMessage("CP_BC_OFFERS_SORT_ORDER"),
			"TYPE" => "LIST",
			"VALUES" => $arAscDesc,
			"DEFAULT" => "asc",
			"ADDITIONAL_VALUES" => "Y",
		);
		$arTemplateParameters['OFFERS_SORT_FIELD2'] = array(
			"PARENT" => "OFFERS_SETTINGS",
			"NAME" => GetMessage("CP_BC_OFFERS_SORT_FIELD2"),
			"TYPE" => "LIST",
			"VALUES" => $arSort,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "id",
		);
		$arTemplateParameters['OFFERS_SORT_ORDER2'] = array(
			"PARENT" => "OFFERS_SETTINGS",
			"NAME" => GetMessage("CP_BC_OFFERS_SORT_ORDER2"),
			"TYPE" => "LIST",
			"VALUES" => $arAscDesc,
			"DEFAULT" => "desc",
			"ADDITIONAL_VALUES" => "Y",
		);

		$arTemplateParameters["LIST_OFFERS_FIELD_CODE"] = CIBlockParameters::GetFieldCode(GetMessage("CP_BC_LIST_OFFERS_FIELD_CODE"), "OFFERS_SETTINGS");
		if (!$usePropertyFeatures)
		{
			$arTemplateParameters["LIST_OFFERS_PROPERTY_CODE"] = array(
				"PARENT" => "OFFERS_SETTINGS",
				"NAME" => GetMessage("CP_BC_LIST_OFFERS_PROPERTY_CODE"),
				"TYPE" => "LIST",
				"MULTIPLE" => "Y",
				"VALUES" => $arProperty_Offers,
				"ADDITIONAL_VALUES" => "Y",
			);
		}
		$arTemplateParameters["LIST_OFFERS_LIMIT"] = array(
			"PARENT" => "OFFERS_SETTINGS",
			"NAME" => GetMessage("CP_BC_LIST_OFFERS_LIMIT"),
			"TYPE" => "STRING",
			"DEFAULT" => 5,
		);

		$arTemplateParameters['OFFER_ADD_PICT_PROP'] = array(
			'PARENT' => 'OFFERS_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_OFFER_ADD_PICT_PROP'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $arFileOfferPropList
		);

		if (!$usePropertyFeatures)
		{
			$arTemplateParameters['OFFER_TREE_PROPS'] = array(
				'PARENT' => 'OFFERS_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_OFFER_TREE_PROPS'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'Y',
				'ADDITIONAL_VALUES' => 'N',
				'REFRESH' => 'Y',
				'DEFAULT' => '-',
				'VALUES' => $arTreeOfferPropList
			);
		}
		if (!empty($arCurrentValues['OFFER_TREE_PROPS']))
		{
			$selected = array();

			foreach ($arCurrentValues['OFFER_TREE_PROPS'] as $code)
			{
				if (isset($arTreeOfferPropList[$code]))
				{
					$selected[$code] = $arTreeOfferPropList[$code];
				}
			}

			$arTemplateParameters['OFFER_TREE_DROPDOWN_PROPS'] = array(
				'PARENT' => 'OFFERS_SETTINGS',
				'NAME' => getMessage('RS_MM_OFFER_TREE_DROPDOWN_PROPS'),
				'TYPE' => 'LIST',
				'VALUES' => $defaultValue + $selected,
				'MULTIPLE' => 'Y',
				'DEFAULT' => '-',
			);
		}

/*
		if (isset($arCurrentValues['SHOW_ARTNUMBER']) && $arCurrentValues['SHOW_ARTNUMBER'] === 'Y')
		{
			$arTemplateParameters['OFFER_ARTNUMBER_PROP'] = array(
				'PARENT' => 'OFFERS_SETTINGS',
				'NAME' => Loc::getMessage('RS_MM_OFFER_ARTNUMBER_PROP'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'N',
				'ADDITIONAL_VALUES' => 'N',
				'REFRESH' => 'N',
				'DEFAULT' => '-',
				'VALUES' => $defaultValue + $arAllOfferPropList
			);
		}
*/
	}

	$arTemplateParameters['LIST_USE_VOTE_RATING'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_USE_VOTE_RATING'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y'
	);

	if (isset($arCurrentValues['LIST_USE_VOTE_RATING']) && 'Y' == $arCurrentValues['LIST_USE_VOTE_RATING'])
	{
		$arTemplateParameters['DETAIL_VOTE_DISPLAY_AS_RATING'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_VOTE_DISPLAY_AS_RATING'),
			'TYPE' => 'LIST',
			'VALUES' => array(
				'rating' => GetMessage('CP_BC_TPL_DVDAR_RATING'),
				'vote_avg' => GetMessage('CP_BC_TPL_DVDAR_AVERAGE'),
			),
			'DEFAULT' => 'rating'
		);

		$arTemplateParameters['SHOW_RATING'] = array(
			"NAME" => GetMessage("RS_MM_SHOW_RATING"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		);
	}

	if (!$boolIsCatalog && $boolLightBasket)
	{
		$arTemplateParameters['CATALOG_IS_USE_CART'] = array(
			'NAME' => Loc::getMessage('RS_MM_IS_USE_CART'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
			'REFRESH' => 'Y',
		);

		unset($addToBasketActions['BUY']);
	}

	if ($boolIsCatalog)
	{
		$addToBasketActions['BUY1CLICK'] = GetMessage('RS_MM_BUY1CLICK');

/*
	$arTemplateParameters['USE_COMMON_SETTINGS_BASKET_POPUP'] = array(
		'PARENT' => 'BASKET',
		'NAME' => GetMessage('CP_BC_TPL_USE_COMMON_SETTINGS_BASKET_POPUP'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y'
	);
	$useCommonSettingsBasketPopup = (
		isset($arCurrentValues['USE_COMMON_SETTINGS_BASKET_POPUP'])
		&& $arCurrentValues['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y'
	);
	$arTemplateParameters['COMMON_ADD_TO_BASKET_ACTION'] = array(
		'PARENT' => 'BASKET',
		'NAME' => GetMessage('CP_BC_TPL_COMMON_ADD_TO_BASKET_ACTION'),
		'TYPE' => 'LIST',
		'VALUES' => $addToBasketActions,
		'DEFAULT' => 'ADD',
		'REFRESH' => 'N',
		'HIDDEN' => ($useCommonSettingsBasketPopup ? 'N' : 'Y')
	);
	$arTemplateParameters['COMMON_SHOW_CLOSE_POPUP'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_COMMON_SHOW_CLOSE_POPUP'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	);
	$arTemplateParameters['MESS_PRICE_RANGES_TITLE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_MESS_PRICE_RANGES_TITLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_PRICE_RANGES_TITLE_DEFAULT')
	);
	$arTemplateParameters['TOP_ADD_TO_BASKET_ACTION'] = array(
		'PARENT' => 'BASKET',
		'NAME' => GetMessage('CP_BC_TPL_TOP_ADD_TO_BASKET_ACTION'),
		'TYPE' => 'LIST',
		'VALUES' => $addToBasketActions,
		'DEFAULT' => 'ADD',
		'REFRESH' => 'N',
		'HIDDEN' => (!$useCommonSettingsBasketPopup ? 'N' : 'Y')
	);
*/
		$arTemplateParameters['PRODUCT_SUBSCRIPTION'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_PRODUCT_SUBSCRIPTION'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		);
		$arTemplateParameters['SHOW_DISCOUNT_PERCENT'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_SHOW_DISCOUNT_PERCENT'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y',
		);

/*
	if (isset($arCurrentValues['SHOW_DISCOUNT_PERCENT']) && $arCurrentValues['SHOW_DISCOUNT_PERCENT'] === 'Y')
	{
		$arTemplateParameters['DISCOUNT_PERCENT_POSITION'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_DISCOUNT_PERCENT_POSITION'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'position'),
			'JS_EVENT' => 'initPositionControl',
			'JS_DATA' => Json::encode(
				array(
					'positions' => array(
						'top-left', 'top-center', 'top-right',
						'middle-left', 'middle-center', 'middle-right',
						'bottom-left', 'bottom-center', 'bottom-right'
					),
					'className' => 'bx-pos-parameter-block-circle'
				)
			),
			'DEFAULT' => 'bottom-right'
		);
	}
*/
		$arTemplateParameters['SHOW_MAX_QUANTITY'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_SHOW_MAX_QUANTITY'),
			'TYPE' => 'LIST',
			'REFRESH' => 'Y',
			'MULTIPLE' => 'N',
			'VALUES' => array(
				'N' => GetMessage('CP_BC_TPL_SHOW_MAX_QUANTITY_N'),
				'Y' => GetMessage('CP_BC_TPL_SHOW_MAX_QUANTITY_Y'),
				'M' => GetMessage('CP_BC_TPL_SHOW_MAX_QUANTITY_M')
			),
			'DEFAULT' => array('N')
		);

		if (isset($arCurrentValues['SHOW_MAX_QUANTITY']))
		{
			if ($arCurrentValues['SHOW_MAX_QUANTITY'] !== 'N')
			{
				$arTemplateParameters['MESS_SHOW_MAX_QUANTITY'] = array(
					'PARENT' => 'VISUAL',
					'NAME' => GetMessage('CP_BC_TPL_MESS_SHOW_MAX_QUANTITY'),
					'TYPE' => 'STRING',
					'DEFAULT' => '', // GetMessage('CP_BC_TPL_MESS_SHOW_MAX_QUANTITY_DEFAULT')
				);
			}

			if ($arCurrentValues['SHOW_MAX_QUANTITY'] === 'M')
			{
				$arTemplateParameters['RELATIVE_QUANTITY_FACTOR'] = array(
					'PARENT' => 'VISUAL',
					'NAME' => GetMessage('CP_BC_TPL_RELATIVE_QUANTITY_FACTOR'),
					'TYPE' => 'STRING',
					'DEFAULT' => '5'
				);
				$arTemplateParameters['CATALOG_MESS_RELATIVE_QUANTITY_MANY'] = array(
					'PARENT' => 'VISUAL',
					'NAME' => GetMessage('CP_BC_TPL_MESS_RELATIVE_QUANTITY_MANY'),
					'TYPE' => 'STRING',
					'DEFAULT' => GetMessage('CP_BC_TPL_MESS_RELATIVE_QUANTITY_MANY_DEFAULT')
				);
				$arTemplateParameters['CATALOG_MESS_RELATIVE_QUANTITY_FEW'] = array(
					'PARENT' => 'VISUAL',
					'NAME' => GetMessage('CP_BC_TPL_MESS_RELATIVE_QUANTITY_FEW'),
					'TYPE' => 'STRING',
					'DEFAULT' => GetMessage('CP_BC_TPL_MESS_RELATIVE_QUANTITY_FEW_DEFAULT')
				);
			}
		}

		$arTemplateParameters['FILL_ITEM_ALL_PRICES'] = array(
			'PARENT' => 'PRICE',
			'NAME' => Loc::getMessage('RS_MM_FILL_ITEM_ALL_PRICES'),
			"TYPE" => 'CHECKBOX',
			"DEFAULT" => 'N',
		);
/*
	$arTemplateParameters['USE_GIFTS'] = array(
		'PARENT' => 'GIFTS_SETTINGS',
		'NAME' => GetMessage('RS_MM_USE_GIFTS'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		//'REFRESH' => 'Y',
	);
*/
	}
	else
	{
		$arTemplateParameters['CATALOG_PRICE_PROP'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => Loc::getMessage('RS_MM_CATALOG_PRICE_PROP'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arAllCatalogPropList,
			'DEFAULT' => '-',
		);
		$arTemplateParameters['CATALOG_DISCOUNT_PROP'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => Loc::getMessage('RS_MM_CATALOG_DISCOUNT_PROP'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arAllCatalogPropList,
			'DEFAULT' => '-',
		);

		$arTemplateParameters['CATALOG_CURRENCY_PROP'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => Loc::getMessage('RS_MM_CATALOG_CURRENCY_PROP'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arAllCatalogPropList,
			'DEFAULT' => '-',
		);

		$arTemplateParameters['CATALOG_PRICE_DECIMALS'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => Loc::getMessage('RS_MM_CATALOG_PPRICE_DECIMALS'),
			'TYPE' => 'LIST',
			'VALUES' => array(
				'0' => '0',
				'1' => '1',
				'2' => '2',
			),
			'DEFAULT' => '0',
		);
	}

	$arTemplateParameters['SHOW_OLD_PRICE'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_SHOW_OLD_PRICE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	);

	$arTemplateView = array(
		'default' => 'default',
		'popup' => 'popup',
		// 'scroll' => 'scroll',
	);

	$arTemplateParameters['CATALOG_TEMPLATE_VIEW'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('RS_MM_TEMPLATE_VIEW'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'DEFAULT' => key(reset($arTemplateView)),
		'VALUES' => $arTemplateView
	);

/*
$arTemplateParameters['PRODUCT_PREVIEW'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('RS_MM_PRODUCT_PREVIEW'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);
*/
	$arTemplateParameters['CATALOG_ADD_TO_BASKET_ACTION'] = array(
		'PARENT' => 'BASKET',
		'NAME' => GetMessage('CP_BC_TPL_SECTION_ADD_TO_BASKET_ACTION'),
		'TYPE' => 'LIST',
		'VALUES' => array_diff_key($addToBasketActions, array('BUY1CLICK' => '')),
		'DEFAULT' => 'ADD',
		'REFRESH' => 'N',
		//'HIDDEN' => (!$useCommonSettingsBasketPopup ? 'N' : 'Y')
	);
	$arTemplateParameters['SHOW_ARTNUMBER'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('RS_MM_SHOW_ARTNUMBER'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y'
	);

	if (isset($arCurrentValues['SHOW_ARTNUMBER']) && $arCurrentValues['SHOW_ARTNUMBER'] === 'Y')
	{
		$arTemplateParameters['CATALOG_ARTNUMBER_PROP'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => Loc::getMessage('RS_MM_CATALOG_ARTNUMBER_PROP'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arAllCatalogPropList,
			'DEFAULT' => '-',
		);
	}

	$arTemplateParameters['MESS_BTN_BUY'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_BUY'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_BUY_DEFAULT')
	);
	$arTemplateParameters['MESS_BTN_ADD_TO_BASKET'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_ADD_TO_BASKET'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_ADD_TO_BASKET_DEFAULT')
	);
	$arTemplateParameters['MESS_BTN_COMPARE'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_COMPARE'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_COMPARE_DEFAULT')
	);
	$arTemplateParameters['MESS_BTN_DETAIL'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_DETAIL'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_DETAIL_DEFAULT')
	);
	$arTemplateParameters['MESS_NOT_AVAILABLE'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_MESS_NOT_AVAILABLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_NOT_AVAILABLE_DEFAULT')
	);
	$arTemplateParameters['MESS_BTN_SUBSCRIBE'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => Loc::getMessage('CP_BC_TPL_MESS_BTN_SUBSCRIBE'),
		'TYPE' => 'STRING',
		'DEFAULT' => Loc::getMessage('CP_BC_TPL_MESS_BTN_SUBSCRIBE_DEFAULT')
	);
}

$arTemplateParameters['USE_ENHANCED_ECOMMERCE'] = array(
	'PARENT' => 'ANALYTICS_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_USE_ENHANCED_ECOMMERCE'),
	'TYPE' => 'CHECKBOX',
	'REFRESH' => 'Y',
	'DEFAULT' => 'N'
);

if (isset($arCurrentValues['USE_ENHANCED_ECOMMERCE']) && $arCurrentValues['USE_ENHANCED_ECOMMERCE'] === 'Y')
{
	$arTemplateParameters['DATA_LAYER_NAME'] = array(
		'PARENT' => 'ANALYTICS_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_DATA_LAYER_NAME'),
		'TYPE' => 'STRING',
		'DEFAULT' => 'dataLayer'
	);
	$arTemplateParameters['CATALOG_BRAND_PROPERTY'] = array(
		'PARENT' => 'ANALYTICS_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_BRAND_PROPERTY'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'DEFAULT' => '',
		'VALUES' => $defaultValue + $arAllCatalogPropList
	);
}

$arTemplateParameters['LIST_DISPLAY_PREVIEW_TEXT'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => Loc::getMessage('RS_MM_DISPLAY_PREVIEW_TEXT'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y',
);

if ($arCurrentValues['LIST_DISPLAY_PREVIEW_TEXT'] == 'Y')
{
	$arTemplateParameters['PREVIEW_TRUNCATE_LEN'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('RS_MM_PREVIEW_TRUNCATE_LEN'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	);
}


// MEGAMART
if (Loader::includeModule('redsign.megamart'))
{
	$arTemplateParameters['LIST_TEMPLATE'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => Loc::getMessage('RS_MM_LIST_TEMPLATE'),
		'TYPE' => 'LIST',
		'VALUES' => $arCSTemplates,
		'DEFAULT' => 'catalog', // in_array('catalog', $arCSTemplates) ? 'catalog' : $arCSTemplates[0],
		'REFRESH' => 'Y',
	);

	ParametersUtils::addCommonParameters(
		$arTemplateParameters,
		$arCurrentValues,
		array(
			// 'share',
			'lazy-images',
		)
	);
}

if (ModuleManager::isModuleInstalled('redsign.favorite'))
{
	$arTemplateParameters['USE_FAVORITE'] = array(
		'NAME' => getMessage('RS_MM_USE_FAVORITE'),
		'TYPE' => 'CHECKBOX',
		'MULTIPLE' => 'N',
		'VALUE' => 'Y',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	);

	if ($arCurrentValues['USE_FAVORITE'] == 'Y')
	{
		$arTemplateParameters['CATALOG_FAVORITE_COUNT_PROP'] = array(
			'NAME' => getMessage('RS_MM_CATALOG_FAVORITE_COUNT_PROP'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arAllCatalogPropList,
			'DEFAULT' => '-',
		);
/*
		$arTemplateParameters['MESS_BTN_FAVORITE'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => getMessage('RS_MM_MESS_BTN_FAVORITE'),
			'TYPE' => 'STRING',
			'DEFAULT' => getMessage('RS_MM_MESS_BTN_FAVORITE_DEFAULT'),
		);
*/
	}
}
