<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

if (count($arResult['ITEMS']) > 0): ?>
<div class="l-section l-section--outer-spacing">
	<div class="adv-index-full">
		<?php foreach ($arResult['ITEMS'] as $arItem): ?>
			<?php
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="b-adv-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<?php if (isset($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'])): ?>
				<a href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>" <?
					?>target="<?=(isset($arItem['DISPLAY_PROPERTIES']['LINK_TARGET']['VALUE_XML_ID'])) ? $arItem['DISPLAY_PROPERTIES']['LINK_TARGET']['VALUE_XML_ID'] : '_self' ?>"<?
					?>title="<?=(isset($arItem['DISPLAY_PROPERTIES']['LINK_TITLE']['VALUE'])) ? $arItem['DISPLAY_PROPERTIES']['LINK_TITLE']['VALUE'] : '' ?>">
			<?php endif; ?>
					<div class="b-adv-index-full" style="background-image: url('<?=CFile::GetPath($arItem['DISPLAY_PROPERTIES']['IMAGE']['VALUE'])?>')"></div>
			<?php if (isset($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'])): ?>
				</a>
			<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>