<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

/**
 * Class CEDirectYaExchange
 * exchange methods for Yandex API
 * @category module wtc.easydirect YaExchange
 */
class CEDirectYaExchange{

 	private $jsonurlv4= 'https://api.direct.yandex.ru/live/v4/json/';
 	private $jsonurlv5= 'https://api.direct.yandex.com/json/v5/';
 	private $ip='';
	private $yalogin = '';
	private $skipLogin = false;
	private $lastresult = '';
	private $token ='';
	private $isTokenError=false;
	
	private $arCache=array();	
	private $curlEnabled=false;
	private $ch=null; 
	
	//===============================
	//-----------------------------EXEC FUNTIONS------------------
	//===============================	
	/**
	 * constructor SET default params init main Curl params
	 */	
	  public function __construct() {
            global $EDirectMain;
	        //check is enabled curl 
	        if(function_exists('curl_init')) {$this->curlEnabled=true;}
	        else {return 0;}
	      
 	        if(defined('EDIRECT_YA_EXCHANGE_IP')) $this->ip=EDIRECT_YA_EXCHANGE_IP;  //IP
	  		$this->yalogin  = EDIRECT_YALOGIN;	 // Ya login
	  		$this->token  = $EDirectMain->getYaToken(); //token
	  	
			$this->ch = curl_init();
			
			curl_setopt($this->ch, CURLINFO_HEADER_OUT,1);
			curl_setopt($this->ch, CURLOPT_HEADER,1);			
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,1);
			if($this->ip) curl_setopt($this->ch,CURLOPT_INTERFACE,$this->ip);
			curl_setopt($this->ch, CURLOPT_POST,1);

