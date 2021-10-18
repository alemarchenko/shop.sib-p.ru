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


//=======EXEC===============
//---------------importCompanies---------------
if($_POST["CMD"]=="showTableReplaceFields"){
    if($_POST["IB_ID"]>0){
        $arTblReplaceFields=CEDirectTemplates::getReplaceFieldsArray($_POST["IB_ID"],$_POST["FOR_SECTIONS"]);
        foreach ($arTblReplaceFields as &$val){
            if(strlen($val[0])>0) {
                $caseParam="";
                if(!preg_match("/(\.ID|\.URL|\_FILTER\_PATH)/", $val[0])){
                    $caseParam=' / <a onclick="enterParamCode(this)" href="#stayhere">{!'.$val[0].'}</a>';
                }                
                $val[0]='<a onclick="enterParamCode(this)" href="#stayhere">{'.$val[0].'}</a>'.$caseParam;
            }
            else {
                $val[0]="<b>".$val[1].":</b>";
                $val[1]="";
            }
        }
        
        $obTblReplaceFields=new CEDirectShowTbl(
            array(
                GetMessage("EASYDIRECT_TEMPLATES_fields_tbl1"),
                GetMessage("EASYDIRECT_TEMPLATES_fields_tbl2"),
            ),
            $arTblReplaceFields);
        
        echo $obTblReplaceFields->ShowTbl();
    }
    else echo GetMessage("EASYDIRECT_TEMPLATES_fields_iberr");
}
//============================

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
?>