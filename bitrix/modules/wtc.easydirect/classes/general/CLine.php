<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

/**
 * Class CEDirectLine
 * Work with need to UPDATE price line of companys
 * @category module wtc.easydirect Line
 */
class CEDirectLine extends CAllEDirectTable
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
			"ID_COMPANY"=>"number",
			"IS_LOCK"=>"string_equal",
			"INSERT_DATE"=>"date",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
	
		return CAllEDirectTable::baseGetList("wtc_edirect_sys_line", $FilterFieds, $arOrder, $arFilter,$arGroupBy,$arSelectFields);
	}	
	
	/**
	 * Return elements list from DB with COMPANY information
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
		$maintblname="SYS_LINE";
		$FilterFieds=array(
				$maintblname."."."ID"=>"number",
				$maintblname."."."ID_COMPANY"=>"number",
				$maintblname."."."IS_LOCK"=>"string_equal",
				$maintblname."."."INSERT_DATE"=>"date",
				"COMPANY.NAME"=>"string"
		);
		
		if(count($arSelectFields)==0){
			$arSelectFields= array(
					$maintblname.".*",
					"COMPANY.NAME as COMPANY_NAME",
				);
		}
		else {
			$arSelectFields=CAllEDirectTable::PrepExArSelect($maintblname,$arSelectFields);
		}

		$from="wtc_edirect_sys_line ".$maintblname."
					LEFT JOIN wtc_edirect_company COMPANY
					ON (COMPANY.ID=".$maintblname.".ID_COMPANY)";
	
		return CAllEDirectTable::baseGetList($from, $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
	
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */
	public static function Add($arFields)
	{
		//ID_COMPANY is enique
		$res=CEDirectLine::GetList(array(),array("ID_COMPANY"=>$arFields["ID_COMPANY"]));
		if($res->Fetch()) return 0;
		
		$arFields['INSERT_DATE']="NOW()";
		return CAllEDirectTable::baseAdd("wtc_edirect_sys_line", $arFields);
	}
	
	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */
	public static function Update($ID,$arFields)
	{
		return CAllEDirectTable::baseUpdate("wtc_edirect_sys_line", $ID, $arFields);
	}	
	
	/**
	 * Delete exist element from DB
	 *
	 * @param int $ID
	 */
	public static function Delete($ID)
	{
		return CAllEDirectTable::baseDelete("wtc_edirect_sys_line", $ID);
	}	
	
	//===========LINE METHODS===========
	/**
	 * add need to update price company to line
	 *
	 * @return int 0/1
	 */	
	public static function addToLine()
	{
		$res=CEDirectCompany::GetList(
		    Array("BET_DATE"=>"ASC"),
		    array(
		        "<BET_DATE"=>ConvertTimeStamp(AddToTimeStamp(array("MI"=>"-".EDIRECT_TIME_TO_SET_PRICES)),"FULL"),
		        "ACTIVE"=>"Y"		        
		    )
		);
		while ($arCompany=$res->Fetch()) {
    	     CEDirectLine::add(array("ID_COMPANY" => $arCompany['ID']));
		}
		return 1;
	}
	
	/**
	 * get companyes from line
	 *
	 * @return array  Companyes IDs
	 */	
	public static function getLine()
	{
		$returnCompany=array();
		
		//get all not LOCK company + get all old element
		$res=CEDirectLine::GetList(
					array("INSERT_DATE"=>"ASC"),
					array(
							"LOGIC"=>"OR",
							"IS_LOCK"=>"N",
							"<INSERT_DATE"=>ConvertTimeStamp(AddToTimeStamp(array("MI"=>"-120")),"FULL")
							)
				);
		$difficult=0;
		while($ar_res=$res->Fetch()) {
		    //difficult calculate
		    $curDifficult=CEDirectCompany::getCompanyPhrasesCnt($ar_res['ID_COMPANY']);
		    if( $difficult>0 && ($difficult+$curDifficult)>EDIRECT_MAX_DIFFICULT_IN_PHRASES ){break;}
		    $difficult+=$curDifficult;
		        
			$returnCompany[]=$ar_res['ID_COMPANY'];
			if($ar_res['IS_LOCK']=="Y") {			    
			    CEDirectLine::Update($ar_res['ID'], array("INSERT_DATE"=>ConvertTimeStamp(AddToTimeStamp(array("MI"=>"-10")),"FULL")));
			    //--write log--
			    $rsData = CEDirectCompany::GetByID($ar_res['ID_COMPANY']);
			    $arData = $rsData->Fetch();
			    CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CLINE_LOG_MESS',array("#NAME#"=>$arData['NAME'],"#ID#"=>$ar_res['ID_COMPANY'])),"TYPE"=>"W"));
			    //---------------
			}
			else CEDirectLine::Update($ar_res['ID'], array("IS_LOCK"=>"Y"));
		}
		
		if(count($returnCompany)) return $returnCompany;
		else return array();
	}
	
	/**
	 * delete line str for Company ID
	 *
	 * @param int $CompanyID  Company ID
	 */	
	public static function DeleteCompanyFromLine($CompanyID)
	{
		$res=CEDirectLine::GetList(array(),array("ID_COMPANY"=>$CompanyID),false,array("ID"));
		while($ar_res=$res->Fetch()) CEDirectLine::Delete($ar_res['ID']);
	}	
	
}
?>