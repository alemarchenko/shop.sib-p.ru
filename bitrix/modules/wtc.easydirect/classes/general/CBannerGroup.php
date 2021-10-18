<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectBannerGroup
 * Work with Yandex Banner Groups
 * @category module wtc.easydirect Banners
 */
class CEDirectBannerGroup extends CAllEDirectTable
{
    /**
     * Return element from DB by ID
     *
     * @param int $ID
     * @param array $arSelectFields     What fields need to select
     * @uses CEDirectBannerGroup::GetList()
     * @return CDBResult
     */
	public static function GetByID($ID,$arSelectFields=Array())
	{
		return CEDirectBannerGroup::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
			"ID_COMPANY"=>"number",
			"NAME"=>"string",
		    "SERVING_STATUS"=>"string",
		    "REGIONIDS"=>"string",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_banner_groups", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}

	/**
	 * Return elements list from DB with Banner information
	 * !IMPORTANT! left join BANNER table get doubles of Groups. Need GroupBy or array Unique
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
	    $maintblname="BANNER_GROUP";
	    $FilterFieds=array(
	        $maintblname."."."ID"=>"number",
	        $maintblname."."."ID_COMPANY"=>"number",
	        $maintblname."."."NAME"=>"string",
	        $maintblname."."."SERVING_STATUS"=>"string",
	        "BANNER.ACTIVE"=>"string_equal",
	        "COMPANY.NAME"=>"string",
	        "COMPANY.IS_RSYA"=>"string_equal",
	        "COMPANY.ID_CATALOG_ITEM"=>"number"
	    );
	    
	    if(count($arSelectFields)==0){
	        $arSelectFields= array(
	            $maintblname.".*",
	            "COMPANY.NAME as COMPANY_NAME",
	            "COMPANY.IS_RSYA as COMPANY_IS_RSYA",
	            "COMPANY.ID_CATALOG_ITEM as COMPANY_ID_CATALOG_ITEMN",
	            "BANNER.ID as BANNER_ID",
	            "BANNER.HREF as BANNER_HREF",
	            "BANNER.TITLE as BANNER_TITLE"
	        );
	    }
	    else {
	        $arSelectFields=CAllEDirectTable::PrepExArSelect($maintblname,$arSelectFields);
	    }
	    
	    $from="wtc_edirect_banner_groups ".$maintblname."
					LEFT JOIN wtc_edirect_banners BANNER
					ON (BANNER.ID_BANNER_GROUP=".$maintblname.".ID)
					LEFT JOIN wtc_edirect_company COMPANY
					ON (COMPANY.ID=".$maintblname.".ID_COMPANY)";
	    
	    return CAllEDirectTable::baseGetList($from, $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}	
	
	/**
	 * import or update BannerGroup from Yandex
	 *
	 * @param array $arGroup      Data array from Yandex API
	 * @return int 1/0
	 */	
	public static function import($arGroup)
	{
	    if(is_array($arGroup)) {
	        //check isset group in DB
	        if(!isset($arGroup["isset"])){
	            $arGroup["isset"]=CEDirectBannerGroup::IsEmpty($arGroup['Id']);
	        }
	        
	        $arGroupFields=array(
	            "NAME" => $arGroup['Name'],
	            "SERVING_STATUS"=>$arGroup['ServingStatus'],
	            "REGIONIDS"=>CEDirectBannerGroup::SerializeArrayField($arGroup['RegionIds'])
	        );
	        
	        if(!$arGroup["isset"]){
	            $arGroupFields["ID"]=$arGroup['Id'];
	            $arGroupFields["ID_COMPANY"] = $arGroup['CampaignId'];
	            CEDirectBannerGroup::Add($arGroupFields);
	        }
	        else {
	            CEDirectBannerGroup::Update($arGroup['Id'],$arGroupFields);
	        }
	        
	        return 1;
	    }
	    return 0;
	}	
	
	/**
	 * import or update Banner&Phrases from Yandex in BannerGroups
	 *
	 * @param array $arBannerGroupsIDs      BannerGroups IDs
	 * @return int 1/0
	 */
	public static function importBannerAndPhrases($arBannerGroupsIDs)
	{
	    global $obYaExchange;
	    
	    if(is_array($arBannerGroupsIDs)) {
	        //----Import Phrases------
	        //must stay before add banners, because ARCHEVED group delete where we add banners and we need delete ghost phreses with it
	        $PhrasesIDs=CEDirectBannerGroup::importPhrases($arBannerGroupsIDs);
	        //Delete Phrases if it didn't get or was ignore
	        CEDirectPhrase::SverkaPoID($arBannerGroupsIDs,$PhrasesIDs);
	        //----------------------------------
	        
	        //get isset catalog Element URLs and Main id_ctatlog_item from Company if define Catalog Integration
	        $arIssetCatalogURLs=array();
	        $arGroupMainIntegration=array();
	        if(EDIRECT_IS_CATALOG_INTEGRATION){
	            //get isset catalog Element URLs
    	        $res=CEDirectBanner::GetList(Array("ID"=>"ASC"),array(">ID_CATALOG_ITEM"=>0),array("ID_CATALOG_ITEM"),array("ID_CATALOG_ITEM","HREF"));
    	        while($ar_res=$res->Fetch()) $arIssetCatalogURLs[$ar_res["ID_CATALOG_ITEM"]]=$ar_res['HREF'];
    	        //get Main id_ctatlog_item from Company to Banner Groups
    	        $res=CEDirectBannerGroup::GetListEx(Array("BANNER_GROUP.ID"=>"ASC"),array(">COMPANY.ID_CATALOG_ITEM"=>0),false,array("BANNER_GROUP.ID","COMPANY.ID_CATALOG_ITEM"));
    	        while($ar_res=$res->Fetch()) $arGroupMainIntegration[$ar_res["ID"]]=$ar_res['COMPANY_ID_CATALOG_ITEM'];
	        }
	        //-------------------------------------------
	        
	        //Get isset banners IDs and params from DB
	        $arBannerIDsInDB=array();
	        $arBannerInDBParams=array();
	        $res=CEDirectBanner::GetList(Array(),array("ID_BANNER_GROUP"=>$arBannerGroupsIDs),false,array("ID","ID_CATALOG_ITEM","HREF"));
	        while($ar_res=$res->Fetch()) {
	            $arBannerIDsInDB[]=$ar_res['ID'];
	            $arBannerInDBParams[$ar_res['ID']]=array(
	                "ID_CATALOG_ITEM"=>$ar_res['ID_CATALOG_ITEM'],
	                "HREF"=>$ar_res['HREF']
	            );
	        }
	        
	        //Import Banners
	        $arBannerIDsInYa=array();
	        $res=$obYaExchange->getBanners($arBannerGroupsIDs);
	        if($res==0) return 0;
	        $GLOBALS["DB"]->StartTransaction();
	        foreach ($res as $banner)
	        {
	            if(is_array($banner)&&isset($banner["Id"])){
	                //-----prepare data ------------
	                $arBannerIDsInYa[]=$banner["Id"];
	                $key=array_search($banner["Id"], $arBannerIDsInDB);
    	            if($key!==false){$banner["isset"]=1; unset($arBannerIDsInDB[$key]);}
    	            else {$banner["isset"]=0;}
    	            //-------------------------------------

    	            //----get catalog Element---
    	            if(EDIRECT_IS_CATALOG_INTEGRATION){
    	                //if isset and not change href will not change idCatalogItem
    	                if(
    	                    $banner["isset"]==1
    	                    && $arBannerInDBParams[$banner["Id"]]["ID_CATALOG_ITEM"]>0
    	                    && $banner['TextAd']['Href']==$arBannerInDBParams[$banner["Id"]]["HREF"]
    	                   ){
    	                       $banner["idCatalogItem"]=$arBannerInDBParams[$banner["Id"]]["ID_CATALOG_ITEM"];
    	                }
    	                // searh idCatalogItem if not isset or Change Href
    	                else if($banner["isset"]==0
    	                          || ($banner["isset"]==1 && $banner['TextAd']['Href']!=$arBannerInDBParams[$banner["Id"]]["HREF"])
    	                    ){
    	                    //check isset id_ctatlog_item in company
    	                    if(isset($arGroupMainIntegration[$banner['AdGroupId']])&&$arGroupMainIntegration[$banner['AdGroupId']]>0) {
    	                        $banner["idCatalogItem"]=$arGroupMainIntegration[$banner['AdGroupId']];
    	                    }
    	                    else {
        	                    //First in isset URLs in DB
        	                    $issetCatalogId=array_search($banner['TextAd']['Href'], $arIssetCatalogURLs);
        	                    if($issetCatalogId!==false){$banner["idCatalogItem"]=$issetCatalogId;}
        	                    //after search catalog ELEMENT in IBLock and create idCatalogItem
        	                    else {
        	                        $arCatalogElementInfo=CEDirectCatalogItems::getCatalogElementInfoByUrl($banner['TextAd']['Href']);
        	                        if(count($arCatalogElementInfo)>0){
        	                           $IDCatalogItem=CEDirectCatalogItems::Add($arCatalogElementInfo);
        	                           if($IDCatalogItem>0) {
        	                               $banner["idCatalogItem"]=$IDCatalogItem;
        	                               //add to isset URL
        	                               $arIssetCatalogURLs[$IDCatalogItem]=$banner['TextAd']['Href'];
        	                           }
        	                        }
        	                    }
    	                    }
    	                }
    	            }
    	            //-------------------------------------
    	            
                    CEDirectBanner::import($banner);
	            }
	        }
	        $GLOBALS["DB"]->Commit();
	        $obYaExchange->clearCache(); //clear cache Banners sitelinks and images
	        //Delete Banners if it didn't get from Yandex (deleted banners) / very uncommon situation
	        $delBannersIDs=array_diff($arBannerIDsInDB,$arBannerIDsInYa);
	        if(count($delBannersIDs)>0){
	            foreach ($delBannersIDs as $value){
	               CEDirectBanner::Delete($value);
	            }
	        }
	         
	        return 1;
	    }
	    return 0;
	}	
	
	/**
	 * import or update Phrases from Yandex in BannerGroups
	 *
	 * @param array $arBannerGroupsIDs      BannerGroups IDs
	 * @return array  IDs add or update Phrases
	 */
	public static function importPhrases($arBannerGroupsIDs)
	{
	    global $obYaExchange;
	    
	    $PhrasesIDs=array();
	    
	    if(is_array($arBannerGroupsIDs)&&count($arBannerGroupsIDs)>0) {
	        //Get isset Phrases IDs from DB
	        $arPhraseIDsInDB=array();
	        $res=CEDirectPhrase::GetList(Array(),array("ID_BANNER_GROUP"=>$arBannerGroupsIDs),false,array("ID"));
	        while($ar_res=$res->Fetch()) $arPhraseIDsInDB[]=$ar_res['ID'];
	        	        
	        //Import Phrases
	        $res=$obYaExchange->getPhrases($arBannerGroupsIDs);
	        if($res==0) return $PhrasesIDs;
	        $GLOBALS["DB"]->StartTransaction();
	        foreach ($res as $phrase)
	        {
	            if(is_array($phrase)&&isset($phrase["Id"])){
	                $key=array_search($phrase["Id"], $arPhraseIDsInDB);
	                if($key!==false){$phrase["isset"]=1; unset($arPhraseIDsInDB[$key]);}
	                else {$phrase["isset"]=0;}	
	                       
    	            if(CEDirectPhrase::import($phrase)){
    	                $PhrasesIDs[]=$phrase["Id"];
    	            }
	            }
	        }
	        $GLOBALS["DB"]->Commit();
	    }
	    
	    return $PhrasesIDs;
	}	
	
	/**
	 * create BannerGroups in Yandex
	 *
	 * @param array $arGroups      new Groups Params array
	 * @param array $baseParamArray      base params
	 * @return array NewBannerGroupIDs or empty array
	 */
	public static function createBannerGroupsInYa($arGroups,$baseParamArray=array())
	{
	    global $obYaExchange;
	     
	    if(!is_array($baseParamArray)){
	        $baseParamArray=array();
	    }
	    //add params from BaseArray
	    foreach ($arGroups as &$Group){
	       $Group=$obYaExchange->unionParamsArrays($Group,$baseParamArray);	    
	    }
	    //send data to Yandex
	    $arNewBannerGroupIDs=$obYaExchange->createNewBannerGroups($arGroups);
	
	    return $arNewBannerGroupIDs;
	}	
	
	/**
	 * move Banners and Phrases from one Group to second Group in Yandex
	 *
	 * @param array $arFromGroupsIDs      Groups IDs need to move
	 * @param int $ToGroupID      group ID - in this group will create new banners
	 * @return int Errors cnt
	 */
	public static function moveGroupsInYa($arFromGroupsIDs,$ToGroupID)
	{
	    global $obYaExchange;
	
	    $cntErr=0;
	    $arBannerToYa=array();
	    $arPhrasesToYa=array();
	     
	    if($ToGroupID>0){
	        if(CEDirectBannerGroup::IsEmpty($ToGroupID)){
	            //--------banners--------------
	            //get IS_RSYA
	            $is_rsya="N";
	            $res=CEDirectBannerGroup::GetListEx(array(),array("BANNER_GROUP.ID"=>$ToGroupID));
	            if($arRes=$res->Fetch()){
	                $is_rsya=$arRes["COMPANY_IS_RSYA"];
	            }
	            
	            //get banners info for clone
	            $resBanners=$obYaExchange->getBanners($arFromGroupsIDs,TRUE);
	            foreach ($resBanners as $Banner){
	                //price prepare
	                if($Banner["TextAd"]["PriceExtension"]["Price"]>0){
	                    $Banner["TextAd"]["PriceExtension"]=array("Price"=>$Banner["TextAd"]["PriceExtension"]["Price"],"PriceQualifier"=>"NONE","PriceCurrency"=>CEDirectCatalogItems::getCatalogCurrency());
	                }
	                else unset($Banner["TextAd"]["PriceExtension"]);
	                 
	                $arBannerToYa[]=array(
	                    "AdGroupId"=>$ToGroupID,
	                    "TextAd"=>$obYaExchange->clearParamsArray($Banner["TextAd"])
	                );
	            }	             
	            
	            //--------phrases--------------
	            //isset phrases in group
	            $arIssetPhrasesNames=array();
	            $res=CEDirectPhrase::GetList(array(),array("ID_BANNER_GROUP"=>$ToGroupID),false,array("ID","ID_BANNER_GROUP","NAME"));
	            while($arRes=$res->Fetch()){
	                $arIssetPhrasesNames[]=ToLower(CEDirectPhrase::stripPhrase($arRes["NAME"]));
	            }
	             
	            //get phrases info for clone
	            $res=CEDirectPhrase::GetList(array(),array("ID_BANNER_GROUP"=>$arFromGroupsIDs),false,array("ID","ID_BANNER_GROUP","NAME","PRICE","CONTEXTPRICE"));
	            while($arRes=$res->Fetch()){
	                if(!in_array(ToLower(CEDirectPhrase::stripPhrase($arRes["NAME"])),$arIssetPhrasesNames)){
                        $arTmp=array(
                            'Keyword'=>$arRes["NAME"],
                            'AdGroupId'=>$ToGroupID
                        );
                        if($is_rsya=="Y") $arTmp['ContextBid']=CEDirectCompany::convertCurrencyToYa($arRes["CONTEXTPRICE"]);
                        else $arTmp['Bid']=CEDirectCompany::convertCurrencyToYa($arRes["PRICE"]);
                        
                        $arPhrasesToYa[]=$arTmp;
	                }
	            }
	            
	            //------------send info to Yandex-----
	            //Create new banners and phrases
	            $arNewBannerIDs=CEDirectBanner::createBannersInYa($arBannerToYa);
	            if(count($arNewBannerIDs)==0) $cntErr++;
	            if(!$obYaExchange->createNewPhrases($arPhrasesToYa)) $cntErr++;
	            
	            //if no errors, stop old banners
	            if($cntErr==0){
	                //send new banners to Moderate
	                $obYaExchange->setModerateBanners($arNewBannerIDs);
	                
	                //get old (from) banners IDs
	                $arBannersToStopIDs=array();
	                $res=CEDirectBanner::GetList(array(),array("ID_BANNER_GROUP"=>$arFromGroupsIDs),false,array("ID","ID_BANNER_GROUP"));
	                while($arRes=$res->Fetch()){
	                    $arBannersToStopIDs[]=$arRes["ID"];
	                }
	                //stop nad archive banners
	                if(count($arBannersToStopIDs)>0) {
	                    if($obYaExchange->setBannersState($arBannersToStopIDs,"suspend")){
	                        sleep(3); //wait to suspend Banners
	                        $obYaExchange->setBannersState($arBannersToStopIDs,"archive");
	                    }
	                }
	            }
	        }
	        else $cntErr++;
	    }
	    else $cntErr++;
	    	
	    return $cntErr;
	}

    //=========ADDITIONAL FUNCTIONS=======
    /**
     * get spread prices in banner groups
     *
     * @param array $arGroupIDs phrases IDs
     * @return array  spreads array [max,min,mid] or empty array if error
     */
    public static function getSpreadPricesInGroups($arGroupIDs)
    {
        $arReturn=array();

        if(is_array($arGroupIDs)&&count($arGroupIDs)){
            $res=CEDirectPhrase::GetList(array(),array("ID_BANNER_GROUP"=>$arGroupIDs,"!NAME"=>"---autotargeting"),false,array("ID","ID_BANNER_GROUP","NAME"));
            $arPhrasesIds=array();
            while($arRes=$res->Fetch()){
                $arPhrasesIds[]=$arRes["ID"];
            }

            $arReturn=CEDirectPhrase::getSpreadPrices($arPhrasesIds);
        }

        return $arReturn;
    }

    //==============================

	/**
	 * Check is element exist in DB
	 *
	 * @param int $ID
	 */	
	public static function IsEmpty($ID)
	{
		return CAllEDirectTable::baseIsEmpty("wtc_edirect_banner_groups", $ID);
	}
			
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */	
	public static function Add($arFields)
	{
		return CAllEDirectTable::baseAdd("wtc_edirect_banner_groups", $arFields);
	}

	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */	
	public static function Update($ID,$arFields)
	{			
		return CAllEDirectTable::baseUpdate("wtc_edirect_banner_groups", $ID, $arFields);
	}

	/**
	 * Delete exist element and related data from DB
	 *
	 * @param int $ID
	 */
	public static function Delete($ID)
	{
	    if(CEDirectBannerGroup::IsEmpty($ID))
	    {
    		//phrases delete
    		$res=CEDirectPhrase::GetList(Array(),array("ID_BANNER_GROUP"=>$ID),false,array("ID"));
    		while($ar_res=$res->Fetch()) CEDirectPhrase::Delete($ar_res['ID']);
    		//banners delete
    		$res=CEDirectBanner::GetList(Array(),array("ID_BANNER_GROUP"=>$ID),false,array("ID"));
    		while($ar_res=$res->Fetch()) CEDirectBanner::Delete($ar_res['ID']);
    		//Mconditions delete
    		$res=CEDirectMcondition::GetList(Array(),array("ID_BANNER_GROUP"=>$ID),false,array("ID"));
    		while($ar_res=$res->Fetch()) CEDirectMcondition::Delete($ar_res['ID']);
    			
    		return CAllEDirectTable::baseDelete("wtc_edirect_banner_groups", $ID);
	    }
	    else 
	    {
	        return 0;
	    }
	}
	
}
?>