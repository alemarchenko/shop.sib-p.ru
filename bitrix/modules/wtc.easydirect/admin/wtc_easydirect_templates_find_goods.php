<?
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUser $USER */

use Bitrix\Main\Loader,
	Bitrix\Main,
	Bitrix\Currency,
	Bitrix\Iblock,
	Bitrix\Catalog;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Loader::includeModule("iblock");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/prolog.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/iblock/admin/iblock_element_admin.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/interface/admin_lib.php");
IncludeModuleLangFile(__FILE__);

//GET catalog Iblock Info
Loader::IncludeModule("wtc.easydirect");
if($_REQUEST['find_ibid']) {
    if($_REQUEST['find_ibid']!=$_REQUEST['oldIBID']) {
        echo '<script language="JavaScript">window.top.location.href="'.$APPLICATION->GetCurPage().'?IBLOCK_ID='.$_REQUEST['find_ibid'].'";</script>';
        die();
    }
    $IB_ID=$_REQUEST['find_ibid'];
}
else if($_REQUEST['IBLOCK_ID']>0){
    $IB_ID=$_REQUEST['IBLOCK_ID'];
}
else {
    $IB_ID=CEDirectCatalogItems::getCatalogIBlockIDs();
    $IB_ID=$IB_ID[0];
}

if($IB_ID>0){
    $res = CIBlock::GetByID($IB_ID);
    if($ar_res = $res->GetNext())
    {
        $_REQUEST['IBLOCK_ID']=$ar_res["ID"];
        $_REQUEST["type"]=$ar_res["IBLOCK_TYPE_ID"];
    }
}
else{
	$APPLICATION->SetTitle(GetMessage("IBLOCK_BAD_IBLOCK"));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	CAdminMessage::ShowMessage(
    	Array(
    	"TYPE"=>"ERROR",
    	"MESSAGE" => GetMessage("EASYDIRECT_TEMPLATES_fgoods_ibid_err"),
    	"HTML"=>true
    	)
	);
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}
//------------------------------
$bBizproc = 0;
$bWorkflow = 0;
$bFileman = 0;
$bExcel = isset($_REQUEST["mode"]) && ($_REQUEST["mode"] == "excel");
$dsc_cookie_name = COption::GetOptionString('main', 'cookie_name', 'BITRIX_SM')."_DSC";

$bSearch = false;
$bCurrency = false;
$arCurrencyList = array();
$elementsList = array();

if (isset($_REQUEST['mode']) && ($_REQUEST['mode']=='list' || $_REQUEST['mode']=='frame'))
	CFile::DisableJSFunction(true);

$arIBTYPE = CIBlockType::GetByIDLang($_REQUEST["type"], LANGUAGE_ID);
if($arIBTYPE===false)
	$APPLICATION->AuthForm(GetMessage("IBLOCK_BAD_BLOCK_TYPE_ID"));

$IBLOCK_ID = 0;
if (isset($_REQUEST['IBLOCK_ID']))
	$IBLOCK_ID = (int)$_REQUEST["IBLOCK_ID"];

$arIBlock = CIBlock::GetArrayByID($IBLOCK_ID);
if($arIBlock)
	$bBadBlock = !CIBlockRights::UserHasRightTo($IBLOCK_ID, $IBLOCK_ID, "iblock_admin_display");
else
	$bBadBlock = true;

if($bBadBlock)
{
	$APPLICATION->SetTitle($arIBTYPE["NAME"]);
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	ShowError(GetMessage("IBLOCK_BAD_IBLOCK"));?>
	<a href="<?echo htmlspecialcharsbx("iblock_admin.php?lang=".LANGUAGE_ID."&type=".urlencode($_REQUEST["type"]))?>"><?echo GetMessage("IBLOCK_BACK_TO_ADMIN")?></a>
	<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}

$arIBlock["SITE_ID"] = array();
$rsSites = CIBlock::GetSite($IBLOCK_ID);
while($arSite = $rsSites->Fetch())
	$arIBlock["SITE_ID"][] = $arSite["LID"];

$listImageSize = Main\Config\Option::get('iblock', 'list_image_size');
$minImageSize = array("W" => 1, "H"=>1);
$maxImageSize = array(
	"W" => $listImageSize,
	"H" => $listImageSize,
);
unset($listImageSize);
$useCalendarTime = (string)Main\Config\Option::get('iblock', 'list_full_date_edit') == 'Y';

define("MODULE_ID", "iblock");
define("ENTITY", "CIBlockDocument");
define("DOCUMENT_TYPE", "iblock_".$IBLOCK_ID);

$bCatalog = Loader::includeModule("catalog");
$arCatalog = false;
$boolSKU = false;
$boolSKUFiltrable = false;
$strSKUName = '';
$uniq_id = 0;
$strUseStoreControl = '';
$strSaveWithoutPrice = '';
$boolCatalogRead = false;
$boolCatalogPrice = false;
$boolCatalogPurchasInfo = false;
$catalogPurchasInfoEdit = false;
$boolCatalogSet = false;
$showCatalogWithOffers = false;
$productTypeList = array();
if ($bCatalog)
{
	$strUseStoreControl = COption::GetOptionString('catalog', 'default_use_store_control');
	$strSaveWithoutPrice = COption::GetOptionString('catalog','save_product_without_price');
	$boolCatalogRead = $USER->CanDoOperation('catalog_read');
	$boolCatalogPrice = $USER->CanDoOperation('catalog_price');
	$boolCatalogPurchasInfo = $USER->CanDoOperation('catalog_purchas_info');
	$boolCatalogSet = CBXFeatures::IsFeatureEnabled('CatCompleteSet');
	$arCatalog = CCatalogSKU::GetInfoByIBlock($arIBlock["ID"]);
	if (empty($arCatalog))
	{
		$bCatalog = false;
	}
	else
	{
		if (CCatalogSKU::TYPE_PRODUCT == $arCatalog['CATALOG_TYPE'] || CCatalogSKU::TYPE_FULL == $arCatalog['CATALOG_TYPE'])
		{
			if (CIBlockRights::UserHasRightTo($arCatalog['IBLOCK_ID'], $arCatalog['IBLOCK_ID'], "iblock_admin_display"))
			{
				$boolSKU = true;
				$strSKUName = GetMessage('IBEL_A_OFFERS');
			}
		}
		if (!$boolCatalogRead && !$boolCatalogPrice)
			$bCatalog = false;
		$productTypeList = CCatalogAdminTools::getIblockProductTypeList($arIBlock['ID'], true);
	}
	$showCatalogWithOffers = (COption::GetOptionString('catalog', 'show_catalog_tab_with_offers') == 'Y');
	if ($boolCatalogPurchasInfo)
		$catalogPurchasInfoEdit = $boolCatalogPrice && $strUseStoreControl != 'Y';
}

$dbrFProps = CIBlockProperty::GetList(
	array(
		"SORT"=>"ASC",
		"NAME"=>"ASC"
	),
	array(
		"IBLOCK_ID"=>$IBLOCK_ID,
		"CHECK_PERMISSIONS"=>"N",
	)
);

$arFileProps = array();
$arProps = array();
while ($arProp = $dbrFProps->GetNext())
{
	if ($arProp["ACTIVE"] == "Y")
	{
		$arProp["PROPERTY_USER_TYPE"] = ('' != $arProp["USER_TYPE"] ? CIBlockProperty::GetUserType($arProp["USER_TYPE"]) : array());
		$arProps[] = $arProp;
	}

	if ($arProp["PROPERTY_TYPE"] == Iblock\PropertyTable::TYPE_FILE)
		$arFileProps[$arProp["ID"]] = $arProp;
}
unset($arProp, $dbrFProps);

if ($boolSKU)
{
	$dbrFProps = CIBlockProperty::GetList(
		array(
			"SORT" => "ASC",
			"NAME" => "ASC"
		),
		array(
			"IBLOCK_ID" => $arCatalog['IBLOCK_ID'],
			"ACTIVE" => "Y",
			"FILTRABLE" => "Y",
			"CHECK_PERMISSIONS" => "N",
		)
	);

	$arSKUProps = array();
	while ($arProp = $dbrFProps->GetNext())
	{
		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE || $arCatalog['SKU_PROPERTY_ID'] == $arProp['ID'])
			continue;

		$arProp["PROPERTY_USER_TYPE"] = ('' != $arProp["USER_TYPE"] ? CIBlockProperty::GetUserType($arProp["USER_TYPE"]) : array());
		$boolSKUFiltrable = true;
		$arSKUProps[] = $arProp;
	}
	unset($arProp, $dbrFProps);
}

$sTableID = (defined("CATALOG_PRODUCT")? "tbl_ed__product_admin_": "tbl_ed_iblock_element_").md5($_REQUEST["type"].".".$IBLOCK_ID);
$oSort = new CAdminSorting($sTableID, "timestamp_x", "desc");
if (!isset($by))
	$by = 'ID';
if (!isset($order))
	$order = 'asc';
$by = strtoupper($by);
switch ($by)
{
	case 'ID':
		$arOrder = array('ID' => $order);
		break;
	case 'CATALOG_TYPE':
		$arOrder = array('CATALOG_TYPE' => $order, 'CATALOG_BUNDLE' => $order, 'ID' => 'ASC');
		break;
	default:
		$arOrder = array($by => $order, 'ID' => 'ASC');
		break;
}
$lAdmin = new CAdminList($sTableID, $oSort);
$lAdmin->bMultipart = true;

$arFilterFields = array(
    "find_ibid",
	"find_el",
	"find_el_type",
	"find_section_section",
	"find_el_subsections",
	"find_el_id_start",
	"find_el_id_end",
	"find_el_timestamp_from",
	"find_el_timestamp_to",
	"find_el_modified_user_id",
	"find_el_modified_by",
	"find_el_created_from",
	"find_el_created_to",
	"find_el_created_user_id",
	"find_el_created_by",
	"find_el_status_id",
	"find_el_status",
	"find_el_date_active_from_from",
	"find_el_date_active_from_to",
	"find_el_date_active_to_from",
	"find_el_date_active_to_to",
	"find_el_active",
	"find_el_name",
	"find_el_intext",
	"find_el_code",
	"find_el_external_id",
	"find_el_tags",
);

foreach ($arProps as &$arProp)
{
	if ($arProp["FILTRABLE"] != "Y" || $arProp["PROPERTY_TYPE"] == Iblock\PropertyTable::TYPE_FILE)
		continue;
	$arFilterFields[] = "find_el_property_".$arProp["ID"];
}
unset($arProp);

if ($boolSKU && $boolSKUFiltrable)
{
	foreach ($arSKUProps as &$arProp)
		$arFilterFields[] = "find_sub_el_property_".$arProp["ID"];
	unset($arProp);
}

if ($bCatalog)
{
	$arFilterFields[] = "find_el_catalog_type";
	$arFilterFields[] = "find_el_catalog_available";
	if ($boolCatalogSet)
		$arFilterFields[] = "find_el_catalog_bundle";
}

if(isset($_REQUEST["del_filter"]) && $_REQUEST["del_filter"] != "")
	$find_section_section = -1;
elseif(isset($_REQUEST["find_section_section"]))
	$find_section_section = $_REQUEST["find_section_section"];
else
	$find_section_section = -1;

//We have to handle current section in a special way
$section_id = intval($find_section_section);
$lAdmin->InitFilter($arFilterFields);
if(!defined("CATALOG_PRODUCT"))
	$find_section_section = $section_id;
//This is all parameters needed for proper navigation
$sThisSectionUrl = '&type='.urlencode($type).'&lang='.LANGUAGE_ID.'&IBLOCK_ID='.$IBLOCK_ID.'&find_section_section='.intval($find_section_section);

$arFilter = array(
	"IBLOCK_ID" => $IBLOCK_ID,
	"SECTION_ID" => $find_section_section,
	"MODIFIED_USER_ID" => $find_el_modified_user_id,
	"MODIFIED_BY" => $find_el_modified_by,
	"CREATED_USER_ID" => $find_el_created_user_id,
	"ACTIVE" => $find_el_active,
	"CODE" => $find_el_code,
	"EXTERNAL_ID" => $find_el_external_id,
	"?TAGS" => $find_el_tags,
	"?NAME" => ($find_el!='' && $find_el_type == "name"? $find_el: $find_el_name),
	"?SEARCHABLE_CONTENT" => ($find_el!='' && $find_el_type == "desc"? $find_el: $find_el_intext),
	"SHOW_NEW" => "Y",
	"CHECK_PERMISSIONS" => "Y",
	"MIN_PERMISSION" => "R",
);

foreach ($arProps as &$arProp)
{
	if ($arProp["FILTRABLE"] != 'Y' || $arProp["PROPERTY_TYPE"] == Iblock\PropertyTable::TYPE_FILE)
		continue;

	if (!empty($arProp['PROPERTY_USER_TYPE']) && isset($arProp["PROPERTY_USER_TYPE"]["AddFilterFields"]))
	{
		call_user_func_array($arProp["PROPERTY_USER_TYPE"]["AddFilterFields"], array(
			$arProp,
			array("VALUE" => "find_el_property_".$arProp["ID"]),
			&$arFilter,
			&$filtered,
		));
	}
	else
	{
		$value = ${"find_el_property_".$arProp["ID"]};
		if(is_array($value) || strlen($value))
		{
			if($value === "NOT_REF")
				$value = false;
			$arFilter["?PROPERTY_".$arProp["ID"]] = $value;
		}
	}
}
unset($arProp);

