<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$this->addExternalCss(SITE_TEMPLATE_PATH.'/components/bitrix/news.list/list/style.css');

$strItemEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strItemDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arItemDeleteParams = array('CONFIRM' => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>

<?php if ($arParams['DISPLAY_TOP_PAGER']): ?>
	<?=$arResult['NAV_STRING']?>
<?php endif; ?>

<ul class="b-news-list-tile row list-unstyled mb-0">
<?php foreach ($arResult['ITEMS'] as $arItem):
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strItemEdit);
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strItemDelete, $arItemDeleteParams);
	?>
	<li class="<?=$arParams['RS_GRID_CLASS']?> mb-6 d-flex" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

		<div class="b-news-list-tile__item"><?php
			if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])) {
				$tag = 'a';
			} else {
				$tag = 'div';
			}
			?><<?=$tag?> <?
				?>href="<?=$arItem['DETAIL_PAGE_URL']?>" <?
				?>title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>" <?
				if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y') {
					?>class="b-news-list-tile__pic lazy-anim-bg" <?
					?>data-lazy-img <?
					?>data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']; ?>" <?
				} else {
					?>class="b-news-list-tile__pic" <?
					?>style="background-image:url('<?=$arItem['PREVIEW_PICTURE']['SRC']?>')" <?
				}
				?>>
			<?php if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
			</a>
			<?php else: ?>
			</div>
			<?php endif; ?>

			<div class="b-news-list-tile__body">
				<div class="b-news-list-tile__stickers">
					<div class="b-news-list-tile__stickers-in">
						<?php
						if (isset($arResult['IBLOCKS'][$arItem['IBLOCK_ID']]['NAME'])):
							$sColor = isset($arParams['RS_TAG_'.$arItem['IBLOCK_ID'].'_COLOR']) ? $arParams['RS_TAG_'.$arItem['IBLOCK_ID'].'_COLOR'] : "#000";

					 	?>
						<a class="c-sticker" href="<?=$arResult['IBLOCKS'][$arItem['IBLOCK_ID']]['LIST_PAGE_URL']?>" style="background-color: <?=$sColor?>; color: <?=$sColor?>">
							<span class="c-sticker__name"><?=$arResult['IBLOCKS'][$arItem['IBLOCK_ID']]['NAME']?></span>
						</a>
						<?php endif; ?>
					</div>
				</div>
				<div class="b-news-list-tile__head">
					<div class="b-news-list-tile__info"><?
						?><?php if (!empty($arItem['DISPLAY_ACTIVE_FROM'])): ?><?
						?><span <?
							?>class="b-news-list-tile__info-date"><?=$arItem['DISPLAY_ACTIVE_FROM']?><?
						?></span><?
						?><?php endif; ?><?
					?></div>
					<h6 class="b-news-list-tile__title">
						<?php if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=$arItem['NAME']?>"><?=$arItem['NAME']?></a>
						<?php else: ?><?=$arItem['NAME']?><?php endif; ?>
					</h6>
					<?php if ($arParams['DISPLAY_PREVIEW_TEXT'] == 'Y' && !empty($arItem['PREVIEW_TEXT'])): ?>
					<div class="b-news-list-tile__preview-text"><?=$arItem['PREVIEW_TEXT']?></div>
					<?php endif; ?>
				</div>
			</div>
		</div><!-- /b-news-list__item -->

	</li>
<?php endforeach; ?>
</ul>

<?php if ($arParams['DISPLAY_BOTTOM_PAGER']): ?>
	<?=$arResult['NAV_STRING']?>
<?php endif; ?>
