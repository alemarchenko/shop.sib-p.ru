<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

if (count($arResult['ITEMS']) > 0): ?>
<div class="l-section l-section--container l-section--outer-spacing">
	<?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
		<div class="l-section__container">
	<?php endif; ?>
		<div class="l-section__main">
			<div class="adv-index-mimi">
				<div class="row">
					<?php
					$sGridClass = implode(
						' ',
						array_map(
							function ($key, $val) {
								return 'col-'.$key.'-'.$val;
							},
							array_keys($arParams['GRID_RESPONSIVE_SETTINGS']),
							$arParams['GRID_RESPONSIVE_SETTINGS']
						)
					);
					?>
					<?php foreach ($arResult['ITEMS'] as $arItem): ?>
						<div class="<?=$sGridClass?> mb-6 mb-md-0">
							<?php
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							?>
							<div class="b-adv-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<?php if (isset($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'])): ?>
								<a class="b-adv-index-mini-banner" href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>" <?
									?>target="<?=(isset($arItem['DISPLAY_PROPERTIES']['LINK_TARGET']['VALUE_XML_ID'])) ? $arItem['DISPLAY_PROPERTIES']['LINK_TARGET']['VALUE_XML_ID'] : '_self' ?>"<?
									?>title="<?=(isset($arItem['DISPLAY_PROPERTIES']['LINK_TITLE']['VALUE'])) ? $arItem['DISPLAY_PROPERTIES']['LINK_TITLE']['VALUE'] : '' ?>">
							<?php endif; ?>
							<?php if (isset($arItem['DISPLAY_PROPERTIES']['IMAGE']['VALUE'])): ?>
									<img class="img-fluid" src="<?=CFile::GetPath($arItem['DISPLAY_PROPERTIES']['IMAGE']['VALUE'])?>"<?
										?>alt="<?=(isset($arItem['DISPLAY_PROPERTIES']['LINK_TITLE']['VALUE'])) ? $arItem['DISPLAY_PROPERTIES']['LINK_TITLE']['VALUE'] : '' ?>">
							<?php endif; ?>
							<?php if (isset($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'])): ?>
								</a>
							<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
		</div>
	<?php endif; ?>
</div>
<?php endif; ?>