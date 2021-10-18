<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

//module include
CModule::IncludeModule("wtc.easydirect");
IncludeModuleLangFile(__FILE__);

//get POST_RIGHT
$POST_RIGHT = $APPLICATION->GetGroupRight("wtc.easydirect");
//Check POST_RIGHT
if ($POST_RIGHT < "W")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

// init Tabs
$aTabs = array(
  array("DIV" => "edit1", "TAB" => GetMessage("EDIRECT_EDIT_tab1name"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("EDIRECT_EDIT_tab1name")),
  array("DIV" => "edit2", "TAB" => GetMessage("EDIRECT_EDIT_tab2name"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("EDIRECT_EDIT_tab2name"))
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if( !is_numeric($ID) ) $ID = 0;
$message = null; //error message
$bVarsFromForm = false; // if data come from form, not from DB

// ******************************************************************** //
//               PROCESSING FORM'S DATA                             //
// ******************************************************************** //

if(
	$REQUEST_METHOD == "POST"
	&&
	($save!="" || $apply!="")
	&&
	$POST_RIGHT=="W"
	&&
	check_bitrix_sessid()
)
{
  //-----MOVE banners-----
  if( $ID>0 && $_POST["TO_GROUP_ID"]>0 && count($_POST["unionGroupIDs"])>0 ){
        $key=array_search($_POST["TO_GROUP_ID"], $_POST["unionGroupIDs"]);
        if($key!==FALSE){
            unset($_POST["unionGroupIDs"][$key]);
        }
        if(count($_POST["unionGroupIDs"])>0){
            $cntErr=CEDirectBannerGroup::moveGroupsInYa($_POST["unionGroupIDs"],$_POST["TO_GROUP_ID"]);
            CEDirectCompany::import($ID,true);
            if($cntErr>0){
                $message = new CAdminMessage(GetMessage("EDIRECT_COMAPNY_EDIT_group_union_err"));
            }
        }
        else $message = new CAdminMessage(GetMessage("EDIRECT_COMAPNY_EDIT_group_union_select_err"));
  }    
  //----------------------
    
  //---SAVE DATA------  
  // main data
  $arFields = Array(
    "NAME"    => $NAME,
    "IN_GOOGLE"    => ($IN_GOOGLE <> "Y"? "N":"Y"),
    "NOT_CHECK_SEO"    => ($NOT_CHECK_SEO <> "Y"? "N":"Y")
  );
  
  // prepare Fields
  if($ID > 0)
  {
  	//condition items
  	//delete
  	if(count($_POST['dellcond'])){
  		foreach ($_POST['dellcond'] as $value){
  			CEDirectMcondition::delete($value);
  		}
  	}
  	// add/change condition
  	if(count($_POST['cond'])){
	  	foreach ($_POST['cond'] as $val){
	  		if($val['metod']){
		  		$arFieldsCond=array(
		  				"FROM_HOUR" => $val['from'],
		  				"TO_HOUR" => $val['to'],
		  				"MAX_PRICE" => $val['max'],
		  				"ID_METOD" => $val['metod']
		  		);
		  		
		  		if($val['id']>0){
		  			CEDirectMcondition::update($val['id'],$arFieldsCond);
		  		}
		  		else{
		  			if($val['companyid']>0) $arFieldsCond["ID_COMPANY"]=$val['companyid'];
		  			if($val['bannerid']>0) $arFieldsCond["ID_BANNER_GROUP"]=$val['bannerid'];	  			
		  			CEDirectMcondition::add($arFieldsCond);
		  		}
	  		}
	  	}
  	}
  	
  	// company active
  	CEDirectCompany::setActive($ID,($ACTIVE <> "Y"? "N":"Y"));
  	
  	//if change not_check_seo - clear all info in phrases
  	if( is_numeric($ID) && ($rsData = CEDirectCompany::GetByID($ID)) && ($arData = $rsData->Fetch()) )
  	{
        if($arFields["NOT_CHECK_SEO"]=="Y"&&$arData["NOT_CHECK_SEO"]!=$arFields["NOT_CHECK_SEO"]) {
            $arFieldsPhrase=array(
                "MESTO_SEO"=>0,
                "CHECK_MESTO_DATE" => ConvertTimeStamp(AddToTimeStamp(array("DD"=>"-30")),"FULL")
            );
            $DB->StartTransaction();
            $res=CEDirectPhrase::GetListEx(array("ID"=>"ASC"),array("COMPANY.ID"=>$ID),array("PHRASE.ID"),array("PHRASE.ID","COMPANY.ID"));
            while($ar_res=$res->Fetch()) {
                CEDirectPhrase::Update($ar_res['ID'],$arFieldsPhrase);
            }
            $DB->Commit();
        }
  	}
  	
  	//---ID_CATALOG_ITEM-------
  	if($ID_CATALOG_ITEM>0||$ID_IB_ITEM>0){
  	    if($ID_IB_ITEM!=$OLD_ID_IB_ITEM){
  	        if(!$ID_IB_ITEM){ //delete ID_CATALOG_ITEM
  	            $arFields["ID_CATALOG_ITEM"]="NULL";
  	        }
  	        else if($IBLOCK_ID>0){
  	            //if isset change to isset ID
  	            $res=CEDirectCatalogItems::GetList(array(),array("IBLOCK_ELEMENT_ID"=>$ID_IB_ITEM,"IBLOCK_ID"=>$IBLOCK_ID,"IS_SECTION"=>"N"),false, array("ID"));
  	            if($ar_res=$res->Fetch()) {
  	                $arFields["ID_CATALOG_ITEM"]=$ar_res["ID"];
  	            }
  	            //create new catalog element
  	            else{
  	                $arCatalogElementInfo=CEDirectCatalogItems::getIBlockElementInfo($IBLOCK_ID,$ID_IB_ITEM,"ELEMENT_ID");
  	                if(count($arCatalogElementInfo)>0){
  	                    $IDCatalogItem=CEDirectCatalogItems::Add($arCatalogElementInfo);
  	                    if($IDCatalogItem>0) {
  	                        $arFields["ID_CATALOG_ITEM"]=$IDCatalogItem;
  	                    }
  	                }
  	            }
  	        }
  	        //update banners ID_CATALOG_ITEM if change ID_CATALOG_ITEM in company
  	        if($arFields["ID_CATALOG_ITEM"]>0||$arFields["ID_CATALOG_ITEM"]=="NULL"){
  	            $rsBanner = CEDirectBanner::GetListEx(array("BANNER.ID"=>"ASC"), array("BANNER_GROUP.ID_COMPANY"=>$ID),false,array("BANNER.ID","BANNER.ID_CATALOG_ITEM","BANNER_GROUP.ID_COMPANY"));
  	            while($arBanner = $rsBanner->Fetch())
  	            {
  	                if($arBanner["ID_CATALOG_ITEM"]!=$arFields["ID_CATALOG_ITEM"]){
  	                    CEDirectBanner::Update($arBanner["ID"], array("ID_CATALOG_ITEM"=>$arFields["ID_CATALOG_ITEM"]));
  	                }
  	            }
  	        }  	        
  	    }
  	    else {
  	        $arFields["ID_CATALOG_ITEM"]=$ID_CATALOG_ITEM;
  	    }
  	}
  	//--------------------------------------------
  	
  }
  	 
  if(!$message){
      //save changes
      if($ID > 0) $res = CEDirectCompany::Update($ID, $arFields);
      if($res) //if OK
      {
        if ($apply != "")
          LocalRedirect("/bitrix/admin/wtc_easydirect_company_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&".$tabControl->ActiveTabParam());
        else
          LocalRedirect("/bitrix/admin/wtc_easydirect_company.php?lang=".LANG);
      }
      else // if error
      {
        if($e = $APPLICATION->GetException())
          $message = new CAdminMessage(GetMessage("EDIRECT_EDIT_save_error"), $e);
        $bVarsFromForm = true;
      }
  }
}
//UPDATE Catalog Integration
else if($_POST["updateCompanyIntegration"]){
    if($ID > 0){
        if(CEDirectCatalogItems::rebuildCompanyCatalogIntegration($ID)){
            $message = new CAdminMessage(array("MESSAGE"=>GetMessage("EDIRECT_EDIT_catalog_integration_update_ok"),"TYPE"=>"OK"));
        }
        else {
            $message = new CAdminMessage(GetMessage("EDIRECT_EDIT_catalog_integration_update_err"));
        }
    }
}
else if($save!="" || $apply!="")  {
      $message = new CAdminMessage(GetMessage("EDIRECT_EDIT_noob_error"));
}

// ******************************************************************** //
//                GET DATA                     //
// ******************************************************************** //

// from BD
if($ID>0&&CEDirectCompany::IsEmpty($ID))
{
  $company = CEDirectCompany::GetByID($ID);
  if(!$company->ExtractFields("str_",false))
    $ID=0;
}
else {
    $ID=0;
}

// if data receive from form
if($bVarsFromForm)
  $DB->InitTableVarsForEdit("b_list_company", "", "str_");

// ******************************************************************** //
//               SHOW FORM                                           //
// ******************************************************************** //

// set title
$APPLICATION->SetTitle(GetMessage("EDIRECT_EDIT_title_edit").": ".$str_NAME);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// admin buttons
$aMenu = array(
  array(
    "TEXT"=>GetMessage("EDIRECT_EDIT_menu_list"),
    "TITLE"=>GetMessage("EDIRECT_EDIT_menu_list"),
    "LINK"=>"wtc_easydirect_company.php?lang=".LANG,
    "ICON"=>"btn_list",
  )
);

$context = new CAdminContextMenu($aMenu);
$context->Show();
?>

<?
// show messages
if($ID==0){
    CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("EDIRECT_EDIT_notfind"), "TYPE"=>"ERROR"));
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");    
    exit();
}

if($_REQUEST["mess"] == "ok" && $ID>0)
  CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("EDIRECT_EDIT_saved"), "TYPE"=>"OK"));

