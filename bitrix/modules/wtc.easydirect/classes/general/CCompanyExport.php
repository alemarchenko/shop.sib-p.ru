<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

/**
 * Class CEDirectCompanyExport
 * Export Company to Yandex
 * @category module wtc.easydirect Company
 */
class CEDirectCompanyExport
{
    private $execStepTime=10; //max execute step time
    private $imageCntSendByStep=10; //send slice by X images, CNT step (default 10, max 100)
    private $groupCntSendByStep=200; //send slice by X groups, CNT step (default 200, max 1000)
    private $bannerCntSendByStep=300; //send slice by X banners, CNT step (default 300, max 1000)
    private $phraseCntSendByStep=800; //send slice by X phrases, CNT step (default 800, max 1000)
    
    private $curStep=1;
    private $defPrice=3;
    private $startTime=0;
    
    private $message=array();
    private $error=0;
    private $cntErr=0;
    private $curProgress=0;
    private $curPodProgress=0;
    private $maxProgress=0;
    
    private $postData="";
    private $minusWords=array();
    private $siteLinks=array();
    private $arActions=array();
    
    private $curAction="";
    private $curCompanyID=0;
    private $isRSYA=false;
    private $arBaseParams=array();
    private $isManualStrategy=true;
    private $issetBannersCnt=0;
    
    private $arImgHashes=array();
    private $arTheSameImages=array();
    private $isCreateImg=false;
    private $arSendImages=array();
    
    private $arSendBannerGroups=array();
    private $arCreatedBannerGroupIDs=array();
    
    private $arSendBanners=array();
    private $arSendPhrases=array();
    
    private $sitelinkSetId=0;
    private $newVCardId=0;
        
    /**
     * constructor prepare and save Post data
     */
    public function __construct($postData) {
        
        //------------price-----------------
        $this->defPrice=CEDirectCompany::convertCurrencyToYa($postData['price']);
        unset($postData['price']);
        
        //---------minus keywords--
        $minus=array();
        foreach (explode("\n",$postData['minuswords']) as $str){
            foreach (explode(",",$str) as $value){
                $value=trim($value);
                if(strlen($value)>1) $minus[]=$value;
            }
        }
        $this->minusWords=array_values(array_unique($minus));
        unset($postData['minuswords']);        
        //--------------------------------------
         
        //---Sitelinks Prepare----------
        foreach ($postData["Sitelinks"] as $key=>$value){
            if(strlen($value["Title"])<3) unset($postData["Sitelinks"][$key]);
            else{
                $postData["Sitelinks"][$key]["Title"]=$this->html_entity_decodeEx($postData["Sitelinks"][$key]["Title"]);
                $postData["Sitelinks"][$key]["Href"]=CEDirectBanner::addProtokolToURL($postData["Sitelinks"][$key]["Href"]);
                $postData["Sitelinks"][$key]["Description"]=$this->html_entity_decodeEx($postData["Sitelinks"][$key]["Description"]);
                if(!strlen($postData["Sitelinks"][$key]["Description"])) unset($postData["Sitelinks"][$key]["Description"]);
            }
        }
        $this->siteLinks=array_values($postData["Sitelinks"]);
        unset($postData["Sitelinks"]);
        //---------------------------------------
        
        //---compile action array for add or create several companies
        if(is_numeric($postData['addtocompany'])&&$postData['addtocompany']>0){ //add banners to isset company
            $this->arActions[]=array(
                "action"=>"addtocompany",
                "basecompany"=>$postData['addtocompany'],
                "isRSYA"=>CEDirectCompany::IsRsya($postData['addtocompany']),
                "issetCompanyID"=>$postData['addtocompany']
            );
        }
        else if($postData['addtocompany']=="createnew"){ //create new companies
            if($postData["forSEARCH"]=="Y"){
                if(!is_numeric($postData['basecompany'])) $postData['basecompany']=0;
                $this->arActions[]=array(
                    "action"=>"createnew",
                    "basecompany"=>$postData['basecompany'],
                    "isRSYA"=>false,
                    "issetCompanyID"=>0
                );
            }
            if($postData["forRSYA"]=="Y"){
                if(!is_numeric($postData['basecompany_rsya'])) $postData['basecompany_rsya']=0;
                $this->arActions[]=array(
                    "action"=>"createnew",
                    "basecompany"=>$postData['basecompany_rsya'],
                    "isRSYA"=>true,
                    "issetCompanyID"=>0
                );
            }
        }
        else {
            $this->message=array(
                "MESSAGE"=>GetMessage("EASYDIRECT_EXPORT_create_yandex_no_params"),
                "TYPE"=>"ERROR"
            );
            $this->error=1;
        }
        //------------------------------------------------------------------------------------------
        
        $this->maxProgress=100*count($this->arActions);
        
        $this->postData=$postData;
    }
    
