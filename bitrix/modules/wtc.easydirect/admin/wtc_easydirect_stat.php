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
//check amcharts version
$amchartsVersion="3.0";
if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/js/main/amcharts/3.13")) $amchartsVersion="3.13";
else if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/js/main/amcharts/3.11")) $amchartsVersion="3.11";

//place to txt array
$arPlaceText=array(
    "11"=>GetMessage("EASYDIRECT_STAT_chart_place_11"),
    "12"=>GetMessage("EASYDIRECT_STAT_chart_place_12"),
    "13"=>GetMessage("EASYDIRECT_STAT_chart_place_13"),
    "14"=>GetMessage("EASYDIRECT_STAT_chart_place_14"),
    "21"=>GetMessage("EASYDIRECT_STAT_chart_place_21"),
    "22"=>GetMessage("EASYDIRECT_STAT_chart_place_22"),
    "23"=>GetMessage("EASYDIRECT_STAT_chart_place_23"),
    "24"=>GetMessage("EASYDIRECT_STAT_chart_place_24"),
    "31"=>GetMessage("EASYDIRECT_STAT_chart_place_31"),
);

//interval date
if($_POST["date_from"]) {$date_from=$_POST["date_from"];}
else {$date_from=ConvertTimeStamp(time()-86400);}
if($_POST["date_to"]) {$date_to=$_POST["date_to"];}
else {$date_to=ConvertTimeStamp(time()+86400);}

//CNT days in interval
$intervalDays=(MakeTimeStamp($date_to, CSite::GetDateFormat("FULL")) - MakeTimeStamp($date_from, CSite::GetDateFormat("FULL")))/86400;
//CNT chart scale
if($intervalDays>2) $chartScale=ceil(((24*60/EDIRECT_STEP_PHRASE_LOG)*$intervalDays)/100);
else $chartScale=1;

//min period(step) in charts
$minPeriod=EDIRECT_STEP_PHRASE_LOG."mm";

$IS_RSYA=false;
$ALL_COMPANY=false;
$typepref="";
$TYPE="";
$NAME="";
$arCharts=array();
$COMPANY_ID=0;