$arSubQuery = array();
if ($boolSKU && $boolSKUFiltrable)
{
	$arSubQuery = array("IBLOCK_ID" => $arCatalog['IBLOCK_ID']);
	foreach ($arSKUProps as $arProp)
	{
		if (!empty($arProp["PROPERTY_USER_TYPE"]) && isset($arProp["PROPERTY_USER_TYPE"]["AddFilterFields"]))
		{
			call_user_func_array($arProp["PROPERTY_USER_TYPE"]["AddFilterFields"], array(
				$arProp,
				array("VALUE" => "find_sub_el_property_".$arProp["ID"]),
				&$arSubQuery,
				&$filtered,
			));
		}
		else
		{
			$value = ${"find_sub_el_property_".$arProp["ID"]};
			if(is_array($value) || strlen($value))
			{
				if($value === "NOT_REF")
					$value = false;
				$arSubQuery["?PROPERTY_".$arProp["ID"]] = $value;
			}
		}
	}
}

if ($boolSKU && 1 < sizeof($arSubQuery))
	$arFilter['ID'] = CIBlockElement::SubQuery('PROPERTY_'.$arCatalog['SKU_PROPERTY_ID'], $arSubQuery);

if(IntVal($find_section_section)<0 || strlen($find_section_section)<=0)
{
	unset($arFilter["SECTION_ID"]);
}
elseif($find_el_subsections=="Y")
{
	if($arFilter["SECTION_ID"]==0)
		unset($arFilter["SECTION_ID"]);
	else
		$arFilter["INCLUDE_SUBSECTIONS"] = "Y";
}

if(!empty($find_el_id_start))
	$arFilter[">=ID"] = $find_el_id_start;
if(!empty($find_el_id_end))
	$arFilter["<=ID"] = $find_el_id_end;
if(!empty($find_el_timestamp_from))
	$arFilter["DATE_MODIFY_FROM"] = $find_el_timestamp_from;
if(!empty($find_el_timestamp_to))
	$arFilter["DATE_MODIFY_TO"] = CIBlock::isShortDate($find_el_timestamp_to)? ConvertTimeStamp(AddTime(MakeTimeStamp($find_el_timestamp_to), 1, "D"), "FULL"): $find_el_timestamp_to;
if(!empty($find_el_created_from))
	$arFilter[">=DATE_CREATE"] = $find_el_created_from;
if(!empty($find_el_created_to))
	$arFilter["<=DATE_CREATE"] = CIBlock::isShortDate($find_el_created_to)? ConvertTimeStamp(AddTime(MakeTimeStamp($find_el_created_to), 1, "D"), "FULL"): $find_el_created_to;
if(!empty($find_el_created_by) && strlen($find_el_created_by)>0)
	$arFilter["CREATED_BY"] = $find_el_created_by;
if(!empty($find_el_status_id))
{
	$intPos = strpos($find_el_status_id,'-');
	if (false !== $intPos)
	{
		$arFilter["WF_STATUS"] = substr($find_el_status_id, 0, $intPos);
		$arFilter["WF_LAST_STATUS_ID"] = substr($find_el_status_id, $intPos+1);
	}
	else
	{
		$arFilter["WF_STATUS"] = $find_el_status_id;
	}
}
if(!empty($find_el_status) && strlen($find_el_status)>0)
	$arFilter["WF_STATUS"] = $find_el_status;
if(!empty($find_el_date_active_from_from))
	$arFilter[">=DATE_ACTIVE_FROM"] = $find_el_date_active_from_from;
if(!empty($find_el_date_active_from_to))
	$arFilter["<=DATE_ACTIVE_FROM"] = $find_el_date_active_from_to;
if(!empty($find_el_date_active_to_from))
	$arFilter[">=DATE_ACTIVE_TO"] = $find_el_date_active_to_from;
if(!empty($find_el_date_active_to_to))
	$arFilter["<=DATE_ACTIVE_TO"] = $find_el_date_active_to_to;
if (!empty($find_el_catalog_type))
	$arFilter['CATALOG_TYPE'] = $find_el_catalog_type;
if (!empty($find_el_catalog_available))
	$arFilter['CATALOG_AVAILABLE'] = $find_el_catalog_available;
if (!empty($find_el_catalog_bundle) && $boolCatalogSet)
	$arFilter['CATALOG_BUNDLE'] = $find_el_catalog_bundle;

//==========================================================

if ($arID = $lAdmin->GroupAction()) ///GROUP ACT
{
	if ($_REQUEST['action_target']=='selected')
	{
		$rsData = CIBlockElement::GetList($arOrder, $arFilter, false, false, array('ID'));
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

	if($_REQUEST['action']=='send_to_ed') {
	    $_SESSION["WTCED_IDS_TO_CREATE_ADS"]=$arID;
	    $_SESSION["WTCED_IBID_TO_CREATE_ADS"]=$IBLOCK_ID;
	    $_SESSION["WTCED_IS_SECTION_TO_CREATE_ADS"]="N";
	    echo '
	    <script type="text/javascript">
	       window.top.location.href="/bitrix/admin/wtc_easydirect_templates_create_ads.php?genstart=Y&lang='.LANG.'";
	    </script>
	    ';
	}
	

}

//===========================================================

CJSCore::Init(array('date'));

$arHeader = array();
if ($bCatalog)
{
	$arHeader[] = array(
		"id" => "CATALOG_TYPE",
		"content" => GetMessage("IBEL_CATALOG_TYPE"),
		"title" => GetMessage('IBEL_CATALOG_TYPE_TITLE'),
		"align" => "right",
		"sort" => "CATALOG_TYPE",
		"default" => true,
	);
}

$arHeader[] = array(
	"id" => "NAME",
	"content" => GetMessage("IBLOCK_FIELD_NAME"),
	"title" => "",
	"sort" => "name",
	"default" => true,
);
$arHeader[] = array(
	"id" => "ACTIVE",
	"content" => GetMessage("IBLOCK_FIELD_ACTIVE"),
	"title" => "",
	"sort" => "active",
	"default" => true,
	"align" => "center",
);
$arHeader[] = array(
	"id" => "DATE_ACTIVE_FROM",
	"content" => GetMessage("IBEL_A_ACTFROM"),
	"title" => "",
	"sort" => "date_active_from",
	"default" => false,
);
$arHeader[] = array(
	"id" => "DATE_ACTIVE_TO",
	"content" => GetMessage("IBEL_A_ACTTO"),
	"title" => "",
	"sort" => "date_active_to",
	"default" => false,
);
$arHeader[] = array(
	"id" => "SORT",
	"content" => GetMessage("IBLOCK_FIELD_SORT"),
	"title" => "",
	"sort" => "sort",
	"default" => true,
	"align" => "right",
);
$arHeader[] = array(
	"id" => "TIMESTAMP_X",
	"content" => GetMessage("IBLOCK_FIELD_TIMESTAMP_X"),
	"title" => "",
	"sort" => "timestamp_x",
	"default" => true,
);
$arHeader[] = array(
	"id" => "USER_NAME",
	"content" => GetMessage("IBLOCK_FIELD_USER_NAME"),
	"title" => "",
	"sort" => "modified_by",
	"default" => false,
);
$arHeader[] = array(
	"id" => "DATE_CREATE",
	"content" => GetMessage("IBLOCK_EL_ADMIN_DCREATE"),
	"title" => "",
	"sort" => "created",
	"default" => false,
);
$arHeader[] = array(
	"id" => "CREATED_USER_NAME",
	"content" => GetMessage("IBLOCK_EL_ADMIN_WCREATE2"),
	"title" => "",
	"sort" => "created_by",
	"default" => false,
);
$arHeader[] = array(
	"id" => "CODE",
	"content" => GetMessage("IBEL_A_CODE"),
	"title" => "",
	"sort" => "code",
	"default" => false,
);
$arHeader[] = array(
	"id" => "EXTERNAL_ID",
	"content" => GetMessage("IBEL_A_EXTERNAL_ID"),
	"title" => "",
	"sort" => "external_id",
	"default" => false,
);
$arHeader[] = array(
	"id" => "TAGS",
	"content" => GetMessage("IBEL_A_TAGS"),
	"title" => "",
	"sort" => "tags",
	"default" => false,
);

$arHeader[] = array(
	"id" => "SHOW_COUNTER",
	"content" => GetMessage("IBEL_A_EXTERNAL_SHOWS"),
	"title" => "",
	"sort" => "show_counter",
	"align" => "right",
	"default" => false,
);
$arHeader[] = array(
	"id" => "SHOW_COUNTER_START",
	"content" => GetMessage("IBEL_A_EXTERNAL_SHOW_F"),
	"title" => "",
	"sort" => "show_counter_start",
	"align" => "right",
	"default" => false,
);
$arHeader[] = array(
	"id" => "PREVIEW_PICTURE",
	"content" => GetMessage("IBEL_A_EXTERNAL_PREV_PIC"),
	"title" => "",
	"sort" => "has_preview_picture",
	"align" => "right",
	"default" => false,
);
$arHeader[] = array(
	"id" => "PREVIEW_TEXT",
	"content" => GetMessage("IBEL_A_EXTERNAL_PREV_TEXT"),
	"title" => "",
	"default" => false,
);
$arHeader[] = array(
	"id" => "DETAIL_PICTURE",
	"content" => GetMessage("IBEL_A_EXTERNAL_DET_PIC"),
	"title" => "",
	"sort" => "has_detail_picture",
	"align" => "center",
	"default" => false,
);
$arHeader[] = array(
	"id" => "DETAIL_TEXT",
	"content" => GetMessage("IBEL_A_EXTERNAL_DET_TEXT"),
	"title" => "",
	"default" => false,
);
$arHeader[] = array(
	"id" => "ID",
	"content" => "ID",
	"title" => "",
	"sort" => "id",
	"default" => true,
	"align" => "right",
);

foreach($arProps as $arFProps)
{
	$arHeader[] = array(
		"id" => "PROPERTY_".$arFProps['ID'],
		"content" => $arFProps['NAME'],
		"title" => "",
		"align" => ($arFProps["PROPERTY_TYPE"]=='N'? "right": "left"),
		"sort" => ($arFProps["MULTIPLE"]!='Y'? "PROPERTY_".$arFProps['ID']: ""),
		"default" => false,
	);
}
unset($arFProps);

$arWFStatusAll = Array();
$arWFStatusPerm = Array();

if($bCatalog)
{
	$arHeader[] = array(
		"id" => "CATALOG_AVAILABLE",
		"content" => GetMessage("IBEL_CATALOG_AVAILABLE"),
		"title" => GetMessage("IBEL_CATALOG_AVAILABLE_TITLE_EXT"),
		"align" => "center",
		"sort" => "CATALOG_AVAILABLE",
		"default" => true,
	);
	if ($arCatalog['CATALOG_TYPE'] != CCatalogSKU::TYPE_PRODUCT)
	{
		$arHeader[] = array(
			"id" => "CATALOG_QUANTITY",
			"content" => GetMessage("IBEL_CATALOG_QUANTITY_EXT"),
			"title" => "",
			"align" => "right",
			"sort" => "CATALOG_QUANTITY",
			"default" => false,
		);
		$arHeader[] = array(
			"id" => "CATALOG_QUANTITY_RESERVED",
			"content" => GetMessage("IBEL_CATALOG_QUANTITY_RESERVED"),
			"align" => "right"
		);
		$arHeader[] = array(
			"id" => "CATALOG_MEASURE_RATIO",
			"content" => GetMessage("IBEL_CATALOG_MEASURE_RATIO"),
			"title" => GetMessage('IBEL_CATALOG_MEASURE_RATIO_TITLE'),
			"align" => "right",
			"default" => false,
		);
		$arHeader[] = array(
			"id" => "CATALOG_MEASURE",
			"content" => GetMessage("IBEL_CATALOG_MEASURE"),
			"title" => GetMessage('IBEL_CATALOG_MEASURE_TITLE'),
			"align" => "right",
			"default" => false,
		);
		$arHeader[] = array(
			"id" => "CATALOG_QUANTITY_TRACE",
			"content" => GetMessage("IBEL_CATALOG_QUANTITY_TRACE"),
			"title" => "",
			"align" => "right",
			"default" => false,
		);
		$arHeader[] = array(
			"id" => "CATALOG_WEIGHT",
			"content" => GetMessage("IBEL_CATALOG_WEIGHT"),
			"title" => "",
			"align" => "right",
			"sort" => "CATALOG_WEIGHT",
			"default" => false,
		);
		$arHeader[] = array(
			"id" => "CATALOG_WIDTH",
			"content" => GetMessage("IBEL_CATALOG_WIDTH"),
			"title" => "",
			"align" => "right",
			"default" => false,
		);
		$arHeader[] = array(
			"id" => "CATALOG_LENGTH",
			"content" => GetMessage("IBEL_CATALOG_LENGTH"),
			"title" => "",
			"align" => "right",
			"default" => false,
		);
		$arHeader[] = array(
			"id" => "CATALOG_HEIGHT",
			"content" => GetMessage("IBEL_CATALOG_HEIGHT"),
			"title" => "",
			"align" => "right",
			"default" => false,
		);
		$arHeader[] = array(
			"id" => "CATALOG_VAT_INCLUDED",
			"content" => GetMessage("IBEL_CATALOG_VAT_INCLUDED"),
			"title" => "",
			"align" => "right",
			"default" => false,
		);
		if ($boolCatalogPurchasInfo)
		{
			$arHeader[] = array(
				"id" => "CATALOG_PURCHASING_PRICE",
				"content" => GetMessage("IBEL_CATALOG_PURCHASING_PRICE"),
				"title" => "",
				"align" => "right",
				"sort" => "CATALOG_PURCHASING_PRICE",
				"default" => false,
			);
		}
		if ($strUseStoreControl == "Y")
		{
			$arHeader[] = array(
				"id" => "CATALOG_BAR_CODE",
				"content" => GetMessage("IBEL_CATALOG_BAR_CODE"),
				"title" => "",
				"align" => "right",
				"default" => false,
			);
		}

		$arCatGroup = CCatalogGroup::GetListArray();
		if (!empty($arCatGroup))
		{
			foreach ($arCatGroup as $priceType)
			{
				$arHeader[] = array(
					"id" => "CATALOG_GROUP_".$priceType["ID"],
					"content" => htmlspecialcharsEx(!empty($priceType["NAME_LANG"]) ? $priceType["NAME_LANG"] : $priceType["NAME"]),
					"align" => "right",
					"sort" => "CATALOG_PRICE_".$priceType["ID"],
					"default" => false,
				);
			}
			unset($priceType);
		}

		$arCatExtra = array();
		$db_extras = CExtra::GetList(array("ID" => "ASC"));
		while ($extras = $db_extras->Fetch())
			$arCatExtra[$extras['ID']] = $extras;
		unset($extras, $db_extras);
	}
}


$lAdmin->AddHeaders($arHeader);
$lAdmin->AddVisibleHeaderColumn('ID');

$arSelectedFields = $lAdmin->GetVisibleHeaderColumns();

$arSelectedProps = array();
$selectedPropertyIds = array();
$arSelect = array();
foreach($arProps as $i => $arProperty)
{
	$k = array_search("PROPERTY_".$arProperty['ID'], $arSelectedFields);
	if($k!==false)
	{
		$arSelectedProps[] = $arProperty;
		$selectedPropertyIds[] = $arProperty['ID'];
		if($arProperty["PROPERTY_TYPE"] == "L")
		{
			$arSelect[$arProperty['ID']] = array();
			$rs = CIBlockProperty::GetPropertyEnum($arProperty['ID']);
			while($ar = $rs->GetNext())
				$arSelect[$arProperty['ID']][$ar["ID"]] = $ar["VALUE"];
		}
		elseif($arProperty["PROPERTY_TYPE"] == "G")
		{
			$arSelect[$arProperty['ID']] = array();
			$rs = CIBlockSection::GetTreeList(array("IBLOCK_ID"=>$arProperty["LINK_IBLOCK_ID"]), array("ID", "NAME", "DEPTH_LEVEL"));
			while($ar = $rs->GetNext())
				$arSelect[$arProperty['ID']][$ar["ID"]] = str_repeat(" . ", $ar["DEPTH_LEVEL"]).$ar["NAME"];
		}
		unset($arSelectedFields[$k]);
	}
}

$arSelectedFields[] = "ID";
$arSelectedFields[] = "CREATED_BY";
$arSelectedFields[] = "LANG_DIR";
$arSelectedFields[] = "LID";
$arSelectedFields[] = "WF_PARENT_ELEMENT_ID";
$arSelectedFields[] = "ACTIVE";

if(in_array("LOCKED_USER_NAME", $arSelectedFields))
	$arSelectedFields[] = "WF_LOCKED_BY";
if(in_array("USER_NAME", $arSelectedFields))
	$arSelectedFields[] = "MODIFIED_BY";
if(in_array("PREVIEW_TEXT", $arSelectedFields))
	$arSelectedFields[] = "PREVIEW_TEXT_TYPE";
if(in_array("DETAIL_TEXT", $arSelectedFields))
	$arSelectedFields[] = "DETAIL_TEXT_TYPE";

$arSelectedFields[] = "LOCK_STATUS";
$arSelectedFields[] = "WF_NEW";
$arSelectedFields[] = "WF_STATUS_ID";
$arSelectedFields[] = "DETAIL_PAGE_URL";
$arSelectedFields[] = "SITE_ID";
$arSelectedFields[] = "CODE";
$arSelectedFields[] = "EXTERNAL_ID";

$measureList = array(0 => ' ');
if ($bCatalog)
{
	if (in_array("CATALOG_QUANTITY_TRACE", $arSelectedFields))
		$arSelectedFields[] = "CATALOG_QUANTITY_TRACE_ORIG";
	if (in_array('CATALOG_QUANTITY_RESERVED', $arSelectedFields) || in_array('CATALOG_MEASURE', $arSelectedFields))
	{
		if (!in_array('CATALOG_TYPE', $arSelectedFields))
			$arSelectedFields[] = 'CATALOG_TYPE';
	}
	if (in_array('CATALOG_TYPE', $arSelectedFields) && $boolCatalogSet)
		$arSelectedFields[] = 'CATALOG_BUNDLE';

	$boolPriceInc = false;
	if ($boolCatalogPurchasInfo)
	{
		if (in_array("CATALOG_PURCHASING_PRICE", $arSelectedFields))
		{
			$arSelectedFields[] = "CATALOG_PURCHASING_CURRENCY";
			$boolPriceInc = true;
		}
	}
	if (!empty($arCatGroup) && is_array($arCatGroup))
	{
		foreach($arCatGroup as &$CatalogGroups)
		{
			if (in_array("CATALOG_GROUP_".$CatalogGroups["ID"], $arSelectedFields))
			{
				$arFilter["CATALOG_SHOP_QUANTITY_".$CatalogGroups["ID"]] = 1;
				$boolPriceInc = true;
			}
		}
		unset($CatalogGroups);
	}
	if ($boolPriceInc)
	{
		$bCurrency = Loader::includeModule('currency');
		if ($bCurrency)
			$arCurrencyList = array_keys(Currency\CurrencyManager::getCurrencyList());
	}
	unset($boolPriceInc);

	if (in_array('CATALOG_MEASURE', $arSelectedFields))
	{
		$measureIterator = CCatalogMeasure::getList(array(), array(), false, false, array('ID', 'MEASURE_TITLE', 'SYMBOL_RUS'));
		while($measure = $measureIterator->Fetch())
			$measureList[$measure['ID']] = ($measure['SYMBOL_RUS'] != '' ? $measure['SYMBOL_RUS'] : $measure['MEASURE_TITLE']);
		unset($measure, $measureIterator);
	}
}

$arSelectedFieldsMap = array();
foreach($arSelectedFields as $field)
	$arSelectedFieldsMap[$field] = true;

if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "excel")
{
	$arNavParams = false;
}
else
{
	$arNavParams = array("nPageSize"=>CAdminResult::GetNavSize(
			$sTableID,
			array('nPageSize' => 20, 'sNavID' => $APPLICATION->GetCurPage().'?IBLOCK_ID='.$IBLOCK_ID))
	);
}

