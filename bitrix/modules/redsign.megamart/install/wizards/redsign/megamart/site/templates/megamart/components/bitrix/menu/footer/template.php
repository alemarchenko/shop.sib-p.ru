<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arParams['LVL1_COUNT'] = 100;
$arParams['LVL2_COUNT'] = 400;

$this->setFrameMode(true);

$countMainLvl = 1;
?>
<?php if (!empty($arResult)): ?>
	<div class="footer-menu <?php if (isset($arParams['COLUMNS'])): ?> footer-menu--columns-<?=$arParams['COLUMNS']?><?php endif; ?>">
		<?php
		$PREVIOUS_DEPTH_LEVEL = 1;
		$lvl1_count = 0;
		$lvl2_count = 0;
		?>
		<?php foreach ($arResult as $key => $arMenu): ?>
			<?php
			$CURRENT_DEPTH_LEVEL = $arMenu['DEPTH_LEVEL'];


			if ($lvl1_count == $arParams['LVL1_COUNT'] && $CURRENT_DEPTH_LEVEL == 1)
				break;

			if ($CURRENT_DEPTH_LEVEL == 1)
				$lvl2_count = 0;

			if (($lvl2_count >= $arParams['LVL2_COUNT'] && $arParams['LVL2_COUNT'] > 0) || ($CURRENT_DEPTH_LEVEL > 1 && $arParams['LVL2_COUNT'] < 1))
				continue;

			if ($CURRENT_DEPTH_LEVEL > $arParams['MAX_LEVEL'])
				continue;

			if ($CURRENT_DEPTH_LEVEL == 1) {
				if ($key > 0) {
					?></div><?
				}
				if (($countMainLvl - 1) % 3 == 0 ) {
					if ($countMainLvl != 1) {
						/*?></div><?*/
					}
					/*?><div class="clearfix"><?*/
				}
				?><div class="footer-menu__group"><?
				$countMainLvl++;
				$lvl1_count++;
			} else {
				$lvl2_count++;
			}
			?>
				<a class="footer-menu__item footer-menu__item--lvl<?=$CURRENT_DEPTH_LEVEL?>" href="<?=$arMenu['LINK']?>" title="<?=$arMenu['TEXT']?>"><span><?=$arMenu['TEXT']?></span></a>
			<?php
			$PREVIOUS_DEPTH_LEVEL = $CURRENT_DEPTH_LEVEL;
			?>
		<?php endforeach; ?>
		<?/*</div>*/?>
		</div>
	</div>
<?php endif; ?>
