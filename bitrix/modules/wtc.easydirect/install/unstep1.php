<?
if(!check_bitrix_sessid()) return;
IncludeModuleLangFile(__FILE__);
?>
<p><?echo GetMessage("EDIRECT_MOD_UNINSTALL"); ?></p>
<form action="<?=$APPLICATION->GetCurPage()?>" name="form1">
    <input type="checkbox" name="savedb" value="Y"> <?echo GetMessage("EDIRECT_MOD_UNINSTALL_SAVE_DB"); ?><br><br>
    <input type="submit" name="uninst" value="<?echo GetMessage("EDIRECT_MOD_UNINSTALL_BUT"); ?>">

    <?echo bitrix_sessid_post(); ?>
    <input type="hidden" name="id" value="wtc.easydirect">
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="step" value="2">
	<input type="hidden" name="lang" value="<?echo LANG?>">
</form>