$rsData = CIBlockElement::GetList(
	$arOrder,
	$arFilter,
	false,
	$arNavParams,
	$arSelectedFields
);
$rsData->SetTableID($sTableID);

$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(htmlspecialcharsbx($arIBlock["ELEMENTS_NAME"])));
$arRows = array();

$bSearch = Loader::includeModule('search');

function GetElementName($ID)
{
	$ID = (int)$ID;
	if ($ID <= 0)
		return '';
	static $cache = array();
	if(!isset($cache[$ID]))
	{
		$rsElement = CIBlockElement::GetList(array(), array("ID"=>$ID, "SHOW_HISTORY"=>"Y"), false, false, array("ID","IBLOCK_ID","NAME"));
		$cache[$ID] = $rsElement->GetNext();
	}
	return $cache[$ID];
}
function GetIBlockTypeID($IBLOCK_ID)
{
	$IBLOCK_ID = (int)$IBLOCK_ID;
	if ($IBLOCK_ID <= 0)
		return '';
	static $cache = array();
	if(!isset($cache[$IBLOCK_ID]))
	{
		$rsIBlock = CIBlock::GetByID($IBLOCK_ID);
		if(!($cache[$IBLOCK_ID] = $rsIBlock->GetNext()))
			$cache[$IBLOCK_ID] = array("IBLOCK_TYPE_ID"=>"");
	}
	return $cache[$IBLOCK_ID]["IBLOCK_TYPE_ID"];
}

