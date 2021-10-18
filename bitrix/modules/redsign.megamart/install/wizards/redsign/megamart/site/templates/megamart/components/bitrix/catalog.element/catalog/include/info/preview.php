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

if (
	$arResult['PREVIEW_TEXT'] != ''
	&& (
		$arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S'
		|| ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == '')
	)
) {
	?>
	<div class="font-size-sm text-extra" data-entity="preview">
		<?=$arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : $arResult['PREVIEW_TEXT']?>
		 <?php if ($arResult['DETAIL_TEXT'] != '') :?>
			<div>
				<a class="js-link-scroll" href="#<?=$itemIds['ELEMENT_DETAIL_TEXT']?>"><?=Loc::getMessage('RS_MM_BCE_CATALOG_MORE_INFO')?></a>
			</div>
		 <?php endif; ?>
	</div>
	<?
}