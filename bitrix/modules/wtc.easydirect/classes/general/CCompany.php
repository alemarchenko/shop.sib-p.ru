<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

/**
 * Class CEDirectCompany
 * Work with Yandex Company
 * @category module wtc.easydirect Company
 */
class CEDirectCompany extends CAllEDirectTable
{

    /**
     * Return element from DB by ID
     *
     * @param int $ID
     * @param array $arSelectFields     What fields need to select
     * @uses CEDirectCompany::GetList()
     * @return CDBResult
     */    
	public static function GetByID($ID,$arSelectFields=Array())
	{
		return CEDirectCompany::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
	}	

	/**
	 * Return elements list from DB
	 *
	 * @param array $arOrder       Order parametrs.
	 * @param array $arFilter      Filer property
	 * @param array $arGroupBy     Group property
	 * @param array $arSelectFields     What fields need to select
	 * @uses CAllEDirectTable::GetList()
	 * @return CDBResult
	 */	
	public static function GetList($arOrder=Array(), $arFilter=Array(), $arGroupBy=false, $arSelectFields=Array())
	{
		$FilterFieds=array(
			"ID"=>"number",
		    "ID_CATALOG_ITEM"=>"number",
			"NAME"=>"string",
		    "IS_RSYA"=>"string_equal",
			"STATUS"=>"string",
			"ACTIVE"=>"string_equal",
			"BET_DATE"=>"date",
			"CHECK_MAIN_PARAMS_DATE"=>"date",
			"IMPORT_DATE"=>"date",
		    "FULL_UPDATE_DATE"=>"date",
			"IN_GOOGLE"=>"string_equal",
		    "NOT_CHECK_SEO"=>"string_equal",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_company", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}

	/**
	 * Return elements list from DB with MCONDITION information
	 *
	 * @param array $arOrder       Order parametrs.
	 * @param array $arFilter      Filer property
	 * @param array $arGroupBy     Group property
	 * @param array $arSelectFields     What fields need to select
	 * @uses CAllEDirectTable::GetList()
	 * @return CDBResult
	 */	
	public static function GetListEx($arOrder=Array(), $arFilter=Array(), $arGroupBy=false, $arSelectFields=Array())
	{
		$maintblname="COMPANY";
		
		$FilterFieds=array(
				$maintblname."."."ID"=>"number",
		        $maintblname."."."ID_CATALOG_ITEM"=>"number",
				$maintblname."."."NAME"=>"string",
		        $maintblname."."."IS_RSYA"=>"string_equal",
				$maintblname."."."STATUS"=>"string",
				$maintblname."."."ACTIVE"=>"string_equal",
				$maintblname."."."BET_DATE"=>"date",
				$maintblname."."."CHECK_MAIN_PARAMS_DATE"=>"date",
				$maintblname."."."IMPORT_DATE"=>"date",
		        $maintblname."."."FULL_UPDATE_DATE"=>"date",
				$maintblname."."."IN_GOOGLE"=>"string_equal",
		        $maintblname."."."NOT_CHECK_SEO"=>"string_equal",
				"MCONDITION.ID_METOD"=>"number",
		);
	
		if(count($arSelectFields)==0){
			$arSelectFields= array(
					$maintblname.".*",
					"MCONDITION.ID_METOD as MCONDITION_ID_METOD",
				);
		}
		else {
			$arSelectFields=CAllEDirectTable::PrepExArSelect($maintblname,$arSelectFields);
		}

		$from="wtc_edirect_company ".$maintblname."
					LEFT JOIN wtc_edirect_mcondition MCONDITION
					ON (".$maintblname.".ID=MCONDITION.ID_".$maintblname.")";
		
		return CAllEDirectTable::baseGetList($from, $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
		
	/**
	 * import or update company from Yandex
	 *
	 * @param mixed $IDs      company IDs needed to update int or array
	 * @param boolean $notCheckChanges default false     Use or not use changes date from Yandex, if true full update company
	 * @return int 1/0
	 */
	public static function import($IDs,$notCheckChanges=false)
	{
		global $obYaExchange;
		
		if(!is_array($IDs)) $IDs=array($IDs);
		
		if($notCheckChanges){//don't check updates, in any case get all company ONLY to exists companys
			$arUpdatedID=array();
			$arUpdatedID["company"]=$IDs;
			$arUpdatedID["Timestamp"]=ConvertTimeStamp(time(),"FULL");
			//Get bannerGroups IDs from DB
			$arBanerGroupsIDs=array();
			$res=CEDirectBannerGroup::GetList(array(),array("ID_COMPANY"=>$IDs),false);
			while($arElement=$res->Fetch()) $arBanerGroupsIDs[]=$arElement["ID"];
			$arUpdatedID["bannerGroups"]=$arBanerGroupsIDs;
			
			$CompanyToImport=$arUpdatedID["company"];
		}
		else{
			//====get IDs changed companies and banners from Ya====
			//Get companies data from DB
			$arIDinBD=array();
			$fromDate="";
			$res=CEDirectCompany::GetList(array("IMPORT_DATE"=>"ASC"),array("ID"=>$IDs),false,array("ID","IMPORT_DATE"));
			$i=0;
			while($arCompany=$res->Fetch())
			{
				if($i==0) $fromDate=$arCompany["IMPORT_DATE"];
				$arIDinBD[]=$arCompany["ID"];
				$i++;
			}
			//get changed IDs
			$arUpdatedID=array();
			if(count($arIDinBD)){
				$res=$obYaExchange->getCompanyChanges($arIDinBD,$fromDate);
				if($res==0) return 0;
				$arUpdatedID["company"]=$res["Campaigns"];
				$arUpdatedID["bannerGroups"]=$res["BannerGroupIDs"];
				$arUpdatedID["Timestamp"]=$res["Timestamp"];
				
				//get all bannerGroups by changed Banner
				$arBanerGroupsIDs=array();				
				if(count($res["Banners"])>0){
    				$resGroups=CEDirectBanner::GetList(array(),array("ID"=>$res["Banners"]),false,array("ID","ID_BANNER_GROUP"));
    				while($arElement=$resGroups->Fetch()) $arBanerGroupsIDs[]=$arElement["ID_BANNER_GROUP"];
				}
				
				$arUpdatedID["bannerGroups"]=array_unique(array_merge($arUpdatedID["bannerGroups"],$arBanerGroupsIDs));
			}
			
			//NEW company
			$CompanyToImport=array_diff($IDs,$arIDinBD);
			//merge Update and New companyes
			if(count($arUpdatedID["company"])) $CompanyToImport=array_merge($CompanyToImport,$arUpdatedID["company"]);
			//change IMPORT date in all companyes in DB
			if($arUpdatedID["Timestamp"]){
    			foreach ($arIDinBD as $CompanyID){
    			    CEDirectCompany::Update($CompanyID,array("IMPORT_DATE"=>$arUpdatedID["Timestamp"]));
    			}
			}
		}
		
		//get all data from Ya and sync DB data
		if(count($CompanyToImport)) {
			$arNewCompanyID=array();
			$arCampaigns=$obYaExchange->getCompanyParams($CompanyToImport);
			if($arCampaigns==0) return 0;
			foreach ($arCampaigns as $compval)
			{
				//-------ImportCompany-------------------------------
				if($compval["Id"]>0){
					$arFields=array(
							"STATUS" => $compval['StatusClarification'],
							//"ACTIVE" => "Y"  //not update ACTIVE, it will be change in CRON functons UpdateCompanyMainParams
					);
					if($arUpdatedID["Timestamp"]) $arFields["IMPORT_DATE"]=$arUpdatedID["Timestamp"];
					
					//check if company is RSYA
					$STRATEGY_TYPE=$compval['TextCampaign']['BiddingStrategy']['Search']['BiddingStrategyType'];
					if($compval['TextCampaign']['BiddingStrategy']['Search']['BiddingStrategyType']=="SERVING_OFF"){
					    $arFields['IS_RSYA']="Y";
					    $STRATEGY_TYPE=$compval['TextCampaign']['BiddingStrategy']['Network']['BiddingStrategyType'];
					}
					$arFields["STRATEGY_TYPE"]=$STRATEGY_TYPE;
				
					if(!CEDirectCompany::IsEmpty($compval['Id'])){
					    $arFields['NAME'] = $compval['Name'];
					    //check company state, set ACTIVE in NEW companys
					    $arFields['ACTIVE']="Y";
					    if(in_array($compval['State'], array("ARCHIVED","ENDED","SUSPENDED"))) {
					        $arFields['ACTIVE']="N";
					    }
						$arFields["ID"]=$compval['Id'];
						$arFields["BET_DATE"]= "NOW()";
						$arFields["IMPORT_DATE"] = "NOW()";
						$arFields["FULL_UPDATE_DATE"] = "NOW()";
						CEDirectCompany::Add($arFields);
						//add default Mcondition for new company
						$defMetod=EDIRECT_DEFMETOD;
						$defPrice=EDIRECT_DEFMAXPRICE;
						if($arFields['IS_RSYA']=="Y") {
						    $defPrice=EDIRECT_DEFRSYAMAXPRICE;
						    if(EDIRECT_DEFMETOD_RSYA) $defMetod=EDIRECT_DEFMETOD_RSYA;
					        else $defMetod=EDIRECT_NULLMETOD;
						}
						CEDirectMcondition::Add(
							array(
							"ID_COMPANY"=>$compval['Id'],
							"FROM_HOUR"=>0,
							"TO_HOUR"=>24,
							"MAX_PRICE"=>$defPrice,
							"ID_METOD"=>$defMetod
							)
						);
						CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_COMPANY_LOG_MESS_2',array("#NAME#"=>$compval['Name'],"#ID#"=>$compval['Id']))));
						$arNewCompanyID[]=$compval['Id'];
					}
					else {
					    //update ALL banners and phrases if full update company. See bottom. Update FULL_UPDATE_DATE
					    if($notCheckChanges) {
					        $arNewCompanyID[]=$compval['Id'];
					        $arFields["FULL_UPDATE_DATE"] = "NOW()";
					    }
						CEDirectCompany::Update($compval['Id'],$arFields);
						CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_COMPANY_LOG_MESS_3',array("#NAME#"=>$compval['Name'],"#ID#"=>$compval['Id']))));
					}		
				}
			} 
			//add/update ALL banners and phrases for new companies or if full update company
			if(count($arNewCompanyID)>0){
			    //Get isset groups IDs from Companys
			    $arGroupIDsInDB=array();
			    $res=CEDirectBannerGroup::GetList(Array(),array("ID_COMPANY"=>$arNewCompanyID),false,array("ID"));
			    while($ar_res=$res->Fetch()) $arGroupIDsInDB[]=$ar_res['ID'];
			    
			    //Import BannerGroups
			    $BannerGroupsIDs=array();
				$res=$obYaExchange->getCampaignBannerGroups($arNewCompanyID);
				if($res==0) return 0;
				$GLOBALS["DB"]->StartTransaction();
				foreach ($res as $group)
				{
				    if(is_array($group)&&isset($group["Id"])){
    				    if(in_array($group["Id"], $arGroupIDsInDB)){$group["isset"]=1;}
    				    else {$group["isset"]=0;}
    				    
    				    if(CEDirectBannerGroup::import($group)){
    				        $BannerGroupsIDs[]=$group["Id"];
    				    }
				    }
				}
				$GLOBALS["DB"]->Commit();
				
				//Import Banners And Phrases
				CEDirectBannerGroup::importBannerAndPhrases($BannerGroupsIDs);
				
				//Delete BannerGroups if it didn't get
				if(count($BannerGroupsIDs)>0){
    				$dellbgroup=array_diff($arGroupIDsInDB, $BannerGroupsIDs);
    				foreach ($dellbgroup as $value){
    				    CEDirectBannerGroup::Delete($value);
    				}
				}
			}
		}
		
		//===get all changed bannerGroups data===
		if( count($arUpdatedID["bannerGroups"]) && !$notCheckChanges ){
		    //Update Banners And Phrases in Groups
		    CEDirectBannerGroup::importBannerAndPhrases($arUpdatedID["bannerGroups"]);
		    
		    //---------------------------------------
		    //Update all pharases in other Groups. It need to update CTR info
			//Get bannerGroups IDs from DB
			$arBanerGroupsIDs=array();
			$res=CEDirectBannerGroup::GetList(array(),array("ID_COMPANY"=>$IDs),false);
			while($arElement=$res->Fetch()) $arBanerGroupsIDs[]=$arElement["ID"];
			//get not Update Groups
			$arNotUpdateGroupsIDs=array_diff($arBanerGroupsIDs,$arUpdatedID["bannerGroups"]);
			//Update Phrases Info in NotUpdateGroups
			CEDirectBannerGroup::importPhrases($arNotUpdateGroupsIDs);
			//----------------------------------------
		}
		
		return 1;
	}
	
	/**
	 * create company in Yandex
	 *
	 * @param array $arCompany      new Company Params
 	 * @param array $baseParamArray      base params
	 * @return int  created company ID
	 */
	public static function createCompanyInYa($arCompany,$baseParamArray=array())
	{
	    global $obYaExchange;
	    
	    if(!is_array($baseParamArray)){
	        $baseParamArray=array();
	    }
	    //add params to BaseArray
	    $arCompany=$obYaExchange->unionParamsArrays($arCompany,$baseParamArray);
	    //send data to Yandex
	    $newCID=$obYaExchange->createNewCompany($arCompany);

	    return $newCID;
	}	
	
	/**
	 * get BaseCompany params from Yandex
	 *
	 * @param int $baseCompanyID      get default params from Company
	 * @return array  base params Company/BannerGroup/Banner/Phrase
	 */
	public static function getBaseParamsFromYa($baseCompanyID)
	{
	    global $obYaExchange;
	     
	    $arBaseParams=array(
	        "Company"=>array(),
	        "BannerGroup"=>array(),
	        "Banner"=>array()
	    );
	     
	    if($baseCompanyID>0){
	        //------get Company base params-------------------------
	        $arBaseCompany=$obYaExchange->getCompanyParams($baseCompanyID,TRUE);
	        if(is_array($arBaseCompany)){
	            $arBaseCompany=$arBaseCompany[0];
	            $arBaseParams['Company']['DailyBudget']=$arBaseCompany['DailyBudget'];
	            $arBaseParams['Company']['BlockedIps']=$arBaseCompany['BlockedIps'];
	            $arBaseParams['Company']['ExcludedSites']=$arBaseCompany['ExcludedSites'];
	            $arBaseParams['Company']['Notification']=$arBaseCompany['Notification'];
	            $arBaseParams['Company']['TimeTargeting']=$arBaseCompany['TimeTargeting'];
	            $arBaseParams['Company']['TimeZone']=$arBaseCompany['TimeZone'];
	            $arBaseParams['Company']['TextCampaign']['BiddingStrategy']=$arBaseCompany['TextCampaign']['BiddingStrategy'];
	            $arBaseParams['Company']['TextCampaign']['CounterIds']=$arBaseCompany['TextCampaign']['CounterIds'];
	            $arBaseParams['Company']['TextCampaign']['PriorityGoals']=$arBaseCompany['TextCampaign']['PriorityGoals'];
	            $arBaseParams['Company']['TextCampaign']['AttributionModel']=$arBaseCompany['TextCampaign']['AttributionModel'];

	            //----delete some options-----------
	            $arDelOptions=array(
	                "REQUIRE_SERVICING",
	                "SHARED_ACCOUNT_ENABLED",
	                "DAILY_BUDGET_ALLOWED",
	                "MAINTAIN_NETWORK_CPC"
	            );
	            foreach ($arDelOptions as $delOption){
	                foreach ($arBaseCompany['TextCampaign']['Settings'] as $key=>$val){
	                    if($val['Option']==$delOption) {
	                        unset($arBaseCompany['TextCampaign']['Settings'][$key]);
	                        break;
	                    }
	                }
	            }
	            $arBaseParams['Company']['TextCampaign']['Settings']=array_values($arBaseCompany['TextCampaign']['Settings']);
	        }
	        
	        //---get BannerGroup base params -----------------------
            $arBaseBannerGroup=$obYaExchange->getCampaignBannerGroups($baseCompanyID,TRUE);
            if(is_array($arBaseBannerGroup)){
                $arBaseBannerGroup=$arBaseBannerGroup[0];
                $arBaseParams['BannerGroup']['RegionIds']=$arBaseBannerGroup['RegionIds'];
            }
            
            //------get Banner base params ---------------------------
            if($arBaseBannerGroup["Id"]>0){
                $arBaseBanner=$obYaExchange->getBanners($arBaseBannerGroup["Id"],TRUE);
                if(is_array($arBaseBanner)){
                    $arBaseBanner=$arBaseBanner[0];
                    if($arBaseBanner['TextAd']['VCardId']) $arBaseParams['Additional']['VCardId']=$arBaseBanner['TextAd']['VCardId'];
                    $arBaseParams['Banner']['TextAd']['AdExtensionIds']=$arBaseBanner['TextAd']['AdExtensionIds'];
                    //$arBaseParams['Banner']['TextAd']['VideoExtension']['CreativeId']=$arBaseBanner['TextAd']['VideoExtension']['CreativeId'];
                    $arBaseParams['Banner']['TextAd']['TurboPageId']=$arBaseBanner['TextAd']['TurboPageId'];
                }
            }
            
	    }
	     
	    return $obYaExchange->clearParamsArray($arBaseParams);
	}	
	
	/**
	 * convert currency from Yandex
	 *
	 * @param int $YaValue      value from Yandex (long)
	 * @return float  converted Value
	 */
	public static function convertCurrencyFromYa($YaValue)
	{
	    return round($YaValue/1000000,2);
	}
	
	/**
	 * convert currency to Yandex
	 *
	 * @param int $Value      value to Yandex (long)
	 * @return float  converted Value
	 */
	public static function convertCurrencyToYa($Value)
	{
	    //we need multiply prise in 1000000
	    //because $Value can be float we will able to get problem 490000.9999(9)
	    //to fix it we make two multiply and intval	    
	    return ( intval($Value*100) * 10000);
	}

    //=========ADDITIONAL FUNCTIONS=======
	/**
	 * get phrases count in company
	 *
	 *@param int $companyID      company ID
	 * @return int  phrases count
	 */
	public static function getCompanyPhrasesCnt($companyID)
	{
	    if($companyID>0){
	        //2 responce more quikly than GetListEx
	        $arBannerGroupIDs=array();
	        $res=CEDirectBannerGroup::GetList(array(),array("ID_COMPANY"=>$companyID),false);
	        while($arRes=$res->Fetch()){
	            $arBannerGroupIDs[]=$arRes["ID"];
	        }	        
	        
	        if(count($arBannerGroupIDs)){
        	    $rsData =CEDirectPhrase::GetList(array(),array("ID_BANNER_GROUP"=>$arBannerGroupIDs),array());
        	    $arCnt=$rsData->Fetch();
        	    return $arCnt["CNT"];
	        }
	    }
	    
	    return 0;
	}

    /**
     * get spread prices in company
     *
     * @param array $CompanyID Company ID
     * @return array  spreads array [max,min,mid] or empty array if error
     */
    public static function getSpreadPricesInCopany($CompanyID)
    {
        $arReturn=array();

        if($CompanyID>0){
            $res=CEDirectPhrase::GetListEx(array(),array("COMPANY.ID"=>$CompanyID,"!PHRASE.NAME"=>"---autotargeting"),array("PHRASE.ID"),array("PHRASE.ID","COMPANY.ID","PHRASE.NAME"));
            $arPhrasesIds=array();
            while($arRes=$res->Fetch()){
                $arPhrasesIds[]=$arRes["ID"];
            }

            $arReturn=CEDirectPhrase::getSpreadPrices($arPhrasesIds);
        }

        return $arReturn;
    }
	
	//--------------------------------------------------------------
	//--------------EXPORT--------------------------------------
	//-------------------------------------------------------------	
	/**
	 * create CSV file to еxport to Google
	 *
	 * @param int $arCompaniesIDs - array of company IDs
	 * @return string  - file URL
	 */
	public static function exportCompaniesToGoogle($arCompaniesIDs)
	{
	    GLOBAL $USER;
	    
	    //----path of file CSV----
	    $filename=EDIRECT_TMP.'export-google'.$USER->GetID().'.csv';
	     
	    if(is_array($arCompaniesIDs)&&count($arCompaniesIDs)){
	        //---create or clear file----
	        $file = fopen($_SERVER['DOCUMENT_ROOT'].$filename, 'w');
	        
	        $cnt=0;
	        foreach ($arCompaniesIDs as $CID){
	            $list=CEDirectCompany::getCompanyGoogleCSV($CID);
                foreach ($list as $key=>$line) {
                    if($cnt!=0&&$key==0) continue; 
                    if(EDIRECT_UTFSITE) $line=iconv("utf-8", "windows-1251", $line); //if site in UTF8, Convert string to cp1251
                    fputs($file,preg_replace('/\|/', "\t", $line)."\n");
                    $cnt++;
                }
	        }
	        
	        //--------close file--------------
	        fclose($file);
	    }
	    
	    return $filename;
	}	
	
	/**
	 * get company Google CSV 
	 *
	 * @param int $companyID - company ID
	 * @return string  - csv string
	 */
	public static function getCompanyGoogleCSV($companyID)
	{
	    GLOBAL $obYaExchange;
	    $list=array();
	    
	    $res=CEDirectCompany::GetByID($companyID);
	    if($arCompany=$res->Fetch()){
	        //prepare data
	        $arSiteLinks=array();	         
	        $arCompany["NAME"]=str_replace("|","/",$arCompany["NAME"]);
	        
	        //------GET addtitional Company params from Yandex and DB---
	        $arCampaign=$obYaExchange->getCompanyParams($companyID,true);
	        if(is_array($arCampaign)){
	            $arCampaign=$arCampaign[0];
	            //get minus words
	            $arCompany["MINUS"]=array();
	            if(is_array($arCampaign['NegativeKeywords']['Items'])&&count($arCampaign['NegativeKeywords']['Items'])) $arCompany["MINUS"]=$arCampaign['NegativeKeywords']['Items'];
	            //get end date
	            $arCompany["END_DATE"]="";
	            if(strlen($arCampaign['EndDate'])>2) {
	                $arCompany["END_DATE"]=$DB->FormatDate($arCampaign['EndDate'], "YYYY-MM-DD", CSite::GetDateFormat("SHORT"));
	            }
	            //DailyBudget
	            $DailyBudget=CEDirectCompany::convertCurrencyFromYa($arCampaign["DailyBudget"]["Amount"]);
	            
	            //Ad Schedule
	            $schedule="";
	            foreach( $arCampaign["TimeTargeting"]["Schedule"]["Items"] as $val){
	                $arDays=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
	                $strTime="";
	                $val=explode(",",$val);
	                $open=0;
	                foreach ($val as $key=>$time){
	                    if($key==0) continue;
	                    else if($time>0&&!$open) {
	                        if($strTime!="") $strTime.=",";
	                        $strTime.=(($key-1)<10?"0".($key-1):($key-1)).":00";
	                        $open=1;
	                    }
	                    else if($time==0&&$open) {
	                        $strTime.="-".($key-1).":00";
	                        $open=0;
	                    }
	                }
	                if($open==1) $strTime.="-24:00";
	                foreach (explode(",",$strTime) as $interval){
	                    $schedule.="(".$arDays[$val[0]-1]."[".$interval."]);";
	                }
	            }
	        }
	        //cpc_price
	        $res=CEDirectMcondition::GetListEx(array("ID"=>"ASC"),array("MCONDITION.ID_COMPANY"=>$companyID));
	        if($ar_res=$res->Fetch()) {
	            $cpc_price=$ar_res['MAX_PRICE'];
	        }
	        //-------------------------------------------------------
	        
	        //----------MAIN PARAMS--------
	        $list=array("Campaign|Labels|Budget|Budget type|Campaign Type|Networks|Languages|Bid Strategy Type|Bid Strategy Name|Enhanced CPC|Maximum CPC bid limit|Start Date|End Date|Ad Schedule|Ad rotation|Delivery method|Targeting method|Exclusion method|DSA Website|DSA Language|DSA targeting source|DSA page feeds|Flexible Reach|Ad Group|Max CPC|Max CPM|Target CPA|Max CPV|Target CPM|Target ROAS|Desktop Bid Modifier|Mobile Bid Modifier|Tablet Bid Modifier|TV Screen Bid Modifier|Top Content Bid Modifier|Display Network Custom Bid Type|Targeting optimization|Ad Group Type|Tracking template|Final URL suffix|Custom parameters|Age|Bid Modifier|Final URL|Final mobile URL|Criterion Type|ID|Location|Reach|Feed|Radius|Unit|Keyword|First page bid|Top of page bid|First position bid|Quality score|Landing page experience|Expected CTR|Ad relevance|Feed Name|Platform Targeting|Device Preference|Link Text|Destination URL|Description Line 1|Description Line 2|Phone Number|Country of Phone|Call Reporting|Conversion Action|Callout text|Ad type|Headline 1|Headline 2|Headline 3|Path 1|Path 2|Campaign Status|Ad Group Status|Status|Approval Status|Comment");
	        if($arCompany["IS_RSYA"]=="Y"){
	            if(!$cpc_price) $cpc_price="10.00";
	            $list[]=$arCompany["NAME"]."||".$DailyBudget."|Daily|Display|Display Network|ru|Maximize clicks||Disabled|".$cpc_price."||".$arCompany["END_DATE"]."|".$schedule."|Optimize for clicks|Standard|Location of presence or Area of interest|Location of presence or Area of interest|||Google||Audiences||||||||||||||||||||||||||||||||||||||||||||||||||||||||Enabled||||";
	        }
	        else{
	            if(!$cpc_price) $cpc_price="30.00";
	            $list[]=$arCompany["NAME"]."||".$DailyBudget."|Daily|Search|Google search;Search Partners|ru|Maximize clicks||Disabled|".$cpc_price."||".$arCompany["END_DATE"]."|".$schedule."|Optimize for clicks|Standard|Location of presence or Area of interest|Location of presence or Area of interest|||Google||Audiences||||||||||||||||||||||||||||||||||||||||||||||||||||||||Enabled||||";
	       }
	       
	       //-----GROUPS & BANNERS & PHRASES--------
	       //get banners info
	       $arBannerGoupIDs=array();
	       $rsData = CEDirectBannerGroup::GetList(array("ID"=>"ASC"), array("ID_COMPANY"=>$companyID));
	       while($arGroup = $rsData->GetNext())
	       {
	           //write group
	           $arBannerGoupIDs[]=$arGroup["ID"];
	           $arGroup["NAME"]=str_replace("|","/",$arGroup["NAME"]);
	           $list[]=$arCompany["NAME"]."||||||||||||||||||||||Audiences|".$arGroup["NAME"]."|".$cpc_price."|||||||||||None|Conservative|Default|||||||||||||||||||||||||||||||||||||||||Enabled|Enabled|||";
	           
	           //get phrases
	           $resp=CEDirectPhrase::GetList(array(),array("ID_BANNER_GROUP"=>$arGroup['ID']));
	           while ($arElement=$resp->Fetch()) {
	               //write phrase
	               $list[]=$arCompany["NAME"]."|||||||||||||||||||||||".$arGroup["NAME"]."|||||||||||||||||||||||||||||".$arElement['NAME']."||||||||||||||||||||||||||Enabled|Enabled|Enabled||";
	           }
	           	
	           //get banners
	           $resp=CEDirectBanner::GetList(array(),array("ID_BANNER_GROUP"=>$arGroup['ID']));
	           while ($arElement=$resp->Fetch()) {
	               //templates processing -> #keyword#
	               $arElement["DISPLAY_URL"]=str_replace("#","",$arElement["DISPLAY_URL"]);
	               $arElement["TEXT"]=preg_replace("/#([^#]*)#/","{KeyWord:$1}",$arElement["TEXT"]);
	               $arElement["TITLE"]=preg_replace("/#([^#]*)#/","{KeyWord:$1}",$arElement["TITLE"]);
	               $arElement["TITLE2"]=preg_replace("/#([^#]*)#/","{KeyWord:$1}",$arElement["TITLE2"]);
	               //write banner
	               $list[]=$arCompany["NAME"]."|||||||||||||||||||||||".$arGroup["NAME"]."||||||||||||||||||||".$arElement["HREF"]."||||||||||||||||||||||".$arElement["TEXT"]."|||||||Expanded text ad|".$arElement["TITLE"]."|".$arElement["TITLE2"]."||".$arElement["DISPLAY_URL"]."||Enabled|Enabled|Enabled||";
	                
	               //save SITELINKS
	               if(!isset($arCompany["SITELINKS"])) {
	                   foreach (CAllEDirectTable::UnSerializeArrayField($arElement["SITELINKS"]) as $link){
	                       $find=0;
	                       foreach ($arSiteLinks as $oldlink){
	                           if($link["Href"]==$oldlink["Href"]) {$find=1;break;}
	                       }
	                       if($find==0) {$arSiteLinks[]=$link;}
	                   }
	               }
	           }
	       }
	       
	       //------MINUS WORDS---------
	       foreach ($arCompany["MINUS"] as $minusWord){
	           $list[]=$arCompany["NAME"]."|||||||||||||||||||||||||||||||||||||||||||||Campaign Negative Broad|||||||".$minusWord."||||||||||||||||||||||||||Enabled||Enabled||";
	       }
	       
	       //----------SITELINKS-------------
	       foreach ($arSiteLinks as $link){
	           $list[]=$arCompany["NAME"]."|||||||||||[]|[]|[]||||||||||||||||||||||||||||||".$link["Href"]."|||||||||||||||||Главный фид дополнительных ссылок|All|All|".$link["Title"]."|||||||||||||||Enabled||Enabled||";
	       }
	       
	       //-------GET ADDITION BANNERS PARAMS FROM Ya-----
	       if(count($arBannerGoupIDs)>0){
	           $arBannersParams=$obYaExchange->getBanners($arBannerGoupIDs,TRUE);
	           if(is_array($arBannersParams)){
	               
	               //GET Phone
	               if($arBannersParams[0]['TextAd']['VCardId']>0) {
	                   $arVCard=$obYaExchange->getVCard($arBannersParams[0]['TextAd']['VCardId']);
	                   //write Phone
	                   if($arVCard["Phone"]["PhoneNumber"]) $list[]=$arCompany["NAME"]."|||||||||||[]|[]|[]|||||||||||||||||||||||||||||||||||||||||||||||Main call feed|All|All|||||".$arVCard["Phone"]["CountryCode"]." ".$arVCard["Phone"]["CityCode"]." ".$arVCard["Phone"]["PhoneNumber"]."|RU|Enabled|\"Использовать настройки, заданные на уровне аккаунта\"||||||||Enabled||Enabled||";
	               }
	               
	               //get Extensions texts
	               $arExtensions=array();
	               foreach ($arBannersParams as $Banner){
	                   foreach ($Banner['TextAd']['AdExtensions'] as $extension){
	                       if($extension["Type"] == "CALLOUT"){
	                           $arExtensions[]=$extension["AdExtensionId"];
	                       }
	                   }
	               }
	               if(count($arExtensions)>0) {
	                   $res=$obYaExchange->getExtensions(array_unique($arExtensions));
	                   if($res&&is_array($res)){
    	                   foreach ($res as $extension){
    	                       //write Extensions
    	                       $list[]=$arCompany["NAME"]."|||||||||||[]|[]|[]|||||||||||||||||||||||||||||||||||||||||||||||Главный фид уточнений|All|All|||||||||".$extension["Callout"]["CalloutText"]."|||||||Enabled||Enabled||";
    	                   }
	                   }
	               }
	           }
	       }
	       //--------------------------------------
	       
	    }
	    return $list;
	}	
	
	//-----------------------------------------------------------------------------
	//------SAVE/LOAD company data in file when Create/Export--------
	//----------------------------------------------------------------------------------
	/**
	 * work with save data where company create/export
	 *
	 * @param strind $action  - save/load/isset
	 * @param mixed $saveData  - Data need to save
	 * @param strind $prefixFile - prefix to save file name
	 * @return string  - 1/0/Data
	 */
	public static function saveDataInFile($action,$saveData=array(),$prefixFile="")
	{
        global $USER;
        $filename=$_SERVER['DOCUMENT_ROOT'].EDIRECT_TMP.'createCompanySave'.$USER->GetID().$prefixFile.'.txt';
        if($action=="save"){
            if( file_put_contents($filename, serialize($saveData)) !== FALSE ) return 1;
            else return 0;
        }
        else if($action=="load"){
            $getData=unserialize(file_get_contents($filename));
            if($getData!=FALSE) return $getData;
            else return 0;
        }
        else if($action=="isset"){
            return file_exists($filename);
        }
    }
    
    /**
     * save user Image before export in Yandex
     *
     * @param strind $FileData  - upload file array
     * @return moxed  - false / file URL
     */
    public static function saveImageAsTmp($FileData)
    {
        global $USER;
        $destination=$_SERVER['DOCUMENT_ROOT'].EDIRECT_TMP.'img_'.$USER->GetID().'_'.$FileData["name"];
        
        if(move_uploaded_file($FileData["tmp_name"], $destination)){
            return $destination;
        }
        else return false;
    }
    
    /**
     * clear tmp folder from old images
     *
     * @param strind $arImagesInTmp  - urls of used images
     * @return boolean  - boolean
     */
    public static function clearTmpImages($arImagesInTmp)
    {
        global $USER;
        $folder=$_SERVER['DOCUMENT_ROOT'].EDIRECT_TMP;
        $prefix='img_'.$USER->GetID();
        foreach( scandir($folder) as $file ){
            if(strpos($file,$prefix) !== false && !in_array($folder.$file, $arImagesInTmp) && file_exists($folder.$file)){
                unlink($folder.$file);
            }
        }
        return true;
    }    
	
    /**
     * resize Images for Yandex
     *
     *@param array $file   ["url","name"]
     *@return string $resizeImgUrl  new Image Url
     */
    public function resizeImageForYa($file){
        global $USER;
        $resizeImgUrl="";
         
        if($arImage=CFile::GetImageSize($file["url"])){
            $arNewSize=null;
            $ratio=$arImage[0]/$arImage[1];
            if($ratio>1.77&&$ratio<1.78){ //16:9
                if($arImage[0]<1080||$arImage[1]<607) $arNewSize=array('width'=>1080,'height'=>607);
                else if($arImage[0]>5000||$arImage[1]>2812) $arNewSize=array('width'=>5000,'height'=>2812);
            }
            else if($ratio<0.75||$ratio>1.33){ //not in interval 3:4 - 4:3
                if($arImage[0]>$arImage[1]) $arNewSize=array('width'=>round($arImage[1]*1.3),'height'=>$arImage[1]);
                else $arNewSize=array('width'=>$arImage[0],'height'=>round($arImage[0]/0.76));
            }
            else if($arImage[0]<450||$arImage[1]<450){ //check min size 450*450
                if($arImage[0]<450) $arNewSize=array('width'=>450,'height'=>($arImage[1]+450-$arImage[0]));
                else if($arImage[1]<450) $arNewSize=array('width'=>($arImage[0]+450-$arImage[1]),'height'=>450);
            }
            else if($arImage[0]>5000||$arImage[1]>5000){//check max size 5000*5000
                if($arImage[0]>5000) $arNewSize=array('width'=>5000,'height'=>($arImage[1]-($arImage[0]-5000)));
                else if($arImage[1]>5000) $arNewSize=array('width'=>($arImage[0]-($arImage[1]-5000)),'height'=>5000);
            }
    
            if($arNewSize!=null){
                //finaly check min size in new sizes 450*450
                if($arNewSize["width"]<450) $arNewSize=array('width'=>450,'height'=>($arNewSize["height"]+450-$arNewSize["width"]));
                if($arNewSize["height"]<450) $arNewSize=array('width'=>($arNewSize["width"]+450-$arNewSize["height"]),'height'=>450);
    
                //finaly check max size in new sizes 5000*5000
                if($arNewSize["width"]>5000) $arNewSize=array('width'=>5000,'height'=>($arNewSize["height"]-($arNewSize["width"]-5000)));
                if($arNewSize["height"]>5000) $arNewSize=array('width'=>($arNewSize["width"]-($arNewSize["height"]-5000)),'height'=>5000);
    
                $destUrl=$_SERVER['DOCUMENT_ROOT'].EDIRECT_TMP.'img_'.$USER->GetID().'_'.$file["name"]."_resize";
                CFile::ResizeImageFile($file["url"],$destUrl,$arNewSize,BX_RESIZE_IMAGE_EXACT);
                $resizeImgUrl=$destUrl;
            }
            else{
                $resizeImgUrl=$file["url"];
            }
        }
    
        return $resizeImgUrl;
    }    
    
	//------------------------------------------------------------------------
	//------------------------------------------------------------------------
	
    /**
     * Check is this manual strategy
     *
     * @param string checkStrategyType
     * @return boolean
     */
    public static function isManualStrategy($checkStrategyType)
    {
        if($checkStrategyType=="HIGHEST_POSITION" || $checkStrategyType=="MAXIMUM_COVERAGE")
        {
            return true;
        }
        else {return false;}
    }    
    
	/**
	 * Change active company in DB and Yandex
	 *
	 * @param int $ID
	 * @param char $active  Y / N
	 */	
	public static function setActive($ID,$active)
	{
		global $obYaExchange;
		
		if(($rsData = CEDirectCompany::GetByID($ID)) && ($arFields = $rsData->Fetch()))
		{
			if($arFields["ACTIVE"]!=$active){
				if($active=='N') $r=$obYaExchange->setStopCompany($ID);
				else {
				    //check ARCHIVE
				    $arCampaigns=$obYaExchange->getCompanyParams($ID);
				    if($arCampaigns[0]['State']=="ARCHIVED") {
				        //before unarchived COMPANY
				        $obYaExchange->setUnarchiveCompany($ID);
				    }
				    $r=$obYaExchange->setResumeCompany($ID);
				}
				if($r) {
					CEDirectCompany::Update($ID, array("ACTIVE"=>$active));		
					return 1;
				}
				else return 0;		
			}
			else return 1;
		}
		else return 0;
	}	
	
	/**
	 * Check is company RSYA
	 *
	 * @param int $ID
	 * @return boolean or -1 if error
	 */
	public static function IsRsya($ID)
	{
	    if(($rsData = CEDirectCompany::GetByID($ID)) && ($arFields = $rsData->Fetch()))
		{
			if($arFields["IS_RSYA"]=="Y"){return true;}
			else return false;
		}
		return -1;
	}	
	
	/**
	 * Check is element exist in DB
	 *
	 * @param int $ID
	 */
	public static function IsEmpty($ID)
	{
		return CAllEDirectTable::baseIsEmpty("wtc_edirect_company", $ID);
	}
			
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */	
	public static function Add($arFields)
	{
		return CAllEDirectTable::baseAdd("wtc_edirect_company", $arFields);
	}

	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */	
	public static function Update($ID,$arFields)
	{			
		return CAllEDirectTable::baseUpdate("wtc_edirect_company", $ID, $arFields);
	}

	/**
	 * Delete exist element and related data from DB
	 *
	 * @param int $ID
	 */	
	public static function Delete($ID)
	{
		//delete banners
		$res=CEDirectBannerGroup::GetList(Array(),array("ID_COMPANY"=>$ID),false,array("ID"));
		while($ar_res=$res->Fetch()) CEDirectBannerGroup::Delete($ar_res['ID']);
		//delete Mcondition
		$res=CEDirectMcondition::GetList(Array(),array("ID_COMPANY"=>$ID),false,array("ID"));
		while($ar_res=$res->Fetch()) CEDirectMcondition::Delete($ar_res['ID']);
		
		return CAllEDirectTable::baseDelete("wtc_edirect_company", $ID);
	}
	
}
?>