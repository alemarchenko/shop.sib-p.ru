<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

//incluse IblockModule
CModule::IncludeModule("iblock");
//include Highloadblock
CModule::IncludeModule('highloadblock');
/**
 * Class CEDirectTemplates
 * Work with BANNERS TEMPLATES
 * @category module wtc.easydirect templates
 */
class CEDirectTemplates extends CAllEDirectTable
{

    /**
     * Return element from DB by ID
     *
     * @param int $ID
     * @param array $arSelectFields     What fields need to select
     * @uses CEDirectTemplates::GetList()
     * @return CDBResult
     */
	public static function GetByID($ID,$arSelectFields=Array())
	{
		return CEDirectTemplates::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
		    "IBLOCK_ID"=>"number",
		    "FOR_SECTIONS"=>"string_equal",
			"NAME"=>"string",
		    "TITLE"=>"string",
		    "TITLE2"=>"string",
		    "TEXT"=>"string",
		    "HREF"=>"string",
		    "DISPLAY_URL"=>"string",
		    "PRICE"=>"string",
		    "SITELINKS"=>"string",
		    "PHRASES"=>"string",
			"MODIFIED_DATE"=>"date",
			"MODIFIED_IDUSER"=>"number"
		);
		
		return CAllEDirectTable::baseGetList("wtc_edirect_templates", $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
	}
	
	/**
	 * Check is element exist in DB
	 *
	 * @param int $ID
	 */
	public static function IsEmpty($ID)
	{
		return CAllEDirectTable::baseIsEmpty("wtc_edirect_templates", $ID);
	}
			
	/**
	 * Add new element in DB
	 *
	 * @param array $arFields      Element fields
	 */
	public static function Add($arFields)
	{
		return CAllEDirectTable::baseAdd("wtc_edirect_templates", $arFields);
	}

	/**
	 * Update exist element in DB
	 *
	 * @param int $ID
	 * @param array $arFields      Element fields
	 */
	public static function Update($ID,$arFields)
	{			
		return CAllEDirectTable::baseUpdate("wtc_edirect_templates", $ID, $arFields);
	}

	/**
	 * Delete exist element from DB
	 *
	 * @param int $ID
	 */
	public static function Delete($ID)
	{
        return CAllEDirectTable::baseDelete("wtc_edirect_templates", $ID);
	}
	
