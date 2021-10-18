<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

$module_id="wtc.easydirect";

//get POST_RIGHT
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
//Check POST_RIGHT
if ($POST_RIGHT < "W")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$install_status = CModule::IncludeModuleEx($module_id);
CModule::IncludeModule("iblock");

IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php"); 
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/wtc.easydirect/options_status.php");

//check is isset token
$isTokenIsset=false;
if(strlen(EDIRECT_TOKEN)>3){
    $isTokenIsset=true;
}
//check is isset secret
$isSecretIsset=false;
if(strlen(COption::GetOptionString($module_id, "ya_token_secret"))){
    $isSecretIsset=true;
}

if($install_status!=3){ //demo expired
    //methods select array
    $arSearchMetods=array();
    $arRSYAMetods=array();
    $rsData=CEDirectMetod::GetList(array(),array(),false,array("ID","NAME","TYPE"));
    while ($arRes=$rsData->GetNext()) {
        if($arRes["TYPE"]=="SEARCH") $arSearchMetods[$arRes["ID"]]=$arRes["NAME"];
        else if($arRes["TYPE"]=="RSYA") $arRSYAMetods[$arRes["ID"]]=$arRes["NAME"];
        else{
            $arSearchMetods[$arRes["ID"]]=$arRes["NAME"];
            $arRSYAMetods[$arRes["ID"]]=$arRes["NAME"];
        }
    }
    //CNT words to check SEO
    $wordsCNT=CEDirectPhrase::getPhraseToSeoCheck(true,false);
    //companyes select array
    $arCompanyes=array();
    $rsData = CEDirectCompany::GetList(array("NAME"=>"ASC"), array(), false, array("ID","NAME"));
    while($arRes = $rsData->Fetch()){
        $arCompanyes[$arRes["ID"]]=$arRes["NAME"];
    }
    //IBlock select array
    $arIBlocks=array();
    $res = CIBlock::GetList(Array(),Array('ACTIVE'=>'Y'),false);
    $arIBlocks[0]=GetMessage("EASYDIRECT_CATALOG_IB_ID_NOT_SELECT");
    while($ar_res = $res->Fetch())
    {        
        $arIBlocks[$ar_res["ID"]]=$ar_res["NAME"];
    }
    //Catalog prices Types
    $arPriceTypes=array();
    $isCatalogInstal=false;
    if (IsModuleInstalled("catalog")&&CModule::IncludeModule("catalog"))
    {
        $isCatalogInstal=true;
        $res = \Bitrix\Catalog\GroupTable::getList([
            "order" => ["BASE" => "DESC"]
        ]);
        while($ar_res=$res->fetch()){
            $arPriceTypes[$ar_res["ID"]]=$ar_res["NAME"];
        }    
    }
    //catalog items CTN
    $catalogItemsCNT=0;
    if($isCatalogInstal){
        $rsData =CEDirectCatalogItems::GetList(array(),array(">ID"=>0),array());
        $arCnt=$rsData->Fetch();
        $catalogItemsCNT=$arCnt["CNT"];        
    }
    //SEO Regions select array
    $arRegions=array();
    $arRegions[0]=GetMessage("EASYDIRECT_PARAM_YAXML_REGION_auto");    
    if($isTokenIsset){
        $YASearchXML=new CEDirectYaXml();
        foreach ($YASearchXML->getYaCityRegions() as $key=>$val){
            $arRegions[$key]=$val;
        }
    }
    //Currencies
    $arCurrencies=array();
    if($isTokenIsset){
        $res=$obYaExchange->getDictionary("Currencies");
        if(is_array($res)){
            foreach ($res as $val){
                $arCurrencies[$val["Currency"]]=$val["Currency"]." - ".$val["Properties"][1]["Value"];
            }
        }    
    }
    //Log Size
    $sys_log_size=CEDirectLog::getLogSize();
    $phrase_log_size=CEDirectPhraseLog::getLogSize();
}

