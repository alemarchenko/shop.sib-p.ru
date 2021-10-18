<?
$function_bidByPhraseName=function($params)
{
    $list=array();
    //========USER SETTINGS============
    
    $list[1]=array(
        array("купить",50),
        //array("билеты",150,"fix"),
    );
    
    $listToCompany=array(
        "8190027"=>1,
    );    
    
    
    //======================================
    //=========DO NOT EDIT BOTTOM CODE============
    //======================================
    $retbid=0;
    if(!EDIRECT_UTFSITE) $params["NAME"]=iconv("windows-1251","utf-8", $params["NAME"]);
    
    if($listToCompany[$params["ID_COMPANY"]]>0){
        foreach($list[$listToCompany[$params["ID_COMPANY"]]] as $find){
            $params["NAME"]=CEDirectPhrase::stripPhrase($params["NAME"]);
            if(preg_match("/".$find[0]."/ui", $params["NAME"])){
                if(isset($find[2])&&$find[2]=="fix"){
                    $retbid=array("TYPE"=>"fixbet","PRICE"=>$find[1]);
                }
                else{
                    $retbid=array("TYPE"=>"maxprice","PRICE"=>$find[1]);
                }
                break;
            }
        }        
    }
    
    return $retbid;
};
?>