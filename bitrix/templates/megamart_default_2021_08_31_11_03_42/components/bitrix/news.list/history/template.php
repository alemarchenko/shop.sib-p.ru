<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

if (count($arResult['ITEMS']) > 0):
?>
<div class="l-timeline">
	<?php if (!empty($arResult['DESCRIPTION'])): ?>
	<div class="b-timeline-descr"><?=$arResult['DESCRIPTION']?></div>
	<?php endif; ?>
	<div class="l-timeline__items">
		<?php foreach ($arResult['ITEMS'] as $index => $arItem): ?>
		<?php
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="b-timeline-item<?=($index%2 ? ' b-timeline-item--inverted':'')?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<?php if (!empty($arItem['PREVIEW_PICTURE'])): ?>
			<div class="b-timeline-item__picture">
				<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
			</div>
			<?php endif; ?>
			<div class="b-timeline-item__content">
				<?php if(isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_DATE']]['DISPLAY_VALUE'])): ?>
				    <div class="b-timeline-item__date"><?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_DATE']]['DISPLAY_VALUE']?></div>
				<?php endif; ?>
				<h4 class="b-timeline-item__title"><?=$arItem['NAME']?></h4>
				<div class="b-timeline-item__text"><?=$arItem['PREVIEW_TEXT']?></div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif;
