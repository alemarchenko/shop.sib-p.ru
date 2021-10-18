<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); 

//module include
$install_status = CModule::IncludeModuleEx("wtc.easydirect");
IncludeModuleLangFile(__FILE__);

//get POST_RIGHT
$POST_RIGHT = $APPLICATION->GetGroupRight("wtc.easydirect");
//Check POST_RIGHT
if ($POST_RIGHT < "W")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

// ******************************************************************** //
//                    SEND  DATA                               //
// ******************************************************************** //
if(isset($_POST["sendBanners"])||isset($_POST["saveBanners"])||isset($_POST["autoreplace"])||isset($_POST["loadsitelinks"])){
	// SET TITLE
	$APPLICATION->SetTitle(GetMessage("EASYDIRECT_CREATE_title_send"));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	
	//prepare and filter Data
	$arFGroups=array();
	$gCountID=10000;
	$bCountID=20000;
	$arImagesInTmp=array();
	foreach ($_POST["group"] as $gid=>$group){
	    foreach ($group["banners"] as $bid=>$banner){
	        if(strlen($banner["title"])>2){ //do not add empty elements
	            //save user images
	            if($banner["image_type"]=="new_user_file"){
	                unset($banner["image_type"]);
	                unset($banner["image"]);
	                if($_FILES["imgfile_".$gid."_".$bid]["error"]==0){
	                    if($fileurl=CEDirectCompany::saveImageAsTmp($_FILES["imgfile_".$gid."_".$bid])){
	                        $banner["image_type"]="user_file";
	                        $banner["image"]=$fileurl;
	                    }
	                }
	            }	            
	            //collect all tmp images in array
	            if($banner["image_type"]=="user_file"){
	                $arImagesInTmp[]=$banner["image"];
	            }
	            //save banner
	            $arFGroups[$gCountID]["banners"][$bCountID]=$banner;
	            $bCountID++;
	        }
	    }
	    if(count($arFGroups[$gCountID]["banners"])>0){ //do not add empty elements
	        //clone group if pharese > 200, max in one group / Splite phrases
	        $arGroupPhrases=array_chunk( array_unique(explode("\n",$group['phrases'])) , (EDIRECT_YALIMIT_PHRASES_IN_GROUP-20) );
	        foreach ($arGroupPhrases as $phraseGroup=>$arPhrases){
	            if($phraseGroup>0){
	                foreach ($arFGroups[($gCountID-1)]["banners"] as $clonebanner){
	                    $arFGroups[$gCountID]["banners"][$bCountID]=$clonebanner;
	                    $bCountID++;
	                }
	            }	            
	            $arFGroups[$gCountID]["phrases"]=implode(PHP_EOL,$arPhrases);
	            $gCountID++;	            
	        }
	    }
	}
	$_POST["group"]=$arFGroups;	
	
	//--delete old tmp images
	CEDirectCompany::clearTmpImages($arImagesInTmp);
	//--save Data--
	$prefixFile="";
	if(isset($_POST["autoreplace"])) $prefixFile="-ar-tmp";
	CEDirectCompany::saveDataInFile("save",$_POST,$prefixFile);
	if($_POST["price"]>0) COption::SetOptionString("wtc.easydirect","create_company_price",$_POST["price"]);
	if($_POST["basecompany"]>0) COption::SetOptionString("wtc.easydirect","create_company_basecompany",$_POST["basecompany"]);
	if($_POST["basecompany_rsya"]>0) COption::SetOptionString("wtc.easydirect","create_company_basecompany_rsya",$_POST["basecompany_rsya"]);
		
	//--if only save data will not send data---
	if(isset($_POST["saveBanners"])||isset($_POST["autoreplace"])||isset($_POST["loadsitelinks"])) {
	    $_POST["CType"]="";
	    if(isset($_POST["saveBanners"])){
    	    CAdminMessage::ShowMessage(array(
    	        "MESSAGE"=>GetMessage("EASYDIRECT_CREATE_save_ok"),
    	        "DETAILS"=> '<a href="/bitrix/admin/wtc_easydirect_create_company.php?loadData=Y">'.GetMessage("EASYDIRECT_CREATE_save_continue_link").'</a>',
    	        "TYPE"=>"OK",
    	        "HTML"=>true
    	    ));
	    }
	}
	     
	//=======================
	//----------create Yandex----------------------
	//=======================	
	if($_POST["CType"]=="YA"){
	    //======Init Export Class========
	    $export = new CEDirectCompanyExport($_POST);
	    //save export class state in file
	    CEDirectCompany::saveDataInFile("save",$export,"-export-tmp");
	    
	    //======start progress bar=======
	    echo '<div class="edirect-progress">';
	    CAdminMessage::ShowMessage(array(
        	    "MESSAGE"=>GetMessage("EASYDIRECT_CREATE_start_export"),
        	    "DETAILS"=> "#PROGRESS_BAR#".GetMessage("EASYDIRECT_CREATE_start_export_mess"),
        	    "HTML"=>true,
        	    "TYPE"=>"PROGRESS",
        	    "PROGRESS_TOTAL" => 100,
        	    "PROGRESS_VALUE" => 2
    	    ));	        
	    echo '</div>';
	    
	    //=====JQuery Recursion=========
	    CJSCore::Init(array("jquery"));
	    ?>
	    <script language="JavaScript">
	    $.ajaxSetup({cache: false}); 
	    //export company by steps
	    function execCompanyExport(){
	    	$.post(
	    			"/bitrix/admin/wtc_easydirect_ajax_company_export.php",
	    			{},
	    			function(data){
	    				$('.edirect-progress').html(data);
	    			}
	    			);	
	    }

	    $(document).ready(function() {
	    	window.top.execCompanyExport();
	    });
	    </script>	    
	    <?
	    //=========================
	}
	//=======================

	//if autoreplace forward to loadData
	if(isset($_POST["autoreplace"])||isset($_POST["loadsitelinks"])){
	    $_REQUEST["loadData"]="Y";
	}
	//finish page if not autoreplace
	else{
	    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	    exit();	    
	}
}

// ******************************************************************** //
//**********GET POST PARAMS SET DEFAULTS*****************************//
//update banners
$groupBanners=false;
$delSpec=false;
if($_POST["btnUpdate"]) {
    if($_POST["groupBanners"]=="Y") $groupBanners="Y";
    if($_POST["delSpec"]=="Y") $delSpec="Y";
}
	