if($message)
  echo $message->Show();
?>

<? //-------GET IMPORTANT PARAMS-------------
//check is manual strategy
$manualStrategy=false;
if(CEDirectCompany::isManualStrategy($str_STRATEGY_TYPE)){
    $manualStrategy=true;
}

// get metods array
$metods=array();
if($str_IS_RSYA=="Y") $arFilter=array("TYPE"=>array("RSYA","UNI"));
else $arFilter=array("TYPE"=>array("SEARCH","UNI"));
$resm=CEDirectMetod::GetList(array(),$arFilter,false,array("ID","NAME"));
while ($arMetod=$resm->GetNext()) {
	$metods[]=$arMetod;
}
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?php CJSCore::Init(array("jquery"));?>
<script language="JavaScript">
$.ajaxSetup({cache: false}); 

function setBannerActive(id)
{
    $('#loadHBDdata').hide();
}

function showbet(id){
	$("#ss"+id).empty();
	$("#tbl"+id).show();
}
function showBannerBet(id){
	$("#banner"+id).find(".openprice").empty();
	$("#banner"+id).find(".prices").show();
}
function showAllBet(){
	$("#bannerTbl").find(".openprice").empty();
	$("#bannerTbl").find(".prices").show();
}

//condition
var teknomer=0;

function condBannerEdit(id){
	$('#condBanner'+id).hide();
	$('#condBannerEditShow'+id).hide();
	$('#condtable0-'+id).show();
	$('#condBannerEditAdd'+id).show();
}

