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
$arFilter=array(
		"COMPANY.ACTIVE"=>"Y",
		"BANNER.ACTIVE"=>"Y",
        "!BANNER_GROUP.SERVING_STATUS"=>"RARELY_SERVED"
);
		
$res=CEDirectPhrase::GetListEx(Array("COMPANY.ID"=>"ASC","BANNER.ID"=>"ASC"),$arFilter,array("PHRASE.ID"));
$vivod=array();
while($arPhrase=$res->Fetch()){
    $type="";
    $priceNeed=0;
    $price=0;
    $shows=0;
    $CTR=0;
    if($arPhrase["COMPANY_IS_RSYA"]=="N"){
        $price=$arPhrase["PRICE"];
        $shows=$arPhrase["SHOWS"];
        $CTR=round(($arPhrase["CLICKS"]/$arPhrase["SHOWS"])*100,2);
        if($arPhrase["PRICE"]<$arPhrase["MINBET"]&&$arPhrase["PRICE"]<$arPhrase["PREMIUMMIN"]) {$type="searchGar"; $priceNeed=$arPhrase["MINBET"];}
        else if($arPhrase["PRICE"]<$arPhrase["PREMIUMMIN"]) {$type="searchSpec"; $priceNeed=$arPhrase["PREMIUMMIN"];}
    }
    else{
        $price=$arPhrase["CONTEXTPRICE"];
        $PRICES=CEDirectPhrase::UnSerializeArrayField($arPhrase['CONTEXTCOVERAGE']);
        $shows=$arPhrase["CONTEXTSHOWS"];
        $CTR=round(($arPhrase["CONTEXTCLICKS"]/$arPhrase["CONTEXTSHOWS"])*100,2);
        if(count($PRICES)>0){
            $PRICES=CEDirectCalculate::convertCoverageAr($PRICES);
            if($arPhrase["CONTEXTPRICE"]<$PRICES[20]["Price"]) {$type="rsya20"; $priceNeed=$PRICES[20]["Price"];}
            else if($arPhrase["CONTEXTPRICE"]<$PRICES[50]["Price"]) {$type="rsya50"; $priceNeed=$PRICES[50]["Price"];}
            else if($arPhrase["CONTEXTPRICE"]<$PRICES[100]["Price"]) {$type="rsya100"; $priceNeed=$PRICES[100]["Price"];}
        }
    }
    
    if($type){
        $vivod[$type][]=array(
            $arPhrase["COMPANY_NAME"].'<a target="_blank" href="/bitrix/admin/wtc_easydirect_company_edit.php?ID='.$arPhrase["COMPANY_ID"].'&PID='.$arPhrase["ID"].'&lang=ru#phrase"> >></a>',
            $arPhrase["BANNER_TITLE"],
            $arPhrase["NAME"],
            $shows,
            $CTR,
            $price,
            $priceNeed
        );
    }
}

//build tables
$obTblGar=new CEDirectShowTbl(
    array(GetMessage("EASYDIRECT_LOWPRICE_tbl1"),GetMessage("EASYDIRECT_LOWPRICE_tbl2"),GetMessage("EASYDIRECT_LOWPRICE_tbl3"),GetMessage("EASYDIRECT_LOWPRICE_tbl31"),GetMessage("EASYDIRECT_LOWPRICE_tbl32"),GetMessage("EASYDIRECT_LOWPRICE_tbl4"),GetMessage("EASYDIRECT_LOWPRICE_tbl5")), 
    $vivod["searchGar"],
    array(),
    "searchGar"
    );
$obTblSpec=new CEDirectShowTbl(
    array(GetMessage("EASYDIRECT_LOWPRICE_tbl1"),GetMessage("EASYDIRECT_LOWPRICE_tbl2"),GetMessage("EASYDIRECT_LOWPRICE_tbl3"),GetMessage("EASYDIRECT_LOWPRICE_tbl31"),GetMessage("EASYDIRECT_LOWPRICE_tbl32"),GetMessage("EASYDIRECT_LOWPRICE_tbl4"),GetMessage("EASYDIRECT_LOWPRICE_tbl5")), 
    $vivod["searchSpec"],
    array(),
    "searchSpec"
    );
$obTblRsya20=new CEDirectShowTbl(
    array(GetMessage("EASYDIRECT_LOWPRICE_tbl1"),GetMessage("EASYDIRECT_LOWPRICE_tbl2"),GetMessage("EASYDIRECT_LOWPRICE_tbl3"),GetMessage("EASYDIRECT_LOWPRICE_tbl31"),GetMessage("EASYDIRECT_LOWPRICE_tbl32"),GetMessage("EASYDIRECT_LOWPRICE_tbl4"),GetMessage("EASYDIRECT_LOWPRICE_tbl5")), 
    $vivod["rsya20"],
    array(),
    "rsya20"
    );
