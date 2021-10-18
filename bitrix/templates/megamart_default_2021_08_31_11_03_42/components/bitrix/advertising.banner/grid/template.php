<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}
$iBannersCount = count($arResult['BANNERS']);

if ($iBannersCount > 0): ?>
<section class="l-section l-section--container l-section--outer-spacing">
	<?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
		<div class="l-section__container">
	<?php endif; ?>

	<div class="l-section__main">
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

			<?php foreach($arResult["BANNERS"] as $k => $banner): ?>
				<div class="<?=$sGridClass?> <?=($k == ($iBannersCount - 1) ? ' mb-md-0' : ' mb-6 mb-md-0')?>">
					<?=$banner?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
		</div>
	<?php endif; ?>
</section>
<?php endif;
