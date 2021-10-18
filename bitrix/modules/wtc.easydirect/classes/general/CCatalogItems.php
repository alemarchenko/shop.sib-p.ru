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

/**
 * Class CEDirectCatalogItems
 * Work with Bitrix Catalog
 * @category module wtc.easydirect Catalog
 */
class CEDirectCatalogItems extends CAllEDirectTable
{
    /**
     * Return element from DB by ID
     *
     * @param int $ID
     * @param array $arSelectFields     What fields need to select
     * @uses CEDirectBanner::GetList()
     * @return CDBResult
     */
    public static function GetByID($ID,$arSelectFields=Array())
    {
        return CEDirectCatalogItems::GetList(Array(), Array("ID"=>$ID),false,$arSelectFields);
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
        global $DB;
    
        $FilterFieds=array(
            "ID"=>"number",
            "IBLOCK_ELEMENT_ID"=>"number",
            "IBLOCK_ID"=>"number",
            "PARENT_SECTION_ID"=>"number",
            "IS_SECTION"=>"string_equal",
            "NAME"=>"string",
            "PRICE"=>"number",
            "IS_AVAILABLE"=>"string_equal",
            "UPDATE_DATE"=>"date",
            "MODIFIED_DATE"=>"date",
            "MODIFIED_IDUSER"=>"number"
        );
    
        return CAllEDirectTable::baseGetList("wtc_edirect_catalog_items", $FilterFieds, $arOrder, $arFilter,$arGroupBy,$arSelectFields);
    }
    
    /**
     * Return elements list from DB with BANNER information
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
        $maintblname="CATALOG_ITEM";
    
        $FilterFieds=array(
            $maintblname."."."ID"=>"number",
            $maintblname."."."IBLOCK_ELEMENT_ID"=>"number",
            $maintblname."."."IBLOCK_ID"=>"number",
            $maintblname."."."PARENT_SECTION_ID"=>"number",
            $maintblname."."."IS_SECTION"=>"string_equal",
            $maintblname."."."NAME"=>"string",
            $maintblname."."."PRICE"=>"number",
            $maintblname."."."IS_AVAILABLE"=>"string_equal",
            $maintblname."."."UPDATE_DATE"=>"date",
            "BANNER.ID"=>"number",
            "BANNER.ID_BANNER_GROUP"=>"number",
            "BANNER.ID_CATALOG_ITEM"=>"number"
        );
    
        if(count($arSelectFields)==0){
            $arSelectFields= array(
                $maintblname.".*",
                "BANNER.ID as BANNER_ID",
                "BANNER.ID_BANNER_GROUP as BANNER_ID_BANNER_GROUP",
                "BANNER.ID_CATALOG_ITEM as BANNER_ID_CATALOG_ITEM"
            );
        }
        else {
            $arSelectFields=CAllEDirectTable::PrepExArSelect($maintblname,$arSelectFields);
        }
    
        $from="wtc_edirect_catalog_items ".$maintblname."
					LEFT JOIN wtc_edirect_banners BANNER
					ON (".$maintblname.".ID=BANNER.ID_CATALOG_ITEM)";
    
        return CAllEDirectTable::baseGetList($from, $FilterFieds, $arOrder, $arFilter, $arGroupBy,$arSelectFields);
    }
    
    /**
     * Add new element in DB
     *
     * @param array $arFields      Element fields
     */
    public static function Add($arFields)
    {
        if($arFields["IBLOCK_ELEMENT_ID"]>0&&$arFields["IBLOCK_ID"]>0){
            //!!need check section, because the same ID in SECTIONs and ELEMENTs
            $IS_SECTION="N";
            if(isset($arFields["IS_SECTION"])) $IS_SECTION=$arFields["IS_SECTION"];
            //if isset return isset ID
            $res=CEDirectCatalogItems::GetList(array(),array("IBLOCK_ELEMENT_ID"=>$arFields["IBLOCK_ELEMENT_ID"],"IBLOCK_ID"=>$arFields["IBLOCK_ID"],"IS_SECTION"=>$IS_SECTION),false, array("ID"));
            if($ar_res=$res->Fetch()) return $ar_res["ID"];
            
            return CAllEDirectTable::baseAdd("wtc_edirect_catalog_items", $arFields);
        }
        else return 0;
    }
    
