<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

if (count($arResult) < 1) {
  return;
}
?><ul class="b-dl-menu">
<?php
$previousLevel = 0;
foreach ($arResult as $arItem):
?>
    <?php if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
        <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
    <?php endif; ?>

    <?php if ($arItem["IS_PARENT"]): ?>
        <li class="b-dl-menu__item has-subitems<?php if ($arItem['DEPTH_LEVEL'] == 1 && strpos($arItem['LINK'], $arParams['CATALOG_PATH']) !== false): ?> is-catalog<?php endif; ?>">
            <a href="<?=$arItem['LINK']?>" class="b-dl-menu__link"><?=$arItem['TEXT']?></a>
            <ul class="b-dl-menu__subitems">
                <li class="b-dl-menu__item b-dl-menu__item--back">
					<a href="<?=$arItem['LINK']?>" class="b-dl-menu__link is-back">
						<?=Loc::getMessage('RS_MM_M_BACK');?>
					</a>
				</li>
                <li class="b-dl-menu__item b-dl-menu__item--main">
					<a href="<?=$arItem['LINK']?>" class="b-dl-menu__link"><?=$arItem['TEXT']?></a>
				</li>
    <?php else: ?>
      <li class="b-dl-menu__item"><a href="<?=$arItem['LINK']?>" class="b-dl-menu__link"><?=$arItem['TEXT']?></a></li>
    <?php endif; ?>
<?php $previousLevel = $arItem["DEPTH_LEVEL"]; endforeach; ?>

<?php if ($previousLevel > 1): str_repeat("</ul></li>", ($previousLevel-1) );
endif;
?>

</ul>
