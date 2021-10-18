<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

/**
 * Class CEDirectCron
 * wtc.easydirect Cron
 * @category module wtc.easydirect Cron
 */
class CEDirectCron
{
    //EXEC CRON Events
	public static function CronExec()
	{
		global $DB;
		
		//CRON Events Array
		$arEvents=array(
		    "CreateLine" => -2, //add company to line
		    "ChangePrices" => -3, //change Phrese Prices in Ya
		    "WorkWithCatalogItems" => -4, //Sync and Update Catalog Items Info. Change Prices, Stop/Start banners by Catalog Items Info
		    "UpdateCompanyMainParams" => -10, //check main params, ACTIVE, every 10 min
		    "FullUpdateCompany" => -30, //check changes in companies & update
		    "CheckSeoPosition" => -62, //check Seo Position every 1 hour
		    "ClearLogs"=> -1450, //Daily functions. Clear logs, clear CatalogItems
            "RefreshToken"=> -1450, //every day
		    "SendLastErrToMail" => -1920 //send last Errors to mail every day in 8 hour
		);		
		
		foreach ($arEvents as $fname=>$time){
			$res = $DB->Query("SELECT * FROM  wtc_edirect_sys_cron WHERE FNAME='".$fname."'", false, __LINE__);
			if($ar_res=$res->Fetch()) {
				$prevCallTime=MakeTimeStamp($ar_res['EXEC_DATE'], "YYYY-MM-DD HH:MI:SS");
				if($prevCallTime<(AddToTimeStamp(array("MI"=>$time)))){
					$DB->Update("wtc_edirect_sys_cron", array("EXEC_DATE"=>"'".CAllEDirectTable::TimeStampToBDFormat(time()-2)."'"), "WHERE FNAME='".$fname."'", __LINE__);//update exec time
					//run function
					$result=call_user_func_array(
							array("CEDirectCron",$fname),
							array($prevCallTime)
					);
					if(defined("EDIRECT_DEBUG")&&EDIRECT_DEBUG) Bitrix\Main\Diag\Debug::writeToFile(date("Y-m-d H:i:s")." Start Cron - ".$fname);
					//if error, we return old exec date
					if($result===0) $DB->Update("wtc_edirect_sys_cron", array("EXEC_DATE"=>"'".CAllEDirectTable::TimeStampToBDFormat($prevCallTime)."'"), "WHERE FNAME='".$fname."'", __LINE__);
				}
			}
			else $DB->Insert("wtc_edirect_sys_cron", array("FNAME"=>"'".$fname."'","EXEC_DATE"=>"'".CAllEDirectTable::TimeStampToBDFormat(time()-86400)."'"), __LINE__);
		}
		
		return "CEDirectCron::CronExec();";
	}
	
	//============CRON FUNC=============================
	//Clear logs every day
	private static function ClearLogs($prevCallTime)
	{
		global $DB;

		//ClearLogs
		CEDirectYaExchangeStat::ClearStat();
		CEDirectLog::ClearLog(EDIRECT_LOG_SAVETIME);
		CEDirectPhraseLog::ClearLog(EDIRECT_PHRASE_LOG_SAVETIME);

		//delete Old Unlinked Catalog Items
		if(EDIRECT_IS_CATALOG_INTEGRATION) CEDirectCatalogItems::deleteUnlinkedCatalogItems();

		//update date exec as start day time
		$DB->Update("wtc_edirect_sys_cron", array("EXEC_DATE"=>"'".date("Y-m-d")." 00:00:00'"), "WHERE FNAME='ClearLogs'", __LINE__);
		
		return 1;
	}

    //check need to Refresh Token every day
    private static function RefreshToken($prevCallTime)
    {
        global $DB, $EDirectMain;

        $expire_date=COption::GetOptionString("wtc.easydirect", "ya_token_expire_date");

        if($expire_date && ($expire_date-86400*60) < time() ){ //remained 30 days
            $arRes=$EDirectMain->createYaToken("N");
            if(isset($arRes["error"])){
                CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_TOKEN_UPDATE_ERR').$arRes["error"]." ".$arRes["error_description"],"TYPE"=>"E"));
            }
            else if($arRes["ok"]=="savetoken"){
                CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_TOKEN_UPDATE_OK'),"TYPE"=>"M"));
            }
            else{
                CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_TOKEN_ERR')."UNKNOWN ERROR","TYPE"=>"E"));
            }
        }

        //update date exec as start day time
        $DB->Update("wtc_edirect_sys_cron", array("EXEC_DATE"=>"'".date("Y-m-d")." 00:00:00'"), "WHERE FNAME='RefreshToken'", __LINE__);

        return 1;
    }

