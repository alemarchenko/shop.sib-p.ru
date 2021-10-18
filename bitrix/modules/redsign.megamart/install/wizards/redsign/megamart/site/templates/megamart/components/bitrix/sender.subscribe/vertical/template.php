<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

$senderSubscribeId = 'sender-subscribe-'.$this->randString();

$layout = new \Redsign\MegaMart\Layouts\Section();

$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing');

$layout->start();
?>
		<div class="l-subscribe-vertical">

			<div class="l-subscribe-vertical__zebra"></div>

			<div class="row align-items-center"><div class="col-12">

				<div class="l-subscribe-vertical__subscribe text-center">
					<div class="l-subscribe-vertical__icon-inbox">
						<img class="l-subscribe-vertical__icon-inbox__picture" src="<?=$templateFolder?>/mail.png">
					</div>
					<div class="l-subscribe-vertical__texts">
						<h5 class="l-subscribe-vertical__text mb-1"><?=Loc::getMessage("subscr_form_h")?></h5>
						<p class="l-subscribe-vertical__text"><?=Loc::getMessage("subscr_form_text")?> </p>
					</div>

					<div class="l-subscribe-vertical__body" id="<?=$senderSubscribeId?>">

						<?php $frame = $this->createFrame($senderSubscribeId, false)->begin(); ?>
						<form role="form" method="post" action="<?=$arResult["FORM_ACTION"]?>">
							<?=bitrix_sessid_post()?>
							<input type="hidden" name="sender_subscription" value="add">

							<div class="d-none">
								<?php foreach ($arResult["RUBRICS"] as $itemID => $itemValue): ?>
								<div class="bx_subscribe_checkbox_container">
									<input class="d-none" type="checkbox" name="SENDER_SUBSCRIBE_RUB_ID[]" id="SENDER_SUBSCRIBE_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?>>
								</div>
								<?php endforeach; ?>
							</div>

							<input class="form-control mb-3" type="email" name="SENDER_SUBSCRIBE_EMAIL" value="<?=$arResult["EMAIL"]?>" title="<?=Loc::getMessage("subscr_form_email_title")?>" placeholder="<?=htmlspecialcharsbx(GetMessage('subscr_form_email_title'))?>">
							<button class="btn btn-primary mb-3 l-subscribe-vertical__buttons" id="bx_subscribe_btn_<?=$buttonId?>"><?=Loc::getMessage("subscr_form_button")?></button>

							<?php if ($arParams['USER_CONSENT'] == 'Y'): ?>
							<div class="bx_subscribe_checkbox_container bx-sender-subscribe-agreement position-absolute invisible">
								<?$APPLICATION->IncludeComponent(
									"bitrix:main.userconsent.request",
									"",
									array(
										"ID" => $arParams["USER_CONSENT_ID"],
										"IS_CHECKED" => 'N',
										"AUTO_SAVE" => "Y",
										'INPUT_NAME' => 'CONSENT',
										"IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
										"ORIGIN_ID" => "sender/sub",
										"ORIGINATOR_ID" => "",
										"REPLACE" => array(
											"button_caption" => Loc::getMessage("subscr_form_button"),
											"fields" => array(Loc::getMessage("subscr_form_email_title"))
										),
									)
								);?>
							</div>
							<?php endif; ?>
						</form>

						<?php $frame->beginStub(); ?>
						<form role="form" method="post" action="<?=$arResult["FORM_ACTION"]?>">
							<?=bitrix_sessid_post()?>
							<input type="hidden" name="sender_subscription" value="add">

							<div style="display: none;">
								<?php foreach($arResult["RUBRICS"] as $itemID => $itemValue): ?>
								<div class="bx_subscribe_checkbox_container">
									<input class="d-none" type="checkbox" name="SENDER_SUBSCRIBE_RUB_ID[]" id="SENDER_SUBSCRIBE_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?>>
								</div>
								<?php endforeach; ?>
							</div>

							<div class="subscribe__pod-1<?=($arParams['SHORT'] == 'Y' ? ' m-short' : '')?>">
								<input class="form-control" type="email" name="SENDER_SUBSCRIBE_EMAIL" value="<?=$arResult["EMAIL"]?>" title="<?=GetMessage("subscr_form_email_title")?>" placeholder="<?=htmlspecialcharsbx(GetMessage('subscr_form_email_title'))?>">
							</div>
							<div class="subscribe__pod-2<?=($arParams['SHORT'] == 'Y' ? ' m-short' : '')?>">
								<button class="btn btn-primary subscribe__btn_subs" id="bx_subscribe_btn_<?=$buttonId?>"><span><?=Loc::getMessage("subscr_form_button")?></span></button>
							</div>
							<div class="clear"></div>

							<?php if ($arParams['USER_CONSENT'] == 'Y'): ?>
							<div class="bx_subscribe_checkbox_container bx-sender-subscribe-agreement position-absolute invisible">
								<?$APPLICATION->IncludeComponent(
									"bitrix:main.userconsent.request",
									"",
									array(
										"ID" => $arParams["USER_CONSENT_ID"],
										"IS_CHECKED" => 'N',
										"AUTO_SAVE" => "Y",
										'INPUT_NAME' => 'CONSENT',
										"IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
										"ORIGIN_ID" => "sender/sub",
										"ORIGINATOR_ID" => "",
										"REPLACE" => array(
											"button_caption" => Loc::getMessage("subscr_form_button"),
											"fields" => array(Loc::getMessage("subscr_form_email_title"))
										),
									)
								);?>
							</div>
							<?php endif; ?>
						</form>
						<?php $frame->end(); ?>
					</div><!-- /.l-subscribe-vertical__body -->

				</div>

			</div></div>

			<div class="l-subscribe-vertical__zebra"></div>

		</div><!-- /.l-subscribe-vertical -->

<?php $layout->end();