while($arRes = $rsData->NavNext(true, "f_"))
{
	$arRes_orig = $arRes;
	$lockStatus = "";
	if ($bCatalog)
	{
		if (isset($arSelectedFieldsMap['CATALOG_QUANTITY_TRACE']))
		{
			$arRes['CATALOG_QUANTITY_TRACE'] = $arRes['CATALOG_QUANTITY_TRACE_ORIG'];
			$f_CATALOG_QUANTITY_TRACE = $f_CATALOG_QUANTITY_TRACE_ORIG;
		}
		if (isset($arSelectedFieldsMap['CATALOG_TYPE']))
		{
			$arRes['CATALOG_TYPE'] = (int)$arRes['CATALOG_TYPE'];

			if (
				$arRes['CATALOG_TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_SKU
				|| $arRes['CATALOG_TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_SET
			)
			{
				$arRes['CATALOG_QUANTITY_RESERVED'] = '';
			}
			if (
				$arRes['CATALOG_TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_SKU
				&& !$showCatalogWithOffers
			)
			{
				$arRes['CATALOG_QUANTITY'] = '';
				$arRes['CATALOG_QUANTITY_TRACE'] = '';
				$arRes['CATALOG_QUANTITY_TRACE_ORIG'] = '';
				$arRes['CATALOG_CAN_BUY_ZERO'] = '';
				$arRes['CATALOG_CAN_BUY_ZERO_ORIG'] = '';
				$arRes['CATALOG_NEGATIVE_AMOUNT_TRACE'] = '';
				$arRes['CATALOG_NEGATIVE_AMOUNT_TRACE_ORIG'] = '';
				$arRes['CATALOG_PURCHASING_PRICE'] = '';
				$arRes['CATALOG_PURCHASING_CURRENCY'] = '';
			}
		}
		if (isset($arSelectedFieldsMap['CATALOG_MEASURE']))
		{
			$arRes['CATALOG_MEASURE'] = (int)$arRes['CATALOG_MEASURE'];
			if ($arRes['CATALOG_MEASURE'] < 0)
				$arRes['CATALOG_MEASURE'] = 0;
		}
	}

	$arRes['lockStatus'] = $lockStatus;
	$arRes["orig"] = $arRes_orig;
	$arRes["edit_url"] = CIBlock::GetAdminElementEditLink($IBLOCK_ID, $arRes_orig['ID'], array(
		"find_section_section" => $find_section_section,
		'WF' => 'Y',
	));
	$arRows[$f_ID] = $row = $lAdmin->AddRow($f_ID, $arRes, $arRes["edit_url"], GetMessage("IBEL_A_EDIT"));

	$boolEditPrice = false;
	$boolEditPrice = CIBlockElementRights::UserHasRightTo($IBLOCK_ID, $f_ID, "element_edit_price");

	$row->AddViewField("ID", '<a href="'.$arRes["edit_url"].'" title="'.GetMessage("IBEL_A_EDIT_TITLE").'">'.$f_ID.'</a>');

	if(isset($f_LOCKED_USER_NAME) && $f_LOCKED_USER_NAME)
		$row->AddViewField("LOCKED_USER_NAME", '<a href="user_edit.php?lang='.LANGUAGE_ID.'&ID='.$f_WF_LOCKED_BY.'" title="'.GetMessage("IBEL_A_USERINFO").'">'.$f_LOCKED_USER_NAME.'</a>');
	if(isset($f_USER_NAME) && $f_USER_NAME)
		$row->AddViewField("USER_NAME", '<a href="user_edit.php?lang='.LANGUAGE_ID.'&ID='.$f_MODIFIED_BY.'" title="'.GetMessage("IBEL_A_USERINFO").'">'.$f_USER_NAME.'</a>');
	if(isset($f_CREATED_USER_NAME) && $f_CREATED_USER_NAME)
		$row->AddViewField("CREATED_USER_NAME", '<a href="user_edit.php?lang='.LANGUAGE_ID.'&ID='.$f_CREATED_BY.'" title="'.GetMessage("IBEL_A_USERINFO").'">'.$f_CREATED_USER_NAME.'</a>');


	$row->arRes['props'] = array();
	$arProperties = array();
	if (!empty($arSelectedProps))
	{
		$rsProperties = CIBlockElement::GetProperty($IBLOCK_ID, $arRes['ID'], 'id', 'asc', array('ID' => $selectedPropertyIds));
		while($ar = $rsProperties->GetNext())
		{
			if(!array_key_exists($ar["ID"], $arProperties))
				$arProperties[$ar["ID"]] = array();
			$arProperties[$ar["ID"]][$ar["PROPERTY_VALUE_ID"]] = $ar;
		}
		unset($ar);
		unset($rsProperties);
	}

	foreach($arSelectedProps as $aProp)
	{
		$arViewHTML = array();
		$arEditHTML = array();
		$arUserType = (strlen($aProp["USER_TYPE"])>0 ? CIBlockProperty::GetUserType($aProp["USER_TYPE"]) : array());

		$last_property_id = false;
		foreach($arProperties[$aProp["ID"]] as $prop_id => $prop)
		{
			$prop['PROPERTY_VALUE_ID'] = intval($prop['PROPERTY_VALUE_ID']);
			$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].']['.$prop['PROPERTY_VALUE_ID'].'][VALUE]';
			$DESCR_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].']['.$prop['PROPERTY_VALUE_ID'].'][DESCRIPTION]';
			//View part
			if(array_key_exists("GetAdminListViewHTML", $arUserType))
			{
				$arViewHTML[] = call_user_func_array($arUserType["GetAdminListViewHTML"],
					array(
						$prop,
						array(
							"VALUE" => $prop["~VALUE"],
							"DESCRIPTION" => $prop["~DESCRIPTION"]
						),
						array(
							"VALUE" => $VALUE_NAME,
							"DESCRIPTION" => $DESCR_NAME,
							"MODE"=>"iblock_element_admin",
							"FORM_NAME"=>"form_".$sTableID,
						),
					));
			}
			elseif($prop['PROPERTY_TYPE']=='N')
				$arViewHTML[] = $bExcel && isset($_COOKIE[$dsc_cookie_name])? number_format($prop["VALUE"], 4, chr($_COOKIE[$dsc_cookie_name]), ''): $prop["VALUE"];
			elseif($prop['PROPERTY_TYPE']=='S')
				$arViewHTML[] = $prop["VALUE"];
			elseif($prop['PROPERTY_TYPE']=='L')
				$arViewHTML[] = $prop["VALUE_ENUM"];
			elseif($prop['PROPERTY_TYPE']=='F')
			{
				if ($bExcel)
				{
					$arFile = CFile::GetFileArray($prop["VALUE"]);
					if (is_array($arFile))
						$arViewHTML[] = CHTTP::URN2URI($arFile["SRC"]);
					else
						$arViewHTML[] = "";
				}
				else
				{
					$arViewHTML[] = CFileInput::Show('NO_FIELDS['.$prop['PROPERTY_VALUE_ID'].']', $prop["VALUE"], array(
						"IMAGE" => "Y",
						"PATH" => "Y",
						"FILE_SIZE" => "Y",
						"DIMENSIONS" => "Y",
						"IMAGE_POPUP" => "Y",
						"MAX_SIZE" => $maxImageSize,
						"MIN_SIZE" => $minImageSize,
						), array(
							'upload' => false,
							'medialib' => false,
							'file_dialog' => false,
							'cloud' => false,
							'del' => false,
							'description' => false,
						)
					);
				}
			}
			elseif($prop['PROPERTY_TYPE']=='G')
			{
				if(intval($prop["VALUE"])>0)
				{
					$rsSection = CIBlockSection::GetList(
						array(),
						array("ID" => $prop["VALUE"]),
						false,
						array('ID', 'NAME', 'IBLOCK_ID')
					);
					if($arSection = $rsSection->GetNext())
					{
						$arViewHTML[] = $arSection['NAME'].
						' [<a href="'.
						htmlspecialcharsbx(CIBlock::GetAdminSectionEditLink($arSection['IBLOCK_ID'], $arSection['ID'])).
						'" title="'.GetMessage("IBEL_A_SEC_EDIT").'">'.$arSection['ID'].'</a>]';
					}
				}
			}
			elseif($prop['PROPERTY_TYPE']=='E')
			{
				if($t = GetElementName($prop["VALUE"]))
				{
					$arViewHTML[] = $t['NAME'].
					' [<a href="'.htmlspecialcharsbx(CIBlock::GetAdminElementEditLink($t['IBLOCK_ID'], $t['ID'], array(
						"find_section_section" => $find_section_section,
						'WF' => 'Y',
					))).'" title="'.GetMessage("IBEL_A_EL_EDIT").'">'.$t['ID'].'</a>]';
				}
			}
			//Edit Part
			$bUserMultiple = $prop["MULTIPLE"] == "Y" &&  array_key_exists("GetPropertyFieldHtmlMulty", $arUserType);
			if($bUserMultiple)
			{
				if($last_property_id != $prop["ID"])
				{
					$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].']';
					$arEditHTML[] = call_user_func_array($arUserType["GetPropertyFieldHtmlMulty"], array(
						$prop,
						$arProperties[$prop["ID"]],
						array(
							"VALUE" => $VALUE_NAME,
							"DESCRIPTION" => $VALUE_NAME,
							"MODE"=>"iblock_element_admin",
							"FORM_NAME"=>"form_".$sTableID,
						)
					));
				}
			}
			elseif(array_key_exists("GetPropertyFieldHtml", $arUserType))
			{
				$arEditHTML[] = call_user_func_array($arUserType["GetPropertyFieldHtml"],
					array(
						$prop,
						array(
							"VALUE" => $prop["~VALUE"],
							"DESCRIPTION" => $prop["~DESCRIPTION"],
						),
						array(
							"VALUE" => $VALUE_NAME,
							"DESCRIPTION" => $DESCR_NAME,
							"MODE"=>"iblock_element_admin",
							"FORM_NAME"=>"form_".$sTableID,
						),
					));
			}
			elseif($prop['PROPERTY_TYPE']=='N' || $prop['PROPERTY_TYPE']=='S')
			{
				if($prop["ROW_COUNT"] > 1)
					$html = '<textarea name="'.$VALUE_NAME.'" cols="'.$prop["COL_COUNT"].'" rows="'.$prop["ROW_COUNT"].'">'.$prop["VALUE"].'</textarea>';
				else
					$html = '<input type="text" name="'.$VALUE_NAME.'" value="'.$prop["VALUE"].'" size="'.$prop["COL_COUNT"].'">';
				if($prop["WITH_DESCRIPTION"] == "Y")
					$html .= ' <span title="'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC").'">'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC_1").
						'<input type="text" name="'.$DESCR_NAME.'" value="'.$prop["DESCRIPTION"].'" size="18"></span>';
				$arEditHTML[] = $html;
			}
			elseif($prop['PROPERTY_TYPE']=='L' && ($last_property_id!=$prop["ID"]))
			{
				$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][]';
				$arValues = array();
				foreach($arProperties[$prop["ID"]] as $g_prop)
				{
					$g_prop = intval($g_prop["VALUE"]);
					if($g_prop > 0)
						$arValues[$g_prop] = $g_prop;
				}
				if($prop['LIST_TYPE']=='C')
				{
					if($prop['MULTIPLE'] == "Y" || count($arSelect[$prop['ID']]) == 1)
					{
						$html = '<input type="hidden" name="'.$VALUE_NAME.'" value="">';
						foreach($arSelect[$prop['ID']] as $value => $display)
						{
							$html .= '<input type="checkbox" name="'.$VALUE_NAME.'" id="id'.$uniq_id.'" value="'.$value.'"';
							if(array_key_exists($value, $arValues))
								$html .= ' checked';
							$html .= '>&nbsp;<label for="id'.$uniq_id.'">'.$display.'</label><br>';
							$uniq_id++;
						}
					}
					else
					{
						$html = '<input type="radio" name="'.$VALUE_NAME.'" id="id'.$uniq_id.'" value=""';
						if(count($arValues) < 1)
							$html .= ' checked';
						$html .= '>&nbsp;<label for="id'.$uniq_id.'">'.GetMessage("IBLOCK_ELEMENT_EDIT_NOT_SET").'</label><br>';
						$uniq_id++;
						foreach($arSelect[$prop['ID']] as $value => $display)
						{
							$html .= '<input type="radio" name="'.$VALUE_NAME.'" id="id'.$uniq_id.'" value="'.$value.'"';
							if(array_key_exists($value, $arValues))
								$html .= ' checked';
							$html .= '>&nbsp;<label for="id'.$uniq_id.'">'.$display.'</label><br>';
							$uniq_id++;
						}
					}
				}
				else
				{
					$html = '<select name="'.$VALUE_NAME.'" size="'.$prop["MULTIPLE_CNT"].'" '.($prop["MULTIPLE"]=="Y"?"multiple":"").'>';
					$html .= '<option value=""'.(count($arValues) < 1? ' selected': '').'>'.GetMessage("IBLOCK_ELEMENT_EDIT_NOT_SET").'</option>';
					foreach($arSelect[$prop['ID']] as $value => $display)
					{
						$html .= '<option value="'.$value.'"';
						if(array_key_exists($value, $arValues))
							$html .= ' selected';
						$html .= '>'.$display.'</option>'."\n";
					}
					$html .= "</select>\n";
				}
				$arEditHTML[] = $html;
			}
			elseif($prop['PROPERTY_TYPE']=='F' && ($last_property_id != $prop["ID"]))
			{
				if($prop['MULTIPLE'] == "Y")
				{
					$inputName = array();
					foreach($arProperties[$prop["ID"]] as $g_prop)
					{
						$inputName['FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].']['.$g_prop['PROPERTY_VALUE_ID'].'][VALUE]'] = $g_prop["VALUE"];
					}
					if (class_exists('\Bitrix\Main\UI\FileInput', true))
					{
						$arEditHTML[] = \Bitrix\Main\UI\FileInput::createInstance(array(
								"name" => 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][n#IND#]',
								"description" => $prop["WITH_DESCRIPTION"]=="Y",
								"upload" => true,
								"medialib" => false,
								"fileDialog" => false,
								"cloud" => false,
								"delete" => true,
							))->show($inputName);
					}
					else
					{
						$arEditHTML[] = CFileInput::ShowMultiple($inputName, 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][n#IND#]', array(
							"IMAGE" => "Y",
							"PATH" => "Y",
							"FILE_SIZE" => "Y",
							"DIMENSIONS" => "Y",
							"IMAGE_POPUP" => "Y",
							"MAX_SIZE" => $maxImageSize,
							"MIN_SIZE" => $minImageSize,
							), false, array(
								'upload' => true,
								'medialib' => false,
								'file_dialog' => false,
								'cloud' => false,
								'del' => true,
								'description' => $prop["WITH_DESCRIPTION"]=="Y",
							)
						);
					}
				}
				else
				{
					$arEditHTML[] = CFileInput::Show($VALUE_NAME, $prop["VALUE"], array(
						"IMAGE" => "Y",
						"PATH" => "Y",
						"FILE_SIZE" => "Y",
						"DIMENSIONS" => "Y",
						"IMAGE_POPUP" => "Y",
						"MAX_SIZE" => $maxImageSize,
						"MIN_SIZE" => $minImageSize,
						), array(
							'upload' => true,
							'medialib' => false,
							'file_dialog' => false,
							'cloud' => false,
							'del' => true,
							'description' => $prop["WITH_DESCRIPTION"]=="Y",
						)
					);
				}
			}
			elseif(($prop['PROPERTY_TYPE']=='G') && ($last_property_id!=$prop["ID"]))
			{
				$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][]';
				$arValues = array();
				foreach($arProperties[$prop["ID"]] as $g_prop)
				{
					$g_prop = intval($g_prop["VALUE"]);
					if($g_prop > 0)
						$arValues[$g_prop] = $g_prop;
				}
				$html = '<select name="'.$VALUE_NAME.'" size="'.$prop["MULTIPLE_CNT"].'" '.($prop["MULTIPLE"]=="Y"?"multiple":"").'>';
				$html .= '<option value=""'.(count($arValues) < 1? ' selected': '').'>'.GetMessage("IBLOCK_ELEMENT_EDIT_NOT_SET").'</option>';
				foreach($arSelect[$prop['ID']] as $value => $display)
				{
					$html .= '<option value="'.$value.'"';
					if(array_key_exists($value, $arValues))
						$html .= ' selected';
					$html .= '>'.$display.'</option>'."\n";
				}
				$html .= "</select>\n";
				$arEditHTML[] = $html;
			}
			elseif($prop['PROPERTY_TYPE']=='E')
			{
				$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].']['.$prop['PROPERTY_VALUE_ID'].']';
				$fixIBlock = $prop["LINK_IBLOCK_ID"] > 0;
				$windowTableId = 'iblockprop-'.Iblock\PropertyTable::TYPE_ELEMENT.'-'.$prop['ID'].'-'.$prop['LINK_IBLOCK_ID'];
				if($t = GetElementName($prop["VALUE"]))
				{
					$arEditHTML[] = '<input type="text" name="'.$VALUE_NAME.'" id="'.$VALUE_NAME.'" value="'.$prop["VALUE"].'" size="5">'.
					'<input type="button" value="..." onClick="jsUtils.OpenWindow(\'iblock_element_search.php?lang='.LANGUAGE_ID.'&amp;IBLOCK_ID='.$prop["LINK_IBLOCK_ID"].'&amp;n='.urlencode($VALUE_NAME).($fixIBlock ? '&amp;iblockfix=y' : '').'&amp;tableId='.$windowTableId.'\', 900, 700);">'.
					'&nbsp;<span id="sp_'.$VALUE_NAME.'" >'.$t['NAME'].'</span>';
				}
				else
				{
					$arEditHTML[] = '<input type="text" name="'.$VALUE_NAME.'" id="'.$VALUE_NAME.'" value="" size="5">'.
					'<input type="button" value="..." onClick="jsUtils.OpenWindow(\'iblock_element_search.php?lang='.LANGUAGE_ID.'&amp;IBLOCK_ID='.$prop["LINK_IBLOCK_ID"].'&amp;n='.urlencode($VALUE_NAME).($fixIBlock ? '&amp;iblockfix=y' : '').'&amp;tableId='.$windowTableId.'\', 900, 700);">'.
					'&nbsp;<span id="sp_'.$VALUE_NAME.'" ></span>';
				}
				unset($windowTableId);
				unset($fixIBlock);
			}
			$last_property_id = $prop['ID'];
		}
		$table_id = md5($f_ID.':'.$aProp['ID']);
		if($aProp["MULTIPLE"] == "Y")
		{
			$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][n0][VALUE]';
			$DESCR_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][n0][DESCRIPTION]';
			if(array_key_exists("GetPropertyFieldHtmlMulty", $arUserType))
			{
			}
			elseif(array_key_exists("GetPropertyFieldHtml", $arUserType))
			{
				$arEditHTML[] = call_user_func_array($arUserType["GetPropertyFieldHtml"],
					array(
						$prop,
						array(
							"VALUE" => "",
							"DESCRIPTION" => "",
						),
						array(
							"VALUE" => $VALUE_NAME,
							"DESCRIPTION" => $DESCR_NAME,
							"MODE"=>"iblock_element_admin",
							"FORM_NAME"=>"form_".$sTableID,
						),
					));
			}
			elseif($prop['PROPERTY_TYPE']=='N' || $prop['PROPERTY_TYPE']=='S')
			{
				if($prop["ROW_COUNT"] > 1)
					$html = '<textarea name="'.$VALUE_NAME.'" cols="'.$prop["COL_COUNT"].'" rows="'.$prop["ROW_COUNT"].'"></textarea>';
				else
					$html = '<input type="text" name="'.$VALUE_NAME.'" value="" size="'.$prop["COL_COUNT"].'">';
				if($prop["WITH_DESCRIPTION"] == "Y")
					$html .= ' <span title="'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC").'">'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC_1").'<input type="text" name="'.$DESCR_NAME.'" value="" size="18"></span>';
				$arEditHTML[] = $html;
			}
			elseif($prop['PROPERTY_TYPE']=='F')
			{
			}
			elseif($prop['PROPERTY_TYPE']=='E')
			{
				$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][n0]';
				$fixIBlock = $prop["LINK_IBLOCK_ID"] > 0;
				$windowTableId = 'iblockprop-'.Iblock\PropertyTable::TYPE_ELEMENT.'-'.$prop['ID'].'-'.$prop['LINK_IBLOCK_ID'];
				$arEditHTML[] = '<input type="text" name="'.$VALUE_NAME.'" id="'.$VALUE_NAME.'" value="" size="5">'.
					'<input type="button" value="..." onClick="jsUtils.OpenWindow(\'iblock_element_search.php?lang='.LANGUAGE_ID.'&amp;IBLOCK_ID='.$prop["LINK_IBLOCK_ID"].'&amp;n='.urlencode($VALUE_NAME).($fixIBlock ? '&amp;iblockfix=y' : '').'&amp;tableId='.$windowTableId.'\', 900, 700);">'.
					'&nbsp;<span id="sp_'.$VALUE_NAME.'" ></span>';
				unset($windowTableId);
				unset($fixIBlock);
			}

			if(
				$prop["PROPERTY_TYPE"] !== "G"
				&& $prop["PROPERTY_TYPE"] !== "L"
				&& $prop["PROPERTY_TYPE"] !== "F"
				&& !$bUserMultiple
			)
				$arEditHTML[] = '<input type="button" value="'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD").'" onClick="addNewRow(\'tb'.$table_id.'\')">';
		}
		if(!empty($arViewHTML))
		{
			if($prop["PROPERTY_TYPE"] == "F")
				$row->AddViewField("PROPERTY_".$aProp['ID'], implode("", $arViewHTML));
			else
				$row->AddViewField("PROPERTY_".$aProp['ID'], implode(" / ", $arViewHTML));
		}

		if(count($arEditHTML) > 0)
			$row->arRes['props']["PROPERTY_".$aProp['ID']] = array("table_id"=>$table_id, "html"=>$arEditHTML);
	}

	if ($bCatalog)
	{
		if (isset($arCatGroup) && !empty($arCatGroup))
		{
			$row->arRes['price'] = array();
			foreach($arCatGroup as &$CatGroup)
			{
				if (isset($arSelectedFieldsMap["CATALOG_GROUP_".$CatGroup["ID"]]))
				{
					$price = "";
					$sHTML = "";
					$selectCur = "";
					$extraId = (isset($arRes['CATALOG_EXTRA_ID_'.$CatGroup['ID']]) ? (int)$arRes['CATALOG_EXTRA_ID_'.$CatGroup['ID']] : 0);
					if (!isset($arCatExtra[$extraId]))
						$extraId = 0;
					if ($bCurrency)
					{
						$price = htmlspecialcharsEx(CCurrencyLang::CurrencyFormat(
							$arRes["CATALOG_PRICE_".$CatGroup["ID"]],
							$arRes["CATALOG_CURRENCY_".$CatGroup["ID"]],
							true
						));
						if ($extraId > 0)
						{
							$price .= ' <span title="'.
								htmlspecialcharsbx(GetMessage(
									'IBEL_CATALOG_EXTRA_DESCRIPTION',
									array('#VALUE#' => $arCatExtra[$extraId]['NAME'])
								)).
								'">(+'.$arCatExtra[$extraId]['PERCENTAGE'].'%)</span>';
						}
						if ($boolCatalogPrice && $boolEditPrice)
						{
							$selectCur = '<select name="CATALOG_CURRENCY['.$f_ID.']['.$CatGroup["ID"].']" id="CATALOG_CURRENCY['.$f_ID.']['.$CatGroup["ID"].']"';
							if ($CatGroup["BASE"]=="Y")
								$selectCur .= ' onchange="top.ChangeBaseCurrency('.$f_ID.')"';
							elseif ($extraId > 0)
								$selectCur .= ' disabled="disabled" readonly="readonly"';
							$selectCur .= '>';
							foreach ($arCurrencyList as &$currencyCode)
							{
								$selectCur .= '<option value="'.$currencyCode.'"';
								if ($currencyCode == $arRes["CATALOG_CURRENCY_".$CatGroup["ID"]])
									$selectCur .= ' selected';
								$selectCur .= '>'.$currencyCode.'</option>';
							}
							unset($currencyCode);
							$selectCur .= '</select>';
						}
					}
					else
					{
						$price = htmlspecialcharsEx($arRes["CATALOG_PRICE_".$CatGroup["ID"]]." ".$arRes["CATALOG_CURRENCY_".$CatGroup["ID"]]);
					}

					$row->AddViewField("CATALOG_GROUP_".$CatGroup["ID"], $price);

					if ($boolCatalogPrice && $boolEditPrice)
					{
						$sHTML = '<input type="text" size="9" id="CATALOG_PRICE['.$f_ID.']['.$CatGroup["ID"].']" name="CATALOG_PRICE['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_PRICE_".$CatGroup["ID"]].'"';
						if ($CatGroup["BASE"]=="Y")
							$sHTML .= ' onchange="top.ChangeBasePrice('.$f_ID.')"';
						elseif ($extraId > 0)
							$sHTML .= ' disabled readonly';
						$sHTML .= '> '.$selectCur;
						if ($extraId > 0)
							$sHTML .= '<input type="hidden" id="CATALOG_EXTRA['.$f_ID.']['.$CatGroup["ID"].']" name="CATALOG_EXTRA['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_EXTRA_ID_".$CatGroup["ID"]].'">';

						$sHTML .= '<input type="hidden" name="CATALOG_old_PRICE['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_PRICE_".$CatGroup["ID"]].'">';
						$sHTML .= '<input type="hidden" name="CATALOG_old_CURRENCY['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_CURRENCY_".$CatGroup["ID"]].'">';
						$sHTML .= '<input type="hidden" name="CATALOG_PRICE_ID['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_PRICE_ID_".$CatGroup["ID"]].'">';
						$sHTML .= '<input type="hidden" name="CATALOG_QUANTITY_FROM['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_QUANTITY_FROM_".$CatGroup["ID"]].'">';
						$sHTML .= '<input type="hidden" name="CATALOG_QUANTITY_TO['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_QUANTITY_TO_".$CatGroup["ID"]].'">';

						$row->arRes['price']["CATALOG_GROUP_".$CatGroup["ID"]] = $sHTML;
					}
					unset($extraId);
				}
			}
			unset($CatGroup);
		}
		if (isset($arSelectedFieldsMap['CATALOG_MEASURE_RATIO']))
		{
			$row->arRes['CATALOG_MEASURE_RATIO'] = 1;
		}
	}

}