function dellnew(id){
	$('#condn'+id).empty();
}
function dellcond(id){
	$('#cond'+id).empty();
	$('#cond'+id).html('<input type="hidden" name="dellcond[]" value="'+id+'">');
}
		
function addcond(companyid,bannerid){
	var addtext='';

	addtext+='<tr id="condn'+ teknomer+'">';
	addtext+='<td><input type="text" name="cond['+ teknomer+'][from]" size="3" maxlenght="2" value="0"></td>';
	addtext+='<td><input type="text" name="cond['+ teknomer+'][to]" size="3" maxlenght="2" value="24"></td>';
	addtext+='<td><input type="text" name="cond['+ teknomer+'][max]" size="5" value=""></td>';
	addtext+='<td>';
		addtext+='<select name="cond['+ teknomer+'][metod]">';
			<?
			foreach  ($metods as $value) {
					echo 'addtext+=\'<option value="'.$value['ID'].'">'.$value['NAME'].'</option>\';';
			}												
			?>				
		addtext+='</select>';		
	addtext+='<input type="hidden" name="cond['+ teknomer+'][companyid]" value="'+companyid+'">';
	addtext+='<input type="hidden" name="cond['+ teknomer+'][bannerid]" value="'+bannerid+'">';
	addtext+='</td>';
	addtext+='<td><a onclick="dellnew('+ teknomer+')" style="color:red;" href="#stayhere"><?=GetMessage("EDIRECT_COMAPNY_EDIT_delete")?></a></td>';
	addtext+='</tr>';
	
	$('#condtable'+companyid+'-'+bannerid).append(addtext);

	teknomer++;
}

//showUnionGroup
function showUnionGroup(){
	$("#bannersButtons").hide();
	$("#unionGroups").show();
	$("#bannerTbl").find(".group-checkbox").show();
}
</script>
<form method="POST" Action="<?echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="post_form">
<?// check session ?>
<?echo bitrix_sessid_post();?>
<?
// tabs start
$tabControl->Begin();
?>
<?
//********************
// first tab - banners
//********************
$tabControl->BeginNextTab();
?>
  <tr>
    <td colspan="2">
    <div id="bannersButtons">
        <a href="#stayhere" onclick="showUnionGroup()" class="adm-btn">&Sigma; <?=GetMessage("EDIRECT_COMAPNY_EDIT_group_union_btn")?></a>&nbsp;
        <?if($manualStrategy):?>
        <a target="_blank" href="/bitrix/admin/wtc_easydirect_stat.php?company=<?=$str_ID?>" class="adm-btn"><?=GetMessage("EDIRECT_COMAPNY_EDIT_company_stat")?> &rarr;</a>
        <?endif;?>
    </div>
    <div id="unionGroups" style="display: none;">
        <?=GetMessage("EDIRECT_COMAPNY_EDIT_group_union_select")?>:
		<select name="TO_GROUP_ID">
			<option selected value="NULL"><?=GetMessage("EDIRECT_COMAPNY_EDIT_group_union_select_def")?></option>
    		<?
    		$rsData = CEDirectBannerGroup::GetList(array(),array("ID_COMPANY"=>$str_ID));
    		while($arRes = $rsData->Fetch()){
    			echo '<option value="'.$arRes['ID'].'">'.$arRes['NAME'].' (Id: '.$arRes['ID'].')'.'</option>';
    		}
    		?>
		</select>            
    </div>
    <br>
