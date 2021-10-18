<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader;


if (is_array($arResult['GROUPED_ITEMS']) && count($arResult['GROUPED_ITEMS']) > 0)
{
	
	foreach($arResult['GROUPED_ITEMS'] as $sGroupKey => $arrValue)
	{
		if (!is_array($arrValue['PROPERTIES']) || count($arrValue['PROPERTIES']) < 1)
		{
			unset($arResult['GROUPED_ITEMS'][$sGroupKey]);
		}
	}
}