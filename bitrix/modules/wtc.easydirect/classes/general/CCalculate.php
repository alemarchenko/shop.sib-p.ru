<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

/**
 * Class CEDirectCalculate
 * calculate prices for pharases
 * @category module wtc.easydirect calculate
 */
class CEDirectCalculate
{
    private $arUserMethods=array();
    
    /**
     * init user methods
     */
    public function __construct() {
        //find user method and functions and save it as anonym functions
        foreach (array_merge(CEDirectMetod::getListUserMethods("user"),CEDirectMetod::getListUserMethods("function")) as $fname){
            $method_file=$_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/wtc.easydirect/user_methods/".$fname.".php";
            if(file_exists($method_file)){
                try{
                    require($method_file);  //require_once have a problems with Anonym function
                    if(isset($$fname)){
                        $this->arUserMethods[$fname]=array(
                            "TYPE"=>"Anonym",
                            "FUNCTION"=>$$fname
                        );
                    }
                }
                catch ( ParseError $e ){
                    CEDirectLog::Add(array("MESSAGE"=>GetMessage('EDIRECT_CALCULATE_USER_METHOD_ERR',array("#NAME#"=>$fname,"#FILE#"=>$e->getFile(),"#MESS#"=>$e->getMessage(),"#STRINGNUM#"=>$e->getLine())),"TYPE"=>"E"));
                }
            }            
        }
         
        //find old user method made in Bitrix Handler System
        $rsEvents = GetModuleEvents("wtc.easydirect", "userCalculateMethod");
        while ($arEvent = $rsEvents->Fetch())
        {
            $this->arUserMethods[$arEvent["CALLBACK"][1]]=array(
                "TYPE"=>"Handler",
                "FUNCTION"=>$arEvent
            );
        }
    }
    
	//---------------------------Exec Service Functions---------------
    /**
     * calculate different one price from other price in percent
     *
     * @param int $st1  price one |  $st1>=$st2
     * @param int $st2  price two
     * @return float  different in percent
     */
	public static function getDiffInPerc($st1,$st2)
	{
		if($st1>=$st2) return round( (($st1-$st2)*100)/$st1 );
		else return 0;
	}
	
	/**
	 * convert prices array to another form
	 *
	 * @param array $prices  array from yandex
	 * @return array  new form array
	 */	
	public static function convertPricesAr($prices){
	    $newAr=array();
	    foreach ($prices as $val){
	        $newAr[$val["Position"]]=$val;
	    }
	    return $newAr;
	}	
	
	/**
	 * convert Coverage array to another form
	 *
	 * @param array $prices  array from yandex
	 * @return array  new form array
	 */
	public static function convertCoverageAr($prices){
	    $newAr=array();
	    foreach ($prices as $val){
	        $newAr[$val["Probability"]]=$val;
	    }
	    return $newAr;
	}	
	
	/**
	 * exec system or user method
	 *
	 * @param string $fname    method fuction name need to exec
	 * @param array $params    phrase params
	 * @return duoble  new bid
	 */	
	public function method_exec($fname,$params)
	{	
		if(preg_match("/^sys_/",$fname)){ //exec sys method
		    if(method_exists("CEDirectCalculate",$fname)){
    			return call_user_func_array(
    				array('CEDirectCalculate',$fname),
    				array($params)
    			);
		    }
		    else return 0;
		}
		else { //exec external method
		     //find is user method init
		     if(isset($this->arUserMethods[$fname])){
		         if($this->arUserMethods[$fname]["TYPE"]=="Anonym"){
		             return $this->arUserMethods[$fname]["FUNCTION"]($params);
		         }
		         else if($this->arUserMethods[$fname]["TYPE"]=="Handler"){
		             return ExecuteModuleEventEx($this->arUserMethods[$fname]["FUNCTION"], array($params));
		         }
		     }
		     
		    //not find method
		    return 0;
		}
	}
	
