<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectPodborReports
 * Work with YandexReports
 * @category module wtc.easydirect CreateBanners
 */
class CEDirectPodborReports extends CAllEDirectTable
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
		return CEDirectPodborReports::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
			"YAREPORT_ID"=>"number",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_podbor_reports", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
	
	/**
	 * Check is element exist in DB
	 *
	 * @param int $ID
	 */
	public static function IsEmpty($ID)
	{
		return CAllEDirectTable::baseIsEmpty("wtc_edirect_podbor_reports", $ID);
	}
			
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */
	public static function Add($arFields)
	{
		return CAllEDirectTable::baseAdd("wtc_edirect_podbor_reports", $arFields);
	}

	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */	
	public static function Update($ID,$arFields)
	{			
		return CAllEDirectTable::baseUpdate("wtc_edirect_podbor_reports", $ID, $arFields);
	}

	/**
	 * Delete exist element from DB
	 *
	 * @param int $ID
	 */	
	public static function Delete($ID)
	{
		return CAllEDirectTable::baseDelete("wtc_edirect_podbor_reports", $ID);
	}
	
	/**
	 * Delete report By Yandex report ID
	 *
	 * @param int $yandexReportID
	 */
	public static function DeleteByYandexID($yandexReportID)
	{
	    global $obYaExchange;
	    //delete from Yandex
	    $obYaExchange->delWsreport($yandexReportID);
	    //delete from BD
	    CEDirectPodborReports::Delete(CEDirectPodborReports::getIDByYandexID($yandexReportID));	    
	}	
	
	/**
	 * Get Report ID in BD By Yandex report ID
	 *
	 * @param int $yandexReportID
	 * @return int ID
	 */
	public static function getIDByYandexID($yandexReportID)
	{
	    $rsData =CEDirectPodborReports::GetList(array(),array("YAREPORT_ID"=>$yandexReportID));
	    if($arReport=$rsData->Fetch()) {
	        return $arReport["ID"];
	    }	     
	    else {
	        return 0;
	    }
	}
	
	/**
	 * Delete All exist element from DB
	 */
	public static function DeleteAll()
	{
	    global $obYaExchange;
	    
	    //delete from BD
	    $rsData =CEDirectPodborReports::GetList(array("ID"=>"ASC"));
        while ($arReport=$rsData->Fetch()) {
            CEDirectPodborReports::Delete($arReport["ID"]);
        }
        
        //delete from Yandex
        $reportList=$obYaExchange->getWsreportList();
        foreach ($reportList as $report)
        {
            if($report['ReportID']>0) $obYaExchange->delWsreport($report['ReportID']);
        }
        
	}	
	
	/**
	 * Get CNT reports
	 * 
	 * @param int $cntNoSend  if true return CNT no send reports, another return all CNT
	 * @return int CNT
	 */
	public static function getCnt($cntNoSend=false)
	{
	    if($cntNoSend) $arFilter=array("YAREPORT_ID"=>0);
	    else $arFilter=array();
        $rsData =CEDirectPodborReports::GetList(array(),$arFilter,array());
        $arCnt=$rsData->Fetch();
        return $arCnt["CNT"];
	}
	
	/**
	 * Create reports by Phrases, send it in Yandex
	 *
	 *@param bolean $checkQuotes  if TRUE create reports with quotes
	 * @return int 1/0
	 */	
	public static function createWsreport($checkQuotes=false)
	{
	    $arPackages=array();
	    $i=0;
	    $arFilter=array("TYPE"=>"S");
	    if($checkQuotes) $arFilter["SHOWS_QUOTES"]=0;
	    else $arFilter["SHOWS"]=0;
	    $rsData =CEDirectPodborPhrases::GetList(array("ID"=>"ASC"), $arFilter);
	    while ($arPhrase=$rsData->Fetch()) {
	        $clearPhrase=$arPhrase['NAME'];
	        //check cnt words, will not be big than 7
	        $words=explode(" ",$clearPhrase);
	        if(count($words)>7) continue;
	        
	        if($checkQuotes) $arPhrase['NAME']='"'.$clearPhrase.'"';
	        $arPackages[$i][]=$arPhrase['NAME'];
	        if(count($arPackages[$i])==10) $i++;
	    }
	    	
	    if(count($arPackages))
	    {
	        foreach ($arPackages as $package){
	            $arFields=array(
	                "PHRASES" => serialize($package),
	                "YAREPORT_ID"=>0
	            );
	            CEDirectPodborReports::Add($arFields);
	        }
	        return 1;
	    }
	    else return 0;
	}	
	
}
?>