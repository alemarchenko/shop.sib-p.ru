<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;

if ($arResult['PROPERTIES'][$sPropCode]['VALUE']['TYPE'] == 'text')
{
	echo $arResult['PROPERTIES'][$sPropCode]['VALUE']['TEXT'];
}
elseif (isset($arResult['DISPLAY_PROPERTIES'][$sPropCode]))
{
	echo $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'];
}