$boolIBlockElementAdd = CIBlockSectionRights::UserHasRightTo($IBLOCK_ID, $find_section_section, "section_element_bind");

$arElementOps = CIBlockElementRights::UserHasRightTo(
	$IBLOCK_ID,
	array_keys($arRows),
	"",
	CIBlockRights::RETURN_OPERATIONS
);
$availQuantityTrace = COption::GetOptionString("catalog", "default_quantity_trace");
$arQuantityTrace = array(
	"D" => GetMessage("IBEL_DEFAULT_VALUE")." (".($availQuantityTrace=='Y' ? GetMessage("IBEL_YES_VALUE") : GetMessage("IBEL_NO_VALUE")).")",
	"Y" => GetMessage("IBEL_YES_VALUE"),
	"N" => GetMessage("IBEL_NO_VALUE"),
);
if ($bCatalog && !empty($arRows))
{
	$arRowKeys = array_keys($arRows);
	if ($strUseStoreControl == "Y" && in_array("CATALOG_BAR_CODE", $arSelectedFields))
	{
		$rsProducts = CCatalogProduct::GetList(
			array(),
			array('@ID' => $arRowKeys),
			false,
			false,
			array('ID', 'BARCODE_MULTI')
		);
		$productsWithBarCode = array();
		while ($product = $rsProducts->Fetch())
		{
			if (isset($arRows[$product["ID"]]))
			{
				if ($product["BARCODE_MULTI"] == "Y")
					$arRows[$product["ID"]]->arRes["CATALOG_BAR_CODE"] = GetMessage("IBEL_CATALOG_BAR_CODE_MULTI");
				else
					$productsWithBarCode[] = $product["ID"];
			}
		}
		if (!empty($productsWithBarCode))
		{
			$rsProducts = CCatalogStoreBarCode::getList(array(), array(
				"PRODUCT_ID" => $productsWithBarCode,
			));
			while ($product = $rsProducts->Fetch())
			{
				if (isset($arRows[$product["PRODUCT_ID"]]))
				{
					$arRows[$product["PRODUCT_ID"]]->arRes["CATALOG_BAR_CODE"] = htmlspecialcharsEx($product["BARCODE"]);
				}
			}
		}
	}

	if (isset($arSelectedFieldsMap['CATALOG_MEASURE_RATIO']))
	{
		$rsRatios = CCatalogMeasureRatio::getList(
			array(),
			array('@PRODUCT_ID' => $arRowKeys),
			false,
			false,
			array('ID', 'PRODUCT_ID', 'RATIO')
		);
		while ($arRatio = $rsRatios->Fetch())
		{
			$arRatio['PRODUCT_ID'] = (int)$arRatio['PRODUCT_ID'];
			if (isset($arRows[$arRatio['PRODUCT_ID']]))
			{
				$arRows[$arRatio['PRODUCT_ID']]->arRes['CATALOG_MEASURE_RATIO'] = $arRatio['RATIO'];
			}
		}
	}
}

