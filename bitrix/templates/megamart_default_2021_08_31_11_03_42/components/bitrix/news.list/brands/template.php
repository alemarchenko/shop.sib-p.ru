<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
 

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\MyTemplate;

$this->setFrameMode(true);

$sBlockId = 'rs-brands-'.randString(5);

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{
	$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
	$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
	$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'));

	include(MyTemplate::getTemplatePart($templateFolder.'/templates/'.ToLower($arParams['RS_TEMPLATE']).'.php'));
}
