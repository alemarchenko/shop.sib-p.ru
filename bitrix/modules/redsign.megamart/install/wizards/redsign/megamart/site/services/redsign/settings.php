<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

Option::set('redsign.megamart', 'wizard_installed', 'Y', WIZARD_SITE_ID);

if (Loader::includeModule('redsign.devfunc'))
{
	$arData = array(
		'mp_code' => array('redsign.megamart'),
	);

	$ret = \Redsign\DevFunc\Module::registerInstallation($arData);
}