    /**
     * set Default Variables before start new action
     */
    private function setDefaultVariables()
    {
        //set default params
        $this->curAction="";
        $this->isRSYA=false;
        $this->arBaseParams=array();
        $this->isManualStrategy=true;
        $this->issetBannersCnt=0;
        
        $this->arSendBannerGroups=array();
        $this->arCreatedBannerGroupIDs=array();
        $this->arSendBanners=array();
        $this->arSendPhrases=array();
        
        $this->newVCardId=0;
    }
    
    /**
     * Execute export from last step
     * 
     * @return int 0 - error; 1 - next step; 2 - finish
     */    
	public function execute()
	{
	    global $obYaExchange;
	    
		if($this->error){
		    return 0;
		}
		
		$this->startTime=time();
		
		//=================================
		//==============STEP 1===============
		//=================================
		//create company, sitelinks, vcard, collect images and groups arrays
		if($this->curStep==1){
		    //get action array
		    $action=array_shift($this->arActions);
		    if($action===NULL) return 2;
		    //set default params
		    $this->setDefaultVariables();
		    $this->isRSYA=$action["isRSYA"];
		    $this->curAction=$action['action'];
		    
		    //---------Get BASE PARAMS -----------------
		    if($action["basecompany"]>0){
		        $this->arBaseParams=CEDirectCompany::getBaseParamsFromYa($action["basecompany"]);
		    }
		    //------------------------------------------------
		    	
		    //=========CREATE/UPDATE COMPANY =============		    
		    //-------------CREATE NEW COMPANY-----------------
		    if($action['action']=="createnew")  {
		        //new Company main Params
		        $arNewCompany=array(
		            "Name"=>trim($this->postData['name']."/".($this->isRSYA?GetMessage("EASYDIRECT_CREATE_rsya_prefix"):GetMessage("EASYDIRECT_CREATE_poisk_prefix"))),
		            "StartDate"=>date("Y-m-d"),
		            "TextCampaign"=>array(
		                "BiddingStrategy"=>array(
		                    "Search"=>array(
		                        "BiddingStrategyType"=>"HIGHEST_POSITION"
		                    ),
		                    "Network"=>array(
		                        "BiddingStrategyType"=>"SERVING_OFF"
		                    ),
		                )
		            )
		        );
		         
		        if(count($this->minusWords)>0) $arNewCompany["NegativeKeywords"]=array("Items"=>$this->minusWords);
		         
		        //change strategy if RSYA
		        if($this->isRSYA) {
		            $arNewCompany['TextCampaign']['BiddingStrategy']['Search']['BiddingStrategyType'] = "SERVING_OFF";
		            $arNewCompany['TextCampaign']['BiddingStrategy']['Network']['BiddingStrategyType'] = "MAXIMUM_COVERAGE";
		        }
		         
		        //delete default strategy type if it isset in basecompany, check manual strategy type
		        if(isset($this->arBaseParams["Company"]['TextCampaign']['BiddingStrategy'])){
		            //check manual strategy
		            if($this->isRSYA) $STRATEGY_TYPE=$this->arBaseParams["Company"]['TextCampaign']['BiddingStrategy']['Network']['BiddingStrategyType'];
		            else $STRATEGY_TYPE=$this->arBaseParams["Company"]['TextCampaign']['BiddingStrategy']['Search']['BiddingStrategyType'];
		            $this->isManualStrategy=CEDirectCompany::isManualStrategy($STRATEGY_TYPE);
		            	
		            unset($arNewCompany["TextCampaign"]);
		        }
		    
		        //create Company and get params from basecompany
		        $newCID=CEDirectCompany::createCompanyInYa($arNewCompany,$this->arBaseParams["Company"]);
		         
		        if(!$newCID){
		            $this->message=array(
		                "MESSAGE"=>GetMessage("EASYDIRECT_EXPORT_create_yandex_err_send_company"),
		                "DETAILS"=> '<a href="/bitrix/admin/wtc_easydirect_log.php?lang=ru">'.GetMessage("EASYDIRECT_EXPORT_create_yandex_err_mess")."</a>",
		                "TYPE"=>"ERROR",
		                "HTML"=>true
		            );
		            $this->error=1;
		            return 0;
		        }
		        else if($action["basecompany"]>0){
		            //---------------COPY BidModifiers------------------
		            if(!$obYaExchange->copyCompanyBidModifiers($action["basecompany"],$newCID)){
		                $this->cntErr++;
		            }
		            //------------------------------------------------------------
		        }
		        $this->curCompanyID=$newCID;
		    }
		    //-----OR ADD banners to isset company----
		    else if($action['action']=="addtocompany"&&$action['issetCompanyID']>0) {
		        //---add new MINUS words to  isset company---
		        if(count($this->minusWords)>0){
		            //get old minus words
		            $arCampaign=$obYaExchange->getCompanyParams($action['issetCompanyID']);
		            if(is_array($arCampaign)){
		                $arCampaign=$arCampaign[0];
		                if(is_array($arCampaign['NegativeKeywords']['Items'])&&count($arCampaign['NegativeKeywords']['Items'])) {
		                    $this->minusWords=array_values(array_unique(array_merge($arCampaign['NegativeKeywords']['Items'],$this->minusWords)));
		                }
		                $obYaExchange->updateCompany(array(
		                    "Id"=>$action['issetCompanyID'],
		                    "NegativeKeywords"=>array("Items"=>$this->minusWords)
		                ));
		            }
		        }
		        //-------------count isset groups-------------------
		        $rsCnt = CEDirectBannerGroup::GetList(array(), array("ID_COMPANY"=>$action['issetCompanyID']),array());
		        $arCnt = $rsCnt->Fetch();
		        if($arCnt['CNT']>0){$this->issetBannersCnt=$arCnt['CNT'];}
		        //---------------------------------------------------------------
		         
		        $this->curCompanyID=$action['issetCompanyID'];
		    }
		    //=================================
		    
		    //======CREATE SITELINKS & VCARD ===========
		    if( $this->curCompanyID>0 && count($this->postData['group']) ){
		        //-------------CREATE SITELINKS---------------------------
		        if( count($this->siteLinks)>0 ){
		            //ADD UTM to Href of Links
		            if($this->postData["addUtm"]=="Y"){
		                $arSiteLinks=$this->siteLinks;
		                foreach ($arSiteLinks as &$link){
		                      $link["Href"]=$this->addUtmToUrl($link["Href"],"blink");	
		                }
		                unset($link);
		            }
		            //or copy without UTM
		            else {
		                //create new if not create before
		                if($this->sitelinkSetId==0) $arSiteLinks=$this->siteLinks;
		                else $arSiteLinks=array();
		            }
		            
		            //send SiteLinks to Yandex
		            if(count($arSiteLinks)>0){
    		            $this->sitelinkSetId=$obYaExchange->createNewSiteLinks($arSiteLinks);
    		            if(!$this->sitelinkSetId) $this->cntErr++;
		            }
		        }
		        //-------------------------------------------------------------------
		        //---------------CLONE VCARD-------------------------------
		        if( $this->arBaseParams['Additional']['VCardId']>0 ){
		            $this->newVCardId=$obYaExchange->cloneVCard($this->curCompanyID,$this->arBaseParams['Additional']['VCardId']);
		        }
		        //-------------------------------------------------------------------		        
		    }
		    else {
		        $this->message=array(
		            "MESSAGE"=>GetMessage("EASYDIRECT_EXPORT_create_yandex_err"),
		            "DETAILS"=> '<a href="/bitrix/admin/wtc_easydirect_log.php?lang=ru">'.GetMessage("EASYDIRECT_EXPORT_create_yandex_err_mess")."</a>",
		            "TYPE"=>"ERROR"
		        );		        
		        $this->error=1;		        
		        return 0;
		    }
		    //===================================
		
    		//===CREATE IMAGES SEND ARRAY / Upadte post banner data==
    		if($this->isCreateImg==false){ //export images and create hashes only ones
    		    //collect all images info
    		    $arImages=array();
    		    //collect def image info
    		    if($this->postData["rsya_img"]["tmp_name"]){
    		        $arImages["defimg"]["url"]=$_SERVER['DOCUMENT_ROOT']."/upload/tmp".$this->postData["rsya_img"]["tmp_name"];
    		        $arImages["defimg"]["name"]=$this->postData["rsya_img"]["name"];
    		    }
    		    //collect banners images
    		    foreach ($this->postData['group'] as $gid=>$group){
    		        foreach ($group["banners"] as $bid=>$banner){
    		            if($banner["image"]){
    		                if($banner["image_type"]=="bitrix_file"){
    		                    $filePath=CFile::GetPath($banner["image"]);
    		                    if($filePath!==NULL){
    		                        $arImages[$bid]["url"]=$_SERVER['DOCUMENT_ROOT'].$filePath;
    		                    }
    		                }
    		                else if($banner["image_type"]=="user_file"){
    		                    $arImages[$bid]["url"]=$banner["image"];
    		                }
    		                else if($banner["image_type"]=="yandex_hash"){
    		                    //add hash to banner
    		                    $this->postData['group'][$gid]['banners'][$bid]['image_hash']=$banner["image"];
    		                }
    		                if( strlen($arImages[$bid]["url"])>0 && preg_match("/.*\/([^\/]*)$/".(EDIRECT_UTFSITE?"u":""), $arImages[$bid]["url"],$regs) ){
    		                    $arImages[$bid]["name"]=$regs[1];
    		                }
    		            }
    		            if( !isset($arImages[$bid]["url"]) || !isset($arImages[$bid]["name"]) || $this->postData["defImgForAll"]=="Y"){ //using def image
    		                if(isset($arImages["defimg"])) {
    		                    $arImages[$bid]=$arImages["defimg"];
    		                    //clear image hash if isset from old banner
    		                    $this->postData['group'][$gid]['banners'][$bid]['image_hash']="";
    		                }
    		            }
    		            //add images params to banner array
    		            $this->postData['group'][$gid]['banners'][$bid]['image_name']=$arImages[$bid]["name"];
    		        }
    		    }
    		    //delete def image
    		    unset($arImages["defimg"]);
    		    
    		    //------filter $arImages delete the same pictures before send-------
    		    $arUniqueImages=array();
    		    $arImagesNames=array();
    		    $arImagesSrcHashes=array();
    		    $arTheSameImages=array();
    		    foreach ($arImages as $val){
    		        //skip empty urls
    		        if(strlen($val["url"])<6) continue;
    		        //skip the same names, same names = same images
    		        if(in_array($val["name"],$arImagesNames)) continue;
    		        //check isset hashes to find the same images with different names
    		        $srcHash=sha1_file($val["url"]);
    		        if($srcHash&&isset($arImagesSrcHashes[$srcHash])) {
    		            //write mapping
    		            $arTheSameImages[$val["name"]]=$arImagesSrcHashes[$srcHash];
    		            continue;
    		        }
    		        //compile unique array for send
    		        $arUniqueImages[]=$val;    		        
	                $arImagesNames[]=$val["name"];
	                if($srcHash) $arImagesSrcHashes[$srcHash]=$val["name"];
    		    }
    		    //-----------------------------------------------------------------------------------------------
    		    $this->arTheSameImages=$arTheSameImages;
    		    $this->arSendImages=$arUniqueImages;
    		}
    		//======================================
    		
    		//=====CREATE BANNER GROUPS SEND ARRAYS=======
    		$arBannerGroupsToYa=array();
    		$arBannersToYa=array();
    		$arPhrasesToYa=array();		
    		if( count($this->postData['group']) ) {
    
    		    $cntGroup=$this->issetBannersCnt+1;
    		    foreach ($this->postData['group'] as $group){
    		    	$groupName=GetMessage("EASYDIRECT_CREATE_create_yandex_group").$cntGroup;
    		    	$bannerKeys=array_keys($group["banners"]);
    				if(strlen($group["banners"][$bannerKeys[0]]["title"])>0) $groupName=$group["banners"][$bannerKeys[0]]["title"];
    		        $arNewGroup=array(
    		            "Name"=>$groupName,
    		            "CampaignId"=>$this->curCompanyID,
    		            "RegionIds"=>array(0)
    		        );
    		        if(isset($this->arBaseParams["BannerGroup"]["RegionIds"])&&count($this->arBaseParams["BannerGroup"]["RegionIds"])>0) unset($arNewGroup["RegionIds"]);
    		    	    
    		        //collect Groups in array, will send it in step 2
    		        $arBannerGroupsToYa[]=$arNewGroup;
    		        $cntGroup++;
    		        
    		    }
    		    $this->arSendBannerGroups=$arBannerGroupsToYa;
    		}
    		//=================================
    		
    		//print_r($this->arSendImages);
    		//print_r($this->arSendBannerGroups);
    		//print_r($this->arTheSameImages);
    		
    		$this->addToMainProgress(10);
    		$this->sleep();
    		$this->curStep=2;
    		return 1;
		}
		
		//=================================
		//==============STEP 2===============
		//=================================
		//send images and groups arrays
		else if($this->curStep==2){
		    //====SEND IMAGES from collected data=======
		    if($this->isCreateImg==false&&count($this->arSendImages)>0){
    	       while(count($this->arSendImages)>0){
    	           //create images in Yandex
    	   	       $arHashes=$obYaExchange->createImagesInYa(array_splice( $this->arSendImages, -1*($this->imageCntSendByStep) ));  //send slice images, CNT step
    	   	       //save hashes
    	   	       $this->arImgHashes=array_merge($this->arImgHashes,$arHashes);
    	   	       //check exec time, stop if much time
    	   	       //do not stop if arSendImages emty, because we need add the same images with different names bottom    	   	       
    	   	       if( $this->isNextStep() && count($this->arSendImages)>0 ) {return 1;}
    	       }
    	       
    	       //plus the same images with different names
    	       foreach ($this->arTheSameImages as $imgName=>$theSameImgName){
    	           if(isset($this->arImgHashes[$theSameImgName])) {
    	               $this->arImgHashes[$imgName]=$this->arImgHashes[$theSameImgName];
    	           }
    	       }
    	       
    	       $this->isCreateImg=true;
    	       //add Progress
    	       if(count($this->arSendImages)==0) $this->addToMainProgress(30);
		    }
		    //===============================
		    
		    //=====SEND BannerGroup from collected data ===
		    while(count($this->arSendBannerGroups)>0){
		        //create groups in Yandex
		        $arNewBannerGroupIDs=CEDirectBannerGroup::createBannerGroupsInYa(
		            array_splice( $this->arSendBannerGroups, -1*($this->groupCntSendByStep) ), //send slice groups, CNT step
		            $this->arBaseParams["BannerGroup"]
		            );
		        //save new IDs
		        $this->arCreatedBannerGroupIDs=array_merge($this->arCreatedBannerGroupIDs,$arNewBannerGroupIDs);
		        //check exec time, stop if much time
		        if( $this->isNextStep() && count($this->arSendBannerGroups)>0 ) {return 1;}
		        //add Progress
		        if(count($this->arSendBannerGroups)==0) $this->addToMainProgress(10);
		    }
		    //===============================
		    
		    //print_r($this->arImgHashes);
		    //print_r($this->arCreatedBannerGroupIDs);
		    
		    $this->sleep();
		    $this->curStep=3;
		    return 1;		    
		}
		
		//=================================
		//==============STEP 3===============
		//=================================
		//collect banners and phrases arrays
		else if($this->curStep==3){
		    if(count($this->arCreatedBannerGroupIDs)>0){ //check if groups IDs isset
		        foreach ($this->postData['group'] as $group){
		            $newBGID=array_shift($this->arCreatedBannerGroupIDs);
		            if($newBGID>0){
		                //-------------CREATE BANNERS---------------------------
		                foreach ($group["banners"] as $banner){
		                    $arNewBanner=array(
		                        "AdGroupId"=>$newBGID,
		                        "TextAd"=>array(
		                            "Title"=>$this->html_entity_decodeEx($banner["title"]),
		                            "Text"=>$this->html_entity_decodeEx($banner["text"]),
		                            "Mobile"=>"NO",
		                            "Href"=>((strlen($banner["href"])>2)?$banner["href"]:$this->postData['href']),
		                            "DisplayUrlPath"=>((strlen($banner["display_url"])>2)?$banner["display_url"]:$this->postData['display_url'])
		                        )
		                    );
		                    $arNewBanner["TextAd"]["Href"]=$this->addUtmToUrl(CEDirectBanner::addProtokolToURL($arNewBanner["TextAd"]["Href"]));
		                    $arNewBanner["TextAd"]["DisplayUrlPath"]=preg_replace("/( +)/", "-", trim($arNewBanner["TextAd"]["DisplayUrlPath"]));
		                    if(is_numeric($banner["price"])&&$banner["price"]>0) $arNewBanner["TextAd"]["PriceExtension"]=array("Price"=>CEDirectCompany::convertCurrencyToYa($banner["price"]),"PriceQualifier"=>"NONE","PriceCurrency"=>CEDirectCatalogItems::getCatalogCurrency());
		                    if(strlen($arNewBanner["TextAd"]["DisplayUrlPath"])<3) unset($arNewBanner["TextAd"]["DisplayUrlPath"]);
		                    if(strlen($banner["title2"])>2) $arNewBanner["TextAd"]["Title2"]=$this->html_entity_decodeEx($banner["title2"]);
		                    if($this->sitelinkSetId>0) $arNewBanner["TextAd"]["SitelinkSetId"]=$this->sitelinkSetId;
		                    if($this->newVCardId>0) $arNewBanner["TextAd"]["VCardId"]=$this->newVCardId;
		                    //Image
		                    if(strlen($banner["image_hash"])>10) $arNewBanner["TextAd"]["AdImageHash"]=$banner["image_hash"];
		                    else if( 
		                        isset($this->arImgHashes[$banner["image_name"]]) 
		                        && strlen($this->arImgHashes[$banner["image_name"]])>10
		                       ) {
		                            $arNewBanner["TextAd"]["AdImageHash"]=$this->arImgHashes[$banner["image_name"]];
		                    }
		                     
		                    //collect banners in array, will send it in step 4
		                    $this->arSendBanners[]=$arNewBanner;
		                }
		                //-------------CREATE PHRASES---------------------------
		                $arPhrases=array_unique(explode("\n",$group['phrases']));
		                if (count($arPhrases)){
		                    foreach ($arPhrases as $value){
		                        $value=trim($value);
		                        if($value!=""&&$value!="%%%") {
		                            $arTmp=array(
		                                'Keyword'=>$value,
		                                'AdGroupId'=>$newBGID
		                            );
		                            //apply Bid if ManualStrategy, different type for other companies
		                            if($this->isManualStrategy){
		                                if($this->isRSYA) $arTmp["ContextBid"]=$this->defPrice;
		                                else $arTmp["Bid"]=$this->defPrice;
		                            }
		                            //collect Phrases in array, will send it in step 4
		                            $this->arSendPhrases[]=$arTmp;
		                        }
		                    }
		                }
		            }
		            else $this->cntErr++;
		        }
		    }
		    else {
		        $this->message=array(
		            "MESSAGE"=>GetMessage("EASYDIRECT_EXPORT_create_yandex_no_create_groups"),
		            "TYPE"=>"ERROR"
		        );
		        $this->error=1;		        
		        return 0;
		    }
		    
		    $this->addToMainProgress(5);
		    $this->sleep();
		    $this->curStep=4;
		    return 1;
		}	

		//=================================
		//==============STEP 4===============
		//=================================
		//send banners and phrases arrays
		else if($this->curStep==4){
		    //=====SEND Banners from collected data =====
		    while(count($this->arSendBanners)>0){
		        //create Banners in Yandex
		        $arNewBannerIDs=CEDirectBanner::createBannersInYa(
		            array_splice($this->arSendBanners, -1*($this->bannerCntSendByStep) ), //send slice banners, CNT step
		            $this->arBaseParams["Banner"]
		        );
		        //cnt Errors
		        if(count($arNewBannerIDs)==0) $this->cntErr++;
		        //add Progress
		        if(count($this->arSendBanners)==0) $this->addToMainProgress(20);
		        //check exec time, stop if much time
		        if( $this->isNextStep() ) {return 1;}
		    }
		    //===============================
		    
		    //=====SEND Phrases from collected data =====
		    while(count($this->arSendPhrases)>0){
		        //create Phrases in Yandex
		        $res=$obYaExchange->createNewPhrases( array_splice($this->arSendPhrases, -1*($this->phraseCntSendByStep)) ); //send slice Phrases, CNT step
		        //cnt Errors
		        if(!$res) $this->cntErr++;
		        //add Progress
		        if(count($this->arSendPhrases)==0) $this->addToMainProgress(20);
		        //check exec time, stop if much time
		        if( $this->isNextStep() ) {return 1;}
		    }
		    //===============================		    
		    

		    //if add banners to isset company, update company in module
		    if( $this->curAction=="addtocompany" && $this->curCompanyID>0 ){
		        CEDirectCompany::import($this->curCompanyID,true);
		    }
		    $this->addToMainProgress(5);
		    
		    $this->sleep();
		    $this->curStep=1;
		    return 1;		    
		}		
	}	
	
