<?php
/**
 * @var CBitrixComponentTemplate $this
 */

use Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

$this->setFrameMode(true);

global $RS_FAVORITE_DATA;

if (!isset($RS_FAVORITE_DATA))
	return;

$widgetId = 'favorite-icon-'.$this->randString();
$countItems = $RS_FAVORITE_DATA['COUNT'];
$sWidgetPath = $RS_FAVORITE_DATA['PATH_TO_FAVORITE'];

$frame = $this->createFrame($widgetId.'-frame');
$frame->begin();
?>
	<a href="<?=$sWidgetPath?>" <?
		?>class="c-icon-count<?=($countItems ? ' has-items' : '')?> js-global-favorite"<?
		if ($arParams['AJAX_LOAD'] == 'Y') {
			?> data-src="<?=SITE_DIR.'ajax/wishlist.php';?>" data-type="ajax" data-popup-type="side" data-need-cache="Y"<?php
		}
		?> title="<?=Loc::getMessage('RS_MM_UI_WIDGET_FAVORITE');?>" id="<?=$widgetId?>" aria-label="<?=Loc::getMessage('RS_MM_UI_WIDGET_FAVORITE')?>">
		<svg class="icon-svg"><use xlink:href="#svg-heart"></use></svg>
		<span class="c-icon-count__quantity js-global-favorite__count"><?=$countItems?></span>
	</a>
<?php $frame->beginStub(); ?>
	<a href="<?=$sWidgetPath?>" class="c-icon-count js-global-favorite" id="<?=$widgetId?>" aria-label="<?=Loc::getMessage('RS_MM_UI_WIDGET_FAVORITE')?>">
		<svg class="icon-svg"><use xlink:href="#svg-heart"></use></svg>
		<span class="c-icon-count__quantity js-global-favorite__count">0</span>
	</a>
<?php $frame->end(); ?>
