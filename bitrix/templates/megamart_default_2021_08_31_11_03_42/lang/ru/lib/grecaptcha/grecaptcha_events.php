<?php 

$sPhones = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_phones');
$arPhones = unserialize($sPhones);

if (is_array($arPhones) && count($arPhones) > 0)
{
    $sErrorMsg = 'К сожалению, форма не может быть отправлена в настоящее время. Пожалуйста, позвоните нам по телефону '.$arPhones[0].'. и мы ответим на все ваши вопросы.';
}
else
{
    $sErrorMsg = 'К сожалению, форма не может быть отправлена в настоящее время.';
}

$MESS['RS_FORMS_CAPTCHA_ERROR'] = $sErrorMsg;
$MESS['B_B_PC_CAPTCHA_ERROR'] = $sErrorMsg;
$MESS['POSTM_CAPTCHA'] = $sErrorMsg;
$MESS['F_BAD_CAPTCHA'] = $sErrorMsg;
$MESS['FORUM_POSTM_CAPTCHA'] = $sErrorMsg;
$MESS['B_B_PC_CAPTCHA_ERROR'] = $sErrorMsg;
$MESS['MF_CAPTCHA_WRONG'] = $sErrorMsg;
$MESS['MF_CAPTHCA_EMPTY'] = $sErrorMsg;