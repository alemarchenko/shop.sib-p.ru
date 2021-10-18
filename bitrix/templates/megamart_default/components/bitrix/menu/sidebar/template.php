<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->setFrameMode(true);

if (count($arResult) < 1) {
	return;
}

$layout = new \Redsign\MegaMart\Layouts\Section();
$layout
	->addModifier('white')
	->addModifier('shadow')
	->addModifier('outer-spacing');

$layout->start();
?>
<ul class="b-sidebar-nav">
	<?php
	$previousLevel = 0;
	foreach ($arResult as $index => $arItem):
	?>
		<?php if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
			<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
		<?php endif; ?>

		<?php if ($arItem["IS_PARENT"]): ?>
			<li class="dropdown-submenu b-sidebar-nav__item js-smenu-item<?php if ($arItem['SELECTED']) echo ' is-selected'; ?>">
				<a href="<?=$arItem['LINK']?>" class="b-sidebar-nav__link">
					<?=$arItem['TEXT']?>
					<span class="b-sidebar-nav__toggle js-smenu-item__toggle<?php if (!$arItem['SELECTED']) echo ' collapsed'; ?>" data-toggle="collapse" aria-haspopup="true" aria-expanded="true" data-target="#sidebar_menu_<?=$index?>_pc">
						<svg class="icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
					</span>
				</a>
				<ul class="b-sidebar-nav__submenu collapse<?php if ($arItem['SELECTED']) echo ' show collapsed'; ?>" id="sidebar_menu_<?=$index?>_pc" aria-expanded="false">
		<?php else: ?>
			<li class="b-sidebar-nav__item<?php if ($arItem['SELECTED']) echo ' is-selected'; ?>"><a href="<?=$arItem['LINK']?>" class="b-sidebar-nav__link"><?=$arItem['TEXT']?></a></li>
		<?php endif; ?>
	<?php $previousLevel = $arItem["DEPTH_LEVEL"]; endforeach; ?>

	<?php if ($previousLevel > 1): ?>
		<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
	<?php endif; ?>
</ul>
<?php
$layout->end();
