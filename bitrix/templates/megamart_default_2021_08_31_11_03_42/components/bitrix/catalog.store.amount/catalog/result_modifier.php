<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */
 
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

if (Loader::includeModule('redsign.devfunc'))
{
	Redsign\DevFunc\Sale\Location\Region::editCatalogStores($arResult);
}

$arResult['JS']['CLASSES'] = array(
    'NOT_MUCH_GOOD' => 'is-limited',
    'ABSENT' => 'is-outofstock',
    'LOT_OF_GOOD' => 'is-instock',
);

$arResult['JS']['MESSAGES']['NOT_MUCH_GOOD'] = strlen(Loc::getMessage('RS_MM_BCSA_CATALOG_NOT_MUCH_GOOD')) > 0
	? Loc::getMessage('RS_MM_BCSA_CATALOG_NOT_MUCH_GOOD')
	: $arResult['JS']['MESSAGES']['NOT_MUCH_GOOD'];
$arResult['JS']['MESSAGES']['ABSENT'] = strlen(Loc::getMessage('RS_MM_BCSA_CATALOG_ABSENT')) > 0
	? Loc::getMessage('RS_MM_BCSA_CATALOG_ABSENT')
	: $arResult['JS']['MESSAGES']['ABSENT'];
$arResult['JS']['MESSAGES']['LOT_OF_GOOD'] = strlen(Loc::getMessage('RS_MM_BCSA_CATALOG_LOT_OF_GOOD')) > 0
	? Loc::getMessage('RS_MM_BCSA_CATALOG_LOT_OF_GOOD')
	: $arResult['JS']['MESSAGES']['LOT_OF_GOOD'];

if (empty($arResult["STORES"]))
	return;

$arParams['HAS_SHOWED_STOCK'] = false;
foreach ($arResult["STORES"] as $pid => $arProperty)
{
	if (isset($arProperty['REAL_AMOUNT']) && $arProperty['REAL_AMOUNT'] > 0)
	{
		$arParams['HAS_SHOWED_STOCK'] = true;
		break;
	}
}
