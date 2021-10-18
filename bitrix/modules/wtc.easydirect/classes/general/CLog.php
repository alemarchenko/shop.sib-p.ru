<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectLog
 * write/get Logs
 * @category module wtc.easydirect Log
 */
class CEDirectLog extends CAllEDirectTable
{
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
		global $DB;
	
		$FilterFieds=array(
			"ID"=>"number",
			"MESSAGE"=>"string",
			"TYPE"=>"string_equal",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
	
		return CAllEDirectTable::baseGetList("wtc_edirect_sys_log", $FilterFieds, $arOrder, $arFilter,$arGroupBy,$arSelectFields);
	}	
	
	/**
	 * Return only Error and Warning list from DB
	 *
	 * @return CDBResult
	 */
	public static function GetListError()
	{
		$arFilter=Array(
				"TYPE"=>array("E","W")
		);
		$arOrder=array(
				"MODIFIED_DATE"=>"DESC",
				"ID"=>"DESC"
		);
		
		return CEDirectLog::GetList($arOrder, $arFilter);
	}
	
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */	
	public static function Add($arFields)
	{
		// TYPE   [M, W, E]
        return CAllEDirectTable::baseAdd("wtc_edirect_sys_log", $arFields);
	}
	
	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */	
	public static function Update($ID,$arFields)
	{
		return CAllEDirectTable::baseUpdate("wtc_edirect_sys_log", $ID, $arFields);
	}	
	
	/**
	 * Delete exist element from DB
	 *
	 * @param int $ID
	 */	
	public static function Delete($ID)
	{
		return CAllEDirectTable::baseDelete("wtc_edirect_sys_log", $ID);
	}	

	/**
	 * Get log size
	 */
	public static function getLogSize() {
	    global $DB;
	    $err_mess = "<br>Function: CEDirectLog::getLogSize<br>Line: ";
	    $res=$DB->Query("SHOW TABLE STATUS LIKE 'wtc_edirect_sys_log'", false, $err_mess.__LINE__);
	    $arStatus=$res->Fetch();
	    $size=round((($arStatus["Data_length"]/1024)/1024),2);
	    return $size;
	}
		
	/**
	 * Clear old logs
	 */	
 	public static function ClearLog($savetime) {
 	  	global $DB;
 	  	$savetime=intval($savetime);
 	  	$err_mess = "<br>Function: CEDirectLog::ClearLog<br>Line: ";
		$DB->Query("DELETE FROM wtc_edirect_sys_log WHERE MODIFIED_DATE<'".date("Y-m-d H:i:s", AddToTimeStamp(array("DD"=>"-".$savetime)))."'", false, $err_mess.__LINE__);
		$DB->Query("OPTIMIZE TABLE wtc_edirect_sys_log", false, $err_mess.__LINE__);
		return 1;
	  }		  
	
}
?>