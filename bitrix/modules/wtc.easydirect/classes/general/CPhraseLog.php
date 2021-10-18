<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectPhraseLog
 * Work with Phrases Log
 * @category module wtc.easydirect PhrasesLog
 */
class CEDirectPhraseLog extends CAllEDirectTable
{

    /**
     * Return element from DB by ID
     *
     * @param int $ID
     * @param array $arSelectFields     What fields need to select
     * @uses CEDirectPhraseLog::GetList()
     * @return CDBResult
     */    
	public static function GetByID($ID,$arSelectFields=Array())
	{
		return CEDirectPhraseLog::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
		    "ID_PHRASE"=>"number",
			"ID_BANNER_GROUP"=>"number",
		    "ID_COMPANY"=>"number",
			"PRICE"=>"number",
			"SHOWS"=>"number",
			"CLICKS"=>"number",
		    "SEARCHCTR"=>"number",
		    "SEARCHPLACE"=>"number",
		    "SEARCHPRICES"=>"string",		    
			"CONTEXTSHOWS"=>"number",
			"CONTEXTCLICKS"=>"number",
		    "CONTEXTCTR"=>"number",
		    "CONTEXTPRICE"=>"number",
		    "CONTEXTCOVERAGE"=>"string",
		    "CONTEXTPRICES"=>"string",
			"CHECK_DATE"=>"date",
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_phrases_log", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
	
	//=========ADDITIONAL FUNCTIONS=======
	/**
	 * serialize data PRICES to JSON (more compact data) & rebuild array
	 *
	 * @param sring $serializePrices   serialize data
	 * @return array $arPrices   rebuild array
	 */
	public static function serializePricesToJson($serializePrices)
	{
	    $arPrices=array();
	    foreach (CEDirectPhrase::UnSerializeArrayField($serializePrices) as $val){
	        if(isset($val["Position"])){
	            $arPrices[$val["Position"]]=array("Bid"=>$val["Bid"],"Price"=>$val["Price"]);
	        }
	        else if(isset($val["Probability"])){
	            $arPrices[$val["Probability"]]=$val["Price"];
	        }	        
	    }
	    
	    return \Bitrix\Main\Web\Json::encode($arPrices, $options = null);
	}	
	
	/**
	 *decode JSON data
	 *
	 * @param sring $data
	 */
	public static function decodeJson($data)
	{
	    return \Bitrix\Main\Web\Json::decode($data);
	}	
	
	//==============================
	/**
	 * Write Phrases States to Log
	 *
	 * @param array $arPhrasesIDs      Pharse IDs need to save
	 */
	public static function WritePhrasesLog($arPhrasesIDs)
	{
	    $stepInSecond=EDIRECT_STEP_PHRASE_LOG*60;
	    $CHECK_DATE=ConvertTimeStamp(floor(time()/$stepInSecond)*$stepInSecond,"FULL");
	    
	    //get phrases wrote log already
	    $arPhrasesIssetLog=array();
	    $res=CEDirectPhraseLog::GetList(array("ID"=>"ASC"),array("CHECK_DATE"=>$CHECK_DATE),false,array("ID","ID_COMPANY","ID_PHRASE"));
	    while($arPhraseLog=$res->Fetch()){
	        $arPhrasesIssetLog[$arPhraseLog["ID_PHRASE"]]=$arPhraseLog["ID"];
	        //do not update alredy exist info
	        $key=array_search($arPhraseLog["ID_PHRASE"], $arPhrasesIDs);
	        if($key!==false){unset($arPhrasesIDs[$key]);}
	    }
	    
	    if(count($arPhrasesIDs)==0) return 1;
	    
	    $prevBannerGroupID=0;
	    $companyID=0;
	    $arMetod=array();
	    $res=CEDirectPhrase::GetListEx(array(),array("PHRASE.ID"=>$arPhrasesIDs,"!BANNER_GROUP.SERVING_STATUS"=>"RARELY_SERVED"),array("PHRASE.ID"));
	    $GLOBALS["DB"]->StartTransaction();
	    while($arPhrase=$res->Fetch()){
	        //get Metod
	        if($prevBannerGroupID!=$arPhrase["ID_BANNER_GROUP"]){
	            //get current metod
	            $arMetod=CEDirectMcondition::getCurrentMetod($arPhrase["ID_BANNER_GROUP"],$arPhrase["COMPANY_ID"]);
	            $prevBannerGroupID=$arPhrase["ID_BANNER_GROUP"];
	        }
	        
	        $arPhraseLogFields=array(
	            "ID_PHRASE" => $arPhrase["ID"],
	            "ID_BANNER_GROUP" => $arPhrase["ID_BANNER_GROUP"],
	            "ID_COMPANY" => $arPhrase["COMPANY_ID"],
	            "MAX_PRICE"=>$arMetod['MAX_PRICE'],
	            "PRICE" => $arPhrase["PRICE"],
	            "PRICE_ON_SEARCH" => $arPhrase["PRICE_ON_SEARCH"],
	            "SHOWS" => $arPhrase["SHOWS"],
	            "CLICKS" => $arPhrase["CLICKS"],
	            "SEARCHCTR" => ($arPhrase['SHOWS']?round(($arPhrase['CLICKS']*100)/$arPhrase['SHOWS'],2):0),
	            "SEARCHPLACE"=>0,
	            "CONTEXTSHOWS" => $arPhrase["CONTEXTSHOWS"],
	            "CONTEXTCLICKS" => $arPhrase["CONTEXTCLICKS"],
	            "CONTEXTCTR" => ($arPhrase['CONTEXTSHOWS']?round(($arPhrase['CONTEXTCLICKS']*100)/$arPhrase['CONTEXTSHOWS'],2):0),
	            "CONTEXTPRICE" => $arPhrase["CONTEXTPRICE"],
	            "CONTEXTCOVERAGE"=>0,
	            "CHECK_DATE" => $CHECK_DATE
	        );
	        
	        if($arPhrase["COMPANY_IS_RSYA"]=="N"){
	            $arPhraseLogFields["SEARCHPRICES"] = CEDirectPhraseLog::serializePricesToJson($arPhrase["PRICES"]);
    	        //SEARCHPLACE calculate
    	        if($arPhrase["PRICE"]>0){
        	        $PRICES=CEDirectPhrase::UnSerializeArrayField($arPhrase['PRICES']);
        	        foreach ($PRICES as $value){
        	            if($value["Bid"]<=$arPhrase["PRICE"]) {
        	                preg_match("/P([0-9]{2})/",$value["Position"],$regs);
        	                $arPhraseLogFields["SEARCHPLACE"]=$regs[1];
        	                break;
        	            }
        	        }	        
        	        if(!$arPhraseLogFields["SEARCHPLACE"]) $arPhraseLogFields["SEARCHPLACE"]=31;
    	        }
	        }
	        else {
	            $arPhraseLogFields["CONTEXTPRICES"] = CEDirectPhraseLog::serializePricesToJson($arPhrase["CONTEXTCOVERAGE"]);
    	        //CONTEXTCOVERAGE calculate
    	        if($arPhrase["CONTEXTPRICE"]>0){
        	        $PRICES=CEDirectPhrase::UnSerializeArrayField($arPhrase['CONTEXTCOVERAGE']);
        	        if(count($PRICES)==0) {$arPhraseLogFields["CONTEXTCOVERAGE"]=0;}
        	        else{
        	            foreach ($PRICES as $value){
        	                if($value["Price"]<=$arPhrase["CONTEXTPRICE"]) {
        	                    $arPhraseLogFields["CONTEXTCOVERAGE"]=$value["Probability"];
        	                    break;
        	                }
        	            }
        	            if(!$arPhraseLogFields["CONTEXTCOVERAGE"]) $arPhraseLogFields["CONTEXTCOVERAGE"]=10;
        	        }	        
    	        }
	        }
	        
	        //write new or update log through transaction
	        if(isset($arPhrasesIssetLog[$arPhrase["ID"]])) { // check isset log, Update !!Now disabled. We dont update exist log. See above.
	            CEDirectPhraseLog::Update($arPhrasesIssetLog[$arPhrase["ID"]],$arPhraseLogFields);
	        }
	        else { //New
	            CEDirectPhraseLog::Add($arPhraseLogFields);
	        }	        
	    }
	    $GLOBALS["DB"]->Commit();
    	    
	    return 1;
	}
	
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */	
	public static function Add($arFields)
	{
		return CAllEDirectTable::baseAdd("wtc_edirect_phrases_log", $arFields);
	}
	
	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */
	public static function Update($ID,$arFields)
	{
	    return CAllEDirectTable::baseUpdate("wtc_edirect_phrases_log", $ID, $arFields);
	}	

	/**
	 * Delete exist element and related data from DB
	 *
	 * @param int $phraseID      Pharse ID
	 */	
	public static function DeletePhraseLog($phraseID)
	{
	    global $DB;
	    $err_mess = "<br>Function: CEDirectPhraseLog::DeletePhraseLog<br>Line: ";
	    $DB->Query("DELETE FROM wtc_edirect_phrases_log WHERE ID_PHRASE=".$phraseID, false, $err_mess.__LINE__);
	    return 1;	    
	}
	
	/**
	 * Get log size
	 */
	public static function getLogSize() {
	    global $DB;
	    $err_mess = "<br>Function: CEDirectPhraseLog::getLogSize<br>Line: ";
	    $res=$DB->Query("SHOW TABLE STATUS LIKE 'wtc_edirect_phrases_log'", false, $err_mess.__LINE__);
	    $arStatus=$res->Fetch();
	    $size=round((($arStatus["Data_length"]/1024)/1024),2);
	    return $size;
	}	
	
	/**
	 * Clear old Log
	 */
	public static function ClearLog($savetime)
	{
	    global $DB;
	    $savetime=intval($savetime);
	    $err_mess = "<br>Function: CEDirectPhraseLog::ClearLog<br>Line: ";
	    $DB->Query("DELETE FROM wtc_edirect_phrases_log WHERE MODIFIED_DATE<'".date("Y-m-d H:i:s", AddToTimeStamp(array("DD"=>"-".$savetime)))."'", false, $err_mess.__LINE__);
	    $DB->Query("OPTIMIZE TABLE wtc_edirect_phrases_log", false, $err_mess.__LINE__);
	    return 1;	    
	}
	
}
?>