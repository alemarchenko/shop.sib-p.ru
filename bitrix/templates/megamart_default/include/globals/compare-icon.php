<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

global $RS_COMPARE_DATA; // from catalog.compare.list -> global

if (isset($RS_COMPARE_DATA)) {
	$rs = new \Bitrix\Main\Type\RandomSequence();
	$id = $rs->randString();

	$sCompareUrl = $RS_COMPARE_DATA['COMPARE_URL'];

	$frame = new \Bitrix\Main\Page\FrameBuffered($id);

	$frame->begin();
	?><a href="<?=$sCompareUrl?>" class="c-icon-count<?=($RS_COMPARE_DATA['COUNT'] > 0 ? ' has-items' : '')?> js-global-compare" title="<?=Loc::getMessage('RS_SIDE_PANEL_COMPARE')?>">
		<svg class="icon-svg"><use xlink:href="#svg-copy"></use></svg>
		<span class="c-icon-count__quantity js-global-compare__count"><?=$RS_COMPARE_DATA['COUNT']?></span>
	</a>
	<?php

	$frame->beginStub();
	?><a href="<?=$sCompareUrl?>" class="c-icon-count js-global-compare">
		<svg class="icon-svg"><use xlink:href="#svg-copy"></use></svg>
		<span class="c-icon-count__quantity js-global-compare__count">0</span>
	</a>
	<?php $frame->end();
}