//============WORK WITH TOKEN================
//===============tab 1 Token=================
//interface
$waiter="<span id='waiter' style='display: none'><img src='/bitrix/themes/.default/images/wait.gif'><br>".GetMessage("EASYDIRECT_TOKEN_WAITER")."</span>";
$getSecretButton='<input onclick="showWaiter()" type="submit" class="adm-btn-save" name="getTokenBut" value="' . GetMessage("EASYDIRECT_TOKEN_GET_SECRET") . '">';
$getTokenButton='<input onclick="showWaiter()" type="submit" class="adm-btn-save" name="getTokenBut" value="' . GetMessage("EASYDIRECT_TOKEN_GET_TOKEN") . '">';
$checkButton='<input onclick="showWaiter()" type="submit" name="checkAPIBut" value="' . GetMessage("EASYDIRECT_TOKEN_CHECKAPI") . '">';

$showGetTokenInfo="";
$arError=array();
//--actions--
//check API connect
if(isset($checkAPIBut)){
    if($obYaExchange->ping()){
        $obYaExchange->setSkipLogin(true);
        $UserInfo=$obYaExchange->getUserInfo();
        $obYaExchange->setSkipLogin(false);
        if($UserInfo!==0) {
            COption::SetOptionString($module_id, 'ya_currency', $UserInfo["Currency"]);
            COption::SetOptionString($module_id, 'ya_login', $UserInfo["Login"]);
            if(!EDIRECT_CATALOG_CURRENCY) COption::SetOptionString($module_id, 'catalog_currency', $UserInfo["Currency"]);
            $message = new CAdminMessage(array("MESSAGE"=>GetMessage("EASYDIRECT_APITEST_OK"), "TYPE"=>"OK"));
            //check SUB accaunt
            if($UserInfo["Type"]=="SUBCLIENT") {
                CAdminMessage::ShowMessage(GetMessage("EASYDIRECT_APITEST_SUBCLIENT"));
            }
        }
        else $message = new CAdminMessage(array("MESSAGE"=>GetMessage("EASYDIRECT_APITEST_USER_ERR"), "TYPE"=>"ERROR"));
    }
    else $message = new CAdminMessage(array("MESSAGE"=>GetMessage("EASYDIRECT_APITEST_ERR"), "TYPE"=>"ERROR"));

    echo $message->Show();
}
//---------------------
//get token or secret
if(strlen($getTokenBut)>0||strlen($getNewTokenBut)>0){

    $createNew="N";
    if(strlen($getNewTokenBut)>0) {
        $createNew = "Y";
        //reset token options
        COption::SetOptionString("wtc.easydirect", "ya_token","");
        COption::SetOptionString("wtc.easydirect", "ya_token_secret","");
        COption::SetOptionString("wtc.easydirect", "ya_token_expire_date","");
        COption::SetOptionString("wtc.easydirect", "ya_login","");
        COption::SetOptionString("wtc.easydirect", "ya_currency","");
    }

    $arRes=$EDirectMain->createYaToken($createNew);
    if(isset($arRes["error"])){
        $arError=array('error_description'=>$arRes["error_description"],'error'=>$arRes["error"]);
    }
    else{
        if($arRes["ok"]=="savesecret"){
            $showGetTokenInfo="<script>$(document).ready(function(){showYaPopup();});</script>";
            $showGetTokenInfo.=$getTokenButton;
        }
        else if($arRes["ok"]=="savetoken"){
            LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&checkAPIBut=Y");
        }
        else {
            $arError=array('error_description'=>GetMessage("EASYDIRECT_TOKEN_UNKNOWN_ERROR"),'error'=>'UNKNOWN');
        }
    }

}
//no token, no secret
else if(!$isTokenIsset&&!$isSecretIsset){
    $showGetTokenInfo = $getSecretButton;
}
//no token, yes secret
else if(!$isTokenIsset&&$isSecretIsset){
    $showGetTokenInfo = $getTokenButton;
}
//yes token, yes secret
else if($isTokenIsset&&$isSecretIsset){
    $showGetTokenInfo = $checkButton;
}

