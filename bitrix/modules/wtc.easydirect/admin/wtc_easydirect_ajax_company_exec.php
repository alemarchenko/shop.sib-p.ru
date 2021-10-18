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

$arStatus=array();
$processFinish=0;
$companyID=0;
//-----------GET COMPANY ID-------------------
if(is_array($_SESSION["EDIRECT_COMPANY_IDS"])&&count($_SESSION["EDIRECT_COMPANY_IDS"])>0){ //get company ID
    if($_SESSION["EDIRECT_COMPANY_IDS_CNT"]!=0){
        $companyID=array_shift($_SESSION["EDIRECT_COMPANY_IDS"]);
    }

    $arStatus=array(
        "PROGRESS_TOTAL"=>($_SESSION["EDIRECT_COMPANY_IDS_CNT"]+count($_SESSION["EDIRECT_COMPANY_IDS"])),
        "PROGRESS_VALUE"=>$_SESSION["EDIRECT_COMPANY_IDS_CNT"]
    );
    $_SESSION["EDIRECT_COMPANY_IDS_CNT"]++;
}
else { //show OK message
    sleep(2);
    $processFinish=1;
    unset($_SESSION["EDIRECT_COMPANY_IDS_CNT"]);
    unset($_SESSION["EDIRECT_COMPANY_IDS"]);
}
//----------------------------------------------------


//=======EXEC===============
//---------------importCompanies---------------
if($_POST["CMD"]=="importCompanies"){
    if($companyID>0){
        CEDirectCompany::import($companyID);
    }
    //messages
    $arStatus["MESSAGE"]=GetMessage("EASYDIRECT_COMPANY_EXEC_import_title");
    if($companyID==0) $arStatus["DETAILS"]=GetMessage("EASYDIRECT_COMPANY_EXEC_import_addition_mess_0");
    else $arStatus["DETAILS"]=GetMessage("EASYDIRECT_COMPANY_EXEC_import_addition_mess",array("#ID#"=>$companyID));
    $arStatus["MESSAGE_OK"]=GetMessage("EASYDIRECT_COMPANY_EXEC_import_OK");
    $arStatus["MESSAGE_OK_txt"]=GetMessage("EASYDIRECT_COMPANY_EXEC_import_OK_txt");    
}
//---------------updateCompanies---------------
else if($_POST["CMD"]=="updateCompanies"){
    if($companyID>0){
        CEDirectCompany::import($companyID,true);
    }
    //messages
    $arStatus["MESSAGE"]=GetMessage("EASYDIRECT_COMPANY_EXEC_update_title");
    if($companyID==0) $arStatus["DETAILS"]=GetMessage("EASYDIRECT_COMPANY_EXEC_update_addition_mess_0");
    else $arStatus["DETAILS"]=GetMessage("EASYDIRECT_COMPANY_EXEC_update_addition_mess",array("#ID#"=>$companyID));
    $arStatus["MESSAGE_OK"]=GetMessage("EASYDIRECT_COMPANY_EXEC_update_OK");
}
//---------------deleteCompanies---------------
else if($_POST["CMD"]=="deleteCompanies"){
    if($companyID>0){
        $DB->StartTransaction();
         if(!CEDirectCompany::Delete($companyID)){$DB->Rollback();}
         $DB->Commit();
         sleep(1);
    }
    //messages
    $arStatus["MESSAGE"]=GetMessage("EASYDIRECT_COMPANY_EXEC_delete_title");
    if($companyID==0) $arStatus["DETAILS"]=GetMessage("EASYDIRECT_COMPANY_EXEC_delete_addition_mess_0");
    else $arStatus["DETAILS"]=GetMessage("EASYDIRECT_COMPANY_EXEC_delete_addition_mess",array("#ID#"=>$companyID));
    $arStatus["MESSAGE_OK"]=GetMessage("EASYDIRECT_COMPANY_EXEC_delete_OK");    
}
//---------------activateCompanies/deactivateCompanies---------------
else if($_POST["CMD"]=="activateCompanies"||$_POST["CMD"]=="deactivateCompanies"){
    $err=0;
    if($companyID>0){
        if(!CEDirectCompany::setActive( $companyID, ($_POST["CMD"]=="activateCompanies"?"Y":"N") )){
            $err=1;
        }
        sleep(1);
    }
    //messages
    $arStatus["MESSAGE"]=GetMessage("EASYDIRECT_COMPANY_EXEC_activate_title");
    if($companyID==0) $arStatus["DETAILS"]=GetMessage("EASYDIRECT_COMPANY_EXEC_activate_addition_mess_0");
    else if($err) $arStatus["DETAILS"]=GetMessage("EASYDIRECT_COMPANY_EXEC_activate_addition_err_mess",array("#ID#"=>$companyID));
    else $arStatus["DETAILS"]=GetMessage("EASYDIRECT_COMPANY_EXEC_activate_addition_mess",array("#ID#"=>$companyID));
    $arStatus["MESSAGE_OK"]=GetMessage("EASYDIRECT_COMPANY_EXEC_activate_OK");
}
//=========================

//===========SHOW MESSGES=====
if($processFinish){
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"OK",
            "MESSAGE" => $arStatus["MESSAGE_OK"],
            "DETAILS"=> $arStatus["MESSAGE_OK_txt"],
            "HTML"=>true
        )
    );
    echo '<script language="JavaScript">window.top.reloadTable();</script>';
}
else{
    //----SHOW PROGRESS------------
    CAdminMessage::ShowMessage(array(
        "MESSAGE"=>$arStatus["MESSAGE"],
        "DETAILS"=> "#PROGRESS_BAR#".$arStatus["DETAILS"],
        "HTML"=>true,
        "TYPE"=>"PROGRESS",
        "PROGRESS_TOTAL" => $arStatus["PROGRESS_TOTAL"],
        "PROGRESS_VALUE" => $arStatus["PROGRESS_VALUE"]
    ));
    //---------------------------------------
	echo '<script language="JavaScript">window.top.execCompanyAction("'.$_POST["CMD"].'");</script>';
}
//============================

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
?>