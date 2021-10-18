<?
//field_NAME=Главный метод - клон
//field_FNAME=user_main
//field_TYPE=SEARCH
//field_IS_IMPORTANT=N
//field_DESCRIPTION=Главный метод - клон
//field_SORT=900

$user_main=function($params)
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
            else if( $PRICES["P13"]["Price"]>0 && ($PRICES["P13"]["Bid"]/2)<$PRICES["P13"]["Price"] && $PRICES["P13"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P13"]["Price"],$minPremiumPrice)<$dopusk ) $spec=$PRICES["P13"]["Bid"];				    
            else $spec=$params['PREMIUMMIN'];
        }
        
        //guarante Calcuate
        $dopusk=5;
        if( $PRICES["P21"]["Price"]>0 && ($PRICES["P21"]["Bid"]/3)<$PRICES["P21"]["Price"] && $PRICES["P21"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P21"]["Price"],$minGuarantePrice)<$dopusk ) $gar=$PRICES["P21"]["Bid"];
        else if( $PRICES["P22"]["Price"]>0 && ($PRICES["P22"]["Bid"]/3)<$PRICES["P22"]["Price"] && $PRICES["P22"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P22"]["Price"],$minGuarantePrice)<$dopusk ) $gar=$PRICES["P22"]["Bid"];
        else if( $PRICES["P23"]["Price"]>0 && ($PRICES["P23"]["Bid"]/3)<$PRICES["P23"]["Price"] && $PRICES["P23"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P23"]["Price"],$minGuarantePrice)<$dopusk ) $gar=$PRICES["P23"]["Bid"];
        else $gar=$params['MINBET'];
                                
        if($params['MESTO_SEO']>0&&$params['MESTO_SEO']<4) $retstavk=$gar; //For TOP3
        else if($spec>0) $retstavk=$spec;
        else $retstavk=$gar;
    }
    else $retstavk=$params['MAX_PRICE'];
                            
    return $retstavk;
};
?>