//---------IF PHRASE-------------
if(is_numeric($_GET["phrase"]) && $_GET["phrase"]>0){
    $TYPE="phrase";

    //get phrase data
    $arPhrase =CEDirectPhrase::GetListEx(array(),array("PHRASE.ID"=>$_GET["phrase"]))->Fetch();
    $NAME=$arPhrase["NAME"];
    //IS_RSYA
    $arCompnay=CEDirectCompany::GetByID($arPhrase["COMPANY_ID"])->Fetch();
    if($arCompnay["IS_RSYA"]=="Y") {$IS_RSYA=true; $typepref="_rsya";}
    $COMPANY_ID=$arCompnay["ID"];
    
    //get phrase log
    $rsData =CEDirectPhraseLog::GetList(
        array("CHECK_DATE"=>"ASC"),
        array(
            "ID_PHRASE"=>$_GET["phrase"],
            ">CHECK_DATE"=>$date_from,
            "<CHECK_DATE"=>$date_to
        )
    );
    $i=0;
    while($arLog=$rsData->Fetch()) {
        //pass point in Scale
        $i++;
        if($i!=1){
            if($i<$chartScale) continue;
            else $i=1;
        }
        
        if($IS_RSYA){
            if(strlen($arLog['CONTEXTPRICES'])>2) $PRICES=CEDirectPhraseLog::decodeJson($arLog['CONTEXTPRICES']);
            
            $arCharts["Places"][]=array(
                "date"=>$arLog["CHECK_DATE"],
                "place"=>$arLog["CONTEXTCOVERAGE"],
                "info"=>($arLog["CONTEXTCOVERAGE"]==10?"<20":$arLog["CONTEXTCOVERAGE"])
            );
            $arCharts["CTR"][]=array(
                "date"=>$arLog["CHECK_DATE"],
                "ctr"=>$arLog["CONTEXTCTR"],
                "info"=>GetMessage("EASYDIRECT_STAT_chart_ctr_shows").$arLog["CONTEXTSHOWS"]."<br>".GetMessage("EASYDIRECT_STAT_chart_ctr_clicks").$arLog["CONTEXTCLICKS"]
            );
            $arCharts["Bids"][]=array(
                "date"=>$arLog["CHECK_DATE"],
                "maxprice"=>$arLog["MAX_PRICE"],
                "price"=>$arLog["CONTEXTPRICE"],
                "cov100"=>$PRICES["100"],
                "cov50"=>$PRICES["50"],
                "cov20"=>$PRICES["20"],
            );
        }
        else{
            if(strlen($arLog['SEARCHPRICES'])>2) $PRICES=CEDirectPhraseLog::decodeJson($arLog['SEARCHPRICES']);
            
            $arCharts["Places"][]=array(
                "date"=>$arLog["CHECK_DATE"],
                "place"=>$arLog["SEARCHPLACE"],
                "info"=>$arPlaceText[$arLog["SEARCHPLACE"]]
            );        
            $arCharts["CTR"][]=array(
                "date"=>$arLog["CHECK_DATE"],
                "ctr"=>$arLog["SEARCHCTR"],
                "info"=>GetMessage("EASYDIRECT_STAT_chart_ctr_shows").$arLog["SHOWS"]."<br>".GetMessage("EASYDIRECT_STAT_chart_ctr_clicks").$arLog["CLICKS"]
            );        
            $arCharts["Bids"][]=array(
                "date"=>$arLog["CHECK_DATE"],
                "maxprice"=>$arLog["MAX_PRICE"],
                "price"=>$arLog["PRICE"],
                "premiummax"=>$PRICES["P11"]["Bid"],
                "premiummin"=>($PRICES["P14"]["Bid"]>0?$PRICES["P14"]["Bid"]:$PRICES["P13"]["Bid"]),  //add 4 place to SPEC
                "garmax"=>$PRICES["P21"]["Bid"],
                "garmin"=>$PRICES["P23"]["Bid"]
            );
            $arCharts["Prices"][]=array(
                "date"=>$arLog["CHECK_DATE"],
                "maxprice"=>$arLog["MAX_PRICE"],
                "price"=>$arLog["PRICE_ON_SEARCH"],
                "premiummax"=>$PRICES["P11"]["Price"],
                "premiummin"=>($PRICES["P14"]["Price"]>0?$PRICES["P14"]["Price"]:$PRICES["P13"]["Price"]),  //add 4 place to SPEC
                "garmax"=>$PRICES["P21"]["Price"],
                "garmin"=>$PRICES["P23"]["Price"]
            );        
        }
    }    
}
//---------IF BANNERGROUP or COMPANY-------------
else {
    //-----SET TYPE Log--------------------------
    if(is_numeric($_GET["bannergroup"]) && $_GET["bannergroup"]>0) {
        $TYPE="bannergroup";
        
        //get group data
        $arBannerGroup =CEDirectBannerGroup::GetByID($_GET["bannergroup"])->Fetch();
        
        //IS_RSYA
        $arCompnay=CEDirectCompany::GetByID($arBannerGroup["ID_COMPANY"])->Fetch();
        if($arCompnay["IS_RSYA"]=="Y") {$IS_RSYA=true; $typepref="_rsya";}
        $NAME= $arBannerGroup["NAME"].", ID:".$arBannerGroup["ID"]." ( ".$arCompnay["NAME"]." )";
        $COMPANY_ID=$arCompnay["ID"];
    }
    else if(is_numeric($_GET["company"]) && $_GET["company"]>0){
        $TYPE="company";
        
        //IS_RSYA, company data
        $arCompnay=CEDirectCompany::GetByID($_GET["company"])->Fetch();
        if($arCompnay["IS_RSYA"]=="Y") {$IS_RSYA=true; $typepref="_rsya";}
        $NAME= $arCompnay["NAME"];
        $COMPANY_ID=$arCompnay["ID"];
    }
    else{
        $ALL_COMPANY=true;
        if($_POST["IS_RSYA"]=="Y") $IS_RSYA=true;
        if($IS_RSYA) $NAME=GetMessage("EASYDIRECT_STAT_type_title_allcompany_rsya");        
        else $NAME=GetMessage("EASYDIRECT_STAT_type_title_allcompany");
    }
    
    //--------build filter array---------------------
    $arFilter=array(
        ">CHECK_DATE"=>$date_from,"<CHECK_DATE"=>$date_to
    );
    if($TYPE=="bannergroup")  $arFilter["ID_BANNER_GROUP"]=$_GET["bannergroup"];
    else if($TYPE=="company") $arFilter["ID_COMPANY"]=$_GET["company"];
    
    //-----------get places log   ------------------    
    if($IS_RSYA){
        $arFilter["SEARCHPRICES"]=false;
        $arFilter[">CONTEXTCOVERAGE"]="0";
        $rsData =CEDirectPhraseLog::GetList(
            array("CHECK_DATE"=>"ASC", "CONTEXTCOVERAGE"=>"ASC"),
            $arFilter,
            array("CHECK_DATE","CONTEXTCOVERAGE"),
            array("CHECK_DATE","CONTEXTCOVERAGE","COUNT(*) AS CNT")
        );
        
        $i=1;
        $prevCHECK_DATE=false;
        $arLogInfo=array();
        while($arLog=$rsData->Fetch()) {
            //pass point in Scale
            if($prevCHECK_DATE==false) $prevCHECK_DATE=$arLog["CHECK_DATE"];
            if($prevCHECK_DATE!=$arLog["CHECK_DATE"]) {
                $i++; 
                $prevCHECK_DATE=$arLog["CHECK_DATE"];
            }
            if($i!=1){
                if($i<$chartScale) continue;
                else $i=1;
            }
            
            //write data
            unset($arLog["CHECK_DATE"]);
            $arLogInfo[$prevCHECK_DATE][]=$arLog;
        }
        
        //parse data array
        foreach ($arLogInfo as $key=>$val){
            $arPlaces=array(
                "date"=>$key   
            );
            foreach ($val as $info){
                $arPlaces[$info["CONTEXTCOVERAGE"]]=$info["CNT"];
            }
            $arCharts["Places"][]=$arPlaces;
        }
    }
    else {
        $arFilter["CONTEXTPRICES"]=false;        
        $rsData =CEDirectPhraseLog::GetList(
            array("CHECK_DATE"=>"ASC", "SEARCHPLACE"=>"ASC"),
            $arFilter,
            array("CHECK_DATE","SEARCHPLACE"),
            array("CHECK_DATE","SEARCHPLACE","COUNT(*) AS CNT")
        );
        
        $i=1;
        $prevCHECK_DATE=false;
        $arLogInfo=array();
        while($arLog=$rsData->Fetch()) {
            //pass point in Scale
            if($prevCHECK_DATE==false) $prevCHECK_DATE=$arLog["CHECK_DATE"];
            if($prevCHECK_DATE!=$arLog["CHECK_DATE"]) {
                $i++;
                $prevCHECK_DATE=$arLog["CHECK_DATE"];
            }
            if($i!=1){
                if($i<$chartScale) continue;
                else $i=1;
            }
        
            //write data
            unset($arLog["CHECK_DATE"]);
            $arLogInfo[$prevCHECK_DATE][]=$arLog;
        }
        
        //parse data array
        foreach ($arLogInfo as $key=>$val){
            $arPlaces=array(
                "date"=>$key,
                "spec"=>0,
                "gar"=>0,
                "nogar"=>0,
                "infospec"=>"",
                "infogar"=>"",
                "infonogar"=>""
            );
            foreach ($val as $info){
                if($info["SEARCHPLACE"]<20) {
                    $arPlaces["spec"]+=$info["CNT"];
                    if($arPlaces["infospec"]!="") $arPlaces["infospec"].="<br>";
                    $arPlaces["infospec"].=$arPlaceText[$info["SEARCHPLACE"]]." - ".$info["CNT"].GetMessage("EASYDIRECT_STAT_chart_place_sht");
                }
                else if($info["SEARCHPLACE"]<30) {
                    $arPlaces["gar"]+=$info["CNT"];
                    if($arPlaces["infogar"]!="") $arPlaces["infogar"].="<br>";
                    $arPlaces["infogar"].=$arPlaceText[$info["SEARCHPLACE"]]." - ".$info["CNT"].GetMessage("EASYDIRECT_STAT_chart_place_sht");
                }
                else {
                    $arPlaces["nogar"]+=$info["CNT"];
                }
            }
        
            if($arPlaces["nogar"]>0) $arPlaces["infonogar"].=$arPlaces["nogar"].GetMessage("EASYDIRECT_STAT_chart_place_sht");
        
            $arCharts["Places"][]=$arPlaces;
        }        
    }

    //-----------get CTR log   ------------------
    /*$rsData =CEDirectPhraseLog::GetList(
        array("CHECK_DATE"=>"ASC"),
        $arFilter,
        array("CHECK_DATE"),
        array("CHECK_DATE","SUM(SHOWS) AS SHOWS","SUM(CLICKS) AS CLICKS")
    );
    
    $i=1;
    while($arLog=$rsData->Fetch()) {
        //pass point in Scale
        $i++;
        if($i!=1){
            if($i<$chartScale) continue;
            else $i=1;
        }
                
        $arCharts["CTR"][]=array(
            "date"=>$arLog["CHECK_DATE"],
            "ctr"=>($arLog['SHOWS']?round(($arLog['CLICKS']*100)/$arLog['SHOWS'],2):0),
            "info"=>GetMessage("EASYDIRECT_STAT_chart_ctr_shows").$arLog["SHOWS"]."<br>".GetMessage("EASYDIRECT_STAT_chart_ctr_clicks").$arLog["CLICKS"]
        );        
    }*/    
}

