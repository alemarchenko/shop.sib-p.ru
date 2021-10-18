<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectPhrase
 * Work with Yandex Phrases
 * @category module wtc.easydirect Phrases
 */
class CEDirectPhrase extends CAllEDirectTable
{

    /**
     * Return element from DB by ID
     *
     * @param int $ID
     * @param array $arSelectFields     What fields need to select
     * @uses CEDirectPhrase::GetList()
     * @return CDBResult
     */    
	public static function GetByID($ID,$arSelectFields=Array())
	{
		return CEDirectPhrase::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
			"ID_BANNER_GROUP"=>"number",
			"NAME"=>"string",
			"PRICE"=>"number",
			"PRICE_ON_SEARCH"=>"number",
			"SHOWS"=>"number",
			"CLICKS"=>"number",
			"CONTEXTSHOWS"=>"number",
			"CONTEXTCLICKS"=>"number",		
		    "CONTEXTCOVERAGE"=>"string",
		    "CONTEXTPRICE"=>"number",		    
			"MAXBET"=>"number",
			"MINBET"=>"number",
			"PREMIUMMAX"=>"number",
			"PREMIUMMIN"=>"number",				
			"PRICES"=>"string",
			"MESTO_SEO"=>"number",
			"CHECK_MESTO_DATE"=>"date",
		    "UPDATE_BIDS_DATE"=>"date",
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_phrases", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
	
	/**
	 * Return elements list from DB with Banner And Company information
	 * !IMPORTANT! left join BANNER table get doubles of Phrases. Need GroupBy or array Unique
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
		$maintblname="PHRASE";
		$FilterFieds=array(
			$maintblname."."."ID"=>"number",
			$maintblname."."."ID_BANNER_GROUP"=>"number",
			$maintblname."."."NAME"=>"string",
			$maintblname."."."PRICE"=>"number",
			$maintblname."."."PRICE_ON_SEARCH"=>"number",
			$maintblname."."."SHOWS"=>"number",
			$maintblname."."."CLICKS"=>"number",
			$maintblname."."."CONTEXTSHOWS"=>"number",
			$maintblname."."."CONTEXTCLICKS"=>"number",		
		    $maintblname."."."CONTEXTCOVERAGE"=>"string",
		    $maintblname."."."CONTEXTPRICE"=>"number",		    
			$maintblname."."."MAXBET"=>"number",
			$maintblname."."."MINBET"=>"number",
			$maintblname."."."PREMIUMMAX"=>"number",
			$maintblname."."."PREMIUMMIN"=>"number",				
			$maintblname."."."PRICES"=>"string",
			$maintblname."."."MESTO_SEO"=>"number",
			$maintblname."."."CHECK_MESTO_DATE"=>"date",
		    $maintblname."."."UPDATE_BIDS_DATE"=>"date",
		    $maintblname."."."MODIFIED_DATE"=>"date",
			"BANNER.ACTIVE"=>"string_equal",
		    "BANNER_GROUP.SERVING_STATUS"=>"string",
			"COMPANY.ACTIVE"=>"string_equal",
		    "COMPANY.IS_RSYA"=>"string_equal",
			"COMPANY.NAME"=>"string",
		    "COMPANY.NOT_CHECK_SEO"=>"string_equal",
		    "COMPANY.ID"=>"number",
		);
	
		if(count($arSelectFields)==0){
			$arSelectFields= array(
				$maintblname.".*",
				"BANNER.ACTIVE as BANNER_ACTIVE",
				"BANNER.HREF as BANNER_HREF",
				"BANNER.TITLE as BANNER_TITLE",
			    "BANNER.ID as BANNER_ID",
			    "BANNER_GROUP.SERVING_STATUS as BANNER_GROUP_SERVING_STATUS",
			    "BANNER_GROUP.ID as BANNER_GROUP_ID",
			    "BANNER_GROUP.NAME as BANNER_GROUP_NAME",
			    "BANNER_GROUP.REGIONIDS as BANNER_GROUP_REGIONIDS",
				"COMPANY.ACTIVE as COMPANY_ACTIVE",
			    "COMPANY.IS_RSYA as COMPANY_IS_RSYA",
				"COMPANY.NAME as COMPANY_NAME",
			    "COMPANY.NOT_CHECK_SEO as COMPANY_NOT_CHECK_SEO",
				"COMPANY.ID as COMPANY_ID",
				"PHRASE.CHECK_MESTO_DATE as PHRASE_CHECK_MESTO_DATE"
			);
		}
		else {
			$arSelectFields=CAllEDirectTable::PrepExArSelect($maintblname,$arSelectFields);
		}
	
		$from="wtc_edirect_phrases ".$maintblname."
					LEFT JOIN wtc_edirect_banner_groups BANNER_GROUP
					ON (BANNER_GROUP.ID=".$maintblname.".ID_BANNER_GROUP)
		    		LEFT JOIN wtc_edirect_banners BANNER
					ON (BANNER.ID_BANNER_GROUP=".$maintblname.".ID_BANNER_GROUP)
					LEFT JOIN wtc_edirect_company COMPANY
					ON (COMPANY.ID=BANNER_GROUP.ID_COMPANY)";
	
		return CAllEDirectTable::baseGetList($from, $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}	

	/**
	 * import or update phrase in DB from Yandex
	 *
	 * @param array $arPhrase      Data array from Yandex API
	 * @return int 1/0
	 */	
	public static function import($arPhrase)
	{
		if(is_array($arPhrase)) {
		    //check isset banner in DB
		    if(!isset($arPhrase["isset"])){
		        $arPhrase["isset"]=CEDirectPhrase::IsEmpty($arPhrase['Id']);
		    }
		    
			$arFields=array(
					"NAME" =>$arPhrase['Keyword'],
					"PRICE" => CEDirectCompany::convertCurrencyFromYa($arPhrase['Bid']),
					"SHOWS" => $arPhrase['StatisticsSearch']['Impressions'],
					"CLICKS" => $arPhrase['StatisticsSearch']['Clicks'],
					"CONTEXTSHOWS" => $arPhrase['StatisticsNetwork']['Impressions'],
					"CONTEXTCLICKS" => $arPhrase['StatisticsNetwork']['Clicks'],
			        "CONTEXTPRICE"=>CEDirectCompany::convertCurrencyFromYa($arPhrase['ContextBid'])
			);
					
			if(!$arPhrase["isset"]){
				$arFields["ID"]=$arPhrase['Id'];
				$arFields["ID_BANNER_GROUP"] = $arPhrase['AdGroupId'];
				if($arPhrase["State"]!="SUSPENDED") {  //add only if phrase isn't OFF
			         CEDirectPhrase::Add($arFields);
			         return 1;
				}
				else return 0;
			}
			else {
			    if($arPhrase["State"]!="SUSPENDED") { //update only if phrase isn't archive
			        CEDirectPhrase::Update($arPhrase['Id'],$arFields);
			        return 1;
			    }
			    else{
			        // if OFF and exists in DB then Delete
			        CEDirectPhrase::Delete($arPhrase['Id']);
			        return 0;
			    }			    
			}
		}
		return 0;		
	}
	
	/**
	 * update phrases Bids in DB from Yandex
	 *
	 * @param array $PhrasesIDs
	 * @return int 1/0
	 */
	public static function importBids($arPhrasesIDs)
	{
	    global $obYaExchange;
	    
	    if(count($arPhrasesIDs)<1) return 0;
	    
	    //get old Phrases bids to compare with new bids. Compare PREMIUMMIN
	    //not update bids if it smaller than old. Update in any case if pass 30 minutes.
	    //AND separate phrases to RSYA and SEARCH
    	$arOldBids=array();
        $arSearchPhrases=array();
        $arRSYAPhrases=array();
        $TimeStampFromInterval=AddToTimeStamp(array("MI"=>"-".EDIRECT_PHRASE_HOLDBID_INTERVAL));
        $res=CEDirectPhrase::GetListEx(array(),array("PHRASE.ID"=>$arPhrasesIDs),array("PHRASE.ID"),array("PHRASE.UPDATE_BIDS_DATE","COMPANY.IS_RSYA","PHRASE.ID","PHRASE.PREMIUMMIN"));
        while($arPhrase=$res->Fetch()){
            if($arPhrase["COMPANY_IS_RSYA"]=="N"){
                $arSearchPhrases[]=$arPhrase["ID"];
                //get old PREMIUMMIN if not pass PHRASE_HOLDBID_INTERVAL
                if(CAllEDirectTable::DateToTimeStamp($arPhrase["UPDATE_BIDS_DATE"])>$TimeStampFromInterval){
                    $arOldBids[$arPhrase["ID"]]=$arPhrase["PREMIUMMIN"];
                }
            }
            else {
                $arRSYAPhrases[]=$arPhrase["ID"];
            }
        }
        
        //ned unique because banners left join gets doubles
        //disabled because add groupBy
        //$arSearchPhrases=array_unique($arSearchPhrases);
        //$arRSYAPhrases=array_unique($arRSYAPhrases);
        
        //-------GET BIDS--------------
        //old method
	    //$allBids=$obYaExchange->getPhrasesBidsOld($PhrasesIDs);
        
	    $allBids=array();
	    //get Search phrases Prices
        if(count($arSearchPhrases)) {
            $res=$obYaExchange->getPhrasesBids($arSearchPhrases);
    	    if($res==0) return 0;
	        $allBids=array_merge($allBids,$res);
        }
        
	    //get RSYA phrases Prices
	    if(count($arRSYAPhrases)) {
	        $res=$obYaExchange->getPhrasesBids($arRSYAPhrases,true);
    	    if($res==0) return 0;
    	    $allBids=array_merge($allBids,$res);	   
	    }

	    $GLOBALS["DB"]->StartTransaction();
	    foreach ($allBids as $phraseBids)
	    {
	        if(is_array($phraseBids)) {
	            $arFields=array(
	                "UPDATE_BIDS_DATE"=>"NOW()"
                );
	            //add Search fields
	            if(isset($phraseBids['Bid'])){
    	            $arFields=array_merge($arFields,array(
    	                "PRICE" => CEDirectCompany::convertCurrencyFromYa($phraseBids['Bid']),
    	                "PRICE_ON_SEARCH" => CEDirectCompany::convertCurrencyFromYa($phraseBids['CurrentSearchPrice']),
    	                "PRICES" => serialize(CEDirectPhrase::convertPrices($phraseBids['AuctionBids'])),
    	            ));	   
	            }
	            //add RSYA fields
	            if(isset($phraseBids['ContextBid'])){
	                $arFields=array_merge($arFields,array(
	                    "CONTEXTCOVERAGE" => serialize(CEDirectPhrase::convertPrices($phraseBids['ContextCoverage']['Items'])),
	                    "CONTEXTPRICE"=>CEDirectCompany::convertCurrencyFromYa($phraseBids['ContextBid']),
	                ));
	            }

	            //parse SearchPrices array
	            if(isset($phraseBids['SearchPrices'])){
    	            $phraseBids['SearchPrices']=CEDirectPhrase::convertPrices($phraseBids['SearchPrices']);
    	            foreach ($phraseBids['SearchPrices'] as $val){
    	                if($val["Position"]=='PREMIUMFIRST') $arFields["PREMIUMMAX"]=$val['Price'];
    	                else if($val["Position"]=='PREMIUMBLOCK') $arFields["PREMIUMMIN"]=$val['Price'];
    	                else if($val["Position"]=='FOOTERFIRST') $arFields["MAXBET"]=$val['Price'];
    	                else if($val["Position"]=='FOOTERBLOCK') $arFields["MINBET"]=$val['Price'];	                 
    	            }
	            }
	          
	            //not update bids if it smaller than old.
	            if(isset($arOldBids[$phraseBids['KeywordId']])&&$arFields["PREMIUMMIN"]<$arOldBids[$phraseBids['KeywordId']]) {
	                //UPDATE only PRICE
	                CEDirectPhrase::Update($phraseBids['KeywordId'],array(
    	                "PRICE" => $arFields["PRICE"]
	                ));
	            }
	            else {
	                CEDirectPhrase::Update($phraseBids['KeywordId'],$arFields);
	            }
	        }
	    }
	    $GLOBALS["DB"]->Commit();
	}
	
	/**
	 * convert prices in get arrays from Yandex
	 *
	 * @param array $array
	 * @return array
	 */
	public static function convertPrices($array)
	{
	    if(!is_array($array)) return $array;
	    
	    foreach ($array as &$val){
	        foreach ($val as $key=>&$val1){
	            if(in_array($key,array('Price','Bid'))) $val1=CEDirectCompany::convertCurrencyFromYa($val1);
	        }
	    }
	    
	    return $array;
	}	
	
	/**
	 * Delete Phreses from DB if it was not in arPhrasesid
	 *
	 * @param int $arBannerGroupsIDs    check Phrases in this Groups
	 * @param array $arPhrasesid      delete all phrases in group not isset in this array
	 * @return int 1/0
	 */
	public static function SverkaPoID($arBannerGroupsIDs,$arPhrasesid)
	{
		if(!count($arPhrasesid)) return 0;
	
		$res=CEDirectPhrase::GetList(Array(),array("ID_BANNER_GROUP"=>$arBannerGroupsIDs),false,array("ID"));
		$phrasesinid=array();
		while($ar_res=$res->Fetch()) $phrasesinid[]=$ar_res['ID'];
		$dellphrases=array_diff($phrasesinid, $arPhrasesid);
		$GLOBALS["DB"]->StartTransaction();
		foreach ($dellphrases as $value){
				CEDirectPhrase::Delete($value);
		}
		$GLOBALS["DB"]->Commit();
		
		return 1;
	}	
	
	/**
	 * Calculate new prices for phrases, return array for API Yandex
	 *
	 * @param array $arBannerGroupsID    Groups ID need calculate
	 * @return array  for export to API Yandex
	 */
	public static function getNewPrices($arBannerGroupsID)
	{
		$return=array();
		$resPhrases=CEDirectPhrase::GetList(Array("ID_BANNER_GROUP"=>"ASC","NAME"=>"DESC"),array("ID_BANNER_GROUP"=>$arBannerGroupsID));
		$prevBannerGroupID=0;
		$arMetod=array();
		$arPhrasesNewBids=array();
		$IS_RSYA=false;
		$arSpreads=array();
		$companyID=0;
		$maxPriceInGroup=0;
		$calcBet=new CEDirectCalculate();
		
		while($arPhrase=$resPhrases->Fetch()){
			if($prevBannerGroupID!=$arPhrase["ID_BANNER_GROUP"]){
				//get companyID && IS_RSYA
				$res=CEDirectBannerGroup::GetListEx(array(),array("BANNER_GROUP.ID"=>$arPhrase["ID_BANNER_GROUP"]),false,array("BANNER_GROUP.ID_COMPANY","COMPANY.IS_RSYA"));
				if($ar_res=$res->Fetch()) {
					$companyID=$ar_res["ID_COMPANY"];
					if($ar_res["COMPANY_IS_RSYA"]=="Y") $IS_RSYA=true;
					else $IS_RSYA=false;
                    $arSpreads=array();
					if($IS_RSYA) {//get spreads
                        $arSpreads = CEDirectBannerGroup::getSpreadPricesInGroups(array($arPhrase["ID_BANNER_GROUP"]));
                        if (count($arSpreads) == 0) $arSpreads = CEDirectCompany::getSpreadPricesInCopany($companyID);
                    }
				}
				//get current metod
				$arMetod=CEDirectMcondition::getCurrentMetod($arPhrase["ID_BANNER_GROUP"],$companyID);
				//null max Price
				$maxPriceInGroup=0;
				
				$prevBannerGroupID=$arPhrase["ID_BANNER_GROUP"];
			}
			
			$newbet=0;
			if($arPhrase['NAME']!="---autotargeting"){ //do not calculate if ---autotargeting
    			//if check mesto date is later than 2 days, don't use it
    			if($arPhrase['MESTO_SEO']!=0&&CEDirectPhrase::DateToTimeStamp($arPhrase["CHECK_MESTO_DATE"])<AddToTimeStamp(array("DD"=>"-2"))) {$arPhrase['MESTO_SEO']=0;}
    				
    			//calculate price
    			$params=array(
    			        'ID_COMPANY'=>$companyID,
    					'NAME'=>$arPhrase['NAME'],
    					'MAX_PRICE'=>$arMetod['MAX_PRICE'],
    					'MESTO_SEO'=>$arPhrase['MESTO_SEO'],
    					'PRICE_ON_SEARCH'=>$arPhrase['PRICE_ON_SEARCH'],
    					'SHOWS'=>$arPhrase['SHOWS'],
    					'CLICKS'=>$arPhrase['CLICKS'],
    					'CTR'=>($arPhrase['SHOWS']?round(($arPhrase['CLICKS']*100)/$arPhrase['SHOWS'],2):0),
    					'PRICE'=>$arPhrase['PRICE'],
    					'PREMIUMMAX'=>$arPhrase['PREMIUMMAX'],
    					'PREMIUMMIN'=>$arPhrase['PREMIUMMIN'],
    					'MAXBET'=>$arPhrase['MAXBET'],
    					'MINBET'=>$arPhrase['MINBET'],
    					'PRICES'=>CEDirectPhrase::UnSerializeArrayField($arPhrase['PRICES']),
    			        'CONTEXTSHOWS' =>$arPhrase['CONTEXTSHOWS'],
    			        'CONTEXTCLICKS'=>$arPhrase['CONTEXTCLICKS'],
    			        'CONTEXTPRICE'=>$arPhrase['CONTEXTPRICE'],
        			    'CONTEXTCTR'=>($arPhrase['CONTEXTSHOWS']?round(($arPhrase['CONTEXTCLICKS']*100)/$arPhrase['CONTEXTSHOWS'],2):0),
    			        'CONTEXTCOVERAGE'=>CEDirectPhrase::UnSerializeArrayField($arPhrase['CONTEXTCOVERAGE']),
                        'SPREADS'=>$arSpreads
    			);
    				
    			if($arMetod['FNAME']) $newbet=$calcBet->method_exec($arMetod['FNAME'],$params);
    			
    			//Update MaxPrice
    			if($maxPriceInGroup<$newbet) $maxPriceInGroup=$newbet;
			}
			else{//---autotargeting Price
			    $newbet=$maxPriceInGroup;
			}
			
			//if price not change, we will not update it
			if($IS_RSYA&&$newbet==$arPhrase['CONTEXTPRICE']) {$newbet=0;}
			else if(!$IS_RSYA&&$newbet==$arPhrase['PRICE']) {$newbet=0;}
				
			if($newbet>0) {
			    if($IS_RSYA){
    				$return[]=array(
    					"KeywordId" => $arPhrase['ID'],
    				    "NetworkBid"=> CEDirectCompany::convertCurrencyToYa($newbet)
    				);
    				
    				//update fields
    				$arPhraseFieldsUpdate=array(
    				    "CONTEXTPRICE" => $newbet
    				);    				
			    }
			    else{
			        $return[]=array(
			            "KeywordId" => $arPhrase['ID'],
			            "SearchBid" => CEDirectCompany::convertCurrencyToYa($newbet)
			        );			        
			        
			        //Find CurrentSearchPrice
			        $CurrentSearchPrice=0;
			        foreach ($params["PRICES"] as $val){
			            if($newbet>=$val["Bid"]) {
			                $CurrentSearchPrice=$val["Price"];
			                break;
			            }
			        }			        
			        if($CurrentSearchPrice==0) $CurrentSearchPrice=$newbet;
			        
			        //update fields
			        $arPhraseFieldsUpdate=array(
			            "PRICE_ON_SEARCH"=>$CurrentSearchPrice,
			            "PRICE" => $newbet			             
			        );
			    }
			    
			    //save new Bid info in array
			    $arPhrasesNewBids[$arPhrase['ID']]=$arPhraseFieldsUpdate;
			}
		}
		
		//update new Bids in phrases from array through transaction (it is more fast than individual)
		$GLOBALS["DB"]->StartTransaction();
		foreach ($arPhrasesNewBids as $phraseId=>$updateFieldsArray){
		    CEDirectPhrase::Update($phraseId,$updateFieldsArray);
		}
		$GLOBALS["DB"]->Commit();
		
		return $return;
	}	
	
	//=========ADDITIONAL FUNCTIONS=======
    /**
     * get spread prices in phrases
     *
     * @param array $phrasesIDs phrases IDs
     * @return array  spreads array [max,min,mid] or empty array if error
     */
    public static function getSpreadPrices($phrasesIDs)
    {
        $arReturn=array();

        if(count($phrasesIDs)){
            $arReturn=array("max"=>0,"min"=>0,"mid"=>0);
            $arPrices=array();
            $resPhrases=CEDirectPhrase::GetListEx(
                array("PHRASE.ID_BANNER_GROUP"=>"ASC"),
                array("PHRASE.ID"=>$phrasesIDs),
                array("PHRASE.ID"),
                array("PHRASE.ID","PHRASE.ID_BANNER_GROUP","PHRASE.PRICE","PHRASE.CONTEXTPRICE","PHRASE.CONTEXTCOVERAGE","COMPANY.IS_RSYA")
            );
            while($arPhrase=$resPhrases->Fetch()){
                $price=0;
                if($arPhrase["COMPANY_IS_RSYA"]!="Y"&&$arPhrase["PRICE"]>0){
                    $price=$arPhrase["PRICE"]; //search price
                }
                else if($arPhrase["CONTEXTPRICE"]>0){
                    if(count(CEDirectPhrase::UnSerializeArrayField($arPhrase['CONTEXTCOVERAGE']))){
                        $price=$arPhrase["CONTEXTPRICE"]; //RSYA price only if isset CONTEXTCOVERAGE
                    }
                }
                //calcutale spreads
                if($price>0){
                    if($arReturn["max"]<$price) $arReturn["max"]=$price;
                    else if($arReturn["min"]>$price||$arReturn["min"]==0) $arReturn["min"]=$price;
                    $arPrices[]=$price;
                }
            }
            //calcutale mid
            if(count($arPrices)){
                $arReturn["mid"]=array_sum($arPrices)/count($arPrices);
                $arReturn["mid"]=(ceil($arReturn["mid"]*10)/10);
            }
            else{ //return empty array
                $arReturn=array();
            }
        }

        return $arReturn;
    }

	/**
	 * remove special simbols and minus words from phrase, 
	 *
	 * @param string $phrase 
	 * @return string $phrase
	 */
	public static function stripPhrase($phrase)
	{
	    //delete minus
	    $arr=explode("-",$phrase);
	    //delete spec simbols
	    $phrase=trim(str_replace(array("\"","'","!","+","[","]"),'',$arr[0]));
	    
	    return $phrase;
	}	
	
	/**
	 * count words in phrase
	 *
	 * @param string $phrase
	 * @return int $CNT
	 */
	public static function cntWordsInPhrase($phrase)
	{
	    $phrase=CEDirectPhrase::stripPhrase($phrase);
	    $arWords=explode(" ",$phrase);
	    return count($arWords);
	}	
	
	//=========SEO POSITIONS============
	/**
	 * get Phrases to check seo position from DB
	 *
	 * @param boolean $CNT default false   if true return CNT
	 * @param boolean $only_current default true   return only not check phrase   
	 * @return int or CDBResult
	 */
	public static function getPhraseToSeoCheck($CNT=false, $only_current=true){
		$arFilter=array(
				"COMPANY.ACTIVE"=>"Y",
    	        "COMPANY.NOT_CHECK_SEO"=>"N",
		        "COMPANY.IS_RSYA"=>"N",
				"BANNER.ACTIVE"=>"Y",
		        "!BANNER_GROUP.SERVING_STATUS"=>"RARELY_SERVED",
		        "!PHRASE.NAME"=>"---autotargeting",
				">PHRASE.SHOWS"=>EDIRECT_YA_XML_MINSHOWS,
    		    array(
    		        "LOGIC"=>"OR",
				     "PHRASE.UPDATE_BIDS_DATE"=>false,
    		         ">PHRASE.PREMIUMMIN"=>EDIRECT_YA_XML_MINPREMIUMBET
    		        )
		);
		if($only_current) $arFilter["<PHRASE.CHECK_MESTO_DATE"]=ConvertTimeStamp(AddToTimeStamp(array("DD"=>"-1")),"FULL");
		
		if($CNT) {
			$res=CEDirectPhrase::GetListEx(Array("ID"=>"ASC"),$arFilter,array("PHRASE.ID"));
			$i=0;
			while($arRes=$res->Fetch()) {$i++;}
			$res=$i;
		}
		else $res=CEDirectPhrase::GetListEx(Array("CHECK_MESTO_DATE"=>"ASC"),$arFilter,array("PHRASE.ID"));
		
		return $res;
	}

	/**
	 * check Phrases SEO position
	 *
	 * @param array $arPhrasesID   Phrases ID nedd to check
	 * @return 1/0
	 */	
	public static function checkSeoPositions($arPhrasesID)
	{
		$YASearchXML=new CEDirectYaXml();
		
		$res=CEDirectPhrase::GetListEx(array("PHRASE.ID_BANNER_GROUP"=>"ASC"),array("PHRASE.ID"=>$arPhrasesID),array("PHRASE.ID"));
		$oldBannerGroupID=0;
		$phraseRegion=0;		
		while($ar_res=$res->Fetch()) {
		    
		    //--------get phrase Region--------
		    if($oldBannerGroupID!=$ar_res['ID_BANNER_GROUP']){
		        $phraseRegion=0;
    		    if(strlen($ar_res['BANNER_GROUP_REGIONIDS'])>3){
    		      $regionIDs=CEDirectBannerGroup::UnSerializeArrayField($ar_res['BANNER_GROUP_REGIONIDS']);
    		      if(count($regionIDs)){
    		          foreach ($regionIDs as $region){
    		              if($region>0){
    		                  $phraseRegion=$YASearchXML->getMainRegion($region);
    		                  break;
    		              }
    		          }
    		      }
    		    }
    		    $oldBannerGroupID=$ar_res['PHRASE_ID_BANNER_GROUP'];
		    }
		    //-------------------------------------
		    
			$position=$YASearchXML->checkPosition($ar_res['NAME'],$ar_res['BANNER_HREF'],$phraseRegion);
			if($position==-1){
			    return 0;
			}
			else{
    			$arFields=array(
    				"MESTO_SEO"=>$position,
    				"CHECK_MESTO_DATE" => "NOW()"
    			);
    			CEDirectPhrase::Update($ar_res['ID'],$arFields);
    			sleep(1);
		    }
		}
	
		return 1;
	}		
	//==============================
	
	/**
	 * Check is element exist in DB
	 *
	 * @param int $ID
	 */	
	public static function IsEmpty($ID)
	{
		return CAllEDirectTable::baseIsEmpty("wtc_edirect_phrases", $ID);
	}
			
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */	
	public static function Add($arFields)
	{
		$arFields["MESTO_SEO"] = 0;
		$arFields["CHECK_MESTO_DATE"] = "NOW()";
		return CAllEDirectTable::baseAdd("wtc_edirect_phrases", $arFields);
	}

	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */	
	public static function Update($ID,$arFields)
	{			
		return CAllEDirectTable::baseUpdate("wtc_edirect_phrases", $ID, $arFields);
	}

	/**
	 * Delete exist element and related data from DB
	 *
	 * @param int $ID
	 */	
	public static function Delete($ID)
	{
		//DO NOT delete phrase log, it will delete from CRON
		return CAllEDirectTable::baseDelete("wtc_edirect_phrases", $ID);
	}
	
}
?>