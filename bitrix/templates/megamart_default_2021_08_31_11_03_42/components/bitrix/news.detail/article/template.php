<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Loader,
	\Bitrix\Main\Application,
	\Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
?>

<?php $this->SetViewTarget('rs_megamart_article_head'); ?>
<div class="b-article-detail-head">
	<div class="b-article-detail-head__title">
		<link itemprop="mainEntityOfPage" content="<?=CHTTP::URN2URI($APPLICATION->GetCurPage())?>">
			<h1 itemprop="name headline"><?=$arResult['NAME']?></h1>
		<link>
	</div>

	<?php if (isset($arResult['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['DISPLAY_VALUE'])): ?>
	<div class="b-article-detail-head__stickers">
		<?php if (is_array($arResult['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['DISPLAY_VALUE'])): ?>
			<?php
			foreach ($arResult['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['DISPLAY_VALUE'] as $nKey => $sStickerName):
				$sColor = $arResult['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['VALUE_XML_ID'][$nKey];
			?>
			<div class="c-sticker"<?=(!empty($sColor) ? ' style="background-color: #'.$sColor.';color: #'.$sColor.';"' : '')?>>
				<span class="c-sticker__name"><?=$sStickerName?></span>
			</div>
			<?php endforeach; ?>
		<?php else: ?>
			<?php $sColor = $arResult['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['VALUE_XML_ID'][0]; ?>
			<div class="c-sticker"<?=(!empty($sColor) ? ' style="background-color: #'.$sColor.';color: #'.$sColor.';"' : '')?>>
				<span class="c-sticker__name"><?=$arResult['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['DISPLAY_VALUE']?></span>
			</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<div class="b-article-detail-head__meta">
		<div class="d-flex align-items-center justify-content-between w-100 flex-column flex-md-row">
			<div class="d-flex mb-3 mb-md-0">
			<?php if ($arResult['LIST_PAGE_URL'] == '/sale-promotions/'): ?>
				<?php if (!empty($arResult['DATE_ACTIVE_FROM']) && (!empty($arResult['DATE_ACTIVE_TO']))): ?>
					<div class="small text-extra pr-4" itemprop="datePublished" content="<?=date("Y-m-d", strtotime($arResult["ACTIVE_FROM"]))?>">
						<?=Loc::getMessage('RS_ND_SALE-PROMOTIONS_FROM')?>
						<?=date("d.m.Y", strtotime($arResult['DATE_ACTIVE_FROM']))?><?=Loc::getMessage('RS_ND_SALE-PROMOTIONS_TO')?>
						<?=date("d.m.Y", strtotime($arResult['DATE_ACTIVE_TO']))?><?=Loc::getMessage('RS_ND_SALE-PROMOTIONS_YEAR')?>
					</div>
				<?php else: ?>
					<div class="small text-extra pr-4" itemprop="datePublished" content="<?=date("Y-m-d", strtotime($arResult["ACTIVE_FROM"]))?>"><?=$arResult['DISPLAY_ACTIVE_FROM']?></div>
				<?php endif; ?>
			<?php else: ?>
				<?php if (!empty($arResult['DISPLAY_ACTIVE_FROM'])): ?>
					<div class="small text-extra pr-4" itemprop="datePublished" content="<?=date("Y-m-d", strtotime($arResult["ACTIVE_FROM"]))?>"><?=$arResult['DISPLAY_ACTIVE_FROM']?></div>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($arResult['LIST_PAGE_URL'] != '/sale-promotions/'): ?>
				<?php if (isset($arResult['READING_TIME'])): ?>
					<div class="small text-extra"><?=Loc::getMessage('RS_ND_ARTICLE_READING_TIME')?><span itemprop="timeRequired"><?=$arResult['READING_TIME']?></span></div>
				<?php endif; ?>
			<?php endif; ?>
			</div>

			<div class="d-flex">
				<?php if ($arParams['USE_RSS'] == 'Y' && !empty($arParams['RSS_URL'])): ?>
				<a class="d-inline-flex align-items-center mr-4" href="<?=$arParams['RSS_URL']?>">
					<svg class="icon-svg text-extra lh-0 mb-0 mr-2"><use xlink:href="#svg-rss"></use></svg>
					<span class="small"><?=Loc::getMessage('RS_ND_ARTICLE_RSS');?></span>
				</a>
				<?php endif; ?>
				<a class="d-inline-flex align-items-center" href="#" onclick="event.preventDefault(); window.print();">
					<svg class="icon-svg text-extra lh-0 mb-0 mr-2"><use xlink:href="#svg-print"></use></svg>
					<span class="small"><?=Loc::getMessage('RS_ND_ARTICLE_PRINT');?></span>
				</a>
			</div>
		</div>
	</div>
</div>
<?php $this->EndViewTarget(); ?>

<div class="b-article-detail-content">

	<?php if (!empty($arResult['DETAIL_PICTURE']['SRC'])): ?>
	<div class="b-article-detail-content__picture">
		<img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" title="<?=$arResult['DETAIL_PICTURE']['TITLE']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>" itemprop="image">
	</div>
	<?php endif; ?>

	<?php if (!empty($arResult['PREVIEW_TEXT'])): ?>
	<div class="b-article-detail-content__preview">
		<span class="text-muted" itemprop="description"><?=$arResult['PREVIEW_TEXT']?></span>
		<hr class="title-delimiter">
	</div>
	<?php endif; ?>

	<div class="b-article-detail-content__body">
	<?php if ($arResult['NAV_RESULT']): ?>
		<?php if ($arParams['DISPLAY_TOP_PAGER']):?><?=$arResult['NAV_STRING']?><br /><?endif;?>
		<?=$arResult['NAV_TEXT']?>
		<?php if ($arParams['DISPLAY_BOTTOM_PAGER']):?><br /><?=$arItem['NAV_STRING']?><?endif;?>
	<?php elseif (strlen($arResult['DETAIL_TEXT']) > 0): ?>
		<div class="b-article-detail-content__detail-text" itemprop="articleBody"><?=$arResult['DETAIL_TEXT'];?></div>
	<?php endif; ?>
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

<?php $this->SetViewTarget('rs_megamart_article_footer'); ?>
<div class="b-article-detail-footer">
	<div class="row align-items-md-center flex-column flex-md-row">
		<div class="col-xs-12 col-md-6 col-lg-4 mb-4 mb-md-0  text-center text-md-left"><?php
			$svgArrowLeft = '<svg class="icon-svg mr-3"><use xlink:href="#svg-arrow-left"></use></svg>';
			?><a class="btn btn-outline-secondary" href="<?=$arParams['NEWS_DETAIL_BACK_URL']?>"><?=$svgArrowLeft?><?=Loc::getMessage('RS_ND_ARTICLE_BACK_URL')?></a><?
		?></div>
		<div class="col-xs-12 col-md-6 col-lg-8"><?php
			?><div class="d-flex align-items-center justify-content-center justify-content-md-end"><?
				?><div class="small text-extra mr-4"><?=Loc::getMessage('RS_ND_ARTICLE_SHARE_TITLE')?></div><?
				?><div class="d-block"><?
					include Application::getDocumentRoot().SITE_TEMPLATE_PATH.'/include/ya-share.php';
				?></div><?
			?></div><?
		?></div>
	</div>
</div>

<!-- schema.org additional -->
<div class="b-article-detail__additional-items">
	<!-- author -->
	<?php if (isset($arResult['MICRODATA']['AUTHOR'])): ?>
		<link class="additional-items__author" itemprop="author" content="<?=$arResult['MICRODATA']['AUTHOR']['FULL_NAME']?>">
	<?php endif; ?>
	<!-- /author -->

	<!-- publisher -->
	<?php if (isset($arParams['PUBLISHER_TYPE']) && isset($arResult['MICRODATA']['AUTHOR'])): ?>
		<div class="additional-items__publisher">
			<?php if ($arParams['PUBLISHER_TYPE'] == 'person'): ?>
				<div itemprop="publisher" itemscope itemtype="http://schema.org/Person">
					<link itemprop="name" content="<?=$arResult['MICRODATA']['AUTHOR']['NAME']?>">
					<link itemprop="familyName" content="<?=$arResult['MICRODATA']['AUTHOR']['LAST_NAME']?>">
				</div>
			<?php endif; ?>
			<?php if ($arParams['PUBLISHER_TYPE'] == 'organization'): ?>
				<div itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
					<link itemprop="name" content="<?=$arResult['MICRODATA']['AUTHOR']['FULL_NAME']?>">
					<?php if (isset($arResult['MICRODATA']['ORGANIZATION']['IMAGE_URL']) && strlen($arResult['MICRODATA']['ORGANIZATION']['IMAGE_URL']) > 0): ?>
						<span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">
							<? $logoUrl = CHTTP::URN2URI($arResult['MICRODATA']['ORGANIZATION']['IMAGE_URL']); ?>
							<link itemprop="url contentUrl" href="<?=$logoUrl?>" content="<?=$logoUrl?>">
	          </span>
					<?php endif; ?>
					<?php if (isset($arResult['MICRODATA']['ORGANIZATION']['ADDRESS']) && strlen($arResult['MICRODATA']['ORGANIZATION']['ADDRESS']) > 0): ?>
						<link itemprop="address" content="<?=$arResult['MICRODATA']['ORGANIZATION']['ADDRESS']?>">
					<?php endif; ?>
					<?php if (isset($arResult['MICRODATA']['ORGANIZATION']['PHONE']) && strlen($arResult['MICRODATA']['ORGANIZATION']['PHONE']) > 0): ?>
						<link itemprop="telephone" content="<?=$arResult['MICRODATA']['ORGANIZATION']['PHONE']?>">
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<!-- /publisher -->

	<!-- date modifier -->
	<?php if (isset($arResult['TIMESTAMP_X']) && strlen($arResult['TIMESTAMP_X']) > 0): ?>
		<link class="additional-items__date-modified" itemprop="dateModified" content="<?=date("Y-m-d", strtotime($arResult['TIMESTAMP_X']))?>">
	<?php endif; ?>
	<!-- /date modifier -->
</div>
<!-- /schema.org additional -->
<?php $this->EndViewTarget(); ?>

<?php
	$templateData = array(
		'ITEM' => array(
			'PREVIEW_TEXT' => $arResult['PREVIEW_TEXT'],
			'DETAIL_PICTURE' => $arResult['DETAIL_PICTURE']
		)
	);
?>
