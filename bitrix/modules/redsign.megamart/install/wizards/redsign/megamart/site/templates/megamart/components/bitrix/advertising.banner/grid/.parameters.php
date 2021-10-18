<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

Loc::loadMessages(__FILE__);


if (Loader::includeModule('redsign.megamart'))
{
	ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('gridSettings'));
}
