<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$this->setFrameMode(true);

$layout = new \Redsign\MegaMart\Layouts\Section();

$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing');

$layout->start();
?>
<div class="shops-detail">
	<?php if (isset($arResult['DISPLAY_PROPERTIES'][$arParams['PROP_COORDS']]['DISPLAY_VALUE'])): ?>
	<div class="shops-detail__map">
		<?=$arResult['DISPLAY_PROPERTIES'][$arParams['PROP_COORDS']]['DISPLAY_VALUE']?>
	</div>
	<?php endif; ?>


	<div class="block-spacing">
		<hr class="title-delimiter mt-0">
		<div class="shops-detail__data row">
			<div class="col-md-6">
				<?php
				foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty):
					if(in_array($arProperty['CODE'], $arResult['DISPLAY_SKIP_PROPERTIES'])) {
						continue;
					}
					?>
					<div class="shops-detail-prop mt-4">
						<div class="text-muted small"><?=$arProperty['NAME']?>:</div>
						<div><?=is_array($arProperty['DISPLAY_VALUE']) ? implode(' / ', $arProperty['DISPLAY_VALUE']) : $arProperty['DISPLAY_VALUE']?></div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="col-md-6">
				<?php if (!empty($arResult['PREVIEW_TEXT'])): ?>
					<div class="text-muted lead mt-4"><?=$arResult['PREVIEW_TEXT']?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>


	<?php if (!empty($arResult['DETAIL_TEXT'])): ?>
	<div class="shops-detail__content block-spacing-x py-4">
		<?=$arResult['DETAIL_TEXT']?>
	</div>
	<?php endif; ?>
</div>

<?php
$layout->end();
