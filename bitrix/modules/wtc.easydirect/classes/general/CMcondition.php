<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectMcondition
 * Work with conditions (BET MAX_PRICE)
 * @category module wtc.easydirect metods
 */
class CEDirectMcondition extends CAllEDirectTable
{

    /**
     * Return element from DB by ID
     *
     * @param int $ID
     * @param array $arSelectFields     What fields need to select
     * @uses CEDirectMcondition::GetList()
     * @return CDBResult
     */
	public static function GetByID($ID,$arSelectFields=Array())
	{
		return CEDirectMcondition::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
			"ID_BANNER_GROUP"=>"number",
			"FROM_HOUR"=>"number",
			"TO_HOUR"=>"number",
			"MAX_PRICE"=>"number",
			"ID_METOD"=>"number",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_mcondition", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}

	/**
	 * Return elements list from DB with Metod information
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
		$maintblname="MCONDITION";
		$FilterFieds=array(
				$maintblname."."."ID"=>"number",
				$maintblname."."."ID_COMPANY"=>"number",
				$maintblname."."."ID_BANNER_GROUP"=>"number",
				$maintblname."."."FROM_HOUR"=>"number",
				$maintblname."."."TO_HOUR"=>"number",
				$maintblname."."."MAX_PRICE"=>"number",
				$maintblname."."."ID_METOD"=>"number",
				"METOD.NAME"=>"string",
				"METOD.FNAME"=>"string",
		);
		
		if(count($arSelectFields)==0){
			$arSelectFields= array(
					$maintblname.".*",
					"METOD.NAME as METOD_NAME",
					"METOD.FNAME as METOD_FNAME",
				);
		}
		else {
			$arSelectFields=CAllEDirectTable::PrepExArSelect($maintblname,$arSelectFields);
		}

		$from="wtc_edirect_mcondition ".$maintblname."
					LEFT JOIN wtc_edirect_metod METOD
					ON (METOD.ID=".$maintblname.".ID_METOD)";
	
		return CAllEDirectTable::baseGetList($from, $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
		
	/**
	 * Return metod param for BannerGroup or Company
	 *
	 * @param int $bannerID
	 * @param int $companyID
	 * @return array METOD params
	 */	
	public static function getCurrentMetod($bannerGroupID,$companyID)
	{
		$hour=date("H");
			
		$res = CEDirectMcondition::GetListEx(array("ID_BANNER_GROUP"=>"DESC","ID"=>"ASC"),array("LOGIC"=>"OR","MCONDITION.ID_BANNER_GROUP"=>$bannerGroupID,"MCONDITION.ID_COMPANY"=>$companyID));
		while($ar_res=$res->Fetch()) {
			if($ar_res['FROM_HOUR']>$ar_res['TO_HOUR']) {
				if(!($hour<=$ar_res['FROM_HOUR']&&$hour>=$ar_res['TO_HOUR'])) return array("ID"=>$ar_res['ID'],"MAX_PRICE"=>$ar_res['MAX_PRICE'],"FNAME"=>$ar_res['METOD_FNAME']);
			}
			if($hour>=$ar_res['FROM_HOUR']&&$hour<$ar_res['TO_HOUR']) return array("ID"=>$ar_res['ID'],"MAX_PRICE"=>$ar_res['MAX_PRICE'],"FNAME"=>$ar_res['METOD_FNAME']);
		}
			
		return array();
	}	
	
	/**
	 * Check is element exist in DB
	 *
	 * @param int $ID
	 */	
	public static function IsEmpty($ID)
	{
		return CAllEDirectTable::baseIsEmpty("wtc_edirect_mcondition", $ID);
	}
			
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */	
	public static function Add($arFields)
	{
	    $arFields=CEDirectMcondition::CheckFields($arFields);
		return CAllEDirectTable::baseAdd("wtc_edirect_mcondition", $arFields);
	}

	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */
	public static function Update($ID,$arFields)
	{			
	    $arFields=CEDirectMcondition::CheckFields($arFields);
		return CAllEDirectTable::baseUpdate("wtc_edirect_mcondition", $ID, $arFields);
	}

	/**
	 * Delete exist element from DB
	 *
	 * @param int $ID
	 */	
	public static function Delete($ID)
	{
		return CAllEDirectTable::baseDelete("wtc_edirect_mcondition", $ID);
	}
	
	
	/**
	 * Check Fields before send to DB
	 *
	 * @param array $arFields      Element fields
	 */
	public static function CheckFields($arFields)
	{
        if(isset($arFields["MAX_PRICE"])){
            $arFields["MAX_PRICE"]=str_replace(",",".",$arFields["MAX_PRICE"]);
            $arFields["MAX_PRICE"]=preg_replace('/[^0-9\.]/', '', $arFields["MAX_PRICE"]);
            $arFields["MAX_PRICE"]=floatval($arFields["MAX_PRICE"]);
        }
        
        return $arFields;
	}	
}
?>