    /**
     * Update exist element in DB
     *
     * @param int $ID
     * @param array $arFields      Element fields
     */
    public static function Update($ID,$arFields)
    {
        return CAllEDirectTable::baseUpdate("wtc_edirect_catalog_items", $ID, $arFields);
    }
    
    /**
     * Delete exist element from DB
     *
     * @param int $ID
     */
    public static function Delete($ID)
    {
        return CAllEDirectTable::baseDelete("wtc_edirect_catalog_items", $ID);
    }    
    
    //--------------------------------------------------------------------------------------------------
    //--------WORK WITH CATALOG INTEGRATION-------------------------------------
    //---------------------------------------------------------------------------------------------------    
    
    /**
     * Return catalog IBlock IDs in array
     *
     * @return array IDs
     */
    public static function getCatalogIBlockIDs()
    {
        if(strlen(EDIRECT_IBLOCK_CATALOG_ID)>0) return explode(",",EDIRECT_IBLOCK_CATALOG_ID);
        else return array();
    }
    
    /**
     * Get catalog currecnci
     *
     * @return array IDs
     */
    public static function getCatalogCurrency()
    {
        if(strlen(EDIRECT_CATALOG_CURRENCY)>0) return EDIRECT_CATALOG_CURRENCY;
        else if(strlen(EDIRECT_CURRENCY)>0) return EDIRECT_CURRENCY;
        else return "RUB";
    }    
    
    /**
     * rebuild catalog integration in company, set new banners and catalog links
     *
     * @param int $idCompany  - Company ID
     * @return bolean
     */
    public static function rebuildCompanyCatalogIntegration($idCompany)
    {
        if($idCompany>0){
            $comp_ID_CATALOG_ITEM=0;
    
            //get company ID_CATALOG_ITEM
            $res=CEDirectCompany::GetByID($idCompany);
            $arCompany = $res->Fetch();
            if($arCompany["ID_CATALOG_ITEM"]>0) $comp_ID_CATALOG_ITEM=$arCompany["ID_CATALOG_ITEM"];
    
            $arIssetCatalogURLs=array();
            //get banners from company
            $rsBanner = CEDirectBanner::GetListEx(
                array("BANNER.ID"=>"ASC"),
                array("BANNER_GROUP.ID_COMPANY"=>$idCompany),
                array("BANNER.ID"),
                array("BANNER.ID","BANNER.ID_CATALOG_ITEM","BANNER_GROUP.ID_COMPANY","BANNER.HREF")
            );
            while($arBanner = $rsBanner->Fetch())
            {
                $banner_ID_CATALOG_ITEM="NULL";
                //---find ID_CATALOG_ITEM--
                if($comp_ID_CATALOG_ITEM>0) { //if isset ID_CATALOG_ITEM in company set it
                    $banner_ID_CATALOG_ITEM=$comp_ID_CATALOG_ITEM;
                }
                else{ //else Find by BANNER.HREF
                    //First find in isset URLs in DB
                    $issetCatalogId=array_search($arBanner["HREF"], $arIssetCatalogURLs);
                    if($issetCatalogId!==false){$banner_ID_CATALOG_ITEM=$issetCatalogId;}
                    //after search catalog ELEMENT in IBLock and create idCatalogItem
                    else {
                        $arCatalogElementInfo=CEDirectCatalogItems::getCatalogElementInfoByUrl($arBanner["HREF"]);
                        if(count($arCatalogElementInfo)>0){
                            $IDCatalogItem=CEDirectCatalogItems::Add($arCatalogElementInfo);
                            if($IDCatalogItem>0) {
                                $banner_ID_CATALOG_ITEM=$IDCatalogItem;
                                //add to isset URL
                                $arIssetCatalogURLs[$IDCatalogItem]=$arBanner["HREF"];
                            }
                        }
                    }
                }
                //---------------------------------------
    
                //update ID_CATALOG_ITEM in banner
                if($arBanner["ID_CATALOG_ITEM"]!=$banner_ID_CATALOG_ITEM){
                    CEDirectBanner::Update($arBanner["ID"], array("ID_CATALOG_ITEM"=>$banner_ID_CATALOG_ITEM));
                }
            }
            return true;
        }
        else return false;
    }
    
