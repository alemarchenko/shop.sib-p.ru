<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) 2015-2016 The WebTechCom Studio. All Rights Reserved.
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

//error
$rsData =CEDirectLog::GetListError();
$vivod=array();
$rsData->NavStart(20);
while ($arStat=$rsData->Fetch()) {
	$buf=array(
			$arStat['MODIFIED_DATE'],
			$arStat['MESSAGE'],
	);
	if(CEDirectShowTbl::GetColorCSS($arStat['TYPE'])) $buf["Props"]=array("class"=>CEDirectShowTbl::GetColorCSS($arStat['TYPE']));	
	$vivod[]=$buf;
}
$obTblErr=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_LOG_tbl1"),GetMessage("EASYDIRECT_LOG_tbl2")), $vivod);

//all
$arFilter=array();
if(isset($_REQUEST["searchLog"])&&strlen($_REQUEST["searchLog"])>0) {
    $arFilter=array(
        "MESSAGE"=>"%".$_REQUEST["searchLog"]."%"
    );
} 
$rsData =CEDirectLog::GetList(array("MODIFIED_DATE"=>"DESC","ID"=>"DESC"), $arFilter);
$vivod=array();
$rsData->NavStart(50);
while ($arStat=$rsData->Fetch()) {
	$buf=array(
			$arStat['MODIFIED_DATE'],
			$arStat['MESSAGE'],
	);
	if(CEDirectShowTbl::GetColorCSS($arStat['TYPE'])) $buf["Props"]=array("class"=>CEDirectShowTbl::GetColorCSS($arStat['TYPE']));
	$vivod[]=$buf;
}
$obTbl=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_LOG_tbl1"),GetMessage("EASYDIRECT_LOG_tbl2")), $vivod);

// ******************************************************************** //
//                 SHOW DATA                                                 //
// ******************************************************************** //
// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_LOG_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?
	echo GetMessage("EASYDIRECT_LOG_error")."<br>";
	echo $obTblErr->ShowTbl();
?>
<br>
<?echo GetMessage("EASYDIRECT_LOG_alllog")."<br>";?>
<form method="get">
<div>
    <input type="text" name="searchLog" size="20" value="<?=$_REQUEST["searchLog"]?>">
    <input type="submit" name="changeParams" value="<?=GetMessage("EASYDIRECT_LOG_btn_filter_search")?>">
</div>
</form>
<?
	echo $obTbl->ShowTbl();
	if($rsData->IsNavPrint())
	{
		echo "<p>".$rsData->NavPrint(GetMessage("EASYDIRECT_LOG_pages"))."</p>";
	}
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>