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
?>
<li class="product-cat-scu-item-text-container btn btn-outline-secondary btn-sm" title="<?=$value['NAME']?>"
	data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
	<div class="product-cat-scu-item-text-block">
		<div class="product-cat-scu-item-text"><?=$value['NAME']?></div>
	</div>
</li>