<? 
	$vivod=array();
	$rsData = CEDirectBannerGroup::GetList(array("ID"=>"ASC"), array("ID_COMPANY"=>$str_ID));
	$rsData->NavStart(40);
	while($arElement = $rsData->Fetch())
	{
		$arStr=array();
		
		//ankor to group from instruments
		if($_GET["GID"]==$arElement["ID"]) $arStr[1].="<a name=\"group\"></a>";
		
		//Show Group Info
		$arStr[1].='<br>';
		$arStr[1].='<span class="group-checkbox" style="display: none;"><input name="unionGroupIDs[]" value="'.$arElement['ID'].'" type="checkbox"></span> '.$arElement['NAME'].' (Id: '.$arElement['ID'].')';
		if($manualStrategy) $arStr[1].='<br><a target="_blank" href="/bitrix/admin/wtc_easydirect_stat.php?bannergroup='.$arElement['ID'].'">'.GetMessage("EDIRECT_COMAPNY_EDIT_bannergroup_stat").'</a>';
		if($arElement['SERVING_STATUS']=="RARELY_SERVED") $arStr[1].='<br><span style="color:red;">'.GetMessage("EDIRECT_COMAPNY_EDIT_bannergroup_lowshows").'</span><br>';
		$arStr[1].='<hr>';
		
		//=======banners=========
		$i=0;
		$rsBanner = CEDirectBanner::GetListEx(array("BANNER.ACTIVE"=>"DESC","BANNER.ID"=>"ASC"), array("BANNER.ID_BANNER_GROUP"=>$arElement['ID']));
		while($arBanner = $rsBanner->Fetch())
		{
		    if($i>0) $arStr[1].="<br>";
		    if($arBanner['ACTIVE']!="Y") $disabled=' noactive';
		    else $disabled="";

		    $BImage="";
		    $arImage=CAllEDirectTable::UnSerializeArrayField($arBanner["IMAGE"]);
		    if(strlen($arImage["AdImageURL"])>4) $BImage='<a target="_blank" href="'.$arImage["AdImageURL"].'">'.GetMessage("EDIRECT_EDIT_banner_img").'</a>';
		    
		    //calculate DISPLAY_URL
		    $DISPLAY_URL=$arBanner['HREF'];
		    if(strlen($arBanner['DISPLAY_URL'])>2) {
		        preg_match("/\:\/\/([^\/]*)\/(.*)/",$arBanner['HREF'],$regs);
		        $DOMEN=$regs[1];		        
		        $DISPLAY_URL=$DOMEN."/".$arBanner['DISPLAY_URL'];
		    }
		    
    		$arStr[1].='
    		<table class="banner'.$disabled.'">';
    		if(EDIRECT_IS_CATALOG_INTEGRATION){
    		    if($arBanner['CATALOG_ITEM_ID']>0){
        		  $arBanner['CATALOG_ITEM_NAME']=TruncateText($arBanner['CATALOG_ITEM_NAME'],30);
        		  if($arBanner['CATALOG_ITEM_IS_SECTION']=="Y") $arBanner['CATALOG_ITEM_NAME']="<".$arBanner['CATALOG_ITEM_NAME'].">";
        		  $arStr[1].='<tr><td>&harr;<span> ['.$arBanner['CATALOG_ITEM_ID'].'] '.$arBanner['CATALOG_ITEM_NAME'].'</span></td></tr>';
    		    }
    		    else $arStr[1].='<tr><td><font color="red">&harr; !?</font></td></tr>';
    		}
    		$arStr[1].='
            <tr><td><span>BannerId: '.$arBanner['ID'].'</span>&nbsp;&nbsp;'.$BImage.'</td></tr>
    		<tr><td class="title">
    		<a target="_blank" href="'.$arBanner['HREF'].'">'.$arBanner['TITLE'].'</a>
    		</td></tr>
    		<tr><td class="title2">
    		'.$arBanner['TITLE2'].'
    		</td></tr>    
    		<tr><td class="text">'.$arBanner['TEXT'].'
    		</td></tr>';
    		if($arBanner['PRICE']>0) $arStr[1].='<tr><td><span>'.GetMessage("EDIRECT_EDIT_banner_price").': '.$arBanner['PRICE'].'</span></td></tr>';
    		$arStr[1].='
    		<tr><td class="sitelinks">';
        		$arSiteLinks=CAllEDirectTable::UnSerializeArrayField($arBanner["SITELINKS"]);
        		foreach ($arSiteLinks as $val){
        		    $arStr[1].='<a href="'.$val["Href"].'">'.$val["Title"]."</a> ";
        		}    		        		
    		$arStr[1].='
    		</td></tr>
    		<tr><td class="href">'.$DISPLAY_URL.'
    		</td></tr>
    		</table>';

    		$i++;
    		//if($arElement['ACTIVE']=="Y") {$stop=GetMessage("EDIRECT_COMAPNY_EDIT_deactivate");}
    		//else {$stop=GetMessage("EDIRECT_COMAPNY_EDIT_activate"); $arStr["Props"]=array("class"=>"noactive");}
    		//<br><a href="?id='.$_GET['id'].'&idbanners='.$arElement['ID'].'&cmd=activ">'.$stop.'</a>';
		}
		$arStr[1].='<hr>';
		//---condition items of banners----
		if($manualStrategy){
    		$vivodCond=array();
    		$vivodCondEdit=array();
    		$rsDataCond = CEDirectMcondition::GetListEx(array("ID"=>"ASC"), array("MCONDITION.ID_BANNER_GROUP"=>$arElement['ID']));
    		while($arCond = $rsDataCond->Fetch())
    		{
    			//only show
    			$vivodCond[]=array(
    					$arCond['FROM_HOUR']." - ".$arCond['TO_HOUR'],
    					$arCond['MAX_PRICE'],
    					$arCond['METOD_NAME'],
    			);
    
    			//for edit
    			$metodSelect='<select name="cond['.$arCond['ID'].'][metod]">';
    			foreach  ($metods as $value) {
    				$metodSelect.='<option ';
    				if($value['ID']==$arCond['ID_METOD']) $metodSelect.= 'selected ';
    				$metodSelect.= 'value="'.$value['ID'].'">'.$value['NAME'].'</option>';
    			}
    			$metodSelect.= '</select>';
    			$vivodCondEdit[]=array(
    					"Props"=>array("id"=>"cond".$arCond["ID"]),
    					'<input type="text" name="cond['.$arCond['ID'].'][from]" size="3" maxlenght="2" value="'.$arCond['FROM_HOUR'].'">',
    					'<input type="text" name="cond['.$arCond['ID'].'][to]" size="3" maxlenght="2" value="'.$arCond['TO_HOUR'].'">',
    					'<input type="text" name="cond['.$arCond['ID'].'][max]" size="5" value="'.$arCond['MAX_PRICE'].'">',
    					$metodSelect,
    					'<input type="hidden" name="cond['.$arCond['ID'].'][id]" value="'.$arCond['ID'].'">
    							<a onclick="dellcond('.$arCond['ID'].')" style="color:red;" href="#stayhere">'.GetMessage("EDIRECT_COMAPNY_EDIT_delete").'</a>'
    			);			
    		}
    
    		if(count($vivodCond)){
    			$obTblCond=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_COMAPNY_met_tbl1"),
    																				GetMessage("EASYDIRECT_COMAPNY_met_tbl2"),
    																				GetMessage("EASYDIRECT_COMAPNY_met_tbl3")),
    																	$vivodCond, 
    																	array("id"=>"condBanner".$arElement['ID']));
    			$arStr[1].= $obTblCond->ShowTbl();
    			unset($obTblCond);
    		}
    
    		$arStr[1].='<a onclick="condBannerEdit('.$arElement['ID'].')" id="condBannerEditShow'.$arElement['ID'].'" href="#stayhere">'.GetMessage("EDIRECT_COMAPNY_EDIT_edit_conditions").'</a>';
    		$obTblCond=new CEDirectShowTbl(
    				array(GetMessage("EASYDIRECT_COMAPNY_metedit_tbl1"),
    						GetMessage("EASYDIRECT_COMAPNY_metedit_tbl2"),
    						GetMessage("EASYDIRECT_COMAPNY_metedit_tbl3"),
    						GetMessage("EASYDIRECT_COMAPNY_metedit_tbl4"),
    						GetMessage("EASYDIRECT_COMAPNY_metedit_tbl5")),
    				$vivodCondEdit,
    				array("id"=>"condtable0-".$arElement['ID'],"class"=>"condBanerEdit"));
    		$arStr[1].=$obTblCond->ShowTbl();
    		$arStr[1].='<a onclick="addcond(0,'.$arElement['ID'].')" id="condBannerEditAdd'.$arElement['ID'].'" href="#stayhere" class="condBanerEdit">'.GetMessage("EDIRECT_COMAPNY_EDIT_add_condition").'</a>';
    		$arStr[1].='<br><br>';
    		unset($obTblCond);		
		}
		//------------------------------------------
		
		//=======Phrases=========
		$resp=CEDirectPhrase::GetList(array("NAME"=>"ASC","ID"=>"ASC"),array("ID_BANNER_GROUP"=>$arElement['ID']));
		$vivodPhrases=array();
		$isHereSearchPhrase=0;
		while ($arPhrase=$resp->Fetch()) {
				
		    if($manualStrategy && $arPhrase['NAME']!="---autotargeting"){
    			$prices="<a class='openprice' id='ss".$arPhrase['ID']."' onclick='showbet(\"".$arPhrase['ID']."\")' href='#stayhere'>".GetMessage("EDIRECT_COMAPNY_EDIT_show")."</a><table id='tbl".$arPhrase['ID']."' class='prices'><tr><td>";
    			if($str_IS_RSYA=="Y"){
    			    $arPhrase['SHOWS']=$arPhrase['CONTEXTSHOWS'];
    			    $arPhrase['CLICKS']=$arPhrase['CONTEXTCLICKS'];
    			    $arPhrase['PRICE']=$arPhrase['CONTEXTPRICE'];
    			    $PRICES=CEDirectPhrase::UnSerializeArrayField($arPhrase['CONTEXTCOVERAGE']);
    			    if(count($PRICES)==0) {$prices.=" - ";}
    			    else{
    			        $selectCurrent=false;
        			    foreach ($PRICES as $value){
        			         $price=$value["Price"]." -> ".$value["Probability"]."%";
        			         if(!$selectCurrent&&$arPhrase['PRICE']>=$value["Price"]) {
        			             $prices.="<nobr class='currentPrice'>".$price."</nobr><br>";
        			             $selectCurrent=true;
        			         }
        			         else{
        			             $prices.="<nobr>".$price."</nobr><br>";
        			         }    			         
        			    }
    			    }
    			    $prices.="</td></tr>
        							<tr><td colspan='2'>";
        			$prices.='<a target="_blank" href="/bitrix/admin/wtc_easydirect_stat.php?phrase='.$arPhrase['ID'].'">'.GetMessage("EDIRECT_COMAPNY_EDIT_phrase_stat").'</a>';
        			$prices.="</td></tr></table>";
    			}
    			else{
        			$flags=array("sp"=>0,"gar"=>0);
        			$PRICES=CEDirectPhrase::UnSerializeArrayField($arPhrase['PRICES']);
        			$selectCurrent=false;
        			$arTraffic=array("P11"=>"100x","P12"=>"&nbsp;85x","P13"=>"&nbsp;75x","P14"=>"&nbsp;65x","P21"=>"15x","P22"=>"10x","P23"=>"&nbsp;5x");
        			foreach ($PRICES as $value){
        			    if(preg_match("/P2+/",$value["Position"])&&$flags['sp']==0) {$prices.="</td><td>"; $flags['sp']=1;}
        				$price="<small>".$arTraffic[$value["Position"]]."/".$value["Position"]."</small> ".$value["Bid"]." <span class='onserch'>(".$value["Price"].")</span>";
        				if(!$selectCurrent&&$arPhrase['PRICE']>=$value["Bid"]) {
        				    $prices.="<nobr class='currentPrice'>".$price."</nobr><br>";
        				    $selectCurrent=true;
        				}
        				else{
        				    $prices.="<nobr>".$price."</nobr><br>";    				    
        				}			
        			}
        			$prices.="</td></tr>
        							<tr><td colspan='2'>";
        			$prices.='<a target="_blank" href="/bitrix/admin/wtc_easydirect_stat.php?phrase='.$arPhrase['ID'].'">'.GetMessage("EDIRECT_COMAPNY_EDIT_phrase_stat").'</a>';
        			$prices.="</td></tr></table>";
    			}
		    }
		    else {
		        if($str_IS_RSYA=="Y"){
		            $arPhrase['SHOWS']=$arPhrase['CONTEXTSHOWS'];
		            $arPhrase['CLICKS']=$arPhrase['CONTEXTCLICKS'];
		            $arPhrase['PRICE']=$arPhrase['CONTEXTPRICE'];
		        }
		        $prices="-";
		        if(!$manualStrategy) $arPhrase['PRICE']="auto";
		        $arPhrase['PRICE_ON_SEARCH']="-";
		    }
			
			if($_GET["PID"]==$arPhrase["ID"]) $isHereSearchPhrase=1;
				
			$vivodPhrases[]=array(
					($_GET["PID"]==$arPhrase["ID"]?"<b>---></b> ".$arPhrase['NAME']:$arPhrase['NAME']),
					(MakeTimeStamp($arPhrase['CHECK_MESTO_DATE'], "YYYY-MM-DD HH:MI:SS")>AddToTimeStamp(array("DD"=>"-2")))?$arPhrase['MESTO_SEO']:"<span>-</span>",
					$arPhrase['SHOWS'],
					$arPhrase['CLICKS'],
					$arPhrase['SHOWS']?round(($arPhrase['CLICKS']*100)/$arPhrase['SHOWS'],2):0,
					$arPhrase['PRICE'].($str_IS_RSYA=="N"?"&nbsp;<span class='onserch'>(".$arPhrase['PRICE_ON_SEARCH'].")</span>":""),
					$prices
			);
		}
		
		if($isHereSearchPhrase) $arStr[2].="<a name='phrase'></a>";
		$strShowBets="";
		if($manualStrategy){$strShowBets='<br><a onclick="showBannerBet(\''.$arElement['ID'].'\')" href="#stayhere">'.GetMessage("EDIRECT_COMAPNY_EDIT_show_banners").'</a>';}
		$obTbl1=new CEDirectShowTbl(
															array(GetMessage("EDIRECT_EDIT_phrase_tbl1"),
																	GetMessage("EDIRECT_EDIT_phrase_tbl2"),
																	GetMessage("EDIRECT_EDIT_phrase_tbl3"),
																	GetMessage("EDIRECT_EDIT_phrase_tbl4"),
																	GetMessage("EDIRECT_EDIT_phrase_tbl5"),
																	GetMessage("EDIRECT_EDIT_phrase_tbl6"),
																	GetMessage("EDIRECT_EDIT_phrase_tbl7").$strShowBets
															), 
															$vivodPhrases,
															array("id"=>"banner".$arElement['ID'],"class"=>"phrases")
														);
		$arStr[2].=$obTbl1->ShowTbl();
		unset($obTbl1);
		
		$vivod[]=$arStr;
	}
	$strShowBets="";
	if($manualStrategy){$strShowBets=' <a onclick="showAllBet(\''.$arElement['ID'].'\')" href="#stayhere">'.GetMessage("EDIRECT_COMAPNY_EDIT_show_all").'</a>';}
	$obTbl=new CEDirectShowTbl(array(GetMessage("EDIRECT_EDIT_banner_tbl1"),GetMessage("EDIRECT_EDIT_banner_tbl2").$strShowBets), $vivod, array("id"=>"bannerTbl"));
	if($rsData->IsNavPrint())
	{
	    echo "<p>".$rsData->NavPrint(GetMessage("EDIRECT_COMAPNY_EDIT_banners_pagenav"))."</p>";
	}
	echo $obTbl->ShowTbl();
	if($rsData->IsNavPrint())
	{
	    echo "<p>".$rsData->NavPrint(GetMessage("EDIRECT_COMAPNY_EDIT_banners_pagenav"))."</p>";
	}
	unset($obTbl);