	//add company to line
	private static function WorkWithCatalogItems($prevCallTime)
	{
	    if(EDIRECT_IS_CATALOG_INTEGRATION){
	        //STEP 1 Update Catalog Items Info
	        CEDirectCatalogItems::updateCatalogItemsInfo();
	        //STEP 2 Change Prices, Stop/Start banners
	        CEDirectCatalogItems::updateYaBannersByCatalogItemsInfo();
	    }
	    return 1;
	}
	
	//send LAST errors to e-mail
	private static function SendLastErrToMail($prevCallTime){
	    global $DB;
	     
	    $arFilter=Array(
	        ">MODIFIED_DATE"=>ConvertTimeStamp(AddToTimeStamp(array("MI"=>-1450)),"FULL"),
	        "TYPE"=>"E"
	    );
	    $arOrder=array(
	        "MODIFIED_DATE"=>"DESC",
	        "ID"=>"DESC"
	    );
	    
	    $LastErrors=array();
	    $rsData = CEDirectLog::GetList($arOrder, $arFilter);
	    while ($arStat=$rsData->Fetch()) {
	        $LastErrors[]=CAllEDirectTable::DateToSiteFormat($arStat['MODIFIED_DATE']) ." / ". $arStat['MESSAGE'];
	    }
	     
	    if(count($LastErrors)>0){
	        if(EDIRECT_UTFSITE) $charset="UTF-8";
	        else $charset="windows-1251";
	        $text =  GetMessage("EDIRECT_CRON_LAST_ERR_TEXT",array("#SERVERNAME#"=>COption::GetOptionString('main', 'server_name'),"#LOGIN#"=>EDIRECT_YALOGIN));
    	    $text .= PHP_EOL. implode(PHP_EOL, $LastErrors);
    	    $header = 'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset='.$charset . "\n";    	    
    	    $send=mail(EDIRECT_NOTICE_EMAIL, '=?'.$charset.'?B?'.base64_encode(GetMessage("EDIRECT_CRON_LAST_ERR_SUBJECT")).'?=', $text, $header);
	    }
	    
	    //update date exec as start day time
	    $DB->Update("wtc_edirect_sys_cron", array("EXEC_DATE"=>"'".date("Y-m-d")." 00:00:00'"), "WHERE FNAME='SendLastErrToMail'", __LINE__);
	     
	    return 1;
	}
	
	//add company to line
	private static function CreateLine($prevCallTime)
	{
		CEDirectLine::addToLine();
		return 1;
	}
	
	//check changes in companies & update
	private static function FullUpdateCompany($prevCallTime)
	{
	    //count active company
	    $rsData =CEDirectCompany::GetList(array(),array("ACTIVE"=>"Y"),array());
	    $arCnt=$rsData->Fetch();
	    $cntCompany=$arCnt["CNT"];
	    
	    if($cntCompany>0){
	        //ones in day we exec FULL-FULL update
	        $toFullUpdateID=array();
	        $oneStepCntFullUpdate=ceil( $cntCompany/(24*2) );
	        $res=CEDirectCompany::GetList(array("FULL_UPDATE_DATE"=>"ASC"),array("ACTIVE"=>"Y","<FULL_UPDATE_DATE"=>ConvertTimeStamp(AddToTimeStamp(array("HH"=>"-24")),"FULL")));
	        $i=0;
	        while($arCompany=$res->Fetch()){
	            $toFullUpdateID[]=$arCompany["ID"];
	            CEDirectCompany::import($arCompany["ID"],true); //FULL update
                //CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_MESS_6',array("#NAME#"=>$arCompany['NAME'],"#ID#"=>$arCompany["ID"]))));
	            $i++;
	            if($i>=$oneStepCntFullUpdate) break;
	        }
	        //---------------------------------------------------
	        
    		//check only changes and update it
	        $toUpdateID=array();
	        $oneStepCnt=ceil( $cntCompany/(EDIRECT_UPDATE_COMPANY_INTERVAL*2) );
    		$res=CEDirectCompany::GetList(array("IMPORT_DATE"=>"ASC"),array("ACTIVE"=>"Y","<IMPORT_DATE"=>ConvertTimeStamp(AddToTimeStamp(array("HH"=>"-".EDIRECT_UPDATE_COMPANY_INTERVAL)),"FULL")));
    		$i=0;
    		while($arCompany=$res->Fetch()){
    		        if(!in_array($arCompany["ID"],$toFullUpdateID))	$toUpdateID[]=$arCompany["ID"];
    		        $i++;
    		        if($i>=$oneStepCnt) break;
    		}
    		if(count($toUpdateID)>0) {
    		    CEDirectCompany::import($toUpdateID);
    		}
    		//---------------------------------------------------
    		
	    }
		
		return 1;
	}	
	
