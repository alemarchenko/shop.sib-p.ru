<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

global $RS_BASKET_DATA; // from sale.basket.basket.line -> global
global $RS_COMPARE_DATA; // from catalog.compare.list -> global
global $RS_FAVORITE_DATA; // from sale.basket.basket.line -> global
?>
<div class="container">
	<div class="d-flex justify-content-center justify-content-sm-between">

		<div class="d-flex">

			<div class=""></div>
			<?php
			$APPLICATION->IncludeFile(
				SITE_DIR.'include/panels/bottom/personal.php',
				array(),
				array(
					'SHOW_BORDER' => false
				)
			);
			?>

			<?php
			$APPLICATION->IncludeFile(
				SITE_DIR.'include/panels/bottom/ask_question.php',
				array()
			);
			?>
		</div>

		<div class="d-flex">

			<a href="/catalog/" class="c-button-control c-button-control__bottom-panel c-button-control-bottom-panel bottom-panel-control js-global-favorite">
				<span class="c-button-control__icon">
					<svg class="icon-svg"><use xlink:href="#svg-catalog"></use></svg>
				</span>
				<span class="c-button-control__title">
					<?=Loc::getMessage('RS_PANEL_BOTTOM_CATALOG');?>
				</span>
			</a>

			<?php
			if (isset($RS_FAVORITE_DATA)) {
				$rs = new \Bitrix\Main\Type\RandomSequence();
				$id = $rs->randString();

				$sPathToFavorite = $RS_FAVORITE_DATA['PATH_TO_FAVORITE'];

				$frame = new \Bitrix\Main\Page\FrameBuffered($id);

				$isAjax = true;

				$frame->begin();

				?><a href="<?=$sPathToFavorite?>" class="c-button-control c-button-control__bottom-panel bottom-panel-control js-global-favorite<?=($RS_FAVORITE_DATA['COUNT'] > 0 ? ' has-items' : '')?>"<?
					if ($isAjax) {
						?> data-type="ajax" data-popup-type="bottom" data-need-cache="Y" data-src="<?=SITE_DIR.'ajax/wishlist.php'?>"<?
					}
				?>>
					<span class="c-button-control__icon">
						<svg class="icon-svg"><use xlink:href="#svg-heart"></use></svg>
						<span class="c-button-control__quantity js-global-favorite__count"><?=$RS_FAVORITE_DATA['COUNT']?></span>
					</span>
					<span class="c-button-control__title">
						<?=Loc::getMessage('RS_PANEL_BOTTOM_FAVORITE');?>
					</span>
				</a>
				<?php

				$frame->beginStub();
				?><a href="<?=$sPathToFavorite?>" class="c-button-control c-button-control__bottom-panel bottom-panel-control js-global-favorite">
					<span class="c-button-control__icon">
						<svg class="icon-svg"><use xlink:href="#svg-heart"></use></svg>
						<span class="c-button-control__quantity js-global-favorite__count">0</span>
					</span>
					<span class="c-button-control__title">
						<?=Loc::getMessage('RS_PANEL_BOTTOM_FAVORITE');?>
					</span>
				</a>
				<?php $frame->end();
			}
			?>

			<?php
			if (isset($RS_COMPARE_DATA)) {
				$rs = new \Bitrix\Main\Type\RandomSequence();
				$id = $rs->randString();

				$sCompareUrl = $RS_COMPARE_DATA['COMPARE_URL'];

				$frame = new \Bitrix\Main\Page\FrameBuffered($id);

				$frame->begin();
				?><a href="<?=$sCompareUrl?>" class="c-button-control c-button-control__bottom-panel bottom-panel-control js-global-compare<?=($RS_COMPARE_DATA['COUNT'] > 0 ? ' has-items' : '')?>" data-type="bottom-control">
					<span class="c-button-control__icon">
						<svg class="icon-svg"><use xlink:href="#svg-copy"></use></svg>
						<span class="c-button-control__quantity js-global-compare__count"><?=$RS_COMPARE_DATA['COUNT']?></span>
					</span>
					<span class="c-button-control__title">
						<?=Loc::getMessage('RS_PANEL_BOTTOM_COMPARE');?>
					</span>
				</a>
				<?php

				$frame->beginStub();
				?><a href="<?=$sCompareUrl?>" class="c-button-control c-button-control__bottom-panel bottom-panel-control js-global-compare">
					<span class="c-button-control__icon">
						<svg class="icon-svg"><use xlink:href="#svg-copy"></use></svg>
						<span class="c-button-control__quantity js-global-compare__count">0</span>
					</span>
					<span class="c-button-control__title">
						<?=Loc::getMessage('RS_PANEL_BOTTOM_COMPARE');?>
					</span>
				</a>
				<?php $frame->end();
			}
			?>


			<?php
			if (isset($RS_BASKET_DATA)) {
				$rs = new \Bitrix\Main\Type\RandomSequence();
				$id = $rs->randString();

				$sPathToCart = $RS_BASKET_DATA['PATH_TO_BASKET'];

				$frame = new \Bitrix\Main\Page\FrameBuffered($id);

				$isAjax = true;
				$sCurrentPage = mb_strtolower(\Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPage());
				$sPathToOrder = mb_strtolower($RS_BASKET_DATA['PATH_TO_ORDER']);

				if (
					strncmp($sCurrentPage, $sPathToCart, mb_strlen($sPathToCart)) == 0
					|| strncmp($sCurrentPage, $sPathToOrder, mb_strlen($sPathToOrder)) == 0
				) {
					$isAjax = false;
				}

				$frame->begin();
				?><a href="/personal/cart/"<?
					if ($isAjax) {
						?> data-type="ajax" data-popup-type="bottom" data-need-cache="Y" data-src="<?=SITE_DIR.'ajax/cart.php'?>"<?
					}
				?>>
					<span class="c-button-control__icon">
						<svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg>
						<span class="c-button-control__quantity js-global-cart__count"><?=$RS_BASKET_DATA['NUM_PRODUCTS']?></span>
					</span>
					<span class="c-button-control__title">
						<?=Loc::getMessage('RS_PANEL_BOTTOM_CART');?>
					</span>
				</a>
				<?php

				$frame->beginStub();
				?><a href="/personal/cart/">
					<span class="c-button-control__icon">
						<svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg>
						<span class="c-button-control__quantity js-global-cart__count">0</span>
					</span>
					<span class="c-button-control__title">
						<?=Loc::getMessage('RS_PANEL_BOTTOM_CART');?>
					</span>
				</a>
				<?php $frame->end();
			}
			?>
		</div>
	</div>
</div>
