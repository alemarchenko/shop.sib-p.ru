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
	array("DIV" => "edit1", "TAB" => GetMessage("EASYDIRECT_TEMPLATES_EDIT_tab1name"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("EASYDIRECT_TEMPLATES_EDIT_tab1name")),
);
$tabControl = new CAdminTabControl("tabControlTemplates", $aTabs);

$ID = (int)$ID;
$message = null;	//error message
$bVarsFromForm = false; // if data come from form, not from DB

// ******************************************************************** //
//								PROCESSING FORM'S DATA 												 //
// ******************************************************************** //

if(
	$REQUEST_METHOD == "POST"
	&&
	($save!="" || $apply!="")
	&&
	(strlen($NAME)>0 && $IBLOCK_ID >0)
	&&
	$POST_RIGHT=="W"
	&&
	check_bitrix_sessid()
)
{
	 // main data
	$arFields = Array(
	    "IBLOCK_ID" => $IBLOCK_ID,
	    "FOR_SECTIONS" => ($FOR_SECTIONS <> "Y"? "N":"Y"),
		"NAME"		=> $NAME,
	    "TITLE"		=> $TITLE,
	    "TITLE2"		=> $TITLE2,
	    "TEXT"		=> $TEXT,
	    "HREF"		=> $HREF,
	    "DISPLAY_URL"		=> $DISPLAY_URL,
	    "PRICE"		=> $PRICE,
	    "PHRASES"		=> $PHRASES,
	    "MINUS_WORDS" => $MINUS_WORDS,
	    "SITELINKS"		=> CAllEDirectTable::SerializeArrayField($SITELINKS)
	);
	
	// save changes
	if($ID > 0)
	{
		$res = CEDirectTemplates::Update($ID, $arFields);
	}
	else
	{
		$ID = CEDirectTemplates::Add($arFields);
		$res = ($ID > 0);
	}

	if($res)//if OK
	{
		if ($apply != "")
			LocalRedirect("/bitrix/admin/wtc_easydirect_templates_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&".$tabControl->ActiveTabParam());
		else
			LocalRedirect("/bitrix/admin/wtc_easydirect_templates.php?lang=".LANG);
	}
	else // if error
	{
		if($e = $APPLICATION->GetException())
			$message = new CAdminMessage(GetMessage("EASYDIRECT_TEMPLATES_EDIT_save_error"), $e);
		$bVarsFromForm = true;
	}
}
else if($save!="" || $apply!="")	{
			$message = new CAdminMessage(GetMessage("EASYDIRECT_TEMPLATES_EDIT_noob_error"));
}

// ******************************************************************** //
//								GET DATA 									 //
// ******************************************************************** //

// default values
//$str_SORT= 500;
//$str_ACTIVE	="Y";

// from BD
if($ID>0)
{
	$event = CEDirectTemplates::GetByID($ID);
	if(!$event->ExtractFields("str_",false))
		$ID=0;
}

// if data receive from form
if($bVarsFromForm)
	$DB->InitTableVarsForEdit("b_list_templates", "", "str_");

// ******************************************************************** //
//								SHOW FORM    																	 //
// ******************************************************************** //

// set title
$APPLICATION->SetTitle(($ID>0? GetMessage("EASYDIRECT_TEMPLATES_EDIT_title_edit")." ".$str_NAME : GetMessage("EASYDIRECT_TEMPLATES_EDIT_title_add")));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// admin buttons
$aMenu = array(
	array(
		"TEXT"=>GetMessage("EASYDIRECT_TEMPLATES_EDIT_menu_list"),
		"TITLE"=>GetMessage("EASYDIRECT_TEMPLATES_EDIT_menu_list"),
		"LINK"=>"wtc_easydirect_templates.php?lang=".LANG,
		"ICON"=>"btn_list",
	)
);

