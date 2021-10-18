<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

/**
 * Class CEDirectYaXml
 * Work with Yandex XML for Check Phrases SEO Position
 * @category module wtc.easydirect Phrases
 */
class CEDirectYaXml {

 	private $key="";
 	private $host="";
 	private $region=0;
 	private $ip="";
 	private $url;
 	
 	private $mesto=0;
 	private $tekmesto=0;
 	
 	private $parser;
 	
 	private $arRegions=array();
 
 	/**
 	 * SET main params
 	 */
	public function __construct() {
	        global $obYaExchange;
	        
	  		if( defined('EDIRECT_YA_XML_IP') && strlen(EDIRECT_YA_XML_IP)>3 ) $this->ip=EDIRECT_YA_XML_IP;
	  		$this->url=EDIRECT_YA_XML_URL;

            if(strlen(EDIRECT_TOKEN)>3) {
                $res = $obYaExchange->getDictionary("GeoRegions");
                if (is_array($res)) {
                    foreach ($res as $val) {
                        $this->arRegions[$val["GeoRegionId"]] = $val;
                    }
                    ksort($this->arRegions);
                }
            }
	}

 	private function writelog($log) {	  		
 	  		$log="YandexXmlError ".$log;
	      	CEDirectLog::Add(array("MESSAGE"=>$log,"TYPE"=>"E"));
	}
	
	//=====WORK WITH YANDEX REGIONS==========
	/**
	 * get Yandex CITIES Regions
	 *
	 * @return array   arrayYaRegions [ID]=>Name
	 */
	public function getYaCityRegions() {
	    $arYaRegions=array();
	    foreach ($this->arRegions as $val){
	        if( $val["GeoRegionType"]=="City" || $val["GeoRegionType"]=="Village" ){
	           $arYaRegions[$val["GeoRegionId"]]=$val["GeoRegionName"];
	        }
	    }
	    asort($arYaRegions);
	    return $arYaRegions;
	}
	
	/**
	 * get Yandex Region Type
	 *
	 * @param int $regionID
	 * @return string Type
	 */
	public function getMainRegion($regionID) {
	    if($regionID>0){
	        foreach ($this->arRegions as $val){
	            if($regionID==$val["GeoRegionId"]){
	                if( $val["GeoRegionType"]=="City" || $val["GeoRegionType"]=="Village" ) return $val["GeoRegionId"];
	                else {
	                    $cityReg=$this->getCityByRegion($val["GeoRegionId"]);
	                    if($cityReg>0) return $cityReg;
	                    else return $val["GeoRegionId"];
	                }
	            }
	        }
	    }
	    else return 0;
	}	
	
	/**
	 * get City by Yandex Region
	 *
	 * @param int $regionID
	 * @return int   id city region
	 */
	public function getCityByRegion($regionID) {
	    if($regionID>0){
    	    foreach ($this->arRegions as $val){
    	        if($regionID==$val["ParentId"]){
    	            if( $val["GeoRegionType"]=="City" || $val["GeoRegionType"]=="Village" ) return $val["GeoRegionId"];
    	        }
    	    }
    	    return 0;
	    }
	    else return 0;
	}
	//=================================
	
	/**
	 * check Phrase Position
	 *
	 * @param string $key       search key
	 * @param string $host     search host
	 * @param string $regoin     search regoin
	 */
	public function checkPosition($key,$host,$regoin=0) {
	       $this->newPhrase($key,$host,$regoin);
	       if($this->getRequest()) return $this->getPosition();
	       else return -1;
	}
	
