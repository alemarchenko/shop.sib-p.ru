<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock"))
	return;

if(COption::GetOptionString("redsign.megamart", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
{
	if($wizard->GetVar('rewriteIndex', true) && $wizard->GetVar('siteLogoSet', true))
	{
		$iblockCode = "redsign_megamart_content_collection_".WIZARD_SITE_ID;
		$iblockType = "adv_banners";

		$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
		$iblockID = false;
		if ($arIBlock = $rsIBlock->Fetch())
		{
            CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/brands/index.php", array("CONTENT_COLLECTION_IBLOCK_ID" => $iblockID));
			CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/header/search_popup.php", array("CONTENT_COLLECTION_IBLOCK_ID" => $iblockID));
		}
	}
	return;
}

$iblockCode = "redsign_megamart_content_collection_".WIZARD_SITE_ID;
$iblockType = "content";

if (in_array(LANGUAGE_ID, array(/*'en', */'ru'))) {
    $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH.'/xml/_all/'.$iblockType.'/collection-'.LANGUAGE_ID.'.xml';
} else {
	$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH.'/xml/_all/'.$iblockType.'/collection-ru.xml';
}

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
$iblockID = false;

if ($arIBlock = $rsIBlock->Fetch())
{
	$iblockID = $arIBlock["ID"];
	if (WIZARD_INSTALL_DEMO_DATA)
	{
		CIBlock::Delete($arIBlock["ID"]);
		$iblockID = false;
	}
}

if($iblockID == false)
{
	$permissions = Array(
		"1" => "X",
		"2" => "R"
	);

	$by = "";
	$order = "";
	$dbGroup = CGroup::GetList('', '', Array("STRING_ID" => "content_editor"));
	if($arGroup = $dbGroup -> Fetch())
	{
		$permissions[$arGroup["ID"]] = 'W';
	};
	$iblockID = WizardServices::ImportIBlockFromXML(
		$iblockXMLFile,
		"redsign_megamart_collection",
		$iblockType,
		WIZARD_SITE_ID,
		$permissions
	);

	if ($iblockID < 1)
		return;

	//IBlock fields
	$iblock = new CIBlock;
	$arFields = Array(
		"ACTIVE" => "Y",
		"FIELDS" => array(
			'IBLOCK_SECTION' => array(
				'IS_REQUIRED' => 'N',
				'DEFAULT_VALUE' => '',
			),
			'ACTIVE' => array(
				'IS_REQUIRED' => 'Y',
				'DEFAULT_VALUE' => 'Y',
			),
			'ACTIVE_FROM' => array(
				'IS_REQUIRED' => 'N',
				'DEFAULT_VALUE' => '=today',
			),
			'ACTIVE_TO' => array(
				'IS_REQUIRED' => 'N',
				'DEFAULT_VALUE' => '',
			),
			'SORT' => array('IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '',),
			'NAME' => array('IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => '',),
			'PREVIEW_PICTURE' => array(
				'IS_REQUIRED' => 'N',
				'DEFAULT_VALUE' => array(
					'FROM_DETAIL' => 'N',
					'SCALE' => 'N',
					'WIDTH' => '',
					'HEIGHT' => '',
					'IGNORE_ERRORS' => 'N',
					'METHOD' => 'resample',
					'COMPRESSION' => 95,
					'DELETE_WITH_DETAIL' => 'N',
					'UPDATE_WITH_DETAIL' => 'N',
				),
			),
			'PREVIEW_TEXT_TYPE' => array('IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text',),
			'PREVIEW_TEXT' => array('IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '',),
			'DETAIL_PICTURE' => array(
				'IS_REQUIRED' => 'N',
				'DEFAULT_VALUE' => array(
					'SCALE' => 'N',
					'WIDTH' => '',
					'HEIGHT' => '',
					'IGNORE_ERRORS' => 'N',
					'METHOD' => 'resample',
					'COMPRESSION' => 95,
				),
			),
			'DETAIL_TEXT_TYPE' => array('IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text',),
			'DETAIL_TEXT' => array('IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '',),
			'XML_ID' => array('IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '',),
			'CODE' => array(
				'IS_REQUIRED' => 'Y',
				'DEFAULT_VALUE' => array(
					'UNIQUE' => 'Y',
					'TRANSLITERATION' => 'Y',
					'TRANS_LEN' => 100,
					'TRANS_CASE' => 'L',
					'TRANS_SPACE' => '-',
					'TRANS_OTHER' => '-',
					'TRANS_EAT' => 'Y',
					'USE_GOOGLE' => 'Y',
				),
			),
			'TAGS' => array('IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '',),
			'SECTION_NAME' => array('IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => '',),
			'SECTION_PICTURE' => array(
				'IS_REQUIRED' => 'N',
				'DEFAULT_VALUE' => array(
					'FROM_DETAIL' => 'N',
					'SCALE' => 'N',
					'WIDTH' => '',
					'HEIGHT' => '',
					'IGNORE_ERRORS' => 'N',
					'METHOD' => 'resample',
					'COMPRESSION' => 95,
					'DELETE_WITH_DETAIL' => 'N',
					'UPDATE_WITH_DETAIL' => 'N',
				),
			),
			'SECTION_DESCRIPTION_TYPE' => array('IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text',),
			'SECTION_DESCRIPTION' => array('IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '',),
			'SECTION_DETAIL_PICTURE' => array(
				'IS_REQUIRED' => 'N',
				'DEFAULT_VALUE' => array(
					'SCALE' => 'N',
					'WIDTH' => '',
					'HEIGHT' => '',
					'IGNORE_ERRORS' => 'N',
					'METHOD' => 'resample',
					'COMPRESSION' => 95,
				),
			),
			'SECTION_XML_ID' => array('IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '',),
			'SECTION_CODE' => array(
				'IS_REQUIRED' => 'N',
				'DEFAULT_VALUE' => array(
					'UNIQUE' => 'N',
					'TRANSLITERATION' => 'N',
					'TRANS_LEN' => 100,
					'TRANS_CASE' => 'L',
					'TRANS_SPACE' => '-',
					'TRANS_OTHER' => '-',
					'TRANS_EAT' => 'Y',
					'USE_GOOGLE' => 'N',
				),
			),
		),
		"CODE" => "collection",
		"XML_ID" => $iblockCode,
		//"NAME" => "[".WIZARD_SITE_ID."] ".$iblock->GetArrayByID($iblockID, "NAME")
	);

	$iblock->Update($iblockID, $arFields);
}
else
{
	$arSites = array();
	$db_res = CIBlock::GetSite($iblockID);
	while ($res = $db_res->Fetch())
		$arSites[] = $res["LID"];
	if (!in_array(WIZARD_SITE_ID, $arSites))
	{
		$arSites[] = WIZARD_SITE_ID;
		$iblock = new CIBlock;
		$iblock->Update($iblockID, array("LID" => $arSites));
	}
}
$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if($arSite = $dbSite -> Fetch())
	$lang = $arSite["LANGUAGE_ID"];
if($lang == '')
	$lang = "ru";

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/brands/index.php", array("CONTENT_COLLECTION_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/header/search_popup.php", array("CONTENT_COLLECTION_IBLOCK_ID" => $iblockID));