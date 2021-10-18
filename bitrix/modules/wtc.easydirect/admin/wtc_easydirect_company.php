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
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/wtc.easydirect/options_status.php");

//get POST_RIGHT
$POST_RIGHT = $APPLICATION->GetGroupRight("wtc.easydirect");
//Check POST_RIGHT
if ($POST_RIGHT < "R")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$sTableID = "tbl_ed_company"; // Table ID
$oSort = new CAdminSorting($sTableID, "ID", "desc"); // sort object
$lAdmin = new CAdminList($sTableID, $oSort);  // main list object


// ******************************************************************** //
//													 FILTER															 //
// ******************************************************************** //

// build filer fields
$FilterArr = Array(
    "find_name",
	"find_id",
    "find_is_rsya",
    "find_not_check_seo",    
	"find_active",
	"find_in_google",
	"find_metod",
);
$lAdmin->InitFilter($FilterArr);

// create a filter array for get from DB
$arFilter = Array(
	"NAME"		=> "%".$find_name."%",
	"ID"		=> $find_id,
	"ACTIVE"	=> $find_active,
	"IN_GOOGLE"	=> $find_in_google,
    "IS_RSYA"	=> $find_is_rsya,
    "NOT_CHECK_SEO"	=> $find_not_check_seo    
);

if($find_metod&&!$arFilter['ID']){
	$arComp=array();
	$rsData = CEDirectMcondition::GetList(array(), array("ID_METOD"=>$find_metod), array("ID_COMPANY"), array("ID_COMPANY"));
	while($arRes = $rsData->Fetch())
		$arComp[]=$arRes['ID_COMPANY'];
	
	if(count($arComp)>0) $arFilter['ID']=$arComp;
	else $arFilter['ID']=0;
}

// ******************************************************************** //
//								EXEC OPERATION of the ITEMS												//
// ******************************************************************** //

// save edit elements
if($lAdmin->EditAction() && $POST_RIGHT=="W")
{
	// each edit items
	foreach($FIELDS as $ID=>$arFields)
	{
		if(!$lAdmin->IsUpdated($ID))
			continue;
		
		// save all changed items
		$DB->StartTransaction();
		if( is_numeric($ID) && ($rsData = CEDirectCompany::GetByID($ID)) && ($arData = $rsData->Fetch()) )
		{
		    $arNewFields=array();
			foreach($arFields as $key=>$value){
				// speshial function for activate
				if($key=="ACTIVE") {
					if(!CEDirectCompany::setActive($ID,$value))
						$lAdmin->AddGroupError(GetMessage("EASYDIRECT_COMAPNY_active_error"), $ID);
				}
				else $arNewFields[$key]=$value;
			}
				
			if(!CEDirectCompany::Update($ID, $arNewFields))
			{
				$lAdmin->AddGroupError(GetMessage("EASYDIRECT_COMAPNY_save_error"), $ID);
				$DB->Rollback();
			}
		}
		else
		{
			$lAdmin->AddGroupError(GetMessage("EASYDIRECT_COMAPNY_save_error")." ".GetMessage("EASYDIRECT_COMAPNY_no_event"), $ID);
			$DB->Rollback();
		}
		$DB->Commit();
	}
}

