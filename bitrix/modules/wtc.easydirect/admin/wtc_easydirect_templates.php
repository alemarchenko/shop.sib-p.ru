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
if ($POST_RIGHT < "R")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$sTableID = "tbl_ed_templates"; // Table ID
$oSort = new CAdminSorting($sTableID, "ID", "asc"); // sort object
$lAdmin = new CAdminList($sTableID, $oSort); // main list object

// ******************************************************************** //
//													 FILTER													 //
// ******************************************************************** //
// build filer fields
$FilterArr = Array(
	"find_name",
    "find_iblock_id",
    "find_for_sections",    
);
$lAdmin->InitFilter($FilterArr);

// create a filter array for get from DB
$arFilter = Array(
	"NAME"	=> (($find_name)?"%".$find_name."%":""),
    "IBLOCK_ID"	=> $find_iblock_id,
    "FOR_SECTIONS"	=> $find_for_sections
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
		if(($rsData = CEDirectTemplates::GetByID($ID)) && ($arData = $rsData->Fetch()))
		{
			foreach($arFields as $key=>$value)
				$arData[$key]=$value;
				
			if(!CEDirectTemplates::Update($ID, $arData))
			{
				$lAdmin->AddGroupError(GetMessage("EASYDIRECT_TEMPLATES_save_error"), $ID);
				$DB->Rollback();
			}
		}
		else
		{
			$lAdmin->AddGroupError(GetMessage("EASYDIRECT_TEMPLATES_save_error").", ".GetMessage("EASYDIRECT_TEMPLATES_no_element"), $ID);
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
		$rsData = CEDirectTemplates::GetList(array($by=>$order), $arFilter, false, array("ID"));
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
			$DB->StartTransaction();
			if(!CEDirectTemplates::Delete($ID))
			{
				$DB->Rollback();
				$lAdmin->AddGroupError(GetMessage("EASYDIRECT_TEMPLATES_del_error"), $ID);
			}
			$DB->Commit();
		 break;
		 
		 // copy
		 case "copy":
    		if(($rsData = CEDirectTemplates::GetByID($ID)) && ($arData = $rsData->Fetch()))
    		{
    			foreach($arFields as $key=>$value)
    				$arData[$key]=$value;
    				
    			unset($arData["ID"]);
    			unset($arData["MODIFIED_DATE"]);
    			unset($arData["MODIFIED_IDUSER"]);
    			$arData["NAME"].=GetMessage("EASYDIRECT_TEMPLATES_copy_prefix");
    			if(!CEDirectTemplates::Add($arData))
    			{
    				$lAdmin->AddGroupError(GetMessage("EASYDIRECT_TEMPLATES_copy_error"), $ID);
    			}
    		}
    		else
    		{
    			$lAdmin->AddGroupError(GetMessage("EASYDIRECT_TEMPLATES_copy_error").", ".GetMessage("EASYDIRECT_TEMPLATES_no_element"), $ID);
    		}
		     break;		 
		
		}
	}
}

// ******************************************************************** //
//								GET LIST FROM DB															//
// ******************************************************************** //

// GET DATA FROM DB
$rsData =CEDirectTemplates::GetList(array($by=>$order), $arFilter);

// convert result to instance of class CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

//PAGE NAVIGATION
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("EASYDIRECT_TEMPLATES_nav")));

// ******************************************************************** //
//								PREPERE & BUILD LIST														//
// ******************************************************************** //
//Iblock names for use
$arCatalogIBlock=array();
$res = CIBlock::GetList(Array(),Array("ID"=>CEDirectCatalogItems::getCatalogIBlockIDs(),"ACTIVE"=>"Y","CNT_ACTIVE"=>"Y"), false);
while($ar_res = $res->Fetch()){
    $arCatalogIBlock[$ar_res["ID"]]=$ar_res["NAME"];
}
//-------------------------------

$lAdmin->AddHeaders(array(
	array(	
		"id"		=>"ID",
		"content"	=>"ID",
		"sort"		=>"id",
		"align"		=>"right",
		"default"	=>true,
	),
	array(
		"id"		=>"NAME",
		"content"	=>GetMessage("EASYDIRECT_TEMPLATES_name"),
		"sort"		=>"name",
		"default"	=>true,
	),
    array(
        "id"		=>"IBLOCK_ID",
        "content"	=>GetMessage("EASYDIRECT_TEMPLATES_iblock_id"),
        "sort"		=>"iblock_id",
        "default"	=>true,
    ),
    array(
        "id"		=>"FOR_SECTIONS",
        "content"	=>GetMessage("EASYDIRECT_TEMPLATES_for_section"),
        "sort"		=>"for_sections",
        "default"	=>true,
    )    
    
));