//show error
if(count($arError)){
    $showGetTokenInfo="<span style='color: #7D0000'>".GetMessage("EASYDIRECT_TOKEN_ERROR").$arError["error"]."</span>: ".$arError["error_description"]
        ."<br>".GetMessage("EASYDIRECT_TOKEN_ERROR_NOTE")
        ."<br><br>".$getSecretButton;
}

//----first Tab Options show-----
$status="<strong style='font-size:14px;'>"
    .($isTokenIsset?"<span style='color: #007700'>".GetMessage("EASYDIRECT_TOKEN_STATUS_ON"):"<span style='color: #7D0000'>".GetMessage("EASYDIRECT_TOKEN_STATUS_OFF"))
    ."</span></strong><br>";
$arAllOptions = Array(
		"main" => Array(
            GetMessage("EASYDIRECT_TIT_TOKEN"),
            Array("", GetMessage("EASYDIRECT_TOKEN_STATUS"), $status, Array("statichtml")),
            Array("note"=>"<span id='gettockeninfo'>".$showGetTokenInfo."</span><br>".$waiter)
        )
);
//show login Info
if($isTokenIsset&&!isset($getNewTokenBut)){
    $changeUserButton='<input onclick="showWaiter()" type="submit" name="getNewTokenBut" value="' . GetMessage("EASYDIRECT_TOKEN_SET_USER_BUT") . '">';
    $arAllOptions["main"]=array_merge($arAllOptions["main"],Array(
        GetMessage("EASYDIRECT_TIT_TOKEN_DATA"),
        Array("ya_login", GetMessage("EASYDIRECT_PARAM_LOGIN"), "", Array("statictext")),
        Array("ya_currency", GetMessage("EASYDIRECT_PARAM_CURRENCY"), "", Array("statictext")),
        Array("", GetMessage("EASYDIRECT_TOKEN_SET_USER"), $changeUserButton, Array("statichtml"))
    ));
}

//==============================================

//tab 2 properties
$arAllOptionsDop = Array(
		    //group1
		         Array("def_metod", GetMessage("EASYDIRECT_PARAM_DEFMETOD"), "", Array("selectbox", $arSearchMetods)),
		         Array("def_metod_rsya", GetMessage("EASYDIRECT_PARAM_DEFMETOD_RSYA"), "", Array("selectbox", $arRSYAMetods)),
		         Array("notice_email", GetMessage("EASYDIRECT_PARAM_NOTICE_EMAIL"), "", Array("text", 20)),
		         Array("time_to_set_prices", GetMessage("EASYDIRECT_PARAM_TIME_SET_PRICE"), "", Array("text", 4)),
			     Array("def_max_price", GetMessage("EASYDIRECT_PARAM_DEFMAXPRICE"), "", Array("text", 4)),
		         Array("def_rsya_max_price", GetMessage("EASYDIRECT_PARAM_DEFRSYAMAXPRICE"), "", Array("text", 4)),
		    
		    //catalog integration
		        GetMessage("EASYDIRECT_TIT_CATALOG"),
    		    Array("catalog_ib_ids", GetMessage("EASYDIRECT_CATALOG_IB_ID"), "", Array("multiselectbox", $arIBlocks))
);		    
//catalog integration depend of catalog module
if($isCatalogInstal){
$arAllOptionsDop = array_merge($arAllOptionsDop,Array(
                Array("catalog_auto_update_by_available", GetMessage("EASYDIRECT_CATALOG_AUTO_AVAILABLE"), "", Array("checkbox", "Y") ),
                Array("catalog_auto_update_prices", GetMessage("EASYDIRECT_CATALOG_AUTO_PRICE"), "", Array("checkbox", "Y") ),
                Array("catalog_def_price_type_id", GetMessage("EASYDIRECT_CATALOG_DEF_PRICE_TYPE"), "", Array("selectbox", $arPriceTypes) ),
                ((!EDIRECT_CATALOG_CURRENCY)?array():Array("catalog_currency", GetMessage("EASYDIRECT_CATALOG_CURRENCY"), "", Array("selectbox", $arCurrencies))),
                Array("note"=>GetMessage("EASYDIRECT_UPDATE_SPEED_INFO",array("#CNT#"=>$catalogItemsCNT))),
                Array("catalog_update_speed", GetMessage("EASYDIRECT_CATALOG_UPDATE_SPEED"), "", Array("text", 8)),
                Array("catalog_update_interval", GetMessage("EASYDIRECT_CATALOG_UPDATE_INTERVAL"), "", Array("text", 4))
));
}
else{
$arAllOptionsDop = array_merge($arAllOptionsDop,Array(    
                Array("note"=>GetMessage("EASYDIRECT_CATALOG_NOT_INSTALL"))
));
}

