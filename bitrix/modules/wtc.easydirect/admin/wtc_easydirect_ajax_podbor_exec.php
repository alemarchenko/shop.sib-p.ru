<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");

//module include
CModule::IncludeModule("wtc.easydirect");
IncludeModuleLangFile(__FILE__);
header('Content-Type: text/html; charset='.LANG_CHARSET);
?>

<?
$return=array();
//---------------addMinus---------------
if($_POST["CMD"]=="addMinus"){
        if(!EDIRECT_UTFSITE) $_POST["NAME"]=iconv("utf-8", "windows-1251", $_POST["NAME"]); //if site isn't in UTF8, Convert string
        $arFields=array(
            "NAME" => trim($_POST["NAME"]),
            "TYPE" => "M"
        );
        $ID=CEDirectPodborPhrases::Add($arFields);
        if($ID) {
            $return=array(
                "ERROR"=>0,
                "APPEND_STR"=>'<tr><td>-</td><td><a class="del-phrase" id="phrase'.$ID.'" onclick="delPhrase('.$ID.')" href="#stayhere">&nbsp;X&nbsp;</a></td><td>'.$_POST["NAME"].'</td><td><a class="edit-phrase" onclick="editPhrase('.$ID.',true)" href="#stayhere">&nbsp;E&nbsp;</a></td><td><input type="checkbox" class="delchecked" name="delPhrases[]" value="'.$ID.'"></td></tr>'
            );
        }
}
//---------------delPhrase---------------
else if($_POST["CMD"]=="delPhrase"){
    if(CEDirectPodborPhrases::Delete($_POST["ID"]))
    {
        $return=array("ERROR"=>0,"ID"=>$_POST["ID"]);
    }
}
//---------------delPhrase---------------
else if($_POST["CMD"]=="delAllMinusPhrases"){
    $rsData =CEDirectPodborPhrases::GetList(array("ID"=>"ASC"), array("TYPE"=>"M"));
    while ($arPhrase=$rsData->Fetch()) {
        CEDirectPodborPhrases::Delete($arPhrase["ID"]);
    }
    $return=array("ERROR"=>0);
}
//---------------editPhrase---------------
else if($_POST["CMD"]=="editPhrase"){
    if($_POST["ISSHOW"]){
        $res=CEDirectPodborPhrases::GetByID($_POST["ID"]);
        $arPhrase=$res->Fetch();
        $additFields="";
        if($arPhrase["TYPE"]=="S") $additFields='<td><input type="text" id="editShows'.$arPhrase['ID'].'" size="3" value="'.$arPhrase['SHOWS'].'"></td><td><input type="text" id="editShowsQuotes'.$arPhrase['ID'].'" size="3" value="'.$arPhrase['SHOWS_QUOTES'].'"></td>';
        
        $return=array(
            "ERROR"=>0,
            "APPEND_STR"=>'<td colspan="2">-</td><td><input id="editName'.$arPhrase['ID'].'" type="text" size="30" value="'.$arPhrase['NAME'].'"></td>'.$additFields.'<td colspan="2"><a class="adm-btn" id="phrase'.$arPhrase['ID'].'" onclick="editPhrase('.$arPhrase['ID'].')">'.GetMessage("EASYDIRECT_PODBOR_EXEC_save").'</a></td>',
            "ID"=>$_POST["ID"]
        );
    }
    else {
        if(!EDIRECT_UTFSITE) $_POST["NAME"]=iconv("utf-8", "windows-1251", $_POST["NAME"]); //if site isn't in UTF8, Convert string
        $arFields=array(
            "NAME" => $_POST["NAME"],
            "SHOWS" => ($_POST["SHOWS"]?$_POST["SHOWS"]:0),
            "SHOWS_QUOTES" => ($_POST["SHOWS_QUOTES"]?$_POST["SHOWS_QUOTES"]:0)
        );        
        if(CEDirectPodborPhrases::Update($_POST["ID"],$arFields)){
            $res=CEDirectPodborPhrases::GetByID($_POST["ID"]);
            $arPhrase=$res->Fetch();
            $additFields="";
            if($arPhrase["TYPE"]=="S") $additFields='<td>'.$_POST["SHOWS"].'</td><td>'.$_POST["SHOWS_QUOTES"].'</td>';            

            $return=array(
                "ERROR"=>0,
                "APPEND_STR"=>'<td>-</td><td><a class="del-phrase" id="phrase'.$_POST['ID'].'" onclick="delPhrase('.$_POST["ID"].')" href="#stayhere">&nbsp;X&nbsp;</a></td><td>'.$_POST["NAME"].'</td>'.$additFields.'<td><a class="edit-phrase" onclick="editPhrase('.$_POST["ID"].',true)" href="#stayhere">&nbsp;E&nbsp;</a></td><td><input class="delchecked" name="delPhrases[]" value="'.$_POST["ID"].'" type="checkbox"></td>',
                "ID"=>$_POST["ID"]
            );        
        }
        else {
            $return=array(
                "ERROR"=>1,
                "ERROR_TXT"=>GetMessage("EASYDIRECT_PODBOR_EXEC_not_save_error"),
            );            
        }
    }
}
//---------------clearAll---------------
else if($_POST["CMD"]=="clearAll"){
    //Delete all phrases
    $rsData =CEDirectPodborPhrases::GetList(array("ID"=>"ASC"));
    while ($arPhrase=$rsData->Fetch()) {
        CEDirectPodborPhrases::Delete($arPhrase["ID"]);
    }
    //Delete all Report
    CEDirectPodborReports::DeleteAll();
}
//---------------clearReportsOnly--------
else if($_POST["CMD"]=="clearReports"){
    CEDirectPodborReports::DeleteAll();
    $return=array(
        "CHECK_QUOTES"=>$_POST["CHECK_QUOTES"]
    );    
}
//---------------getWsreport---------------
else if($_POST["CMD"]=="getWsreport"){
    $ERROR=0;
    $ERROR_TXT="";
    
    $CNT=CEDirectPodborReports::getCnt();
    
    // create new reports in BD if CNT==0
    if($CNT==0) {
        //create quotes report
        if($_POST["CHECK_QUOTES"]) {
            //also check all not Quotes words
            CEDirectPodborReports::createWsreport();
            CEDirectPodborReports::createWsreport(true);
        }
        //create normal report
        else CEDirectPodborReports::createWsreport();
    }
    //check DONE reports
    else{
        $reportList=$obYaExchange->getWsreportList();
        foreach ($reportList as $report)
        {
            //if isset report and DONE
            if( $report['StatusReport']=="Done" && CEDirectPodborReports::IsEmpty(CEDirectPodborReports::getIDByYandexID($report['ReportID'])) )
            {
                //get DATA report
                $arReport=$obYaExchange->getWsreport($report['ReportID']);
                foreach ($arReport as $arPhraseReport){
                    //--GET PHRASE SORT--
                    $SORT=500;
                    $res=CEDirectPodborPhrases::GetList(Array(), Array("NAME"=>str_replace('"', '', $arPhraseReport['Phrase']),"TYPE"=>"S"));
                    if($arPhrase=$res->Fetch()){
                        $SORT=$arPhrase["SORT"];
                    }
                    //-----
                    
                    foreach ($arPhraseReport['SearchedWith'] as $phrase)
                    {
                        $res=CEDirectPodborPhrases::GetList(Array(), Array("NAME"=>str_replace('"', '', $phrase['Phrase']),"TYPE"=>"S"));
                        if($arPhrase=$res->Fetch()) {
                            //if get QUOTES phrase update SHOWS_QUOTES
                            if( strpos($phrase['Phrase'], '"')!==false){
                                if(!$arPhrase["SHOWS_QUOTES"]) CEDirectPodborPhrases::Update( $arPhrase["ID"], array("SHOWS_QUOTES"=>$phrase['Shows']) );
                            }
                            //update SHOWS
                            else if(!$arPhrase["SHOWS"]){
                                CEDirectPodborPhrases::Update( $arPhrase["ID"], array("SHOWS"=>$phrase['Shows']) );
                            }
                        }
                        //add phrase
                        else if(!$_POST["CHECK_QUOTES"]){
                            $arFields=array(
                                "NAME" => $phrase['Phrase'],
                                "SHOWS" => $phrase['Shows'],
                                "SHOWS_QUOTES"=>0,
                                "TYPE" => "S",
                                "SORT"=>$SORT
                            );
                            CEDirectPodborPhrases::Add($arFields);
                        }
                    }
                }
                //delete report
                CEDirectPodborReports::DeleteByYandexID($report['ReportID']);
            }
        }
    }
    
    //send new Report in Yandex
    //check send reports
    $CNTsend =CEDirectPodborReports::getCnt()-CEDirectPodborReports::getCnt(true);
    if( $CNTsend<5 && CEDirectPodborReports::getCnt(true)>0 ){
        for($i=$CNTsend;$i<5;$i++){
            $rsData =CEDirectPodborReports::GetList(array(),array("YAREPORT_ID"=>0));
            if($arReport=$rsData->Fetch()){
                $yandexReportID=$obYaExchange->createWsreport(unserialize($arReport["PHRASES"]));
                if($yandexReportID>0) CEDirectPodborReports::Update($arReport["ID"],array("YAREPORT_ID"=>$yandexReportID));
                else{//error
                    CEDirectPodborReports::DeleteAll();
                    $ERROR=1;
                    $ERROR_TXT=GetMessage("EASYDIRECT_PODBOR_EXEC_create_report_error");
                }
            }
        }
    }
    
    $return=array(
      "ERROR"=>$ERROR,
      "ERROR_TXT"=>$ERROR_TXT,
      "CHECK_QUOTES"=>$_POST["CHECK_QUOTES"],
      "CNT"=>CEDirectPodborReports::getCnt()
    );            
    
}
//---------------addPhrasesToCompany---------------
else if($_POST["CMD"]=="addPhrasesToCompany"){
    $arPhrasesToYa=array();
    
    $BANNER_GROUP_ID=0;
    $SKIP_BANNER_GROUP_ID=0;
    $arCompanies=array();
    $bid=array();
    
    $rsData =CEDirectPodborPhrases::GetList(array("SORT"=>"ASC"), array(">SORT"=>"100000","TYPE"=>"S"));
    while ($arPhrase=$rsData->Fetch()) {
        if($SKIP_BANNER_GROUP_ID!=0&&$BANNER_GROUP_ID==$SKIP_BANNER_GROUP_ID) continue;
        if($BANNER_GROUP_ID!=$arPhrase["SORT"]){
            $res=CEDirectBannerGroup::GetListEx(array(),array("BANNER_GROUP.ID"=>$arPhrase["SORT"]));
            if($arBGroup=$res->Fetch()){
                $BANNER_GROUP_ID=$arPhrase["SORT"];
                //get isset phrases from banners
                $arIssetWords=array();
                $resp=CEDirectPhrase::GetList(array("ID"=>"ASC"),array("ID_BANNER_GROUP"=>$BANNER_GROUP_ID));
                while ($arElement=$resp->Fetch()) {
                    $arIssetWords[]=CEDirectPhrase::stripPhrase($arElement['NAME']);
                }                
                //collect company IDs
                $arCompanies[]=$arBGroup["ID_COMPANY"];
                //check RSYA and set bid
                if($arBGroup["COMPANY_IS_RSYA"]=="Y"){
                    $bid=array('ContextBid'=>CEDirectCompany::convertCurrencyToYa(EDIRECT_DEFRSYAMAXPRICE));
                }
                else{
                    $bid=array('Bid'=>CEDirectCompany::convertCurrencyToYa(EDIRECT_DEFMAXPRICE));
                }
            }
            else {
                $SKIP_BANNER_GROUP_ID=$arPhrase["SORT"];
                continue;
            }
        }
    
        if(!in_array($arPhrase["NAME"], $arIssetWords)){
            $arPhrasesToYa[]=array_merge(
                array(
                    'Keyword'=>$arPhrase["NAME"],
                    'AdGroupId'=>$BANNER_GROUP_ID
                ),
                $bid
            );
        }
    
    }
    
    if(count($arPhrasesToYa)>0){
        if($obYaExchange->createNewPhrases($arPhrasesToYa)){
            //unique company array
            $arCompanies=array_unique($arCompanies);
    
            //add new MINUS words to company
            $arNewMinusWords=array();
            $rsData =CEDirectPodborPhrases::GetList(array("NAME"=>"ASC","ID"=>"ASC"), array("TYPE"=>"M"));
            while ($arPhrase=$rsData->Fetch()) {
                $arNewMinusWords[]=trim($arPhrase["NAME"]);
            }
            if(count($arNewMinusWords)>0){
                foreach ($arCompanies as $CompanyID){
                    //get old minus words and add new
                    $arCampaign=$obYaExchange->getCompanyParams($CompanyID);
                    if(is_array($arCampaign)){
                        $arCampaign=$arCampaign[0];
                        $arCurMinus=array();
                        if(is_array($arCampaign['NegativeKeywords']['Items'])&&count($arCampaign['NegativeKeywords']['Items'])) {
                            $arCurMinus=array_values(array_unique(array_merge($arCampaign['NegativeKeywords']['Items'],$arNewMinusWords)));
                        }
                        $obYaExchange->updateCompany(array(
                            "Id"=>$CompanyID,
                            "NegativeKeywords"=>array("Items"=>$arCurMinus)
                        ));
                    }
                }
            }
    
            //update company
            CEDirectCompany::import($arCompanies,true);
    
            //show message
            $return=array(
                "ERROR"=>0,
                "ERROR_TXT"=>GetMessage("EASYDIRECT_PODBOR_add_mess",array("#COUNT#"=>count($arPhrasesToYa)))
            );            
        }
        else{
            $return=array(
                "ERROR"=>1,
                "ERROR_TXT"=>GetMessage("EASYDIRECT_PODBOR_add_err")
            );
        }
    }
    else{
        $return=array(
            "ERROR"=>1,
            "ERROR_TXT"=>GetMessage("EASYDIRECT_PODBOR_add_err_nowords")
        );
    }
}
//---------------set Pobdor Region---------------
else if($_POST["CMD"]=="setRegion"){    
    COption::SetOptionString("wtc.easydirect","podbor_phrase_region",$_POST["REGION"]);
    $return=array(
        "ERROR"=>0,
        "ERROR_TXT"=>GetMessage("EASYDIRECT_PODBOR_set_region_mess")
    );
}

echo \Bitrix\Main\Web\Json::encode($return, $options = null);
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
?>