//==================CHART MAIN SETTINGS=============
$chartMainSettings='
        	"path": "/bitrix/js/main/amcharts/'.$amchartsVersion.'/",
        	"pathToImages": "/bitrix/js/main/amcharts/'.$amchartsVersion.'/images/",
            "theme": "light",
            "balloon": {
                "fillAlpha": 1
            },
            "chartCursor": {
            	"enabled": true,
                "categoryBalloonDateFormat": "DD/HH:NN",
                "cursorAlpha": 0.2,
                "graphBulletAlpha": 1,
                "cursorPosition": "middle"
            },
            "dataDateFormat": "YYYY-MM-DD HH:NN:SS",
            "chartScrollbar": {
                "graph":"g1",
                "gridAlpha":0,
                "color":"#888888",
                "scrollbarHeight":55,
                "backgroundAlpha":0,
                "selectedBackgroundAlpha":0.1,
                "selectedBackgroundColor":"#888888",
                "graphFillAlpha":0,
                "autoGridCount":true,
                "selectedGraphFillAlpha":0,
                "graphLineAlpha":1,
                "graphLineColor":"#c2c2c2",
                "selectedGraphLineColor":"#888888",
                "selectedGraphLineAlpha":1
            }    
';

// ******************************************************************** //
//                 SHOW DATA                                                 //
// ******************************************************************** //
// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_STAT_title")." ".GetMessage("EASYDIRECT_STAT_type_title_".$TYPE).": ".$NAME);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?if($COMPANY_ID>0): ?>
<a href="/bitrix/admin/wtc_easydirect_company_edit.php?ID=<?=$COMPANY_ID?>"><< <?=GetMessage("EASYDIRECT_STAT_back_to_company")?></a><br>
<?endif; ?>
<br>