$obTblRsya50=new CEDirectShowTbl(
    array(GetMessage("EASYDIRECT_LOWPRICE_tbl1"),GetMessage("EASYDIRECT_LOWPRICE_tbl2"),GetMessage("EASYDIRECT_LOWPRICE_tbl3"),GetMessage("EASYDIRECT_LOWPRICE_tbl31"),GetMessage("EASYDIRECT_LOWPRICE_tbl32"),GetMessage("EASYDIRECT_LOWPRICE_tbl4"),GetMessage("EASYDIRECT_LOWPRICE_tbl5")), 
    $vivod["rsya50"],
    array(),
    "rsya50"
    );
$obTblRsya100=new CEDirectShowTbl(
    array(GetMessage("EASYDIRECT_LOWPRICE_tbl1"),GetMessage("EASYDIRECT_LOWPRICE_tbl2"),GetMessage("EASYDIRECT_LOWPRICE_tbl3"),GetMessage("EASYDIRECT_LOWPRICE_tbl31"),GetMessage("EASYDIRECT_LOWPRICE_tbl32"),GetMessage("EASYDIRECT_LOWPRICE_tbl4"),GetMessage("EASYDIRECT_LOWPRICE_tbl5")), 
    $vivod["rsya100"],
    array(),
    "rsya100"
    );


// ******************************************************************** //
//               SHOW DATA                                             //
// ******************************************************************** //
$obTblGar->CheckListMode();
$obTblSpec->CheckListMode();
$obTblRsya20->CheckListMode();
$obTblRsya50->CheckListMode();
$obTblRsya100->CheckListMode();

// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_LOWPRICE_title"));
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
	countTabs=5;
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
</script>

<?//================BUTTONS=================== ?>
<div class="adm-list-table-top">
	<a href="#stayhere" onclick="showTab(0)" id="bTab0" class="adm-btn adm-btn-active"><?=GetMessage("EASYDIRECT_LOWPRICE_tab0")?></a>
	<a href="#stayhere" onclick="showTab(1)" id="bTab1" class="adm-btn"><?=GetMessage("EASYDIRECT_LOWPRICE_tab1")?></a>
	<a href="#stayhere" onclick="showTab(2)" id="bTab2" class="adm-btn"><?=GetMessage("EASYDIRECT_LOWPRICE_tab2")?></a>
	<a href="#stayhere" onclick="showTab(3)" id="bTab3" class="adm-btn"><?=GetMessage("EASYDIRECT_LOWPRICE_tab3")?></a>
	<a href="#stayhere" onclick="showTab(4)" id="bTab4" class="adm-btn"><?=GetMessage("EASYDIRECT_LOWPRICE_tab4")?></a>
	<a href="#stayhere" onclick="showTab(5)" id="bTab5" class="adm-btn"><?=GetMessage("EASYDIRECT_LOWPRICE_tab5")?></a>
</div>
<br>


<?
//=====no enter in guaranty===========
echo '<div id="divTab1">';
echo "<h3>".GetMessage("EASYDIRECT_LOWPRICE_search_gar")."</h3>";
echo $obTblGar->ShowTbl(true);
echo "<br></div>";

//=====no enter in SPEC===========
echo '<div id="divTab2">';
echo "<h3>".GetMessage("EASYDIRECT_LOWPRICE_search_spec")."</h3>";
echo $obTblSpec->ShowTbl(true);
echo "<br></div>";

//=====no enter in RSYA_20===========
echo '<div id="divTab3">';
echo "<h3>".GetMessage("EASYDIRECT_LOWPRICE_RSYA_20")."</h3>";
echo $obTblRsya20->ShowTbl(true);
echo "<br></div>";

//=====no enter in RSYA_50===========
echo '<div id="divTab4">';
echo "<h3>".GetMessage("EASYDIRECT_LOWPRICE_RSYA_50")."</h3>";
echo $obTblRsya50->ShowTbl(true);
echo "<br></div>";

//=====no enter in RSYA_100===========
echo '<div id="divTab5">';
echo "<h3>".GetMessage("EASYDIRECT_LOWPRICE_RSYA_100")."</h3>";
echo $obTblRsya100->ShowTbl(true);
echo "<br></div>";

?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>