	/**
	 * exec user function
	 *
	 * @param string $fname    fuction name need to exec
	 * @param array $params    array params
	 * @return mixed
	 */
	public function user_function_exec($fname,$params)
	{
	        if(isset($this->arUserMethods[$fname])){
	            if($this->arUserMethods[$fname]["TYPE"]=="Anonym"){
	                return $this->arUserMethods[$fname]["FUNCTION"]($params);
	            }
	        }
	        //not find function
	        return FALSE;
	}	
	//---------------------------------------------------------------------------
	//============FUNCTIONS of METHODS=================
    /*
		$params=array(
		    'ID_COMPANY' - company ID
			'NAME' - phrase
			'MAX_PRICE'  - max price for the phrase
			'MESTO_SEO' - place in SEO, 0 - is not in TOP50.
			'PRICE_ON_SEARCH' - Real pay price after autobroker
			'SHOWS' - phrase shows
			'CLICKS' - phrase clicks
			'CTR' - CTR
			'PRICE' - user price in the phrase
			'PREMIUMMAX' - 1'st place of premium Bid! Not price
			'PREMIUMMIN' - bottom place of premium Bid! Not price
			'MAXBET' - 1'st place of guaranty Bid! Not price
			'MINBET' - bottom place of guaranty Bid! Not price
			'PRICES' - array prices for all places
                        [
                          { 
                             "Position": (string),
                             "Bid": (float), 
                             "Price": (float)
                          }
                          ...
                       ]		
            //---RSYA params---
	        'CONTEXTSHOWS' - phrase shows in RSYA
	        'CONTEXTCLICKS' - phrase clicks in RSYA
	        'CONTEXTPRICE' - user price in RSYA
	        'CONTEXTCTR'	- CTR  in RSYA
	        'CONTEXTCOVERAGE'	- array prices for coverage
                        [
                          { 
                             "Probability": (int)
                             "Price": (float)
                          }
                          ...
                       ]
            'SPREADS' {max,min,mid} - spread prices
		);
    */
	