    /**
     * get Company info by catalog integration, banners CNT
     *
     * @param int $idCompany  - Company ID
     * @return array IDs $arReturn [BANNERS_WITH_INT_CNT,BANNERS_WITHOUT_INT_CNT,BANNERS_CNT]
     */
    public static function getCompanyCatalogIntegrationInfo($idCompany)
    {
        $arReturn=array(
            "BANNERS_WITH_INT_CNT"=>0,
            "BANNERS_WITHOUT_INT_CNT"=>0,
            "BANNERS_CNT"=>0
        );
        //get banners from company
        $rsBanner = CEDirectBanner::GetListEx(
            array("BANNER.ID"=>"ASC"),
            array("BANNER_GROUP.ID_COMPANY"=>$idCompany),
            array("BANNER.ID"),
            array("BANNER.ID","BANNER.ID_CATALOG_ITEM","BANNER_GROUP.ID_COMPANY")
        );
        while($arBanner = $rsBanner->Fetch())
        {
            if($arBanner["ID_CATALOG_ITEM"]>0) $arReturn["BANNERS_WITH_INT_CNT"]++;
            else $arReturn["BANNERS_WITHOUT_INT_CNT"]++;
    
            $arReturn["BANNERS_CNT"]++;
        }
        return $arReturn;
    }
    
	//--------------------------------------------------------------------------------------------------
	//-------------------------------CRON FUNCTIONS-----------------------------------------
	//---------------------------------------------------------------------------------------------------	
    
    /**
     * Cron Function
     * find and delete all not linked with banners catalog_elements
     *
     * @return bolean
     */
    public static function deleteUnlinkedCatalogItems()
    {    
        $res=CEDirectCatalogItems::GetListEx(Array("CATALOG_ITEM.ID"=>"ASC"),array("BANNER.ID"=>false),false,array("CATALOG_ITEM.ID","BANNER.ID"));
        while($ar_res=$res->Fetch()){
            CEDirectCatalogItems::Delete($ar_res["ID"]);
        }
        return true;
    }
    
    /**
     * Cron Function syncSTEP 1
     * update catalog items info: PRICE, NAME, IS_AVAILABLE
     * delete unlinked items
     *
     * @return bolean
     */
    public static function updateCatalogItemsInfo()
    {
        //include catalog if installled, get default price type
        $defPriceTypeID=0;
        $isCatalogInstal=false;
        if (IsModuleInstalled("catalog")&&CModule::IncludeModule("catalog"))
        {
            $isCatalogInstal=true;
            $defPriceTypeID=CEDirectCatalogItems::getDefaultPriceType();
        }
        
        //get all not update element from CATALOG_ITEMS explode by iblocks. SECTION WILL NOT UPDATE
        $arCatalogElements=array();
        $arCatalogElementLink=array();
        $stepCnt=EDIRECT_CATALOG_UPDATE_SPEED;
        $res=CEDirectCatalogItems::GetList(
            Array("UPDATE_DATE"=>"ASC","IBLOCK_ID"=>"ASC"),
            array("IS_SECTION"=>"N","<UPDATE_DATE"=>ConvertTimeStamp(AddToTimeStamp(array("MI"=>(-1*EDIRECT_CATALOG_UPDATE_INTERVAL))),"FULL")),
            false,
            array("ID","IBLOCK_ID","IBLOCK_ELEMENT_ID")
        );
        for($i=0;$i<$stepCnt&&$ar_res=$res->Fetch();$i++){
            //collect elements id with explode by iblocks
            $arCatalogElements[$ar_res["IBLOCK_ID"]][]=$ar_res["IBLOCK_ELEMENT_ID"];
            $arCatalogElementLink[$ar_res["IBLOCK_ID"]."_".$ar_res["IBLOCK_ELEMENT_ID"]]=$ar_res["ID"];
        }        
        
        //update info in CATALOG_ITEMS
        foreach ($arCatalogElements as $IBLOCK_ID => $arIDs){
            //prepare select property Names
            $arSelectFields=array('ID', 'NAME', 'ACTIVE', 'IBLOCK_ID');
            if($isCatalogInstal) {
                $arSelectFields[]='AVAILABLE';
                if($defPriceTypeID>0) $arSelectFields[]='PRICE_'.$defPriceTypeID;
            }
            $res = \CIBlockElement::GetList(array(),array('IBLOCK_ID' => $IBLOCK_ID,"ID"=>$arIDs),false,false,$arSelectFields);
            while($arElement=$res->fetch()){
                $arUpdateFields=array(
                    "NAME"=>$arElement["NAME"],
                    "IS_AVAILABLE"=>((isset($arElement["AVAILABLE"])&&$arElement["AVAILABLE"]=="N"||$arElement["ACTIVE"]=="N")?"N":"Y"),
                    "PRICE"=>((isset($arElement['PRICE_'.$defPriceTypeID])&&$arElement['PRICE_'.$defPriceTypeID]>0)?round($arElement['PRICE_'.$defPriceTypeID]):0),                    
                    "UPDATE_DATE"=>"NOW()"
                );
                
                if(
                    isset($arCatalogElementLink[$arElement["IBLOCK_ID"]."_".$arElement["ID"]])
                    &&$arCatalogElementLink[$arElement["IBLOCK_ID"]."_".$arElement["ID"]]>0
                ){
                    CEDirectCatalogItems::Update($arCatalogElementLink[$arElement["IBLOCK_ID"]."_".$arElement["ID"]],$arUpdateFields);
                }
            }
        }        
    }
    
