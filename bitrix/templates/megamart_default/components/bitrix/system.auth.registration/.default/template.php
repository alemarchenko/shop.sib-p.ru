<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Localization\Loc;

if($arResult["SHOW_SMS_FIELD"] == true)
{
	CJSCore::Init('phone_auth');
}
?>
<div class="row">
	<div class="col-sm-9 col-md-6">
		<?php
		if (!empty($arParams["~AUTH_RESULT"]))
		{
			$text = '';
			if (is_array($arParams['~AUTH_RESULT']['MESSAGE']))
			{
				$text = implode('<br>', $arParams['~AUTH_RESULT']['MESSAGE']);
				
			}
			else
			{
				$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
			}
		  
			?>
			<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
			<?php
		}
		?>

		<?php if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
	  	<div class="alert alert-success"><?=Loc::getMessage("AUTH_EMAIL_SENT")?></div>
		<?php endif; ?>

		<?php if(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"] && $arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	  	<div class="alert alert-warning"><?=Loc::getMessage("AUTH_EMAIL_WILL_BE_SENT")?></div>
		<?php endif; ?>
		<!--noindex-->

		<?if($arResult["SHOW_SMS_FIELD"] == true):?>

			<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="regform">
				<input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult["SIGNED_DATA"])?>" />
				<div class="form-group bmd-form-group">
					<label for="INPUT_SMS_CODE" class="bmd-label-floating"><?=Loc::getMessage('main_register_sms_code');?><span class="text-danger">*</span></label>
					<input size="30" type="text" name="SMS_CODE" id="INPUT_SMS_CODE" maxlength="255" value="<?=htmlspecialcharsbx($arResult["SMS_CODE"])?>" class="bmd-form-control" autocomplete="off">
				</div>
				<div class="form-group bmd-form-group">
					<input type="submit" class="btn btn-primary" name="code_submit_button" value="<?=Loc::getMessage('main_register_sms_send');?>">
				</div>
			</form>

			<script>
			new BX.PhoneAuth({
				containerId: 'bx_register_resend',
				errorContainerId: 'bx_register_error',
				interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
				data:
					<?=CUtil::PhpToJSObject([
						'signedData' => $arResult["SIGNED_DATA"],
					])?>,
				onError:
					function(response)
					{
						var errorDiv = BX('bx_register_error');
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

			<div id="bx_register_error" class="alert alert-danger" style="display:none"><?ShowError("error")?></div>

			<div id="bx_register_resend"></div>

		<?elseif(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"]):?>
			<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data" novalidate>
				<?php if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
				<?php endif; ?>

				<input type="hidden" name="AUTH_FORM" value="Y">
				<input type="hidden" name="TYPE" value="REGISTRATION">

				<div class="form-group bmd-form-group">
					<label for="INPUT_USER_NAME" class="bmd-label-floating"><?=Loc::getMessage('AUTH_NAME');?></label>
					<input type="text" name="USER_NAME" id="INPUT_USER_NAME" maxlength="255" value="<?=$arResult["USER_NAME"]?>" class="bmd-form-control">
				</div>

				<div class="form-group bmd-form-group">
					<label for="INPUT_USER_LAST_NAME" class="bmd-label-floating"><?=Loc::getMessage('AUTH_LAST_NAME');?></label>
					<input type="text" name="USER_LAST_NAME" id="INPUT_USER_LAST_NAME" maxlength="255" value="<?=$arResult["USER_LAST_NAME"]?>" class="bmd-form-control">
				</div>

				<div class="form-group bmd-form-group">
					<label for="INPUT_USER_LOGIN" class="bmd-label-floating"><?=Loc::getMessage('AUTH_LOGIN_MIN');?><span class="text-danger">*</span></label>
					<input type="text" name="USER_LOGIN" id="INPUT_USER_LOGIN" maxlength="255" value="<?=$arResult["USER_LOGIN"]?>" class="bmd-form-control" pattern="^.{3,}$" required>
					<div class="invalid-feedback"><?=Loc::getMessage('MSG_LOGIN_HINT');?></div>
				</div>

				<div class="form-group bmd-form-group">
					<label for="INPUT_USER_PASSWORD" class="bmd-label-floating">
						<?=Loc::getMessage('AUTH_PASSWORD_REQ');?><span class="text-danger">*</span>
						<!-- <?php if($arResult["SECURE_AUTH"]):?>
							<br><?=Loc::getMessage("AUTH_SECURE_NOTE")?>
						<?php endif; ?> -->
					</label>
					<input type="password" name="USER_PASSWORD" id="INPUT_USER_PASSWORD" maxlength="255" class="bmd-form-control" autocomplete="off" required>
					<div class="invalid-feedback"><?=Loc::getMessage('MSG_PASSWORD_HINT');?></div>
				</div>

				<div class="form-group bmd-forum-group">
					<label for="INPUT_USER_CONFIRM_PASSWORD" class="bmd-label-floating">
						<?=Loc::getMessage('AUTH_CONFIRM');?><span class="text-danger">*</span>
					</label>
					<input type="password" name="USER_CONFIRM_PASSWORD" id="INPUT_USER_CONFIRM_PASSWORD" maxlength="255" class="bmd-form-control" autocomplete="off" required>
					<?php if($arResult["SECURE_AUTH"]):?>
					<small class="form-text text-muted"><?=Loc::getMessage('AUTH_SECURE_NOTE')?></small>
					<?php endif; ?>
					<div class="invalid-feedback"><?=Loc::getMessage('MSG_CONFIRM_HINT');?></div>
				</div>
<?if($arResult["EMAIL_REGISTRATION"]):?>
				<div class="form-group bmd-form-group">
					<label for="INPUT_USER_EMAIL" class="bmd-label-floating"><?=Loc::getMessage('AUTH_EMAIL');?><?php if($arResult["EMAIL_REQUIRED"]): ?><span class="text-danger">*</span><?php endif; ?></label>
					<input type="text" name="USER_EMAIL" id="INPUT_USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" class="bmd-form-control"<?php if($arResult["EMAIL_REQUIRED"]): ?> required<?php endif;?>>
					<div class="invalid-feedback"><?=Loc::getMessage('MSG_EMAIL_HINT');?></div>
				</div>
<?endif?>

<?if($arResult["PHONE_REGISTRATION"]):?>
				<div class="form-group bmd-form-group">
					<label for="INPUT_USER_PHONE_NUMBER" class="bmd-label-floating"><?=Loc::getMessage('main_register_phone_number');?><?php if($arResult["PHONE_REQUIRED"]): ?><span class="text-danger">*</span><?php endif; ?></label>
					<input type="text" name="USER_PHONE_NUMBER" id="INPUT_USER_PHONE_NUMBER" maxlength="255" value="<?=$arResult["USER_PHONE_NUMBER"]?>" class="bmd-form-control"<?php if($arResult["PHONE_REQUIRED"]): ?> required<?php endif;?>>
					<div class="invalid-feedback"><?=Loc::getMessage('MSG_EMAIL_HINT');?></div>
				</div>
<?endif?>

<?// ********************* User properties ***************************************************?>
<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
				<?php foreach ($arResult["USER_PROPERTIES"]["DATA"] as $fieldName => $arUserField): ?>
				<div class="bmd-form-group">
					<label for="INPUT_USER_LOGIN" class="bmd-label-floating"><?=$arUserField["EDIT_FORM_LABEL"]?><?if ($arUserField["MANDATORY"]=="Y"):?><span class="text-danger">*</span><?php endif; ?></label>
					<?$APPLICATION->IncludeComponent(
					  	"bitrix:system.field.edit",
					  	$arUserField["USER_TYPE"]["USER_TYPE_ID"],
					  	array(
								"bVarsFromForm" => $arResult["bVarsFromForm"],
								"arUserField" => $arUserField,
								"form_name" => "bform"
					  	),
					  	null,
					  	array("HIDE_ICONS"=>"Y")
					);?>
				</div>
				<?php endforeach; ?>
<?endif;?>
<?// ******************** /User properties ***************************************************

	/* CAPTCHA */
	if ($arResult["USE_CAPTCHA"] == "Y")
	{
		?>
			<div class="d-block captcha-wrap mt-5">
				<label for="<?=$arResult['FORM_NAME']?>_captcha_word" class="small text-extra"><?=Loc::getMessage('CAPTCHA_REGF_PROMT'); ?>  (<a href="#" data-captcha-update-code><?=Loc::getMessage('CAPTCHA_REGF_UPDATE')?></a>)<span class="text-danger">*</span></label>
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
			<?
	}
	/* CAPTCHA */
	?>

				<div class="mt-4">
				<?$APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "",
					array(
						"ID" => COption::getOptionString("main", "new_user_agreement", ""),
						"IS_CHECKED" => "Y",
						"AUTO_SAVE" => "N",
						"IS_LOADED" => "Y",
						"ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
						"ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
						"INPUT_NAME" => $arResult["AGREEMENT_INPUT_NAME"],
						"REPLACE" => array(
							"button_caption" => GetMessage("AUTH_REGISTER"),
							"fields" => array(
								rtrim(GetMessage("AUTH_NAME"), ":"),
								rtrim(GetMessage("AUTH_LAST_NAME"), ":"),
								rtrim(GetMessage("AUTH_LOGIN_MIN"), ":"),
								rtrim(GetMessage("AUTH_PASSWORD_REQ"), ":"),
								rtrim(GetMessage("AUTH_EMAIL"), ":"),
							)
						),
					)
				);?>
				</div>

				<div class="mt-4">
					<input type="submit" class="btn btn-primary" name="Register" value="<?=Loc::getMessage('AUTH_REGISTER');?>">
				</div>

				<div class="mt-5">
					<p><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
					<p><span class="text-danger">*</span> - <?=Loc::getMessage('AUTH_REQ');?></p>
					<a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_AUTH")?></a>
				</div>

			</form>
<?endif?>
		<script>
		(function() {
			'use strict';
			<?if($arResult["SHOW_SMS_FIELD"] == true):?>
			var form = document.forms['regform'];
			<?elseif(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"]):?>
			var form = document.forms['bform'];
			<?endif?>

			if (form) {

				form.addEventListener('submit', function (event) {
					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
					}

					BX.closeWait();

					form.classList.add('was-validated');
				});

				$(form).find('input[data-mask]').each(function () {
					var maskOptions = {
						mask: this.getAttribute('data-mask')
					};

					var mask = new IMask(this, maskOptions);
				});
			}
		})();
		</script>
		<!--/noindex-->
	</div>
</div>