<div class="adm-list-table-top">
<form name="intForm" method="post">
<table><tr>
    <td><?=GetMessage("EASYDIRECT_STAT_filter_date")?>&nbsp;&nbsp;</td>
    <td><?=CalendarPeriod("date_from", $date_from, "date_to", $date_to, "intForm","N","",'',12)?></td>
    <?if($ALL_COMPANY){?>
        <td>&nbsp;&nbsp;		
        <select name="IS_RSYA">
			<option value="N"><?=GetMessage("EASYDIRECT_STAT_select_search")?></option>
			<option value="Y" <?=($_POST["IS_RSYA"]=="Y"?"selected":"")?>><?=GetMessage("EASYDIRECT_STAT_select_rsya")?></option>
		</select>
		</td>
    <?}?>
    <td>&nbsp;&nbsp;<input type="submit" name="changeParams" value="<?=GetMessage("EASYDIRECT_STAT_filter_button")?>"></td>
</tr></table>
</form>
</div>
<br>

<!-- Resources -->
<script src="/bitrix/js/main/amcharts/<?=$amchartsVersion?>/amcharts.js"></script>
<script src="/bitrix/js/main/amcharts/<?=$amchartsVersion?>/serial.js"></script>
<script src="/bitrix/js/main/amcharts/<?=$amchartsVersion?>/themes/light.js"></script>

