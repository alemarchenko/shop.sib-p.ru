<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

use \Bitrix\Main\Localization\Loc;
?>

<?$APPLICATION->IncludeComponent(
	'bitrix:iblock.vote',
	'stars',
	array(
		'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
		'IBLOCK_ID' => $item['IBLOCK_ID'],
		'ELEMENT_ID' => $item['ID'],
		'ELEMENT_CODE' => '',
		'MAX_VOTE' => '5',
		'VOTE_NAMES' => array('1', '2', '3', '4', '5'),
		'SET_STATUS_404' => 'N',
		'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'SHOW_RATING' => $arParams['SHOW_RATING'],
		'READ_ONLY' => "Y",
	),
	$component,
	array('HIDE_ICONS' => 'Y')
);?>