	//=========TEMLATE FUNCTIONS=============
	/**
	 * GET Replace Fields Array for Templates
	 *
	 * @param int $IBLOCK_ID  GET property types from this IB
	 * @param int 0/1 $FOR_SECTIONS  is it for section template
	 * @return array
	 */
	public static function getReplaceFieldsArray($IBLOCK_ID,$FOR_SECTIONS=0)
	{
	   if($IBLOCK_ID>0){
    	   //DEFAULT Fields
	       if(!$FOR_SECTIONS){
        	   $arTmplFields[]=array("ELEMENT.ID",GetMessage('EDIRECT_TEMPLATES_FNAME_ELEMENT_ID'));
        	   $arTmplFields[]=array("ELEMENT.CODE",GetMessage('EDIRECT_TEMPLATES_FNAME_ELEMENT_CODE'));
	       }
	       $arTmplFields[]=array("ELEMENT.NAME",GetMessage('EDIRECT_TEMPLATES_FNAME_ELEMENT_NAME'));	       
    	   $arTmplFields[]=array("SECTION.ID",GetMessage('EDIRECT_TEMPLATES_FNAME_SECTION_ID'));
    	   $arTmplFields[]=array("SECTION.CODE",GetMessage('EDIRECT_TEMPLATES_FNAME_SECTION_CODE'));
    	   $arTmplFields[]=array("SECTION.NAME",GetMessage('EDIRECT_TEMPLATES_FNAME_SECTION_NAME'));    
    	   $arTmplFields[]=array("PARENT_SECTION.NAME",GetMessage('EDIRECT_TEMPLATES_FNAME_PARENT_SECTION_NAME'));    	   
    	   $arTmplFields[]=array("IBLOCK.ID",GetMessage('EDIRECT_TEMPLATES_FNAME_IBLOCK_ID'));
    	   $arTmplFields[]=array("IBLOCK.CODE",GetMessage('EDIRECT_TEMPLATES_FNAME_IBLOCK_CODE'));
    	   $arTmplFields[]=array("IBLOCK.NAME",GetMessage('EDIRECT_TEMPLATES_FNAME_IBLOCK_NAME'));
    	   $arTmplFields[]=array("","URLs");
    	   if(!$FOR_SECTIONS){
    	       $arTmplFields[]=array("ELEMENT.URL",GetMessage('EDIRECT_TEMPLATES_FNAME_ELEMENT_URL'));
    	   }
	       $arTmplFields[]=array("SECTION_SMART_FILTER.URL",GetMessage('EDIRECT_TEMPLATES_FNAME_SECTION_SMART_FILTER_URL'));    	       
	       $arTmplFields[]=array("SMART_FILTER_PATH",GetMessage('EDIRECT_TEMPLATES_FNAME_SMART_FILTER_PATH'));
    	   $arTmplFields[]=array("SECTION.URL",GetMessage('EDIRECT_TEMPLATES_FNAME_SECTION_URL'));
    	   $arTmplFields[]=array("IBLOCK.URL",GetMessage('EDIRECT_TEMPLATES_FNAME_IBLOCK_URL'));
    	   
    	   
    	   //get sections User Type Property List from IB
    	   if($FOR_SECTIONS){
    	       $arTmplFields[]=array("",GetMessage("EDIRECT_TEMPLATES_SECTION_UF_PROP"));
        	   $rsData = CUserTypeEntity::GetList( array("FIELD_NAME"=>"ASC"), array("ENTITY_ID"=>"IBLOCK_".$IBLOCK_ID."_SECTION","LANG"=>LANGUAGE_ID) );
        	   while($arRes = $rsData->Fetch())
        	   {
        	       $arTmplFields[]=array(
        	           "UF.".$arRes["FIELD_NAME"],
        	           ($arRes["EDIT_FORM_LABEL"]?$arRes["EDIT_FORM_LABEL"]:$arRes["FIELD_NAME"]).($arRes["MULTIPLE"]=="Y"?" (".GetMessage('EDIRECT_TEMPLATES_FIELD_MULTIPLE').")":"")
        	       );
        	   }    	    
    	   }
    	   
    	    //get property types from IB
    	    $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$IBLOCK_ID));
    	    $arTmplFields[]=array("",GetMessage('EDIRECT_TEMPLATES_SECTION_ITEM_PROP'));
    	    while ($prop_fields = $properties->GetNext())
    	    {
    	        $arTmplFields[]=array(
    	            "PROP.".$prop_fields["CODE"],
    	            $prop_fields["NAME"].($prop_fields["MULTIPLE"]=="Y"?" (".GetMessage('EDIRECT_TEMPLATES_FIELD_MULTIPLE').")":"")
    	        );
    	    }    	    
    	    