// exec personal and group operotions
if(($arID = $lAdmin->GroupAction()) && $POST_RIGHT=="W")
{
	// if exec for all items
	if($_REQUEST['action_target']=='selected')
	{
		$rsData = CEDirectCompany::GetList(array($by=>$order), $arFilter);
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

	// each check items
	$arIDs=array();
	foreach($arID as $ID)
	{
		if( strlen($ID)<=0 || !is_numeric($ID) )
			continue;
		
		// EXEC OPERATION
		switch($_REQUEST['action'])
		{
			// update, delete, export and set activate company
		    //it is mass operation, all change in boottom
			case "delete":
			case "update":
		    case "activate":
		    case "deactivate":	
	        case "exportgoogle":
			 	$arIDs[]=$ID;
		 	break;		 
		 	
		 	//change Price
		 	case "izmprice":
		 	    if($_REQUEST['izmprice_value']>0) {
		 	        $value=$_REQUEST['izmprice_value'];
		 	        if($_REQUEST['izmprice_deystv']=="down")	$value=$value*-1;
		 	        
		 	        //get all banner groups
		 	        $arGrops=array();
		 	        $res=CEDirectBannerGroup::GetList(array(),array("ID_COMPANY"=>$ID));
		 	        while ($arGroup = $res->Fetch()){
		 	            $arGrops[]=$arGroup["ID"];
		 	        }
		 	        
		 	        $rsDataCond = CEDirectMcondition::GetListEx(array(), array("LOGIC"=>"OR","MCONDITION.ID_COMPANY"=>$ID,"MCONDITION.ID_BANNER_GROUP"=>$arGrops));
		 	        while($arCond = $rsDataCond->Fetch()){
		 	            $arFields['MAX_PRICE']=$arCond['MAX_PRICE']+$value;
		 	            CEDirectMcondition::Update($arCond["ID"],$arFields);
		 	        }
		 	    }
	 	    break;		 	
		}
	}
	
	//mass operation exec
	switch($_REQUEST['action'])
	{
	    //delete company
	    case "delete":
	        $_SESSION["EDIRECT_COMPANY_IDS"]=$arIDs;
	        $_SESSION["EDIRECT_COMPANY_IDS_CNT"]=0;
	        echo '<script language="JavaScript">window.top.execCompanyAction("deleteCompanies");</script>';	        
	    break;
	    
		// update company
		case "update":
		    $_SESSION["EDIRECT_COMPANY_IDS"]=$arIDs;
		    $_SESSION["EDIRECT_COMPANY_IDS_CNT"]=0;
		    echo '<script language="JavaScript">window.top.execCompanyAction("updateCompanies");</script>';
		break;		
		
		//set active
		case "activate":
		case "deactivate":
		    $_SESSION["EDIRECT_COMPANY_IDS"]=$arIDs;
		    $_SESSION["EDIRECT_COMPANY_IDS_CNT"]=0;
		    if($_REQUEST['action']=="activate"){
		        echo '<script language="JavaScript">window.top.execCompanyAction("activateCompanies");</script>';
		    }
		    else{
		        echo '<script language="JavaScript">window.top.execCompanyAction("deactivateCompanies");</script>';		        
		    }
		break;		
		
		//export to google
		case "exportgoogle":
		    $url=CEDirectCompany::exportCompaniesToGoogle($arIDs);
		    CAdminMessage::ShowMessage(array(
        	    "MESSAGE"=>GetMessage("EASYDIRECT_COMAPNY_creategoogle"),
        	    "DETAILS"=> GetMessage("EASYDIRECT_COMAPNY_creategoogle_mess",array("#URLTOFILE#"=>$url.'?r='.rand())),
        	    "TYPE"=>"OK",
        	    "HTML"=>true
    	     ));
	    break;
	}	
}

// ******************************************************************** //
//								GET LIST FROM DB															//
// ******************************************************************** //

// GET DATA FROM DB
$rsData =CEDirectCompany::GetList(array($by=>$order), $arFilter);

// convert result to instance of class CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

//PAGE NAVIGATION
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("EASYDIRECT_COMAPNY_nav")));

// ******************************************************************** //
//								PREPERE & BUILD LIST														//
// ******************************************************************** //

