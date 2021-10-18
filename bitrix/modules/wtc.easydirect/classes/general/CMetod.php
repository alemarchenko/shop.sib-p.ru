<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

/**
 * Class CEDirectMetod
 * Work with METHODs (BET MAX_PRICE)
 * @category module wtc.easydirect metods
 */
class CEDirectMetod extends CAllEDirectTable
{

    /**
     * Return element from DB by ID
     *
     * @param int $ID
     * @param array $arSelectFields     What fields need to select
     * @uses CEDirectMetod::GetList()
     * @return CDBResult
     */
	public static function GetByID($ID,$arSelectFields=Array())
	{
		return CEDirectMetod::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
		$FilterFields=array(
			"ID"=>"number",
			"NAME"=>"string",
			"FNAME"=>"string",
		    "TYPE"=>"string",
			"IS_IMPORTANT"=>"string_equal",
			"IS_USER"=>"string_equal",
			"DESCRIPTION"=>"string",
			"SORT"=>"number",
			"ACTIVE"=>"string_equal",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_metod", $FilterFields, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
	
	/**
	 * GET TYPEs Array
	 *
	 * @return array
	 */
	public static function GetTypeArray()
	{
	    $arType=array(
	        "SEARCH"=>GetMessage('EDIRECT_METOD_TYPE_SERACH'),
	        "RSYA"=>GetMessage('EDIRECT_METOD_TYPE_RSYA'),
	        "UNI"=>GetMessage('EDIRECT_METOD_TYPE_UNI')
	    );	    
	    return $arType;
	}	
	
	/**
	 * Check is element exist in DB
	 *
	 * @param int $ID
	 */
	public static function IsEmpty($ID)
	{
		return CAllEDirectTable::baseIsEmpty("wtc_edirect_metod", $ID);
	}
			
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */
	public static function Add($arFields)
	{
		return CAllEDirectTable::baseAdd("wtc_edirect_metod", $arFields);
	}

	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */
	public static function Update($ID,$arFields)
	{			
		return CAllEDirectTable::baseUpdate("wtc_edirect_metod", $ID, $arFields);
	}

	/**
	 * Delete exist element from DB
	 *
	 * @param int $ID
	 */
	public static function Delete($ID)
	{
	    //search mcondition with metods, not delete if isset
	    $rsCnt=CEDirectMcondition::GetList(Array(), Array("ID_METOD"=>$ID),array());
	    $arCnt = $rsCnt->Fetch();
	    if($arCnt['CNT']==0){
	        return CAllEDirectTable::baseDelete("wtc_edirect_metod", $ID);
	    }
	    else return 0;
	}
	
	//=======WORK WITH USER CALCULATE METHODS======
	/**
	 * get list of name user methods from files
	 *
	 * @param string $prefix
	 * @return array  methods names array
	 */
	public static function getListUserMethods($prefix="user"){
	    $arListUserMethods=array();
	     
	    //get user methods as anonym functions
	    $method_folder=$_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/wtc.easydirect/user_methods";
	    $dir = opendir($method_folder);
	    while (( $file = readdir($dir)))
	    {
	        if( is_file ($method_folder."/".$file) && strpos($file,".php")>0 )
	        {
	            $fname=str_replace(".php", "", $file);
	            if(preg_match("/^".$prefix."_/",$fname)) {//collect files started from $prefix
	               $arListUserMethods[]=$fname;
	            }
	        }
	    }
	    closedir ($dir);
	    //-------------------------
	     
	    return $arListUserMethods;
	}
	
	/**
	 * get list of name user methods from Handlers Deprecated system
	 *
	 * @return array  methods names array
	 */
	public static function getListUserHandlerMethods(){
	    $arListUserMethods=array();
	
	    //get old user methods made in Bitrix Handler System
	    $rsEvents = GetModuleEvents("wtc.easydirect", "userCalculateMethod");
	    while ($arEvent = $rsEvents->Fetch())
	    {
	        $fname=$arEvent["CALLBACK"][1];
	        if(preg_match("/^sys_/",$fname)) continue; //skip all name start from sys
	        $arListUserMethods[]=$fname;
	    }
	    //--------------------------
	
	    return $arListUserMethods;
	}	
	
}
?>