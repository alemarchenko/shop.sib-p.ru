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
use \Bitrix\Main\ModuleManager;

if ($arParams['USE_VOTE_RATING'] === 'Y')
{
	?><span class="d-inline-block align-middle py-2 mr-5 font-size-base"><small><?
	$APPLICATION->IncludeComponent(
		'bitrix:iblock.vote',
		'stars',
		array(
			'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
			'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
			'IBLOCK_ID' => $arParams['IBLOCK_ID'],
			'ELEMENT_ID' => $arResult['ID'],
			'ELEMENT_CODE' => '',
			'MAX_VOTE' => '5',
			'VOTE_NAMES' => array('1', '2', '3', '4', '5'),
			//'MAX_VOTE' => '10',
			//'VOTE_NAMES' => array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
			'SET_STATUS_404' => 'N',
			'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
			'CACHE_TYPE' => $arParams['CACHE_TYPE'],
			'CACHE_TIME' => $arParams['CACHE_TIME']
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
	?></small></span><?
}