$lAdmin->AddHeaders(array(
	array(	
		"id"		=>"ID",
		"content"	=>"ID",
		"sort"		=>"id",
		"align"		=>"right",
		"default"	=>true,
	),
	array(	
		"id"		=>"ACTIVE",
		"content"	=>GetMessage("EASYDIRECT_COMAPNY_active"),
		"sort"		=>"active",
		"default"	=>true,
	),
	array(
		"id"		=>"IN_GOOGLE",
		"content"	=>GetMessage("EASYDIRECT_COMAPNY_google"),
		"sort"		=>"in_google",
		"default"	=>true,
	),
    array(
        "id"		=>"IS_RSYA",
        "content"	=>GetMessage("EASYDIRECT_COMAPNY_rsya"),
        "sort"		=>"is_rsya",
        "default"	=>true,
    ),    
    array(
        "id"		=>"NOT_CHECK_SEO",
        "content"	=>GetMessage("EASYDIRECT_COMAPNY_not_check_seo"),
        "sort"		=>"not_check_seo",
        "default"	=>true,
    ),
	array(	
		"id"		=>"NAME",
		"content"	=>GetMessage("EASYDIRECT_COMAPNY_name"),
		"sort"		=>"name",
		"default"	=>true,
	),
	array(
		"id"		=>"USLOVIYA",
		"content"	=>GetMessage("EASYDIRECT_COMAPNY_usloviya"),
		"sort"		=>"",
		"default"	=>true,
	),    
    array(
        "id"		=>"ID_CATALOG_ITEM",
        "content"	=>GetMessage("EASYDIRECT_COMAPNY_catalog_item"),
        "sort"		=>"",
        "default"	=>true,
    ),
	array(
		"id"		=>"BET_DATE",
		"content"	=>GetMessage("EASYDIRECT_COMAPNY_datestavk"),
		"sort"		=>"bet_date",
		"default"	=>true,
	),
	array(
		"id"		=>"STATUS",
		"content"	=>GetMessage("EASYDIRECT_COMAPNY_status"),
		"sort"		=>"status",
		"default"	=>true,
	),
    array(
        "id"		=>"IMPORT_DATE",
        "content"	=>GetMessage("EASYDIRECT_COMAPNY_importdate"),
        "sort"		=>"import_date",
        "default"	=>true,
    ),    
));

