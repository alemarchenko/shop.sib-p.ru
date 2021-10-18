<?php

namespace Redsign\MegaMart\GReCaptcha;

use \Bitrix\Main\Context;
use \Bitrix\Main\Web\HttpClient;

class GReCaptchaV3
{
	const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

	private $secretKey = '';
	private $publicKey = '';
	private $minScore  = 0.5;

	private $error = '';

	private $httpClient;
	private $context;


	public function __construct($publicKey, $secretKey, $minScore = 0.5, HttpClient $httpClient = null, Context $context = null)
	{
		$this->publicKey = $publicKey;
		$this->secretKey = $secretKey;
		$this->minScore = $minScore;

		$this->httpClient = !is_null($httpClient) ? $httpClient : new HttpClient();
		$this->context = !is_null($context) ? $context : \Bitrix\Main\Context::getCurrent();
	}

	public function verify($token)
	{
		$this->error = '';

		if (empty($token))
		{
			$this->error = 'Token is empty';
			return false;
		}

		$rawResponse = $this->httpClient->post(
			self::VERIFY_URL,
			[
				'secret' => $this->getSecretKey(),
				'response' => $token
			]
		);

		$response = json_decode($rawResponse);
		
		if ($response->success)
		{
			if (isset($response->score) && $this->checkMinScore($response->score))
			{
				return true;
			}
			else
			{
				$this->error = 'Score too low';
			}
		}
		else
		{
			$this->error = 'Invalid request';
        }

		return false;
	}

	public function checkMinScore($score)
	{
		return $score >= $this->minScore;
	}

	public function getError()
	{
		return $this->error;
	}

	public function getSecretKey()
	{
		return $this->secretKey;
	}

	public function setSecretKey($secretKey)
	{
		$this->secretKey = $secretKey;
	}

	public function getPublicKey()
	{
		return $this->publicKey;
	}

	public function setPublicKey($publicKey)
	{
		$this->publicKey = $publicKey;
	}

	public function setMinScore($minScore)
	{
		$this->minScore = $minScore;
	}
}
