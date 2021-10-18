<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

/**
 * Class CEDirectShowTbl
 * Show table by parameters 
 * @category module wtc.easydirect
 */
class CEDirectShowTbl
{
	private $header=array();
	private $arFields=array();
	private $arTableProps=array();
	private $arTableId="edtbl1";
	
	/**
	 * new table's constructor
	 *
	 * @param array $header       header table params array
	 * @param array $arFields      string of values array | may have element named "Props" for "tr" addition parametrs
	 * @param array $arTableProps     addition parametrs for table
	 * @param array $arTableId     table id for action panel, !not for HTML
	 */	
	public function __construct($header, $arFields, $arTableProps=array(), $arTableId="edtbl1")
	{
		$this->header=$header;
		$this->arFields=$arFields;
		$this->arTableProps=$arTableProps;
		$this->arTableId=$arTableId;
	}
	
	/**
	 * build table
	 *
     * @param boolean $showActionsPanel   if true, show panel with actions (excel export)
	 * @return string   table in string
	 */	
	public function ShowTbl($showActionsPanel=false)
	{
	    /** @global CMain $APPLICATION */
	    global $APPLICATION;
	    
		$str='';
		
		$str.='<table '.$this->GetPropsStr(array_merge(array("class"=>"wtc-easydirect-show-data-table"),$this->arTableProps)).'>';
		
		//show actions
		if($showActionsPanel){
		    $link = DeleteParam(array("mode","edtblid"));
		    $link = $APPLICATION->GetCurPage()."?mode=excel&edtblid=".$this->arTableId.($link <> ""? "&".$link:"");
		    $str.='<tr class="action-panel">';
	        $str.='<td colspan="'.count($this->header).'"><div><a class="excel" href="'.$link.'"><i></i>'.GetMessage('EDIRECT_SHOWTBL_EXCEL').'</a></div></td>';
		    $str.='</tr>';		    
		}
		
		$str.='<tr>';
					foreach($this->header as $val){
					            $str.='<th>'.$val.'</th>';
					}
		$str.='</tr>';

		if(count($this->arFields)>0){
			foreach($this->arFields as $coll){
					  if(isset($coll['Props'])) {$str.='<tr '.$this->GetPropsStr($coll['Props']).'>'; unset($coll['Props']);}
				      else $str.='<tr>';
				      foreach($coll as $val){
				      	$str.='<td>'.$val.'</td>';
				      }
					  $str.='</tr>';
			}
		}
		else {
		      $str.='<tr><td colspan="'.count($this->header).'">'.GetMessage('EDIRECT_SHOWTBL_NO_ELEMENTS').'</td></tr>';
		}	
		
		$str.='</table>';

		return $str;
	}
	
	/**
	 * echo excel
	 */	
	public function DisplayExcel(){
	    /** @global CMain $APPLICATION */
	    global $APPLICATION;
	    	    
	    $CURRENT_PAGE = EDIRECT_URL_PREFIX;
	    $CURRENT_PAGE .= $_SERVER["HTTP_HOST"];
	     
	    echo '
    		<html>
    		<head>
    		<title>'.$APPLICATION->GetTitle().'</title>
    		<meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET.'">
    		<style>
    			td {mso-number-format:\@;}
    			.number0 {mso-number-format:0;}
    			.number2 {mso-number-format:Fixed;}
    		</style>
    		</head>
    		<body>';
	    
		echo '<table>';
		echo '<tr>';
					foreach($this->header as $val){
					           if(preg_match("/(checkbox|del-phrase|&nbsp;)/", $val)){continue;}
					           echo '<td>'.$val.'</td>';
					}
		echo '</tr>';

		if(count($this->arFields)>0){
			foreach($this->arFields as $coll){
					  if(isset($coll['Props'])) unset($coll['Props']);
				      echo '<tr>';
				      foreach($coll as $val){
				        if(preg_match("/(checkbox|edit-phrase|del-phrase|&nbsp;)/", $val)){continue;}
				        if(preg_match("/class=\"null\"/", $val)){$val=trim(strip_tags($val));}
				        $val=preg_replace("/lang=/", "LANG=", $val);
				        $val=preg_replace("/href=\"\//", "href=\"".$CURRENT_PAGE."/", $val);				        
				      	echo '<td>'.$val.'</td>';
				      }
					  echo '</tr>';
			}
		}
		
		echo '</table>';
	    echo '</body></html>'; 
	}
	
	/**
	 * return data as EXCEL
	 *
	 * @return string   html document for execel
	 */
	public function CheckListMode(){
		/** @global CMain $APPLICATION */
		global $APPLICATION;
	    
	    if (!isset($_REQUEST["mode"]))
	        return;
	    
	    //not this table
	    if (isset($_REQUEST["edtblid"]) && $_REQUEST["edtblid"]!=$this->arTableId )
	        return;
	    
	    if($_REQUEST["mode"]=='excel')
	    {
	        $fname = basename($APPLICATION->GetCurPage(), ".php");
	        // http response splitting defence
	        $fname = str_replace(array("\r", "\n"), "", $fname);
	    
	        header("Content-Type: application/vnd.ms-excel");
	        header("Content-Disposition: filename=".$fname.".xls");
            $this->DisplayExcel();	        
	        require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin_after.php");
	        die();
	    }	    
	}	
	
	/**
	 * build props string
	 *
	 * @param array  $arProps   props
	 * @return string   props in string
	 */	
	private function GetPropsStr($arProps){
		$preProps=array();
		foreach ($arProps as $key=>$val){
			if(isset($preProps[$key])) $preProps[$key]=$preProps[$key]." ".$val;
			else $preProps[$key]=$val;
		}
		
		$strProps="";
		foreach ($preProps as $key=>$val){
			$strProps.=" ".$key.'="'.$val.'"';
		}	

		return $strProps;
	}

	/**
	 * return color name by TYPE
	 *
	 * @param char  $TYPE  
	 * @return string   color name
	 */	
	public static function GetColorCSS($TYPE){
		$arClasses=array(
			"E"=>"red",
			"W"=>"yellow",
		);
	
		return $arClasses[$TYPE];
	}
	
}
?>