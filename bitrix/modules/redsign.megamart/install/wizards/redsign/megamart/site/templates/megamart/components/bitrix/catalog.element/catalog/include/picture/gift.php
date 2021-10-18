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

if ($arParams['USE_GIFTS'] == 'Y' && is_array($arResult['GIFT_ITEMS']) && count($arResult['GIFT_ITEMS']) > 0)
{
	?>
	<span class="product-cat-gift">
		<svg class="product-cat-gift-icon icon-svg"><use xlink:href="#svg-gift"></use></svg>
	</span>
	<?
}