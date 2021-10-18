<?php

namespace Redsign\MegaMart\GReCaptcha;

use \Bitrix\Main\Config\Option;

class GReCaptchaEvents
{
	public static function onPageStart()
	{
		if (!\Bitrix\Main\Loader::includeModule('redsign.megamart'))
		{
			return;
		}
		
		$bEnabled = Option::get('redsign.megamart', 'global_grecaptcha_enabled', '', SITE_ID) == 'Y';
		if (!$bEnabled)
		{
			return;
		}

		$sPublicKey = Option::get('redsign.megamart', 'global_grecaptcha_public_key', '', SITE_ID);
		$sSecretKey = Option::get('redsign.megamart', 'global_grecaptcha_private_key', '', SITE_ID);
		$nMinScore = Option::get('redsign.megamart', 'global_grecaptcha_min_score', '', SITE_ID);
		$sBadgeBlockId = Option::get('redsign.megamart', 'global_grecaptcha_block_id', '', SITE_ID);
		$sRemoveSelectors = Option::get('redsign.megamart', 'global_grecaptcha_remove_selectors', '', SITE_ID);
		
		$asset = \Bitrix\Main\Page\Asset::getInstance();
		$asset->addString(Tools::getScriptContent('grecaptcha_callback.js', ['#PUBLIC_KEY#' => $sPublicKey]));
		$asset->addString('<script src="https://www.google.com/recaptcha/api.js?render=explicit&onload=_grecaptchaCallback"></script>');
		$asset->addString(Tools::getScriptContent('grecaptcha_replace.js', ['#SELECTORS#' => $sRemoveSelectors], false));
		
		$request = \Bitrix\Main\Context::getCurrent()->getRequest();
		
		$sToken = $request->getPost("g-recaptcha-response");
		$sCaptchaSid = !empty($request->getPost("captcha_sid")) ? $request->getPost("captcha_sid") : $request->getPost("captcha_code");

		$isUsing = !empty($sCaptchaSid);

		if ($isUsing)
		{
			$_REQUEST['captcha_word'] = $_POST['captcha_word'] = '';
			$_REQUEST['captcha_sid'] = $_REQUEST['captcha_code'] = $sCaptchaSid;
			$_POST['captcha_sid'] = $_POST['captcha_code'] = $sCaptchaSid;
		}
		
		if ($isUsing && !empty($sToken))
		{
			$grecaptcha = new GReCaptchaV3($sPublicKey, $sSecretKey, $nMinScore);
			
			if ($grecaptcha->verify($sToken))
			{
				$captchaCode = Tools::getCaptchaCodeBySid($sCaptchaSid);

				if ($captchaCode)
				{
					$_REQUEST['captcha_word'] = $_POST['captcha_word'] = $captchaCode;
					
					$_REQUEST['captcha_sid'] = $_REQUEST['captcha_code'] = $sCaptchaSid;
					$_POST['captcha_sid'] = $_POST['captcha_code'] = $sCaptchaSid;
				}
			}
		}

		if ($isUsing)
		{
			Tools::reInitRequest();
		}
		
		//custom errors
		\Bitrix\Main\Localization\Loc::loadCustomMessages(__FILE__);
	}
}