	//main method
	public static function sys_methodMain($params) 
	{
			$retstavk=0;
			//check right params or not
			if( !( is_array($params["PRICES"]) && count($params["PRICES"]) ) ){ return 0;}
			
			$PRICES=CEDirectCalculate::convertPricesAr($params["PRICES"]);
			//minGuarantePrice price in guarante
			if($PRICES["P23"]["Price"]>0) $minGuarantePrice=$PRICES["P23"]["Price"];
			else $minGuarantePrice=$params['MINBET'];
			//minPremiumPrice price in spec
			if($PRICES["P14"]["Price"]>0) $minPremiumPrice=$PRICES["P14"]["Price"];
			else $minPremiumPrice=$params['PREMIUMMIN'];
								
			if($minGuarantePrice<=$params['MAX_PRICE']){
			    
			    //premium Calculate
				$spec=0;
				if($minPremiumPrice<=$params['MAX_PRICE']){
				    $dopusk=11;
				    if( $PRICES["P11"]["Price"]>0 && ($PRICES["P11"]["Bid"]/2)<$PRICES["P11"]["Price"] && $PRICES["P11"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P11"]["Price"],$minPremiumPrice)<$dopusk ) $spec=$PRICES["P11"]["Bid"];
				    else if( $PRICES["P12"]["Price"]>0 && ($PRICES["P12"]["Bid"]/2)<$PRICES["P12"]["Price"] && $PRICES["P12"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P12"]["Price"],$minPremiumPrice)<$dopusk ) $spec=$PRICES["P12"]["Bid"];
				    else if( $PRICES["P13"]["Price"]>0 && $PRICES["P13"]["Price"]<=$params['MAX_PRICE'] ) $spec=$PRICES["P13"]["Bid"];				    
				    else $spec=$params['PREMIUMMIN'];
				    //else if( ($params['PREMIUMMIN']/2)<$PRICES["P14"]["Price"] ) $spec=$params['PREMIUMMIN'];
				    //else if( $PRICES["P14"]["Price"]*2<=$params['MAX_PRICE'] ) $spec=$PRICES["P14"]["Price"]*2;
				    //else $spec=$params['MAX_PRICE'];
				    
					//$spec=ceil($spec+$spec*0.1);
					//$spec=ceil($spec+1);
				}
				
				//guarante Calcuate
				$dopusk=5;
				if( $PRICES["P21"]["Price"]>0 && ($PRICES["P21"]["Bid"]/3)<$PRICES["P21"]["Price"] && $PRICES["P21"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P21"]["Price"],$minGuarantePrice)<$dopusk ) $gar=$PRICES["P21"]["Bid"];
				else if( $PRICES["P22"]["Price"]>0 && ($PRICES["P22"]["Bid"]/3)<$PRICES["P22"]["Price"] && $PRICES["P22"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P22"]["Price"],$minGuarantePrice)<$dopusk ) $gar=$PRICES["P22"]["Bid"];
				else if( $PRICES["P23"]["Price"]>0 && ($PRICES["P23"]["Bid"]/3)<$PRICES["P23"]["Price"] && $PRICES["P23"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P23"]["Price"],$minGuarantePrice)<$dopusk ) $gar=$PRICES["P23"]["Bid"];
				else $gar=$params['MINBET'];
				
				//$gar=ceil($gar+$gar*0.1);
				
				//check, gar will not show in premium
				//if(ceil($gar+1)>=$params['PREMIUMMIN']) $gar=$params['PREMIUMMIN']-0.2;
				//else $gar=ceil($gar+1);					    
								
				if($params['MESTO_SEO']>0&&$params['MESTO_SEO']<4) $retstavk=$gar; //For TOP3
				else if($spec>0) $retstavk=$spec;
				else $retstavk=$gar;
			}
			else $retstavk=$params['MAX_PRICE'];									
									
			return $retstavk;
	}
	
	//show min in block
	public static function sys_methodMinInBlock($params)
	{
	    $retstavk=0;
	    //check right params or not
	    if( !( is_array($params["PRICES"]) && count($params["PRICES"]) ) ){ return 0;}
	     
	    $PRICES=CEDirectCalculate::convertPricesAr($params["PRICES"]);
	    //minGuarantePrice price in guarante
	    if($PRICES["P23"]["Price"]>0) $minGuarantePrice=$PRICES["P23"]["Price"];
	    else $minGuarantePrice=$params['MINBET'];
	    //minPremiumPrice price in spec
	    if($PRICES["P14"]["Price"]>0) $minPremiumPrice=$PRICES["P14"]["Price"];
	    else $minPremiumPrice=$params['PREMIUMMIN'];
	
	    if($minGuarantePrice<=$params['MAX_PRICE']){
	        //premium Calculate
	        $spec=0;
	        if($minPremiumPrice<=$params['MAX_PRICE']){
	            $spec=$params['PREMIUMMIN'];
	        }
	
	        //guarante Calcuate
	        $gar=$params['MINBET'];
	
	        if($params['MESTO_SEO']>0&&$params['MESTO_SEO']<4) $retstavk=$gar; //For TOP3
	        else if($spec>0) $retstavk=$spec;
	        else $retstavk=$gar;
	    }
	    else $retstavk=$params['MAX_PRICE'];
	     
	    return $retstavk;
	}
	
	//show only in Spec places with optimization
	public static function sys_methodOnlySpec($params)
	{
	    $retstavk=0;
	    //check right params or not
	    if( !( is_array($params["PRICES"]) && count($params["PRICES"]) ) ){ return 0;}
	
	    $PRICES=CEDirectCalculate::convertPricesAr($params["PRICES"]);
	    //minPremiumPrice price in spec
	    if($PRICES["P14"]["Price"]>0) $minPremiumPrice=$PRICES["P14"]["Price"];
	    else $minPremiumPrice=$params['PREMIUMMIN'];
	
	    if($minPremiumPrice<=$params['MAX_PRICE']){
	        //premium Calculate
	        $spec=0;
		    $dopusk=11;
		    if( $PRICES["P11"]["Price"]>0 && ($PRICES["P11"]["Bid"]/2)<$PRICES["P11"]["Price"] && $PRICES["P11"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P11"]["Price"],$minPremiumPrice)<$dopusk ) $spec=$PRICES["P11"]["Bid"];
		    else if( $PRICES["P12"]["Price"]>0 && ($PRICES["P12"]["Bid"]/2)<$PRICES["P12"]["Price"] && $PRICES["P12"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P12"]["Price"],$minPremiumPrice)<$dopusk ) $spec=$PRICES["P12"]["Bid"];
		    else if( $PRICES["P13"]["Price"]>0 && $PRICES["P13"]["Price"]<=$params['MAX_PRICE'] ) $spec=$PRICES["P13"]["Bid"];				    
		    else $spec=$params['PREMIUMMIN'];
		
	        if($params['MESTO_SEO']>0&&$params['MESTO_SEO']<4) $retstavk=EDIRECT_YALIMIT_MIN_YANDEX_SEARCH_PRICE; //For TOP3, close show
	        else $retstavk=$spec;
	    }
	    else $retstavk=EDIRECT_YALIMIT_MIN_YANDEX_SEARCH_PRICE;
	
	    return $retstavk;
	}
	
	//show only in Guarante places with optimization
	public static function sys_methodOnlyGuarante($params)
	{
	    $retstavk=0;
	    //check right params or not
	    if( !( is_array($params["PRICES"]) && count($params["PRICES"]) ) ){ return 0;}
	
	    $PRICES=CEDirectCalculate::convertPricesAr($params["PRICES"]);
	    //minGuarantePrice price in guarante
	    if($PRICES["P23"]["Price"]>0) $minGuarantePrice=$PRICES["P23"]["Price"];
	    else $minGuarantePrice=$params['MINBET'];
	
	    if($minGuarantePrice<=$params['MAX_PRICE']){
			//guarante Calcuate
			$dopusk=5;
			if( $PRICES["P21"]["Price"]>0 && ($PRICES["P21"]["Bid"]/3)<$PRICES["P21"]["Price"] && $PRICES["P21"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P21"]["Price"],$minGuarantePrice)<$dopusk ) $gar=$PRICES["P21"]["Bid"];
			else if( $PRICES["P22"]["Price"]>0 && ($PRICES["P22"]["Bid"]/3)<$PRICES["P22"]["Price"] && $PRICES["P22"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P22"]["Price"],$minGuarantePrice)<$dopusk ) $gar=$PRICES["P22"]["Bid"];
			else if( $PRICES["P23"]["Price"]>0 && ($PRICES["P23"]["Bid"]/3)<$PRICES["P23"]["Price"] && $PRICES["P23"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P23"]["Price"],$minGuarantePrice)<$dopusk ) $gar=$PRICES["P23"]["Bid"];
			else $gar=$params['MINBET'];
			
			$retstavk=$gar;
		}
		else $retstavk=$params['MAX_PRICE'];	
	
	    return $retstavk;
	}	
	
	//not change bid, NULL method
	public static function sys_methodNoBidChange($params)
	{
	    return 0;
	}
	
	//main method RSYA
	public static function sys_methodMainRSYA($params)
	{
	    $retstavk=0;
	    
	    if(count($params["CONTEXTCOVERAGE"])>0){ //is coverage DATA
	        $PRICES=CEDirectCalculate::convertCoverageAr($params["CONTEXTCOVERAGE"]);
	        
	        if($params["CONTEXTSHOWS"]>1000){
	            if( CEDirectCalculate::getDiffInPerc($PRICES[50]["Price"],$PRICES[20]["Price"])>45 ) $retstavk=$PRICES[20]["Price"];
	            else if( CEDirectCalculate::getDiffInPerc($PRICES[100]["Price"],$PRICES[50]["Price"])>45 ) $retstavk=$PRICES[50]["Price"];
	            else $retstavk=$PRICES[100]["Price"];
	            
	            if($retstavk>$params['MAX_PRICE']) $retstavk=$params['MAX_PRICE'];
	        }
	        else {
    	        if($PRICES[100]["Price"]<$params['MAX_PRICE']) $retstavk=$PRICES[100]["Price"];
    	        else $retstavk=$params['MAX_PRICE'];
	        }
	        
	    }
	    else{ // have not coverage DATA
	        if(count($params["SPREADS"])>0&&$params["SPREADS"]["mid"]>0) {
	            $retstavk=$params["SPREADS"]["mid"];
                if($retstavk>$params['MAX_PRICE']) $retstavk=$params['MAX_PRICE'];
            }
	        else {
                if ($params["CONTEXTSHOWS"] < 100) $retstavk = $params['MAX_PRICE'];
                else if ($params["CONTEXTSHOWS"] < 500) $retstavk = $params['MAX_PRICE'] * 0.7;
                else if ($params["CONTEXTSHOWS"] < 1000) $retstavk = $params['MAX_PRICE'] * 0.6;
                else $retstavk = $params['MAX_PRICE'] * 0.5;
            }
	    }
	    
	    if($retstavk<EDIRECT_YALIMIT_MIN_YANDEX_RSYA_IMG_PRICE&&$params['MAX_PRICE']>=EDIRECT_YALIMIT_MIN_YANDEX_RSYA_IMG_PRICE){ //min bet to show IMG in RUB!!!
	        $retstavk=EDIRECT_YALIMIT_MIN_YANDEX_RSYA_IMG_PRICE;
	    }
	        
	    return (ceil($retstavk*100)/100);
	}
	
	//max method RSYA
	public static function sys_methodMaxRSYA($params)
	{
	    $retstavk=0;
	    
	    if(count($params["CONTEXTCOVERAGE"])>0){ //is coverage DATA
	        $PRICES=CEDirectCalculate::convertCoverageAr($params["CONTEXTCOVERAGE"]);
	        
	        if($params["CONTEXTCLICKS"]>20){
	            if( CEDirectCalculate::getDiffInPerc($PRICES[100]["Price"],$PRICES[50]["Price"])>50 && ($PRICES[50]["Price"]+$PRICES[20]["Price"])<$PRICES[100]["Price"] ) $retstavk=$PRICES[50]["Price"]+$PRICES[20]["Price"];
	            else $retstavk=$PRICES[100]["Price"];
	            
	            if($retstavk>$params['MAX_PRICE']) $retstavk=$params['MAX_PRICE'];
	        }
	        else {
    	        if($PRICES[100]["Price"]<$params['MAX_PRICE']) $retstavk=$PRICES[100]["Price"];
    	        else $retstavk=$params['MAX_PRICE'];
	        }
	        
	    }
	    else{ // have not coverage DATA
            if(count($params["SPREADS"])>0&&$params["SPREADS"]["max"]>0) {
                $retstavk=$params["SPREADS"]["max"];
                if($retstavk>$params['MAX_PRICE']) $retstavk=$params['MAX_PRICE'];
            }
            else {
                if ($params["CONTEXTCLICKS"] < 100) $retstavk = $params['MAX_PRICE'];
                else if ($params["CONTEXTCLICKS"] < 500) $retstavk = $params['MAX_PRICE'] * 0.8;
                else if ($params["CONTEXTCLICKS"] < 1000) $retstavk = $params['MAX_PRICE'] * 0.7;
                else $retstavk = $params['MAX_PRICE'] * 0.6;
            }
	    }
	    
	    if($retstavk<EDIRECT_YALIMIT_MIN_YANDEX_RSYA_IMG_PRICE&&$params['MAX_PRICE']>=EDIRECT_YALIMIT_MIN_YANDEX_RSYA_IMG_PRICE){ //min bet to show IMG in RUB!!!
	        $retstavk=EDIRECT_YALIMIT_MIN_YANDEX_RSYA_IMG_PRICE;
	    }
	        
	    return (ceil($retstavk*100)/100);
	}	
}
?>