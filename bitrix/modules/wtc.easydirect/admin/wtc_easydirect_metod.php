<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) 2015-2016 The WebTechCom Studio. All Rights Reserved.
 */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

//module include
CModule::IncludeModule("wtc.easydirect");
IncludeModuleLangFile(__FILE__);

//get POST_RIGHT
$POST_RIGHT = $APPLICATION->GetGroupRight("wtc.easydirect");
//Check POST_RIGHT
if ($POST_RIGHT < "R")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$sTableID = "tbl_ed_metod"; // Table ID
$oSort = new CAdminSorting($sTableID, "SORT", "asc"); // sort object
$lAdmin = new CAdminList($sTableID, $oSort); // main list object

// ******************************************************************** //
//													 FILTER													 //
// ******************************************************************** //
// build filer fields
$FilterArr = Array(
	"find_name",
	"find_fname",
	"find_important",
	"find_user",
	"find_active"
);
$lAdmin->InitFilter($FilterArr);

// create a filter array for get from DB
$arFilter = Array(
	"NAME"	=> (($find_name)?"%".$find_name."%":""),
	"FNAME"	=> (($find_fname)?"%".$find_fname."%":""),
	"IS_IMPORTANT"	=> $find_important,
	"IS_USER"	=> $find_user,
	"ACTIVE"	=> $find_active
);


// ******************************************************************** //
//								EXEC OPERATION of the ITEMS							//
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
		$ID = IntVal($ID);
		if(($rsData = CEDirectMetod::GetByID($ID)) && ($arData = $rsData->Fetch()))
		{
			foreach($arFields as $key=>$value)
				$arData[$key]=$value;
				
			if(!CEDirectMetod::Update($ID, $arData))
			{
				$lAdmin->AddGroupError(GetMessage("EASYDIRECT_METOD_save_error"), $ID);
				$DB->Rollback();
			}
		}
		else
		{
			$lAdmin->AddGroupError(GetMessage("EASYDIRECT_METOD_save_error").", ".GetMessage("EASYDIRECT_METOD_no_element"), $ID);
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
		$rsData = CEDirectMetod::GetList(array($by=>$order), $arFilter, false, array("ID"));
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

	// each check items
	foreach($arID as $ID)
	{
		if(strlen($ID)<=0)
			continue;
		
		$ID = IntVal($ID);
		
		// EXEC OPERATION
		switch($_REQUEST['action'])
		{
		// delete
		case "delete":
			if(($rsData = CEDirectMetod::GetByID($ID)) && ($arData = $rsData->Fetch()) && ($arData["IS_USER"]=="Y")) // if method is system don't delete
			{
				$rsCnt=CEDirectMcondition::GetList(Array(), Array("ID_METOD"=>$ID),array());	//check dependency before delete
				$arCnt = $rsCnt->Fetch();
				if($arCnt['CNT']>0){
						$lAdmin->AddGroupError(GetMessage("EASYDIRECT_METOD_del_error").", ".GetMessage("EASYDIRECT_METOD_est_svazi"), $ID);
				}
				else{
					$DB->StartTransaction();
					if(!CEDirectMetod::Delete($ID))
					{
						$DB->Rollback();
						$lAdmin->AddGroupError(GetMessage("EASYDIRECT_METOD_del_error"), $ID);
					}
					$DB->Commit();
				}
			}
			else $lAdmin->AddGroupError(GetMessage("EASYDIRECT_METOD_del_error_sysmetod"), $ID);
		 break;

		// activate/deactivate
		case "activate":
		case "deactivate":
			if(($rsData = CEDirectMetod::GetByID($ID)) && ($arFields = $rsData->Fetch()))
			{
				$arFields["ACTIVE"]=($_REQUEST['action']=="activate"?"Y":"N");
				if(!CEDirectMetod::Update($ID, $arFields))
					$lAdmin->AddGroupError(GetMessage("EASYDIRECT_METOD_save_error"), $ID);
			}
			else
				$lAdmin->AddGroupError(GetMessage("EASYDIRECT_METOD_save_error").", ".GetMessage("EASYDIRECT_METOD_no_element"), $ID);
			break;
		
		}
	}
}