<!--================ ========== ============================ -->
<!--================ Chart Places code ============================ -->
<!--================ ========== ============================ -->
<?if($TYPE=="phrase"){?>
<script>
var chartDataPlaces =[
                <? 
        		foreach ($arCharts["Places"] as $point){
        		    echo "{";
        		    foreach ($point as $key=>$val){
                		   echo '"'.$key.'": "'.$val.'",';
        		    }
        		    echo "},";
        		}
        		?>
        ];

        AmCharts.makeChart("chartPlaces", {
            "type": "serial", 
            "categoryField": "date",
            "titles":[{"text":"<?=GetMessage("EASYDIRECT_STAT_chart_place_title".$typepref)?>"}],
            "dataProvider": chartDataPlaces,
            "graphs": [{
                "valueField": "place",
                "type": "step",
                "id":"g1",
                "balloonText": "[[title]]: <?=($IS_RSYA?"[[info]]%":"[[info]] ([[value]])") ?>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_place_title".$typepref)?>",
            	"lineColor": "blue",
                "lineThickness": 2,
                "bullet":"square",
                "bulletAlpha":1,
                "bulletSize":4,
                "bulletBorderAlpha":0
            }],
            "categoryAxis": {
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_x_title")?>",
                "minPeriod": "<?=$minPeriod?>",
                "parseDates": true,
                "gridAlpha": 0,
                //"labelRotation": 90
                "dateFormats": [{"period":"fff","format":"JJ:NN:SS"},{"period":"ss","format":"JJ:NN:SS"},{"period":"mm","format":"DD/JJ:NN"},{"period":"hh","format":"DD/JJ:NN"},{"period":"DD","format":"MMM DD"},{"period":"WW","format":"MMM DD"},{"period":"MM","format":"MMM"},{"period":"YYYY","format":"YYYY"}],
                "markPeriodChange": false,
            },
            "valueAxes": [{
                "position": "left",
                "reversed": <?=($IS_RSYA?"false":"true")?>,
                "title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_y_title".$typepref)?>",
            }],    
            <?=$chartMainSettings ?>
        });
</script>
<div id="chartPlaces" style="width: 100%; height	: 300px; background: #ffffff; border: 1px #adadad solid;"></div><br>
<?}else if($IS_RSYA){?>
<script>
var chartDataPlaces =[
                <? 
        		foreach ($arCharts["Places"] as $point){
        		    echo "{";
        		    foreach ($point as $key=>$val){
                		   echo '"'.$key.'": "'.$val.'",';
        		    }
        		    echo "},";
        		}
        		?>
        ];

        AmCharts.makeChart("chartPlaces", {
            "type": "serial", 
            "categoryField": "date",
            "titles":[{"text":"<?=GetMessage("EASYDIRECT_STAT_chart_place_title_rsya")?>"}],
            "dataProvider": chartDataPlaces,
            "graphs": [{
                "valueField": "100",
                "type": "column",
                "id":"g1",
                "balloonText": "[[title]] - [[value]] <?=GetMessage("EASYDIRECT_STAT_chart_place_sht")?>",
            	"title": "100%",
            	"lineColor": "green",
            	"fillAlphas": 1,
            },
            {
                "valueField": "50",
                "type": "column",
                "id":"g2",
                "balloonText": "[[title]] - [[value]] <?=GetMessage("EASYDIRECT_STAT_chart_place_sht")?>",
            	"title": "50%",
            	"lineColor": "#d59e21",
            	"fillAlphas": 1,
            },
            {
                "valueField": "20",
                "type": "column",
                "id":"g3",
                "balloonText": "[[title]] - [[value]] <?=GetMessage("EASYDIRECT_STAT_chart_place_sht")?>",
            	"title": "20%",
            	"lineColor": "#000",
            	"fillAlphas": 1,
            },
            {
                "valueField": "10",
                "type": "column",
                "id":"g4",
                "balloonText": "[[title]] - [[value]] <?=GetMessage("EASYDIRECT_STAT_chart_place_sht")?>",
            	"title": "<20%",
            	"lineColor": "#d61f4e",
            	"fillAlphas": 1,
            }        
            ],
            "categoryAxis": {
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_x_title")?>",
                "minPeriod": "<?=$minPeriod?>",
                "parseDates": true,
                "gridAlpha": 0,
                "dateFormats": [{"period":"fff","format":"JJ:NN:SS"},{"period":"ss","format":"JJ:NN:SS"},{"period":"mm","format":"DD/JJ:NN"},{"period":"hh","format":"DD/JJ:NN"},{"period":"DD","format":"MMM DD"},{"period":"WW","format":"MMM DD"},{"period":"MM","format":"MMM"},{"period":"YYYY","format":"YYYY"}],
                "markPeriodChange": false,
            },
            "valueAxes": [{
                "position": "left",
                "title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_group_y_title")?>",
            }],    
        	"legend": { 
        		"enabled": true,
        		"useGraphSettings": true
        	},              
            <?=$chartMainSettings ?>
        });
