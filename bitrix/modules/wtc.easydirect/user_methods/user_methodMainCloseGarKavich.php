<?
//field_NAME=Главный метод без Гарантии + учет кавычек
//field_FNAME=user_methodMainCloseGarKavich
//field_TYPE=SEARCH
//field_IS_IMPORTANT=N
//field_DESCRIPTION=Главный метод для спец. размещения. Показы только в СР, без гарантии. Если слово с кавычками, ставку увеличиваем на определенный % (10).
//field_SORT=840

$user_methodMainCloseGarKavich= function($params)
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
            $dopusk=11;
            if( $PRICES["P11"]["Price"]>0 && ($PRICES["P11"]["Bid"]/2)<$PRICES["P11"]["Price"] && $PRICES["P11"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P11"]["Price"],$minPremiumPrice)<$dopusk ) $spec=$PRICES["P11"]["Bid"];
            else if( $PRICES["P12"]["Price"]>0 && ($PRICES["P12"]["Bid"]/2)<$PRICES["P12"]["Price"] && $PRICES["P12"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P12"]["Price"],$minPremiumPrice)<$dopusk ) $spec=$PRICES["P12"]["Bid"];
            else if( $PRICES["P13"]["Price"]>0 && ($PRICES["P13"]["Bid"]/2)<$PRICES["P13"]["Price"] && $PRICES["P13"]["Price"]<=$params['MAX_PRICE'] && CEDirectCalculate::getDiffInPerc($PRICES["P13"]["Price"],$minPremiumPrice)<$dopusk ) $spec=$PRICES["P13"]["Bid"];
            else if( ($params['PREMIUMMIN']/2)<$PRICES["P14"]["Price"] ) $spec=$params['PREMIUMMIN'];
            else if( $PRICES["P14"]["Price"]*2<=$params['MAX_PRICE'] ) $spec=$PRICES["P14"]["Price"]*2;
            else $spec=$params['MAX_PRICE'];
        }
         
        //guarante Calcuate
        $gar=0.3;
         
        if($params['MESTO_SEO']>0&&$params['MESTO_SEO']<4) $retstavk=$gar; //For TOP3
        else if($spec>0) $retstavk=$spec;
        else $retstavk=$gar;
        
        //Up Stavk
        if($retstavk!=0.3&&strpos($params["NAME"], '"')!==false){
            $retstavk=ceil($retstavk+$retstavk*($PERCENT/100));
        }        
    }
    else $retstavk=0.3;

    return $retstavk;
};
?>
