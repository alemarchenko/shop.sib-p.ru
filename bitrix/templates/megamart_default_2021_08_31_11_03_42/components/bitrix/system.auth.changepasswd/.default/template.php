<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["PHONE_REGISTRATION"])
{
	CJSCore::Init('phone_auth');
}

use \Bitrix\Main\Localization\Loc;
?>
<div class="row">
	<div class="col-sm-9 col-md-7">
		<?php
		if(!empty($arParams["~AUTH_RESULT"])):
		 $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
		?>
			<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
		<?php endif; ?>

		<?if($arResult["SHOW_FORM"]):?>

		<form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
			<?php if (strlen($arResult["BACKURL"]) > 0): ?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
			<?php endif; ?>

			<input type="hidden" name="AUTH_FORM" value="Y">
			<input type="hidden" name="TYPE" value="CHANGE_PWD">

<?if($arResult["PHONE_REGISTRATION"]):?>
			<div class="form-group bmd-form-group">
				<label for="USER_PHONE_NUMBER" class="bmd-label-floating"><?=Loc::getMessage('sys_auth_chpass_phone_number');?><span class="text-danger">*</span></label>
				<input type="text" name="USER_PHONE_NUMBER" id="USER_PHONE_NUMBER" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>" class="bmd-form-control" disabled="disabled">
				<input type="hidden" name="USER_PHONE_NUMBER" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>" />
			</div>
			<div class="form-group bmd-form-group">
				<label for="USER_CHECKWORD" class="bmd-label-floating"><?=Loc::getMessage('sys_auth_chpass_code');?><span class="text-danger">*</span></label>
				<input type="text" name="USER_CHECKWORD" maxlength="50" id="USER_CHECKWORD" value="<?=$arResult["USER_CHECKWORD"]?>" class="bmd-form-control" autocomplete="off">
			</div>
<?else:?>
			<div class="form-group bmd-form-group">
				<label for="USER_LOGIN" class="bmd-label-floating"><?=Loc::getMessage('AUTH_LOGIN');?><span class="text-danger">*</span></label>
				<input type="text" name="USER_LOGIN" id="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" class="bmd-form-control">
			</div>

			<div class="form-group bmd-form-group">
				<label for="USER_CHECKWORD" class="bmd-label-floating"><?=Loc::getMessage('AUTH_CHECKWORD');?><span class="text-danger">*</span></label>
				<input type="text" name="USER_CHECKWORD" id="USER_CHECKWORD" maxlength="255" value="<?=$arResult["USER_CHECKWORD"]?>" class="bmd-form-control">
			</div>
<?endif?>
			<div class="form-group bmd-form-group">
				<label for="USER_PASSWORD" class="bmd-label-floating">
					<?=Loc::getMessage('AUTH_NEW_PASSWORD_REQ');?><span class="text-danger">*</span>
				</label>
				<input type="password" name="USER_PASSWORD" id="USER_PASSWORD" maxlength="255" class="bmd-form-control" autocomplete="off">
				<?php if($arResult["SECURE_AUTH"]):?>
					<br><?=Loc::getMessage("AUTH_SECURE_NOTE")?>
				<?php endif; ?>
			</div>

			<div class="form-group bmd-form-group">
				<label for="USER_CONFIRM_PASSWORD" class="bmd-label-floating">
					<?=Loc::getMessage('AUTH_NEW_PASSWORD_CONFIRM');?><span class="text-danger">*</span>
					<?php if($arResult["SECURE_AUTH"]):?>
						<br><?=Loc::getMessage("AUTH_SECURE_NOTE")?>
					<?php endif; ?>
				</label>
				<input type="password" name="USER_CONFIRM_PASSWORD" id="USER_CONFIRM_PASSWORD" maxlength="255" class="bmd-form-control" autocomplete="off">
			</div>

			<?php if ($arResult['USE_CAPTCHA'] == 'Y'): ?>
			<div class="d-block captcha-wrap mt-5">
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
				<label for="<?=$arResult['FORM_NAME']?>_captcha_word" class="small text-extra"><?=Loc::getMessage('system_auth_captcha'); ?><span class="text-danger">*</span></label>
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


			<div class="mt-4">
				<input type="submit" class="btn btn-primary" name="change_pwd" value="<?=Loc::getMessage('AUTH_CHANGE');?>">
			</div>

			<div class="mt-5">
				<p><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
				<p><span class="text-danger">*</span> - <?=Loc::getMessage('AUTH_REQ');?></p>
			</div>


			<?if($arResult["PHONE_REGISTRATION"]):?>

			<script type="text/javascript">
			new BX.PhoneAuth({
				containerId: 'bx_chpass_resend',
				errorContainerId: 'bx_chpass_error',
				interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
				data:
					<?=CUtil::PhpToJSObject([
						'signedData' => $arResult["SIGNED_DATA"]
					])?>,
				onError:
					function(response)
					{
						var errorDiv = BX('bx_chpass_error');
						var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
						errorNode.innerHTML = '';
						for(var i = 0; i < response.errors.length; i++)
						{
							errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
						}
						errorDiv.style.display = '';
					}
			});
			</script>

			<div id="bx_chpass_error" style="display:none" class="alert alert-danger"><?ShowError("error")?></div>

			<div id="bx_chpass_resend"></div>

			<?endif?>

			<?endif?>
			<p><a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_AUTH")?></a></p>
		</form>
		<script>
		document.bform.USER_LOGIN.focus();
		</script>
	</div>
</div>