// ******************************************************************** //
//								GET LIST FROM DB															//
// ******************************************************************** //

// GET DATA FROM DB
$rsData =CEDirectMetod::GetList(array($by=>$order), $arFilter);

// convert result to instance of class CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

//PAGE NAVIGATION
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("EASYDIRECT_METOD_nav")));

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
		"id"		=>"SORT",
		"content"	=>GetMessage("EASYDIRECT_METOD_sort"),
		"sort"		=>"sort",
		"default"	=>true,
	),
	array(
		"id"		=>"NAME",
		"content"	=>GetMessage("EASYDIRECT_METOD_name"),
		"sort"		=>"name",
		"default"	=>true,
	),
	array(
		"id"		=>"FNAME",
		"content"	=>GetMessage("EASYDIRECT_METOD_fname"),
		"sort"		=>"",
		"default"	=>true,
	),
    array(
        "id"		=>"TYPE",
        "content"	=>GetMessage("EASYDIRECT_METOD_type"),
        "sort"		=>"",
        "default"	=>true,
    ),    
	array(	
		"id"		=>"IS_IMPORTANT",
		"content"	=>GetMessage("EASYDIRECT_METOD_important"),
		"sort"		=>"is_important",
		"default"	=>true,
	),
	array(	
		"id"		=>"IS_USER",
		"content"	=>GetMessage("EASYDIRECT_METOD_user"),
		"sort"		=>"is_user",
		"default"	=>true,
	),
	array(	
		"id"		=>"ACTIVE",
		"content"	=>GetMessage("EASYDIRECT_METOD_active"),
		"sort"		=>"active",
		"default"	=>true,
	),
	array(	
		"id"		=>"DESCRIPTION",
		"content"	=>GetMessage("EASYDIRECT_METOD_descript"),
		"sort"		=>"",
		"default"	=>true,
	),
));

$arTypes=CEDirectMetod::GetTypeArray();

while($arRes = $rsData->NavNext(true, "f_")):
	
	// create line. Result - instance of class CAdminListRow
	$row =& $lAdmin->AddRow($f_ID, $arRes); 
	
	// next build field for view and edit
	
	// NAME edit as text and show as URL
	$row->AddInputField("NAME", array("size"=>20));
	$row->AddViewField("NAME", '<a href="wtc_easydirect_metod_edit.php?ID='.$f_ID.'&lang='.LANG.'">'.$f_NAME.'</a>');

	// TYPE
	$row->AddViewField("TYPE",$arTypes[$f_TYPE]);
	
	// SORT edit as text
	$row->AddInputField("SORT", array("size"=>10));
		
	// flag edit as checkbox
	$row->AddCheckField("IS_IMPORTANT");
	$row->AddCheckField("ACTIVE"); 
	
	//is_user
	//$row->AddCheckField("IS_USER");
	$row->AddViewField("IS_USER", (($f_IS_USER=="Y")?GetMessage("EASYDIRECT_METOD_isuser"):GetMessage("EASYDIRECT_METOD_nouser")));
	
	
	// BUILD context menu
	$arActions = Array();

	if ($POST_RIGHT>="W"){
    	    // EDIT ELEMENT
    	    $arActions[] = array(
    	        "ICON"=>"edit",
    	        "DEFAULT"=>true,
    	        "TEXT"=>GetMessage("EASYDIRECT_METOD_cmen_edit"),
    	        "ACTION"=>$lAdmin->ActionRedirect("wtc_easydirect_metod_edit.php?ID=".$f_ID.'&lang='.LANG)
    	    );
    	    
    	    // DELETE ELEMENT
			$arActions[] = array(
				"ICON"=>"delete",
				"TEXT"=>GetMessage("EASYDIRECT_METOD_cmen_del"),
				"ACTION"=>"if(confirm('".GetMessage('EASYDIRECT_METOD_cmen_del_conf')."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
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
    ));
}