if($ID>0)
{
	$aMenu[] = array("SEPARATOR"=>"Y");
	$aMenu[] = array(
		"TEXT"=>GetMessage("EASYDIRECT_TEMPLATES_EDIT_menu_add"),
		"TITLE"=>GetMessage("EASYDIRECT_TEMPLATES_EDIT_menu_add"),
		"LINK"=>"wtc_easydirect_templates_edit.php?lang=".LANG,
		"ICON"=>"btn_new",
	);
	$aMenu[] = array(
		"TEXT"=>GetMessage("EASYDIRECT_TEMPLATES_EDIT_menu_del"),
		"TITLE"=>GetMessage("EASYDIRECT_TEMPLATES_EDIT_menu_del"),
		"LINK"=>"javascript:if(confirm('".GetMessage("EASYDIRECT_TEMPLATES_EDIT_menu_del_conf")."'))window.location='wtc_easydirect_templates.php?ID=".$ID."&action=delete&lang=".LANG."&".bitrix_sessid_get()."';",
		"ICON"=>"btn_delete",
	);
}

$context = new CAdminContextMenu($aMenu);
$context->Show();
?>

<?
// show messages
if($_REQUEST["mess"] == "ok" && $ID>0)
	CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("EASYDIRECT_TEMPLATES_EDIT_saved"), "TYPE"=>"OK"));

if($message)
	echo $message->Show();
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?
// SHOW FORM
?>
<?php CJSCore::Init(array("jquery"));?>
<script language="JavaScript">
$.ajaxSetup({cache: false}); 

var curElement;

function insertTextAtCursor(el, text, offset) {
    var val = el.value, endIndex, range, doc = el.ownerDocument;
    if (typeof el.selectionStart == "number"
            && typeof el.selectionEnd == "number") {
        endIndex = el.selectionEnd;
        el.value = val.slice(0, endIndex) + text + val.slice(endIndex);
        el.selectionStart = el.selectionEnd = endIndex + text.length+(offset?offset:0);
    } else if (doc.selection != "undefined" && doc.selection.createRange) {
        el.focus();
        range = doc.selection.createRange();
        range.collapse(false);
        range.text = text;
        range.select();
    }
}

function enterParamCode(element){
	insertTextAtCursor(curElement,$(element).html());
}

function setFocus(element){
	curElement=element;
}

function getTableReplaceFields(){
	var for_sections=0;
	if($("#for_sections").attr("checked") == 'checked'){
		for_sections=1;
		if($("#banner_href").attr("value")=="{ELEMENT.URL}"||$("#banner_href").attr("value")==""){
			$("#banner_href").attr("value","{SECTION_SMART_FILTER.URL}")
		}
	}
	else{
		if($("#banner_href").attr("value")=="{SECTION_SMART_FILTER.URL}"||$("#banner_href").attr("value")==""){
			$("#banner_href").attr("value","{ELEMENT.URL}")
		}		
	}
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_template.php",
			{CMD: "showTableReplaceFields",IB_ID: $("#iblock_id").attr("value"),FOR_SECTIONS: for_sections}, 
			function(data){
				$('#tblReplaceFields').html(data);
			}
			);	
}