	//check main params, ACTIVE
	private static function UpdateCompanyMainParams($prevCallTime)
	{
		global $obYaExchange;
		
		//get important metods
		/*$arImportantMetodIDs=array();
		$res=CEDirectMetod::GetList(array(),array("IS_IMPORTANT"=>"Y"));
		while($arMetod=$res->Fetch()){
		    $arImportantMetodIDs[]=$arMetod["ID"];
		}*/
		
		//find company in DB
		$toUpdate=array();
		//$arImportantCID=array();
		$res=CEDirectCompany::GetListEx(array(),array("<COMPANY.CHECK_MAIN_PARAMS_DATE"=>ConvertTimeStamp(AddToTimeStamp(array("MI"=>"-20")),"FULL")));
		while($arCompany=$res->Fetch()){
			$toUpdate[$arCompany["ID"]]=$arCompany["ACTIVE"];
			//if(in_array($arCompany["MCONDITION_ID_METOD"], $arImportantMetodIDs)) $arImportantCID[]=$arCompany["ID"];
		}
		
		if(count($toUpdate)>0){
			//get all param from Ya
			$arCampaigns=$obYaExchange->getCompanyParams(array_keys($toUpdate));
			foreach ($arCampaigns as $compval){
			    //update company main data	    			    			 
				if($compval['Id']>0){
					$arFields=array(
							"STATUS" => $compval['StatusClarification'],
							"ACTIVE" => "Y",
							"CHECK_MAIN_PARAMS_DATE" => "NOW()"
					);
					
					//STRATEGY_TYPE
					$STRATEGY_TYPE=$compval['TextCampaign']['BiddingStrategy']['Search']['BiddingStrategyType'];
					if($STRATEGY_TYPE=="SERVING_OFF"){
					    $STRATEGY_TYPE=$compval['TextCampaign']['BiddingStrategy']['Network']['BiddingStrategyType'];
					}
					$arFields["STRATEGY_TYPE"]=$STRATEGY_TYPE;
					
					//check company OFF state, set ACTIVE
					if(in_array($compval['State'], array("ARCHIVED","ENDED","SUSPENDED"))) {
					    $arFields['ACTIVE']="N";
					    if($toUpdate[$compval['Id']]=="Y"){
					       if($compval['State']=="ARCHIVED") CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_MESS_STATE_1',array("#NAME#"=>$compval['Name'],"#ID#"=>$compval['Id'])),"TYPE"=>"E"));
					       else if($compval['State']=="ENDED") CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_MESS_STATE_2',array("#NAME#"=>$compval['Name'],"#ID#"=>$compval['Id'])),"TYPE"=>"E"));
					       else if($compval['State']=="SUSPENDED") CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_MESS_STATE_3',array("#NAME#"=>$compval['Name'],"#ID#"=>$compval['Id'])),"TYPE"=>"E"));
					    }
					}
						
					CEDirectCompany::Update($compval['Id'],$arFields);
					
					//write log
					if(EDIRECT_WRITE_DETAIL_LOG=="Y"){
					   CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_MESS_1',array("#NAME#"=>$compval['Name'],"#ID#"=>$compval["Id"]))));
					}
				}
				
			}
		}

		return 1;
	}	
	