foreach($arRows as $f_ID => $row)
{
	/** @var CAdminListRow $row */

	if(array_key_exists("PREVIEW_TEXT", $arSelectedFieldsMap))
		$row->AddViewField("PREVIEW_TEXT", ($row->arRes["PREVIEW_TEXT_TYPE"]=="text" ? htmlspecialcharsEx($row->arRes["PREVIEW_TEXT"]) : HTMLToTxt($row->arRes["PREVIEW_TEXT"])));
	if(array_key_exists("DETAIL_TEXT", $arSelectedFieldsMap))
		$row->AddViewField("DETAIL_TEXT", ($row->arRes["DETAIL_TEXT_TYPE"]=="text" ? htmlspecialcharsEx($row->arRes["DETAIL_TEXT"]) : HTMLToTxt($row->arRes["DETAIL_TEXT"])));

	if(isset($arElementOps[$f_ID]) && isset($arElementOps[$f_ID]["element_edit"]))
	{
		if ($bCatalog)
		{
			if ($showCatalogWithOffers || $row->arRes['CATALOG_TYPE'] != CCatalogProduct::TYPE_SKU)
			{
				if (isset($arElementOps[$f_ID]["element_edit_price"]))
				{
					if (isset($row->arRes['price']) && is_array($row->arRes['price']))
						foreach($row->arRes['price'] as $price_id => $sHTML)
							$row->AddEditField($price_id, $sHTML);
				}
			}
			else
			{
				if(isset($row->arRes['price']) && is_array($row->arRes['price']))
					foreach($row->arRes['price'] as $price_id => $sHTML)
						$row->AddViewField($price_id, ' ');
			}
		}

		$row->AddCheckField("WF_NEW", false);
		$row->AddCheckField("ACTIVE");
		$row->AddInputField("NAME", array('size'=>'35'));
		$row->AddViewField("NAME", '<a href="'.$row->arRes["edit_url"].'" title="'.GetMessage("IBEL_A_EDIT_TITLE").'">'.htmlspecialcharsEx($row->arRes["NAME"]).'</a>');
		$row->AddInputField("SORT", array('size'=>'3'));
		$row->AddInputField("CODE");
		$row->AddInputField("EXTERNAL_ID");
		if ($bSearch)
		{
			$row->AddViewField("TAGS", htmlspecialcharsEx($row->arRes["TAGS"]));
			$row->AddEditField("TAGS", InputTags("FIELDS[".$f_ID."][TAGS]", $row->arRes["TAGS"], $arIBlock["SITE_ID"]));
		}
		else
		{
			$row->AddInputField("TAGS");
		}
		$row->AddCalendarField("DATE_ACTIVE_FROM", array(), $useCalendarTime);
		$row->AddCalendarField("DATE_ACTIVE_TO", array(), $useCalendarTime);

		if(!empty($arWFStatusPerm))
			$row->AddSelectField("WF_STATUS_ID", $arWFStatusPerm);
		if($row->arRes['orig']['WF_NEW']=='Y' || $row->arRes['WF_STATUS_ID']=='1')
			$row->AddViewField("WF_STATUS_ID", htmlspecialcharsEx($arWFStatusAll[$row->arRes['WF_STATUS_ID']]));
		else
			$row->AddViewField("WF_STATUS_ID", '<a href="'.$row->arRes["edit_url"].'" title="'.GetMessage("IBEL_A_ED_TITLE").'">'.htmlspecialcharsEx($arWFStatusAll[$row->arRes['WF_STATUS_ID']]).'</a> / <a href="'.htmlspecialcharsbx(CIBlock::GetAdminElementEditLink($IBLOCK_ID, $row->arRes['orig']['ID'], array(
				"find_section_section" => $find_section_section,
				'view' => (!isset($arElementOps[$f_ID]) || !isset($arElementOps[$f_ID]["element_edit_any_wf_status"])? 'Y': null)
			))).'" title="'.GetMessage("IBEL_A_ED2_TITLE").'">'.htmlspecialcharsEx($arWFStatusAll[$row->arRes['orig']['WF_STATUS_ID']]).'</a>');

		if (array_key_exists("PREVIEW_PICTURE", $arSelectedFieldsMap))
		{
			$row->AddFileField("PREVIEW_PICTURE", array(
				"IMAGE" => "Y",
				"PATH" => "Y",
				"FILE_SIZE" => "Y",
				"DIMENSIONS" => "Y",
				"IMAGE_POPUP" => "Y",
				"MAX_SIZE" => $maxImageSize,
				"MIN_SIZE" => $minImageSize,
				), array(
					'upload' => true,
					'medialib' => false,
					'file_dialog' => false,
					'cloud' => true,
					'del' => true,
					'description' => true,
				)
			);
		}
		if (array_key_exists("DETAIL_PICTURE", $arSelectedFieldsMap))
		{
			$row->AddFileField("DETAIL_PICTURE", array(
				"IMAGE" => "Y",
				"PATH" => "Y",
				"FILE_SIZE" => "Y",
				"DIMENSIONS" => "Y",
				"IMAGE_POPUP" => "Y",
				"MAX_SIZE" => $maxImageSize,
				"MIN_SIZE" => $minImageSize,
				), array(
					'upload' => true,
					'medialib' => false,
					'file_dialog' => false,
					'cloud' => true,
					'del' => true,
					'description' => true,
				)
			);
		}
		if(array_key_exists("PREVIEW_TEXT", $arSelectedFieldsMap))
		{
			$sHTML = '<input type="radio" name="FIELDS['.$f_ID.'][PREVIEW_TEXT_TYPE]" value="text" id="'.$f_ID.'PREVIEWtext"';
			if($row->arRes["PREVIEW_TEXT_TYPE"]!="html")
				$sHTML .= ' checked';
			$sHTML .= '><label for="'.$f_ID.'PREVIEWtext">text</label> /';
			$sHTML .= '<input type="radio" name="FIELDS['.$f_ID.'][PREVIEW_TEXT_TYPE]" value="html" id="'.$f_ID.'PREVIEWhtml"';
			if($row->arRes["PREVIEW_TEXT_TYPE"]=="html")
				$sHTML .= ' checked';
			$sHTML .= '><label for="'.$f_ID.'PREVIEWhtml">html</label><br>';
			$sHTML .= '<textarea rows="10" cols="50" name="FIELDS['.$f_ID.'][PREVIEW_TEXT]">'.htmlspecialcharsbx($row->arRes["PREVIEW_TEXT"]).'</textarea>';
			$row->AddEditField("PREVIEW_TEXT", $sHTML);
		}
		if(array_key_exists("DETAIL_TEXT", $arSelectedFieldsMap))
		{
			$sHTML = '<input type="radio" name="FIELDS['.$f_ID.'][DETAIL_TEXT_TYPE]" value="text" id="'.$f_ID.'DETAILtext"';
			if($row->arRes["DETAIL_TEXT_TYPE"]!="html")
				$sHTML .= ' checked';
			$sHTML .= '><label for="'.$f_ID.'DETAILtext">text</label> /';
			$sHTML .= '<input type="radio" name="FIELDS['.$f_ID.'][DETAIL_TEXT_TYPE]" value="html" id="'.$f_ID.'DETAILhtml"';
			if($row->arRes["DETAIL_TEXT_TYPE"]=="html")
				$sHTML .= ' checked';
			$sHTML .= '><label for="'.$f_ID.'DETAILhtml">html</label><br>';

			$sHTML .= '<textarea rows="10" cols="50" name="FIELDS['.$f_ID.'][DETAIL_TEXT]">'.htmlspecialcharsbx($row->arRes["DETAIL_TEXT"]).'</textarea>';
			$row->AddEditField("DETAIL_TEXT", $sHTML);
		}
		foreach($row->arRes['props'] as $prop_id => $arEditHTML)
			$row->AddEditField($prop_id, '<table id="tb'.$arEditHTML['table_id'].'" border="0" cellpadding="0" cellspacing="0"><tr><td nowrap>'.implode("</td></tr><tr><td nowrap>", $arEditHTML['html']).'</td></tr></table>');

		if ($bCatalog)
		{
			if ($showCatalogWithOffers || $row->arRes['CATALOG_TYPE'] != CCatalogProduct::TYPE_SKU)
			{
				if (isset($arElementOps[$f_ID]["element_edit_price"]) && $boolCatalogPrice)
				{
					if ($strUseStoreControl == "Y")
					{
						$row->AddInputField("CATALOG_QUANTITY", false);
					}
					else
					{
						$row->AddInputField("CATALOG_QUANTITY");
					}
					$row->AddCheckField('CATALOG_AVAILABLE', false);
					$row->AddSelectField("CATALOG_QUANTITY_TRACE", $arQuantityTrace);
					$row->AddInputField("CATALOG_WEIGHT");
					$row->AddInputField("CATALOG_WIDTH");
					$row->AddInputField("CATALOG_HEIGHT");
					$row->AddInputField("CATALOG_LENGTH");
					$row->AddCheckField("CATALOG_VAT_INCLUDED");
					if ($boolCatalogPurchasInfo)
					{
						$price = '';
						if ((float)$row->arRes["CATALOG_PURCHASING_PRICE"] > 0)
						{
							if ($bCurrency)
								$price = CCurrencyLang::CurrencyFormat($row->arRes["CATALOG_PURCHASING_PRICE"], $row->arRes["CATALOG_PURCHASING_CURRENCY"], true);
							else
								$price = $row->arRes["CATALOG_PURCHASING_PRICE"]." ".$row->arRes["CATALOG_PURCHASING_CURRENCY"];
						}
						$row->AddViewField("CATALOG_PURCHASING_PRICE", htmlspecialcharsEx($price));
						unset($price);
						if ($catalogPurchasInfoEdit && $bCurrency)
						{
							$editFieldCode = '<input type="hidden" name="FIELDS_OLD['.$f_ID.'][CATALOG_PURCHASING_PRICE]" value="'.$row->arRes['CATALOG_PURCHASING_PRICE'].'">';
							$editFieldCode .= '<input type="hidden" name="FIELDS_OLD['.$f_ID.'][CATALOG_PURCHASING_CURRENCY]" value="'.$row->arRes['CATALOG_PURCHASING_CURRENCY'].'">';
							$editFieldCode .= '<input type="text" size="5" name="FIELDS['.$f_ID.'][CATALOG_PURCHASING_PRICE]" value="'.$row->arRes['CATALOG_PURCHASING_PRICE'].'">';
							$editFieldCode .= '<select name="FIELDS['.$f_ID.'][CATALOG_PURCHASING_CURRENCY]">';
							foreach ($arCurrencyList as &$currencyCode)
							{
								$editFieldCode .= '<option value="'.$currencyCode.'"';
								if ($currencyCode == $row->arRes['CATALOG_PURCHASING_CURRENCY'])
									$editFieldCode .= ' selected';
								$editFieldCode .= '>'.$currencyCode.'</option>';
							}
							$editFieldCode .= '</select>';
							$row->AddEditField('CATALOG_PURCHASING_PRICE', $editFieldCode);
							unset($editFieldCode);
						}
					}
					$row->AddInputField("CATALOG_MEASURE_RATIO");
				}
				elseif ($boolCatalogRead)
				{
					$row->AddCheckField('CATALOG_AVAILABLE', false);
					$row->AddInputField("CATALOG_QUANTITY", false);
					$row->AddSelectField("CATALOG_QUANTITY_TRACE", $arQuantityTrace, false);
					$row->AddInputField("CATALOG_WEIGHT", false);
					$row->AddInputField("CATALOG_WIDTH", false);
					$row->AddInputField("CATALOG_HEIGHT", false);
					$row->AddInputField("CATALOG_LENGTH", false);
					$row->AddCheckField("CATALOG_VAT_INCLUDED", false);
					if ($boolCatalogPurchasInfo)
					{
						$price = '';
						if ((float)$row->arRes["CATALOG_PURCHASING_PRICE"] > 0)
						{
							if ($bCurrency)
								$price = CCurrencyLang::CurrencyFormat($row->arRes["CATALOG_PURCHASING_PRICE"], $row->arRes["CATALOG_PURCHASING_CURRENCY"], true);
							else
								$price = $row->arRes["CATALOG_PURCHASING_PRICE"]." ".$row->arRes["CATALOG_PURCHASING_CURRENCY"];
						}
						$row->AddViewField("CATALOG_PURCHASING_PRICE", htmlspecialcharsEx($price));
						unset($price);
					}
					$row->AddInputField("CATALOG_MEASURE_RATIO", false);
				}
			}
			else
			{
				$row->AddCheckField('CATALOG_AVAILABLE', false);
				$row->AddViewField('CATALOG_QUANTITY', ' ');
				$row->AddViewField('CATALOG_QUANTITY_TRACE', ' ');
				$row->AddViewField('CATALOG_WEIGHT', ' ');
				$row->AddViewField('CATALOG_WIDTH', ' ');
				$row->AddViewField('CATALOG_HEIGHT', ' ');
				$row->AddViewField('CATALOG_LENGTH', ' ');
				$row->AddViewField('CATALOG_VAT_INCLUDED', ' ');
				$row->AddViewField('CATALOG_PURCHASING_PRICE', ' ');
				$row->AddViewField('CATALOG_MEASURE_RATIO', ' ');
				$row->AddViewField('CATALOG_MEASURE', ' ');
				$row->arRes["CATALOG_BAR_CODE"] = ' ';
			}
		}
	}
	else
	{
		$row->AddCheckField("ACTIVE", false);
		$row->AddViewField("NAME", '<a href="'.$row->arRes["edit_url"].'" title="'.GetMessage("IBEL_A_EDIT_TITLE").'">'.htmlspecialcharsEx($row->arRes["NAME"]).'</a>');
		$row->AddInputField("SORT", false);
		$row->AddInputField("CODE", false);
		$row->AddInputField("EXTERNAL_ID", false);
		$row->AddViewField("TAGS", htmlspecialcharsEx($row->arRes["TAGS"]));
		$row->AddCalendarField("DATE_ACTIVE_FROM", false);
		$row->AddCalendarField("DATE_ACTIVE_TO", false);
		$row->AddViewField("WF_STATUS_ID", htmlspecialcharsEx($arWFStatusAll[$row->arRes['WF_STATUS_ID']]));

		if ($bCatalog)
		{
			if ($showCatalogWithOffers || $row->arRes['CATALOG_TYPE'] != CCatalogProduct::TYPE_SKU)
			{
				$row->AddCheckField('CATALOG_AVAILABLE', false);
				$row->AddInputField("CATALOG_QUANTITY", false);
				$row->AddSelectField("CATALOG_QUANTITY_TRACE", $arQuantityTrace, false);
				$row->AddInputField("CATALOG_WEIGHT", false);
				$row->AddCheckField("CATALOG_VAT_INCLUDED", false);
				if ($boolCatalogPurchasInfo)
				{
					$price = '';
					if ((float)$row->arRes["CATALOG_PURCHASING_PRICE"] > 0)
					{
						if ($bCurrency)
							$price = CCurrencyLang::CurrencyFormat($row->arRes["CATALOG_PURCHASING_PRICE"], $row->arRes["CATALOG_PURCHASING_CURRENCY"], true);
						else
							$price = $row->arRes["CATALOG_PURCHASING_PRICE"]." ".$row->arRes["CATALOG_PURCHASING_CURRENCY"];
					}
					$row->AddViewField("CATALOG_PURCHASING_PRICE", htmlspecialcharsEx($price));
					unset($price);
				}
				$row->AddInputField("CATALOG_MEASURE_RATIO", false);
			}
			else
			{
				$row->AddCheckField('CATALOG_AVAILABLE', false);
				$row->AddViewField('CATALOG_QUANTITY', ' ');
				$row->AddViewField('CATALOG_QUANTITY_TRACE', ' ');
				$row->AddViewField('CATALOG_WEIGHT', ' ');
				$row->AddViewField('CATALOG_WIDTH', ' ');
				$row->AddViewField('CATALOG_HEIGHT', ' ');
				$row->AddViewField('CATALOG_LENGTH', ' ');
				$row->AddViewField('CATALOG_VAT_INCLUDED', ' ');
				$row->AddViewField('CATALOG_PURCHASING_PRICE', ' ');
				$row->AddViewField('CATALOG_MEASURE_RATIO', ' ');
				$row->AddViewField('CATALOG_MEASURE', ' ');
				$row->arRes["CATALOG_BAR_CODE"] = ' ';
			}
		}
		if (array_key_exists("PREVIEW_PICTURE", $arSelectedFieldsMap))
		{
			$row->AddViewFileField("PREVIEW_PICTURE", array(
				"IMAGE" => "Y",
				"PATH" => "Y",
				"FILE_SIZE" => "Y",
				"DIMENSIONS" => "Y",
				"IMAGE_POPUP" => "Y",
				"MAX_SIZE" => $maxImageSize,
				"MIN_SIZE" => $minImageSize,
				)
			);
		}
		if (array_key_exists("DETAIL_PICTURE", $arSelectedFieldsMap))
		{
			$row->AddViewFileField("DETAIL_PICTURE", array(
				"IMAGE" => "Y",
				"PATH" => "Y",
				"FILE_SIZE" => "Y",
				"DIMENSIONS" => "Y",
				"IMAGE_POPUP" => "Y",
				"MAX_SIZE" => $maxImageSize,
				"MIN_SIZE" => $minImageSize,
				)
			);
		}
	}

	if (isset($arSelectedFieldsMap['CATALOG_TYPE']))
	{
		$strProductType = '';
		if (isset($productTypeList[$row->arRes["CATALOG_TYPE"]]))
			$strProductType = $productTypeList[$row->arRes["CATALOG_TYPE"]];
		if ($row->arRes['CATALOG_BUNDLE'] == 'Y' && $boolCatalogSet)
			$strProductType .= ('' != $strProductType ? ', ' : '').GetMessage('IBEL_CATALOG_TYPE_MESS_GROUP');
		$row->AddViewField('CATALOG_TYPE', $strProductType);
	}
	if ($bCatalog && isset($arSelectedFieldsMap['CATALOG_MEASURE']) && ($showCatalogWithOffers || $row->arRes['CATALOG_TYPE'] != CCatalogProduct::TYPE_SKU))
	{
		if (isset($arElementOps[$f_ID]["element_edit_price"]) && $boolCatalogPrice && $row->arRes['CATALOG_TYPE'] != CCatalogProduct::TYPE_SET)
		{
			$row->AddSelectField('CATALOG_MEASURE', $measureList);
		}
		else
		{
			$measureTitle = (isset($measureList[$row->arRes['CATALOG_MEASURE']])
				? $measureList[$row->arRes['CATALOG_MEASURE']]
				: $measureList[0]
			);
			$row->AddViewField('CATALOG_MEASURE', $measureTitle);
			unset($measureTitle);
		}
	}
}