while($arRes = $rsData->NavNext(true, "f_")):
	
	// create line. Result - instance of class CAdminListRow
	$row =& $lAdmin->AddRow($f_ID, $arRes); 
	
	// next build field for view and edit
	
	// NAME edit as text and show as URL
	$row->AddInputField("NAME", array("size"=>20));
	$row->AddViewField("NAME", '<a href="wtc_easydirect_templates_edit.php?ID='.$f_ID.'&lang='.LANG.'">'.$f_NAME.'</a>');	
	$row->AddViewField("IBLOCK_ID", (isset($arCatalogIBlock[$f_IBLOCK_ID])?$arCatalogIBlock[$f_IBLOCK_ID]:$f_IBLOCK_ID));
	$row->AddCheckField("FOR_SECTIONS");

	// BUILD context menu
	$arActions = Array();

	if ($POST_RIGHT>="W"){
    	    // EDIT ELEMENT
    	    $arActions[] = array(
    	        "ICON"=>"edit",
    	        "DEFAULT"=>true,
    	        "TEXT"=>GetMessage("EASYDIRECT_TEMPLATES_cmen_edit"),
    	        "ACTION"=>$lAdmin->ActionRedirect("wtc_easydirect_templates_edit.php?ID=".$f_ID.'&lang='.LANG)
    	    );
    	    
    	    // COPY ELEMENT
    	    $arActions[] = array(
    	        "ICON"=>"copy",
    	        "DEFAULT"=>true,
    	        "TEXT"=>GetMessage("EASYDIRECT_TEMPLATES_cmen_copy"),
    	        "ACTION"=>$lAdmin->ActionDoGroup($f_ID, "copy")
    	    );    	    
    	    
    	    // DELETE ELEMENT
			$arActions[] = array(
				"ICON"=>"delete",
				"TEXT"=>GetMessage("EASYDIRECT_TEMPLATES_cmen_del"),
				"ACTION"=>"if(confirm('".GetMessage('EASYDIRECT_TEMPLATES_cmen_del_conf')."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
			);
	}
	
	// ADD context menu to ELEMENT
	$row->AddActions($arActions);	
	
endwhile;

// Group Action
if ($POST_RIGHT>="W"){
    $lAdmin->AddGroupActionTable(Array(
    	"delete"=>GetMessage("MAIN_ADMIN_LIST_DELETE")        
    ));
}

// ******************************************************************** //
//								ADMINISTRATION MENU										 //
// ******************************************************************** //
	
// BUILD ADMIN BUTTONS
$aContext = array(
	array(
		"TEXT"=>GetMessage("EASYDIRECT_TEMPLATES_add"),
		"LINK"=>"wtc_easydirect_templates_edit.php?lang=".LANG,
		"TITLE"=>GetMessage("EASYDIRECT_TEMPLATES_add"),
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
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_TEMPLATES_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ******************************************************************** //
//								FILTER SHOW																	 //
// ******************************************************************** //

// create filter object
$oFilter = new CAdminFilter(
	$sTableID."_filter",
	array(
		GetMessage("EASYDIRECT_TEMPLATES_f_fname")
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
	<td><?=GetMessage("EASYDIRECT_TEMPLATES_f_name")?>:</td>
	<td>
		<input type="text" name="find_name" size="20" value="<?echo htmlspecialcharsbx($find_name)?>">
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_TEMPLATES_for_section")?>:</td>
	<td>
		<?
		$arr = array(
			"reference" => array(
				GetMessage("EASYDIRECT_TEMPLATES_POST_YES"),
				GetMessage("EASYDIRECT_TEMPLATES_POST_NO"),
			),
			"reference_id" => array(
				"Y",
				"N",
			)
		);
		echo SelectBoxFromArray("find_for_sections", $arr, $find_for_sections, GetMessage("EASYDIRECT_TEMPLATES_POST_ALL"), "");
		?>
	</td>
</tr>
<tr>
	<td><?=GetMessage("EASYDIRECT_TEMPLATES_iblock_id")?>:</td>
	<td>
		<select name="find_iblock_id">
			<option value=""><?=GetMessage("EASYDIRECT_TEMPLATES_POST_ALL")?></option>
    		    <?
        		$res = CIBlock::GetList(Array(),Array("ID"=>CEDirectCatalogItems::getCatalogIBlockIDs(),"ACTIVE"=>"Y","CNT_ACTIVE"=>"Y"), false);
        		foreach($arCatalogIBlock as $id=>$name){
        		    echo '<option '.(($id==$find_iblock_id)?"selected":"").' value="'.$id.'">'.$name.'</option>';
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
// SHOW LIST
$lAdmin->DisplayList();
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>