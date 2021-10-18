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

$sTableID = "tbl_ed_import"; // Table ID
$lAdmin = new CAdminList($sTableID, false); // main list object


// ******************************************************************** //
//								EXEC OPERATION of the ELEMENT							//
// ******************************************************************** //
if(($arID = $lAdmin->GroupAction()) && $POST_RIGHT=="W")
{
	$arIDs=array();
	foreach($arID as $ID)
	{
		if(strlen($ID)<=0)
			continue;
		
		if(is_numeric($ID)) $arIDs[] = $ID;
	}
	
	//EXEC OPERATION
	switch($_REQUEST['action'])
	{
		case "import":
			$_SESSION["EDIRECT_COMPANY_IDS"]=$arIDs;
			$_SESSION["EDIRECT_COMPANY_IDS_CNT"]=0;
			echo '<script language="JavaScript">window.top.execCompanyAction("importCompanies");</script>';
		break;
	}	
}

// ******************************************************************** //
//								GET LIST FROM DB										//
// ******************************************************************** //
// GET DATA FROM Yandex
$rsData =$obYaExchange->getCompanyList();

// get all company in DB
$res=CEDirectCompany::GetList(Array(),array(),false,array("ID"));
$CompanyBDID=array();
while($ar_res=$res->Fetch()) $CompanyBDID[]=$ar_res['ID'];
// ******************************************************************** //
//								PREPERE & BUILD LIST									//
// ******************************************************************** //

$lAdmin->AddHeaders(array(
	array(
		"id"		=>"N",
		"content"	=>"N",
		"sort"		=>"",
		"align"		=>"right",
		"default"	=>true,
	),		
	array(	
		"id"		=>"ID",
		"content"	=>"ID",
		"sort"		=>"",
		"align"		=>"right",
		"default"	=>true,
	),
	array(
		"id"		=>"NAME",
		"content"	=>GetMessage("EASYDIRECT_IMPORT_name"),
		"sort"		=>"",
		"default"	=>true,
	),
));

$i=1;
if($rsData==0) $rsData=array();
foreach ($rsData as $arComp){
	if(in_array($arComp["Id"],$CompanyBDID)) continue;
	
	$row =& $lAdmin->AddRow(
	    $arComp["Id"], 
    	array(
                'ID'=>$arComp['Id'],
                'NAME'=>$arComp['Name']
                )
	    ); 
	$row->AddViewField("N", $i);
	$i++;	
}

// GroupAction
$lAdmin->AddGroupActionTable(Array(
		"import"=>GetMessage("EASYDIRECT_IMPORT_import")
		),
		array("disable_action_target"=>"Y")
);


// ******************************************************************** //
//								SHOW																			 //
// ******************************************************************** //

$lAdmin->CheckListMode();
// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_IMPORT_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?CJSCore::Init(array("jquery"));?>
<script language="JavaScript">
$.ajaxSetup({cache: false});

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
	window['<?=$sTableID?>'].GetAdminList('/bitrix/admin/wtc_easydirect_company_import.php?lang=<?=LANG?>');
}
</script>

<?
//progress bar
echo '<div class="edirect-progress"></div>';

// SHOW LIST
$lAdmin->DisplayList();
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>