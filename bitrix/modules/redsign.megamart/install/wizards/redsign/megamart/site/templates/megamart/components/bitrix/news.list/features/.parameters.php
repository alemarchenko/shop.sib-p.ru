<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;


Loc::loadMessages(__FILE__);

$defaultValue = array('-' => Loc::getMessage('RS_MM_UNDEFINED'));

$arProperties = array(
	'SNL' => array(),
);
if (Loader::includeModule('redsign.devfunc'))
{
	if ($arCurrentValues['IBLOCK_ID'] > 0)
	{
		$arProperties = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);
	}
}

$arTemplateParameters = array(
    'ICON_PROP' => array(
		'NAME' => Loc::getMessage('RS_MM_ICON_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => $defaultValue + $arProperties['SNL'],
        'DEFAULT' => '-',
	),
    'ICON_BODYMOVIN_PROP' => array(
		'NAME' => Loc::getMessage('RS_MM_ICON_BODYMOVIN_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => $defaultValue + $arProperties['SNL'],
        'DEFAULT' => '-',
	),
	'LINK_PROP' => array(
		'NAME' => Loc::getMessage('RS_MM_LINK_PROP'),
		'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arProperties['SNL'],
        'DEFAULT' => '-',
	),
	'TARGET_PROP' => array(
		'NAME' => Loc::getMessage('RS_MM_TARGET_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => $defaultValue + $arProperties['SNL'],
        'DEFAULT' => '-',
	),
);

if (Loader::includeModule('redsign.megamart'))
{
	ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('gridSettings'));

	// if ($arCurrentValues['USE_OWL'] == 'Y') {
		// ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSettings'));
	// } else {
		// ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('bootstrapCols'));
	// }
}