// CType [YA, GGL(not use now)]
if($_REQUEST["CType"]) $CType=$_REQUEST["CType"];
else $CType="YA";

//CID
if($_REQUEST["ID"] && is_numeric($_REQUEST["ID"])) $CID=$_REQUEST["ID"];
else $CID=0;


// ******************************************************************** //
//                PREPARE  DATA                          //
// ******************************************************************** //
function showBanner($Banner,$groupId,$CType){
    global $EDirectMain;
    if($groupId==0) {
        $groupId="'+groupId+'";
        $Banner["ID"]="'+bannerCNT+'";
    }
    $image="";
    if($Banner['IMAGE']){
        //IMAGE_TYPE = yandex_hash / bitrix_file / user_file
        $image='
            <input type="hidden" name="group['.$groupId.'][banners]['.$Banner["ID"].'][image]" value="'.$Banner['IMAGE'].'"></input>
            <select onChange="changeuserfile(this,this.value)" name="group['.$groupId.'][banners]['.$Banner["ID"].'][image_type]">
                <option value="'.$Banner['IMAGE_TYPE'].'">'.GetMessage("EASYDIRECT_CREATE_banner_rsya_img_".$Banner['IMAGE_TYPE']).'</option>
                <option value="new_user_file">'.GetMessage("EASYDIRECT_CREATE_banner_rsya_img_new_user_file").'</option>
            </select><br>';
    }
    else {
        $image='
            <input type="hidden" name="group['.$groupId.'][banners]['.$Banner["ID"].'][image_type]" value="new_user_file"></input>
                ';
    }
    $str= '
        <div id="banner'.$Banner["ID"].'" class="new-banner">
            <span><'.GetMessage("EASYDIRECT_CREATE_banner_title").'></span>
			<br><input class="btitle'.$Banner["ID"].'" type="text" onKeyUp="inputcount(this,0,35)" name="group['.$groupId.'][banners]['.$Banner["ID"].'][title]" size="38" value="'.$EDirectMain->htmlspecialcharsEx($Banner['TITLE']).'">
            <br><span><'.GetMessage("EASYDIRECT_CREATE_banner_title2").'></span>
			<br><input class="btitle2'.$Banner["ID"].'" type="text" onKeyUp="inputcount(this,0,30)" name="group['.$groupId.'][banners]['.$Banner["ID"].'][title2]" size="32" value="'.$EDirectMain->htmlspecialcharsEx($Banner['TITLE2']).'">            				    
			<br><textarea class="btext'.$Banner["ID"].'" rows="3" cols="36" onKeyUp="inputcount(this,0,81)" name="group['.$groupId.'][banners]['.$Banner["ID"].'][text]">'.$EDirectMain->htmlspecialcharsEx($Banner['TEXT']).'</textarea>
            <br><span><'.GetMessage("EASYDIRECT_CREATE_banner_href").'></span>
			<br><input class="bhref'.$Banner["ID"].'" type="text" name="group['.$groupId.'][banners]['.$Banner["ID"].'][href]" size="32" value="'.$Banner['HREF'].'">		    
            <br><span><'.GetMessage("EASYDIRECT_CREATE_banner_display_url").'></span>
			<br><input class="bdisplay_url'.$Banner["ID"].'" type="text" onKeyUp="inputcount(this,0,20)" name="group['.$groupId.'][banners]['.$Banner["ID"].'][display_url]" size="32" value="'.$EDirectMain->htmlspecialcharsEx($Banner['DISPLAY_URL']).'">            				    
            <br><span><'.GetMessage("EASYDIRECT_CREATE_banner_price").'></span>
			<br><input class="bprice'.$Banner["ID"].'" type="text" name="group['.$groupId.'][banners]['.$Banner["ID"].'][price]" size="32" value="'.($Banner['PRICE']>0?$Banner['PRICE']:"").'">		    
		    <br><span><'.GetMessage("EASYDIRECT_CREATE_banner_rsya_img").'></span>
			<br>'.$image.'
		        <input type="file" '.($Banner['IMAGE']==""?'':'style="display:none;"').' name="imgfile_'.$groupId.'_'.$Banner["ID"].'">
        	<br><br><a onclick="delbanner('.$Banner["ID"].')" href="#stayhere" class="wtc-easydirect-link-del"> < '.GetMessage("EASYDIRECT_CREATE_delbanner").' > </a>
        	 <hr>
        </div>
        ';    
    return $str;
}

