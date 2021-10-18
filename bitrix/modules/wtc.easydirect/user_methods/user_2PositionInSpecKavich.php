<?
//field_NAME=2 место СР + учет кавычек
//field_FNAME=user_2PositionInSpecKavich
//field_TYPE=SEARCH
//field_IS_IMPORTANT=N
//field_DESCRIPTION=Второе место спец. размещения или ниже. Если слово с кавычками, ставку увеличиваем на определенный % (10).
//field_SORT=700

$user_2PositionInSpecKavich= function($params)
{
    //------------------
    $PERCENT=10; //percent to UP 
    //------------------

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
            if( $PRICES["P12"]["Price"]<=$params['MAX_PRICE'] ) $spec=$PRICES["P12"]["Bid"];
            else if( $PRICES["P13"]["Price"]<=$params['MAX_PRICE'] ) $spec=$PRICES["P13"]["Bid"];
            else $spec=$params['PREMIUMMIN'];
        }
         
        //guarante Calcuate
        $gar=$params['MINBET'];
         
        if($params['MESTO_SEO']>0&&$params['MESTO_SEO']<4) $retstavk=$gar; //For TOP3
        else if($spec>0) $retstavk=$spec;
        else $retstavk=$gar;
    }
    else $retstavk=$params['MAX_PRICE'];

    //Up Stavk
    if(strpos($params["NAME"], '"')!==false){
        $retstavk=ceil($retstavk+$retstavk*($PERCENT/100));
    }   
    
    return $retstavk;
};
?>
