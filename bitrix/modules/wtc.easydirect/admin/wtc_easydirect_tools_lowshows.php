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

$res=CEDirectPhrase::GetListEx(Array("COMPANY.ID"=>"ASC","BANNER.ID"=>"ASC"),array("COMPANY.ACTIVE"=>"Y","BANNER_GROUP.SERVING_STATUS"=>"RARELY_SERVED"),array("PHRASE.ID"));
$vivod=array();
$arWords=array();
$prevGBannerID=0;
while($arRes=$res->Fetch()){
    if($arRes["BANNER_GROUP_ID"]!=$prevGBannerID){
        if($prevGBannerID!=0) $vivod[]=$bufVivod;
    	$bufVivod=array(
    		$arRes["COMPANY_NAME"],
    	    '<a target="_blank" href="/bitrix/admin/wtc_easydirect_company_edit.php?ID='.$arRes["COMPANY_ID"].'&GID='.$arRes["BANNER_GROUP_ID"].'&lang=ru#group">'.$arRes["BANNER_GROUP_ID"]." (".$arRes["BANNER_GROUP_NAME"].")</a>",
    	    $arRes["BANNER_TITLE"],
    	    $arRes["NAME"]
    	);
    	$prevGBannerID=$arRes["BANNER_GROUP_ID"];
    }
    else{
        $bufVivod[3].="<br>".$arRes["NAME"];
    }
}
if(count($bufVivod)) $vivod[]=$bufVivod;

//build table
$obTbl=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_LOWCTR_tbl1"),GetMessage("EASYDIRECT_LOWCTR_tbl2"),GetMessage("EASYDIRECT_LOWCTR_tbl3"),GetMessage("EASYDIRECT_LOWCTR_tbl4")), $vivod);

// ******************************************************************** //
//               SHOW DATA                                             //
// ******************************************************************** //
$obTbl->CheckListMode();

// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_LOWCTR_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?
echo $obTbl->ShowTbl(true);
?>
<br>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>