// ******************************************************************** //
//								ADMINISTRATION MENU										 //
// ******************************************************************** //
	
// BUILD ADMIN BUTTONS
$aContext = array(
	array(
		"TEXT"=>GetMessage("EASYDIRECT_METOD_add"),
		"LINK"=>"wtc_easydirect_metod_edit.php?lang=".LANG,
		"TITLE"=>GetMessage("EASYDIRECT_METOD_add"),
	),
    array(
        "TEXT"=>GetMessage("EASYDIRECT_METOD_install"),
        "LINK"=>"wtc_easydirect_metod_edit.php?CMD=install&lang=".LANG,
        "TITLE"=>GetMessage("EASYDIRECT_METOD_install"),
        "ICON"=>"btn_new",
    ),    
);

// ADD BUTTONS TO MENU
$lAdmin->AddAdminContextMenu($aContext);

// ******************************************************************** //
//								SHOW DATA																			 //
// ******************************************************************** //

$lAdmin->CheckListMode();
// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_METOD_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ******************************************************************** //
//								FILTER SHOW																	 //
// ******************************************************************** //

// create filter object
$oFilter = new CAdminFilter(
	$sTableID."_filter",
	array(
		GetMessage("EASYDIRECT_METOD_f_fname"),
	 	GetMessage("EASYDIRECT_METOD_f_important"),
		GetMessage("EASYDIRECT_METOD_f_user"),
		GetMessage("EASYDIRECT_METOD_f_active"),
	)
);
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
<?$oFilter->Begin();?>
<tr>
	<td><?=GetMessage("EASYDIRECT_METOD_f_name")?>:</td>
	<td>
		<input type="text" name="find_name" size="20" value="<?echo htmlspecialcharsbx($find_name)?>">
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_METOD_f_fname")?>:</td>
	<td>
		<input type="text" name="find_fname" size="20" value="<?echo htmlspecialcharsbx($find_fname)?>">
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_METOD_f_important")?>:</td>
	<td>
		<?
		$arr = array(
			"reference" => array(
				GetMessage("EASYDIRECT_METOD_POST_YES"),
				GetMessage("EASYDIRECT_METOD_POST_NO"),
			),
			"reference_id" => array(
				"Y",
				"N",
			)
		);
		echo SelectBoxFromArray("find_important", $arr, $find_important, GetMessage("EASYDIRECT_METOD_POST_ALL"), "");
		?>
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_METOD_f_user")?>:</td>
	<td>
		<?
		$arr = array(
			"reference" => array(
				GetMessage("EASYDIRECT_METOD_POST_YES"),
				GetMessage("EASYDIRECT_METOD_POST_NO"),
			),
			"reference_id" => array(
				"Y",
				"N",
			)
		);
		echo SelectBoxFromArray("find_user", $arr, $find_user, GetMessage("EASYDIRECT_METOD_POST_ALL"), "");
		?>
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_METOD_f_active")?>:</td>
	<td>
		<?
		$arr = array(
			"reference" => array(
				GetMessage("EASYDIRECT_METOD_POST_YES"),
				GetMessage("EASYDIRECT_METOD_POST_NO"),
			),
			"reference_id" => array(
				"Y",
				"N",
			)
		);
		echo SelectBoxFromArray("find_active", $arr, $find_active, GetMessage("EASYDIRECT_METOD_POST_ALL"), "");
		?>
	</td>
</tr>
<?
$oFilter->Buttons(array("table_id"=>$sTableID,"url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
$oFilter->End();
?>
</form>

<?
// SHOW LIST
$lAdmin->DisplayList();
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>