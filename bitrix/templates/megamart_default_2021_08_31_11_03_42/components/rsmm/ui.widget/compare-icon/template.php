<?php
/**
 * @var CBitrixComponentTemplate $this
 */

use Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

$this->setFrameMode(true);

global $RS_COMPARE_DATA;

if (!isset($RS_COMPARE_DATA))
	return;

$widgetId = 'favorite-icon-'.$this->randString();
$countItems = $RS_COMPARE_DATA['COUNT'];
$sWidgetPath = $RS_COMPARE_DATA['COMPARE_URL'];

$frame = $this->createFrame($widgetId.'-frame');
$frame->begin();
?>
	<a href="<?=$sWidgetPath?>" <?
		?>class="c-icon-count<?=($countItems ? ' has-items' : '')?> js-global-compare"<?
		if ($arParams['AJAX_LOAD'] == 'Y') {
			?> data-src="<?=SITE_DIR.'ajax/compare.php';?>" data-type="ajax" data-popup-type="side" data-need-cache="Y"<?php
		}
		?> title="<?=Loc::getMessage('RS_MM_UI_WIDGET_COMPARE');?>" id="<?=$widgetId?>" aria-label="<?=Loc::getMessage('RS_MM_UI_WIDGET_COMPARE')?>">
		<svg class="icon-svg"><use xlink:href="#svg-copy"></use></svg>
		<span class="c-icon-count__quantity js-global-compare__count"><?=$countItems?></span>
	</a>
<?php $frame->beginStub(); ?>
	<a href="<?=$sWidgetPath?>" class="c-icon-count js-global-compare" id="<?=$widgetId?>" aria-label="<?=Loc::getMessage('RS_MM_UI_WIDGET_COMPARE')?>">
		<svg class="icon-svg"><use xlink:href="#svg-copy"></use></svg>
		<span class="c-icon-count__quantity js-global-compare__count">0</span>
	</a>
<?php $frame->end(); ?>
