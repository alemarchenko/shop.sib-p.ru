<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule('advertising'))
	return;

IncludeModuleLangFile(__FILE__);

//Matrix
$arWeekday = Array(
	"SUNDAY" => Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23),
	"MONDAY" => Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23),
	"TUESDAY" => Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23),
	"WEDNESDAY" => Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23),
	"THURSDAY" => Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23),
	"FRIDAY" => Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23),
	"SATURDAY" => Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23)
);

$contractId  = false;

$rsADV = CAdvContract::GetList("s_sort", "desc", array("NAME" => 'MEGAMART', 'DESCRIPTION' => GetMessage("CONTRACT_DESC")." [".WIZARD_SITE_ID."]"));
if ($arADV = $rsADV->Fetch())
{
	$contractId  = $arADV["ID"];
	if (WIZARD_INSTALL_DEMO_DATA)
	{
		CAdvContract::Delete($arADV["ID"]);
		$contractId  = false;
	}
}
if ($contractId == false)
{
	$arFields = array(
		'ACTIVE' => 'Y',
		'NAME' => 'MEGAMART',
		'SORT' => 1000,
		'DESCRIPTION' => GetMessage("CONTRACT_DESC")." [".WIZARD_SITE_ID."]",
		'EMAIL_COUNT' => 1,
		'arrTYPE' => array('ALL'),
		'arrWEEKDAY' => $arWeekday,
		'arrSITE' => Array(WIZARD_SITE_ID),
	);
	$contractId = CAdvContract::Set($arFields, 0, 'N');

	//Types
	$arTypes = Array(
		Array(
			"SID" => "RS_INDEX_FULL",
			"ACTIVE" => "Y",
			"SORT" => 1,
			"NAME" => GetMessage("DEMO_ADV_RS_INDEX_FULL"),
			"DESCRIPTION" => ""
		),
		Array(
			"SID" => "RS_INDEX_MINI",
			"ACTIVE" => "Y",
			"SORT" => 2,
			"NAME" => GetMessage("DEMO_ADV_RS_INDEX_MINI"),
			"DESCRIPTION" => ""
		),
		Array(
			"SID" => "RS_OUTER_SIDEBAR",
			"ACTIVE" => "Y",
			"SORT" => 2,
			"NAME" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR"),
			"DESCRIPTION" => ""
		)
	);

	foreach ($arTypes as $arFields)
	{
		$dbResult = CAdvType::GetByID($arTypes["SID"], $CHECK_RIGHTS="N");
		if ($dbResult && $dbResult->Fetch())
			continue;

		CAdvType::Set($arFields, "", $CHECK_RIGHTS="N");
	}

	$pathToBanner = str_replace("\\", "/", dirname(__FILE__));
	$lang = (in_array(LANGUAGE_ID, array("ru", "en", "de"))) ? LANGUAGE_ID : \Bitrix\Main\Localization\Loc::getDefaultLang(LANGUAGE_ID);
	$pathToBanner = $pathToBanner."/lang/".$lang;

	if (CModule::IncludeModule("iblock"))
	{
		$IBLOCK_CATALOG_ID = $_SESSION["WIZARD_CATALOG_CATALOG_IBLOCK_ID"];

		$arSectionLinks = array();

		$urlTemplate = CIBlock::GetArrayById($IBLOCK_CATALOG_ID, "SECTION_PAGE_URL");
		$urlTemplate = str_replace("#SITE_DIR#", WIZARD_SITE_DIR, $urlTemplate);

		$dbSect = CIBlockSection::GetList(Array(), Array("IBLOCK_TYPE" => "catalog", "IBLOCK_ID"=>$IBLOCK_CATALOG_ID, "CODE" => array("instrument", "bytovaya-tekhnika", "kompyutery", "stroyka-i-remont", "odezhda-i-obuv", "bytovaya-tekhnika", "kompyutery", "stiralnye-mashiny", "mikrovolnovye-pechi",), "IBLOCK_SITE_ID" => WIZARD_SITE_ID), false, array("SECTION_PAGE_URL"));
		$dbSect->SetUrlTemplates("", $urlTemplate);
		while ($arSect = $dbSect->GetNext())
		{
			$arSectionLinks[$arSect["CODE"]] = $arSect["SECTION_PAGE_URL"];
		}
	}

	$arBanners = Array(
		Array(
			"CONTRACT_ID" => $contractId,
			"TYPE_SID" => "RS_INDEX_FULL",
			"STATUS_SID" => "PUBLISHED",

			"NAME" => GetMessage("DEMO_ADV_RS_INDEX_FULL_1_NAME"),
			"ACTIVE" => "Y",
			"arrSITE" => Array(WIZARD_SITE_ID),
			"WEIGHT"=> 100,
			"FIX_SHOW" => "N",
			"FIX_CLICK" => "Y",
			"AD_TYPE" => "template",
			"STAT_EVENT_1" => "banner",
			"STAT_EVENT_2" => "click",
			"arrWEEKDAY" => $arWeekday,
			"COMMENTS" => GetMessage("DEMO_ADV_RS_INDEX_FULL_1_COMMENTS", array('#SITE_ID#' => WIZARD_SITE_ID)),
			"TEMPLATE" => serialize(array(
				"NAME" => "redsign_index_full",
				"MODE" => "N",
				"PROPS" => array(
					0 => array(
						"BANNER_NAME" => GetMessage("DEMO_ADV_RS_INDEX_FULL_1_NAME"),
						// "BACKGROUND" => "image",
						// "IMG_FIXED" => "N",
						"LINK_URL" => WIZARD_SITE_DIR.'catalog/elektronika/foto-videotekhnika/tv-pristavki/apple-tv-4k/',
						"LINK_TITLE" => "",
						"LINK_TARGET" => "_self",
					),
				)
			)),
			"TEMPLATE_FILES" => array(
				0 => array(
					"IMG" => Array(
						"name" => "rs_index_full_1.jpg",
						"type" => "image/jpg",
						"tmp_name" => $pathToBanner."/rs_index_full_1.jpg",
						"error" => "0",
						"size" => @filesize($pathToBanner."/rs_index_full_1.jpg"),
						"MODULE_ID" => "advertising"
					)
				))
		),
		Array(
			"CONTRACT_ID" => $contractId,
			"TYPE_SID" => "RS_INDEX_MINI",
			"STATUS_SID" => "PUBLISHED",
			"NAME" => GetMessage("DEMO_ADV_RS_INDEX_MINI_1_NAME"),
			"ACTIVE" => "Y",
			"arrSITE" => Array(WIZARD_SITE_ID),
			"WEIGHT"=> 200,
			"FIX_SHOW" => "N",
			"FIX_CLICK" => "Y",
			"AD_TYPE" => "template",
			"STAT_EVENT_1" => "banner",
			"STAT_EVENT_2" => "click",
			"arrWEEKDAY" => $arWeekday,
			"COMMENTS" => GetMessage("DEMO_ADV_RS_INDEX_MINI_1_COMMENTS", array('#SITE_ID#' => WIZARD_SITE_ID)),
			"TEMPLATE" => serialize(array(
				"NAME" => "redsign_index_mini",
				"MODE" => "N",
				"PROPS" => array(
					0 => array(
						"BANNER_NAME" => GetMessage("DEMO_ADV_RS_INDEX_MINI_1_1_NAME"),
						// "BACKGROUND" => "image",
						// "IMG_FIXED" => "N",
						"LINK_URL" => $arSectionLinks["instrument"],
						"LINK_TITLE" => "",
						"LINK_TARGET" => "_self",
					),
					1 => array(
						"BANNER_NAME" => GetMessage("DEMO_ADV_RS_INDEX_MINI_1_2_NAME"),
						// "BACKGROUND" => "image",
						// "IMG_FIXED" => "N",
						"LINK_URL" => $arSectionLinks["bytovaya-tekhnika"],
						"LINK_TITLE" => "",
						"LINK_TARGET" => "_self",
					),
					2 => array(
						"BANNER_NAME" => GetMessage("DEMO_ADV_RS_INDEX_MINI_1_3_NAME"),
						// "BACKGROUND" => "image",
						// "IMG_FIXED" => "N",
						"LINK_URL" => $arSectionLinks["kompyutery"],
						"LINK_TITLE" => "",
						"LINK_TARGET" => "_self",
					),
				)
			)),
			"TEMPLATE_FILES" => array(
				0 => array(
					"IMG" => Array(
						"name" => "rs_index_mini_1.png",
						"type" => "image/jpg",
						"tmp_name" => $pathToBanner."/rs_index_mini_1.png",
						"error" => "0",
						"size" => @filesize($pathToBanner."/rs_index_mini_1.png"),
						"MODULE_ID" => "advertising"
					)
				),
				1 => array(
					"IMG" => Array(
						"name" => "rs_index_mini_2.png",
						"type" => "image/jpg",
						"tmp_name" => $pathToBanner."/rs_index_mini_2.png",
						"error" => "0",
						"size" => @filesize($pathToBanner."/rs_index_mini_2.png"),
						"MODULE_ID" => "advertising"
					)
				),
				2 => array(
					"IMG" => Array(
						"name" => "rs_index_mini_3.png",
						"type" => "image/jpg",
						"tmp_name" => $pathToBanner."/rs_index_mini_3.png",
						"error" => "0",
						"size" => @filesize($pathToBanner."/rs_index_mini_3.png"),
						"MODULE_ID" => "advertising"
					)
				))
		),
		Array(
			"CONTRACT_ID" => $contractId,
			"TYPE_SID" => "RS_INDEX_MINI",
			"STATUS_SID" => "PUBLISHED",
			"NAME" => GetMessage("DEMO_ADV_RS_INDEX_MINI_2_NAME"),
			"ACTIVE" => "Y",
			"arrSITE" => Array(WIZARD_SITE_ID),
			"WEIGHT"=> 200,
			"FIX_SHOW" => "N",
			"FIX_CLICK" => "Y",
			"AD_TYPE" => "template",
			"STAT_EVENT_1" => "banner",
			"STAT_EVENT_2" => "click",
			"arrWEEKDAY" => $arWeekday,
			"COMMENTS" => GetMessage("DEMO_ADV_RS_INDEX_MINI_2_COMMENTS", array('#SITE_ID#' => WIZARD_SITE_ID)),
			"TEMPLATE" => serialize(array(
				"NAME" => "redsign_index_mini",
				"MODE" => "N",
				"PROPS" => array(
					0 => array(
						"BANNER_NAME" => GetMessage("DEMO_ADV_RS_INDEX_MINI_2_1_NAME"),
						// "BACKGROUND" => "image",
						// "IMG_FIXED" => "N",
						"LINK_URL" => $arSectionLinks["stiralnye-mashiny"],
						"LINK_TITLE" => "",
						"LINK_TARGET" => "_self",
					),
					1 => array(
						"BANNER_NAME" => GetMessage("DEMO_ADV_RS_INDEX_MINI_2_2_NAME"),
						// "BACKGROUND" => "image",
						// "IMG_FIXED" => "N",
						"LINK_URL" => $arSectionLinks["mikrovolnovye-pechi"],
						"LINK_TITLE" => "",
						"LINK_TARGET" => "_self",
					),
					2 => array(
						"BANNER_NAME" => GetMessage("DEMO_ADV_RS_INDEX_MINI_2_3_NAME"),
						// "BACKGROUND" => "image",
						// "IMG_FIXED" => "N",
						"LINK_URL" => $arSectionLinks["mikrovolnovye-pechi"],
						"LINK_TITLE" => "",
						"LINK_TARGET" => "_self",
					),
				)
			)),
			"TEMPLATE_FILES" => array(
				0 => array(
					"IMG" => Array(
						"name" => "rs_index_mini_2_1.jpg",
						"type" => "image/jpg",
						"tmp_name" => $pathToBanner."/rs_index_mini_2_1.jpg",
						"error" => "0",
						"size" => @filesize($pathToBanner."/rs_index_mini_2_1.jpg"),
						"MODULE_ID" => "advertising"
					)
				),
				1 => array(
					"IMG" => Array(
						"name" => "rs_index_mini_2_2.jpg",
						"type" => "image/jpg",
						"tmp_name" => $pathToBanner."/rs_index_mini_2_2.jpg",
						"error" => "0",
						"size" => @filesize($pathToBanner."/rs_index_mini_2_2.jpg"),
						"MODULE_ID" => "advertising"
					)
				),
				2 => array(
					"IMG" => Array(
						"name" => "rs_index_mini_2_3.jpg",
						"type" => "image/jpg",
						"tmp_name" => $pathToBanner."/rs_index_mini_2_3.jpg",
						"error" => "0",
						"size" => @filesize($pathToBanner."/rs_index_mini_2_3.jpg"),
						"MODULE_ID" => "advertising"
					)
				))
		),
		Array(
			"CONTRACT_ID" => $contractId,
			"TYPE_SID" => "RS_OUTER_SIDEBAR",
			"STATUS_SID" => "PUBLISHED",
			"NAME" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_1_NAME"),
			"ACTIVE" => "Y",
			"arrSITE" => Array(WIZARD_SITE_ID),
			"WEIGHT" => 100,
			"FIX_SHOW" => "N",
			"FIX_CLICK" => "Y",
			"AD_TYPE" => "image",

			"URL" => $arSectionLinks["stroyka-i-remont"],
			"IMAGE_ALT" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_1_NAME"),
			"URL_TARGET" => "_blank",
			"arrIMAGE_ID" => Array(
				"name" => "rs_outer_sidebar_1.png",
				"type" => "image/gif",
				"tmp_name" => $pathToBanner."/rs_outer_sidebar_1.png",
				"error" => "0",
				"size" => @filesize($pathToBanner."/rs_outer_sidebar_1.png"),
				"MODULE_ID" => "advertising"
			),

			"STAT_EVENT_1" => "banner",
			"STAT_EVENT_2" => "click",
			"arrWEEKDAY" => $arWeekday,
			"COMMENTS" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_1_COMMENTS", array('#SITE_ID#' => WIZARD_SITE_ID)),
		),
		Array(
			"CONTRACT_ID" => $contractId,
			"TYPE_SID" => "RS_OUTER_SIDEBAR",
			"STATUS_SID" => "PUBLISHED",
			"NAME" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_2_NAME"),
			"ACTIVE" => "Y",
			"arrSITE" => Array(WIZARD_SITE_ID),
			"WEIGHT" => 100,
			"FIX_SHOW" => "N",
			"FIX_CLICK" => "Y",
			"AD_TYPE" => "image",

			"URL" => WIZARD_SITE_DIR."sale-promotions/",
			"IMAGE_ALT" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_2_NAME"),
			"URL_TARGET" => "_blank",
			"arrIMAGE_ID" => Array(
				"name" => "rs_outer_sidebar_2.png",
				"type" => "image/gif",
				"tmp_name" => $pathToBanner."/rs_outer_sidebar_2.png",
				"error" => "0",
				"size" => @filesize($pathToBanner."/rs_outer_sidebar_2.png"),
				"MODULE_ID" => "advertising"
			),

			"STAT_EVENT_1" => "banner",
			"STAT_EVENT_2" => "click",
			"arrWEEKDAY" => $arWeekday,
			"COMMENTS" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_2_COMMENTS", array('#SITE_ID#' => WIZARD_SITE_ID)),
		),
		Array(
			"CONTRACT_ID" => $contractId,
			"TYPE_SID" => "RS_OUTER_SIDEBAR",
			"STATUS_SID" => "PUBLISHED",
			"NAME" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_3_NAME"),
			"ACTIVE" => "Y",
			"arrSITE" => Array(WIZARD_SITE_ID),
			"WEIGHT" => 100,
			"FIX_SHOW" => "N",
			"FIX_CLICK" => "Y",
			"AD_TYPE" => "image",

			"URL" => $arSectionLinks["odezhda-i-obuv"],
			"IMAGE_ALT" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_3_NAME"),
			"URL_TARGET" => "_blank",
			"arrIMAGE_ID" => Array(
				"name" => "rs_outer_sidebar_3.png",
				"type" => "image/gif",
				"tmp_name" => $pathToBanner."/rs_outer_sidebar_3.png",
				"error" => "0",
				"size" => @filesize($pathToBanner."/rs_outer_sidebar_3.png"),
				"MODULE_ID" => "advertising"
			),

			"STAT_EVENT_1" => "banner",
			"STAT_EVENT_2" => "click",
			"arrWEEKDAY" => $arWeekday,
			"COMMENTS" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_3_COMMENTS", array('#SITE_ID#' => WIZARD_SITE_ID)),
		),
		Array(
			"CONTRACT_ID" => $contractId,
			"TYPE_SID" => "RS_OUTER_SIDEBAR",
			"STATUS_SID" => "PUBLISHED",
			"NAME" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_4_NAME"),
			"ACTIVE" => "Y",
			"arrSITE" => Array(WIZARD_SITE_ID),
			"WEIGHT" => 100,
			"FIX_SHOW" => "N",
			"FIX_CLICK" => "Y",
			"AD_TYPE" => "image",

			"URL" => $arSectionLinks["bytovaya-tekhnika"],
			"IMAGE_ALT" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_4_NAME"),
			"URL_TARGET" => "_blank",
			"arrIMAGE_ID" => Array(
				"name" => "rs_outer_sidebar_4.png",
				"type" => "image/gif",
				"tmp_name" => $pathToBanner."/rs_outer_sidebar_4.png",
				"error" => "0",
				"size" => @filesize($pathToBanner."/rs_outer_sidebar_4.png"),
				"MODULE_ID" => "advertising"
			),

			"STAT_EVENT_1" => "banner",
			"STAT_EVENT_2" => "click",
			"arrWEEKDAY" => $arWeekday,
			"COMMENTS" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_4_COMMENTS", array('#SITE_ID#' => WIZARD_SITE_ID)),
		),
		Array(
			"CONTRACT_ID" => $contractId,
			"TYPE_SID" => "RS_OUTER_SIDEBAR",
			"STATUS_SID" => "PUBLISHED",
			"NAME" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_5_NAME"),
			"ACTIVE" => "Y",
			"arrSITE" => Array(WIZARD_SITE_ID),
			"WEIGHT" => 100,
			"FIX_SHOW" => "N",
			"FIX_CLICK" => "Y",
			"AD_TYPE" => "image",

			"URL" => $arSectionLinks["kompyutery"],
			"IMAGE_ALT" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_5_NAME"),
			"URL_TARGET" => "_blank",
			"arrIMAGE_ID" => Array(
				"name" => "rs_outer_sidebar_5.png",
				"type" => "image/gif",
				"tmp_name" => $pathToBanner."/rs_outer_sidebar_5.png",
				"error" => "0",
				"size" => @filesize($pathToBanner."/rs_outer_sidebar_5.png"),
				"MODULE_ID" => "advertising"
			),

			"STAT_EVENT_1" => "banner",
			"STAT_EVENT_2" => "click",
			"arrWEEKDAY" => $arWeekday,
			"COMMENTS" => GetMessage("DEMO_ADV_RS_OUTER_SIDEBAR_5_COMMENTS", array('#SITE_ID#' => WIZARD_SITE_ID)),
		),
	);

	foreach ($arBanners as $arFields)
	{
		$dbResult = CAdvBanner::GetList('', '', Array("COMMENTS" => $arFields["COMMENTS"], "COMMENTS_EXACT_MATCH" => "Y"), null, "N");
		if ($dbResult && $dbResult->Fetch())
			continue;

		CAdvBanner::Set($arFields, "", $CHECK_RIGHTS="N");
	}
}
?>