<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectYaExchangeStat
 * count exchange statistics by Yandex API exec
 * @category module wtc.easydirect YaExchange
 */
class CEDirectYaExchangeStat extends CAllEDirectTable
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
		$FilterFieds=array(
				"ID"=>"number",
				"NAME"=>"string",
				"CALL_CNT"=>"number",
    		    "UNITS_COST"=>"number",
				"MODIFIED_DATE"=>"date",
				"MODIFIED_IDUSER"=>"number"				
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_sys_exchangestat", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}

	/**
	 * Check is element exist in DB by API function name
	 *
	 * @param string $name   API function name
	 * @return int 0/1
	 */	
	public static function IsEmpty($name)
	{
		$res=CEDirectYaExchangeStat::GetList(array(),array("NAME"=>$name));
		if($arRes=$res->Fetch()) return $arRes["ID"];
		else return 0;	
	}
	
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */	
	public static function Add($arFields)
	{
		return CAllEDirectTable::baseAdd("wtc_edirect_sys_exchangestat", $arFields);
	}
	
	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */	
	public static function Update($ID,$arFields)
	{
		return CAllEDirectTable::baseUpdate("wtc_edirect_sys_exchangestat", $ID, $arFields);
	}
	
	/**
	 * Delete exist element from DB
	 *
	 * @param int $ID
	 */	
	public static function Delete($ID)
	{
		return CAllEDirectTable::baseDelete("wtc_edirect_sys_exchangestat", $ID);
	}	
	
	/**
	 * "++" count to API method
	 *
	 * @param array $arFields   ["NAME","CALL_CNT","UNITS_COST"]   if isset "CALL_CNT" will change COUNT to CALL_CNT
	 */	
	public static function AddCount($arFields)
	{
		if(!isset($arFields['NAME'])) return 0;
		if($ID=CEDirectYaExchangeStat::IsEmpty($arFields['NAME'])){
		    $arParams=array(
		        "CALL_CNT"=>( isset($arFields['CALL_CNT']) ? $arFields['CALL_CNT'] : "!?CALL_CNT+1" ),
		    );
			if(isset($arFields['UNITS_COST'])&&$arFields["UNITS_COST"]>0) $arParams["UNITS_COST"]="!?UNITS_COST+".$arFields["UNITS_COST"];
			
			return CEDirectYaExchangeStat::Update($ID,$arParams);
		}
		else {
		    $arParams=array(
		        "NAME"=>$arFields['NAME'],
		        "CALL_CNT"=>"1",
		        "UNITS_COST"=>( isset($arFields['UNITS_COST']) ? $arFields['UNITS_COST'] : 0 )
		    );
		    return CEDirectYaExchangeStat::Add($arParams);
		}
	}
	
	/**
	 * get COUNT times by API function name
	 *
	 * @param string $name   API function name
	 * @return int   COUNT times
	 */	
	public static function GetCount($name)
	{
		$res=CEDirectYaExchangeStat::GetList(array(),array("NAME"=>$name));
		if($arRes=$res->Fetch()) return $arRes["CALL_CNT"];
		else return 0;
	}
	
	/**
	 * reset COUNT by all API function name to 0
	 *
	 * @return int   0/1
	 */	
 	public static function ClearStat() {
	 	global $DB,$EDirectMain;
	 	$err_mess = "<br>Function: CEDirectYaExchangeStat::ClearStat<br>Line: ";
	 	$notset="";
	 	$DB->Query("DELETE FROM wtc_edirect_sys_exchangestat WHERE CALL_CNT=0", false, $err_mess.__LINE__);
	 	if($EDirectMain->isInstall()!=1) $notset="WHERE NAME not like '%.add'";
	 	$DB->Update("wtc_edirect_sys_exchangestat", array("CALL_CNT"=>0,"UNITS_COST"=>0), $notset, $err_mess.__LINE__);
		return 1;
	  }		  
	
}
?>