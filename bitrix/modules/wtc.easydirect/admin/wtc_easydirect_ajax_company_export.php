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

//====execute export next steps===
$export = CEDirectCompany::saveDataInFile("load","","-export-tmp");
$status=$export->execute();
//======================

//=======check status=====
//------------to next step---------
if($status==1) {
    //----SHOW PROGRESS BAR------------
    CAdminMessage::ShowMessage($export->getProgressBarArray());
    //save class state in file
    CEDirectCompany::saveDataInFile("save",$export,"-export-tmp");
    
    echo '<script language="JavaScript">window.top.execCompanyExport();</script>';    
}
//-----------finish-----------------
else if($status==2){
    if($export->getCntAdditionErrors()==0) {
        $message=array(
            "MESSAGE"=>GetMessage("EASYDIRECT_CREATE_create_yandex_ok"),
            "DETAILS"=> GetMessage("EASYDIRECT_CREATE_create_yandex_ok_mess"),
            "TYPE"=>"OK"
        );
    }
    else {
        $message=array(
            "MESSAGE"=>GetMessage("EASYDIRECT_CREATE_create_yandex_isset_additional_error"),
            "DETAILS"=> '<a href="/bitrix/admin/wtc_easydirect_log.php?lang=ru">'.GetMessage("EASYDIRECT_CREATE_create_yandex_err_mess")."</a>",
            "TYPE"=>"ERROR",
            "HTML"=>true
        );
    }    
    CAdminMessage::ShowMessage($message);
}
// -----------error-----------------
else {
    CAdminMessage::ShowMessage($export->getErrorMessage());    
}
//======================


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
?>