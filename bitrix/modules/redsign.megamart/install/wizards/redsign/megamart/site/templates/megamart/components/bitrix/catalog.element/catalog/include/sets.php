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

if ($haveOffers)
{
	if ($arResult['OFFER_GROUP'])
	{
		foreach ($arResult['OFFER_GROUP_VALUES'] as $offerId)
		{
			?>
			<span id="<?=$itemIds['OFFER_GROUP'].$offerId?>" style="display: none;">
				<?
				$APPLICATION->IncludeComponent(
					'bitrix:catalog.set.constructor',
					'.default',
					array(
						'IBLOCK_ID' => $arResult['OFFERS_IBLOCK'],
						'ELEMENT_ID' => $offerId,
						'PRICE_CODE' => $arParams['PRICE_CODE'],
						'BASKET_URL' => $arParams['BASKET_URL'],
						'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
						'CACHE_TYPE' => $arParams['CACHE_TYPE'],
						'CACHE_TIME' => $arParams['CACHE_TIME'],
						'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
						'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
						'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
						'CURRENCY_ID' => $arParams['CURRENCY_ID']
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				);
				?>
			</span>
			<?
		}
	}
}
else
{
	if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP'])
	{
		$APPLICATION->IncludeComponent(
			'bitrix:catalog.set.constructor',
			'.default',
			array(
				'IBLOCK_ID' => $arParams['IBLOCK_ID'],
				'ELEMENT_ID' => $arResult['ID'],
				'PRICE_CODE' => $arParams['PRICE_CODE'],
				'BASKET_URL' => $arParams['BASKET_URL'],
				'CACHE_TYPE' => $arParams['CACHE_TYPE'],
				'CACHE_TIME' => $arParams['CACHE_TIME'],
				'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
				'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
				'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
				'CURRENCY_ID' => $arParams['CURRENCY_ID']
			),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
	}
}