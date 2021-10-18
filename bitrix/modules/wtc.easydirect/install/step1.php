<?
IncludeModuleLangFile(__FILE__);
?>
<p><?echo GetMessage("EDIRECT_MOD_INSTALL"); ?></p>
<form action="<?=$APPLICATION->GetCurPage()?>" name="form1">
    <?echo bitrix_sessid_post(); ?>
    <input type="hidden" name="id" value="wtc.easydirect">
	<input type="hidden" name="install" value="Y">
	<input type="hidden" name="step" value="2">
	<input type="hidden" name="lang" value="<?echo LANG?>">
	<input type="submit" name="inst" value="<?echo GetMessage("MOD_INSTALL"); ?>">
</form>  