    /**
     * Cron Function syncSTEP 2
     * stop or start banners and change PRICE BY catalog items info
     * 
     * @return bolean
     */
    public static function updateYaBannersByCatalogItemsInfo()
    {
        global $obYaExchange;
        $stepCnt=200;
        
        //----UPDATE ACTIVE--------
        //----find banners where BANNER.ACTIVE!=CATALOG_ITEM.IS_AVAILABLE only NOT SECTION, 
        //----if find we must change active in this banners. STEP = $stepCnt
        if(EDIRECT_CATALOG_STRTSTP_ACTIVE=="Y"){
            $arBannersNewState=array(
                "TO_SUSPEND"=>array(), //Stop
                "TO_RESUME"=>array() //Start
            );
                    
            $rsBanner = CEDirectBanner::GetListEx(
                array("BANNER.MODIFIED_DATE"=>"ASC","BANNER.ID"=>"ASC"),
                array(
                    ">BANNER.ID_CATALOG_ITEM"=>0,
                    "CATALOG_ITEM.IS_SECTION"=>"N",
                    "!=BANNER.ACTIVE"=>"!?CATALOG_ITEM.IS_AVAILABLE"
                ),
                false,
                array("BANNER.ID","BANNER.MODIFIED_DATE","BANNER.ACTIVE","CATALOG_ITEM.IS_AVAILABLE","CATALOG_ITEM.IS_SECTION","CATALOG_ITEM.NAME","BANNER_GROUP.ID_COMPANY")
            );
            for($i=0;$i<$stepCnt&&$arBanner = $rsBanner->Fetch();$i++)
            {
                //STOP BANNERS
                if($arBanner["ACTIVE"]=="Y"&&$arBanner["CATALOG_ITEM_IS_AVAILABLE"]=="N"){
                    $arBannersNewState["TO_SUSPEND"][]=$arBanner["ID"];
                    $logActive=GetMessage('EDIRECT_CATALOG_LOG_DEACTIVE');
                }
                //START BANNERS
                else if($arBanner["ACTIVE"]=="N"&&$arBanner["CATALOG_ITEM_IS_AVAILABLE"]=="Y"){
                    $arBannersNewState["TO_RESUME"][]=$arBanner["ID"];
                    $logActive=GetMessage('EDIRECT_CATALOG_LOG_ACTIVE');
                }
                
                //write log
                CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CATALOG_LOG_MESS_ACTIVE',array("#ACTIVE#"=>$logActive,"#BID#"=>$arBanner["ID"],"#CID#"=>$arBanner["BANNER_GROUP_ID_COMPANY"],"#CATALOG_ITEM_NAME#"=>$arBanner["CATALOG_ITEM_NAME"]))));
            }
            
            //update BANNERS ACTIVE
            if(count($arBannersNewState["TO_SUSPEND"])>0){
                if($obYaExchange->setBannersState($arBannersNewState["TO_SUSPEND"],"suspend")){
                    foreach ($arBannersNewState["TO_SUSPEND"] as $bID){
                        CEDirectBanner::Update($bID, array("ACTIVE"=>"N"));
                    }
                }
            }
             if(count($arBannersNewState["TO_RESUME"])>0){
                if($obYaExchange->setBannersState($arBannersNewState["TO_RESUME"],"resume")){
                    foreach ($arBannersNewState["TO_RESUME"] as $bID){
                        CEDirectBanner::Update($bID, array("ACTIVE"=>"Y"));
                    }                
                }
            }
        }
        //----------------------------------
        
        //----UPDATE PRICE--------
        //----find banners where BANNER.PRICE!=CATALOG_ITEM.PRICE only NOT SECTION,
        //----if find, update price in banner in texts and specField
        if(EDIRECT_CATALOG_UPDPRICE_ACTIVE=="Y"){
            $rsBanner = CEDirectBanner::GetListEx(
                array("BANNER.MODIFIED_DATE"=>"ASC","BANNER.ID"=>"ASC"),
                array(
                    ">BANNER.ID_CATALOG_ITEM"=>0,
                    "CATALOG_ITEM.IS_SECTION"=>"N",
                    ">CATALOG_ITEM.PRICE"=>0,
                    "!=BANNER.PRICE"=>"!?CATALOG_ITEM.PRICE"
                ),
                false,
                array("BANNER.ID","BANNER.MODIFIED_DATE","BANNER.PRICE","CATALOG_ITEM.PRICE","CATALOG_ITEM.IS_SECTION","CATALOG_ITEM.NAME","BANNER_GROUP.ID_COMPANY")
            );
            //get banners IDs to update
            $arBannerIDs=array();
            $arBannerDBInfo=array();
            for($i=0;$i<$stepCnt&&$arBanner = $rsBanner->Fetch();$i++)
            {
                $arBannerIDs[]=$arBanner["ID"];
                $arBannerDBInfo[$arBanner["ID"]]=$arBanner;
            }
            
            $arNewBannerToYa=array();
            //we need actual info, so we get info from Ya 
            $arBannerInfo=$obYaExchange->getBannersParamsToUpdPrice($arBannerIDs);
            foreach ($arBannerInfo as $banner){
                if($banner["State"]=="ARCHIVED"){continue;} //we cant update banner if it ARCHIVED
                $newPrice=$arBannerDBInfo[$banner["Id"]]["CATALOG_ITEM_PRICE"];
                $oldPrice=CEDirectCompany::convertCurrencyFromYa($banner["TextAd"]["PriceExtension"]["Price"]);
                //update price in banner. Texts and price property
                unset($banner["State"]); //delete not use prop
                $banner["TextAd"]["PriceExtension"]=array("Price"=>CEDirectCompany::convertCurrencyToYa($newPrice),"PriceQualifier"=>"NONE","PriceCurrency"=>CEDirectCatalogItems::getCatalogCurrency());
                $banner["TextAd"]["Title"]=str_replace($oldPrice, $newPrice, $banner["TextAd"]["Title"]);
                $banner["TextAd"]["Title2"]=str_replace($oldPrice, $newPrice, $banner["TextAd"]["Title2"]);
                $banner["TextAd"]["Text"]=str_replace($oldPrice, $newPrice, $banner["TextAd"]["Text"]);
                
                //compile new info for Yandex
                $arNewBannerToYa[]=$banner;
                
                //update info to DB
                CEDirectBanner::Update($banner['Id'],array(
                    "PRICE"=>$newPrice,
                    "TITLE"=>$banner["TextAd"]["Title"],
                    "TITLE2"=>$banner["TextAd"]["Title2"],
                    "TEXT"=>$banner["TextAd"]["Text"]
                ));
                
                //write log
                CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CATALOG_LOG_MESS_PRICE',array("#BID#"=>$arBannerDBInfo[$banner["Id"]]["ID"],"#CID#"=>$arBannerDBInfo[$banner["Id"]]["BANNER_GROUP_ID_COMPANY"],"#OLD_PRICE#"=>$oldPrice,"#NEW_PRICE#"=>$newPrice,"#CATALOG_ITEM_NAME#"=>$arBannerDBInfo[$banner["Id"]]["CATALOG_ITEM_NAME"]))));                
            }
            
            //send new info to Yandex
            $obYaExchange->updateBanners($arNewBannerToYa);            
        }
        
        //----------------------------------
    }    
    