</script>
<div id="chartPlaces" style="width: 100%; height	: 500px; background: #ffffff; border: 1px #adadad solid;"></div><br>
<?} else {?>
<script>
var chartDataPlaces =[
                <? 
        		foreach ($arCharts["Places"] as $point){
        		    echo "{";
        		    foreach ($point as $key=>$val){
                		   echo '"'.$key.'": "'.$val.'",';
        		    }
        		    echo "},";
        		}
        		?>
        ];

        AmCharts.makeChart("chartPlaces", {
            "type": "serial", 
            "categoryField": "date",
            "titles":[{"text":"<?=GetMessage("EASYDIRECT_STAT_chart_place_title")?>"}],
            "dataProvider": chartDataPlaces,
            "graphs": [{
                "valueField": "spec",
                "type": "column",
                "id":"g1",
                "balloonText": "[[infospec]]",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_group_spec")?>",
            	"lineColor": "green",
            	"fillAlphas": 1,
            },
            {
                "valueField": "gar",
                "type": "column",
                "id":"g2",
                "balloonText": "[[infogar]]",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_group_gar")?>",
            	"lineColor": "#d59e21",
            	"fillAlphas": 1,
            },
            {
                "valueField": "nogar",
                "type": "column",
                "id":"g3",
                "balloonText": "[[title]]:[[infonogar]]",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_group_nogar")?>",
            	"lineColor": "#d61f4e",
            	"fillAlphas": 1,
            }            
            ],
            "categoryAxis": {
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_x_title")?>",
                "minPeriod": "<?=$minPeriod?>",
                "parseDates": true,
                "gridAlpha": 0,
                "dateFormats": [{"period":"fff","format":"JJ:NN:SS"},{"period":"ss","format":"JJ:NN:SS"},{"period":"mm","format":"DD/JJ:NN"},{"period":"hh","format":"DD/JJ:NN"},{"period":"DD","format":"MMM DD"},{"period":"WW","format":"MMM DD"},{"period":"MM","format":"MMM"},{"period":"YYYY","format":"YYYY"}],
                "markPeriodChange": false,
            },
            "valueAxes": [{
                "position": "left",
                "title": "<?=GetMessage("EASYDIRECT_STAT_chart_place_group_y_title")?>",
            }],    
        	"legend": { 
        		"enabled": true,
        		"useGraphSettings": true
        	},              
            <?=$chartMainSettings ?>
        });
</script>
<div id="chartPlaces" style="width: 100%; height	: 500px; background: #ffffff; border: 1px #adadad solid;"></div><br>
<?}?>

