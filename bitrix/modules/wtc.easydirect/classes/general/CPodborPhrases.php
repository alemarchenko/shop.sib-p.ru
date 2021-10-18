<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectPodborPhrases
 * Work with Reports Phrases
 * @category module wtc.easydirect CreateBanners
 */
class CEDirectPodborPhrases extends CAllEDirectTable
{
    /**
     * Return element from DB by ID
     * 
     * @param int $ID
     * @param array $arSelectFields     What fields need to select 
     * @uses CEDirectPodborReports::GetList()
     * @return CDBResult
     */
	public static function GetByID($ID,$arSelectFields=Array())
	{
		return CEDirectPodborPhrases::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
		    "NAME"=>"string",
			"SHOWS"=>"number",
		    "SHOWS_QUOTES"=>"number",
		    "TYPE"=>"string_equal",
		    "SORT"=>"number",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_podbor_phrases", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
	
	/**
	 * Check is element exist in DB
	 *
	 * @param int $ID
	 */
	public static function IsEmpty($ID)
	{
		return CAllEDirectTable::baseIsEmpty("wtc_edirect_podbor_phrases", $ID);
	}
			
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */
	public static function Add($arFields)
	{
	    //delete quotes
	    if(isset($arFields["NAME"])) $arFields["NAME"]=str_replace('"', '', $arFields["NAME"]);
	    //check isset phrase
	    $res=CEDirectPodborPhrases::GetList(Array(), Array("NAME"=>$arFields["NAME"],"TYPE"=>$arFields["TYPE"]));
	    if($arPhrase=$res->Fetch()) {
	        return 0;
	    }
	    else	return CAllEDirectTable::baseAdd("wtc_edirect_podbor_phrases", $arFields);
	}

	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */	
	public static function Update($ID,$arFields)
	{			
	    //delete quotes
	    if(isset($arFields["NAME"])) $arFields["NAME"]=str_replace('"', '', $arFields["NAME"]);
		return CAllEDirectTable::baseUpdate("wtc_edirect_podbor_phrases", $ID, $arFields);
	}

	/**
	 * Delete exist element from DB
	 *
	 * @param int $ID
	 */	
	public static function Delete($ID)
	{
		return CAllEDirectTable::baseDelete("wtc_edirect_podbor_phrases", $ID);
	}
	
	/**
	 * Delete exist Phrase for match 
	 *
	 * @param string str
	 */
	public static function DeleteForMatch($str)
	{
	    $cnt=0;
	    $rsData =CEDirectPodborPhrases::GetList(array(), array("NAME"=>"%".$str."%","TYPE"=>"S"));
	    while ($arPhrase=$rsData->Fetch()) {
	        CEDirectPodborPhrases::Delete($arPhrase["ID"]);
	        $cnt++;
	    }
	    return $cnt;	     
	}	
}
?>