$arAllOptionsDop = array_merge($arAllOptionsDop,Array(
		    
		    //group1.1
    		    GetMessage("EASYDIRECT_TIT_STAT"),
		        Array("note"=>GetMessage("EASYDIRECT_PARAM_SAVETIME_NOTE",array("#SYS_LOG#"=>$sys_log_size,"#PHRASE_LOG#"=>$phrase_log_size))),
		        Array("write_detail_log", GetMessage("EASYDIRECT_PARAM_DETAILLOD"), "", Array("checkbox", "Y")),
		        Array("log_savetime", GetMessage("EASYDIRECT_PARAM_LOG_SAVETIME"), "", Array("text", 4)),
		        Array("write_phrase_log", GetMessage("EASYDIRECT_PARAM_WRITE_PHRASE_LOG"), "", Array("checkbox", "Y")),
    		    Array("phrase_log_savetime", GetMessage("EASYDIRECT_PARAM_PHRASE_LOG_SAVETIME"), "", Array("text", 4)),
		        Array("is_delete_log", GetMessage("EASYDIRECT_PARAM_LOG_DELETE"), "", Array("checkbox", "Y")),
		    		    
		    //group2
    		    GetMessage("EASYDIRECT_TIT_YAXML"),
		        Array("note"=>GetMessage("EASYDIRECT_NOTE_YAXML").
		            GetMessage("EASYDIRECT_NOTE_YAXML_1",array("#IP#"=>(($_SERVER['SERVER_ADDR']=="127.0.0.1")?"":" ".$_SERVER['SERVER_ADDR']))).
		            GetMessage("EASYDIRECT_NOTE_YAXML_2",array("#CNT#"=>$wordsCNT))
		            ),		        
    		    Array("is_yaxml", GetMessage("EASYDIRECT_PARAM_ISYAXML"), "", Array("checkbox", "Y")),
    		    Array("url_yaxml", GetMessage("EASYDIRECT_PARAM_URLYAXML"), "", Array("text", 30)),
		        Array("yaxml_ip_addr", GetMessage("EASYDIRECT_PARAM_YAXML_IP_ADDR"), "", Array("text", 14)),
		        Array("yaxml_region", GetMessage("EASYDIRECT_PARAM_YAXML_REGION"), "", Array("selectbox", $arRegions)),
		        Array("yaxml_day_limit", GetMessage("EASYDIRECT_PARAM_YAXML_DAYLIMIT"), "", Array("text", 4)),
		        Array("yaxml_hourlimit", GetMessage("EASYDIRECT_PARAM_YAXML_HOURLIMIT"), "", Array("text", 4)),
		        Array("yaxml_minshows", GetMessage("EASYDIRECT_PARAM_YAXML_NEEDSHOWS"), "", Array("text", 4)),
		        Array("yaxml_minpremiumbet", GetMessage("EASYDIRECT_PARAM_YAXML_NEDDBET"), "", Array("text", 4)),
));
//add tab 2 properties
$arAllOptions["dopparam"]=$arAllOptionsDop;

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("EASYDIRECT_TAB_SET"), "ICON" => "wtc.easydirect_settings", "TITLE" => GetMessage("EASYDIRECT_TAB_SET")),
	array("DIV" => "edit2", "TAB" => GetMessage("EASYDIRECT_TAB_DOPSET"), "ICON" => "wtc.easydirect_settings", "TITLE" => GetMessage("EASYDIRECT_TAB_DOPSET")),
    array("DIV" => "edit3", "TAB" => GetMessage("EASYDIRECT_TAB_HELP"), "ICON" => "wtc.easydirect_settings", "TITLE" => GetMessage("EASYDIRECT_TAB_HELP")),    
	array("DIV" => "edit4", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "wtc.easydirect_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid() && $install_status!=3)
{
	if(strlen($RestoreDefaults)>0)
	{
		COption::RemoveOption($module_id);
		$z = CGroup::GetList($v1="id",$v2="asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
		while($zr = $z->Fetch())
		    $APPLICATION->DelGroupRight($module_id, array($zr["ID"]));		
	}
	else
	{
		foreach($arAllOptions as $aOptGroup)
		{
			foreach($aOptGroup as $option)
			{
			    if($option[0]=="is_delete_log") continue;
			    if($option[0]=="catalog_ib_ids") {
			        if(in_array(0,$_REQUEST["catalog_ib_ids"])) $_REQUEST["catalog_ib_ids"]=array();
			    }
			    if($option[0]=="time_to_set_prices"&&($_REQUEST["time_to_set_prices"]<5||$_REQUEST["time_to_set_prices"]>120)) continue;
				__AdmSettingsSaveOption($module_id, $option);
			}
		}
	}

	//clear logs
	if($_REQUEST["is_delete_log"]=="Y"){
	   CEDirectLog::ClearLog($_REQUEST["log_savetime"]);
	   CEDirectPhraseLog::ClearLog($_REQUEST["phrase_log_savetime"]);	
	}
	
	$Update = $Update.$Apply;
	ob_start();
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
	ob_end_clean();	

	if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"])>0)
		LocalRedirect($_REQUEST["back_url_settings"]);
	else
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
}

//--------check CURL --------
if($install_status!=3&&!$obYaExchange->isCurlEnabled()){
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"ERROR",
            "MESSAGE" => GetMessage("EASYDIRECT_CURL_ERR"),
            "DETAILS"=> GetMessage("EASYDIRECT_CURL_ERR_TXT"),
            "HTML"=>true
        )
    );
}
//----check CRON AGENTS---
if(CEDirectCron::isAgentRunCrontab() === false){
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"ERROR",
            "MESSAGE" => GetMessage("EASYDIRECT_CRON_ERR"),
            "DETAILS"=> GetMessage("EASYDIRECT_CRON_ERR_TXT"),
            "HTML"=>true
        )
    );    
}
//------check ED agent execute------
if($_GET["reloadAgent"]=="Y") { //manual reload Agent
    if(CEDirectCron::reloadEDAgent()) {
        CAdminMessage::ShowMessage(
            array(
                "TYPE" => "OK",
                "MESSAGE" => GetMessage("EASYDIRECT_AGENT_RELOAD"),
            )
        );
    }
}
else if(CEDirectCron::isEDAgentNotRun()){
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"ERROR",
            "MESSAGE" => GetMessage("EASYDIRECT_AGENT_ERR"),
            "DETAILS"=> GetMessage("EASYDIRECT_AGENT_ERR_TXT"),
            "HTML"=>true
        )
    );
}

