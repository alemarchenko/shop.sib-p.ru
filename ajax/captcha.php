<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php";

$cpt = new CCaptcha();

$sCaptchaCode = $APPLICATION->CaptchaGetCode();

$arResult = array(
    'code' => $sCaptchaCode,
    'src' => '/bitrix/tools/captcha.php?captcha_sid='.$sCaptchaCode
);

echo \Bitrix\Main\Web\Json::encode($arResult);