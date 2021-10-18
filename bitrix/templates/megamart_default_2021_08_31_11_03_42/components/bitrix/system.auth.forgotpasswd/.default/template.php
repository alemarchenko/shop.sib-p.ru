<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

use \Bitrix\Main\Localization\Loc;

?>
<div class="row">
	<div class="col-sm-9 col-md-6">
		<?php
		if(!empty($arParams["~AUTH_RESULT"])):
			$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
		?>
		<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
		<?php endif; ?>

		<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
			<?
			if (strlen($arResult["BACKURL"]) > 0)
			{
			?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?
			}
			?>

			<input type="hidden" name="AUTH_FORM" value="Y">
			<input type="hidden" name="TYPE" value="SEND_PWD">

			<p><?=Loc::getMessage('sys_forgot_pass_label')?></p>

			<div class="form-group bmd-form-group">
				<label for="USER_LOGIN" class="bmd-label-floating"><?=Loc::getMessage('sys_forgot_pass_login1');?> </label>
				<input type="text" name="USER_LOGIN" id="USER_LOGIN" maxlength="255" value="<?=$arResult["USER_LOGIN"]?>" class="bmd-form-control" aria-describedby="USER_LOGIN_HELP">
				<input type="hidden" name="USER_EMAIL" />
				<small id="USER_LOGIN_HELP" class="form-text text-muted"><?=Loc::getMessage('sys_forgot_pass_note_email')?></small>
			</div>

			<?if($arResult["PHONE_REGISTRATION"]):?>
				<div class="form-group bmd-form-group">
					<label for="USER_PHONE_NUMBER" class="bmd-label-floating"><?=Loc::getMessage('sys_forgot_pass_phone');?> </label>
					<input type="text" name="USER_PHONE_NUMBER" id="USER_PHONE_NUMBER" value="" class="bmd-form-control" aria-describedby="USER_PHONE_NUMBER_HELP">
					<input type="hidden" name="USER_EMAIL" />
					<small id="USER_PHONE_NUMBER_HELP" class="form-text text-muted"><?=Loc::getMessage('sys_forgot_pass_note_phone')?></small>
				</div>
			<?endif;?>

			<?php if ($arResult['USE_CAPTCHA'] == 'Y'): ?>
			<div class="d-block captcha-wrap mt-5">
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
				<label for="<?=$arResult['FORM_NAME']?>_captcha_word" class="small text-extra"><?=Loc::getMessage('system_auth_captcha'); ?> <span class="text-danger">*</span></label>
				<div class="d-flex">
					<div class="d-block flex-grow-1 pr-5">
						<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
						<span class="bmd-form-group">
							<input class="bmd-form-control" type="text" name="captcha_word" id="<?=$arResul['FORM_NAME']?>_captcha_word" size="30" maxlength="50" value="" required>
							<div class="invalid-feedback"><?=Loc::getMessage('MSG_CAPTCHA_HINT');?></div>
						</span>
					</div>
					<div class="d-block">
						<img class="mw-none" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div class="mt-5">
				<input type="submit" class="btn btn-primary" name="send_account_info" value="<?=Loc::getMessage('AUTH_SEND');?>">
			</div>
			<div class="mt-4">
				<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><?=Loc::getMessage("AUTH_AUTH")?></a>
			</div>

		</form>
<script type="text/javascript">
document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
document.bform.USER_LOGIN.focus();
</script>
	</div>
</div>