//----------------------------------
//check is token work
if($isTokenIsset){
    if(!$obYaExchange->ping()&&$obYaExchange->getTokenError()){
        CAdminMessage::ShowMessage(
            Array(
                "TYPE"=>"ERROR",
                "MESSAGE" => GetMessage("EASYDIRECT_TOKEN_AUTH_ERR")
            )
        );
    }
}

//check install_status
//demo (2)
if ( $install_status == 2 )
{
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"OK",
            "MESSAGE" => GetMessage("EASYDIRECT_status_demo"),
            "DETAILS"=> GetMessage("EASYDIRECT_buy_html"),
            "HTML"=>true
        )
    );    
}
//demo expired (3)
elseif( $install_status == 3 )
{
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"ERROR",
            "MESSAGE" => GetMessage("EASYDIRECT_status_demo_expired"),
            "DETAILS"=> GetMessage("EASYDIRECT_buy_html"),
            "HTML"=>true
        )
    );
}
//-------------------------

$tabControl->Begin();
?>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&amp;lang=<?echo LANG?>">
<?=bitrix_sessid_post();?>
<?$tabControl->BeginNextTab();?>
	<? __AdmSettingsDrawList($module_id, $arAllOptions["main"]);?>
<?$tabControl->BeginNextTab();?>
	<? __AdmSettingsDrawList($module_id, $arAllOptions["dopparam"]);?>
