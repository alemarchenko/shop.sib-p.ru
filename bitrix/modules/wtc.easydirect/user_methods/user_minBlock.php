<?
//field_NAME=Место в блоке по минимальной цене
//field_FNAME=user_minBlock
//field_TYPE=SEARCH
//field_IS_IMPORTANT=N
//field_DESCRIPTION=Метод стремиться занять минимальное место в блоке. Если максимальной цены хватает для СР - выводит в СР, если не хватает в гарантию.
//field_SORT=700

$user_minBlock = function($params)
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
     };
?>
