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

$dropdownId = $this->getEditAreaId('dd');
?>
<div class="dropdown">
	<button class="btn btn-outline-secondary btn-sm dropdown-toggle mb-3" type="button" id="<?=$dropdownId?>" data-toggle="dropdown" aria-expanded="true">
		<span data-entity="sku-current-value"></span>
	</button>
	<ul class="dropdown-menu" role="menu" aria-labelledby="<?=$dropdownId?>">
		<?
		foreach ($skuProperty['VALUES'] as &$value)
		{
			$value['NAME'] = htmlspecialcharsbx($value['NAME']);
/*
			if ($skuProperty['SHOW_MODE'] === 'PICT')
			{
				?>
				<li class="product-cat-scu-item-color-container" title="<?=$value['NAME']?>"
					<?php if ($value['ID'] == 0): ?> style="display:none"<?php endif; ?>
					data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
					data-onevalue="<?=$value['ID']?>">
					<span class="product-cat-scu-item-color-block">
						<span class="product-cat-scu-item-color" title="<?=$value['NAME']?>"
							style="background-image: url('<?=$value['PICT']['SRC']?>');">
						</span>
					</span>
				</li>
				<?
			}
			else
			{
*/
				?>
				<li class="dropdown-item" title="<?=$value['NAME']?>"
					<?php if ($value['ID'] == 0): ?> style="display:none"<?php endif; ?>
					data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
					data-onevalue="<?=$value['ID']?>">
					<?=$value['NAME']?>
				</li>
				<?
/*
			}
*/
		}
		?>
	</ul>
</div>