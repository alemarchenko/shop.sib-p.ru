<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

// Loc::loadMessages(__DIR__ . '/user_consent.php');
$request = Application::getInstance()->getContext()->getRequest();
$config = \Bitrix\Main\Web\Json::encode($arResult['CONFIG']);

$linkClassName = 'main-user-consent-request-announce';
if ($arResult['URL'])
{
	$url = htmlspecialcharsbx(\CUtil::JSEscape($arResult['URL']));
	$label = htmlspecialcharsbx($arResult['LABEL']);
	$label = explode('%', $label);
	$label = implode('', array_merge(
		array_slice($label, 0, 1),
		['<a href="' . $url  . '" target="_blank">'],
		array_slice($label, 1, 1),
		['</a>'],
		array_slice($label, 2)
	));
}
else
{
	$label = $arResult['INPUT_LABEL'];
	$linkClassName .= '-link';
}

$inputId = 'CONSTENT_'.$arParams['ID'].'_'.htmlspecialcharsbx($arParams['INPUT_NAME']);

$arMessages = Loc::loadLanguageFile(__DIR__.'/user_consent.php');

?>
<div data-bx-user-consent="<?=htmlspecialcharsbx($config)?>">
	<div class="checkbox bmd-custom-checkbox">
		<label class="mb-0">
			<input type="checkbox" value="Y" <?=($arParams['IS_CHECKED'] ? 'checked' : '')?> name="<?=htmlspecialcharsbx($arParams['INPUT_NAME'])?>" id="<?=$inputId?>"> <?=$label?>
			<div class="invalid-feedback"><?=Loc::getMessage('RS_MUR_CONSENT_HINT'); ?></div>
		</label>
	</div>
</div>
<script type="text/html" data-bx-template="main-user-consent-request-loader">
	<div class="fake-fancybox-container popup-form fancybox-is-open" role="dialog" tabindex="-1" id="fancybox-container-1" style="transition-duration: 300ms;"><?php
		?><div class="fancybox-bg"></div><div class="fancybox-inner"><div class="fancybox-stage"><?php
				?><div class="fancybox-slide fancybox-slide--html fancybox-slide--current fancybox-slide--complete" style=""><?php
					?><div data-bx-loader="" class="fancybox-loading"></div><?php
					?><div data-bx-content="">
						<div data-bx-head="" class="fancybox-title fancybox-title-inside-wrap"></div>
<?
/*
						<button data-fancybox-close="" class="fancybox-close-small"><svg class="icon-svg text-secondary"><use xlink:href="#svg-close"></use></svg></button>
*/
?>
						<div class="fancybox-content">
							<div class="rsform">
								<div class="form-group">
									<div data-bx-textarea="" class="form-control overflow-auto" readonly style="height:200px"></div>
									<div data-bx-link="" style="display: none;" class="main-user-consent-request-popup-link">
										<div><?=Loc::getMessage('MAIN_USER_CONSENT_REQUEST_URL_CONFIRM')?></div>
										<div><a target="_blank"></a></div>
									</div>
								</div>
								<div class="d-block text-center mt-5">
									<span data-bx-btn-accept="" class="btn btn-primary mb-4">Y</span>
									<span data-bx-btn-reject="" class="btn btn-outline-secondary mb-4">N</span>
								</div>
							</div>

						</div>
					</div><?php
				?></div><?php
			?></div><?php
		?></div><?php
	?></div>
</script>
<script>
BX.message(<?=CUtil::PhpToJSObject($arMessages);?>);
</script>
<?php if ($request->isAjaxRequest()): ?>
<script>
if (!!BX.UserConsent)
{
	BX.UserConsent.loadFromForms();
}
else
{
  BX.loadScript('<?=$templateFolder?>/user_consent.js', function(){
	BX.message(<?=CUtil::PhpToJSObject($arMessages);?>);
    BX.UserConsent.loadFromForms();
  });
}
</script>
<?php endif; ?>