<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

if (!isset($arParams['DISABLED_FIELDS']) || !is_array($arParams['DISABLED_FIELDS'])) {
	$arParams['DISABLED_FIELDS'] = array();
}

$sBtnSubmitText = isset($arParams['~MESS_SUBMIT'])
	? $arParams['~MESS_SUBMIT']
	: Loc::getMessage('MSG_SUBMIT');
?>
<div class="rsform">
	<?php if (count($arResult['MESSAGES']['ERRORS']) > 0): ?>
	<div class="alert alert-danger"><?php foreach ($arResult['MESSAGES']['ERRORS'] as $msg): ?><?=$msg?><br><?php endforeach; ?></div>
	<script>$(document).trigger("rs_forms.error");</script>
	<?php endif; ?>

	<?php if (count($arResult['MESSAGES']['SUCCESS']) > 0): ?>
	<div class="mt-6 d-flex align-items-center">
		<div class="d-block text-primary mr-4">
			<svg class="icon-svg" style="font-size: 2.5rem;"><use xlink:href="#svg-select-circle"></use></svg>
		</div>
		<div class="d-block">
			<?php if (!empty($arParams['SUCCESS_MESSAGE'])): ?>
				<?=$arParams['SUCCESS_MESSAGE']?>
			<?php else: ?>
				<?=implode('<br>', $arResult['MESSAGES']['SUCCESS']);?>
			<?php endif; ?>
		</div>
	</div>
	<?php else: ?>

	<form action="<?=$arResult['REQUEST_URI']?>" method="POST" id="<?=$arResult['FORM_NAME']?>" novalidate>
		<?=bitrix_sessid_post()?>
		<div class="rsform__all-field clearfix">
			<div class="row">
				<?php
				foreach ($arResult['FIELDS'] as $key => $arField):

					$disabled = in_array($arField['CODE'], $arParams['DISABLED_FIELDS']) ? ' readonly' : '';
				?>
					<?php if ($arField['PROPERTY_TYPE'] == 'S'): ?>
						<div class="col-12 col-lg-<?php if ($arField['USER_TYPE'] == 'HTML') {echo 12;} else {echo 6;} ?>">
							<div class="form-group bmd-form-group">
								<label for="FIELD_<?=$arField['CODE']?>" class="bmd-label-floating">
									<?=$arField['NAME']?>:  <?php if ($arField['IS_REQUIRED'] == 'Y'): ?><span>*</span><?php endif; ?>
								</label>
								<?php if ($arField['USER_TYPE'] == 'HTML'): ?>
									<?php $arUserTypeSettings = unserialize($arField['USER_TYPE_SETTINGS']); ?>
									<textarea id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" class="bmd-form-control<?=$disabled?>"<?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required'?> style="height: <?=$arUserTypeSettings['height']?>px"<?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required'?>><?=$arField['CURRENT_VALUE']?></textarea>
								<?php else: ?>
									<input <?php if(isset($arField['MASK'])) echo 'data-mask="'.$arField['MASK'].'"'; ?> type="<?=$arField['INPUT_TYPE']?>"<?php if(!empty($arField['PATTERN'])) echo ' pattern="'.$arField['PATTERN'].'"'; ?> id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" value="<?=$arField['CURRENT_VALUE']?>" class="bmd-form-control<?=$disabled?>"<?=$disabled?><?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required'?>>
								<?php endif; ?>
								<?php if(!empty($arField['HINT'])): ?>
								<div class="invalid-feedback"><?=$arField['HINT']?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php elseif ($arField['PROPERTY_TYPE'] == 'L' && is_array($arField['VALUES'])): ?>
						<div class="col-12 col-lg-6">
							<div class="form-group bmd-form-group">
								<label for="FIELD_<?=$arField['CODE']?>"><?=$arField['NAME']?>:</label>
								<select class="bmd-form-control" id="FIELD_<?=$arField['CODE']?>">
									<?php foreach ($arField['VALUES'] as $i => $arValue): ?>
										<option <?php if ((empty($arField['CURRENT_VALUE']) && $i == 0) || $arField['CURRENT_VALUE'] == $arValue['ID']): ?>selected="selected"<?php endif; ?> value="<?=$arValue['ID']?>"><?=$arValue['VALUE']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ($arResult['USE_CAPTCHA'] == 'Y'): ?>
		<div class="d-block captcha-wrap mt-5">
			<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
			<label for="<?=$arResult['FORM_NAME']?>_captcha_word" class="small text-extra"><?=Loc::getMessage('MSG_CAPTHA'); ?> (<a href="#" data-captcha-update-code><?=Loc::getMessage('MSG_UPDATE_CAPTCHA')?></a>) <span class="text-danger">*</span></label>
			<div class="d-flex">
				<div class="d-block flex-grow-1 pr-5">
					<span class="bmd-form-group">
						<input class="bmd-form-control" type="text" name="captcha_word" id="<?=$arResul['FORM_NAME']?>_captcha_word" size="30" maxlength="50" value="" autocomplete="off" required>
						<div class="invalid-feedback"><?=Loc::getMessage('MSG_CAPTCHA_HINT');?></div>
					</span>
				</div>
				<img style="height: 38px; width: 170px;" class="mw-none" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
					<div class="d-block text-center">
						<a href="#" class="small" data-captcha-update-code><?=Loc::getMessage('MSG_UPDATE_CAPTCHA')?></a>
					</div>
			</div>
		</div>
		<?php endif; ?>

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

		<div class="d-block mt-5">
			<input type="hidden" name="PARAMS_HASH" value="<?=$arResult['PARAMS_HASH']?>">
			<input type="submit" class="btn btn-primary" name="submit" value="<?=$sBtnSubmitText?>">
		</div>

	</form>
	<?php endif; ?>
</div>