	/**
	 * add UTM to url
	 */
	public function addUtmToUrl($url,$type="")
	{
	    $url=trim($url);
	    if($this->postData["addUtm"]=="Y"){

 	        //compile UTM string
	        if($this->isRSYA) $utm_medium="rsya";
	        else $utm_medium="search";
	        
	        if($type=="blink") $utm_medium=$utm_medium."_blink";
	        
	        $utm='utm_source=direct.yandex.ru&utm_medium='.$utm_medium.'&utm_campaign={campaign_id}&utm_content=ad_{ad_id}&utm_term={keyword}';
	        //-----------------------------

			//find and save anchor #
			$anchor="";
			$arUrl=explode("#",$url);
			if(strlen($arUrl[1])>0){
				$anchor=$arUrl[1];
				$url=$arUrl[0];
			}

	        $arUrl=explode("?",$url);
	        if(strlen($arUrl[1])>0){
	            $pos=stripos($arUrl[1], "utm_");
	            if ($pos === false) {
	                $url=$url."&".$utm;
	            }
	        }
	        else{
	            $url=$url.'?'.$utm;
	        }

	        //add saved anchor
			if(strlen($anchor)>0){
				$url=$url."#".$anchor;
			}

	    }
	    return $url;	     
	}
	
	/**
	 * Check execute time, jump to next step
	 */
	public function isNextStep()
	{
	    if( (time()-$this->startTime) > $this->execStepTime ) {
	        // add to pod Progress
	        if($this->curPodProgress>10) $this->curPodProgress+=1;
	        else $this->curPodProgress+=2;
	        
	        return 1;
	    }
	    else return 0;
	}	
	
