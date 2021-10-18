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
	array("DIV" => "edit1", "TAB" => GetMessage("EASYDIRECT_METOD_EDIT_tab1name"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("EASYDIRECT_METOD_EDIT_tab1name")),
);
$tabControl = new CAdminTabControl("tabControlMetod", $aTabs);

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
	( (strlen($NAME)>0 && strlen($TYPE)>0 && strlen($FNAME)>3) || ($CMD=="install") )
	&&
	$POST_RIGHT=="W"
	&&
	check_bitrix_sessid()
)
{
    $err=0;
    //instal from file
    if($CMD=="install"){
        if(array_key_exists("METHOD_FILE", $_FILES)&&$_FILES["METHOD_FILE"]['error']===0)
        {
            if(strpos($_FILES["METHOD_FILE"]["name"],".php")>0 )
            {
                $userMethodDir=$_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/wtc.easydirect/user_methods";
                if(!file_exists($userMethodDir)){
                    mkdir($userMethodDir, BX_DIR_PERMISSIONS, true);
                }
                
                if (copy($_FILES["METHOD_FILE"]["tmp_name"], $userMethodDir."/".$_FILES["METHOD_FILE"]["name"])) {
                    $fname=str_replace(".php", "", $_FILES["METHOD_FILE"]["name"]);
                    //check if isset method
                    $res=CEDirectMetod::GetList(array(),array("FNAME"=>$fname));
                    if($arIssetFields=$res->Fetch()){
                        foreach ($arIssetFields as $key=>$val){
                            $$key=$val;
                        }
                    }
                    //get field from file
                    $lines = file($userMethodDir."/".$_FILES["METHOD_FILE"]["name"]);
                    foreach ($lines as $line) {
                        if( strpos($line,"field_") !== false ){
                            preg_match('/field_([_A-Z]*)=(.*)/'.(EDIRECT_UTFSITE?"u":""), $line, $matches);
                            $fieldName=trim($matches[1]);
                            if(!EDIRECT_UTFSITE) $matches[2]=iconv("utf-8", "windows-1251", $matches[2]); //if site isn't in UTF8, Convert string
                            $$fieldName=trim($matches[2]);
                        }
                        else if( strpos($line,"function") !== false ){
                            break;
                        }
                    }
                    if(!(strlen($NAME)>0 && strlen($TYPE)>0 && strlen($FNAME)>3)){
                        $message = new CAdminMessage(array("MESSAGE"=>GetMessage("EASYDIRECT_METOD_EDIT_install_fields_err"),"HTML"=>true));
                        $err=1;                        
                    }
                    $IS_USER="Y";
                    $ACTIVE="Y";
                }
                else {
                    $message = new CAdminMessage(GetMessage("EASYDIRECT_METOD_EDIT_install_copy_err"));
                    $err=1;
                }
            }
            else{
                $message = new CAdminMessage(GetMessage("EASYDIRECT_METOD_EDIT_install_php_err"));
                $err=1;
            }
        }
    }
    //----------------
    
    if($err==0){
    	// main data
    	$arFields = Array(
    		"NAME"		=> $NAME,
    		"FNAME"		=> $FNAME,
    	    "TYPE"		=> $TYPE,
    		"IS_IMPORTANT"=>($IS_IMPORTANT <> "Y"? "N":"Y"),
    		"IS_USER"=>($IS_USER <> "Y"? "N":"Y"),
    		"DESCRIPTION"		=> $DESCRIPTION,
    		"SORT"		=> $SORT,
    		"ACTIVE"	=> ($ACTIVE <> "Y"? "N":"Y"),
    	);
    	
    	// save changes
    	if($ID > 0)
    	{
    		$res = CEDirectMetod::Update($ID, $arFields);
    	}
    	else
    	{
    		$ID = CEDirectMetod::Add($arFields);
    		$res = ($ID > 0);
    	}
    
    	if($res)//if OK
    	{
    		if ($apply != ""){
    		    $ok="ok";
    		    if($CMD=="install") $ok="okinstall";
    			LocalRedirect("/bitrix/admin/wtc_easydirect_metod_edit.php?ID=".$ID."&mess=".$ok."&lang=".LANG."&".$tabControl->ActiveTabParam());
    		}
    		else{
    			LocalRedirect("/bitrix/admin/wtc_easydirect_metod.php?lang=".LANG);
    		}
    	}
    	else // if error
    	{
    		if($e = $APPLICATION->GetException())
    			$message = new CAdminMessage(GetMessage("EASYDIRECT_METOD_EDIT_save_error"), $e);
    		$bVarsFromForm = true;
    	}
    }
}
else if($save!="" || $apply!="")	{
			$message = new CAdminMessage(GetMessage("EASYDIRECT_METOD_EDIT_noob_error"));
}

// ******************************************************************** //
//								GET DATA 									 //
// ******************************************************************** //

// default values
$str_SORT= 500;
$str_ACTIVE	="Y";
$str_IS_USER="Y";
$str_IS_IMPORTANT="N";

// from BD
if($ID>0)
{
	$event = CEDirectMetod::GetByID($ID);
	if(!$event->ExtractFields("str_"))
		$ID=0;
}

// if data receive from form
if($bVarsFromForm)
	$DB->InitTableVarsForEdit("b_list_metod", "", "str_");

// ******************************************************************** //
//								SHOW FORM    																	 //
// ******************************************************************** //

