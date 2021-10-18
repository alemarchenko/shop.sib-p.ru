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
//                    SEND  DATA                               //
// ******************************************************************** //
//SAVE & CHANGE params
$curTab=0;
if(isset($_POST["changeParams"])){
    if($_POST["S_CTR"]>0) {
        COption::SetOptionString("wtc.easydirect","tools_lowctr_s_ctr",$_POST["S_CTR"]);
        $curTab=1;
    }
    if($_POST["S_SHOW"]>0) COption::SetOptionString("wtc.easydirect","tools_lowctr_s_show",$_POST["S_SHOW"]);
    if($_POST["RSY_CTR"]>0) {
        COption::SetOptionString("wtc.easydirect","tools_lowctr_rsy_ctr",$_POST["RSY_CTR"]);
        $curTab=2;
    }
    if($_POST["RSY_SHOW"]>0) COption::SetOptionString("wtc.easydirect","tools_lowctr_rsy_show",$_POST["RSY_SHOW"]);
}

//get data
$S_CTR=COption::GetOptionString("wtc.easydirect", "tools_lowctr_s_ctr");
if(!$S_CTR) $S_CTR=5;
$S_SHOW=COption::GetOptionString("wtc.easydirect", "tools_lowctr_s_show");
if(!$S_SHOW) $S_SHOW=30;

$RSY_CTR=COption::GetOptionString("wtc.easydirect", "tools_lowctr_rsy_ctr");
if(!$RSY_CTR) $RSY_CTR=0.5;
$RSY_SHOW=COption::GetOptionString("wtc.easydirect", "tools_lowctr_rsy_show");
if(!$RSY_SHOW) $RSY_SHOW=100;

// ******************************************************************** //
//                PREPARE  DATA                          //
// ******************************************************************** //

//CTR in noRSYA company
$arFilter=array(
		"COMPANY.ACTIVE"=>"Y",
		"COMPANY.IS_RSYA"=>"N",
		"BANNER.ACTIVE"=>"Y",
        ">PHRASE.SHOWS"=>$S_SHOW
);
		
$res=CEDirectPhrase::GetListEx(Array("COMPANY.ID"=>"ASC","BANNER.ID"=>"ASC"),$arFilter,array("PHRASE.ID"));
$vivod=array();
while($arPhrase=$res->Fetch()){
    $CTR=round(($arPhrase["CLICKS"]/$arPhrase["SHOWS"])*100,2);
    if($CTR>$S_CTR) continue;
	$vivod[]=array(
		$arPhrase["COMPANY_NAME"].'<a target="_blank" href="/bitrix/admin/wtc_easydirect_company_edit.php?ID='.$arPhrase["COMPANY_ID"].'&PID='.$arPhrase["ID"].'&lang=ru#phrase"> >></a>',
		$arPhrase["BANNER_TITLE"],
		$arPhrase["NAME"],
		$CTR,
	    $arPhrase["CLICKS"],
	    $arPhrase["SHOWS"]
	);
}
//build table
$obTblSearch=new CEDirectShowTbl(
    array(GetMessage("EASYDIRECT_LOWCTR_tbl1"),GetMessage("EASYDIRECT_LOWCTR_tbl2"),GetMessage("EASYDIRECT_LOWCTR_tbl3"),GetMessage("EASYDIRECT_LOWCTR_tbl4"),GetMessage("EASYDIRECT_LOWCTR_tbl5"),GetMessage("EASYDIRECT_LOWCTR_tbl6")), 
    $vivod, 
    array(), 
    "tblsearch"
    );


//CTR in RSYA company
$arFilter=array(
    "COMPANY.ACTIVE"=>"Y",
    "COMPANY.IS_RSYA"=>"Y",
    "BANNER.ACTIVE"=>"Y",
    ">PHRASE.CONTEXTSHOWS"=>$RSY_SHOW
);

