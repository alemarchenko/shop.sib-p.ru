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

if (!$haveOffers)
{
	if ($showSubscribe)
	{
		$APPLICATION->IncludeComponent(
			'bitrix:catalog.product.subscribe',
			'',
			array(
				'PRODUCT_ID' => $actualItem['ID'],
				'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
				'BUTTON_CLASS' => 'btn btn-outline-primary '.$buttonSizeClass,
				'DEFAULT_DISPLAY' => true,
				'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
				'USE_CAPTCHA' => 'Y',
			),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
	}
}
else
{
	if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
	{
		if ($showSubscribe)
		{
			$APPLICATION->IncludeComponent(
				'bitrix:catalog.product.subscribe',
				'',
				array(
					'PRODUCT_ID' => $item['ID'],
					'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
					'BUTTON_CLASS' => 'btn btn-outline-primary '.$buttonSizeClass,
					'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
					'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
					'USE_CAPTCHA' => 'Y',
				),
				$component,
				array('HIDE_ICONS' => 'Y')
			);
		}
	}
}