$lAdmin->AddFooter(
	array(
		array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$rsData->SelectedRowsCount()),
		array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"),
	)
);

$arGroupActions = array();
$arGroupActions['send_to_ed'] = GetMessage('EASYDIRECT_TEMPLATES_fgoods_sendid');
$arParams = array("disable_action_target"=>"N");
$lAdmin->AddGroupActionTable($arGroupActions, $arParams);

if ($bCatalog && $USER->CanDoOperation('catalog_price'))
{
	$lAdmin->BeginEpilogContent();
	?>
	<div>
		<input type="hidden" name="chprice_value_changing_price">
		<input type="hidden" name="chprice_units">
		<input type="hidden" name="chprice_id_price_type">
		<input type="hidden" name="chprice_format_result">
		<input type="hidden" name="chprice_result_mask">
		<input type="hidden" name="chprice_initial_price_type">
		<input type="hidden" name="chprice_difference_value">
	</div>
	<?

	/** Creation window of common price changer */
	CJSCore::Init(array('window'));
	?>

	<script>
		/**
		 * @func CreateDialogChPrice - creation of common changing price dialog
		 */
		function CreateDialogChPrice()
		{
			var paramsWindowChanger =
			{
				title: "<?=GetMessage("IBLOCK_CHANGING_PRICE")?>",
				content_url: "/bitrix/tools/catalog/iblock_catalog_change_price.php?lang=" + "<?=LANGUAGE_ID?>" + "&bxpublic=Y",
				content_post: "<?=bitrix_sessid_get()?>" + "&sTableID=<?=$sTableID?>",
				width: 800,
				height: 415,
				resizable: false,
				buttons: [
					{
						title: top.BX.message('JS_CORE_WINDOW_SAVE'),
						id: 'savebtn',
						name: 'savebtn',
						className: top.BX.browser.IsIE() && top.BX.browser.IsDoctype() && !top.BX.browser.IsIE10() ? '' : 'adm-btn-save'
					},
					top.BX.CAdminDialog.btnCancel
				]
			};
			var priceChanger = (new top.BX.CAdminDialog(paramsWindowChanger));
			priceChanger.Show();
		}
	</script>

	<?
	$lAdmin->EndEpilogContent();
}
$sLastFolder = '';
$lastSectionId = array();
if(!defined("CATALOG_PRODUCT"))
{
	$chain = $lAdmin->CreateChain();
	if($arIBTYPE["SECTIONS"]=="Y")
	{
		$chain->AddItem(array(
			"TEXT" => htmlspecialcharsEx($arIBlock["NAME"]),
			"LINK" => htmlspecialcharsbx(CIBlock::GetAdminSectionListLink($IBLOCK_ID, array('find_section_section'=>0))),
		));
		$lastSectionId[] = 0;

		if($find_section_section > 0)
		{
			$sLastFolder = htmlspecialcharsbx(CIBlock::GetAdminSectionListLink($IBLOCK_ID, array('find_section_section'=>0)));
			$nav = CIBlockSection::GetNavChain($IBLOCK_ID, $find_section_section, array('ID', 'NAME'));
			while($ar_nav = $nav->GetNext())
			{
				$sLastFolder = htmlspecialcharsbx(CIBlock::GetAdminSectionListLink($IBLOCK_ID, array('find_section_section'=>$ar_nav["ID"])));
				$lastSectionId[] = $ar_nav["ID"];
				$chain->AddItem(array(
					"TEXT" => $ar_nav["NAME"],
					"LINK" => $sLastFolder,
				));
			}
		}
	}
	$lAdmin->ShowChain($chain);
}

$aContext = array();

if ($boolIBlockElementAdd)
{
	if (!empty($arCatalog))
	{
		CCatalogAdminTools::setProductFormParams();
		$arCatalogBtns = CCatalogAdminTools::getIBlockElementMenu(
			$IBLOCK_ID,
			$arCatalog,
			array(
				'IBLOCK_SECTION_ID' => $find_section_section,
				'find_section_section' => $find_section_section
			)
		);
		if (!empty($arCatalogBtns))
			$aContext = $arCatalogBtns;
	}
	if (empty($aContext))
	{
		$aContext[] = array(
			"ICON" => "btn_new",
			"TEXT" => htmlspecialcharsbx($arIBlock["ELEMENT_ADD"]),
			"LINK" => CIBlock::GetAdminElementEditLink($IBLOCK_ID, 0, array(
				'IBLOCK_SECTION_ID'=>$find_section_section,
				'find_section_section'=>$find_section_section
			)),
			"LINK_PARAM" => "",
			"TITLE" => GetMessage("IBEL_A_ADDEL_TITLE")
		);
	}
}

if(strlen($sLastFolder) > 0)
{
	$aContext[] = array(
		"TEXT" => GetMessage("IBEL_A_UP"),
		"LINK" => CIBlock::GetAdminElementListLink($IBLOCK_ID, array(
			'find_section_section'=>$lastSectionId[count($lastSectionId)-2],
		)),
		"LINK_PARAM" => "",
		"TITLE" => GetMessage("IBEL_A_UP_TITLE"),
	);
}

//$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->AddAdminContextMenu();

$lAdmin->CheckListMode();

$APPLICATION->SetTitle($arIBlock["NAME"].": ".$arIBlock["ELEMENTS_NAME"]);
Main\Page\Asset::getInstance()->addJs('/bitrix/js/iblock/iblock_edit.js');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

//We need javascript not in excel mode
if((!isset($_REQUEST["mode"]) || $_REQUEST["mode"]=='list' || $_REQUEST["mode"]=='frame') && $bCatalog && $bCurrency)
{
	?><script type="text/javascript">
		top.arCatalogShowedGroups = [];
		top.arExtra = [];
		top.arCatalogGroups = [];
		top.BaseIndex = '';
	<?
	if (!empty($arCatGroup) && is_array($arCatGroup))
	{
		$i = 0;
		$j = 0;
		foreach($arCatGroup as &$CatalogGroups)
		{
			if(in_array("CATALOG_GROUP_".$CatalogGroups["ID"], $arSelectedFields))
			{
				echo "top.arCatalogShowedGroups[".$i."]=".$CatalogGroups["ID"].";\n";
				$i++;
			}
			if ($CatalogGroups["BASE"]!="Y")
			{
				echo "top.arCatalogGroups[".$j."]=".$CatalogGroups["ID"].";\n";
				$j++;
			}
			else
			{
				echo "top.BaseIndex=".$CatalogGroups["ID"].";\n";
			}
		}
		unset($CatalogGroups);
	}
	if (!empty($arCatExtra) && is_array($arCatExtra))
	{
		$i=0;
		foreach($arCatExtra as &$CatExtra)
		{
			echo "top.arExtra[".$CatExtra["ID"]."]=".$CatExtra["PERCENTAGE"].";\n";
			$i++;
		}
		unset($CatExtra);
	}
	?>
		top.ChangeBasePrice = function(id)
		{
			for(var i = 0, cnt = top.arCatalogShowedGroups.length; i < cnt; i++)
			{
				var pr = top.document.getElementById("CATALOG_PRICE["+id+"]"+"["+top.arCatalogShowedGroups[i]+"]");
				if(pr.disabled)
				{
					var price = top.document.getElementById("CATALOG_PRICE["+id+"]"+"["+top.BaseIndex+"]").value;
					if(price > 0)
					{
						var extraId = top.document.getElementById("CATALOG_EXTRA["+id+"]"+"["+top.arCatalogShowedGroups[i]+"]").value;
						var esum = parseFloat(price) * (1 + top.arExtra[extraId] / 100);
						var eps = 1.00/Math.pow(10, 6);
						esum = Math.round((esum+eps)*100)/100;
					}
					else
						var esum = "";

					pr.value = esum;
				}
			}
		}

		top.ChangeBaseCurrency = function(id)
		{
			var currency = top.document.getElementById("CATALOG_CURRENCY["+id+"]["+top.BaseIndex+"]");
			for(var i = 0, cnt = top.arCatalogShowedGroups.length; i < cnt; i++)
			{
				var pr = top.document.getElementById("CATALOG_CURRENCY["+id+"]["+top.arCatalogShowedGroups[i]+"]");
				if(pr.disabled)
				{
					pr.selectedIndex = currency.selectedIndex;
				}
			}
		}
	</script>
	<?
}

CJSCore::Init('file_input');
?>
<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>
<form method="GET" name="find_form" id="find_form" action="<?echo $APPLICATION->GetCurPage()?>">
<?
$arFindFields = Array();
$arFindFields["IBEL_A_F_ID"] = GetMessage("IBEL_A_F_ID");

if($arIBTYPE["SECTIONS"]=="Y")
	$arFindFields["IBEL_A_F_PARENT"] = GetMessage("IBEL_A_F_PARENT");

$arFindFields["IBEL_A_F_MODIFIED_WHEN"] = GetMessage("IBEL_A_F_MODIFIED_WHEN");
$arFindFields["IBEL_A_F_MODIFIED_BY"] = GetMessage("IBEL_A_F_MODIFIED_BY");
$arFindFields["IBEL_A_F_CREATED_WHEN"] = GetMessage("IBEL_A_F_CREATED_WHEN");
$arFindFields["IBEL_A_F_CREATED_BY"] = GetMessage("IBEL_A_F_CREATED_BY");

$arFindFields["IBEL_A_F_ACTIVE_FROM"] = GetMessage("IBEL_A_ACTFROM");
$arFindFields["IBEL_A_F_ACTIVE_TO"] = GetMessage("IBEL_A_ACTTO");
$arFindFields["IBEL_A_F_ACT"] = GetMessage("IBEL_A_F_ACT");
$arFindFields["IBEL_A_F_NAME"] = GetMessage("IBEL_A_F_NAME");
$arFindFields["IBEL_A_F_DESC"] = GetMessage("IBEL_A_F_DESC");
$arFindFields["IBEL_A_CODE"] = GetMessage("IBEL_A_CODE");
$arFindFields["IBEL_A_EXTERNAL_ID"] = GetMessage("IBEL_A_EXTERNAL_ID");
$arFindFields["IBEL_A_TAGS"] = GetMessage("IBEL_A_TAGS");
if ($bCatalog)
{
	$arFindFields["CATALOG_TYPE"] = GetMessage("IBEL_CATALOG_TYPE");
	$arFindFields["CATALOG_BUNDLE"] = GetMessage("IBEL_CATALOG_BUNDLE");
	$arFindFields["CATALOG_AVAILABLE"] = GetMessage("IBEL_CATALOG_AVAILABLE");
}

foreach($arProps as $arProp)
	if($arProp["FILTRABLE"]=="Y" && $arProp["PROPERTY_TYPE"]!="F")
		$arFindFields["IBEL_A_PROP_".$arProp["ID"]] = $arProp["NAME"];

if ($boolSKU && $boolSKUFiltrable)
{
	foreach($arSKUProps as $arProp)
	{
		$arFindFields["IBEL_A_SUB_PROP_".$arProp["ID"]] = ('' != $strSKUName ? $strSKUName.' - ' : '').$arProp["NAME"];
	}
}

$oFilter = new CAdminFilter($sTableID."_filter", $arFindFields);
?>
<script type="text/javascript">
var arClearHiddenFields = [];

function clearFilterFields()
{
	var index;

	for (index = 0; index < arClearHiddenFields.length; index++)
	{
		if (window[arClearHiddenFields[index]] !== undefined)
		{
			if ('ClearForm' in window[arClearHiddenFields[index]])
			{
				window[arClearHiddenFields[index]].ClearForm();
			}
		}
	}
}

BX.ready(function(){
	BX.addCustomEvent(window, 'onBeforeAdminFilterClear', clearFilterFields);
});