    	    //GET catalog fields names
    	    if (IsModuleInstalled("catalog")&&CModule::IncludeModule("catalog"))
    	    {
    	        $arCatalog=CCatalog::GetByID($IBLOCK_ID);
    	        if ($arCatalog!==false){
    	            if(!$FOR_SECTIONS){
    	                $arTmplFields[]=array("",GetMessage('EDIRECT_TEMPLATES_SECTION_CATALOG_PROP'));
    	                
    	                //PRICE
    	                $defPriceTypeID=CEDirectCatalogItems::getDefaultPriceType();
        	            //get all price types
        	            $resPriceType = \Bitrix\Catalog\GroupTable::getList();
        	            while($arPriceType=$resPriceType->fetch()){
        	                if($arPriceType["ID"]==$defPriceTypeID){
        	                    $arTmplFields[]=array("CATALOG.PRICE",GetMessage('EDIRECT_TEMPLATES_FNAME_CATALOG_PRICE_DEF')." ".$arPriceType["NAME"]." ".GetMessage('EDIRECT_TEMPLATES_FNAME_CATALOG_AUTOUPDATE'));
        	                }
        	                else{
        	                    $arTmplFields[]=array("CATALOG.PRICE_".$arPriceType["ID"],GetMessage('EDIRECT_TEMPLATES_FNAME_CATALOG_PRICE_TYPE')." ".$arPriceType["NAME"]." ".GetMessage('EDIRECT_TEMPLATES_FNAME_CATALOG_NOTUPDATE'));
        	                }
        	            }
        	            
        	            //$arTmplFields[]=array("CATALOG.CURRENCY",GetMessage('EDIRECT_TEMPLATES_FNAME_CATALOG_CURRENCY'));
        	            $arTmplFields[]=array("CATALOG.QUANTITY",GetMessage('EDIRECT_TEMPLATES_FNAME_CATALOG_QUANTITY'));
    	            }
    	    
    	            //------get property types from offers IB----
    	            if($arCatalog["OFFERS_IBLOCK_ID"]>0){
    	                $arTmplFields[]=array("",GetMessage('EDIRECT_TEMPLATES_SECTION_OFFERS_PROP'));
    	                $arTmplFields[]=array("OFFER.NAME",GetMessage('EDIRECT_TEMPLATES_FNAME_OFFER_NAME'));
    	                $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCatalog["OFFERS_IBLOCK_ID"]));
    	                while ($prop_fields = $properties->GetNext())
    	                {
    	                    $arTmplFields[]=array(
    	                        "OFFER_PROP.".$prop_fields["CODE"],
    	                        $prop_fields["NAME"].($prop_fields["MULTIPLE"]=="Y"?" (".GetMessage('EDIRECT_TEMPLATES_FIELD_MULTIPLE').")":"")
    	                    );
    	                }
    	            }
    	            //--------------------------
    	        }
    	    }    	    
    	    
    	    return $arTmplFields;
	   }
	   else return array();
	}	
		
	/**
	 * multidimensional array unique for any single key index
	 *
	 * @param array $array  multidimensional need to sort
	 * @param mixed  key need to unique
	 * @return array  new array
	 */
	public static function uniqueMultidimArray($array, $key) {
	    $temp_array = array();
	    $i = 0;
	    $key_array = array();
	     
	    foreach($array as $val) {
	        if (!in_array($val[$key], $key_array)) {
	            $key_array[$i] = $val[$key];
	            $temp_array[$i] = $val;
	        }
	        $i++;
	    }
	    return $temp_array;
	}
	
	
	/**
	 * get array with Replace Values for Props
	 *
	 * @param &array $arReplaceValues  - link for array where collect Replace Values
	 * @param array $arProps  - iblock Props array
	 * @param string $namePrefix default "PROP"  prifix for template Name
	 * @param bolean $retWithXmlId  return values with XML_ID
	 * @return no return
	 */
	public static function getReplaceValuesForProps(&$arReplaceValues,$arProps,$namePrefix="PROP",$retWithXmlId=false)
	{
	    if(is_array($arProps)){
            foreach ($arProps as $prop){
                $arValues=array();
                if($prop["MULTIPLE"]=="Y"){
                    foreach ($prop["~VALUE"] as $key=>$val){
                        if($retWithXmlId
                            &&$prop["USER_TYPE"]!="directory"
                            &&$prop["PROPERTY_TYPE"]!="G"
                            &&$prop["PROPERTY_TYPE"]!="E") {
                                $arValues[]=array($val,$prop["VALUE_XML_ID"][$key]);
                        }
                        else {$arValues[]=$val;}
                    }
                }
                else if(strlen($prop["~VALUE"])>0) {
                    if($retWithXmlId
                        &&$prop["USER_TYPE"]!="directory"
                        &&$prop["PROPERTY_TYPE"]!="G"
                        &&$prop["PROPERTY_TYPE"]!="E") {
                            $arValues[]=array($prop["~VALUE"],$prop["VALUE_XML_ID"]);
                    }
                    else {$arValues[]=$prop["~VALUE"];}
                }
                
                //-----get values from Iblock and HightLoadBlock---
                if(count($arValues)){
                    //if iblock_section
                    if($prop["PROPERTY_TYPE"]=="G" && $prop["LINK_IBLOCK_ID"]>0){
                        $res = CIBlockSection::GetList(Array(), Array("IBLOCK_ID" => $prop["LINK_IBLOCK_ID"], "ID" => $arValues));
                        $arValues=array();
                        while($arData = $res->GetNext()){
                            if($retWithXmlId) $arValues[]=array($arData["NAME"],$arData["CODE"]);
                            else $arValues[]=$arData["NAME"];
                        }
                    }
                    //if iblock_element
                    else if($prop["PROPERTY_TYPE"]=="E" && $prop["LINK_IBLOCK_ID"]>0){
                        $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => $prop["LINK_IBLOCK_ID"], "ID" => $arValues));
                        $arValues=array();
                        while($arData = $res->GetNext()){
                            if($retWithXmlId) $arValues[]=array($arData["NAME"],$arData["CODE"]);
                            else $arValues[]=$arData["NAME"];
                        }
                    }
                    //-----check HightLoadBlock (directory), get values from HightLoadBlock----
                    else if($prop["USER_TYPE"]=="directory" && strlen($prop["USER_TYPE_SETTINGS"]["TABLE_NAME"])>0 ){
                            //get HLblock info
                            $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
                                'filter' => array('=TABLE_NAME' => $prop["USER_TYPE_SETTINGS"]["TABLE_NAME"])
                                ))->fetch();
                            if($hlblock){
                                //init HLblock Class
                                $hlClass = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock)->getDataClass();
                                $rsData = $hlClass::getList(array(
                                    'filter' => array('=UF_XML_ID' => $arValues)
                                ));
                                $arValues=array();
                                while($el = $rsData->fetch()){
                                   if($retWithXmlId) $arValues[]=array($el["UF_NAME"],$el["UF_XML_ID"]);
                                   else $arValues[]=$el["UF_NAME"];
                                }
                            }
                    }
                }
                //------------------------------------------------------------
                //add new Values
                $fieldName=$namePrefix.".".$prop["CODE"];
                if(isset($arReplaceValues[$fieldName])) $arReplaceValues[$fieldName]=array_merge($arReplaceValues[$fieldName],$arValues);
                else $arReplaceValues[$fieldName]=$arValues;
            }
	    }
	}	
	
	/**
	 * get array with Replace Values for User Type Properties in Section
	 *
	 * @param array $arSectionFields  - all section fields from CIBlockSection::GetList with UF_* fields
	 * @return array Replace property array
	 */
	public static function getReplaceValuesForUserTypeProps($arSectionFields)
	{
	    $namePrefix="UF";
	    $arReturn=array();
	    if(is_array($arSectionFields)){
	        //get all UF property info
	        $arUfFiledInfo=array();
    	    $rsData = CUserTypeEntity::GetList( array("FIELD_NAME"=>"ASC"), array("ENTITY_ID"=>"IBLOCK_".$arSectionFields["IBLOCK_ID"]."_SECTION") );
            while($arRes = $rsData->Fetch())
            {
                $arUfFiledInfo[$arRes["FIELD_NAME"]]=$arRes;
            }
            
            foreach ($arSectionFields as $f_name=>$field){
                if(strpos($f_name, "UF_")===0){
                    $arValues=array();
                    
                    if($arUfFiledInfo[$f_name]["MULTIPLE"]=="Y"){
                        foreach ($arSectionFields["~".$f_name] as $key=>$val){
                            $arValues[]=$val;
                        }
                    }
                    else if(strlen($arSectionFields["~".$f_name])>0) {
                        $arValues[]=$arSectionFields["~".$f_name];
                    }
                    
                    //-----get values from Iblock, HightLoadBlock and other----
                    if(count($arValues)){
                        //if iblock_section
                        if($arUfFiledInfo[$f_name]["USER_TYPE_ID"]=="iblock_section" && $arUfFiledInfo[$f_name]["SETTINGS"]["IBLOCK_ID"]){
                            $res = CIBlockSection::GetList(Array(), Array("IBLOCK_ID" => $arUfFiledInfo[$f_name]["SETTINGS"]["IBLOCK_ID"], "ID" => $arValues));
                            $arValues=array();
                            while($arData = $res->GetNext()){
                                $arValues[]=$arData["NAME"];
                            }
                        }
                        //if iblock_element
                        else if($arUfFiledInfo[$f_name]["USER_TYPE_ID"]=="iblock_element" && $arUfFiledInfo[$f_name]["SETTINGS"]["IBLOCK_ID"]){
                            $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => $arUfFiledInfo[$f_name]["SETTINGS"]["IBLOCK_ID"], "ID" => $arValues));
                            $arValues=array();
                            while($arData = $res->GetNext()){
                                $arValues[]=$arData["NAME"];
                            }
                        }
                        //if list
                        else if($arUfFiledInfo[$f_name]["USER_TYPE_ID"]=="enumeration"){
                            $res = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => $arUfFiledInfo[$f_name]["ID"],"ID" => $arValues));
                            $arValues=array();
                            while($arData = $res->GetNext()){
                                $arValues[]=$arData["VALUE"];
                            }                            
                        }
                        //-----check HightLoadBlock values (hlblock), get values from HightLoadBlock----
                        else if( $arUfFiledInfo[$f_name]["USER_TYPE_ID"]=="hlblock" && $arUfFiledInfo[$f_name]["SETTINGS"]["HLBLOCK_ID"] ){
                            //get UserTypeEntity Name
                            $ar_res = CUserTypeEntity::GetByID( $arUfFiledInfo[$f_name]["SETTINGS"]["HLFIELD_ID"] );
                            if(strlen($ar_res["FIELD_NAME"])>0){
                                //get HLblock info
                                $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($arUfFiledInfo[$f_name]["SETTINGS"]["HLBLOCK_ID"])->fetch();
                                if($hlblock){
                                    //init HLblock Class
                                    $hlClass = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock)->getDataClass();
                                    $rsData = $hlClass::getList(array(
                                        'filter' => array('=ID' => $arValues)
                                    ));
                                    $arValues=array();
                                    while($el = $rsData->fetch()){
                                        $arValues[]=$el[$ar_res["FIELD_NAME"]];
                                    }
                                }       
                            }                 
                        }
                    }
                    
                    //------------------------------------------------------------
                    //add new Values
                    $arReturn[$namePrefix.".".$f_name]=$arValues;                    
                }
            }
	    }
	    
	    return $arReturn;
	}	
	
	
	/**
	 * unique and Prepare to Templates PropsReplaceValues from getReplaceValuesForProps
	 *
	 * @param &array $arProps  -  link Props array returned by getReplaceValuesForProps
	 * @return no return
	 */
	public static function uniqueAndPreparePropsReplaceValues(&$arProps){
	    foreach ($arProps as $fieldName=>$values){
	        $values=array_unique($values);
	        $strprop="";
	        $cnt=0;
	        foreach ($values as $val){
	            if(strlen($strprop)>0) $strprop.="|";
	            $strprop.=$val;
	            $cnt++;
	        }
	        if($cnt>1) $arProps[$fieldName]="{".$strprop."}";
	        else if($strprop=="") unset($arProps[$fieldName]);
	        else $arProps[$fieldName]=$strprop;
	    }
	}
	
	/**
	 * add lover case templates to Values Array
	 *
	 * @param $arFieldsToReplace  -  Replace Values array
	 * @return no return
	 */
	public static function addLoverCaseValuesToArray($arFieldsToReplace){
	    $arReturn=array();	
    	foreach ($arFieldsToReplace as $fieldName=>$value){
    	    $arReturn[$fieldName]=ToLower($value);
    	    $arReturn["!".$fieldName]=$value;
    	    //----convert "!!" ucfirst fields----
    	    $arStrings=explode("|",str_replace(array("{","}"),"",$arReturn[$fieldName]));
    	    if(count($arStrings)>1){ //if multyply field
    	        foreach ($arStrings as &$string){
    	            $string=CEDirectBanner::ucfirstCyrillic($string);    	            
    	        }
    	        $arReturn["!!".$fieldName]="{".implode("|",$arStrings)."}";
    	    }
    	    else { //not multyply field
    	        $arReturn["!!".$fieldName]=CEDirectBanner::ucfirstCyrillic($arReturn[$fieldName]);    	        
    	    }
    	    //----------------------------------------------
    	}
    	return $arReturn;
	}
	
	/**
	 * replace all templates by values
	 *
	 * @param string $tmplString - string with templates for compile
	 * @param array $arFieldsToReplace  -  Replace Values array
	 * @return array new string array
	 */
	public static function compileTemplateString($tmplString,$arFieldsToReplace){
	    $arNewStrings=array();
	    
	    //replace templates in string
	    foreach ($arFieldsToReplace as $replace => $value){
	       $tmplString=preg_replace("/\{$replace\}/", $value, $tmplString);
	    }
	    
	    //clear string from not use templates
	    $tmplString=preg_replace("/\{[^\|\{]*\}/", "", $tmplString);
	    
	    //collect branches get brnches tmpl in $tmplString
	    $arBranches=array();
	    if(preg_match_all("/\{([^\{]*)\}/",$tmplString,$regs)){
	        $i=0;
	        foreach ($regs[1] as $key=>$val){
	            if(preg_match("/".preg_quote($regs[0][$key],'/')."/i",$tmplString)){ //if have the same branches, pass it
    	            $arBranches[$i]=explode("|",$val);
    	            $tmplString=preg_replace("/".preg_quote($regs[0][$key],'/')."/i","{branch$i}",$tmplString);
    	            $i++;
	            }
	        }
	    }
	    $arNewStrings[]=trim($tmplString);
	    
	    //generate values for branches
	    if(count($arBranches)){
	        for($i=0;$i<count($arBranches);$i++){
	            foreach ($arNewStrings as $oldkey=>$string){
    	            foreach ($arBranches[$i] as $val){
    	                $arNewStrings[]=str_replace("{branch$i}",$val,$string);
    	            }
    	            unset($arNewStrings[$oldkey]);
	            }
	        }
	    }
	    
	    return array_values($arNewStrings);
	}
	
	/**
	 * replace all templates by values in array as one group
	 *
	 * @param array $arStrings - union as logic group strings
	 * @param array $arFieldsToReplace  -  Replace Values array
	 * @return array new strings array with replaces
	 */
	public static function compileTemplateStringsArray($arStrings,$arFieldsToReplace){
	    $arNewStrings=array();
	     
	    $keys=array_keys($arStrings);
	    $unionString=implode("<|>",$arStrings);
	    $arNewUnionStrings=CEDirectTemplates::compileTemplateString($unionString, $arFieldsToReplace);
	    foreach ($arNewUnionStrings as $val){
	        $arNewStrings[]=array_combine($keys,explode("<|>",$val));
	    }
	    
	    return array_values($arNewStrings);
	}	

	/**
	 * replace all templates by values in raw words
	 *
	 * @param string $rawWordString - words string fron form
	 * @param array $arFieldsToReplace  -  Replace Values array
	 * @return array new words array with replaces
	 */
	public static function compileRawWordString($rawWordString,$arFieldsToReplace){
	    $arNewWords=array();
	    
	    $words=array();
	    foreach (explode("\n",$rawWordString) as $str){
	        foreach (explode(",",$str) as $value){
	            $value=trim($value);
	            if(strlen($value)>1) $words[]=$value;
	        }
	    }
	    $words=array_unique($words);
	    foreach ($words as $word){
	        $arNewWords=array_merge($arNewWords,CEDirectTemplates::compileTemplateString($word, $arFieldsToReplace));
	    }
	    
	    $arNewWords=array_unique($arNewWords);
	    
	    return array_values($arNewWords);
	}

    /**
     * filter to values from catalog use in array_walk_recursive
     *
     * @param $value value of array
     * @param $value key of array
     */
    public static function filterCatalogValue(&$value, $key){
        $value=htmlspecialchars_decode($value, ENT_QUOTES);
        $value=html_entity_decode ( $value, ENT_QUOTES);
        $value=trim(strip_tags($value));
    }

	/**
	 * multiply Arrays
	 *
	 * @param array with values arrays need to multiply
	 * @return array multiply result
	 */	
	public static function multiplyArrays($arValues){
	    $arReturn=array();
	    
	    foreach ($arValues as $arKey=>$arVal){
	        if(count($arReturn)==0) {
	             foreach ($arVal as $value){
	                if(!is_array($value)&&!strlen($value)) continue;
                    $arReturn[]=array($arKey=>$value);   
                }
	        }
	        else{
	            $arMultiplyRes=array();
	            foreach ($arReturn as $value){
                    foreach ($arVal as $value1){
                        if(!is_array($value)&&!strlen($value1)) continue;
                        $arMultiplyRes[]=array_merge($value,array($arKey=>$value1));
                    }
	            }
	            if(count($arMultiplyRes)) $arReturn=$arMultiplyRes;
	        }
	    }
	    
	    return $arReturn;
	}
}
?>