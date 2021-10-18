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

if ($haveOffers)
{
	?>
	<span class="product-detail-artnumber align-middle py-2 mr-5" data-entity="sku-prop-<?=$actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['ID']?>">
		<?php
		if (!empty($actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['VALUE']))
		{
			echo str_replace(
				'#NUMBER#',
				$actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['VALUE'],
				$arParams['MESS_ITEM_ARTNUMBER']
			);
		}
		?>
	</span>
	<?php
}
elseif (!empty($arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE']))
{
	?>
	<span class="product-detail-artnumber align-middle py-2 mr-5">
		<?php
		echo str_replace(
			'#NUMBER#',
			$arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE'],
			$arParams['MESS_ITEM_ARTNUMBER']
		);
		?>
	</span>
	<?php
}