while($arRes = $rsData->NavNext(true, "f_",false)):
	
	// create line. Result - instance of class CAdminListRow
	$row =& $lAdmin->AddRow($f_ID, $arRes); 
	
    //check is manual strategy
    $manualStrategy=false;
    if(CEDirectCompany::isManualStrategy($f_STRATEGY_TYPE)){
        $manualStrategy=true;
    }

    //ID
    $strStat="";
    if($manualStrategy) {$strStat='<br><a target="_blank" href="/bitrix/admin/wtc_easydirect_stat.php?company='.$f_ID.'">'.GetMessage("EASYDIRECT_COMAPNY_stat").'</a>';}
    $row->AddViewField("ID", $f_ID.$strStat);

	// NAME edit as text and show as URL
	$row->AddInputField("NAME", array("size"=>20));
	$row->AddViewField("NAME", '<a href="wtc_easydirect_company_edit.php?ID='.$f_ID.'&lang='.LANG.'">'.$f_NAME.'</a>');

	// ID_CATALOG_ITEM
	if(EDIRECT_IS_CATALOG_INTEGRATION){
    	if($f_ID_CATALOG_ITEM>0){
    	    $res=CEDirectCatalogItems::GetByID($f_ID_CATALOG_ITEM,array("NAME"));
    	    $ar_res = $res->Fetch();
    	    $row->AddViewField("ID_CATALOG_ITEM", $ar_res["NAME"]);
    	}
    	else $row->AddViewField("ID_CATALOG_ITEM", GetMessage("EASYDIRECT_COMAPNY_catalog_item_auto"));
	}
	else {$row->AddViewField("ID_CATALOG_ITEM","-");}
	
	
	// flag edit as checkbox
	$row->AddCheckField("ACTIVE"); 
	$row->AddCheckField("IN_GOOGLE");
	$row->AddCheckField("IS_RSYA");
	$row->AddCheckField("NOT_CHECK_SEO");
	
	//-----------DATES---------
	$row->AddViewField("IMPORT_DATE", CAllEDirectTable::DateToSiteFormat($f_IMPORT_DATE));
	//BET_DATE
	if($manualStrategy) {$row->AddViewField("BET_DATE", CAllEDirectTable::DateToSiteFormat($f_BET_DATE));}
	else {$row->AddViewField("BET_DATE","-");}
	
	
	//------CONDITIONs--------
	if(!$manualStrategy){$row->AddViewField("USLOVIYA", GetMessage("EASYDIRECT_COMAPNY_usloviya_auto"));}
	else {
    	$vivod=array();
    	$vivodBan=array();
    	//get all banner groups
    	$arGrops=array();
    	$res=CEDirectBannerGroup::GetList(array(),array("ID_COMPANY"=>$f_ID));
    	while ($arGroup = $res->Fetch()){
    	    $arGrops[]=$arGroup["ID"];
    	}
    	
    	$rsDataCond = CEDirectMcondition::GetListEx(array("ID_COMPANY"=>"DESC","FROM_HOUR"=>"ASC"), array("LOGIC"=>"OR","MCONDITION.ID_COMPANY"=>$f_ID,"MCONDITION.ID_BANNER_GROUP"=>$arGrops));
    	while($arCond = $rsDataCond->Fetch())
    	{
    	    $str=array(
    	        $arCond['FROM_HOUR']." - ".$arCond['TO_HOUR'],
    	        $arCond['MAX_PRICE'],
    	        $arCond['METOD_NAME']
    	    );
    	    	
    	    if($arCond["ID_BANNER_GROUP"]) {
    	        $vivodBan[]=$str;    	         
    	    }
    	    else {
    	        $MetodID=$arCond['ID_METOD'];
    	        $vivod[]=$str;
    	    }
    	}
    	if( (count($vivod)+count($vivodBan))==1 && $MetodID==EDIRECT_NULLMETOD ) {
    	    $row->AddViewField("USLOVIYA", "-");
    	}
    	else{
        	$obTbl=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_COMAPNY_met_tbl1"),GetMessage("EASYDIRECT_COMAPNY_met_tbl2"),GetMessage("EASYDIRECT_COMAPNY_met_tbl3")), $vivod);
        	$condBanner="";
        	if(count($vivodBan)){
        	    $condBanner=GetMessage("EASYDIRECT_COMAPNY_banner_cond");
        	    $obTbl1=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_COMAPNY_met_tbl1"),GetMessage("EASYDIRECT_COMAPNY_met_tbl2"),GetMessage("EASYDIRECT_COMAPNY_met_tbl3")), $vivodBan);
        	    $condBanner.=$obTbl1->ShowTbl();
        	    unset($obTbl1);
        	}
        	$row->AddViewField("USLOVIYA", $obTbl->ShowTbl() . $condBanner);
        	unset($obTbl);
    	}
	}
	
	//------ BUILD context menu-----
	$arActions = Array();

	if ($POST_RIGHT>="W"){
		// EDIT ELEMENT
		$arActions[] = array(
			"ICON"=>"edit",
			"DEFAULT"=>true,
			"TEXT"=>GetMessage("EASYDIRECT_COMAPNY_POST_cmen_edit"),
			"ACTION"=>$lAdmin->ActionRedirect("wtc_easydirect_company_edit.php?ID=".$f_ID)
		);
		
		// MANUAL COMPANY UPDATE
		$arActions[] = array(
			"ICON"=>"btn_download",
			"TEXT"=>GetMessage("EASYDIRECT_COMAPNY_POST_cmen_update"),
			"ACTION"=>$lAdmin->ActionDoGroup($f_ID, "update")
		);		
		
		//COPY company
		$arActions[] = array(
			"ICON"=>"copy",
			"DEFAULT"=>true,
			"TEXT"=>GetMessage("EASYDIRECT_COMAPNY_POST_cmen_rsya"),
			"ACTION"=>$lAdmin->ActionRedirect("wtc_easydirect_create_company.php?ID=".$f_ID."&CType=YA")
		);		
		
		//Export to Google
		$arActions[] = array(
		    "ICON"=>"copy",
		    "TEXT"=>GetMessage("EASYDIRECT_COMAPNY_ADMIN_LIST_TOGOOGLE"),
		    "ACTION"=>$lAdmin->ActionDoGroup($f_ID, "exportgoogle")
		);		
		
		// DELETE ELEMENT
		$arActions[] = array(
			"ICON"=>"delete",
			"TEXT"=>GetMessage("EASYDIRECT_COMAPNY_POST_cmen_del"),
			"ACTION"=>"if(confirm('".GetMessage('EASYDIRECT_COMAPNY_POST_cmen_del_conf')."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
		);
	}
	
	// ADD context menu to ELEMENT
	$row->AddActions($arActions);	
	
endwhile;

