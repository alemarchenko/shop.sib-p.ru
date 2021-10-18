<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

global $RS_FAVORITE_DATA; // from redsign:favorite.list -> global

if (isset($RS_FAVORITE_DATA))
{
	$rs = new \Bitrix\Main\Type\RandomSequence();
	$id = $rs->randString();

	$sPathToFavorite = $RS_FAVORITE_DATA['PATH_TO_FAVORITE'];

	$frame = new \Bitrix\Main\Page\FrameBuffered($id);

	$frame->begin();

	?><a href="<?=$sPathToFavorite?>" <?
	 	?>class="c-icon-count<?=($RS_FAVORITE_DATA['COUNT'] > 0 ? ' has-items' : '')?> js-global-favorite"<?
		if ($arParams['AJAX_LOAD'] == 'Y') {
			?> data-src="<?=SITE_DIR.'ajax/wishlist.php';?>" data-type="ajax" data-popup-type="side" data-need-cache="Y"<?php
		}
		?> title="<?=Loc::getMessage('RS_SIDE_PANEL_FAVORITE');?>">
		<svg class="icon-svg"><use xlink:href="#svg-heart"></use></svg>
		<span class="c-icon-count__quantity js-global-favorite__count"><?=$RS_FAVORITE_DATA['COUNT']?></span>
	</a>
	<?php

	$frame->beginStub();
	?><a href="<?=$sPathToFavorite?>" class="c-icon-count js-global-favorite">
		<svg class="icon-svg"><use xlink:href="#svg-heart"></use></svg>
		<span class="c-icon-count__quantity js-global-favorite__count">0</span>
	</a>
	<?php $frame->end();
}
