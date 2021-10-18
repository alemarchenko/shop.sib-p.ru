<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;
?>
<div class="row">
	<div class="col-sm-9 col-md-6">
		<?php
		if(!empty($arParams["~AUTH_RESULT"])):
			$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
		?>
		<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
		<?php endif; ?>

		<?php
		if($arResult['ERROR_MESSAGE'] <> ''):
			$text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']);
		?>
		<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
		<?php endif; ?>

		<h5><?=Loc::getMessage('AUTH_PLEASE_AUTH');?></h5>
		<?php
		if($arResult["AUTH_SERVICES"]) {
			$APPLICATION->IncludeComponent("bitrix:socserv.auth.form",
				"flat",
				array(
					"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
					"AUTH_URL" => $arResult["AUTH_URL"],
					"POST" => $arResult["POST"],
				),
				$component,
				array("HIDE_ICONS"=>"Y")
			);
		}
		?>
		<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" novalidate>
			<input type="hidden" name="AUTH_FORM" value="Y">
			<input type="hidden" name="TYPE" value="AUTH">

			<?php if (strlen($arResult["BACKURL"]) > 0):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
			<?php endif; ?>

			<?php foreach ($arResult["POST"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>">
			<?php endforeach?>

				<div class="form-group bmd-form-group">
					<label for="INPUT_USER_LOGIN" class="bmd-label-floating"><?=Loc::getMessage('AUTH_LOGIN');?><span class="required">*</span></label>
					<input type="text" name="USER_LOGIN" id="INPUT_USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" class="bmd-form-control" required>
					<div class="invalid-feedback"><?=Loc::getMessage('MSG_LOGIN_HINT');?></div>
				</div>


				<div class="form-group bmd-form-group">
					<label for="INPUT_USER_PASSWORD" class="bmd-label-floating">
						<?=Loc::getMessage('AUTH_PASSWORD');?><span class="required">*</span>
					</label>
					<input type="password" name="USER_PASSWORD" id="INPUT_USER_PASSWORD" maxlength="255" class="bmd-form-control" autocomplete="off" required>
					<?php if($arResult["SECURE_AUTH"]):?>
					<small class="form-text text-muted"><?=Loc::getMessage('AUTH_SECURE_NOTE')?></small>
					<?php endif; ?>
					<div class="invalid-feedback"><?=Loc::getMessage('MSG_PASSWORD_HINT');?></div>
				</div>

				<?php if ($arResult['USE_CAPTCHA'] == 'Y'): ?>
				<div class="d-block captcha-wrap mt-5">
					<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
					<label for="<?=$arResult['FORM_NAME']?>_captcha_word" class="small text-extra"><?=Loc::getMessage('MSG_CAPTHA'); ?> <span class="text-danger">*</span></label>
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

				<?php if ($arResult["STORE_PASSWORD"] == "Y"):?>
				<div class="checkbox bmd-custom-checkbox mt-4">
					<label for="USER_REMEMBER">
						<input type="checkbox" class="custom-checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y">
						<?=Loc::getMessage('AUTH_REMEMBER_ME');?>
					</label>
				</div>
				<?php endif; ?>

				<input type="submit" class="mt-4 btn btn-primary" name="Login" value="<?=Loc::getMessage('AUTH_AUTHORIZE');?>">
				<div class="mt-5"><?=Loc::getMessage('AUTH_FORM_NOTE');?></div>
		</form>
		<hr>
		<?php if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
		<noindex><p><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_FORGOT_PASSWORD_2")?></a></p></noindex>
		<?php endif; ?>

		<?php if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
		<noindex>
			<span class="text-extra small"><?=Loc::getMessage("AUTH_FIRST_ONE")?></span><br /><a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_REGISTER")?></a>
		</noindex>
		<?php endif; ?>
	</div>
</div>
<script type="text/javascript">
	(function() {
		'use strict';

		var form = document.forms['form_auth'];

		if (form) {

			form.addEventListener('submit', function (event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
				}

				BX.closeWait();

				form.classList.add('was-validated');
			});

			if (RS.Init) {
				RS.Init(['bmd'], form);
			}

			$(form).find('input[data-mask]').each(function () {
				var maskOptions = {
					mask: this.getAttribute('data-mask')
				};

				var mask = new IMask(this, maskOptions);
			});
		}

		<?php if (strlen($arResult["LAST_LOGIN"])>0): ?>
		try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
		<?php else: ?>
		try{document.form_auth.USER_LOGIN.focus();}catch(e){}
		<?php endif ?>
	})();
</script>
