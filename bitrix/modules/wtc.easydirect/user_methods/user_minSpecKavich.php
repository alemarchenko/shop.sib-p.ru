<?
//field_NAME=Минимальная позиция в СР без Гарантии + учет кавычек
//field_FNAME=user_minSpecKavich
//field_TYPE=SEARCH
//field_IS_IMPORTANT=N
//field_DESCRIPTION=Минимальная позиция в СР. Показы только в СР, без гарантии. Если слово с кавычками, ставку увеличиваем на определенный % (10).
//field_SORT=860

$user_minSpecKavich= function($params)
{
    //------------------
    $PERCENT=10; //percent to UP 
    //------------------
    
    $retstavk=0;
    //check right params or not
    if( !( is_array($params["PRICES"]) && count($params["PRICES"]) ) ){ return 0;}
     
    $PRICES=CEDirectCalculate::convertPricesAr($params["PRICES"]);
    //minPremiumPrice price in spec
    if($PRICES["P14"]["Price"]>0) $minPremiumPrice=$PRICES["P14"]["Price"];
    else $minPremiumPrice=$params['PREMIUMMIN'];

    if($minPremiumPrice<=$params['MAX_PRICE']){
         
        if($params['MESTO_SEO']>0&&$params['MESTO_SEO']<4) $retstavk=0.3; //For TOP3
        else $retstavk=$params['PREMIUMMIN'];
         
        //Up Stavk
        if($retstavk!=0.3&&strpos($params["NAME"], '"')!==false){
            $retstavk=ceil($retstavk+$retstavk*($PERCENT/100));
        }         
    }
    else $retstavk=0.3;
     
    return $retstavk;
};
?>