<?if($TYPE=="phrase"){?>
<!--================ Chart CTR code ============================ -->
<script>
var chartDataCTR =[
                <? 
        		foreach ($arCharts["CTR"] as $point){
        		    echo "{";
        		    foreach ($point as $key=>$val){
                		   echo '"'.$key.'": "'.$val.'",';
        		    }
        		    echo "},";
        		}
        		?>
        ];

        AmCharts.makeChart("chartCTR", {
            "type": "serial", 
            "categoryField": "date",
            "titles":[{"text":"<?=GetMessage("EASYDIRECT_STAT_chart_ctr_title")?>"}],
            "dataProvider": chartDataCTR,
            "graphs": [{
                "valueField": "ctr",  
                "type": "step",
                "id":"g1",
                "balloonText": "[[title]]:<b>[[value]]</b><br>[[info]]",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_ctr_ctr_title")?>",
            	"lineColor": "blue",
                "lineThickness": 2,
                "bullet":"square",
                "bulletAlpha":1,
                "bulletSize":4,
                "bulletBorderAlpha":0
            }],
            "categoryAxis": {
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_ctr_x_title")?>",
                "minPeriod": "<?=$minPeriod?>",
                "parseDates": true,
                "gridAlpha": 0,
                //"labelRotation": 90
                "dateFormats": [{"period":"fff","format":"JJ:NN:SS"},{"period":"ss","format":"JJ:NN:SS"},{"period":"mm","format":"DD/JJ:NN"},{"period":"hh","format":"DD/JJ:NN"},{"period":"DD","format":"MMM DD"},{"period":"WW","format":"MMM DD"},{"period":"MM","format":"MMM"},{"period":"YYYY","format":"YYYY"}],
                "markPeriodChange": false,
            },
            "valueAxes": [{
                "position": "left",
                "title": "<?=GetMessage("EASYDIRECT_STAT_chart_ctr_y_title")?>",
            }],    
            <?=$chartMainSettings ?>
        });
</script>
<div id="chartCTR" style="width: 100%; height	: 300px; background: #ffffff; border: 1px #adadad solid;"></div><br>

<!--================ Chart Bids code ============================ -->
<script>
var chartDataBids =[
                <? 
        		foreach ($arCharts["Bids"] as $point){
        		    echo "{";
        		    foreach ($point as $key=>$val){
                		   echo '"'.$key.'": "'.$val.'",';
        		    }
        		    echo "},";
        		}
        		?>
        ];

        AmCharts.makeChart("chartBids", {
            "type": "serial", 
            "categoryField": "date",
            "titles":[{"text":"<?=GetMessage("EASYDIRECT_STAT_chart_bid_title")?>"}],
            "dataProvider": chartDataBids,
            "graphs": [{
                "valueField": "price",  
                "type": "step",
                "id":"g1",
                "balloonText": "[[title]]:<b>[[value]] </b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_bid_title")?>",
            	"lineColor": "blue",
                "lineThickness": 2,
                "bullet":"square",
                "bulletAlpha":1,
                "bulletSize":4,
                "bulletBorderAlpha":0
            },
            <?if($IS_RSYA){?>
        	{
                "valueField": "cov100",
                "type": "step",
                "id":"g2",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_cov100_title")?>",        
                "lineThickness": 1,
                "lineColor": "green"
        	},
        	{
                "valueField": "cov50",
                "type": "step",
                "id":"g3",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_cov50_title")?>",        
                "lineThickness": 1,
                "lineColor": "#d59e21"
        	},
        	{
                "valueField": "cov20",
                "type": "step",
                "id":"g4",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_cov20_title")?>",        
                "lineThickness": 1,
                "lineColor": "#d61f4e"
        	},            
            <?}else{ ?>
        	{
                "valueField": "premiummax",
                "type": "step",
                "id":"g2",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_premiummax_title")?>",        
                "lineThickness": 1,
                "lineColor": "#d59e21"
        	},
        	{
                "valueField": "premiummin",
                "type": "step",
                "id":"g3",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_premiummin_title")?>",        
                "lineThickness": 1,
                "lineColor": "green"
        	},
        	{
                "valueField": "garmax",
                "type": "step",
                "id":"g4",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_garmax_title")?>",        
                "lineThickness": 1,
                "lineColor": "#000000"
        	},
        	{
                "valueField": "garmin",
                "type": "step",
                "id":"g5",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_garmin_title")?>",        
                "lineThickness": 1,
                "lineColor": "#d61f4e"
        	},
        	<?}?>
        	{
                "valueField": "maxprice",
                "type": "step",
                "id":"g6",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_maxprice_title")?>",        
                "lineThickness": 1,
                "lineColor": "#868686"
        	}        	
            ],
            "categoryAxis": {
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_x_title")?>",
                "minPeriod": "<?=$minPeriod?>",
                "parseDates": true,
                "gridAlpha": 0,
                //"labelRotation": 90
                "dateFormats": [{"period":"fff","format":"JJ:NN:SS"},{"period":"ss","format":"JJ:NN:SS"},{"period":"mm","format":"DD/JJ:NN"},{"period":"hh","format":"DD/JJ:NN"},{"period":"DD","format":"MMM DD"},{"period":"WW","format":"MMM DD"},{"period":"MM","format":"MMM"},{"period":"YYYY","format":"YYYY"}],
                "markPeriodChange": false,
            },
            "valueAxes": [{
                "position": "left",
                "title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_y_title")?>",
            }],
        	"legend": { 
        		"enabled": true,
        		"useGraphSettings": true
        	},    
            <?=$chartMainSettings ?>
        });