	/**
	 * sleep if time between steps is very small / Emulated steps
	 */
	public function sleep()
	{
	    if( (time()-$this->startTime)<2 ) sleep(1); // 1 sec
	    return 1;
	}
	
	/**
	 * Get Error message
	 */
	public function getErrorMessage()
	{
	    return $this->message;
	}	
	
	/**
	 * Get CNT Addition Errors
	 */
	public function getCntAdditionErrors()
	{
	    return $this->cntErr;
	}	
	
	/**
	 * add to main progress
	 */
	public function addToMainProgress($addToProgress)
	{
	    $this->curPodProgress=0;
	    $this->curProgress+=$addToProgress;
	}
	
	/**
	 * Get Progress Variables
	 */
	public function getProgressBarArray()
	{
	    $arProgressBar=array(
            "MESSAGE"=>GetMessage("EASYDIRECT_EXPORT_create_yandex_send"),
            "DETAILS"=> "#PROGRESS_BAR#".GetMessage("EASYDIRECT_EXPORT_create_yandex_send_mess"),
            "HTML"=>true,
            "TYPE"=>"PROGRESS",
            "PROGRESS_TOTAL" => $this->maxProgress,
            "PROGRESS_VALUE" => ($this->curProgress+$this->curPodProgress)
        );
	    return $arProgressBar;
	}

	/**
	 * convert P (RUB) and other HTML entity to normal values
	 */
	public function html_entity_decodeEx($string)
	{
		if(EDIRECT_UTFSITE) { //use only utf sites, in cp1251 it is difficult to convert and need change toJSON function
			return trim(html_entity_decode($string, ENT_QUOTES, "UTF-8"));
		}
		else {
			return trim($string);
		}
	}
}
?> 
