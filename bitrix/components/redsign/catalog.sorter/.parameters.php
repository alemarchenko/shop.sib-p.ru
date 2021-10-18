<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */
/** @global CUserTypeManager $USER_FIELD_MANAGER */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Iblock;
use Bitrix\Currency;

$catalogIncluded = Loader::includeModule('catalog');

$arSort = CIBlockParameters::GetElementSortFields(
	array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
	array('KEY_LOWERCASE' => 'Y')
);

$arPrice = array();
if ($catalogIncluded)
{
	$arSort = array_merge($arSort, CCatalogIBlockParameters::GetCatalogSortFields());
	if (isset($arSort['CATALOG_AVAILABLE']))
		unset($arSort['CATALOG_AVAILABLE']);
	$arPrice = CCatalogIBlockParameters::getPriceTypesList(true);
}
else
{
	//$arPrice = $arProperty_N;
}

$arComponentParameters = array(
	"GROUPS" => array(
		"ALFA_GR_TEMPLATES" => array(
			"NAME" => GetMessage("ALFA_MSG_GROUP_TEMPLATES"),
		),
		"ALFA_GR_TEMPLATES_SOME" => array(
			"NAME" => GetMessage("ALFA_MSG_GROUP_TEMPLATES_SOME"),
		),
		"ALFA_GR_SORTINGS" => array(
			"NAME" => GetMessage("ALFA_MSG_GROUP_SORTINGS"),
		),
		"ALFA_GR_OUTPUT" => array(
			"NAME" => GetMessage("ALFA_MSG_GROUP_OUTPUT"),
		),
	),
	"PARAMETERS" => array(
		"ALFA_ACTION_PARAM_NAME" => array(
			"NAME" => GetMessage("ALFA_MSG_ACTION_PARAM_NAME"),
			"TYPE" => "STRING",
			"PARENT" => "BASE",
			"DEFAULT" => "alfaction",
		),
		"ALFA_ACTION_PARAM_VALUE" => array(
			"NAME" => GetMessage("ALFA_MSG_ACTION_PARAM_VALUE"),
			"TYPE" => "STRING",
			"PARENT" => "BASE",
			"DEFAULT" => "alfavalue",
		),
		"ALFA_CHOSE_TEMPLATES_SHOW" => array(
			"NAME" => GetMessage("ALFA_MSG_CHOSE_TEMPLATES_SHOW"),
			"TYPE" => "CHECKBOX",
			"VALUE" => "Y",
			"PARENT" => 'ALFA_GR_TEMPLATES',
			"REFRESH" => "Y",
		),
		"ALFA_SORT_BY_SHOW" => array(
			"NAME" => GetMessage("ALFA_MSG_SORT_BY_SHOW"),
			"TYPE" => "CHECKBOX",
			"VALUE" => "Y",
			"PARENT" => 'ALFA_GR_SORTINGS',
			"REFRESH" => "Y",
		),
		"ALFA_SHORT_SORTER" => array(
			"NAME" => GetMessage("ALFA_MSG_SHORT_SORTER"),
			"TYPE" => "CHECKBOX",
			"VALUE" => "Y",
			"PARENT" => 'ALFA_GR_SORTINGS',
			"REFRESH" => "N",
		),
		"ALFA_OUTPUT_OF_SHOW" => array(
			"NAME" => GetMessage("ALFA_MSG_OUTPUT_OF_SHOW"),
			"TYPE" => "CHECKBOX",
			"VALUE" => "Y",
			"PARENT" => 'ALFA_GR_OUTPUT',
			"REFRESH" => "Y",
		),
	)
);

if($arCurrentValues["ALFA_CHOSE_TEMPLATES_SHOW"]=="Y")
{
	$arComponentParameters["PARAMETERS"]["ALFA_CNT_TEMPLATES"] = array(
		"PARENT" => "ALFA_GR_TEMPLATES",
		"NAME" => GetMessage("ALFA_MSG_CNT_TEMPLATES"),
		"TYPE" => "STRING",
		"REFRESH" => "Y",
	);
	for($i=0;$i<$arCurrentValues["ALFA_CNT_TEMPLATES"];$i++)
	{
		$arComponentParameters["PARAMETERS"]["ALFA_CNT_TEMPLATES_".$i] = array(
			"PARENT" => "ALFA_GR_TEMPLATES_SOME",
			"NAME" => GetMessage("ALFA_MSG_CNT_TEMPLATES_SOME_NAME_")." #".($i+1),
			"TYPE" => "STRING",
		);
		$arComponentParameters["PARAMETERS"]["ALFA_CNT_TEMPLATES_NAME_".$i] = array(
			"PARENT" => "ALFA_GR_TEMPLATES_SOME",
			"NAME" => GetMessage("ALFA_MSG_CNT_TEMPLATES_SOME_TMPL_NAME_")." #".($i+1),
			"TYPE" => "STRING",
		);
	}
	$arComponentParameters["PARAMETERS"]["ALFA_DEFAULT_TEMPLATE"] = array(
		"PARENT" => "ALFA_GR_TEMPLATES",
		"NAME" => GetMessage("ALFA_MSG_DEFAULT_TEMPLATE"),
		"TYPE" => "STRING",
		"REFRESH" => "N",
	);
}