<?$tabControl->BeginNextTab();?>
    <tr class="heading">
		<td colspan="2"><?=GetMessage("EASYDIRECT_TITLE_INSTRUCTIONS")?></td>
	</tr>	
	<tr>
		<td></td>
		<td>
		    <p><b><?=GetMessage("EASYDIRECT_HELP_1")?>:</b> <a target="blank" href="http://www.easydirect.ru/doc/zapusk-modulya/poluchenie-tokena/">http://www.easydirect.ru/doc/zapusk-modulya/poluchenie-tokena/</a></p>
		    <p><b><?=GetMessage("EASYDIRECT_HELP_2")?>:</b> <a target="blank" href="http://www.easydirect.ru/doc/generatsiya-obyavleniy/sozdanie-shablonov/">http://www.easydirect.ru/doc/generatsiya-obyavleniy/sozdanie-shablonov/</a></p>
		    <p><b><?=GetMessage("EASYDIRECT_HELP_3")?>:</b> <a target="blank" href="http://www.easydirect.ru/doc/svyazka-kataloga-i-direkta/">http://www.easydirect.ru/doc/svyazka-kataloga-i-direkta/</a></p>
		    <p><b><?=GetMessage("EASYDIRECT_HELP_4")?>:</b> <a target="blank" href="http://www.easydirect.ru/doc/sozdanie-obyavleniy/podbor-klichevykh-slov/">http://www.easydirect.ru/doc/sozdanie-obyavleniy/podbor-klichevykh-slov/</a></p>
            <p><b><?=GetMessage("EASYDIRECT_HELP_5")?>:</b> <a target="blank" href="http://www.easydirect.ru/doc/content.php">http://www.easydirect.ru/doc/content.php</a></p>
            <p><b><?=GetMessage("EASYDIRECT_HELP_6")?>:</b> <a target="blank" href="http://www.easydirect.ru/doc/versions.php">http://www.easydirect.ru/doc/versions.php</a></p>
            <br>
            <p><h3><?=GetMessage("EASYDIRECT_TP_TITLE")?>:</h3></p>
            <p><b><?=GetMessage("EASYDIRECT_TP_CHAT")?>:</b> <a target="blank" href="https://webtechcom.bitrix24.ru/online/easydirect">https://webtechcom.bitrix24.ru/online/easydirect</a></p>
            <p><b>E-mail:</b> <a href="mailto:info@easydirect.ru">info@easydirect.ru</a></p>
            <p><b><?=GetMessage("EASYDIRECT_TP_TEL")?>:</b> 8 (495) 973-83-10</p>
	</tr>
    <tr class="heading">
		<td colspan="2"><?=GetMessage("EASYDIRECT_SENDMAIL")?></td>
	</tr>	
	<?	
	$info = CModule::CreateModuleObject($module_id);	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST['submit_mail_button'] && $_REQUEST['ticket_text'])
	{
	    //LastErrors
	    $LastErrors=array();
	    if($install_status!=3){
    	    $rsData =CEDirectLog::GetListError();	     
    	    $rsData->NavStart(5);
    	    while ($arStat=$rsData->Fetch()) {
    	        $LastErrors[]=$arStat['MODIFIED_DATE'] ."/". $arStat['MESSAGE'];
    	    }	    
	    }
	    
	    if(EDIRECT_UTFSITE) $charset="UTF-8";
	    else $charset="windows-1251";	    
	    
		$text =  $_REQUEST['ticket_text'] . PHP_EOL . $_REQUEST['fio'] . PHP_EOL. PHP_EOL;
		$text .= "Server: " . $_SERVER['HTTP_HOST'] .PHP_EOL .
		        "PHP: " . phpversion() .PHP_EOL .
				"Sender e-mail: " . $_REQUEST['email'] .PHP_EOL .				
				"Module Version: " . $info->MODULE_VERSION .PHP_EOL .				
				"Install_status(demo): " . $install_status .PHP_EOL.
		        "LastErrors: " . implode(PHP_EOL, $LastErrors);
		$header = 'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset='.$charset . "\n";
		$send=mail("nikolay@easydirect.ru", '=?'.$charset.'?B?'.base64_encode(GetMessage("EASYDIRECT_SENDMAIL_EMAIL_SUBJECT"). $_SERVER['HTTP_HOST']).'?=', $text, $header);
		echo '<tr><td colspan="2" align="center">';
		if($send){
		    CAdminMessage::ShowMessage(
    		    Array(
        		    "TYPE"=>"OK",
        		    "MESSAGE" => GetMessage("EASYDIRECT_SENDMAIL_OK"),
        		    "DETAILS"=> GetMessage("EASYDIRECT_SENDMAIL_OK_NOTE"),
        		    "HTML"=>true
    		    )
		    );		    
		}
		else {
		    CAdminMessage::ShowMessage(
    		    Array(
        		    "TYPE"=>"ERROR",
        		    "MESSAGE" => GetMessage("EASYDIRECT_SENDMAIL_ERROR"),
        		    "DETAILS"=> GetMessage("EASYDIRECT_SENDMAIL_ERROR_NOTE").$_REQUEST['ticket_text'],
        		    "HTML"=>true
    		    )
		    );		    
		}
		echo "</td></tr>";
	}

	?>
		<tr>
			<td align="right" width="30%"><?=GetMessage("EASYDIRECT_SENDMAIL_NAME")?>:</td>
			<td><input type="text" name="fio"/></td>
		</tr>
		<tr>
			<td align="right" width="30%"><span class="required">*</span><?=GetMessage("EASYDIRECT_SENDMAIL_EMAIL") ?>:</td>
			<td><input type="text" name="email" value="<?=EDIRECT_NOTICE_EMAIL?>"/></td>
		</tr>
		<tr>
			<td align="right" width="30%"><span class="required">*</span><?=GetMessage("EASYDIRECT_SENDMAIL_ABOUT")?>:</td>
			<td><textarea name="ticket_text" rows="6" cols="60"></textarea></td>
		</tr>	
		<tr>
			<td></td>
			<td><input type="submit" name="submit_mail_button" value="<?=GetMessage("EASYDIRECT_SENDMAIL_SUBMIT");?>"></td>
		</tr>    
        <tr class="heading">
			<td colspan="2"><?=GetMessage("EASYDIRECT_SENDMAIL_DEBUG")?></td>
		</tr>				
        <tr>
			<td colspan="2" align="center">
                <?
                echo "Server: " . $_SERVER['HTTP_HOST']. "<br>".
		        "PHP: " . phpversion() . "<br>".
				"Module Version: " . $info->MODULE_VERSION . "<br>".			
				"Install_status: " . $install_status;
                ?>			
			</td>
		</tr>				
<?
$tabControl->BeginNextTab();

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");

$tabControl->Buttons();
?>

<?php CJSCore::Init(array("jquery"));?>
<script language="JavaScript">
$.ajaxSetup({cache: false});

function showWaiter(){
    $('#waiter').show();
    $('#gettockeninfo').hide();
}

function showYaPopup(){
    BX.util.popup('<?=$EDirectMain->getYaOauthURL()?>', 680, 600);
}

function confirmRestoreDefaults()
{
	return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>');
}
</script>

<input type="submit" name="Update" value="<?echo GetMessage("MAIN_SAVE")?>">
<input type="reset" name="reset" value="<?echo GetMessage("MAIN_RESET")?>">
<input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirmRestoreDefaults();" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
<?$tabControl->End();?>
</form>