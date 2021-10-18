<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$sMenuId = !empty($arParams['RS_MENU_ID']) ? $arParams['RS_MENU_ID'] : 'verticalMenu_'.$this->randString();

if (count($arResult) > 0):
	$sTheme = isset($arParams['MENU_COLOR']) ? $arParams['MENU_COLOR'] : 'primary';

	$fnShowVerticalItems = function ($arItems) use (&$fnShowVerticalItems) {
		foreach ($arItems as $arItem):
			$isParent = (bool) $arItem["IS_PARENT"];;

			$sMenuItemClass = 'mmenu-vertical-item mmenu-vertical-item--dd-item level-'.$arItem['DEPTH_LEVEL'];

			if ($isParent) {
				$sMenuItemClass .= ' has-children';
			}

			?><div class="<?=$sMenuItemClass?>">
				<a href="<?=$arItem['LINK']?>" class="mmenu-vertical-item__link">
					<?php if ($arItem['DEPTH_LEVEL'] == 1): ?>
						<div class="mmenu-vertical-item__icon">
						<?php if ($arItem['PARAMS']['ICON']): ?>
							<img src="<?=$arItem['PARAMS']['ICON']?>" alt="<?=$arItem['TEXT']?>" title="<?=$arItem['TEXT']?>">
						<?php endif; ?>
						</div>
					<?php endif; ?>
					<?=$arItem['TEXT']?>
				</a>
				<?php if ($isParent): ?>
				<div class="mmenu-vertical-item__dropdown">
					<?php $fnShowVerticalItems($arItem['SUB_ITEMS']); ?>
				</div>
				<?php endif; ?>
			</div><?
		endforeach;
		?>

		<div class="<?=$sMenuItemClass?> more-item">
			<a href="#" class="mmenu-vertical-item__link text-primary">
				<div class="mmenu-vertical-item__icon"></div>
				<?=Loc::getMessage('RS_M_ALL_CATEGORIES');?>
				<svg class="icon-svg icon-chevron"><use xlink:href="#svg-chevron-down"></use></svg>
			</a>
		</div><?php
	};
?>
	<div class="l-mmenu-vertical l-mmenu-vertical--<?=$sTheme?>" id="<?=$sMenuId?>">
		<?php
		$sItemClass = 'has-children mmenu-vertical-item mmenu-vertical-item--'.$sTheme;
		if ($arParams['OPEN_CATALOG']) {
			$sItemClass .= ' mmenu-vertical-item--is-open';
		}
		?>
		<div class="<?=$sItemClass?>">
			<a href="<?=$arParams['CATALOG_LINK']?>" class="mmenu-vertical-item__link">
				<svg class="icon-svg"><use xlink:href="#svg-view-card"></use></svg>
				<?=$arParams['CATALOG_TITLE']?>
				<svg class="icon-svg mmenu-vertical-item__chevron-down"><use xlink:href="#svg-chevron-down"></use></svg>
			</a>
			<div class="mmenu-vertical-item__dropdown">
				<?php $fnShowVerticalItems($arResult); ?>
			</div>
		</div>

	</div>

	<script> var <?=$menuBlockId.'Obj'?> = new RS.VerticalMenu('<?=$sMenuId?>'); </script>
<?php
endif;
