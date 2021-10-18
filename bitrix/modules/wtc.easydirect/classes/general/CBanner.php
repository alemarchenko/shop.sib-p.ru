<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectBanner
 * Work with Yandex Banner
 * @category module wtc.easydirect Banners
 */
class CEDirectBanner extends CAllEDirectTable
{

    /**
     * Return element from DB by ID
     *
     * @param int $ID
     * @param array $arSelectFields     What fields need to select
     * @uses CEDirectBanner::GetList()
     * @return CDBResult
     */
	public static function GetByID($ID,$arSelectFields=Array())
	{
		return CEDirectBanner::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
		    "ID_CATALOG_ITEM"=>"number",
			"TITLE"=>"string",
		    "TITLE2"=>"string",
			"TEXT"=>"string",
			"HREF"=>"string",
		    "DISPLAY_URL"=>"string",
		    "PRICE"=>"number",
			"SITELINKS"=>"string",
		    "IMAGE"=>"string",
			"ACTIVE"=>"string_equal",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_banners", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
	
	/**
	 * Return elements list from DB with CATALOG_ITEMS and BANNER_GROUP information
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
	    $maintblname="BANNER";
	
	    $FilterFieds=array(
	        $maintblname."."."ID"=>"number",
			$maintblname."."."ID_BANNER_GROUP"=>"number",
		    $maintblname."."."ID_CATALOG_ITEM"=>"number",
			$maintblname."."."TITLE"=>"string",
		    $maintblname."."."TITLE2"=>"string",
			$maintblname."."."TEXT"=>"string",
			$maintblname."."."HREF"=>"string",
		    $maintblname."."."DISPLAY_URL"=>"string",
	        $maintblname."."."PRICE"=>"number",
			$maintblname."."."SITELINKS"=>"string",
		    $maintblname."."."IMAGE"=>"string",
			$maintblname."."."ACTIVE"=>"string_equal",
	        $maintblname."."."MODIFIED_DATE"=>"date",
	        $maintblname."."."MODIFIED_IDUSER"=>"number",
	        "CATALOG_ITEM.ID"=>"number",
            "CATALOG_ITEM.IBLOCK_ELEMENT_ID"=>"number",
            "CATALOG_ITEM.IS_SECTION"=>"string_equal",
            "CATALOG_ITEM.NAME"=>"string",
	        "CATALOG_ITEM.PRICE"=>"number",
	        "CATALOG_ITEM.IS_AVAILABLE"=>"string_equal",
	        "BANNER_GROUP.ID_COMPANY"=>"number"
	    );
	
	    if(count($arSelectFields)==0){
	        $arSelectFields= array(
	            $maintblname.".*",
	            "CATALOG_ITEM.ID as CATALOG_ITEM_ID",
	            "CATALOG_ITEM.IBLOCK_ELEMENT_ID as CATALOG_ITEM_IBLOCK_ELEMENT_ID",
	            "CATALOG_ITEM.IS_SECTION as CATALOG_ITEM_IS_SECTION",
	            "CATALOG_ITEM.NAME as CATALOG_ITEM_NAME",
	            "CATALOG_ITEM.IS_AVAILABLE as CATALOG_ITEM_IS_AVAILABLE",
	            "CATALOG_ITEM.PRICE as CATALOG_ITEM_PRICE",
	            "BANNER_GROUP.ID_COMPANY as BANNER_GROUP_ID_COMPANY"
	        );
	    }
	    else {
	        $arSelectFields=CAllEDirectTable::PrepExArSelect($maintblname,$arSelectFields);
	    }
	
	    $from="wtc_edirect_banners ".$maintblname."
					LEFT JOIN wtc_edirect_catalog_items CATALOG_ITEM
					ON (".$maintblname.".ID_CATALOG_ITEM=CATALOG_ITEM.ID)
					LEFT JOIN wtc_edirect_banner_groups BANNER_GROUP
					ON (BANNER_GROUP.ID=".$maintblname.".ID_BANNER_GROUP)";
	
	    return CAllEDirectTable::baseGetList($from, $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}	
	
	/**
	 * import or update Banner from Yandex
	 *
	 * @param array $arBanner      Data array from Yandex API
	 * @return int 1/0
	 */
	public static function import($arBanner)
	{
	    global $obYaExchange;
	    
	    //IMPORTANT many operations did before in CEDirectBannerGroup::importBannerAndPhrases
	    if(is_array($arBanner)) {
	        //check isset banner in DB
	        if(!isset($arBanner["isset"])){
	            $arBanner["isset"]=CEDirectBanner::IsEmpty($arBanner['Id']);
	        }	        
	        
	        if($arBanner["State"]!="ARCHIVED"){ //add and update only if banner isn't archive
    	    	$arFields=array(
    	    	    "ID_CATALOG_ITEM"=>($arBanner["idCatalogItem"]>0)?$arBanner["idCatalogItem"]:"NULL",
    	            "TITLE" => $arBanner['TextAd']['Title'],
    	    	    "TITLE2" => $arBanner['TextAd']['Title2'],
    	            "TEXT" => $arBanner['TextAd']['Text'],
    	            "HREF" => CEDirectBanner::addProtokolToURL($arBanner['TextAd']['Href']),
    	    	    "DISPLAY_URL" => $arBanner['TextAd']['DisplayUrlPath'],
    	            "ACTIVE" => ($arBanner['State']!="SUSPENDED"?"Y":"N")
    	        );
    	    	
    	    	//get price
    	    	if($arBanner['TextAd']['PriceExtension']['Price']>0){
    	    	    $arFields["PRICE"]=CEDirectCompany::convertCurrencyFromYa($arBanner['TextAd']['PriceExtension']['Price']);
    	    	}
    	    	else $arFields["PRICE"]=0;
    	    	
    	    	//get sitelinks
    	    	if($arBanner['TextAd']['SitelinkSetId']>0){
    	    	      $res=$obYaExchange->getSitelinks($arBanner['TextAd']['SitelinkSetId']);
    	    	      $arFields["SITELINKS"]=CEDirectBanner::SerializeArrayField($res);
    	    	}
    	    	
    	    	//get image
    	    	if(strlen($arBanner['TextAd']['AdImageHash'])>0){
    	    	    $res=$obYaExchange->getImage($arBanner['TextAd']['AdImageHash']);
    	    	    $arFields["IMAGE"]=serialize($res);
    	    	}
    	    	
    	        if(!$arBanner["isset"]){
    	            $arFields["ID"]=$arBanner['Id'];
    	            $arFields["ID_BANNER_GROUP"] = $arBanner['AdGroupId'];
   	                CEDirectBanner::Add($arFields); 
   	                return 1;
    	        }
    	        else {
   	                CEDirectBanner::Update($arBanner['Id'],$arFields);
   	                return 1;
    	        }
	        }
	        // if in archive we must delete group if it have not banner in group. Must stay here because banner group alredy added early.
	        else{
	            if($arBanner["isset"]) { //if archived and isset in DB we must delete banner
	                CEDirectBanner::Delete($arBanner['Id']);
	            }
	            
	            $rsCnt=CEDirectBanner::GetList(Array(),array("ID_BANNER_GROUP"=>$arBanner['AdGroupId']),array());
	            $arCnt = $rsCnt->Fetch();
	            if($arCnt['CNT']==0){
	                CEDirectBannerGroup::Delete($arBanner['AdGroupId']);
	            }
	            return 0;
	        }	        
	    }
	    return 0;	     
	}
	
	/**
	 * create Banners in Yandex
	 *
	 * @param array $arBanners      new Banners Params array
	 * @param array $baseParamArray      base params
	 * @return array NewBannerIDs or empty array
	 */
	public static function createBannersInYa($arBanners,$baseParamArray=array())
	{
	    global $obYaExchange;
	     
	    if(!is_array($baseParamArray)){
	        $baseParamArray=array();
	    }
	    //add params from BaseArray
	    foreach ($arBanners as &$Banner){
	       $Banner=$obYaExchange->unionParamsArrays($Banner,$baseParamArray);
	    }
	    //send data to Yandex
	    $arNewBannerIDs=$obYaExchange->createNewBanners($arBanners);	    
	    
	    return $arNewBannerIDs;
	}	
	
	/**
	 * fist char to big case in Cyrillic charset
	 *
	 * @param string/array $str  Enter string or array with strings
	 * @return string/array $str  new string or array with fist char to big case
	 */	
	public static function ucfirstCyrillic($str)
	{
	    $isArray=true;
	    if(!is_array($str)) {
	        $str=array($str);
	        $isArray=false;
	    }
	    foreach ($str as &$string){
    	    $string = preg_replace("/^[\ ]+/".(EDIRECT_UTFSITE?"u":""),"",$string);
    	    if(EDIRECT_UTFSITE) {
    	        if(extension_loaded('mbstring')){
        	        $encoding='UTF-8';
        	        $string = ToUpper(mb_substr($string,0,1,$encoding)).mb_substr($string,1,mb_strlen($string,$encoding),$encoding);
    	        }
    	    }
    	    else $string = ToUpper(substr($string,0,1)).substr($string,1,strlen($string));
	    }
	    if($isArray) return $str;
	    else return $str[0];
	}	
	
	/**
	 * Check is element exist in DB
	 *
	 * @param int $ID
	 */	
	public static function IsEmpty($ID)
	{
		return CAllEDirectTable::baseIsEmpty("wtc_edirect_banners", $ID);
	}
			
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */
	public static function Add($arFields)
	{
		return CAllEDirectTable::baseAdd("wtc_edirect_banners", $arFields);
	}

	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */	
	public static function Update($ID,$arFields)
	{			
		return CAllEDirectTable::baseUpdate("wtc_edirect_banners", $ID, $arFields);
	}

	/**
	 * Delete exist banner and related data from DB
	 *
	 * @param int $ID
	 */	
	public static function Delete($ID)
	{
	    return CAllEDirectTable::baseDelete("wtc_edirect_banners", $ID);
	}
	
	//--------------ADDITIONAL FUNCTIONS-----------------
	//--------------------------------------------------------------------
	
	/**
	 * ADD Protokol type (http:// or https://) to URL if it not in URL
	 *
	 * @param strung $ID
	 */
	public static function addProtokolToURL($URL)
	{
	    $URL=trim($URL);
	    //--if not http or https in URL add it---
	    if(!preg_match("/(http:|https:)/", $URL)) {
	        $URL=EDIRECT_URL_PREFIX.$URL;
	    }
	    return $URL;
	}
	
}
?>