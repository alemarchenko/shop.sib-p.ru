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

<div class="props_group">
	<div class="props_group__name"><?=$arrValue['GROUP']['NAME']?></div>
	<dl class="product-cat-properties">
		<?php
		foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $iPropKey => $sProp)
		{
			?>
			<dt><?=$arResult['PROPERTIES'][$sPropCode]["DESCRIPTION"][$iPropKey]?>:</dt>
			<dd><span><?=$sProp?></dd>
			<?php
		}
		?>
	</dl>
</div>