			//certificate params
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
	  }	  
	  
	  public function __destruct() {
	  	 	if($this->isCurlEnabled()) curl_close($this->ch);
	  	 	$this->clearCache();
	  }
	  
	  public function sendRequest($request){
	       curl_setopt($this->ch, CURLOPT_POSTFIELDS, $request);
	       return curl_exec($this->ch);
	  }
	  
	  //check is curl enabled
	  public function isCurlEnabled() {
   	        if($this->curlEnabled) return true;
   	        else return false;
	  }
	  
	  //clear Cache
	  public function clearCache() {
	      unset($this->arCache);
	      $this->arCache=array();
	  }	  
	  
	  /**
	   * wrile log
	   * 
	   * @param string $log   message
	   */	  
 	  private function writelog($log,$TYPE="E") {	  
 	  		$log="YandexApiAnswer: ".$log;
 	  		CEDirectLog::Add(array("MESSAGE"=>$log,"TYPE"=>$TYPE));
	  }	  
	    
	  /**
	   * send request to Yandex API v4
	   *
	   * @param string $method   API method name
	   * @param array $params   params for API method
	   * @param bolean $debug   show debug messages, default = false
	   * @return int 1/0 or result
	   */	  
 	  private function zapros($method,$params,$debug=false) {
    	    global $EDirectMain;
 	        //check is curl enabled
  	        if(!$this->isCurlEnabled()) return 0;
 	      
 	        //check Authorization Settings
 	        if(strlen($this->token)<10||$this->isTokenError) return 0;
 	      
 	  		//statistics
 	  		CEDirectYaExchangeStat::AddCount(array("NAME"=>"API4 ".$method));
 	  		
 	  		//setURL
 	  		curl_setopt($this->ch, CURLOPT_URL,$this->jsonurlv4);
 	  		
	 	  	//create request
	 	  	$request = array(
		 	  	'locale'    => 'ru',
	 	  		'token' => $this->token,
		 	  	'method'    => $method,
		 	  	'param'        => $params,
	 	  	);
	 	  	
	 	  	$arResult=$EDirectMain->getResult($request);
	 	  	$result=$arResult["result"];
	 	  	$headers=$arResult["headers"];
	 	  		 	  	 	  	
	 	  	//DEBUG
	 	  	$requestDebugData="";
	 	  	//collect Request Debug data
	 	  	if($debug||EDIRECT_DEBUG_ERRORS_IN_LOG){
    	 	  	$requestDebugData.=PHP_EOL.date("H:i:s").PHP_EOL.PHP_EOL;
    	 	  	$requestDebugData.="<b>==Request:==</b>".PHP_EOL;
    	 	  	$requestDebugData.=print_r($request,true).PHP_EOL.PHP_EOL;
    	 	  	$requestDebugData.="<b>==Headers:==</b>".PHP_EOL;
    	 	  	$requestDebugData.=print_r($headers,true).PHP_EOL.PHP_EOL;
    	 	  	$requestDebugData.="<b>==Result:==</b>".PHP_EOL;
    	 	  	$requestDebugData.=print_r($result,true).PHP_EOL.PHP_EOL;
    	 	  	$requestDebugData.="<b>==Curl_getinfo:==</b>".PHP_EOL;
    	 	  	$requestDebugData.=print_r(curl_getinfo($this->ch),true);
	 	  	} 	
     	    if($debug) {
    	         echo "<pre>";
    	         echo $requestDebugData;
    	         echo "</pre>";
    	         if(!EDIRECT_DEBUG_ERRORS_IN_LOG) $requestDebugData="";
    	    }

            //get HTTPCode from header
            $headerParams=array();
            preg_match("/HTTP\/1\.1 ([0-9]*)/", $headers, $regs);
            $headerParams["HTTPCode"]=$regs[1];

            //Auth error
            if($headerParams["HTTPCode"]=="400"){
                $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_AUTH_ERR'));
                $this->isTokenError=true;
                return 0;
            }

 	        $cErr = curl_error($this->ch);
			if ($cErr != ''&&curl_errno($this->ch)!=0) {
	        	$err = 'cURL ERROR: '.curl_errno($this->ch).': '.$cErr;
	        	$this->writelog($err);
		    	return 0;
			}
			else{
                try { //catch Exception JSON Syntax error
                    $result = \Bitrix\Main\Web\Json::decode($result);
                    $this->lastresult = $result;
                    //print_r($result);
                    if (isset($result['data'])) {
                        //CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_YAEXCH_LOG_MESS_3')." ".$method,"TYPE"=>"M"));
                        return $result['data'];
                    } else if ($result['error_code']) {    // read return error from API
                        //Auth error
                        if($result['error_code']==53){
                            $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_AUTH_ERR'));
                            $this->isTokenError=true;
                        }
                        $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_MESS_1') . " " . $result['error_code'] . ": " . $result['error_str'] . " (" . $result['error_detail'] . ")" . $requestDebugData);
                        return 0;
                    } else {
                        $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_MESS_2', array("#METHOD#" => $method)) . $requestDebugData);
                        return 0;
                    }
                }
                catch(Exception $e){
                    $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_MESS_JSON_ERR', array("#METHOD#" => $method)));
                    return 0;
                }
			}			
	 }
	 
	 /**
	  * send request to Yandex API v5
	  *
	  * @param string $url   API method URL
	  * @param string $method   API method name
	  * @param array $params   params for API method
	  * @param bolean $debug   show debug messages, default = false
	  * @return int 1/0 or result array
	  */
	 private function requestV5($class,$method,$params,$debug=false) {
	    global $EDirectMain;
        //check is curl enabled
        if(!$this->isCurlEnabled()) {
         $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_MESS_CURL'));
         return 0;	     
        }

        //check Authorization Settings
        if(strlen($this->token)<10||(strlen($this->yalogin)<3&&!$this->skipLogin)) {
         $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_MESS_5'));
         return 0;
        }
        //check token error
        if($this->isTokenError) return 0;
        
        //set URL, HTTP Heders
        curl_setopt($this->ch, CURLOPT_URL,$this->jsonurlv5.$class);
        $headers=array(
         "Authorization: Bearer ".$this->token,
         "Accept-Language: ru",
         "Content-Type: application/json; charset=utf-8",
         "Expect:"
        );
        if(!$this->skipLogin) {$headers[]="Client-Login: ".$this->yalogin;}
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        
        //Request
        $request = array(
         'method'    => $method,
         'params'        => $params
        );
        $arResult=$EDirectMain->getResult($request);
        $result=$arResult["result"];
        $headers=$arResult["headers"];

	    //DEBUG
        $requestDebugData="";
        //collect Request Debug data
        if($debug||EDIRECT_DEBUG_ERRORS_IN_LOG){
          	$requestDebugData.=PHP_EOL.date("H:i:s").PHP_EOL.PHP_EOL;
          	$requestDebugData.="<b>==Request:==</b>".PHP_EOL;
          	$requestDebugData.=print_r($request,true).PHP_EOL.PHP_EOL;
          	$requestDebugData.="<b>==Headers:==</b>".PHP_EOL;
          	$requestDebugData.=print_r($headers,true).PHP_EOL.PHP_EOL;
          	$requestDebugData.="<b>==Result:==</b>".PHP_EOL;
          	$requestDebugData.=print_r($result,true).PHP_EOL.PHP_EOL;
          	$requestDebugData.="<b>==Curl_getinfo:==</b>".PHP_EOL;
          	$requestDebugData.=print_r(curl_getinfo($this->ch),true);
        } 	
        if($debug) {
             echo "<pre>";
             echo $requestDebugData;
             echo "</pre>";
             if(!EDIRECT_DEBUG_ERRORS_IN_LOG) $requestDebugData="";
        }
	     
        //get Params from header
        $headerParams=array();
        preg_match("/HTTP\/1\.1 ([0-9]*)/", $headers, $regs);
        $headerParams["HTTPCode"]=$regs[1];
        preg_match("/RequestId: ([0-9]*)/", $headers, $regs);
        $headerParams["RequestId"]=$regs[1];
        preg_match("/Units: ([0-9\/]*)/", $headers, $regs);
        $headerParams["Units"]=explode("/", $regs[1]);

        //Auth error
        if($headerParams["HTTPCode"]=="400"){
            $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_AUTH_ERR'));
            $this->isTokenError=true;
            return 0;
        }

        //Full method name
        $methodFullName=$class.".".$method;        
        //write request Statistic
        CEDirectYaExchangeStat::AddCount(array("NAME"=>$methodFullName,"UNITS_COST"=>$headerParams["Units"][0]));
        
        $cErr = curl_error($this->ch);
        if ($cErr != ''&&curl_errno($this->ch)!=0) {
         $err = 'CURL ERROR: '.curl_errno($this->ch).': '.$cErr." - ".$methodFullName;
         $this->writelog($err);
         return 0;
        }
        else{
            try{ //catch Exception JSON Syntax error
                 $result=\Bitrix\Main\Web\Json::decode($result);
                 //$this->lastresult=$result;
                 //print_r($result);
                 if (isset($result['result'])) {
                     //CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_YAEXCH_LOG_MESS_3')." ".$method,"TYPE"=>"M"));
                     return array_merge($headerParams,$result);
                 } else if($result['error']) { 	// read return error from API
                     //Auth error
                     if($result['error']['error_code']==53){
                         $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_AUTH_ERR'));
                         $this->isTokenError=true;
                     }
                     $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_MESS_1')." ".$result['error']['error_code'].": ".$result['error']['error_string']." (".$result['error']['error_detail'].")".$requestDebugData);
                     return 0;
                 } else {
                     $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_MESS_2',array("#METHOD#"=>$methodFullName)).$requestDebugData);
                     return 0;
                 }
            }
            catch(Exception $e){
                if($this->isNoReturnJson($class)){
                    preg_match("/retryIn: ([0-9]*)/", $headers, $regs);
                    $headerParams["retryIn"]=$regs[1];
                    
                    return array_merge($headerParams,array("result"=>$result));
                }
                else return 0;
            }
        }	     
	 }
	 
	 /**
	  * processing Additional Errors and Warnings in change methods API5
	  *
	  * @param array $arResult
	  * @return int   count Errors and Warnings 
	  */	 
	 private function processingAdditionalErrors($arResult){
	     $cntErr=0;
	     //Warnings
	     if(is_array($arResult["Warnings"])&&count($arResult["Warnings"])>0){
	         foreach ($arResult["Warnings"] as $err){
	             if($err['Code']=="10140") continue; //ignore this warning, it is not important
    	         $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_MESS_6')." ".$err['Code'].": ".$err['Message']." (".$err['Details'].")","W");
    	         $cntErr++;
	         }
	     }
	     //Errors
	     if(is_array($arResult["Errors"])&&count($arResult["Errors"])>0){
	         foreach ($arResult["Errors"] as $err){
	             $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_MESS_1')." ".$err['Code'].": ".$err['Message']." (".$err['Details'].")");
	             $cntErr++;
	         }	     
	     }
	     return $cntErr;
	 }
	 
	 /**
	  * check API class no return JSON
	  *
	  * @param string $name   name API method
	  * @return bool
	  */
	 private function isNoReturnJson($name){
	     $classNames=array(
	         "reports",
	     );
	     return in_array($name,$classNames);
	 }	 
	 
	 /**
	  * print last result for debug
	  */	 
 	 public function getlastresult(){
		print $this->lastresult;  		
		return 1;
 	 }	

 	 /**
 	  * DATE to ISO 8601 converting
 	  * 
 	  * @param string $DATE       date in format YYYY-MM-DD HH:MI:SS
 	  * @return string     ISO 8601 date
 	  */
 	 private function DateToISO($DATE)
 	 {
 	 	preg_match("/([+|-]{1})([0-9]{2})([0-9]{2})/", date("O"),$tzone);
 	 	if($tzone[1]=="-") $tzone[1]="+";
 	 	else $tzone[1]="-";
 	 	$stmp=MakeTimeStamp($DATE,"YYYY-MM-DD HH:MI:SS");
 	 	$stmp=AddToTimeStamp(array("HH"=>$tzone[1].$tzone[2],"MI"=>$tzone[1].$tzone[3]), $stmp); 	 	
 	 	return date("Y-m-d\TH:i:s\Z",$stmp);
 	 }
 	 
 	 /**
 	  * ISO 8601 to site format date
 	  *
 	  * @param string $DATE       date in ISO 8601
 	  * @return string     site format date
 	  */
 	 private function ISODateToSiteFormat($DATE)
 	 {
 	 	$DATE=preg_replace("/[T|Z]/", " " , $DATE);
 	 	preg_match("/([+|-]{1})([0-9]{2})([0-9]{2})/", date("O"),$tzone);
 	 	$stmp=MakeTimeStamp($DATE, "YYYY-MM-DD HH:MI:SS ");
 	 	$stmp=AddToTimeStamp(array("HH"=>$tzone[1].$tzone[2],"MI"=>$tzone[1].$tzone[3]), $stmp);
 	 	return ConvertTimeStamp($stmp,"FULL");
 	 } 	 
 	 
 	 /**
 	  * TSV to array convert
 	  *
 	  * @param string $strTsv 
 	  * @return array
 	  */
 	 private function tsvToArray($strTsv)
 	 {
 	     $arTsv=array();
 	     
         $arr=explode("\n",$strTsv);
         $arKeys=explode("\t",trim($arr[1]));
         $cntKeys=count($arKeys);
         unset($arr[0],$arr[1]);
         $i=0;
         foreach ( $arr as $str ){
             $j=0;
             foreach ( explode("\t",trim($str)) as $key=>$val ){
                 $arTsv[$i][$arKeys[$key]]=$val;
                 $j++;
             }
             if($j<$cntKeys) {
                 unset($arTsv[$i]);
                 break;
             }
             $i++;
         }
         return $arTsv;
 	 }

     /**
     * Set skipLogin in V5 request
     *
     * @param boolean $skip
     */
     public function setSkipLogin($skip)
     {
        $this->skipLogin=$skip;
     }

     /**
     * get token error state
     *
     * @return boolean
     */
     public function getTokenError()
     {
        return $this->isTokenError;
     }

	 //===============================
 	 //-----------------------------METHODS--------------------------
 	 //===============================
 	 
 	 //======GET DATA========
 	 /**
 	  * get list of companies from Yandex
 	  *
 	  * @param boolean $onlyActive   get only Active (not archived) companies from Ya (default = true)
 	  * @return array     Copmany List
 	  */ 	 
 	 public function getCompanyList($onlyActive=true){
 	 	$params=array(
 	 	    'SelectionCriteria'=>array(
 	 	        'Types'=>array('TEXT_CAMPAIGN')
 	 	    ),
 	 	    'FieldNames'=>array('Id','Name','StatusClarification','State','EndDate')
 	 	);
 	 	
 	 	if($onlyActive) $params["SelectionCriteria"]["States"]=array('ON','OFF','SUSPENDED','ENDED');

 	 	$res=$this->requestV5("campaigns","get",$params);
 	 	if($res==0) return 0;
 	 
 	 	return $res["result"]["Campaigns"];
 	 } 	 
 	 
 	 /**
 	  * check changes in companies from date
 	  *
 	  * @param array $arID   company IDs to check
 	  * @param date $time   from date in format YYYY-MM-DD HH:MI:SS
 	  * @return array     changes
 	  */
 	 public function getCompanyChanges($arID,$time){
 	 	if(!is_array($arID)) $arID= array($arID);
 	 
 	 	$arID=array_chunk($arID,EDIRECT_YALIMIT_COMPANY);
 	 	$return=array();
 	 	foreach ($arID as $IDs){
	 	 	$params = array(
	 	 			'CampaignIds' => $IDs,
	 	 	        'FieldNames' => array("CampaignIds","AdGroupIds","AdIds"),
	 	 			'Timestamp'=>$this->DateToISO($time)
	 	 	);

	 	 	$res=$this->requestV5("changes","check",$params);
	 	 	if($res==0) return 0;
	 	 	
	 	 	if(count($return)==0){
	 	 	    $return=array(
	 	 	        "Timestamp"=>$this->ISODateToSiteFormat($res["result"]["Timestamp"]),
	 	 	        "Campaigns"=>array(),
	 	 	        "BannerGroupIDs"=>array(),
	 	 	        "Banners"=>array()
	 	 	    );
	 	 	}
	 	 	
 	 		if(is_array($res["result"]["Modified"]["CampaignIds"])) $return["Campaigns"]=array_merge($return["Campaigns"],$res["result"]["Modified"]["CampaignIds"]);
 	 		if(is_array($res["result"]["Modified"]["AdGroupIds"])) $return["BannerGroupIDs"]=array_merge($return["BannerGroupIDs"],$res["result"]["Modified"]["AdGroupIds"]);
 	 		if(is_array($res["result"]["Modified"]["AdIds"])) $return["Banners"]=array_merge($return["Banners"],$res["result"]["Modified"]["AdIds"]);
 	 	}
 	 	
 	 	return $return;
 	 } 	 
 	 
 	 /**
 	  * get all company's params
 	  *
 	  * @param array $arID   company IDs
 	  * @param bolean $getAdditionalParams   get Additional return params from Ya
 	  * @return array     companyes params
 	  */	 
 	 public function getCompanyParams($arID,$getAdditionalParams=false){
 	 	if(!is_array($arID)) $arID= array($arID);
 	 	
 	 	$arID=array_chunk($arID,EDIRECT_YALIMIT_COMPANY);
 	 	$return=array();
 	 	foreach ($arID as $IDs){
	 	 	$params = array(
	 	 	    'SelectionCriteria'=>array(
	 	 	        'Ids' => $IDs
	 	 	    ),
	 	 	    'FieldNames'=>array('Id','Name','Funds','StatusClarification','State','NegativeKeywords','EndDate'),
	 	 	    'TextCampaignFieldNames'=>array('BiddingStrategy')
	        );
	 	 	
	 	 	if($getAdditionalParams){
	 	 	    $params["FieldNames"]=array_merge($params["FieldNames"],array("DailyBudget","BlockedIps","ExcludedSites","Notification","TimeTargeting","TimeZone"));
	 	 	    $params["TextCampaignFieldNames"]=array_merge($params["TextCampaignFieldNames"],array("CounterIds","Settings","PriorityGoals","AttributionModel"));
	 	 	}
	 	 	
     	 	$res=$this->requestV5("campaigns","get",$params);
     	 	if($res==0) return 0;
	 	 	
     	 	if(is_array($res["result"]["Campaigns"])){
			     $return=array_merge($return,$res["result"]["Campaigns"]);
     	 	}
 	 	}
		return $return;
	 }
	 
	 /**
	  * get all BannerGroup's param by companyes ID
	  *
	  * @param array $arID   company IDs
   	  * @param bolean $getAdditionalParams   get Additional return params from Ya
	  * @return array     banners params
	  */
	 public function getCampaignBannerGroups($arID,$getAdditionalParams=false){
	     if(!is_array($arID)) $arID=array($arID);
	 
	     $arID=array_chunk($arID,EDIRECT_YALIMIT_GETGROUPS_COMP);
	     $return=array();
	     foreach ($arID as $IDs){
	         $params = array(
	             'SelectionCriteria'=>array(
	                 'CampaignIds' => $IDs
	             ),
	             'FieldNames'=>array('Id','Name','CampaignId','Status','ServingStatus','Type','RegionIds')
	         );
	         
	         if($getAdditionalParams){
	             //$params["FieldNames"]=array_merge($params["FieldNames"],array("RegionIds"));
	             1;
	         }
	         
	         $res=$this->requestV5("adgroups","get",$params);
	         if($res==0) return 0;
	         
	         if(is_array($res["result"]["AdGroups"])){
    	         $return=array_merge($return,$res["result"]["AdGroups"]);
	         }
	         unset($res);
	     }
	     return $return;
	 }	 
	 
	 /**
	  * get banner's param to Update Price by Banners ID
	  *
	  * @param array $arBannersIDs   Banners IDs
	  * @return array     banners params
	  */
	 public function getBannersParamsToUpdPrice($arBannersIDs){
	     if(!is_array($arBannersIDs)) $arBannersIDs=array($arBannersIDs);
	 
	     $arBannersIDs=array_chunk($arBannersIDs,EDIRECT_YALIMIT_GETBANNERS_GROUP);
	     $return=array();
	     foreach ($arBannersIDs as $IDs){
	         $params = array(
	             'SelectionCriteria'=>array(
	                 'Ids' => $IDs,
	                 'Types' => array("TEXT_AD")  //only TEXT Ads
	             ),
	             'FieldNames'=>array('Id','State'),
	             'TextAdFieldNames'=>array('Text','Title','Title2'),
	             'TextAdPriceExtensionFieldNames'=>array('Price')
	         );
	          
	         $res=$this->requestV5("ads","get",$params);
	         if($res==0) return 0;
	 
	         if(is_array($res["result"]["Ads"])){
	             $return=array_merge($return,$res["result"]["Ads"]);
	         }
	         unset($res);
	     }
	     
	     return $return;
	 }	 
	 
	 /**
	  * get all banner's param by BannerGroups ID
	  *
	  * @param array $arBannerGroupIDs   BannerGroups IDs
   	  * @param bolean $getAdditionalParams   get Additional return params from Ya
	  * @return array     banners params
	  */	 
	 public function getBanners($arBannerGroupIDs,$getAdditionalParams=false){
	 	if(!is_array($arBannerGroupIDs)) $arBannerGroupIDs=array($arBannerGroupIDs);
	 	 
	 	$arBannerGroupIDs=array_chunk($arBannerGroupIDs,EDIRECT_YALIMIT_GETBANNERS_GROUP);
	 	$return=array();
	 	foreach ($arBannerGroupIDs as $IDs){
	 	    $params = array(
	 	        'SelectionCriteria'=>array(
	 	            'AdGroupIds' => $IDs,
	 	            'Types' => array("TEXT_AD")  //only TEXT Ads
	 	        ),
	 	        'FieldNames'=>array('Id','CampaignId','AdGroupId','Status','State','Type'),
	 	        'TextAdFieldNames'=>array('Text','Title','Title2','Href','DisplayUrlPath','SitelinkSetId','AdImageHash'),
	 	        'TextAdPriceExtensionFieldNames'=>array('Price')
	 	    );
	 	    
	 	    if($getAdditionalParams){
	 	        $params["TextAdFieldNames"]=array_merge($params["TextAdFieldNames"],array("VCardId","AdExtensions","Mobile","VideoExtension","TurboPageId"));
	 	    }	 	    
	 	    
	 	    $res=$this->requestV5("ads","get",$params);
	 	    if($res==0) return 0;	 	    

	 	    if(is_array($res["result"]["Ads"])){
	 		    $return=array_merge($return,$res["result"]["Ads"]);
	 	    }
	 		unset($res);
	 	}
	 	//---rebuild AdExtensions---
	 	if($getAdditionalParams){
	 	    foreach ($return as &$val){
	 	        foreach ($val["TextAd"]["AdExtensions"] as $extension){
	 	            $val["TextAd"]["AdExtensionIds"][]=$extension["AdExtensionId"];	 	            
	 	        }
	 	        unset($val["TextAd"]["AdExtensions"]);
	 	    }
	 	}
	 	//----------------------------------
	 	return $return;
	 }	 
	 
	 /**
	  * get SiteLinks by SitelinkSetId
	  *
	  * @param int $SitelinkSetId   SitelinkSetId
	  * @return array     sitelinks params
	  */
	 public function getSitelinks($SitelinkSetId){
	     //get from cahce
	     if(isset($this->arCache["sitelinks"][$SitelinkSetId])) return $this->arCache["sitelinks"][$SitelinkSetId];
	     
	     $return=array();
         $params = array(
             'SelectionCriteria'=>array(
                 'Ids' => array($SitelinkSetId)
             ),
             'FieldNames'=>array('Id','Sitelinks')
         );
         
         $res=$this->requestV5("sitelinks","get",$params);
         if($res==0) return 0;
 
         //write Cache because many sitelinks the same 
         $this->arCache["sitelinks"][$SitelinkSetId]=$res["result"]["SitelinksSets"][0]['Sitelinks'];
         
         $return=$res["result"]["SitelinksSets"][0]['Sitelinks'];
	         
	     return $return;
	 }	 
	 
	 /**
	  * get Image by ImageHash
	  *
	  * @param string $AdImageHash   ImageHash
	  * @return array     image params
	  */
	 public function getImage($AdImageHash){
	    //get from cahce
	    if(isset($this->arCache["images"][$AdImageHash])) return $this->arCache["images"][$AdImageHash];
	     
  	 	$params = array(
  	 	    'Action' => "Get",
  	 	    'SelectionCriteria' => array('AdImageHashes'=>array($AdImageHash))
  	 	);
  	 	$res=$this->zapros('AdImage',$params);
  	 	if($res==0) return 0;
 	  	 	
  	 	//write Cache because many images the same
  	 	$this->arCache["images"][$AdImageHash]=$res["AdImages"][0];  	 	
  	 	
  	 	return $res["AdImages"][0];
	 }	 
	 
	 /**
	  * get VCard by VCardId
	  *
	  * @param int $VCardId   VCardId
	  * @return array     VCard params
	  */
	 public function getVCard($VCardId){
	     $return=array();
         $params = array(
             'SelectionCriteria'=>array(
                 'Ids' => array($VCardId)
             ),
             'FieldNames'=>array('Id','Phone')
         );
         
         $res=$this->requestV5("vcards","get",$params);
         if($res==0) return 0;
 
         $return=$res["result"]["VCards"][0];
	         
	     return $return;
	 }	 
	 
	 /**
	  * get BidModifiers of Company by CompanyID
	  *
	  * @param int $CompanyID   CompanyID
	  * @return array     Extensions params
	  */
	 public function getCompanyBidModifiers($CompanyID){
	     $return=array();
	     $params = array(
	         'SelectionCriteria'=>array(
	             'CampaignIds' => array($CompanyID),
	             'Levels'=>array("CAMPAIGN")
	         ),
	         'FieldNames'=>array('Id','Type'),
	         'MobileAdjustmentFieldNames'=>array('BidModifier','OperatingSystemType'),
	         'DesktopAdjustmentFieldNames'=>array('BidModifier'),
	         'DemographicsAdjustmentFieldNames'=>array('BidModifier','Gender','Age'),
	         'RetargetingAdjustmentFieldNames'=>array('BidModifier','RetargetingConditionId'),
	         'RegionalAdjustmentFieldNames'=>array('BidModifier','RegionId'),
	         'VideoAdjustmentFieldNames'=>array('BidModifier')
	     );
	      
	     $res=$this->requestV5("bidmodifiers","get",$params);
	     if($res==0) return 0;
	 
	     $return=$res["result"]["BidModifiers"];
	 
	     return $return;
	 }
	 
	 /**
	  * get Extensions by ExtensionsId
	  *
	  * @param int $ExtensionsIds   ExtensionsIds
	  * @return array     Extensions params
	  */
	 public function getExtensions($ExtensionsIds){
	     if(!is_array($ExtensionsIds)) $ExtensionsIds=array($ExtensionsIds);
	 
	     $return=array();
	     $params = array(
	         'SelectionCriteria'=>array(
	             'Ids' => $ExtensionsIds
	         ),
	         'FieldNames'=>array('Id','Type'),
	         'CalloutFieldNames'=>array('CalloutText')
	     );
	      
	     $res=$this->requestV5("adextensions","get",$params);
	     if($res==0) return 0;
	 
	     $return=$res["result"]["AdExtensions"];
	 
	     return $return;
	 }	 
	 
	 /**
	  * get all phrases param by BannerGroups ID
	  *
	  * @param array $arBannerGroupIDs   BannerGroups IDs
	  * @return array     phrases params
	  */	 
	 public function getPhrases($arBannerGroupIDs){
	 	if(!is_array($arBannerGroupIDs)) $arBannerGroupIDs=array($arBannerGroupIDs);
	 	 
	 	$arBannerGroupIDs=array_chunk($arBannerGroupIDs,(EDIRECT_YALIMIT_GETBANNERS_GROUP/20));  //max return 10 000 phrases
	 	$return=array();
	 	foreach ($arBannerGroupIDs as $IDs){
	 	    $params = array(
	 	        'SelectionCriteria'=>array(
	 	            'AdGroupIds' => $IDs
	 	        ),
	 	        'FieldNames'=>array('Id','CampaignId','AdGroupId','Keyword','StatisticsSearch','StatisticsNetwork','Bid','ContextBid','Status','State')
	 	    );
	 	    
	 	    $res=$this->requestV5("keywords","get",$params);
	 	    if($res==0) return 0;

	 	    if(is_array($res["result"]["Keywords"])){
	 		    $return=array_merge($return,$res["result"]["Keywords"]);
	 	    }
	 		unset($res);
	 	}
	 	return $return;	 
	 }	 
	 
	 /**
	  * get phrases BIDs by Phrases IDs
	  *
	  * @param array $arID   Phrases IDs
	  * @return array     phrases Bids params
	  */
	 public function getPhrasesBidsOld($arID){
	     if(!is_array($arID)) $arID=array($arID);
	 
	     $arID=array_chunk($arID,EDIRECT_YALIMIT_PHRASES_BIDS);
	     $return=array();
	     foreach ($arID as $IDs){
	         $params = array(
	             'SelectionCriteria'=>array(
	                 'KeywordIds' => $IDs
	             ),
	             'FieldNames'=>array('KeywordId','CampaignId','AdGroupId','Bid','ContextBid','ContextCoverage','CurrentSearchPrice','AuctionBids','SearchPrices','CompetitorsBids')
	         );
	          
	         $res=$this->requestV5("bids","get",$params);
	         if($res==0) return 0;
	 
	         if(is_array($res["result"]["Bids"])){
    	         $return=array_merge($return,$res["result"]["Bids"]);
	         }
	     }
	     return $return;
	 }	 
	 
	 
	 /**
	  * get phrases BIDs by Phrases IDs
	  *
	  * @param array $arID   Phrases IDs
	  * @param array $isRSYA   is phrases show in Network
	  * @return array     phrases Bids params
	  */
	 public function getPhrasesBids($arID,$isRSYA=false){
	     if(!is_array($arID)) $arID=array($arID);
	 
	     $arID=array_chunk( $arID, (EDIRECT_YALIMIT_PHRASES_BIDS/2) );
	     $return=array();
	     foreach ($arID as $IDs){
	         $params = array(
	             'SelectionCriteria'=>array(
	                 'KeywordIds' => $IDs
	             ),
	             'FieldNames'=>array('KeywordId','CampaignId','AdGroupId'), //'ServingStatus'
	         );
	         
	         //get Network or Search Prices
	         if($isRSYA) $params["NetworkFieldNames"]=array('Bid','Coverage');
	         else $params["SearchFieldNames"]=array('Bid','AuctionBids');
	          
	         $res=$this->requestV5("keywordbids","get",$params); //very long responce to Ya ~ 11 sec to 1000 phrases
	         if($res==0) return 0;

	         if(is_array($res["result"]["KeywordBids"])){
    	         $return=array_merge(
    	             $return,
    	             $this->convertBids($res["result"]["KeywordBids"])  //convert to old form, from traffic to position
    	             );
	         }
	         unset($res);
	     }
	     
	     return $return;
	 }	 
	 
	 //===SET DATA============
	 /**
	  * update prices
	  *
	  * @param array $arPhrasesPrices
	  * @return int 0/1
	  */	 
	 public function setUpdatePrice($arPhrasesPrices){
	 	$arPhrasesPrices=array_chunk($arPhrasesPrices,EDIRECT_YALIMIT_UPDATE_PHRASES);
	 	foreach ($arPhrasesPrices as $Prices){
	         $params = array(
	             'KeywordBids'=>$Prices
	         );
	         $res=$this->requestV5("keywordbids","set",$params);
	         if($res==0) return 0;
	 	}
	 	return 1;
	 }	 
	 
	 /**
	  * Stop company
	  *
	  * @param array $arID    Company IDs
	  * @return int 0/1
	  */
	 public function setStopCompany($arID){
	     if(!is_array($arID)) $arID=array($arID);
	     
	     $params = array(
	         'SelectionCriteria'=>array(
	             'Ids' => $arID
	         )
	     );
	      
	     $res=$this->requestV5("campaigns","suspend",$params);
	     if($res==0) return 0;
	     
	     $cntErr=$this->processingAdditionalErrors($res["result"]["SuspendResults"][0]);	     
	     if($res["result"]["SuspendResults"][0]["Id"]>0) return 1;
	     else return 0;
	 }
	 
	 /**
	  * Resume Campany
	  *
	  * @param int $compid    Company ID
	  * @return int 0/1
	  */
	 public function setResumeCompany($arID){
	     if(!is_array($arID)) $arID=array($arID);
	     
	     $params = array(
	         'SelectionCriteria'=>array(
	             'Ids' => $arID
	         )
	     );
	      
	     $res=$this->requestV5("campaigns","resume",$params);
	     if($res==0) return 0;
	     
	     $cntErr=$this->processingAdditionalErrors($res["result"]["ResumeResults"][0]);	     
	     if($res["result"]["ResumeResults"][0]["Id"]>0) return 1;
	     else return 0;
	 }
	 
	 /**
	  * Unarchive Campany
	  *
	  * @param int $compid    Company ID
	  * @return int 0/1
	  */
	 public function setUnarchiveCompany($arID){
	     if(!is_array($arID)) $arID=array($arID);
	 
	     $params = array(
	         'SelectionCriteria'=>array(
	             'Ids' => $arID
	         )
	     );
	      
	     $res=$this->requestV5("campaigns","unarchive",$params);
	     if($res==0) return 0;
	 
	     $cntErr=$this->processingAdditionalErrors($res["result"]["UnarchiveResults"][0]);
	     if($res["result"]["UnarchiveResults"][0]["Id"]>0) return 1;
	     else return 0;
	 }	 
	 
	 /**
	  * send banners to Moderate
	  *
	  * @param array $arIDs    banner IDs
	  * @return int 0/1
	  */
	 public function setModerateBanners($arIDs){
	     if(!is_array($arIDs)) $arIDs=array($arIDs);
	 
	     $cntErr=0;
	     $params = array(
	         'SelectionCriteria'=>array(
	             'Ids' => $arIDs
	         )
	     );
	      
	     $res=$this->requestV5("ads","moderate",$params);
	     if($res==0) return 0;
	 
	     foreach ($res["result"]["AddResults"] as $result){
	         $cntErr+=$this->processingAdditionalErrors($result);
	     }
	 
	     if($cntErr>0) return 0;
	     else return 1;
	 }	 
	 
	 /**
	  * Suspend/Resume/Archive banners , Set banners state
	  *
	  * @param array $arIDs    banner IDs
	  * @param string $state   banners new State Suspend/Resume/Archive
	  * @return int 0/1
	  */
	 public function setBannersState($arIDs,$state){
	     if(!in_array($state, array("suspend","resume","archive"))) return 0;
	     
	     if(!is_array($arIDs)) $arIDs=array($arIDs);
	 
	     $cntErr=0;
	     $params = array(
	         'SelectionCriteria'=>array(
	             'Ids' => $arIDs
	         )
	     );
	      
	     $res=$this->requestV5("ads",$state,$params);
	     if($res==0) return 0;
	     
	     foreach ($res["result"]["AddResults"] as $result){
	         $cntErr+=$this->processingAdditionalErrors($result);
	     }
	 
 	     if($cntErr>0) return 0;
 	     else return 1;
	 }
	
	 //======MODIFY PARAMS FUNCTIONS===============
	 /**
	  * UNION params arrays
	  *
	  * @param array $fromArray    get params from this Array
	  * @param array $toArray    put params to this Array
	  * @return array   union array
	  */
	 public function unionParamsArrays($fromArray,$toArray){
	     foreach ($fromArray as $key=>$val){
	         if(isset($toArray[$key])&&is_array($toArray[$key])) {
	             $toArray[$key]=$this->unionParamsArrays($val,$toArray[$key]);
	         }
	         else $toArray[$key]=$val;
	     }
	     
	     return $toArray;
	 }	 
	 
	 /**
	  * DELL All null params and empty arrays
	  *
	  * @param array $arParams    params Array
	  * @return array   filtered array
	  */
	 public function clearParamsArray($arParams){
	     foreach ($arParams as $key=>$val){
	         if(isset($arParams[$key])&&is_array($arParams[$key])) {
	             if(count($arParams[$key])==0) unset($arParams[$key]);
	             else $arParams[$key]=$this->clearParamsArray($arParams[$key]);
	         }
	         else if($arParams[$key]!==NULL) {
	             $arParams[$key]=$val;
	         }
	         else unset($arParams[$key]);
	     }
	 
	     return $arParams;
	 }	 
	 
	 /**
	  * Convert Bids: Traffic bids to position bids
	  * 
	  * @param array $resBids    res with Traffic bids fron Ya
	  * @return array   old form bids array
	  */
	 public function convertBids($resBids){
	     foreach ($resBids as &$val){
	         if(isset($val["Search"])) { //phrase on search
	             $val["Bid"]=$val["Search"]["Bid"];
	             $val["CurrentSearchPrice"]=0;
	             
	             foreach ($val["Search"]["AuctionBids"]["AuctionBidItems"] as $tBid){
	               if($tBid["TrafficVolume"]==100){ //P11 (1SR)
	                   $val["SearchPrices"][]=array("Position"=>"PREMIUMFIRST","Price"=>$tBid["Bid"]);
	                   $val["AuctionBids"][]=array("Position"=>"P11","Bid"=>$tBid["Bid"],"Price"=>$tBid["Price"]);
	               }  
	               else if($tBid["TrafficVolume"]==85){ //P12 (2SR)
	                   $val["AuctionBids"][]=array("Position"=>"P12","Bid"=>$tBid["Bid"],"Price"=>$tBid["Price"]);
	               }
	               else if($tBid["TrafficVolume"]==75){ //P13 (3SR)
	                   $val["AuctionBids"][]=array("Position"=>"P13","Bid"=>$tBid["Bid"],"Price"=>$tBid["Price"]);
	               }
	               else if($tBid["TrafficVolume"]==65){ //P14 (4SR enter in TOP)
	                   $val["SearchPrices"][]=array("Position"=>"PREMIUMBLOCK","Price"=>$tBid["Bid"]);
	                   $val["AuctionBids"][]=array("Position"=>"P14","Bid"=>$tBid["Bid"],"Price"=>$tBid["Price"]);
	               }
	               else if($tBid["TrafficVolume"]==15){ //P21 (1 Guaranty)
	                   $val["SearchPrices"][]=array("Position"=>"FOOTERFIRST","Price"=>$tBid["Bid"]);
	                   $val["AuctionBids"][]=array("Position"=>"P21","Bid"=>$tBid["Bid"],"Price"=>$tBid["Price"]);
	               }
	               else if($tBid["TrafficVolume"]==10){ //P22 (2-3 Guaranty)
	                   $val["AuctionBids"][]=array("Position"=>"P22","Bid"=>$tBid["Bid"],"Price"=>$tBid["Price"]);
	               }
	               else if($tBid["TrafficVolume"]==5){ //P23 (enter in Guaranty)
	                   $val["SearchPrices"][]=array("Position"=>"FOOTERBLOCK","Price"=>$tBid["Bid"]);
	                   $val["AuctionBids"][]=array("Position"=>"P23","Bid"=>$tBid["Bid"],"Price"=>$tBid["Price"]);
	               }
	               
	               //CurrentSearchPrice
	               if($val["Bid"]>=$tBid["Bid"]&&$val["CurrentSearchPrice"]==0) $val["CurrentSearchPrice"]=$tBid["Price"];
	             }
	             
	             unset($val["Search"]);
	         }
	         else if(isset($val["Network"])) { //Network phrase
	             $val["ContextBid"]=$val["Network"]["Bid"];
	             $val["ContextCoverage"]["Items"]=$val["Network"]["Coverage"]["CoverageItems"];
	             unset($val["Network"]);
	             //rename Bid to Price
	         	 foreach ($val["ContextCoverage"]["Items"] as $key1=>$val1){
	                 $val["ContextCoverage"]["Items"][$key1]["Price"]=$val["ContextCoverage"]["Items"][$key1]["Bid"];
	                 unset($val["ContextCoverage"]["Items"][$key1]["Bid"]);
	             }
	         }
	     }	     
	     
	     return $resBids;
	 }
	 
 	 //=======CREATE/UPDATE======
	 /**
	  * Create company by params
	  *
	  * @param array $arNewCompany    new company params
	  * @return int newCompanyID
	  */	 
 	 public function createNewCompany($arNewCompany){
	     $params = array(
	         'Campaigns'=>array(
	             $arNewCompany
	         )
	     );
	      
	     $res=$this->requestV5("campaigns","add",$params);
	     if($res==0) return 0;

	     $cntErr=$this->processingAdditionalErrors($res["result"]["AddResults"][0]);	     
	     if($res["result"]["AddResults"][0]["Id"]>0) return $res["result"]["AddResults"][0]["Id"];
	     else return 0;	     
 	 }
 	 
 	 /**
 	  * Update company params
 	  *
 	  * @param array $arCompanyParam    company params
 	  * @return int  - 0/1
 	  */
 	 public function updateCompany($arCompanyParam){
 	     $params = array(
 	         'Campaigns'=>array(
 	             $arCompanyParam
 	         )
 	     );
 	      
 	     $res=$this->requestV5("campaigns","update",$params);
 	     if($res==0) return 0;
 	 
 	     $cntErr=$this->processingAdditionalErrors($res["result"]["AddResults"][0]);
	     if($cntErr>0) return 0;
	     else return 1;
 	 }

 	 /**
 	  * Create sitelinks by params
 	  *
 	  * @param array $arSiteLinks    new sitelinks params
 	  * @return int SitelinkSetId
 	  */
 	 public function createNewSiteLinks($arSiteLinks){
 	     $params = array(
 	         'SitelinksSets'=>array(
 	             array(
 	                  "Sitelinks"=>$arSiteLinks
 	              )
 	         )
 	     );
 	      
 	     $res=$this->requestV5("sitelinks","add",$params);
 	     if($res==0) return 0;
 	 
 	     $cntErr=$this->processingAdditionalErrors($res["result"]["AddResults"][0]);
 	     if($res["result"]["AddResults"][0]["Id"]>0) return $res["result"]["AddResults"][0]["Id"];
 	     else return 0;
 	 } 	 
 	 
	 /**
	  * Create BannerGroups by params
	  *
	  * @param array $arNewBannerGroups    new BannerGroups params
	  * @return array NewBannerGroupIDs or empty array
	  */	 
 	 public function createNewBannerGroups($arNewBannerGroups){
 	     $arNewBannerGroups=array_chunk($arNewBannerGroups,EDIRECT_YALIMIT_GETBANNERS_GROUP);
 	     $cntErr=0;
 	     $arNewBannerGroupIDs=array();
 	     foreach ($arNewBannerGroups as $newGroups){
 	         $params = array(
 	             'AdGroups'=>$newGroups
 	         );
 	         $res=$this->requestV5("adgroups","add",$params);
 	         if($res==0) return 0;
 	          
 	         foreach ($res["result"]["AddResults"] as $result){
 	             if($result["Id"]>0) $arNewBannerGroupIDs[]=$result["Id"];
 	             $cntErr+=$this->processingAdditionalErrors($result);
 	         }
 	     }
 	      
 	     if($cntErr>0) return array();
 	     else return $arNewBannerGroupIDs;
 	 }	    	 
 	 
 	 /**
 	  * Create Banners by params
 	  *
 	  * @param array $arNewBanners    new Banners params
 	  * @return array NewBannerIDs or empty array
 	  */
 	 public function createNewBanners($arNewBanners){
 	     $arNewBanners=array_chunk($arNewBanners,EDIRECT_YALIMIT_BANNERS);
 	     $cntErr=0;
 	     $arNewBannerIDs=array();
 	     foreach ($arNewBanners as $newBanners){
 	         $params = array(
 	             'Ads'=>$newBanners
 	         );	         
 	         $res=$this->requestV5("ads","add",$params);
 	         if($res==0) return 0;
 	         
 	         foreach ($res["result"]["AddResults"] as $result){
 	             if($result["Id"]>0) $arNewBannerIDs[]=$result["Id"];
 	             $cntErr+=$this->processingAdditionalErrors($result);
 	         }
 	     }
 	     
 	     if($cntErr>0) return array();
 	     else return $arNewBannerIDs;
 	 }
 	 
 	 /**
 	  * Update Banners params
 	  *
 	  * @param array $arUpdateBannersInfo    update Banners params
 	  * @return int 0/1
 	  */
 	 public function updateBanners($arUpdateBannersInfo){
 	     $arUpdateBannersInfo=array_chunk($arUpdateBannersInfo,EDIRECT_YALIMIT_BANNERS);
 	     $cntErr=0;
 	     foreach ($arUpdateBannersInfo as $toUpdateBanners){
 	         $params = array(
 	             'Ads'=>$toUpdateBanners
 	         );
 	         $res=$this->requestV5("ads","update",$params);
 	         if($res==0) return 0;
 	          
 	         foreach ($res["result"]["AddResults"] as $result){
 	             $cntErr+=$this->processingAdditionalErrors($result);
 	         }
 	     }
 	      
 	     if($cntErr>0) return 0;
 	     else return 1;
 	 } 	 
	 
 	 /**
 	  * Create Phrases by params
 	  *
 	  * @param array $arNewPhrases    new Phrases params
 	  * @return int 0/1
 	  */
 	 public function createNewPhrases($arNewPhrases){
 	     $arNewPhrases=array_chunk($arNewPhrases,EDIRECT_YALIMIT_CREATE_PHRASES);
 	     $cntErr=0;
 	     foreach ($arNewPhrases as $newPhrases){
     	     $params = array(
     	         'Keywords'=>$newPhrases
     	     );
     	     $res=$this->requestV5("keywords","add",$params);
     	     if($res==0) return 0;
     	     
     	     foreach ($res["result"]["AddResults"] as $result){
     	         $cntErr+=$this->processingAdditionalErrors($result);
     	     }     	     
 	     }     

 	     if($cntErr>0) return 0;
 	     else return 1;
 	 } 	 
 	 
 	 /**
 	  * create Images in Yandex by array
 	  *
 	  *@param array $arImages   Images array ["url","name"]
 	  *@return array  images hashes array ["name"=>"hash"] or empty array
 	  */
 	 public function createImagesInYa($arImages){
 	     //compiled array to Yandex
 	     $arImagesNames=array();
 	     $arImagesToYandex=array();
 	     foreach ($arImages as $val){
 	         //skip empty urls
 	         if(strlen($val["url"])<6) continue;
 	         
 	         $resizeUrl=CEDirectCompany::resizeImageForYa($val);
 	         if(strlen($resizeUrl)>5){
     	         $picture="";
     	         if($fp = fopen($resizeUrl,"rb"))
     	         {
     	             $picture = fread($fp,filesize($resizeUrl));
     	             $arImagesToYandex[]=array(
     	                 "ImageData"=> base64_encode($picture),
     	                 "Name"=>$val["name"]
     	             );
     	             $arImagesNames[]=$val["name"];
     	             fclose($fp);
     	         }
 	         }
 	     }
 	     //send request - create images in yandex, return created hashes
 	     $arImagesToYandex=array_chunk($arImagesToYandex,10);
 	     $cntErr=0;
 	     $arImagesHashes=array();
 	     foreach ($arImagesToYandex as $newImages){
 	         $params = array(
 	             'AdImages'=>$newImages
 	         );
 	         $res=$this->requestV5("adimages","add",$params);
 	         if($res==0) return array();
 	 
 	         foreach ($res["result"]["AddResults"] as $result){
 	             $arImagesHashes[]=$result["AdImageHash"];
 	             $cntErr+=$this->processingAdditionalErrors($result);
 	         }
 	     }
 	     
 	     //combine return array
 	     $arReturn=array_combine($arImagesNames,$arImagesHashes);
 	 
 	     return $arReturn;
 	 } 	 
 	 
 	 /**
 	  * delete all not use Images in Yandex
 	  *
 	  *@return int  0/1 
 	  */
 	 public function deleteNotUseImages(){
 	     //select not use Images hashes
 	     $params = array(
 	         'SelectionCriteria'=>array(
 	             'Associated' => "NO"
 	         ),
 	         'FieldNames'=>array('AdImageHash')
 	     );
 	     $res=$this->requestV5("adimages","get",$params);
 	     if($res==0) return 0;
 	     
 	     //collect Hashes
 	     $arDeleteHashes=array();
 	     if(count($res["result"]["AdImages"])>0){
 	         foreach ($res["result"]["AdImages"] as $val){
 	             $arDeleteHashes[]=$val["AdImageHash"];
 	         }
 	     }
 	      
 	     //delete Images
 	     if(count($arDeleteHashes)>0){
 	         $params = array(
 	             'SelectionCriteria'=>array(
 	                 'AdImageHashes' => $arDeleteHashes
 	             )
 	         );
 	         $res=$this->requestV5("adimages","delete",$params); 	  
 	         if($res==0) return 0;
 	     }
 	     
 	     return 1;
 	 } 	  	
 	 
 	 /**
 	  * Clone VCard
 	  *
 	  *@param int $toCID   clone to this company ID
 	  * @param int $VCardId     what card clone ID
 	  * @return int    NEW VCardID
 	  */
 	 public function cloneVCard($toCID,$VCardId){
 	     //----get VCard params-----
 	     $arVCardParams=array(); 	      
 	     $params = array(
 	         'SelectionCriteria'=>array(
 	             'Ids' => array($VCardId)
 	         ),
 	         'FieldNames'=>array("Country","City","Street","House","Building","Apartment","CompanyName","ExtraMessage","ContactPerson","ContactEmail","MetroStationId","CampaignId","Ogrn","WorkTime","InstantMessenger","Phone","PointOnMap")
 	     );
 	     $res=$this->requestV5("vcards","get",$params);
 	     if($res==0) return 0;
 	     $arVCardParams=$res["result"]["VCards"][0];
 	     $arVCardParams=$this->clearParamsArray($arVCardParams);
 	     //--------------------------------
 	     
 	     //------add new VCard-------
 	     if(count($arVCardParams)){
 	         $arVCardParams["CampaignId"]=$toCID;
     	     $params = array(
     	         'VCards'=>array($arVCardParams)
     	     );
     	     $res=$this->requestV5("vcards","add",$params);
     	     if($res==0) return 0;
     	     
     	     $cntErr=$this->processingAdditionalErrors($res["result"]["AddResults"][0]);
     	     if($res["result"]["AddResults"][0]["Id"]>0) return $res["result"]["AddResults"][0]["Id"];
     	     else return 0;
 	     }
 	     else return 0;
 	     //--------------------------------
 	 } 	 
 	 
 	 /**
 	  * copy Company BidModifiers
 	  *
 	  *@param int $fromCID   copy from this company ID
 	  *@param int $toCID   copy to this company ID
 	  *@return int 0/1
 	  */
 	 public function copyCompanyBidModifiers($fromCID,$toCID){
 	     if($fromCID>0&&$toCID>0){
     	     //----get BidModifiers params and preapare to SEND data-----
     	     $arBidModifiersParams=$this->getCompanyBidModifiers($fromCID);
     	     if(is_array($arBidModifiersParams)){
     	         if(count($arBidModifiersParams)>0){
                        $arBidModifiers=array(
                            "MobileAdjustment"=>array(),
                            "DesktopAdjustment"=>array(),
                            "DemographicsAdjustments"=>array(),
                            "RetargetingAdjustments"=>array(),
                            "RegionalAdjustments"=>array(),
                            "VideoAdjustment"=>array()
                        );
                        $arBidModifiersTypes=array_keys($arBidModifiers);
                        foreach ($arBidModifiersParams as $BidModifier){
                            foreach ($arBidModifiersTypes as $type){
                                if(preg_match("/s$/",$type)){
                                    $type=preg_replace("/s$/", "", $type);
                                    if(isset($BidModifier[$type])) {$arBidModifiers[$type."s"][]=$BidModifier[$type]; break;}
                                }
                                else{
                                    if(isset($BidModifier[$type])) {$arBidModifiers[$type]=$BidModifier[$type]; break;}
                                }
                            }
                        }
                        $arBidModifiers=$this->clearParamsArray($arBidModifiers);
                        
                        $arBidModifiersToYa=array();
                        foreach ($arBidModifiers as $key=>$BidModifier){
                            $arBidModifiersToYa[]=array(
                                "CampaignId"=>$toCID,
                                $key=>$BidModifier
                                );
                        }
     	         }
     	         else return 1; //not BidModifiers in company
     	     }
     	     else return 0;
     	     //--------------------------------
     	      
     	     //------send new BidModifiers to Ya-------
     	     if(isset($arBidModifiersToYa)&&count($arBidModifiersToYa)){
     	         $params = array(
     	             'BidModifiers'=>$arBidModifiersToYa
     	         );
     	         $res=$this->requestV5("bidmodifiers","add",$params);
     	         if($res==0) return 0;
     	         	
     	         $cntErr=$this->processingAdditionalErrors($res["result"]["AddResults"][0]);
     	         if($cntErr>0) return 0;
     	         else return 1;
     	     }
     	     else return 0;
     	     //--------------------------------
 	     }
 	     else return 0;
 	 } 	 
 	 
 	 //=======STATISTICS======
 	 /**
 	  * get company statistics from Yandex by date interval
 	  *
 	  * @param array $arIDs    Company IDs
 	  * @param date $dateFrom   Start date in format YYYY-MM-DD
 	  * @param date $dateTo    End date in format YYYY-MM-DD
 	  * @return array   statistics
 	  */ 	 
 	 public function getCompanyStat($arIDs,$dateFrom,$dateTo){
 	     $params=array(
 	         "SelectionCriteria"=>array(
 	             "DateFrom" => $dateFrom,
 	             "DateTo" => $dateTo,              
 	             "Filter"=>array(
 	                 array(
 	                 "Field"=>"CampaignId",
 	                 "Operator"=> "IN",
 	                 "Values"=> $arIDs
 	                 ), 	                 
 	             ),
 	         ),
 	         "FieldNames"=>array("Date", "CampaignId", "Clicks", "Cost"),
 	         "OrderBy"=>array(
 	             array("Field"=>"Date"),
 	             ),
 	         "ReportName"=> "Cost_of_Company_".$arIDs[0]."_".$dateFrom."_".$dateTo,
 	         "ReportType"=>"CAMPAIGN_PERFORMANCE_REPORT",
 	         "DateRangeType"=> "CUSTOM_DATE",
 	         "Format"=> "TSV",
 	         "IncludeVAT"=> "YES",
 	         "IncludeDiscount"=> "NO" 	    
 	     );
 	      	     
 	     $res=$this->requestV5("reports","get",$params);
 	     
 	     if($res["HTTPCode"]==200) return $this->tsvToArray($res["result"]); //return report
 	     else if($res["HTTPCode"]==201||$res["HTTPCode"]==202) return $res["retryIn"]; //wait for report
 	     else { //error
 	         $this->writelog(GetMessage('EDIRECT_YAEXCH_LOG_GET_REPORT_ERROR')." Cost_of_Company_".$arIDs[0]."_".$dateFrom."_".$dateTo);
 	         return "";
 	     }
 	 }
	 
 	 //======ADDITIONAL/Dictionary=======
 	 /**
 	  * get Dictionary
 	  *
 	  * @param array   $DictionaryName 
 	  * @return array   arrayDataOfDict
 	  */
 	 public function getDictionary($DictionaryName){
 	     $params=array(
 	         'DictionaryNames'=>array($DictionaryName)
 	     );
 	      
 	     $res=$this->requestV5("dictionaries","get",$params);
 	     if($res==0) return 0;
 	     return $res["result"][$DictionaryName];
 	 } 	 
 	 
 	 /**
 	  * DEPRECATED but have not replace
 	  * get COUNT balls in API4
 	  *
 	  * @return int   CNT balls
 	  */ 	 
 	 public function getballs(){
 	 	$res=$this->zapros('GetClientsUnits',array($this->yalogin));
 	 	if($res==0) return 0;
 	 	return $res[0]['UnitsRest'];
 	 }
 	 
 	 /**
 	  * get COUNT balls API V5
 	  *
 	  * @return array   CNT balls array(nowuse,rest,full)
 	  */
 	 public function getballsV5(){
 	    $params=array(
 	         'DictionaryNames'=>array("Currencies")
 	    );
 	    $res=$this->requestV5("dictionaries","get",$params);
  	 	
  	 	if($res==0) return 0;
  	 	return $res['Units'];
 	 } 	 
 	 
 	 /**
 	  * get user info
 	  *
 	  * @return array   client info
 	  */ 	 
 	 public function getUserInfo(){
 	     $params=array(
 	         'FieldNames'=>array("ClientId","CountryId","Currency","Login","Type","VatRate" )
 	     );
 	      	     
 	    $res=$this->requestV5("clients","get",$params);
 	    if($res["result"]["Clients"][0]["ClientId"]>0) return $res["result"]["Clients"][0];
 	    else return 0;
 	 }	     	 
 	 
 	 /**
 	  * PING API
 	  *
 	  * @return int  0/1
 	  */ 
 	 public function ping(){
 	 	$res=$this->zapros('PingAPI',array());

 	 	return $res;
 	 } 	 
 	 
 	 //============PODBOR==============
 	 /**
 	  * get phrases Suggestions
 	  *
 	  * @param array $arPhrases
 	  * @return array   Suggestions
 	  */
 	 public function getSuggestion($arPhrases){
		$params = array(
        	"Keywords" => $arPhrases
		);
		$res=$this->zapros('GetKeywordsSuggestion',$params);
		
		return $res;
 	 }	   

 	 /**
 	  * create Wsreport for phrases
 	  *
 	  * @param array $arPhrases
 	  * @return int   Wsreport  ID
 	  */
 	 public function createWsreport($arPhrases){
		$params = array(
        	"Phrases" => $arPhrases,
		    "GeoID" => array(EDIRECT_PODBOR_PHRASE_REGION)
		);
		$res=$this->zapros('CreateNewWordstatReport',$params);
		return $res;
 	 }	   
 	 
 	 /**
 	  * get Wsreport list
 	  *
 	  * @return array   Wsreports
 	  */ 	 
 	 public function getWsreportList(){
		$params = array();
		$res=$this->zapros('GetWordstatReportList',$params);
		return $res;
 	 }	   
 	 
 	 /**
 	  * get Wsreport by ID
 	  *
 	  * @param array $id   Wsreport ID
 	  * @return array   Wsreport  params
 	  */ 	 
 	 public function getWsreport($id){
		$params = $id;
		$res=$this->zapros('GetWordstatReport',$params);
		return $res;
 	 }	    	 

 	 /**
 	  * delete Wsreport by ID
 	  *
 	  * @param array $id   Wsreport ID
 	  * @return int 0/1
 	  */ 	
 	 public function delWsreport($id){
		$params = $id;
		$res=$this->zapros('DeleteWordstatReport',$params);
		return $res;
 	 }	    	  	 
 }
?>