// Group Action
if ($POST_RIGHT>="W"){
    $lAdmin->AddGroupActionTable(Array(
    	"delete"=>GetMessage("MAIN_ADMIN_LIST_DELETE"),
    	"activate"=>GetMessage("MAIN_ADMIN_LIST_ACTIVATE"),
    	"deactivate"=>GetMessage("MAIN_ADMIN_LIST_DEACTIVATE"),
        "exportgoogle"=>GetMessage("EASYDIRECT_COMAPNY_ADMIN_LIST_TOGOOGLE"), //export to google
    	"update"=>GetMessage("EASYDIRECT_COMAPNY_ADMIN_LIST_UPDATE"), //update
        "izmprice"=>GetMessage("EASYDIRECT_COMAPNY_ADMIN_LIST_IZMPRICE") //change prices
    	),
        array("select_onchange"=>"if(this.value=='izmprice') getizmfield();")        
        );
}

// ******************************************************************** //
//							ADMINISTRATION MENU															 //
// ******************************************************************** //
	
// BUILD ADMIN BUTTONS
$aContext = array(
	array(
		"TEXT"=>GetMessage("EASYDIRECT_COMAPNY_add"),
		"LINK"=>"wtc_easydirect_company_import.php?lang=".LANG,
		"TITLE"=>GetMessage("EASYDIRECT_COMAPNY_add"),
		"ICON"=>"btn_new",
	),
);

// ADD BUTTONS TO MENU
$lAdmin->AddAdminContextMenu($aContext);

// ******************************************************************** //
//								SHOW DATA																		 //
// ******************************************************************** //

$lAdmin->CheckListMode();

// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_COMAPNY_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

//---------------------
//check is token work
if(strlen(EDIRECT_TOKEN)>3){
    if(!$obYaExchange->ping()&&$obYaExchange->getTokenError()){
        CAdminMessage::ShowMessage(
            Array(
                "TYPE"=>"ERROR",
                "MESSAGE" => GetMessage("EASYDIRECT_TOKEN_AUTH_ERR")
            )
        );
    }
}
//------check ED agent execute------
if(CEDirectCron::isEDAgentNotRun()){
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"ERROR",
            "MESSAGE" => GetMessage("EASYDIRECT_AGENT_ERR"),
            "DETAILS"=> GetMessage("EASYDIRECT_AGENT_ERR_TXT"),
            "HTML"=>true
        )
    );
}

//check install_status
//demo (2)
if ( $install_status == 2 )
{
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"OK",
            "MESSAGE" => GetMessage("EASYDIRECT_status_demo"),
            "DETAILS"=> GetMessage("EASYDIRECT_buy_html"),
            "HTML"=>true
        )
    );
}
//demo expired (3)
elseif( $install_status == 3 )
{
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"ERROR",
            "MESSAGE" => GetMessage("EASYDIRECT_status_demo_expired"),
            "DETAILS"=> GetMessage("EASYDIRECT_buy_html"),
            "HTML"=>true
        )
    );
}
//-------------------------
// ******************************************************************** //
//								JSCRIPTS															 //
// ******************************************************************** //
?>
<?CJSCore::Init(array("jquery"));?>
<script language="JavaScript">
$.ajaxSetup({cache: false});

function getizmfield()
{
	var html;
	html="<div class=\"adm-table-item-edit-wrap\" id=\"getizmfield\"><table>";
	html+="<td><select name=\"izmprice_deystv\"><option value=\"up\"><?=GetMessage("EASYDIRECT_COMAPNY_ADMIN_IZMPRICE_UP")?></option><option value=\"down\"><?=GetMessage("EASYDIRECT_COMAPNY_ADMIN_IZMPRICE_DOWN")?></option></select></td>";
	html+="<td>&nbsp;<?=GetMessage("EASYDIRECT_COMAPNY_ADMIN_IZMPRICE_TO")?> <input type=\"text\" name=\"izmprice_value\" size=\"5\" value=\"\"></td>";
	html+="</tr></table></div>";
	if(!$('#form_tbl_ed_company #getizmfield').html()) $('#tbl_ed_company_footer').find('.adm-select-wrap').after(html);
}

