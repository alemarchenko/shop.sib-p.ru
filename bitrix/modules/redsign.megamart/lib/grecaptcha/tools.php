<?php

namespace Redsign\MegaMart\GReCaptcha;

class Tools
{
    public static function getScriptContent($file, $replacements = [], $skipMoving=true, $path = 'modules/redsign.megamart/assets/js/')
    {
        $content = '';
        $filePath = $_SERVER['DOCUMENT_ROOT'].getLocalPath($path).$file;

        if (file_exists($filePath))
        {
            $content .= '<script'.($skipMoving ? ' data-skip-moving="true"' : '').'>';
            $content .= \file_get_contents($filePath);
            $content .= '</script>';
        }

        if (is_array($replacements) && count($replacements) > 0)
        {
            $content = str_replace(
                array_keys($replacements),
                $replacements,
                $content
            );
        }


        return $content;
    }

    public static function getCaptchaCodeBySid($sid)
    {
        $connection = \Bitrix\Main\Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();

        $captchaRes = $connection->query("SELECT CODE FROM b_captcha WHERE id='".$sqlHelper->forSql($sid, 32)."'")->fetch();
        
        if ($captchaRes && isset($captchaRes['CODE']))
        {
            return $captchaRes['CODE'];
        }

        return false;
	}
	
	public static function reInitRequest()
	{
		$application = \Bitrix\Main\Application::getInstance();
		$context = $application->getContext();
		$request = $context->getRequest();
		$server = $context->getServer();

		$httpRequest = new \Bitrix\Main\HttpRequest(
			$server,
			$_REQUEST,
			$_POST,
			$_FILES,
			$_COOKIE
		);

		$context->initialize(
			$httpRequest,
			$context->getResponse(),
			$server,
			[
				'env' => $context->getEnvironment()
			]
		);

		$application->setContext($context);
	}
}