function showBannerGroup($arGroup,$CType="Ya"){
    $arReturn=array();
    //get empty params in group
    if(!count($arGroup)) $arGroup=array("ID"=>"'+groupCNT+'","BANNERS"=>array(),"PHRASES"=>array());
    
    $arReturn[1].='<a onclick="addbanner('.$arGroup["ID"].')" href="#stayhere" > < '.GetMessage("EASYDIRECT_CREATE_addbanner").' > </a><br><br><div></div>';    
    foreach ($arGroup['BANNERS'] as $Banner){
        $arReturn[1].=showBanner($Banner,$arGroup["ID"],$CType);
    }
    $arReturn[2]='<textarea rows="12" cols="50" name="group['.$arGroup["ID"].'][phrases]">'.implode("\n",array_unique($arGroup['PHRASES'])).'</textarea>';
    $arReturn[3]='
    				<a onclick="addgroup('.$arGroup["ID"].')" href="#stayhere">< '.GetMessage("EASYDIRECT_CREATE_addgroup").' ></a>
        			<br><br>
    				<a onclick="delgroup('.$arGroup["ID"].')" href="#stayhere" class="wtc-easydirect-link-del">< '.GetMessage("EASYDIRECT_CREATE_delgroup").' ></a>
    			';    
    
    return $arReturn;
}
//-----------------------------------------------------------------------------
$arGroups=array();
$arCompany=array();
// ----------------------- load save data / autoreplace ----------------------
if(isset($_REQUEST["loadData"])){
    //---autoreplace prefix file----
    $prefixFile="";
    if(isset($_POST["autoreplace"])) $prefixFile="-ar-tmp";
        
    $arData=CEDirectCompany::saveDataInFile("load",array(),$prefixFile);
    if(is_array($arData)){
        //----------autoreplacement-----------
        if(isset($_POST["autoreplace"])){
            $arFindWords=array();
            $arReplaceWords=array();
            $replaceCnt=0;
            $i=0;
            foreach (explode("\n",$_POST['ar_replacements']) as $str){
                $parts=explode("=",$str);
                if(count($parts)>1&&strlen($parts[0])){
                    $arFindWords[$i]=$parts[0];
                    $arReplaceWords[$i]=trim($parts[1],"\n\r");
                    $i++;
                }
            }
            if(count($arFindWords)>0&&strlen($_POST['ar_field'])>1){
                if($_POST['ar_field']=="all"){
                    $arFieldsToCheck=array("Title","Href","Description","title","title2","text","href","display_url");
                }
                else if($_POST['ar_field']=="sl"){
                    $arFieldsToCheck=array("Title","Description");
                }
                else if($_POST['ar_field']=="href"){
                    $arFieldsToCheck=array("Href","href");                
                }
                else if($_POST['ar_field']=="title"){
                    $arFieldsToCheck=array("Title","title");
                }
                else {
                    $arFieldsToCheck=array($_POST['ar_field']);
                }
                
                //sitelinks replace
                if(in_array($_POST['ar_field'],array("sl","all","href"))){
                    foreach ($arData["Sitelinks"] as &$link){
                        foreach ($link as $key=>&$value){
                            if(in_array($key,$arFieldsToCheck)){
                                $value=str_replace($arFindWords, $arReplaceWords, $value, $repCnt);
                                $replaceCnt+=$repCnt;
                            }
                        }
                    }
                }
                unset($value,$link);
                //banners and phrases replace
                if($_POST['ar_field']!="sl"){
                    foreach ($arData["group"] as &$group){
                        foreach ($group["banners"] as &$banner){
                            foreach ($banner as $key=>&$value){
                                if(in_array($key,$arFieldsToCheck)){
                                    $value=str_replace($arFindWords, $arReplaceWords, $value, $repCnt);
                                    $replaceCnt+=$repCnt;
                                }
                            }
                        }
                        if(in_array($_POST['ar_field'],array("all","phrases"))){
                            $group["phrases"]=str_replace($arFindWords, $arReplaceWords, $group["phrases"], $repCnt);
                            $replaceCnt+=$repCnt;
                        }
                    }
                }
                unset($value,$group,$banner);
            }
        }
        //---------------------------------------------------
        
        //main params
        $CID=$arData["ID"];
        $CType=$arData["CType"];
        $_POST["addtocompany"]=$arData["addtocompany"];
        $_POST["forSEARCH"]=(isset($arData["forSEARCH"])?$arData["forSEARCH"]:"N");
        $_POST["forRSYA"]=(isset($arData["forRSYA"])?$arData["forRSYA"]:"N");
        $_POST["basecompany"]=$arData["basecompany"];
        $_POST["basecompany_rsya"]=$arData["basecompany_rsya"];
        $_POST["name"]=$arData["name"];
        $_POST["price"]=$arData["price"];
        $_POST["href"]=$arData["href"];
        $_POST["display_url"]=$arData["display_url"];
        $_POST["addUtm"]=(isset($arData["addUtm"])?$arData["addUtm"]:"N");
        $_POST["minuswords"]=$arData["minuswords"];
        $_POST["rsya_img"]=$arData["rsya_img"];
        $_POST["defImgForAll"]=(isset($arData["defImgForAll"])?$arData["defImgForAll"]:"N");
        
        //--site links---
        if(isset($_POST["loadsitelinks"])&&strlen($_POST["siteliksfrom"])>0){ //LOAD sitelinks FROM Company or Template
            $arLinksParam=explode("_", $_POST["siteliksfrom"]);
            if($arLinksParam[1]>0){
                if($arLinksParam[0]=="comp"){
                    $rsData = CEDirectBannerGroup::GetList(array("ID"=>"ASC"), array("ID_COMPANY"=>$arLinksParam[1]));
                    if($arGroup = $rsData->GetNext())
                    {
                        $resp=CEDirectBanner::GetList(array("ID"=>"ASC"),array("ID_BANNER_GROUP"=>$arGroup['ID']));
                        if ($arElement=$resp->Fetch()) {
                            if(!isset($arCompany["SITELINKS"])) $arCompany["SITELINKS"]=CAllEDirectTable::UnSerializeArrayField($arElement["SITELINKS"]);
                        }
                    }
                }
                else if($arLinksParam[0]=="tmpl"){
                    $res=CEDirectTemplates::GetByID($arLinksParam[1]);
                    $arTemplate = $res->Fetch();
                    $arCompany["SITELINKS"]=CAllEDirectTable::UnSerializeArrayField($arTemplate["SITELINKS"]);
                }

                //delete utm in load links
                foreach ($arCompany["SITELINKS"] as &$link){
                    if(strlen($link["Href"])>0) {
                        $startUrl=$link["Href"];

                        //save anchor
                        $anchor="";
                        $arUrl=explode("#",$startUrl);
                        if(strlen($arUrl[1])>0){
                            $anchor=$arUrl[1];
                            $startUrl=$arUrl[0];
                        }

                        $arUrl=explode("?",$startUrl);
                        if(strlen($arUrl[1])>0){
                            $arUrlParams=explode("&",$arUrl[1]);
                            if(count($arUrlParams)>0){
                                $newUrl=$arUrl[0];
                                $i=0;
                                foreach ($arUrlParams as $param){
                                    if($param&&stripos($param, "utm_")===false){
                                        if($i!=0) $newUrl.="&";
                                        else $newUrl.="?";
                                        $newUrl.=$param;
                                        $i++;
                                    }
                                }

                                //add saved anchor
                                if(strlen($anchor)>0){
                                    $newUrl.="#".$anchor;
                                }
                                //save modified link
                                $link["Href"]=$newUrl;
                            }
                        }
                    }
                }

            }
        }
        else{
            $arCompany["SITELINKS"]=array();
            foreach ($arData["Sitelinks"] as $key=>$link){
                $arCompany["SITELINKS"]["n".$key]=array(
                    "Title"=>$link["Title"],
                    "Href"=>$link["Href"],
                    "Description"=>$link["Description"]
                );
            }
        }
        //----------------
        
        //banners
        foreach ($arData["group"] as $keyGroup=>$group){
            $BANNERS=array();
            foreach ($group["banners"] as $keyBanner=>$banner){
                $BANNERS[]=array(
                    "ID"=>$keyBanner,
                    "TITLE"=>$banner["title"],
                    "TITLE2"=>$banner["title2"],
                    "TEXT"=>$banner["text"],
                    "HREF"=>$banner["href"],
                    "DISPLAY_URL"=>$banner["display_url"],            
                    "PRICE"=>$banner["price"],
                    "IMAGE"=>$banner["image"],
                    "IMAGE_TYPE"=>$banner["image_type"]
                );
            }
            
            $arGroups[]=array(
                "ID"=>$keyGroup,
                "PHRASES"=>explode("\n",$group["phrases"]),
                "BANNERS"=>$BANNERS
            );
        }        
    }
}
//-------------------clone company--------------------
else if($CID>0){
	$res=CEDirectCompany::GetByID($CID);
	if($arCompany=$res->Fetch()){
		//get banners info
		$rsData = CEDirectBannerGroup::GetList(array("ID"=>"ASC"), array("ID_COMPANY"=>$CID));
		while($arGroup = $rsData->GetNext())
		{				    
		    $bannerGroup=array();
		    $bannerGroup["ID"]=$arGroup["ID"];
			
		    //get phrases
			$phrases=array();
			$resp=CEDirectPhrase::GetList(array(),array("ID_BANNER_GROUP"=>$arGroup['ID']));
			while ($arElement=$resp->Fetch()) {
			    //delete all specsimv and minus words
			    if($delSpec) $arElement['NAME']=CEDirectPhrase::stripPhrase($arElement['NAME']);
				$phrases[]=$arElement['NAME'];
			}
			$bannerGroup["PHRASES"]=array_unique($phrases);
			
			//get banners
			$resp=CEDirectBanner::GetList(array(),array("ID_BANNER_GROUP"=>$arGroup['ID']));
			while ($arElement=$resp->Fetch()) {
			    //get image hash, check image size
			    $BImage="";
			    $arImage=CAllEDirectTable::UnSerializeArrayField($arElement["IMAGE"]);
		        if(strlen($arImage["AdImageHash"])>4) $BImage=$arImage["AdImageHash"];
			     
			    //save info
			    $bannerGroup["BANNERS"][]=array(
			        "ID"=>$arElement["ID"],
			        "TITLE"=>$arElement["TITLE"],
			        "TITLE2"=>$arElement["TITLE2"],
			        "TEXT"=>$arElement["TEXT"],
			        "HREF"=>$arElement["HREF"],
			        "DISPLAY_URL"=>$arElement["DISPLAY_URL"],
			        "PRICE"=>$arElement["PRICE"],
			        "IMAGE"=>$BImage,
			        "IMAGE_TYPE"=>"yandex_hash"
			    );
			    
			    //get HREF and SITELINKS
			    if(!isset($arCompany["HREF"])) $arCompany["HREF"]=$arElement["HREF"];
			    if(!isset($arCompany["DISPLAY_URL"])) $arCompany["DISPLAY_URL"]=$arElement["DISPLAY_URL"];
			    if(!isset($arCompany["SITELINKS"])) $arCompany["SITELINKS"]=CAllEDirectTable::UnSerializeArrayField($arElement["SITELINKS"]);			     
			}
			
			$arGroups[]=$bannerGroup;
		}		
		//save group banners and words
		if($groupBanners) {
		    $OneGroupBanner=array();
		    foreach ($arGroups as $group){
		        if(count($OneGroupBanner)==0) {$OneGroupBanner=$group; continue;}
		        $OneGroupBanner["PHRASES"]=array_merge($OneGroupBanner["PHRASES"],$group["PHRASES"]);
		        $OneGroupBanner["BANNERS"]=array_merge($OneGroupBanner["BANNERS"],$group["BANNERS"]);		        
		    }
		    $arGroups=array($OneGroupBanner);
		}
		
		//GET ADDITION DATA from Yandex
		//GET Company Info
		$arCampaign=$obYaExchange->getCompanyParams($CID);
		if(is_array($arCampaign)){
    		$arCampaign=$arCampaign[0];
    		//get minus words
    		$arCompany["MINUS"]=array();
    		if(is_array($arCampaign['NegativeKeywords']['Items'])&&count($arCampaign['NegativeKeywords']['Items'])) $arCompany["MINUS"]=$arCampaign['NegativeKeywords']['Items'];
		}
	}
}
//------------------create NEW banners-------------------
else{
    //fill minus words
    $_POST["minuswords"]="";
    $rsData =CEDirectPodborPhrases::GetList(array("NAME"=>"ASC","ID"=>"ASC"), array("TYPE"=>"M"));
    while ($arPhrase=$rsData->Fetch()) {
        $_POST["minuswords"].=$arPhrase["NAME"].", ";
    }
    
    //check grouped before
    $groupedBefore=0;
    $rsData =CEDirectPodborPhrases::GetList(array("SORT"=>"ASC"), array("!SORT"=>"500","TYPE"=>"S"));
    if ($arPhrase=$rsData->Fetch()) {
        $groupedBefore=1;
    }
    
    //no need make groups
    if($groupBanners=="Y"){
        $arPhrases=array();
        $rsData =CEDirectPodborPhrases::GetList(array("LENGTH(NAME)"=>"DESC"), array("TYPE"=>"S"));
        while ($arPhrase=$rsData->Fetch()) {
            $arPhrases[]=$arPhrase['NAME'];
        }
        $arGroupWords=array(array("SEARCH"=>CEDirectPhrase::stripPhrase($arPhrases[0]),"PHRASES"=>$arPhrases));
    }
    //grouped before
    else if($groupedBefore){
        $oldSort=0;
        $rsData =CEDirectPodborPhrases::GetList(array("SORT"=>"ASC","LENGTH(NAME)"=>"DESC"), array("TYPE"=>"S"));
        while ($arPhrase=$rsData->Fetch()) {
            if($oldSort!=$arPhrase['SORT']){
                if($oldSort!=0) $arGroupWords[]=array("SEARCH"=>CEDirectPhrase::stripPhrase($arPhrases[0]),"PHRASES"=>$arPhrases);        
                $oldSort=$arPhrase['SORT'];
                $arPhrases=array();
            }
            $arPhrases[]=$arPhrase['NAME'];
        }
        $arGroupWords[]=array("SEARCH"=>CEDirectPhrase::stripPhrase($arPhrases[0]),"PHRASES"=>$arPhrases);        
    }
    //put words into the groups
    else{
        $arGroupWords=array();
        $arLongPhrases=array();
        $maxLenght=33;
        $stemmer=new CEDirectStemmer();
        $rsData =CEDirectPodborPhrases::GetList(array("LENGTH(NAME)"=>"DESC"), array("TYPE"=>"S"));
        while ($arPhrase=$rsData->Fetch()) {
            $NAME=CEDirectPhrase::stripPhrase($arPhrase['NAME']);
            if(strlen($NAME)>$maxLenght) {
                $arLongPhrases[]=array("CLEARPHRASE"=>$NAME,"PHRASE"=>$arPhrase['NAME']);
                continue;
            }
            $find=0;
            foreach ($arGroupWords as $key=>$group){
                $arDif=$stemmer->getDifferentWords($group["SEARCH"],$NAME);
                $strDif=implode(" ", $arDif);
                if(count($arDif)==0){
                    $arGroupWords[$key]["PHRASES"][]=$arPhrase['NAME'];
                    $find=1;
                    break;
                }
                /*else if( strlen($group["SEARCH"])<$maxLenght && strlen($group["SEARCH"].$strDif)<$maxLenght ){
                    $arGroupWords[$key]["SEARCH"].=" ".$strDif;
                    $arGroupWords[$key]["PHRASES"][]=$arPhrase['NAME'];
                    $find=1;
                    break;                
                }*/
            }
            if($find==0) $arGroupWords[]=array("SEARCH"=>$NAME,"PHRASES"=>array($arPhrase['NAME']));
        }
        //find place for long phrases
        $arLongPhrases=array_reverse($arLongPhrases);
        for($i=0;$i<2;$i++){
            foreach($arLongPhrases as $keyLong=>$pharase){
                foreach ($arGroupWords as $key=>$group){
                    $arDif=$stemmer->getDifferentWords($pharase["CLEARPHRASE"],$group["SEARCH"]);
                    $arDif2=$stemmer->getDifferentWords($group["SEARCH"],$pharase["CLEARPHRASE"]);
                    if(count($arDif)==0 || count($arDif2)==0){
                        if(count($arDif2)>0){
                            $strDif=implode(" ", $arDif2);
                            $arGroupWords[$key]["SEARCH"].=" ".$strDif;
                        }
                        $arGroupWords[$key]["PHRASES"][]=$pharase['PHRASE'];
                        unset($arLongPhrases[$keyLong]);
                        break;
                    }
                }
            }
        }    
        if(count($arLongPhrases)) {
            foreach ($arLongPhrases as $pharase){
                $arGroupWords[]=array("SEARCH"=>$pharase["CLEARPHRASE"],"PHRASES"=>array($pharase['PHRASE']));
            }
        }
    }
    
    //build banners
    $i=10000;
    foreach ($arGroupWords as $group){
        //delete all specsimv and minus words
        if($delSpec) {
            foreach ($group["PHRASES"] as &$value){
                $value=CEDirectPhrase::stripPhrase($value);
            }
            unset($value);
        }
        $arGroups[]=array(
            "ID"=>$i,
            "PHRASES"=>$group["PHRASES"],            
            "BANNERS"=>array(
                array(
			        "ID"=>$i+10000,
			        "TITLE"=>CEDirectBanner::ucfirstCyrillic(CEDirectPhrase::stripPhrase($group["PHRASES"][0])),
			        "TEXT"=>CEDirectBanner::ucfirstCyrillic($group["SEARCH"])
                     )
            )
		);
        $i++;
    }    
}
//------------------------------------------