	/**
	 * send request
	 */
	private function getRequest() {
			if(strlen($this->key)<1||strlen($this->url)<10)  {
				$this->writelog(GetMessage('EDIRECT_YAXML_LOG_MESS_1'));
				$this->mesto=0; 
				return 0;
			}
			
			$xml_code = '<?xml version="1.0" encoding="UTF-8"?>' .
					'<request>' .
						'<query>'.$this->key.'</query>' .
			    		'<groupings>' .
			        		'<groupby attr="d" mode="deep" groups-on-page="50" docs-in-group="1"/>' .
			    		'</groupings>' .
					'</request>';
			
			$addParamToUrl="";
			//set Region
			if( $this->region>0 || EDIRECT_YA_XML_REGION>0 ) {
			    if(strpos($this->url, "lr=") === false){
			        if(EDIRECT_YA_XML_REGION>0) $addParamToUrl="&lr=".EDIRECT_YA_XML_REGION;
			        else $addParamToUrl="&lr=".$this->region;
			    }			    
			}
			
		    $ch = curl_init($this->url.$addParamToUrl);
		    curl_setopt($ch, CURLOPT_HEADER,0);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	        if($this->ip) curl_setopt($ch, CURLOPT_INTERFACE, $this->ip);
		    curl_setopt($ch, CURLOPT_POST,1);
	      	curl_setopt($ch, CURLOPT_POSTFIELDS,$xml_code);
		    $html = curl_exec($ch);
		    curl_close($ch);
		        
		    if(strlen($html)>100){
		    	 if(preg_match('/<error code="([0-9]{1,2})">(.*)<\/error>/', $html, $regs)) { //check for error
		    	 	if($regs[1]>0) {
		    	 		if(!EDIRECT_UTFSITE) $regs[2]=iconv("utf-8", "windows-1251", $regs[2]); //if site isn't in UTF8, Convert string
		    	 		if($regs[1]==32) CEDirectYaExchangeStat::AddCount(array("NAME"=>"xmlsearch","CALL_CNT"=>EDIRECT_YA_XML_DAYLIMIT+10000)); //max limit is reach
		    	 		$this->writelog(GetMessage('EDIRECT_YAXML_LOG_MESS_4',array("#KOD#"=>$regs[1],"#MESS#"=>$regs[2])));
		    	 	}
		    	 	else $this->writelog(GetMessage('EDIRECT_YAXML_LOG_MESS_3'));
		    	 	return 0;
		    	 }
		    	else {
			      	$this->parser = xml_parser_create();
		    	  	xml_set_object($this->parser, $this);
		      		xml_set_element_handler($this->parser, "tag_open", "tag_close");
		      		xml_set_character_data_handler($this->parser, "cdata"); 
		      		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
		
		      		xml_parse($this->parser,$html);
					xml_parser_free($this->parser);	
					
					CEDirectYaExchangeStat::AddCount(array("NAME"=>"xmlsearch"));
					return 1;
		    	}
		    }
		    else {
		    	$this->writelog(GetMessage('EDIRECT_YAXML_LOG_MESS_2'));
		    	return 0;
		    }
	}
	
	private function tag_open($parser, $tag, $attributes) {
				if($tag=="categ"&&$this->mesto==0) {
					 $this->tekmesto++;
					 if( strpos($attributes['name'],$this->host) !== false ) $this->mesto=$this->tekmesto;
				}
	      }
	      
	private function cdata($parser, $cdata) {
	   }
	   
	private function tag_close($parser, $tag) {
	   } 	

   /**
    * Return position
    */	
 	private function getPosition() {
		return $this->mesto;
	}
	
	/**
	 * change params to new request
	 *
	 * @param string $key       search key
	 * @param string $host      search host
	 * @param string $regoin      search regoin
	 */
 	private function newPhrase($key,$host,$regoin) {
 	    //parce key
 	    $arKey=explode("-",$key);
 	    $key=trim($arKey[0]);
 	    $this->key=preg_replace('[\"|\+|\!|\[|\]]','',$key);
 	    if(!EDIRECT_UTFSITE) $this->key=iconv("windows-1251", "utf-8", $this->key); //if site isn't in UTF8, Convert string
 	    
 	    //parce host
 	    //TODO: camelcase in domen and russian letters domen
 	    $host=preg_replace("/(http:\/\/|https:\/\/|www\.)/",'',htmlspecialcharsbx($host));
 	    $arHost=explode("/",$host);
 	    $this->host=$arHost[0];
 	    
 	    //region
 	    if($regoin>0) $this->region=$regoin;
 	    
 	    //set default values
	  	$this->tekmesto=0;
	  	$this->mesto=0;
	  	
 		return 1;
	}	
  
}
?>