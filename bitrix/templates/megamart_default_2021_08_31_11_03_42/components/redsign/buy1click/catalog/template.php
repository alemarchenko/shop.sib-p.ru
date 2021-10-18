<?php

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED !== true)
	die();

$request = Application::getInstance()->getContext()->getRequest();

if(empty($arParams['DISABLED_FIELDS']))
{
	$arParams['DISABLED_FIELDS'] = array();
}

if ($request->get('backurl') && strlen($request->get('backurl')) > 0)
{
	$arResult['BACKURL'] = htmlspecialchars($request->get('backurl'));
}

$sBtnSubmitText = isset($arParams['~MESS_SUBMIT'])
	? $arParams['~MESS_SUBMIT']
	: Loc::getMessage('MSG_SUBMIT');
?>
<div class="rsform">
	<?php
	if(!empty($arParams['FORM_DESCRIPTION']))
	{
		?>
		<div class="well"><?=$arParams['FORM_DESCRIPTION']?></div>
		<?php
	}

	if ($arResult['LAST_ERROR'] != '')
	{
		?>
		<div class="alert alert-danger" role="alert"><?=$arResult['LAST_ERROR']?></div>
		<?php
	}

	if ($arResult['GOOD_SEND'] == 'Y')
	{
		?>
		<div class="d-block">
			<div class="mt-6 d-flex align-items-center">
				<div class="d-block text-primary mr-4">
					<svg class="icon-svg" style="font-size: 2.5rem;"><use xlink:href="#svg-select-circle"></use></svg>
				</div>
				<div class="d-block"><?=$arResult['GOOD_ORDER_TEXT']?></div>
			</div>
			<div class="d-block mt-4 text-center"><button class="btn btn-primary" onclick="RS.Panel.openned ? RS.Panel.close() : $.fancybox.close();"><?=Loc::getMessage('MSG_CONTINUE')?></button></div>
		</div>
		<?php
		$jsParams = array();
		if (strlen($arResult['BACKURL']) > 0)
		{
			$jsParams['backurl'] = $arResult['BACKURL'];
		}
/*
		?>
		<script>
		if (window.jQuery && appSLine) {
			appSLine.closePopup(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
		}
		</script>
	<?php
*/
	}
	else
	{
		?>
		<form action="<?=$arResult['ACTION_URL']?>" class="was-validated" method="POST" id="<?=$arResult['FORM_NAME']?>">
			<?=bitrix_sessid_post()?>
			<input type="hidden" name="<?=$arParams['REQUEST_PARAM_NAME']?>" value="Y">

			<?php
			if (strlen($arResult["BACKURL"]) > 0)
			{
				?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>"><?php
			}

			foreach ($arResult['SYSTEM_FIELDS'] as $arField)
			{
				?><input type="hidden" name="<?=$arField['CODE']?>" value="<?=$arField['HTML_VALUE']?>"><?php
			}
			unset($arField);

			if (is_array($arResult['SHOW_FIELDS']) && count($arResult['SHOW_FIELDS']) > 0)
			{
				foreach ($arResult['SHOW_FIELDS'] as $key => $arField)
				{
					?>
					<div class="form-group bmd-form-group">
						<label for="<?=$arField['CODE']?>" class="bmd-label-floating">
							<?php
							echo (strlen($arField['NAME']) > 0)
								? $arField['NAME'].':'
								: Loc::getMessage('MSG_'.$arField['CODE']).':';

							if ($arField['REQUIRED_FIELDS'] == 'Y')
							{
								?> <span>*</span><?php
							}
							?>
						</label>
						<input type="text" id="<?=$arField['CODE']?>" name="<?=$arField['CODE']?>" value="<?=$arField['DEFAULT_VALUE'];?>" class="bmd-form-control" <?php if ($arField['REQUIRED_FIELDS'] == 'Y') echo ' required'?>>
					</div>
					<?php
				}
				unset($arField);
			}

			if ($arParams['ALFA_USE_CAPTCHA'] == 'Y')
			{
				?>
				<div class="d-block captcha-wrap mt-5">
					<label for="<?=$arResult['FORM_NAME']?>_captcha_word" class="small text-extra"><?=Loc::getMessage('MSG_CAPTCHA'); ?> <span class="text-danger">*</span></label>
					<div class="d-flex">
						<div class="d-block flex-grow-1 pr-5">
							<input type="hidden" name="captcha_sid" value="<?=$arResult['CATPCHA_CODE']?>">
							<span class="bmd-form-group">
								<input class="bmd-form-control" type="text" name="captcha_word" id="<?=$arResul['FORM_NAME']?>_captcha_word" size="30" maxlength="50" value="" required>
								<div class="invalid-feedback"><?=Loc::getMessage('MSG_CAPTCHA_HINT');?></div>
							</span>
						</div>
						<div class="d-block">
							<img class="mw-none" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CATPCHA_CODE']?>" alt="CAPTCHA">
						</div>
					</div>
				</div>
				<?php
			}

			if ($arParams['USER_CONSENT'] == 'Y')
			{
				?>
				<div class="my-5">
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.userconsent.request",
						"",
						array(
							"ID" => $arParams["USER_CONSENT_ID"],
							"IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
							"IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
							"AUTO_SAVE" => "Y",
							// "ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
							// "ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
							'INPUT_NAME' => 'CONSENT',
							// 'SUBMIT_EVENT_NAME' => '',
							'REPLACE' => array(
								'button_caption' => $sBtnSubmitText,
								'fields' => $arResult['USER_CONSENT_PROPERTY_DATA'],
							)
						)
					);?>
				</div>
				<?php
			}
			?>
			<div class="d-block mt-5">
				<input type="hidden" name="PARAMS_HASH" value="<?=$arResult['PARAMS_HASH']?>">
				<input type="submit" class="btn btn-primary" name="submit" value="<?=$sBtnSubmitText?>">
			</div>
		</form>
	<script>
	(function() {
		'use strict';

		var form = document.forms['<?=$arResult['FORM_NAME']?>'];

		if (form)
		{
			if (RS.Init)
			{
				RS.Init(['bmd'], form);
			}
		}
	})();
	</script><?php
	}
	?>
</div>