//create show banners arrays
$arGroupsVivod=array();
$goupCNT=0;
$bannerCNT=0;
if(count($arGroups)>0){
    foreach($arGroups as $val)
    {
        $goupCNT++;
        $bannerCN+=count($val["BANNERS"]);
        $arStr=array();        	
        $arStr["Props"]=array("id"=>"group".$val["ID"]);
        $arStr=array_merge($arStr,showBannerGroup($val,$CType));
        $arGroupsVivod[]=$arStr;
    }
}

//create show sitelinks arrays
$arSitelinksParam=array("maxlength"=>"66", "maxlengthnote"=>" (66 max)", "length"=>30, "descrLength"=>60);
$arSitelinksVivod=array();
$arSitelinksVivodB2=array();
$block=1;
for($j=0;$j<8;$j++){
    if($j==4) $block=2;
    $arSitelinksBuf=array(
        '<input type="text" onKeyUp="linkscount(this,0,'.$arSitelinksParam["length"].','.$block.')" class="Sitelink'.$j.'" name="Sitelinks['.$j.'][Title]" size="25" value="'.$EDirectMain->htmlspecialcharsEx($arCompany["SITELINKS"]["n".$j]["Title"]).'">',
        '<input type="text" name="Sitelinks['.$j.'][Href]" size="35" value="'.$arCompany["SITELINKS"]["n".$j]["Href"].'">',
        '<input type="text" onKeyUp="linkscount(this,0,'.$arSitelinksParam["descrLength"].','.$block.')" class="DescrSitelink'.$j.'" name="Sitelinks['.$j.'][Description]" size="45" value="'.$EDirectMain->htmlspecialcharsEx($arCompany["SITELINKS"]["n".$j]["Description"]).'">'
    );
    if($j>3){
        $arSitelinksVivodB2[]=$arSitelinksBuf;
    }
    else{
        $arSitelinksVivod[]=$arSitelinksBuf;
    }
}