try {
	var DecimalSeparator = Number("1.2").toLocaleString().charCodeAt(1);
	document.cookie = '<?echo $dsc_cookie_name?>='+DecimalSeparator+'; path=/;';
}
catch (e)
{
}
</script><?
$oFilter->Begin();
?>
	<tr>
		<td><?echo GetMessage("IBLOCK_FILTER_ID")?>:</td>
		<td nowrap>
    		<select name="find_ibid">
    		    <?
        		$res = CIBlock::GetList(Array(),Array("ID"=>CEDirectCatalogItems::getCatalogIBlockIDs(),"ACTIVE"=>"Y","CNT_ACTIVE"=>"Y"), false);
        		while($ar_res = $res->Fetch()){
        		    echo '<option '.(($ar_res['ID']==$IBLOCK_ID)?"selected":"").' value="'.$ar_res['ID'].'">'.$ar_res['NAME'].'</option>';
        		}
        		?>
    		</select> 	
    		<input type="hidden" name="oldIBID" value="<?=$IBLOCK_ID?>">
		</td>
	</tr>
	<tr>
		<td><b><?=GetMessage("MAIN_ADMIN_LIST_FILTER_1ST_NAME")?></b></td>
		<td><input type="text" name="find_el" title="<?=GetMessage("MAIN_ADMIN_LIST_FILTER_1ST")?>" value="<?echo htmlspecialcharsEx($find_el)?>" size="30">
			<select name="find_el_type">
				<option value="name"<?if($find_el_type=="name") echo " selected"?>><?echo GetMessage("IBEL_A_F_NAME")?></option>
				<option value="desc"<?if($find_el_type=="desc") echo " selected"?>><?echo GetMessage("IBEL_A_F_DESC")?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?echo GetMessage("IBLOCK_FILTER_FROMTO_ID")?></td>
		<td nowrap>
			<input type="text" name="find_el_id_start" size="10" value="<?echo htmlspecialcharsEx($find_el_id_start)?>">
			...
			<input type="text" name="find_el_id_end" size="10" value="<?echo htmlspecialcharsEx($find_el_id_end)?>">
		</td>
	</tr>

	<?if($arIBTYPE["SECTIONS"]=="Y"):?>
	<tr>
		<td><?echo GetMessage("IBLOCK_FIELD_SECTION_ID")?>:</td>
		<td>
			<select name="find_section_section">
				<option value="-1"><?echo GetMessage("IBLOCK_VALUE_ANY")?></option>
				<option value="0"<?if($find_section_section=="0")echo" selected"?>><?echo GetMessage("IBLOCK_UPPER_LEVEL")?></option>
				<?
				$bsections = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$IBLOCK_ID), array("ID", "NAME", "DEPTH_LEVEL"));
				while($ar = $bsections->GetNext()):
					?><option value="<?echo $ar["ID"]?>"<?if($ar["ID"]==$find_section_section)echo " selected"?>><?echo str_repeat("&nbsp;.&nbsp;", $ar["DEPTH_LEVEL"])?><?echo $ar["NAME"]?></option><?
				endwhile;
				?>
			</select><br>
			<input type="checkbox" name="find_el_subsections" value="Y"<?if($find_el_subsections=="Y")echo" checked"?>> <?echo GetMessage("IBLOCK_INCLUDING_SUBSECTIONS")?>
		</td>
	</tr>
	<?endif?>

	<tr>
		<td><?echo GetMessage("IBLOCK_FIELD_TIMESTAMP_X")?>:</td>
		<td><?echo CalendarPeriod("find_el_timestamp_from", htmlspecialcharsEx($find_el_timestamp_from), "find_el_timestamp_to", htmlspecialcharsEx($find_el_timestamp_to), "find_form", "Y")?></font></td>
	</tr>

	<tr>
		<td><?=GetMessage("IBLOCK_FIELD_MODIFIED_BY")?>:</td>
		<td>
			<?echo FindUserID(
				"find_el_modified_user_id",
				$find_el_modified_user_id,
				"",
				"find_form",
				"5",
				"",
				" ... ",
				"",
				""
			);?>
		</td>
	</tr>

	<tr>
		<td><?echo GetMessage("IBLOCK_EL_ADMIN_DCREATE")?>:</td>
		<td><?echo CalendarPeriod("find_el_created_from", htmlspecialcharsEx($find_el_created_from), "find_el_created_to", htmlspecialcharsEx($find_el_created_to), "find_form", "Y")?></td>
	</tr>

	<tr>
		<td><?echo GetMessage("IBLOCK_EL_ADMIN_WCREATE")?></td>
		<td>
			<?echo FindUserID(
				"find_el_created_user_id",
				$find_el_created_user_id,
				"",
				"find_form",
				"5",
				"",
				" ... ",
				"",
				""
			);?>
		</td>
	</tr>

	<tr>
		<td><?echo GetMessage("IBEL_A_ACTFROM")?>:</td>
		<td><?echo CalendarPeriod("find_el_date_active_from_from", htmlspecialcharsEx($find_el_date_active_from_from), "find_el_date_active_from_to", htmlspecialcharsEx($find_el_date_active_from_to), "find_form")?></td>
	</tr>

	<tr>
		<td><?echo GetMessage("IBEL_A_ACTTO")?>:</td>
		<td><?echo CalendarPeriod("find_el_date_active_to_from", htmlspecialcharsEx($find_el_date_active_to_from), "find_el_date_active_to_to", htmlspecialcharsEx($find_el_date_active_to_to), "find_form")?></td>
	</tr>

	<tr>
		<td><?echo GetMessage("IBLOCK_FIELD_ACTIVE")?>:</td>
		<td>
			<select name="find_el_active">
				<option value=""><?=htmlspecialcharsEx(GetMessage('IBLOCK_VALUE_ANY'))?></option>
				<option value="Y"<?if($find_el_active=="Y")echo " selected"?>><?=htmlspecialcharsEx(GetMessage("IBLOCK_YES"))?></option>
				<option value="N"<?if($find_el_active=="N")echo " selected"?>><?=htmlspecialcharsEx(GetMessage("IBLOCK_NO"))?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td><?echo GetMessage("IBLOCK_FIELD_NAME")?>:</td>
		<td><input type="text" name="find_el_name" value="<?echo htmlspecialcharsEx($find_el_name)?>" size="30">&nbsp;<?=ShowFilterLogicHelp()?></td>
	</tr>
	<tr>
		<td><?echo GetMessage("IBLOCK_EL_ADMIN_DESC")?></td>
		<td><input type="text" name="find_el_intext" value="<?echo htmlspecialcharsEx($find_el_intext)?>" size="30">&nbsp;<?=ShowFilterLogicHelp()?></td>
	</tr>

	<tr>
		<td><?=GetMessage("IBEL_A_CODE")?>:</td>
		<td><input type="text" name="find_el_code" value="<?echo htmlspecialcharsEx($find_el_code)?>" size="30"></td>
	</tr>
	<tr>
		<td><?=GetMessage("IBEL_A_EXTERNAL_ID")?>:</td>
		<td><input type="text" name="find_el_external_id" value="<?echo htmlspecialcharsEx($find_el_external_id)?>" size="30"></td>
	</tr>
	<tr>
		<td><?=GetMessage("IBEL_A_TAGS")?>:</td>
		<td>
			<?
			if ($bSearch):
				echo InputTags("find_el_tags", $find_el_tags, $arIBlock["SITE_ID"]);
			else:
			?>
				<input type="text" name="find_el_tags" value="<?echo htmlspecialcharsEx($find_el_tags)?>" size="30">
			<?endif?>
		</td>
	</tr>
	<?
if ($bCatalog)
{
	?><tr>
		<td><?=GetMessage("IBEL_CATALOG_TYPE"); ?>:</td>
		<td>
			<select name="find_el_catalog_type[]" multiple>
				<option value=""><?=htmlspecialcharsEx(GetMessage('IBLOCK_VALUE_ANY'))?></option>
				<?
				$catalogTypes = (!empty($find_el_catalog_type) ? $find_el_catalog_type : array());
				foreach ($productTypeList as $productType => $productTypeName)
				{
					?>
					<option value="<? echo $productType; ?>"<? echo (in_array($productType, $catalogTypes) ? ' selected' : ''); ?>><? echo htmlspecialcharsEx($productTypeName); ?></option><?
				}
				unset($productType, $productTypeName, $catalogTypes);
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td><?echo GetMessage("IBEL_CATALOG_BUNDLE")?>:</td>
		<td>
			<select name="find_el_catalog_bundle">
				<option value=""><?=htmlspecialcharsEx(GetMessage('IBLOCK_VALUE_ANY'))?></option>
				<option value="Y"<?if($find_el_catalog_bundle=="Y")echo " selected"?>><?=htmlspecialcharsEx(GetMessage("IBLOCK_YES"))?></option>
				<option value="N"<?if($find_el_catalog_bundle=="N")echo " selected"?>><?=htmlspecialcharsEx(GetMessage("IBLOCK_NO"))?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?echo GetMessage("IBEL_CATALOG_AVAILABLE")?>:</td>
		<td>
			<select name="find_el_catalog_available">
				<option value=""><?=htmlspecialcharsEx(GetMessage('IBLOCK_VALUE_ANY'))?></option>
				<option value="Y"<?if($find_el_catalog_available=="Y")echo " selected"?>><?=htmlspecialcharsEx(GetMessage("IBLOCK_YES"))?></option>
				<option value="N"<?if($find_el_catalog_available=="N")echo " selected"?>><?=htmlspecialcharsEx(GetMessage("IBLOCK_NO"))?></option>
			</select>
		</td>
	</tr>
	<?
}

function _ShowGroupPropertyField2($name, $property_fields, $values)
{
	if (!is_array($values))
		$values = array();

	$res = "";
	$result = "";
	$bWas = false;
	$sections = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$property_fields["LINK_IBLOCK_ID"]), array("ID", "NAME", "DEPTH_LEVEL"));
	while($ar = $sections->GetNext())
	{
		$res .= '<option value="'.$ar["ID"].'"';
		if(in_array($ar["ID"], $values))
		{
			$bWas = true;
			$res .= ' selected';
		}
		$res .= '>'.str_repeat(" . ", $ar["DEPTH_LEVEL"]).$ar["NAME"].'</option>';
	}
	$result .= '<select name="'.$name.'[]" size="5" multiple>';
	$result .= '<option value=""'.(!$bWas?' selected':'').'>'.GetMessage("IBLOCK_ELEMENT_EDIT_NOT_SET").'</option>';
	$result .= $res;
	$result .= '</select>';
	return $result;
}

foreach($arProps as $arProp):
	if($arProp["FILTRABLE"]=="Y" && $arProp["PROPERTY_TYPE"]!="F"):
?>
<tr>
	<td><?=$arProp["NAME"]?>:</td>
	<td>
		<?if(array_key_exists("GetAdminFilterHTML", $arProp["PROPERTY_USER_TYPE"])):
			echo call_user_func_array($arProp["PROPERTY_USER_TYPE"]["GetAdminFilterHTML"], array(
				$arProp,
				array(
					"VALUE" => "find_el_property_".$arProp["ID"],
					"TABLE_ID" => $sTableID,
				),
			));
		elseif($arProp["PROPERTY_TYPE"]=='S'):?>
			<input type="text" name="find_el_property_<?=$arProp["ID"]?>" value="<?echo htmlspecialcharsEx(${"find_el_property_".$arProp["ID"]})?>" size="30">&nbsp;<?=ShowFilterLogicHelp()?>
		<?elseif($arProp["PROPERTY_TYPE"]=='N' || $arProp["PROPERTY_TYPE"]=='E'):?>
			<input type="text" name="find_el_property_<?=$arProp["ID"]?>" value="<?echo htmlspecialcharsEx(${"find_el_property_".$arProp["ID"]})?>" size="30">
		<?elseif($arProp["PROPERTY_TYPE"]=='L'):?>
			<select name="find_el_property_<?=$arProp["ID"]?>">
				<option value=""><?echo GetMessage("IBLOCK_VALUE_ANY")?></option>
				<option value="NOT_REF"<?if(${"find_el_property_".$arProp["ID"]} == "NOT_REF")echo " selected"?>><?echo GetMessage("IBLOCK_ELEMENT_EDIT_NOT_SET")?></option><?
				$dbrPEnum = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC", "VALUE"=>"ASC"), Array("PROPERTY_ID"=>$arProp["ID"]));
				while($arPEnum = $dbrPEnum->GetNext()):
				?>
					<option value="<?=$arPEnum["ID"]?>"<?if(${"find_el_property_".$arProp["ID"]} == $arPEnum["ID"])echo " selected"?>><?=$arPEnum["VALUE"]?></option>
				<?
				endwhile;
		?></select>
		<?
		elseif($arProp["PROPERTY_TYPE"]=='G'):
			echo _ShowGroupPropertyField2('find_el_property_'.$arProp["ID"], $arProp, ${'find_el_property_'.$arProp["ID"]});
		endif;
		?>
	</td>
</tr>
<?
	endif;
endforeach;

if ($boolSKU && $boolSKUFiltrable)
{
	foreach($arSKUProps as $arProp)
	{
?>
<tr>
	<td><? echo ('' != $strSKUName ? $strSKUName.' - ' : ''), $arProp["NAME"]; ?>:</td>
	<td>
		<?if(!empty($arProp["PROPERTY_USER_TYPE"]) && isset($arProp["PROPERTY_USER_TYPE"]["GetAdminFilterHTML"]))
		{
			echo call_user_func_array($arProp["PROPERTY_USER_TYPE"]["GetAdminFilterHTML"], array(
				$arProp,
				array(
					"VALUE" => "find_sub_el_property_".$arProp["ID"],
					"TABLE_ID" => $sTableID,
				),
			));
		}
		elseif($arProp["PROPERTY_TYPE"]=='S')
		{
			?><input type="text" name="find_sub_el_property_<?=$arProp["ID"]?>" value="<?echo htmlspecialcharsEx(${"find_sub_el_property_".$arProp["ID"]})?>" size="30">&nbsp;<?=ShowFilterLogicHelp(); ?><?
		}
		elseif($arProp["PROPERTY_TYPE"]=='N' || $arProp["PROPERTY_TYPE"]=='E')
		{
			?><input type="text" name="find_sub_el_property_<?=$arProp["ID"]?>" value="<?echo htmlspecialcharsEx(${"find_sub_el_property_".$arProp["ID"]})?>" size="30"><?
		}
		elseif($arProp["PROPERTY_TYPE"]=='L')
		{
			?><select name="find_sub_el_property_<?=$arProp["ID"]?>">
				<option value=""><?echo GetMessage("IBLOCK_VALUE_ANY")?></option>
				<option value="NOT_REF"><?echo GetMessage("IBLOCK_ELEMENT_EDIT_NOT_SET")?></option><?
				$dbrPEnum = CIBlockPropertyEnum::GetList(array("SORT"=>"ASC", "VALUE"=>"ASC"), array("PROPERTY_ID"=>$arProp["ID"]));
				while($arPEnum = $dbrPEnum->GetNext())
				{
					?><option value="<?=$arPEnum["ID"]?>"<?if(${"find_sub_el_property_".$arProp["ID"]} == $arPEnum["ID"])echo " selected"?>><?=$arPEnum["VALUE"]?></option><?
				}
			?></select><?
		}
		elseif($arProp["PROPERTY_TYPE"]=='G')
		{
			echo _ShowGroupPropertyField2('find_sub_el_property_'.$arProp["ID"], $arProp, ${'find_sub_el_property_'.$arProp["ID"]});
		}
		?>
	</td>
</tr>
<?
	}
}
$oFilter->Buttons(array(
	"table_id" => $sTableID,
	"url" => $APPLICATION->GetCurPage().'?type='.$type.'&IBLOCK_ID='.$IBLOCK_ID,
	"form" => "find_form",
));
$oFilter->End();
?>
</form>
<?
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");