if ($arCurrentValues["ALFA_SORT_BY_SHOW"] == "Y")
{
	$arSortByValues = array(
		"sort" => GetMessage('ALFA_MSG_SORT_BY_FIELD_SORT'),
		"name" => GetMessage('ALFA_MSG_SORT_BY_FIELD_NAME'),
	);
	
	if (is_array($arPrice) && count($arPrice) > 0)
	{
		foreach ($arPrice as $id => $price)
		{
			$arSortByValues['catalog_price_scale_'.$id] = $price;
		}
		unset($id, $price);
	}

	$arComponentParameters["PARAMETERS"]["ALFA_SORT_BY_NAME"] = array(
		"PARENT" => "ALFA_GR_SORTINGS",
		"NAME" => GetMessage("ALFA_MSG_SORT_BY"),
		"TYPE" => "LIST",
		"VALUES" => $arSortByValues,
		"MULTIPLE" => "Y",
		"ADDITIONAL_VALUES" => "Y",
		"REFRESH" => "Y",
	);
	
	$selected = array();

	foreach ($arCurrentValues['ALFA_SORT_BY_NAME'] as $code)
	{
		if (strlen($code) > 0)
		{
			if (isset($arSortByValues[$code]))
			{
				$selected[$code] = $arSortByValues[$code];
			}
			else
			{
				$selected[$code] = $code;
			}
		}
	}
	
	$arSortByDefaultValues = array();
	
	foreach ($selected as $code => $name)
	{
		$arSortByDefaultValues[$code.'_asc'] = GetMessage(
			'ALFA_MSG_SORT_BY_FIELD_DIRECTION',
			array(
				'#NAME#' => $name,
				'#DIRECTION#' => GetMessage('ALFA_MSG_SORT_DIRECTION_ASC')
			)
		);
		$arSortByDefaultValues[$code.'_desc'] = GetMessage(
			'ALFA_MSG_SORT_BY_FIELD_DIRECTION',
			array(
				'#NAME#' => $name,
				'#DIRECTION#' => GetMessage('ALFA_MSG_SORT_DIRECTION_DESC')
			)
		);
	}
	
	$arComponentParameters["PARAMETERS"]["ALFA_SORT_BY_DEFAULT"] = array(
		"PARENT" => "ALFA_GR_SORTINGS",
		"NAME" => GetMessage("ALFA_MSG_SORT_BY_DEFAULT"),
		"TYPE" => "LIST",
		"VALUES" => $arSortByDefaultValues,
		"VALUE" => "Y",
		"MULTIPLE" => "N",
	);
	unset($selected);
}

if ($arCurrentValues["ALFA_OUTPUT_OF_SHOW"] == "Y")
{
	$arComponentParameters["PARAMETERS"]["ALFA_OUTPUT_OF"] = array(
		"PARENT" => "ALFA_GR_OUTPUT",
		"NAME" => GetMessage("ALFA_MSG_OUTPUT_OF"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => array(
			"5" => "5",
			"10" => "10",
			"15" => "15",
			"20" => "20",
			"25" => "25",
			"50" => "50",
			"75" => "75",
			"100" => "100",
		),
		"ADDITIONAL_VALUES" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["ALFA_OUTPUT_OF_DEFAULT"] = array(
		"PARENT" => "ALFA_GR_OUTPUT",
		"NAME" => GetMessage("ALFA_MSG_OUTPUT_OF_DEFAULT"),
		"TYPE" => "STRING",
	);
	$arComponentParameters["PARAMETERS"]["ALFA_OUTPUT_OF_SHOW_ALL"] = array(
		"PARENT" => "ALFA_GR_OUTPUT",
		"NAME" => GetMessage("ALFA_MSG_OUTPUT_OF_SHOW_ALL"),
		"TYPE" => "CHECKBOX",
		"VALUE" => "Y",
	);
}