//--------------------default params-------------------------
$defPrice=COption::GetOptionString("wtc.easydirect", "create_company_price");
if(!$defPrice) $defPrice=10;
$defBasecompany=COption::GetOptionString("wtc.easydirect", "create_company_basecompany");
$defBasecompanyRSYA=COption::GetOptionString("wtc.easydirect", "create_company_basecompany_rsya");
$arCParams=array(
    "NAME"=>($_POST["name"]?$_POST["name"]:$arCompany["NAME"]),
    "HREF"=>($_POST["href"]?$_POST["href"]:$arCompany["HREF"]),
    "DISPLAY_URL"=>($_POST["display_url"]?$_POST["display_url"]:$arCompany["DISPLAY_URL"]),
    "PRICE"=>($_POST["price"]?$_POST["price"]:$defPrice),
    "BASECOMPANY"=>($_POST["basecompany"]?$_POST["basecompany"]:$defBasecompany),
    "BASECOMPANY_RSYA"=>($_POST["basecompany_rsya"]?$_POST["basecompany_rsya"]:$defBasecompanyRSYA),
    "MINUSWORDS"=>($_POST["minuswords"]?$_POST["minuswords"]:implode(", ",$arCompany["MINUS"]))    
);

// ******************************************************************** //
//               SHOW DATA                                             //
// ******************************************************************** //
// SET TITLE
if($CID) $APPLICATION->SetTitle(GetMessage("EASYDIRECT_CREATE_title2"));
else $APPLICATION->SetTitle(GetMessage("EASYDIRECT_CREATE_title1"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<? 
if($install_status!=1){
    IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/wtc.easydirect/admin/wtc_easydirect_templates_create_ads.php");    
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"ERROR",
            "MESSAGE" => GetMessage("EASYDIRECT_TMPL_CREATE_demo"),
            "DETAILS"=> GetMessage("EASYDIRECT_TMPL_CREATE_demo_txt"),
        )
    );        
}
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?php CJSCore::Init(array("jquery"));?>
<script language="JavaScript">
$.ajaxSetup({cache: false}); 

