<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); 

//module include
CModule::IncludeModule("wtc.easydirect");
IncludeModuleLangFile(__FILE__);

//get POST_RIGHT
$POST_RIGHT = $APPLICATION->GetGroupRight("wtc.easydirect");
//Check POST_RIGHT
if ($POST_RIGHT < "R")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

// ******************************************************************** //
//                PREPARE  DATA                          //
// ******************************************************************** //	
//get phrases to check MESTO SEO
if(EDIRECT_YA_XML_ISACTIVE=="Y"){
	$cntCurrentPhrases=CEDirectPhrase::getPhraseToSeoCheck(true);
	$cntAllPhrases=CEDirectPhrase::getPhraseToSeoCheck(true,false);
}

$res=CEDirectLine::GetListEx(array("INSERT_DATE"=>"ASC"));
$vivod=array();
$i=1;
while ($arLine=$res->Fetch()) {
	$vivod[]=array(
	    $i,
		$arLine['COMPANY_NAME'],
		(($arLine['IS_LOCK']=="Y")?GetMessage("EASYDIRECT_LINE_LOCK_YES"):GetMessage("EASYDIRECT_LINE_LOCK_NO")),
		$arLine['INSERT_DATE']
	);
	$i++;
}
// ******************************************************************** //
//               SHOW DATA                                             //
// ******************************************************************** //
// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_LINE_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?
$obTbl=new CEDirectShowTbl(array("N",GetMessage("EASYDIRECT_LINE_tbl1"),GetMessage("EASYDIRECT_LINE_tbl2"),GetMessage("EASYDIRECT_LINE_tbl3")), $vivod);

if(EDIRECT_YA_XML_ISACTIVE=="Y") {
    echo GetMessage("EASYDIRECT_LINE_CNT_MESTO_SEO").$cntCurrentPhrases.", ".GetMessage("EASYDIRECT_LINE_CNT_MESTO_SEO_ALL").$cntAllPhrases."<br>";
}
echo GetMessage("EASYDIRECT_LINE_CNT_COMPANY").count($vivod)."<br><br>";
echo $obTbl->ShowTbl();
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>