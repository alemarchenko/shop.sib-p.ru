<?
if(!check_bitrix_sessid()) return;
IncludeModuleLangFile(__FILE__);

if($ex = $APPLICATION->GetException()){
	echo CAdminMessage::ShowMessage(Array(
		"TYPE" => "ERROR",
		"MESSAGE" => GetMessage("MOD_INST_ERR"),
		"DETAILS" => $ex->GetString(),
		"HTML" => true,
	));
	?>
	    <form action="/bitrix/admin/partner_modules.php">
	    	<input type="hidden" name="lang" value="<?echo LANG?>">
	    	<input type="submit" name="" value="<?echo GetMessage("EDIRECT_MOD_BACK")?>">
	    </form>     
    <?	
}
else{
    echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
    ?>
    <form action="/bitrix/admin/settings.php">
        <input type="hidden" name="mid" value="wtc.easydirect">
        <input type="hidden" name="mid_menu" value="1">
    	<input type="hidden" name="lang" value="<?echo LANG?>">
    	<input type="submit" name="" value="<?echo GetMessage("EDIRECT_MOD_SET")?>">
    </form>     
    <?
}
?>