</script>
<div id="chartBids" style="width	: 100%; height	: 500px; background: #ffffff; border: 1px #adadad solid;"></div><br>

<!--================ Chart PRICES code ============================ -->
<?if(!$IS_RSYA){?>
<script>
var chartDataPrices =[
                <? 
        		foreach ($arCharts["Prices"] as $point){
        		    echo "{";
        		    foreach ($point as $key=>$val){
                		   echo '"'.$key.'": "'.$val.'",';
        		    }
        		    echo "},";
        		}
        		?>
        ];

        AmCharts.makeChart("chartPrices", {
            "type": "serial", 
            "categoryField": "date",
            "titles":[{"text":"<?=GetMessage("EASYDIRECT_STAT_chart_price_title")?>"}],
            "dataProvider": chartDataPrices,
            "graphs": [{
                "valueField": "price",  
                "type": "step",
                "id":"g1",
                "balloonText": "[[title]]:<b>[[value]] </b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_price_title")?>",
            	"lineColor": "blue",
                "lineThickness": 2,
                "bullet":"square",
                "bulletAlpha":1,
                "bulletSize":4,
                "bulletBorderAlpha":0
            },
        	{
                "valueField": "premiummax",
                "type": "step",
                "id":"g2",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_premiummax_title")?>",        
                "lineThickness": 1,
                "lineColor": "#d59e21"
        	},
        	{
                "valueField": "premiummin",
                "type": "step",
                "id":"g3",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_premiummin_title")?>",        
                "lineThickness": 1,
                "lineColor": "green"
        	},
        	{
                "valueField": "garmax",
                "type": "step",
                "id":"g4",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_garmax_title")?>",        
                "lineThickness": 1,
                "lineColor": "#000000"
        	},
        	{
                "valueField": "garmin",
                "type": "step",
                "id":"g5",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_garmin_title")?>",        
                "lineThickness": 1,
                "lineColor": "#d61f4e"
        	},
        	{
                "valueField": "maxprice",
                "type": "step",
                "id":"g6",
        		"balloonText": "[[title]]:<b>[[value]]</b>",
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_maxprice_title")?>",        
                "lineThickness": 1,
                "lineColor": "#868686"
        	}        	
            ],
            "categoryAxis": {
            	"title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_x_title")?>",
                "minPeriod": "<?=$minPeriod?>",
                "parseDates": true,
                "gridAlpha": 0,
                //"labelRotation": 90
                "dateFormats": [{"period":"fff","format":"JJ:NN:SS"},{"period":"ss","format":"JJ:NN:SS"},{"period":"mm","format":"DD/JJ:NN"},{"period":"hh","format":"DD/JJ:NN"},{"period":"DD","format":"MMM DD"},{"period":"WW","format":"MMM DD"},{"period":"MM","format":"MMM"},{"period":"YYYY","format":"YYYY"}],
                "markPeriodChange": false,
            },
            "valueAxes": [{
                "position": "left",
                "title": "<?=GetMessage("EASYDIRECT_STAT_chart_price_y_title")?>",
            }],    
        	"legend": { 
        		"enabled": true,
        		"useGraphSettings": true
        	},
            <?=$chartMainSettings ?>
        });
</script>
<div id="chartPrices" style="width	: 100%; height	: 500px; background: #ffffff; border: 1px #adadad solid;"></div><br>
<?}?>

<?}?>
<!--================ ========== ============================ -->

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>