?>
	</td>
  </tr>
<?
//********************
// TAB 2 - PARAMETRS AND CONDITION ITEMS OF COMPANY
//********************
$tabControl->BeginNextTab();
?>
  <tr>
    <td width="40%"><?echo GetMessage("EDIRECT_EDIT_active")?></td>
    <td width="60%"><input type="checkbox" name="ACTIVE" value="Y"<?if($str_ACTIVE == "Y") echo " checked"?>></td>
  </tr>
  <tr>
    <td width="40%"><?echo GetMessage("EDIRECT_EDIT_not_check_seo")?></td>
    <td width="60%"><input type="checkbox" name="NOT_CHECK_SEO" value="Y"<?if($str_NOT_CHECK_SEO == "Y") echo " checked"?>></td>
  </tr>
  <tr>
    <td width="40%"><?echo GetMessage("EDIRECT_EDIT_in_google")?></td>
    <td width="60%"><input type="checkbox" name="IN_GOOGLE" value="Y"<?if($str_IN_GOOGLE == "Y") echo " checked"?>></td>
  </tr>
  <tr>
    <td><span class="required">*</span><?echo GetMessage("EDIRECT_EDIT_name")?></td>
    <td><input type="text" name="NAME" value="<?echo $str_NAME;?>" size="60" maxlength="100"></td>
  </tr>  
  <tr>
    <td width="40%" valign="top"><?echo GetMessage("EDIRECT_EDIT_cond")?></td>
    <td>
    <? 
    if($manualStrategy){
    	$vivod=array();
    	$rsDataCond = CEDirectMcondition::GetListEx(array("ID"=>"ASC"), array("MCONDITION.ID_COMPANY"=>$str_ID));
    	while($arCond = $rsDataCond->Fetch())
    	{
    		$metodSelect='<select name="cond['.$arCond['ID'].'][metod]">';
    		foreach  ($metods as $value) {
    			$metodSelect.='<option ';
    			if($value['ID']==$arCond['ID_METOD']) $metodSelect.= 'selected ';
    			$metodSelect.= 'value="'.$value['ID'].'">'.$value['NAME'].'</option>';
    		}
    		$metodSelect.= '</select>';		
    		$vivod[]=array(
    				"Props"=>array("id"=>"cond".$arCond["ID"]),
    				'<input type="text" name="cond['.$arCond['ID'].'][from]" size="3" maxlenght="2" value="'.$arCond['FROM_HOUR'].'">',
    				'<input type="text" name="cond['.$arCond['ID'].'][to]" size="3" maxlenght="2" value="'.$arCond['TO_HOUR'].'">',
    				'<input type="text" name="cond['.$arCond['ID'].'][max]" size="5" value="'.$arCond['MAX_PRICE'].'">',
    				$metodSelect,
    				'<input type="hidden" name="cond['.$arCond['ID'].'][id]" value="'.$arCond['ID'].'">
    				<a onclick="dellcond('.$arCond['ID'].')" style="color:red;" href="#stayhere">'.GetMessage("EDIRECT_COMAPNY_EDIT_delete").'</a>'
    		);
    	}
    	$obTbl=new CEDirectShowTbl(
    		array(GetMessage("EASYDIRECT_COMAPNY_metedit_tbl1"),
    					GetMessage("EASYDIRECT_COMAPNY_metedit_tbl2"),
    					GetMessage("EASYDIRECT_COMAPNY_metedit_tbl3"), 
    					GetMessage("EASYDIRECT_COMAPNY_metedit_tbl4"),
    					GetMessage("EASYDIRECT_COMAPNY_metedit_tbl5")),
    				$vivod,
    				array("id"=>"condtable".$str_ID."-0"));
    	echo $obTbl->ShowTbl();
    	echo '<a onclick="addcond('.$str_ID.',0)" href="#stayhere">'.GetMessage("EDIRECT_COMAPNY_EDIT_add_condition").'</a>';
    	unset($obTbl);
    }
    else{
        echo GetMessage("EDIRECT_EDIT_cond_auto");
    }
	?>
	</td>
  </tr>
  <?if(EDIRECT_IS_CATALOG_INTEGRATION): ?>
  <tr class="heading">
    	<td colspan="2"><?=GetMessage("EDIRECT_EDIT_catalog_integration_title")?></td>
  </tr>	  
  <tr>
	<td></td>
	<td>
		<?
    	echo BeginNote();
    	echo GetMessage("EDIRECT_EDIT_catalog_integration_type").": ";
    	echo "<b>".(($str_ID_CATALOG_ITEM>0)?GetMessage("EDIRECT_EDIT_catalog_integration_custom"):GetMessage("EDIRECT_EDIT_catalog_integration_auto"))."</b>";
    	if(!$str_ID_CATALOG_ITEM){
    	    $arInfo=CEDirectCatalogItems::getCompanyCatalogIntegrationInfo($str_ID);
    	    echo "<br>";
    	    echo ($arInfo["BANNERS_WITH_INT_CNT"]>0)?'<font color="green">'.$arInfo["BANNERS_WITH_INT_CNT"].'</font> ':'<font color="red">'.$arInfo["BANNERS_WITH_INT_CNT"].'</font> ';
            echo GetMessage("EDIRECT_EDIT_catalog_integration_info_1");
            echo ", ";
            echo ($arInfo["BANNERS_WITHOUT_INT_CNT"]>0)?'<font color="red">'.$arInfo["BANNERS_WITHOUT_INT_CNT"].'</font> ':'<font color="green">'.$arInfo["BANNERS_WITHOUT_INT_CNT"].'</font> ';
            echo GetMessage("EDIRECT_EDIT_catalog_integration_info_2");
            echo '<br><input type="submit" name="updateCompanyIntegration" value="'.GetMessage("EDIRECT_EDIT_catalog_integration_update_btn").'">';
    	}
    	echo EndNote();
    	?>
  </tr>  
  <tr>
    <td width="40%"><?echo GetMessage("EDIRECT_EDIT_catalog_integration_custom_set")?></td>
    <td width="60%">
          <?
          $cat_ID_IB_ITEM="";
          $cat_IBLOCK_ID="";
          $cat_NAME="";
          if($str_ID_CATALOG_ITEM>0){
              $res=CEDirectCatalogItems::GetByID($str_ID_CATALOG_ITEM);
              if($ar_res = $res->Fetch()){
                  $cat_IBLOCK_ID=$ar_res["IBLOCK_ID"];
                  $cat_ID_IB_ITEM=$ar_res["IBLOCK_ELEMENT_ID"];
                  $cat_NAME=$ar_res["NAME"];
              }
          }
          ?>
          <table cellpadding="0" cellspacing="0" border="0" class="nopadding" id="tbcc97f3de376052f608a911ee16406645">
          <tr><td>
          	  <select id="iblock_id" name="IBLOCK_ID">
        	  <?    
        		$res = CIBlock::GetList(Array(),Array("ID"=>CEDirectCatalogItems::getCatalogIBlockIDs(),"ACTIVE"=>"Y","CNT_ACTIVE"=>"Y"), false);
        		while($ar_res = $res->Fetch()){
        		    echo '<option '.(($ar_res['ID']==$cat_IBLOCK_ID)?"selected":"").' value="'.$ar_res['ID'].'">'.$ar_res['NAME'].'</option>';
        		}
        	  ?>
        	  </select> &nbsp;
       	  </td><td>
              <input name="ID_IB_ITEM" id="ID_IB_ITEM" value="<?=$cat_ID_IB_ITEM?>" size="5" type="text">
              <input type="button" value="..." onClick="jsUtils.OpenWindow('/bitrix/admin/iblock_element_search.php?lang=ru&amp;IBLOCK_ID='+document.getElementById('iblock_id').value+'&amp;n=ID_IB_ITEM&amp;iblockfix=y&amp;tableId=catalo_item_search', 900, 700);">
              &nbsp;<span id="ID_IB_ITEM_link"><?=$cat_NAME?></span>
          </td></tr>
          </table>
          <input type="hidden" name="OLD_ID_IB_ITEM" value="<?echo $cat_ID_IB_ITEM;?>">
          <input type="hidden" name="ID_CATALOG_ITEM" value="<?echo $str_ID_CATALOG_ITEM;?>">
    </td>
  </tr>  
  <?endif;?>  
  
<?
// form end, show buttons
$tabControl->Buttons(
  array(
    "disabled"=>($POST_RIGHT<"W"),
    "back_url"=>"wtc_easydirect_company.php?lang=".LANG,
  )
);
?>
<input type="hidden" name="lang" value="<?=LANG?>">
<?if($ID>0 && !$bCopy):?>
  <input type="hidden" name="ID" value="<?=$ID?>">
<?endif;?>
<?
// end tabs
$tabControl->End();
?>

<?
// show error message near input
$tabControl->ShowWarnings("post_form", $message);
?>

<?
// note
echo BeginNote();?>
<span class="required">*</span><?echo GetMessage("REQUIRED_FIELDS")?>
<?echo EndNote();?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>
