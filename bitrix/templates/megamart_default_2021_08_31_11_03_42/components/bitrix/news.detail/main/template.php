<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

$this->setFrameMode(true);


$sBlockId = 'rs-news-detail-'.randString(5);

$layout = new \Redsign\MegaMart\Layouts\Section();
$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing');

$layout->start();
?>
<div class="b-article-detail">
	<div class="b-article-detail__content">
		<div class="b-article-detail-content">
			<?php if (!empty($arResult['DETAIL_PICTURE']['SRC'])): ?>
			<div class="b-article-detail-content__picture mt-0">
				<img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" title="<?=$arResult['DETAIL_PICTURE']['TITLE']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>">
			</div>
			<?php endif; ?>

			<div class="b-article-detail-content__body">
			<?php if ($arResult['NAV_RESULT']): ?>
				<?php if ($arParams['DISPLAY_TOP_PAGER']):?><?=$arResult['NAV_STRING']?><br /><?endif;?>
				<?=$arResult['NAV_TEXT']?>
				<?php if ($arParams['DISPLAY_BOTTOM_PAGER']):?><br /><?=$arItem['NAV_STRING']?><?endif;?>
			<?php elseif (strlen($arResult['DETAIL_TEXT']) > 0): ?>
				<?=$arResult['DETAIL_TEXT'];?>
			<?php endif; ?>
			</div>
		</div>

		<?php if (count($arResult['DISPLAY_PROPERTIES']) > 0): ?>
			<div class="b-article-detail-content__props mt-5">
			<?php
			foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty):
				if(in_array($arProperty['CODE'], $arResult['DISPLAY_SKIP_PROPERTIES'])) {
					continue;
				}
			?>
				<div class="b-article-detail-prop mt-4">
					<div class="text-muted small"><?=$arProperty['NAME']?>:</div>
					<div><?=is_array($arProperty['DISPLAY_VALUE']) ? implode(' / ', $arProperty['DISPLAY_VALUE']) : $arProperty['DISPLAY_VALUE']?></div>
				</div>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="b-article-detail__footer">
		<div class="b-article-detail-footer">
			<div class="row align-items-md-center flex-column flex-md-row">
				<div class="col-xs-12 col-md-6 col-lg-4 mb-4 mb-md-0  text-center text-md-left"><?php
					$svgArrowLeft = '<svg class="icon-svg mr-3"><use xlink:href="#svg-arrow-left"></use></svg>';
					?><a class="btn btn-outline-secondary" href="<?=$arParams['NEWS_DETAIL_BACK_URL']?>"><?=$svgArrowLeft?><?=Loc::getMessage('RS_ND_ARTICLE_BACK_URL')?></a><?
				?></div>
				<?php if (isset($arParams['USE_SHARE']) && $arParams['USE_SHARE'] == 'Y'): ?>
				<div class="col-xs-12 col-md-6 col-lg-8"><?php
					?><div class="d-flex align-items-center justify-content-center justify-content-md-end"><?
						?><div class="small text-extra mr-4"><?=Loc::getMessage('RS_ND_ARTICLE_SHARE_TITLE')?></div><?
						?><div class="d-block"><?
								include Application::getDocumentRoot().SITE_TEMPLATE_PATH.'/include/ya-share.php';
						?></div><?
					?></div><?
				?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>

</div>
<?php
$layout->end();