$(document).ready(function() {
	curElement=document.getElementById('tmplTitle');
	getTableReplaceFields();
});
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
// first tab - edit main properties
//********************
$tabControl->BeginNextTab();
?>
	<tr>
		<td><span class="required">*</span><?echo GetMessage("EASYDIRECT_TEMPLATES_EDIT_name")?></td>
		<td><input type="text" name="NAME" value="<?echo $str_NAME;?>" size="30" maxlength="100"></td>
	</tr>
	<tr>
		<td><span class="required">*</span><?echo GetMessage("EASYDIRECT_TEMPLATES_EDIT_iblock")?></td>
		<td>
    		<?
    		if(count(CEDirectCatalogItems::getCatalogIBlockIDs())>0)
    		{
    		?>
    		  <select id="iblock_id" name="IBLOCK_ID" onChange="getTableReplaceFields()">
    		  <?    
        		$res = CIBlock::GetList(Array(),Array("ID"=>CEDirectCatalogItems::getCatalogIBlockIDs(),"ACTIVE"=>"Y","CNT_ACTIVE"=>"Y"), false);
        		while($ar_res = $res->Fetch()){
        		    echo '<option '.(($ar_res['ID']==$str_IBLOCK_ID)?"selected":"").' value="'.$ar_res['ID'].'">'.$ar_res['NAME'].'</option>';
        		}?>
       		   </select> 		
        	   <?
    		}
            else{
                echo "<font color=\"red\"><b>".GetMessage("EASYDIRECT_TEMPLATES_fgoods_ibid_err_title")."</b></font><br>";
                echo GetMessage("EASYDIRECT_TEMPLATES_fgoods_ibid_err_note");
             }
    		?>
		</td>
	</tr>	
    <tr>
        <td><?echo GetMessage("EASYDIRECT_TEMPLATES_EDIT_for_sections")?></td>
        <td><input id="for_sections" onChange="getTableReplaceFields()" type="checkbox" name="FOR_SECTIONS" value="Y"<?if($str_FOR_SECTIONS == "Y") echo " checked"?>></td>
    </tr>	
	<tr>
	   <td colspan="2">
	   <?	   
	   //prepare EDIT templates HTML
	   //if($str_HREF=="") $str_HREF="{ELEMENT.URL}";
	   $arSITELINKS=CAllEDirectTable::UnSerializeArrayField($str_SITELINKS);
	   $editHTML='<table class="no-border" id="tmplTable">
            <tr><td class="right">'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_title").'</td><td><input onfocus="setFocus(this)" type="text" id="tmplTitle" name="TITLE" value="'.$EDirectMain->htmlspecialcharsEx($str_TITLE).'" size="50" maxlength="250"></td></tr>
            <tr><td class="right">'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_title2").'</td><td><input onfocus="setFocus(this)" type="text" name="TITLE2" value="'.$EDirectMain->htmlspecialcharsEx($str_TITLE2).'" size="50" maxlength="250"></td></tr>
            <tr><td class="right">'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_text").'</td><td><textarea onfocus="setFocus(this)" rows="3" cols="50" name="TEXT">'.$EDirectMain->htmlspecialcharsEx($str_TEXT).'</textarea></td></tr>
	        <tr><td class="right">'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_href").'</td><td><input id="banner_href" onfocus="setFocus(this)" type="text" name="HREF" value="'.$str_HREF.'" size="50" maxlength="250"></td></tr>
	        <tr><td class="right">'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_display_url").'</td><td><input onfocus="setFocus(this)" type="text" name="DISPLAY_URL" value="'.$str_DISPLAY_URL.'" size="50" maxlength="250"></td></tr>
	        <tr><td class="right">'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_price").'</td><td><input onfocus="setFocus(this)" type="text" name="PRICE" value="'.$str_PRICE.'" size="50" maxlength="250"></td></tr>
	        <tr><td class="right">'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_phrases").'</td><td><textarea onfocus="setFocus(this)" rows="10" cols="50" name="PHRASES">'.$EDirectMain->htmlspecialcharsEx($str_PHRASES).'</textarea></td></tr>
            <tr><td class="right">'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_minus_words").'</td><td><textarea onfocus="setFocus(this)" rows="3" cols="50" name="MINUS_WORDS">'.$EDirectMain->htmlspecialcharsEx($str_MINUS_WORDS).'</textarea></td></tr>	        
            <tr><td colspan="2">
                    <table class="no-border">
                        <tr><td>'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_sitelinks_tbl1").'</td><td>'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_sitelinks_tbl2").'</td></tr>
                        <tr><td>1. <input type="text" onfocus="setFocus(this)" name="SITELINKS[1][Title]" size="20" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n0"]["Title"]).'"></td><td><input type="text" onfocus="setFocus(this)" name="SITELINKS[1][Href]" size="40" value="'.$arSITELINKS["n0"]["Href"].'"></td></tr>
                        <tr><td>2. <input type="text" onfocus="setFocus(this)" name="SITELINKS[2][Title]" size="20" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n1"]["Title"]).'"></td><td><input type="text" onfocus="setFocus(this)" name="SITELINKS[2][Href]" size="40" value="'.$arSITELINKS["n1"]["Href"].'"></td></tr>
	                    <tr><td>3. <input type="text" onfocus="setFocus(this)" name="SITELINKS[3][Title]" size="20" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n2"]["Title"]).'"></td><td><input type="text" onfocus="setFocus(this)" name="SITELINKS[3][Href]" size="40" value="'.$arSITELINKS["n2"]["Href"].'"></td></tr>
	                    <tr><td>4. <input type="text" onfocus="setFocus(this)" name="SITELINKS[4][Title]" size="20" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n3"]["Title"]).'"></td><td><input type="text" onfocus="setFocus(this)" name="SITELINKS[4][Href]" size="40" value="'.$arSITELINKS["n3"]["Href"].'"></td></tr>
                        <tr><td colspan=2><br></td></tr>
                        <tr><td>5. <input type="text" onfocus="setFocus(this)" name="SITELINKS[5][Title]" size="20" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n4"]["Title"]).'"></td><td><input type="text" onfocus="setFocus(this)" name="SITELINKS[5][Href]" size="40" value="'.$arSITELINKS["n4"]["Href"].'"></td></tr>
                        <tr><td>6. <input type="text" onfocus="setFocus(this)" name="SITELINKS[6][Title]" size="20" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n5"]["Title"]).'"></td><td><input type="text" onfocus="setFocus(this)" name="SITELINKS[6][Href]" size="40" value="'.$arSITELINKS["n5"]["Href"].'"></td></tr>
	                    <tr><td>7. <input type="text" onfocus="setFocus(this)" name="SITELINKS[7][Title]" size="20" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n6"]["Title"]).'"></td><td><input type="text" onfocus="setFocus(this)" name="SITELINKS[7][Href]" size="40" value="'.$arSITELINKS["n6"]["Href"].'"></td></tr>
	                    <tr><td>8. <input type="text" onfocus="setFocus(this)" name="SITELINKS[8][Title]" size="20" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n7"]["Title"]).'"></td><td><input type="text" onfocus="setFocus(this)" name="SITELINKS[8][Href]" size="40" value="'.$arSITELINKS["n7"]["Href"].'"></td></tr>
    
    
                        <tr><td colspan=2><hr>'.GetMessage("EASYDIRECT_TEMPLATES_EDIT_sitelinks_tbl3").'</td></tr>
                        <tr><td colspan=2>1. <input type="text" onfocus="setFocus(this)" name="SITELINKS[1][Description]" size="60" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n0"]["Description"]).'"></td></tr>
                        <tr><td colspan=2>2. <input type="text" onfocus="setFocus(this)" name="SITELINKS[2][Description]" size="60" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n1"]["Description"]).'"></td></tr>
                        <tr><td colspan=2>3. <input type="text" onfocus="setFocus(this)" name="SITELINKS[3][Description]" size="60" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n2"]["Description"]).'"></td></tr>
                        <tr><td colspan=2>4. <input type="text" onfocus="setFocus(this)" name="SITELINKS[4][Description]" size="60" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n3"]["Description"]).'"></td></tr>
	                    <tr><td colspan=2><br></td></tr>
                        <tr><td colspan=2>5. <input type="text" onfocus="setFocus(this)" name="SITELINKS[5][Description]" size="60" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n4"]["Description"]).'"></td></tr>
                        <tr><td colspan=2>6. <input type="text" onfocus="setFocus(this)" name="SITELINKS[6][Description]" size="60" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n5"]["Description"]).'"></td></tr>
                        <tr><td colspan=2>7. <input type="text" onfocus="setFocus(this)" name="SITELINKS[7][Description]" size="60" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n6"]["Description"]).'"></td></tr>
                        <tr><td colspan=2>8. <input type="text" onfocus="setFocus(this)" name="SITELINKS[8][Description]" size="60" value="'.$EDirectMain->htmlspecialcharsEx($arSITELINKS["n7"]["Description"]).'"></td></tr>
	    
                    </table>
                </td>
            </tr>
            </table>
       ';
	   //----------------------------
	   
		$obTblMain=new CEDirectShowTbl(
				array(GetMessage("EASYDIRECT_TEMPLATES_tbl1"),
						GetMessage("EASYDIRECT_TEMPLATES_tbl2"),
        		),
				array(
				    array(
				    $editHTML,
				    "<div id='tblReplaceFields' style='border:#000 outset 2px;overflow: auto; min-width: 600px; height: 710px;'></div>"
				    )
        		)
		);
		echo $obTblMain->ShowTbl();
		?>	   
	   </td>
	</tr>
	
<?
// form end, show buttons
$tabControl->Buttons(
	array(
		"disabled"=>($POST_RIGHT<"W"),
		"back_url"=>"wtc_easydirect_templates.php?lang=".LANG,
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