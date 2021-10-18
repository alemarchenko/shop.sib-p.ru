<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

$arResult['LOCATION_POPUP_TYPE'] = 'window';

if (\Bitrix\Main\Loader::includeModule('redsign.tuning'))
{
	$tuning = \Redsign\Tuning\TuningCore::getInstance();

	if ($tuning)
	{
		$instanceOptionManager = $tuning->getInstanceOptionMananger();

		if ($instanceOptionManager->get('LOCATION_POPUP_TYPE') == 'advanced')
		{
			$arResult['LOCATION_POPUP_TYPE'] = 'fullscreen';
		}
	}
}