	//--------------------------------------------------------------------------------------------------
	//--------------WORK WITH CATALOG IBLOCK-----------------------------------------
	//---------------------------------------------------------------------------------------------------	
    /**
     * Return IBlock element info
     *
     * @param int $IBlockID  - IBlock ID
     * @param int $searchParam  - element ID or CODE
     * @param string $paramName  - what type in $searchParam  ELEMENT_ID/ELEMENT_CODE/SECTION_ID/SECTION_CODE
     * @return array empty or [IBLOCK_ELEMENT_ID,IBLOCK_ID,PARENT_SECTION_ID,IS_SECTION,NAME]
     */
    public static function getIBlockElementInfo($IBlockID,$searchParam,$paramName)
    {
        if( $IBlockID>0 && ($searchParam>0||strlen($searchParam)>0) ){
            //if search ELEMENT
            if($paramName=="ELEMENT_ID"||$paramName=="ELEMENT_CODE"){
                $arFilter=array(
                    "IBLOCK_ID"=>$IBlockID
                );
                if($paramName=="ELEMENT_CODE") $arFilter["CODE"]=$searchParam;
                else $arFilter["ID"]=$searchParam;
                
                $rsElement = CIBlockElement::GetList(array(),$arFilter, false, false, array("ID","IBLOCK_ID","IBLOCK_SECTION_ID","NAME"));
                if($el = $rsElement->GetNext())
                {
                    return array(
                        "IBLOCK_ELEMENT_ID"=>$el["ID"],
                        "IBLOCK_ID"=>$el["IBLOCK_ID"],
                        "PARENT_SECTION_ID"=>$el["IBLOCK_SECTION_ID"],
                        "IS_SECTION"=>"N",
                        "NAME"=>$el["NAME"]
                    );
                }
            }
            //if search SECTION
            else if($paramName=="SECTION_ID"||$paramName=="SECTION_CODE"){
                $arFilter=array(
                    "IBLOCK_ID"=>$IBlockID
                );
                if($paramName=="SECTION_CODE") $arFilter["CODE"]=$searchParam;
                else $arFilter["ID"]=$searchParam;     
                
                $rsElement = CIBlockSection::GetList(Array(), $arFilter,false, false, array("ID","IBLOCK_ID","IBLOCK_SECTION_ID","NAME"));
                if($el = $rsElement->GetNext())
                {
                    return array(
                        "IBLOCK_ELEMENT_ID"=>$el["ID"],
                        "IBLOCK_ID"=>$el["IBLOCK_ID"],
                        "PARENT_SECTION_ID"=>$el["IBLOCK_SECTION_ID"],
                        "IS_SECTION"=>"Y",
                        "NAME"=>$el["NAME"]
                    );
                }           
            }
        }
        
        return array();
    }	
    