	//change Phrase Prices in Ya
	private static function ChangePrices($prevCallTime)
	{
		global $obYaExchange;
		
		//------UpdatePrices------------------------
		$arCompanyInLine=CEDirectLine::getLine();
		if(count($arCompanyInLine)>0){
			//check company
			$res=CEDirectCompany::GetList(array(),array("ID"=>$arCompanyInLine),false,array("ID","ACTIVE","NAME","IS_RSYA","STRATEGY_TYPE"));
			$arManualCompanies=array();
			$arAutoCompanies=array();
			while($arRes=$res->Fetch()){
				//Update Bids&Log only in manual strategy company
				if(CEDirectCompany::isManualStrategy($arRes["STRATEGY_TYPE"])){
				    $arManualCompanies[$arRes["ID"]]=array("NAME"=>$arRes["NAME"],"ID"=>$arRes["ID"],"STRATEGY_TYPE"=>$arRes["STRATEGY_TYPE"]);
				}
				else{
				    $arAutoCompanies[$arRes["ID"]]=array("NAME"=>$arRes["NAME"],"ID"=>$arRes["ID"],"STRATEGY_TYPE"=>$arRes["STRATEGY_TYPE"]);
				}
				
				//check ghost company to delete
				$key=array_search($arRes["ID"],$arCompanyInLine);
				if($key!==FALSE) unset($arCompanyInLine[$key]);
			}
			
			//delete ghost company from line
			if(count($arCompanyInLine)>0){
				foreach ($arCompanyInLine as $ID){
					CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_MESS_4',array("#ID#"=>$ID))));
					CEDirectLine::DeleteCompanyFromLine($ID);
				}
			}
			
			//----update Bids in MANUAL strategy Company----
			$arCompanyIDs=array_keys($arManualCompanies);
			//check count companies again
			if(count($arCompanyIDs)>0){
				
				//GET all bannerGroupIDs from companies. Do not update bids in RARELY_SERVED status every time
				$arBannerGroupIDs=array();
				$res=CEDirectBannerGroup::GetListEx(array(),array("BANNER_GROUP.ID_COMPANY"=>$arCompanyIDs,"BANNER.ACTIVE"=>"Y","!BANNER_GROUP.SERVING_STATUS"=>"RARELY_SERVED"),false);
				while($arRes=$res->Fetch()){
					$arBannerGroupIDs[]=$arRes["ID"];
				}
				//update bids in RARELY_SERVED status
				//this GET all bannerGroupIDs from companies with RARELY_SERVED status and BIDS not update more than EDIRECT_UPDATE_RARELY_SERVED_BID_INTERVAL hour
				$res=CEDirectPhrase::GetListEx(array(),array("COMPANY.ID"=>$arCompanyIDs,"BANNER_GROUP.SERVING_STATUS"=>"RARELY_SERVED","<PHRASE.UPDATE_BIDS_DATE"=>ConvertTimeStamp(AddToTimeStamp(array("HH"=>"-".EDIRECT_UPDATE_RARELY_SERVED_BID_INTERVAL)),"FULL")),array("BANNER_GROUP.ID"));
				while($arRes=$res->Fetch()){
				    $arBannerGroupIDs[]=$arRes["BANNER_GROUP_ID"];
				}
				//ned unique because banners left join gets doubles
				$arBannerGroupIDs=array_unique($arBannerGroupIDs);
				if(count($arBannerGroupIDs)>0){
    				//update CLICKS & SHOWS in phrases
    				CEDirectBannerGroup::importPhrases($arBannerGroupIDs);
    				
    				//update actual Bids from Ya
    				$arPhrasesIDs=array();
    				$res=CEDirectPhrase::GetList(array(),array("ID_BANNER_GROUP"=>$arBannerGroupIDs),false,array("ID","ID_BANNER_GROUP"));
    				while($arRes=$res->Fetch()){
    				    $arPhrasesIDs[]=$arRes["ID"];
    				}
    				CEDirectPhrase::importBids($arPhrasesIDs); //the most slow function
    				
    				//UPADTE phreses BIDs
    				//count all prices, update new_prices in phrases
    				$arPhrasesPrices=CEDirectPhrase::getNewPrices($arBannerGroupIDs);
    				if(count($arPhrasesPrices)>0) {
    				    //update prices in Ya
    				    $obYaExchange->setUpdatePrice($arPhrasesPrices);
    				}
    				
    				//write phrase_log
    				if(EDIRECT_WRITE_PHRASE_LOG=="Y") {
    				    CEDirectPhraseLog::WritePhrasesLog($arPhrasesIDs);
    				}

				}
				//------------------------------
			}
			
			//----update Phrases params in AUTO strategy Company----
			$arCompanyIDs=array_keys($arAutoCompanies);
			//check count companies again
			if(count($arCompanyIDs)>0){
			    //GET all bannerGroupIDs from companies. Do not update bids in RARELY_SERVED status every time
			    $arBannerGroupIDs=array();
			    $res=CEDirectBannerGroup::GetListEx(array(),array("BANNER_GROUP.ID_COMPANY"=>$arCompanyIDs,"BANNER.ACTIVE"=>"Y","!BANNER_GROUP.SERVING_STATUS"=>"RARELY_SERVED"),false);
			    while($arRes=$res->Fetch()){
			        $arBannerGroupIDs[]=$arRes["ID"];
			    }

			    //ned unique because banners left join gets doubles
			    $arBannerGroupIDs=array_unique($arBannerGroupIDs);
			    if(count($arBannerGroupIDs)>0){
			        //update CLICKS & SHOWS in phrases
			        CEDirectBannerGroup::importPhrases($arBannerGroupIDs);
			    }
			}			
			
			
			//update log, line and bet date
			foreach (array_merge($arManualCompanies,$arAutoCompanies) as $val){
			    if(EDIRECT_WRITE_DETAIL_LOG=="Y") {
			        if(CEDirectCompany::isManualStrategy($val["STRATEGY_TYPE"])) { //write log only about manual strategy
			            CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CRON_LOG_MESS_3',array("#NAME#"=>$val['NAME'],"#ID#"=>$val['ID']))));
			        }
			    }
				CEDirectCompany::Update($val["ID"],array("BET_DATE"=>"NOW()"));
				CEDirectLine::DeleteCompanyFromLine($val["ID"]);
			}
		}
		//------------------------------------------------
		
		return 1;
	}	
	