var groupCNT=1;
var bannerCNT=1;

//count
function inputcount(element,min,max){
	var idcount;
	var valuelength;
	idcount="count"+$(element).attr("class");
	var strvalue=$(element).attr("value");
	//delete punctuation, cnt str length
	valuelength=(strvalue.replace(/[\.\,\:\;\"\!\?\#]/g, "")).length;
	
	if($(element).next().attr("id")=="count"+$(element).attr("class")){
		if(valuelength>max||valuelength<min) {
			$("#"+idcount).html(valuelength);
			$("#"+idcount).css("color","red");
			$("#"+idcount).css("font-weight","bold");
			$("#"+idcount).css("font-size","large");
		}
		else {
			$("#"+idcount).html(valuelength);
			$("#"+idcount).css("color","black");
			$("#"+idcount).css("font-weight","normal");
			$("#"+idcount).css("font-size","12px");
		}
	}
	else{	
		var cntnote;
		idcount="count"+$(element).attr("class");
		if(min>0) cntnote=min+"-"+max;
		else cntnote=max;
		$(element).after('<span id="'+idcount+'">'+valuelength+'</span>('+cntnote+')');
		if(valuelength>max||valuelength<min) {
			$("#"+idcount).css("color","red");
			$("#"+idcount).css("font-weight","bold");
			$("#"+idcount).css("font-size","large");
		}		
	}
}

//links cnt
function linkscount(element,min,max,block){
	inputcount(element,min,max);
	var cnt=0;
	var maxlength=<?=$arSitelinksParam["maxlength"]?>;
	if(block==2){
    	for(i=4;i<8;i++){
    		cnt+=($(".Sitelink"+i).attr("value")).length;
    	}		
	}
	else{
    	for(i=0;i<4;i++){
    		cnt+=($(".Sitelink"+i).attr("value")).length;
    	}
	}
	$("#linkscntall"+block).html(cnt+"<?=$arSitelinksParam["maxlengthnote"]?>");
	if(cnt>maxlength) $("#linkscntall"+block).css("color","red");
	else $("#linkscntall"+block).css("color","black");
}

//change first form
function changetocompany(val){
	if(val=='createnew') {
	    $('#companyname').show();
	    $('#basecompany').show();
	}
	else{
	    $('#companyname').hide();
	    $('#basecompany').hide();
	}
}

//change user file in banner
function changeuserfile(curel,val){
	if(val=='new_user_file') {
	    $(curel).nextAll("input:file").show();
	}
	else{
		$(curel).nextAll("input:file").hide();
	}
}

//work with banners
function delgroup(id){
	$("#group"+id).remove();
}
function addgroup(id){
	var str='';
	<?
	$arStr=showBannerGroup(array(),0,$CType);
	foreach ($arStr as $key=>$val){
        $arStr[$key]=trim(preg_replace("/>([^>]*)</".(EDIRECT_UTFSITE?"u":""), "><", $val));
    }
    ?>
	str+='<tr  id="group'+groupCNT+'">';
	str+='<td><?=$arStr[1]?><div></div></td>';	
	str+='<td><?=$arStr[2]?></td>';
	str+='<td><?=$arStr[3]?></td></tr>';
	$("#group"+id).before(str);
	addbanner(groupCNT);
	groupCNT++;
}

function delbanner(id){
	$("#banner"+id).remove();
}
function addbanner(groupId){
	var str='';
	<?
	$arStr=showBanner(array(),0,$CType);
	$arStr=trim(preg_replace("/>([^>]*)</".(EDIRECT_UTFSITE?"u":""), "><", $arStr));
    ?>
	str+='<?=$arStr?>';	
	$("#group"+groupId).find("td:first").find("div:first").before(str);
	bannerCNT++;
}

//------------BUTTONS-----------
function showReplaceForm(){
	$('#tbl_settings').toggle();	
	$('#tbl_autoreplace').toggle();
}
//-------------------------------------

$(document).ready(function() {
	$("input").keyup();
	$("textarea").keyup();
	<?=(isset($_POST["autoreplace"])?"showReplaceForm();":"")?>
	<?if(isset($_POST["addtocompany"])) echo "changetocompany($('#addtocompany').attr('value'));"; ?>
});
</script>

<?
if(count($arGroupsVivod)==0&&!isset($_REQUEST["loadData"])) {
    CAdminMessage::ShowMessage(array(
    "MESSAGE"=>GetMessage("EASYDIRECT_CREATE_create_words"),
    "DETAILS"=> GetMessage("EASYDIRECT_CREATE_create_words_mess"),
    "TYPE"=>"ERROR",
    "HTML"=>true
    ));
}
//replace message
if(isset($_POST["autoreplace"])){
    CAdminMessage::ShowMessage(array(
        "MESSAGE"=>GetMessage("EASYDIRECT_CREATE_ar_mess_title"),
        "DETAILS"=> GetMessage("EASYDIRECT_CREATE_ar_mess",array("#CNT#"=>$replaceCnt)),
        "TYPE"=>"OK",
        "HTML"=>true
    ));
}
?>
<?//==============BUTTONS==========================?>
<div class="adm-list-table-top">
    <?if(CEDirectCompany::saveDataInFile("isset")):?>
	<a href="wtc_easydirect_create_company.php?loadData=Y" class="adm-btn" title="<?=GetMessage("EASYDIRECT_CREATE_BUT_load_data_info")?>"><?=GetMessage("EASYDIRECT_CREATE_BUT_load_data")?></a>
	<?endif; ?> 
	<a href="#stayhere" onclick="showReplaceForm()" class="adm-btn" title="<?=GetMessage("EASYDIRECT_CREATE_BUT_autoreplace")?>"><?=GetMessage("EASYDIRECT_CREATE_BUT_autoreplace")?></a>
</div>
<?//==============FIN BUTTONS========================?>

<form enctype="multipart/form-data" action="" method="post" name="form1">
<input type="hidden" name="CType" value="<?=$CType?>">
<input type="hidden" name="ID" value="<?=$CID?>">

  <table>
        <!----AUTOREPLACE---->
        <tr><td>
            <table id="tbl_autoreplace" style="display: none;" class="wtc-easydirect-show-data-table">
                <tr><th colspan="2"><?=GetMessage("EASYDIRECT_CREATE_BUT_autoreplace")?></th></tr>
                <tr>
                    <td><?=GetMessage("EASYDIRECT_CREATE_ar_field")?>:</td>
                    <td>
                		<select name="ar_field">
                			<option value="all"><?=GetMessage("EASYDIRECT_CREATE_ar_field_all")?></option>
                			<option value="sl" <?=($_POST["ar_field"]=="sl"?"selected":"")?>><?=GetMessage("EASYDIRECT_CREATE_ar_field_1")?></option>
                			<option value="title" <?=($_POST["ar_field"]=="title"?"selected":"")?>><?=GetMessage("EASYDIRECT_CREATE_ar_field_2")?></option>
                			<option value="text" <?=($_POST["ar_field"]=="text"?"selected":"")?>><?=GetMessage("EASYDIRECT_CREATE_ar_field_3")?></option>
                			<option value="phrases" <?=($_POST["ar_field"]=="phrases"?"selected":"")?>><?=GetMessage("EASYDIRECT_CREATE_ar_field_4")?></option>
                			<option value="display_url" <?=($_POST["ar_field"]=="display_url"?"selected":"")?>><?=GetMessage("EASYDIRECT_CREATE_ar_field_5")?></option>
                			<option value="href" <?=($_POST["ar_field"]=="href"?"selected":"")?>><?=GetMessage("EASYDIRECT_CREATE_ar_field_6")?></option>
                		</select>                        
                    </td>
                </tr>
                <tr>
                    <td><?=GetMessage("EASYDIRECT_CREATE_ar_replacements")?></td>
                    <td><textarea rows="10" cols="50" name="ar_replacements"><?=$_POST["ar_replacements"]?></textarea></td>
                </tr>      
                <tr>
                    <td colspan="2">
                        <a href="#stayhere" onclick="showReplaceForm()" class="adm-btn"><< <?=GetMessage("EASYDIRECT_CREATE_ar_button_back")?></a>&nbsp;
                        <input style="float:right;" type="submit" name="autoreplace" value="<?=GetMessage("EASYDIRECT_CREATE_ar_button_repl")?>">
                    </td>
                </tr>
            </table>            
        </td></tr>
        <!----SETTINGS---->
        <tr><td>
            <table id="tbl_settings" class="wtc-easydirect-show-data-table">
                <tr><th colspan="2"><?=GetMessage("EASYDIRECT_CREATE_mainparamtitle")?></th></tr>
        		<tr>
            		<td><?=GetMessage("EASYDIRECT_CREATE_addtocompany")?>:</td>
            		<td>
                		<select id="addtocompany" name="addtocompany" onChange="changetocompany(this.value)">
                			<option value="createnew"><?=GetMessage("EASYDIRECT_CREATE_addtocompany_def")?></option>
                    		<?php
                        		$rsData = CEDirectCompany::GetList(array("NAME"=>"ASC"), array(), false, array("ID","NAME"));
                        		while($arRes = $rsData->Fetch()){
                        			echo '<option '.(($arRes['ID']==$_POST["addtocompany"])?"selected":"").' value="'.$arRes['ID'].'">'.$arRes['NAME'].'</option>';
                        		}
                    		?>
                		</select>
                		<br>
                		<table class="no-border" id="basecompany">
                		  <tr>
                		      <td>
                		          <input name="forSEARCH" value="Y" type="checkbox" <?=((!isset($_POST["forSEARCH"])||$_POST["forSEARCH"]=="Y")?"checked":"")?>> <?=GetMessage("EASYDIRECT_CREATE_addtocompany_forsearch")?>
                		      </td>
                		      <td>
                		        <?=GetMessage("EASYDIRECT_CREATE_basecompany")?>:
                        		<select name="basecompany">
                        			<option value="default"><?=GetMessage("EASYDIRECT_CREATE_basecompany_def")?></option>
                            		<?php
                            		$rsData = CEDirectCompany::GetList(array("NAME"=>"ASC"), array("IS_RSYA"=>"N"), false, array("ID","NAME"));
                            		while($arRes = $rsData->Fetch()){
                            			echo '<option '.(($arRes['ID']==$arCParams["BASECOMPANY"])?"selected":"").' value="'.$arRes['ID'].'">'.$arRes['NAME'].'</option>';
                            		}
                            		?>
                        		</select>
                		      </td>
               		      </tr>                 		
                		  <tr>
                		      <td>
                		          <input name="forRSYA" value="Y" type="checkbox" <?=((!isset($_POST["forRSYA"])||$_POST["forRSYA"]=="Y")?"checked":"")?>> <?=GetMessage("EASYDIRECT_CREATE_addtocompany_forrsya")?>
                		      </td>
                		      <td>
                		        <?=GetMessage("EASYDIRECT_CREATE_basecompany")?>:
                        		<select name="basecompany_rsya">
                        			<option value="default"><?=GetMessage("EASYDIRECT_CREATE_basecompany_def")?></option>
                            		<?php
                            		$rsData = CEDirectCompany::GetList(array("NAME"=>"ASC"), array("IS_RSYA"=>"Y"), false, array("ID","NAME"));
                            		while($arRes = $rsData->Fetch()){
                            			echo '<option '.(($arRes['ID']==$arCParams["BASECOMPANY_RSYA"])?"selected":"").' value="'.$arRes['ID'].'">'.$arRes['NAME'].'</option>';
                            		}
                            		?>
                        		</select>
                		      </td>
               		      </tr>              		      
                		</table>
            		</td>
        		</tr>      		
        		<tr id="companyname"><td><?=GetMessage("EASYDIRECT_CREATE_companyname")?>:<font color="red">*</font></td><td><input type="text" name="name" size="51" value="<?=$arCParams["NAME"]?>"></td></tr>		  
        		<tr><td><?=GetMessage("EASYDIRECT_CREATE_price")?>:<font color="red">*</font></td><td><input type="text" name="price" size="2" value="<?=$arCParams["PRICE"]?>"></td></tr>
        		<tr><td><?=GetMessage("EASYDIRECT_CREATE_href")?>:<font color="red">*</font></td><td><input type="text" name="href" size="51" value="<?=$arCParams["HREF"]?>"></td></tr>
        		<tr><td><?=GetMessage("EASYDIRECT_CREATE_display_url")?>:</td><td><input type="text" onKeyUp="inputcount(this,0,20)" name="display_url" size="30" value="<?=$arCParams["DISPLAY_URL"]?>"></td></tr>		
        		<tr><td><?=GetMessage("EASYDIRECT_CREATE_add_utm")?>:</td><td><input name="addUtm" value="Y" type="checkbox" <?=((isset($_POST["addUtm"])&&$_POST["addUtm"]=="Y")?"checked":"")?>> <?=GetMessage("EASYDIRECT_CREATE_add_utm_check_info")?></td></tr>		
        		<tr><td><?=GetMessage("EASYDIRECT_CREATE_minuswords")?></td><td><textarea rows="2" cols="60" name="minuswords"><?=$arCParams["MINUSWORDS"]?></textarea></td></tr>	
        		<tr>
        		    <td><?=GetMessage("EASYDIRECT_CREATE_img")?>:</td>
        		    <td>
    		    		<?
    		    		echo \Bitrix\Main\UI\FileInput::createInstance(array(
    		    		    "name" => "rsya_img",
    		    		    "description" => false,
    		    		    "upload" => true,
    		    		    "allowUpload" => "I",
    		    		    "medialib" => true,
    		    		    "fileDialog" => true,
    		    		    "cloud" => false,
    		    		    "delete" => true,
    		    		    "maxCount" => 1
    		    		))->show($_POST["rsya_img"],1);
                		?>
        		      <br><input name="defImgForAll" value="Y" type="checkbox" <?=((isset($_POST["defImgForAll"])&&$_POST["defImgForAll"]=="Y")?"checked":"")?>><?=GetMessage("EASYDIRECT_CREATE_img_forall")?>
        		    </td>
        		</tr>        		
        		<?if(!isset($_REQUEST["loadData"])): ?>	
        		<tr><td>
            		<?=GetMessage("EASYDIRECT_CREATE_update")?>
            	</td><td>
            		<input name="delSpec" value="Y" type="checkbox"><?=GetMessage("EASYDIRECT_CREATE_update_delspec")?><br>
                	<input name="groupBanners" value="Y" type="checkbox"><?=GetMessage("EASYDIRECT_CREATE_update_group")?><br><br>
        		    <input type="submit" name="btnUpdate" value="<?=GetMessage("EASYDIRECT_CREATE_update_btn")?>">
        		</td></tr>		
        		<?endif; ?> 
            </table>
        </td></tr>
		
		<tr><td><br>
		<div class="adm-list-table-top">
    		<b><?=GetMessage("EASYDIRECT_CREATE_links")?></b> / 
    		<?=GetMessage("EASYDIRECT_CREATE_links_copy")?>
    		<select name="siteliksfrom">
    			<option value="">---- <?=GetMessage("EASYDIRECT_CREATE_links_copy_comp")?> ----</option>
        		<?php
        		$rsData = CEDirectCompany::GetList(array("NAME"=>"ASC"), array(), false, array("ID","NAME"));
        		while($arRes = $rsData->Fetch()){
        			echo '<option value="comp_'.$arRes['ID'].'">  '.$arRes['NAME'].'</option>';
        		}
        		?>
                <option value="">---- <?=GetMessage("EASYDIRECT_CREATE_links_copy_tmpl")?> ----</option>		
        		<?
        		$rsData = CEDirectTemplates::GetList(array("NAME"=>"ASC"),array(),false, array("ID","NAME"));            		
        		while($arRes = $rsData->Fetch()){
        			echo '<option value="tmpl_'.$arRes['ID'].'">  '.$arRes['NAME'].'</option>';
        		}
        		?>
    		</select>    	
        	<input type="submit" name="loadsitelinks" value="<?=GetMessage("EASYDIRECT_CREATE_links_copy_btn")?>">
    	</div>
		<?
			$obTbl=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_CREATE_links_block1")." / ".GetMessage("EASYDIRECT_CREATE_links_tbl1")." <span id='linkscntall1'></span>",GetMessage("EASYDIRECT_CREATE_links_tbl2"),GetMessage("EASYDIRECT_CREATE_links_tbl3")), $arSitelinksVivod);
			echo $obTbl->ShowTbl();
			unset($obTbl);
		?>
		<?
			$obTbl=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_CREATE_links_block2")." / ".GetMessage("EASYDIRECT_CREATE_links_tbl1")." <span id='linkscntall2'></span>",GetMessage("EASYDIRECT_CREATE_links_tbl2"),GetMessage("EASYDIRECT_CREATE_links_tbl3")), $arSitelinksVivodB2);
			echo $obTbl->ShowTbl();
			unset($obTbl);
		?>
		<br>
		</td></tr>			
		<tr><td>
		<? 
			$obTbl=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_CREATE_tbl1"),GetMessage("EASYDIRECT_CREATE_tbl2"),GetMessage("EASYDIRECT_CREATE_tbl3")), $arGroupsVivod);
			echo $obTbl->ShowTbl();
			unset($obTbl);
		?>
		</td></tr>
		<tr><td><br>
		<input type="submit" name="saveBanners" value="<?=GetMessage("EASYDIRECT_CREATE_button_save")?>">&nbsp;&nbsp;&nbsp;&nbsp;		
		<input type="submit" class="adm-btn-green" onClick="return confirm('<?=GetMessage("EASYDIRECT_CREATE_alert")?>')" name="sendBanners" value="            <?=GetMessage("EASYDIRECT_CREATE_button")?>            "><br>
		</td></tr>
  </table> 
</form>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>