<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 */

use \Bitrix\Main\Localization\Loc;

$arLimitInfo = array(
	'MANY' => array(
		'MESS' => $arParams['MESS_RELATIVE_QUANTITY_MANY'],
		'CLASS' => 'is-instock',
		'ICON' => 'check',
	),
	'FEW' => array(
		'MESS' => $arParams['MESS_RELATIVE_QUANTITY_FEW'],
		'CLASS' => 'is-limited',
		'ICON' => 'bolt',
	),
	'NONE' => array(
		'MESS' => $arParams['MESS_NOT_AVAILABLE'],
		'CLASS' => 'is-outofstock',
		'ICON' => 'meh',
	),
);

if (
	$item['ITEM_MEASURE_RATIO']
	&& (float)$item['CATALOG_QUANTITY'] > 0
	&& $item['CATALOG_QUANTITY_TRACE'] === 'Y'
	&& $item['CATALOG_CAN_BUY_ZERO'] === 'N'
)
{
	if ((float)$item['CATALOG_QUANTITY'] / $item['ITEM_MEASURE_RATIO'] >= $arParams['RELATIVE_QUANTITY_FACTOR'])
	{
		$arCurLimitInfo = $arLimitInfo['MANY'];
	}
	else
	{
		$arCurLimitInfo = $arLimitInfo['FEW'];
	}
	?>
	<span class="product-cat-limit <?=$arCurLimitInfo['CLASS']?>">
		<?php
		if (strlen($arParams['MESS_SHOW_MAX_QUANTITY']) > 0)
		{
			echo $arParams['MESS_SHOW_MAX_QUANTITY'].':';
		}
		?>
		<span class="product-cat-limit-quantity">
			<?
			if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
			{
				echo $arCurLimitInfo['MESS'];
			}
			else
			{
				echo $item['CATALOG_QUANTITY'].' '.$item['ITEM_MEASURE']['TITLE'];
			}
			?>
		</span>
	</span>
	<?
}
elseif (!$item['CAN_BUY'])
{
	?>
	<span class="product-cat-limit <?=$arLimitInfo['NONE']['CLASS']?>">
		<span class="product-cat-limit-quantity"><?=$arLimitInfo['NONE']['MESS']?></span>
	</span>
	<?
}