	// Check SEO Position
	private static function CheckSeoPosition($prevCallTime)
	{
		//------------Check SEO Position---------------------
		//if YA XML ACTIVE
		if(EDIRECT_YA_XML_ISACTIVE=="Y"){
			if(CEDirectYaExchangeStat::GetCount('xmlsearch')<EDIRECT_YA_XML_DAYLIMIT){
				$res=CEDirectPhrase::getPhraseToSeoCheck();
				$phrasesID=array();
				//check phrases, check HOURLIMIT
				for ($i=0;$i<EDIRECT_YA_XML_HOURLIMIT&&$arPhrase=$res->Fetch();$i++) {
					$phrasesID[]=$arPhrase['ID'];
				}
		
				if(count($phrasesID)>0) CEDirectPhrase::checkSeoPositions($phrasesID);
			}
		}
		//-----------------------------------------------------------------------
		
		return 1;
	}

	//============CHECK CRON & AGENT EXEC FUNCTIONS=======================
    //====================================================================
    // Check is agents run on Cron
    public static function isAgentRunCrontab()
    {
        if (defined('BX_CRONTAB')) {
            return false;
        }
        $bCron = COption::GetOptionString("main", "agents_use_crontab", "N") == 'Y' || defined('BX_CRONTAB_SUPPORT') && BX_CRONTAB_SUPPORT === true || COption::GetOptionString("main", "check_agents", "Y") != 'Y';
        if ($bCron) {
            if (!$GLOBALS['DB']->Query('SELECT LAST_EXEC FROM b_agent WHERE LAST_EXEC > NOW() - INTERVAL 1 DAY AND IS_PERIOD = "N" LIMIT 1')->Fetch()) {
                return false;
            }
            return true;
        }
        return false;
    }

    // Check is agents not run
    public static function isEDAgentNotRun()
    {
        $res = CAgent::GetList(Array("ID" => "DESC"), array("NAME" => "CEDirectCron::CronExec();"));
        if($arAgent=$res->fetch()){
            if($arAgent["ACTIVE"]=="N"
                ||(MakeTimeStamp($arAgent["LAST_EXEC"])+3600*2)<time() //not run 2 hour
            )
            {
                return true;
            }
            else return false;
        }
        else return true;
    }

    // reload sleep ED agent
    public static function reloadEDAgent()
    {
        $res = CAgent::GetList(Array("ID" => "DESC"), array("NAME" => "CEDirectCron::CronExec();"));
        if($arAgent=$res->fetch()){
            if($arAgent["ID"]>0){
                CAgent::Update($arAgent["ID"], array(
                    "ACTIVE"=>"Y",
                    "RUNNING"=>"N",
                    "LAST_EXEC"=>ConvertTimeStamp(time()-3600,"FULL"),
                    "DATE_CHECK"=>false,
                    "RETRY_COUNT"=>0
                ));
                return true;
            }
        }
        return false;
    }

} 
?>