    /**
     * Return catalog element info by URL
     *
     * @param string $URL  - element url
     * @return array empty or [IBLOCK_ELEMENT_ID,IBLOCK_ID,PARENT_SECTION_ID,IS_SECTION,NAME]
     */
    public static function getCatalogElementInfoByUrl($URL)
    {
        $arCatalogIBIDs=CEDirectCatalogItems::getCatalogIBlockIDs();

        //delete all parametrs from URL after ?
        if(strpos($URL,"?")!==false) {
            $arUrl = explode("?", $URL);
            $URL = $arUrl[0];
        }

        //delete smart filter PATH from URL
        if(strpos($URL,"/filter/")!==false){
            $arURL=explode("/filter/", $URL);
            $URL=$arURL[0]."/";
        }
        
        $res = CIBlock::GetList(Array(),Array("ID"=>$arCatalogIBIDs,"ACTIVE"=>"Y","CNT_ACTIVE"=>"Y"), false);
        while($ar_res = $res->Fetch())
        {
            $detailPageURLTmpl=preg_quote($ar_res["DETAIL_PAGE_URL"]);
            $detailPageURLTmpl=str_replace("\#","#",$detailPageURLTmpl); // if PHP > 7.3.0 # - add quote

            //search ID element in URL
            if(preg_match("/#ELEMENT_ID#|#ID#/",$ar_res["DETAIL_PAGE_URL"])){
                $urlPattern=preg_replace(array("/\//","/#ELEMENT_ID#|#ID#/","/#SECTION_CODE_PATH#/","/#[^\/.]*#/"),array("\/","([0-9]*)",".*","[^\/.]*"), $detailPageURLTmpl);
                $urlPattern="/".$urlPattern."$/";
                if(preg_match($urlPattern,$URL,$regsEl)){
                    return CEDirectCatalogItems::getIBlockElementInfo($ar_res["ID"],$regsEl[1],"ELEMENT_ID");
                }
            }
            //search CODE element in URL
            else if(preg_match("/#ELEMENT_CODE#|#CODE#/",$ar_res["DETAIL_PAGE_URL"])){
                $urlPattern=preg_replace(array("/\//","/#ELEMENT_CODE#|#CODE#/","/#SECTION_CODE_PATH#/","/#[^\/.]*#/"),array("\/","([^\/.]*)",".*","[^\/.]*"), $detailPageURLTmpl);
                $urlPattern="/".$urlPattern."$/";
                if(preg_match($urlPattern,$URL,$regsCode)){
                     return CEDirectCatalogItems::getIBlockElementInfo($ar_res["ID"],$regsCode[1],"ELEMENT_CODE");
                }
            }

            $sectionPageURLTmpl=preg_quote($ar_res["SECTION_PAGE_URL"]);
            $sectionPageURLTmpl=str_replace("\#","#",$sectionPageURLTmpl); // if PHP > 7.3.0 # - add quote
            //search SECTION ID in URL
            if(preg_match("/#SECTION_ID#|#ID#/",$ar_res["SECTION_PAGE_URL"])){
                $urlPattern=preg_replace(array("/\//","/#SECTION_ID#|#ID#/","/#[^\/.]*#/"),array("\/","([0-9]*)","[^\/.]*"), $sectionPageURLTmpl);
                $urlPattern="/".$urlPattern."$/";
                if(preg_match($urlPattern,$URL,$regsEl)){
                    return CEDirectCatalogItems::getIBlockElementInfo($ar_res["ID"],$regsEl[1],"SECTION_ID");
                }
            }
            //search SECTION CODE in URL
            else if(preg_match("/#CODE#|#SECTION_CODE#|#SECTION_CODE_PATH#/",$ar_res["SECTION_PAGE_URL"])){
                $urlPattern=preg_replace(array("/\//","/#CODE#|#SECTION_CODE#/","/#SECTION_CODE_PATH#/","/#[^\/.]*#/"),array("\/","([^\/.]*)","(.*)","[^\/.]*"), $sectionPageURLTmpl);
                $urlPattern="/".$urlPattern."$/";
                if(preg_match($urlPattern,$URL,$regsCode)){
                    if(strpos($ar_res["SECTION_PAGE_URL"],"#SECTION_CODE_PATH#")!==false){
                        $arSections=explode("/", $regsCode[1]);
                        $regsCode[1]=$arSections[(count($arSections)-1)];
                    }
                    return CEDirectCatalogItems::getIBlockElementInfo($ar_res["ID"],$regsCode[1],"SECTION_CODE");
                }
            }
        }
        
        return array();
    }
    
    /**
     * Return product QUANTITY by product IDs
     * need include Catalog module
     *
     *	@param array $arIDs  -  product IDs
     * @return array IDs=>QUANTITY
     */
    public static function getProductQuantityByIDs($arIDs)
    {
        $arReturn=array();
        $rsData = \Bitrix\Catalog\ProductTable::getList(array(
            'filter' => array('=ID' => $arIDs)
        ));
        while($arOferCat=$rsData->fetch()){
            $arReturn[$arOferCat["ID"]]=$arOferCat["QUANTITY"];
        }
        
        return $arReturn;
    }    
    
    /**
     * Return default price TYPE for banners
     * need include Catalog module
     *
     *	@param array $arIDs  -  product IDs
     * @return int PriceTypeID
     */
    public static function getDefaultPriceType()
    {
        if(EDIRECT_CATALOG_DEF_PRICE_ID>0){
            return EDIRECT_CATALOG_DEF_PRICE_ID;
        }
        else{ //get BASE price type
            $basePriceType = \Bitrix\Catalog\GroupTable::getList([
              "filter" => ["=BASE" => "Y"]
            ])->fetch();
            return $basePriceType["ID"];
        }
    }    
    
} 