$res=CEDirectPhrase::GetListEx(Array("COMPANY.ID"=>"ASC","BANNER.ID"=>"ASC"),$arFilter,array("PHRASE.ID"));
$vivodRSYA=array();
while($arPhrase=$res->Fetch()){
    $CTR=round(($arPhrase["CONTEXTCLICKS"]/$arPhrase["CONTEXTSHOWS"])*100,2);
    if($CTR>$RSY_CTR) continue;
    $vivodRSYA[]=array(
        $arPhrase["COMPANY_NAME"].'<a target="_blank" href="/bitrix/admin/wtc_easydirect_company_edit.php?ID='.$arPhrase["COMPANY_ID"].'&PID='.$arPhrase["ID"].'&lang=ru#phrase"> >></a>',
        $arPhrase["BANNER_TITLE"],
        $arPhrase["NAME"],
        $CTR,
	    $arPhrase["CONTEXTCLICKS"],
	    $arPhrase["CONTEXTSHOWS"]
    );
}
//build table
$obTblRSYA=new CEDirectShowTbl(
    array(GetMessage("EASYDIRECT_LOWCTR_tbl1"),GetMessage("EASYDIRECT_LOWCTR_tbl2"),GetMessage("EASYDIRECT_LOWCTR_tbl3"),GetMessage("EASYDIRECT_LOWCTR_tbl4"),GetMessage("EASYDIRECT_LOWCTR_tbl5"),GetMessage("EASYDIRECT_LOWCTR_tbl6")), 
    $vivodRSYA,
    array(),
    "tblrsya"
    );


// ******************************************************************** //
//               SHOW DATA                                             //
// ******************************************************************** //
$obTblSearch->CheckListMode();
$obTblRSYA->CheckListMode();

// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_LOWCTR_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>
<?php CJSCore::Init(array("jquery"));?>
<script language="JavaScript">
$.ajaxSetup({cache: false}); 

function showTab(tab){
	countTabs=2;
    for(var i=0;i<=countTabs;i++){
        if(i!=0){
            if(tab==0){
            	$("#divTab"+i).show();
            }
            else {
            	$("#divTab"+i).hide();
            }
        }
    	$("#bTab"+i).removeClass("adm-btn-active");
    }
    if(tab!=0) {$("#divTab"+tab).show();}
    $("#bTab"+tab).addClass("adm-btn-active");
}

$(document).ready(function() {
	showTab(<?=$curTab?>);
});
</script>

<?//================BUTTONS=================== ?>
<div class="adm-list-table-top">
	<a href="#stayhere" onclick="showTab(0)" id="bTab0" class="adm-btn adm-btn-active"><?=GetMessage("EASYDIRECT_LOWCTR_tab0")?></a>
	<a href="#stayhere" onclick="showTab(1)" id="bTab1" class="adm-btn"><?=GetMessage("EASYDIRECT_LOWCTR_tab1")?></a>
	<a href="#stayhere" onclick="showTab(2)" id="bTab2" class="adm-btn"><?=GetMessage("EASYDIRECT_LOWCTR_tab2")?></a>
</div>
<br>


<?//=====SEARCH===========?>
<div id="divTab1">
<?echo "<h3>".GetMessage("EASYDIRECT_LOWCTR_search_tbl_title")."</h3>";?>
<form method="post">
<div>
    <?=GetMessage("EASYDIRECT_LOWCTR_tbl_filter_ctr")?>
    <input type="text" name="S_CTR" size="5" value="<?=$S_CTR?>">
    <?=GetMessage("EASYDIRECT_LOWCTR_tbl_filter_show")?>
    <input type="text" name="S_SHOW" size="5" value="<?=$S_SHOW?>">
    <input type="submit" name="changeParams" value="<?=GetMessage("EASYDIRECT_LOWCTR_tbl_button")?>">
</div>
</form>
<?
echo $obTblSearch->ShowTbl(true);
?>
<br></div>

<?//=====RSYA===========?>
<div id="divTab2">
<?echo "<h3>".GetMessage("EASYDIRECT_LOWCTR_rsya_tbl_title")."</h3>";?>
<form method="post">
<div>
    <?=GetMessage("EASYDIRECT_LOWCTR_tbl_filter_ctr")?>
    <input type="text" name="RSY_CTR" size="5" value="<?=$RSY_CTR?>">
    <?=GetMessage("EASYDIRECT_LOWCTR_tbl_filter_show")?>
    <input type="text" name="RSY_SHOW" size="5" value="<?=$RSY_SHOW?>">
    <input type="submit" name="changeParams" value="<?=GetMessage("EASYDIRECT_LOWCTR_tbl_button")?>">
</div>
</form>
<?
echo $obTblRSYA->ShowTbl(true);
?>
<br></div>


<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>