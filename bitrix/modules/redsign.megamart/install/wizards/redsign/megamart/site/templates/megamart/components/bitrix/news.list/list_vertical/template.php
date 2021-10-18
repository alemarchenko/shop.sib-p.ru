<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Loader;
use	\Bitrix\Main\Localization\Loc;

$sBlockId = $this->randString(10);

$this->setFrameMode(true);

$this->addExternalCss(SITE_TEMPLATE_PATH.'/components/bitrix/news.list/list/style.css');

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
$layout
	->addModifier('bg-lg')
	->addModifier('outer-spacing');

$layout->start();
?>
<div id="<?=$sBlockId?>">
	<?php
	$strItemEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
	$strItemDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
	$arItemDeleteParams = array('CONFIRM' => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
	?>

	<?php if ($arParams['DISPLAY_TOP_PAGER']): ?>
		<?=$arResult['NAV_STRING']?>
	<?php endif; ?>

	<ul class="b-news-list-tile row list-unstyled mb-0">
	<?php
	$nCountItems = count($arResult['ITEMS']);
	foreach ($arResult['ITEMS'] as $nIndex => $arItem):
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strItemEdit);
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strItemDelete, $arItemDeleteParams);
		
		$sGridClass = 'd-flex col-12 col-sm-6 col-xl-12 mb-xl-0';
		if ($nIndex + 1 < $nCountItems)
		{
			$sGridClass .= ' mb-6';
			
			if ($nIndex + 2 == $nCountItems)
			{
				$sGridClass .= ' mb-sm-0';
			}
		}
		?>
		<li class="<?=$sGridClass?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

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
						<div class="b-news-list-tile__stickers-in"><?
							?><?php if ($arParams['STICKER_IBLOCK'] == 'Y'): ?><div class="c-sticker"><span class="c-sticker__name"><?=$arResult['NAME']?></span></div><?php endif; ?><?
							if (is_array($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['DISPLAY_VALUE'])) {
								foreach ($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['DISPLAY_VALUE'] as $key => $stickerName) {
									$color = $arItem['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['VALUE_XML_ID'][$key];
									?><div <?
										?>class="c-sticker" <?
										?><?=(!empty($color) ? ' style="background-color: #'.$color.';color: #'.$color.';"' : '')?><?
										?>><span class="c-sticker__name"><?=$stickerName?></span><?
									?></div><?
								}
							} elseif (!empty($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['DISPLAY_VALUE'])) {
								$color = $arItem['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['VALUE_XML_ID'][0];
								$stickerName = $arItem['DISPLAY_PROPERTIES'][$arParams['PROP_STICKER']]['DISPLAY_VALUE'];
								?><div <?
									?>class="c-sticker" <?
									?><?=(!empty($color) ? ' style="background-color: #'.$color.';color: #'.$color.';"' : '')?><?
									?>><span class="c-sticker__name"><?=$stickerName?></span><?
								?></div><?
							}
						?></div>
					</div>
					<div class="b-news-list-tile__head">
						<div class="b-news-list-tile__info"><?
							?><?php if (!empty($arItem['DISPLAY_ACTIVE_FROM'])): ?><?
							?><span <?
								?>class="b-news-list-tile__info-date"><?=$arItem['DISPLAY_ACTIVE_FROM']?><?
							?></span><?
							?><?php endif; ?><?
							?><?php if (!empty($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_SLOGAN']]['DISPLAY_VALUE'])): ?><?
							?><span class="b-news-list-tile__info-slogan"><?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_SLOGAN']]['DISPLAY_VALUE']?></span><?
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

</div>

<?php
$layout->end();