function execCompanyAction(cmd){
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_company_exec.php",
			{CMD: cmd}, 
			function(data){
				$('.edirect-progress').html(data);
			}
			);	
}
function reloadTable(){
	window['<?=$sTableID?>'].GetAdminList('/bitrix/admin/wtc_easydirect_company.php?lang=<?=LANG?>');
}
</script>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>
<?
// ******************************************************************** //
//								FILTER SHOW																	 //
// ******************************************************************** //
// create filter object
$oFilter = new CAdminFilter(
	$sTableID."_filter",
	array(
		GetMessage("EASYDIRECT_COMAPNY_f_id"),							
	    GetMessage("EASYDIRECT_COMAPNY_f_is_rsya"),
	    GetMessage("EASYDIRECT_COMAPNY_f_not_check_seo"),
		GetMessage("EASYDIRECT_COMAPNY_f_active"),
		GetMessage("EASYDIRECT_COMAPNY_f_google"),
		GetMessage("EASYDIRECT_COMAPNY_f_metod"),
	)
);
?>
<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
<?$oFilter->Begin();?>
<tr>
	<td><?=GetMessage("EASYDIRECT_COMAPNY_f_name")?>:</td>
	<td>
		<input type="text" name="find_name" size="27" value="<?echo htmlspecialcharsbx($find_name)?>">
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_COMAPNY_f_id")?>:</td>
	<td>
		<input type="text" name="find_id" size="27" value="<?=$find_id?>">
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_COMAPNY_f_is_rsya")?>:</td>
	<td>
		<?
		$arr = array(
			"reference" => array(
				GetMessage("EASYDIRECT_COMAPNY_POST_YES"),
				GetMessage("EASYDIRECT_COMAPNY_POST_NO"),
			),
			"reference_id" => array(
				"Y",
				"N",
			)
		);
		echo SelectBoxFromArray("find_is_rsya", $arr, $find_is_rsya, GetMessage("EASYDIRECT_COMAPNY_POST_ALL"), "");
		?>
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_COMAPNY_f_not_check_seo")?>:</td>
	<td>
		<?
		$arr = array(
			"reference" => array(
				GetMessage("EASYDIRECT_COMAPNY_POST_YES"),
				GetMessage("EASYDIRECT_COMAPNY_POST_NO"),
			),
			"reference_id" => array(
				"Y",
				"N",
			)
		);
		echo SelectBoxFromArray("find_not_check_seo", $arr, $find_not_check_seo, GetMessage("EASYDIRECT_COMAPNY_POST_ALL"), "");
		?>
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_COMAPNY_f_active")?>:</td>
	<td>
		<?
		$arr = array(
			"reference" => array(
				GetMessage("EASYDIRECT_COMAPNY_POST_YES"),
				GetMessage("EASYDIRECT_COMAPNY_POST_NO"),
			),
			"reference_id" => array(
				"Y",
				"N",
			)
		);
		echo SelectBoxFromArray("find_active", $arr, $find_active, GetMessage("EASYDIRECT_COMAPNY_POST_ALL"), "");
		?>
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_COMAPNY_f_google")?>:</td>
	<td>
		<?
		$arr = array(
			"reference" => array(
				GetMessage("EASYDIRECT_COMAPNY_POST_YES"),
				GetMessage("EASYDIRECT_COMAPNY_POST_NO"),
			),
			"reference_id" => array(
				"Y",
				"N",
			)
		);
		echo SelectBoxFromArray("find_in_google", $arr, $find_in_google, GetMessage("EASYDIRECT_COMAPNY_POST_ALL"), "");
		?>
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_COMAPNY_f_metod")?>:</td>
	<td>
		<select name="find_metod">
			<option value=""><?=GetMessage("EASYDIRECT_COMAPNY_POST_ALL")?></option>
		<?php
		$rsData = CEDirectMetod::GetList(array("SORT"=>"ASC"), array(), false, array("ID","NAME"));
		while($arRes = $rsData->Fetch()){
			echo '<option '.(($arRes['ID']==$find_metod)?"selected":"").' value="'.$arRes['ID'].'">'.$arRes['NAME'].'</option>';
		}
		?>
		</select>
	</td>
</tr>
<?
$oFilter->Buttons(array("table_id"=>$sTableID,"url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
$oFilter->End();
?>
</form>

<?
//progress bar
echo '<div class="edirect-progress"></div>';

// SHOW LIST
$lAdmin->DisplayList();
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>