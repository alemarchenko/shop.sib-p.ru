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
?>
<span class="d-inline-block align-middle py-2 d-inline-block-for-subscribe">
	<?php
	if ($haveOffers)
	{
		if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
		{
			?>
			<span class="product-cat-limit <?=$arCurLimitInfo['CLASS']?>" id="<?=$itemIds['QUANTITY_LIMIT']?>" style="display: none;">
				<?php
				if (strlen($arParams['MESS_SHOW_MAX_QUANTITY']) > 0)
				{
					echo $arParams['MESS_SHOW_MAX_QUANTITY'].':';
				}
				?>
				<span class="product-cat-limit-quantity" data-entity="quantity-limit-value"></span>
			</span>
			<span class="product-cat-limit <?=$arLimitInfo['NONE']['CLASS']?>"
				id="<?=$itemIds['NOT_AVAILABLE_MESS']?>"
				style="display: <?=($actualItem['CAN_BUY'] ? 'none' : '')?>;">
				<span class="product-cat-limit-quantity"><?=$arLimitInfo['NONE']['MESS']?></span>
			</span>
			<?
		}
	}
	else
	{
		if (
			$measureRatio
			&& (float)$actualItem['PRODUCT']['QUANTITY'] > 0
			&& $actualItem['CHECK_QUANTITY']
		)
		{
			
			if ((float)$actualItem['PRODUCT']['QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
			{
				$arCurLimitInfo = $arLimitInfo['MANY'];
			}
			else
			{
				$arCurLimitInfo = $arLimitInfo['FEW'];
			}
			?>
			<span class="product-cat-limit <?=$arCurLimitInfo['CLASS']?>" id="<?=$itemIds['QUANTITY_LIMIT']?>">
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
						echo $actualItem['PRODUCT']['QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
					}
					?>
				</span>
			</span>
			<?
		}
		elseif ($actualItem['CHECK_QUANTITY'] && !$actualItem['CAN_BUY'])
		{
			?>
			<span class="product-cat-limit <?=$arLimitInfo['NONE']['CLASS']?>"
				id="<?=$itemIds['NOT_AVAILABLE_MESS']?>">
				<span class="product-cat-limit-quantity"><?=$arLimitInfo['NONE']['MESS']?></span>
			</span>
			<?
		}
	}
?>
</span>