// set title
if($ID>0) $APPLICATION->SetTitle(GetMessage("EASYDIRECT_METOD_EDIT_title_edit")." ".$str_NAME);
else if($CMD=="install") $APPLICATION->SetTitle(GetMessage("EASYDIRECT_METOD_EDIT_title_install"));
else $APPLICATION->SetTitle(GetMessage("EASYDIRECT_METOD_EDIT_title_add"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// admin buttons
$aMenu = array(
	array(
		"TEXT"=>GetMessage("EASYDIRECT_METOD_EDIT_menu_list"),
		"TITLE"=>GetMessage("EASYDIRECT_METOD_EDIT_menu_list"),
		"LINK"=>"wtc_easydirect_metod.php?lang=".LANG,
		"ICON"=>"btn_list",
	)
);

if($ID>0)
{
	$aMenu[] = array("SEPARATOR"=>"Y");
	$aMenu[] = array(
		"TEXT"=>GetMessage("EASYDIRECT_METOD_EDIT_menu_del"),
		"TITLE"=>GetMessage("EASYDIRECT_METOD_EDIT_menu_del"),
		"LINK"=>"javascript:if(confirm('".GetMessage("EASYDIRECT_METOD_EDIT_menu_del_conf")."'))window.location='wtc_easydirect_metod.php?ID=".$ID."&action=delete&lang=".LANG."&".bitrix_sessid_get()."';",
		"ICON"=>"btn_delete",
	);
}

$context = new CAdminContextMenu($aMenu);
$context->Show();
?>

<?
// show messages
if($_REQUEST["mess"] == "ok" && $ID>0){
	CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("EASYDIRECT_METOD_EDIT_saved"), "TYPE"=>"OK"));
}
else if($_REQUEST["mess"] == "okinstall" && $ID>0){
	CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("EASYDIRECT_METOD_EDIT_install_ok"), "TYPE"=>"OK"));
}

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
<?if($CMD=="install"): //install new method from FILE?>
	<tr>
		<td width="40%"><span class="required">*</span><?echo GetMessage("EASYDIRECT_METOD_EDIT_file_install")?></td>
		<td width="60%">  			
		      <?
  				echo CFile::InputFile("METHOD_FILE", 20, 0);
			?>
		</td>
	</tr>
	<tr>
	   <td width="40%">&nbsp;</td>
	   <td>
            <?
                echo BeginNote(); 
                echo GetMessage("EASYDIRECT_METOD_EDIT_note_install"); 
                echo EndNote();
            ?>
            <input type="hidden" name="CMD" value="install">
	   </td>
	</tr>
<?else: //edit or registrate method?>
	<tr>
		<td width="40%"><?echo GetMessage("EASYDIRECT_METOD_EDIT_active")?></td>
		<td width="60%"><input type="checkbox" name="ACTIVE" value="Y"<?if($str_ACTIVE == "Y") echo " checked"?>></td>
	</tr>
	<tr>
		<td><?echo GetMessage("EASYDIRECT_METOD_EDIT_sort")?></td>
		<td><input type="text" name="SORT" value="<?echo $str_SORT;?>" size="30"></td>
	</tr>
	<tr>
		<td><span class="required">*</span><?echo GetMessage("EASYDIRECT_METOD_EDIT_name")?></td>
		<td><input type="text" name="NAME" value="<?echo $str_NAME;?>" size="30" maxlength="100"></td>
	</tr>	
	<?if($str_IS_USER=="Y"):?>	
	<tr>
		<td><span class="required">*</span><?echo GetMessage("EASYDIRECT_METOD_EDIT_type")?></td>
		<td>
		<select name="TYPE">
		<?php 
    		$arTypes=CEDirectMetod::GetTypeArray();
    		foreach ($arTypes as $key=>$val){
    		    echo '<option '.(($str_TYPE==$key)?"selected":"").' value="'.$key.'">'.$val.'</option>';
    		}
		?>		
		</select>	
	</tr>	
	<tr>
		<td><span class="required">*</span><?echo GetMessage("EASYDIRECT_METOD_EDIT_fname")?></td>
		<td>
		<select name="FNAME">
		<?php 
		        $arListUserMethods=array_merge(CEDirectMetod::getListUserMethods(),CEDirectMetod::getListUserHandlerMethods());
		        foreach ($arListUserMethods as $userMethod){
					echo '<option '.(($str_FNAME==$userMethod)?"selected":"").' value="'.$userMethod.'">'.$userMethod.'</option>';
        		}
		?>		
		</select>	
	</tr>
	<?else:?>
	<input type="hidden" name="FNAME" value="<?=$str_FNAME?>">
	<input type="hidden" name="TYPE" value="<?=$str_TYPE?>">
	<?endif;?>
	<tr>
		<td width="40%"><?echo GetMessage("EASYDIRECT_METOD_EDIT_isimportant")?></td>
		<td width="60%"><input type="checkbox" name="IS_IMPORTANT" value="Y"<?if($str_IS_IMPORTANT == "Y") echo " checked"?>></td>
	</tr>	
	<tr>
		<td valign="top"><?echo GetMessage("EASYDIRECT_METOD_EDIT_descript")?></td>
		<td><textarea rows="5" cols="35" name="DESCRIPTION"><?echo $str_DESCRIPTION;?></textarea></td>
	</tr>
<?endif;?>	
<?
// form end, show buttons
$tabControl->Buttons(
	array(
		"disabled"=>($POST_RIGHT<"W"),
		"back_url"=>"wtc_easydirect_metod.php?lang=".LANG,
	)
);
?>
<input type="hidden" name="lang" value="<?=LANG?>">
<input type="hidden" name="IS_USER" value="<?=$str_IS_USER?>">
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
<span class="required">*</span><?echo GetMessage("REQUIRED_FIELDS")?><?echo GetMessage("EASYDIRECT_METOD_EDIT_note")?>
<?echo EndNote();?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>