<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$sMenuId = !empty($arParams['RS_MENU_ID']) ? $arParams['RS_MENU_ID'] : 'mainMenu_'.$this->randString();

if (count($arResult) > 0):
	$sTheme = isset($arParams['MENU_COLOR']) ? $arParams['MENU_COLOR'] : 'primary';

	$fnShowDropdownItems = function ($arItems) use (&$fnShowDropdownItems) {
		foreach ($arItems as $arItem):
			$isParent = (bool) $arItem["IS_PARENT"];;

			$sMenuItemClass = 'mmenu-type1-item mmenu-type1-item--dd-item';

			if ($isParent) {
				$sMenuItemClass .= ' has-children';
			}

			?><div class="<?=$sMenuItemClass?>">
				<a href="<?=$arItem['LINK']?>" class="mmenu-type1-item__link"><?=$arItem['TEXT']?></a>
				<?php if ($isParent): ?>
				<div class="mmenu-type1-item__dropdown">
					<?php $fnShowDropdownItems($arItem['SUB_ITEMS']); ?>
				</div>
				<?php endif; ?>
			</div><?
		endforeach;
	};

	$fnShowWideMenu = function ($arItems, $arParentItem) use ($arParams) {
		$hasImage = (bool) $arParentItem['PARAMS']['BANNER_IMAGE_SRC'];
		$sItemColClass = '';

		if ($hasImage) {
			$sItemColClass = 'col-md-6 col-lg-6 col-xl-4';
		} else {
			$sItemColClass = 'col-md-6 col-lg-4 col-xl-3';
		}
		?>
		<div class="mmenu-wide<?php if ($hasImage): ?> has-image<?php endif; ?>">
			<div class="mmenu-wide__items">
				<div class="row">
					<?php
					foreach ($arItems as $arItem):
						$hasChildren = !empty($arItem['SUB_ITEMS']);
						$nCountChildren = $hasChildren ? count($arItem['SUB_ITEMS']) : 0;
						$nHiddenChildren = $nCountChildren - $arParams['COUNT_SHOW_SUBSECTIONS'];

						if ($nHiddenChildren < 0) {
							$nHiddenChildren = 0;
						}

					?>
						<div class="<?=$sItemColClass?>">
							<div class="mmenu-wide-item">
								<?php if (isset($arItem['PICTURE'])): ?>
									<a href="<?=$arItem['LINK']?>" class="mmenu-wide-item__picture">
										<img class="img-fluid" src="<?=$arItem['PICTURE']['SRC']?>" alt="<?=$arItem['TEXT']?>" title="<?=$arItem['TEXT']?>">
									</a>
								<?php endif; ?>
								<a href="<?=$arItem['LINK']?>" class="mmenu-wide-item__name">
									<div class="mmenu-wide-item__line"<?php if ($arItem['PARAMS']['SECTION_COLOR']): ?> style="color: <?=$arItem['PARAMS']['SECTION_COLOR']?>"<?php endif; ?>></div>

									<?=$arItem['TEXT']?>

									<?php if ($hasChildren): ?>
									<span class="mmenu-wide-item__count"><?=$nCountChildren?></span>
									<?php endif; ?>
								</a>

								<?php if ($hasChildren): ?>
								<div class="mmenu-wide-item__sub">
									<?php
									foreach ($arItem['SUB_ITEMS'] as $nIndex => $arSubItem):
										if ($nIndex + 1 > $arParams['COUNT_SHOW_SUBSECTIONS']) {
											break;
										}
									?>
									<a href="<?=$arSubItem['LINK']?>" class="mmenu-wide-sub-item"><?=$arSubItem['TEXT']?></a>
									<?php endforeach; ?>

									<?php if ($nHiddenChildren): ?>
									<div class="mt-3">
										<a href="<?=$arItem['LINK']?>" class="mmenu-wide-sub-item"><?php
											echo Loc::getMessage('RS_MM_BM_MAIN_1_ALL_CATEGORIES', ['#NUMBER#' => $nCountChildren - $arParams['COUNT_SHOW_SUBSECTIONS']])
										?></a>
									</div>
									<?php endif; ?>
								</div>

								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php if ($hasImage): ?>
			<div class="mmenu-wide__adv">
				<?php if ($arParentItem['PARAMS']['BANNER_LINK']): ?>
				<a href="<?=$arParentItem['PARAMS']['BANNER_LINK']?>">
				<?php endif; ?>
					<img src="<?=$arParentItem['PARAMS']['BANNER_IMAGE_SRC']?>" alt="<?=$arParentItem['TEXT']?>" title="<?=$arParentItem['TEXT']?>">
				<?php if ($arParentItem['PARAMS']['BANNER_LINK']): ?>
				</a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<?
	}
?>
<div class="l-mmenu-type1 l-mmenu-type1--<?=$sTheme?>" id="<?=$sMenuId?>" style="opacity: 0;">
	<?php
	foreach ($arResult as $arItem):
		$isParent = (bool) $arItem["IS_PARENT"];
		$isSelected = (bool) $arItem["SELECTED"];

		$sItemClass = 'mmenu-type1-item mmenu-type1-item--'.$sTheme;

		if ($isParent) {
			$sItemClass .= ' has-children';
		}

		if ($isSelected) {
			$sItemClass .= ' is-selected';
		}

	?>
	<div class="<?=$sItemClass?>">
		<a href="<?=$arItem['LINK']?>" class="mmenu-type1-item__link"><?=$arItem['TEXT']?></a>

		<?php if ($isParent): ?>
			<?php if ($arParams['CAN_WIDE'] == 'Y' && $arItem['PARAMS']['OPEN_TYPE'] == 'wide'): ?>
			<div class="mmenu-type1-item__wide">
				<?php $fnShowWideMenu($arItem['SUB_ITEMS'], $arItem); ?>
			</div>
			<?php else: ?>
			<div class="mmenu-type1-item__dropdown">
				<?php $fnShowDropdownItems($arItem['SUB_ITEMS']); ?>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php endforeach; ?>

	<div class="mmenu-type1-item mmenu-type1-item--<?=$sTheme?> is-more has-children">
        <a href="#" class="mmenu-type1-item__link">
        	<svg class="icon-svg"><use xlink:href="#svg-points"></use></svg>
        </a>
        <div class="mmenu-type1-item__dropdown"></div>
    </div>
</div>
<script> var <?=$menuBlockId.'Obj'?> = new RS.MainMenu('<?=$sMenuId?>'); </script>
<?php endif;
