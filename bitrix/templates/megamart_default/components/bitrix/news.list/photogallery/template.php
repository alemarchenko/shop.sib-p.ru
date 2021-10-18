<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Loader;
use	\Bitrix\Main\Localization\Loc;

$sBlockId = 'gallery_'.$this->randString(10);

$this->setFrameMode(true);

if (!empty($arResult['ITEMS'])):
?>
<div id="<?=$sBlockId?>">
	<div class="row text-center text-lg-left">
		<?php
		foreach ($arResult['ITEMS'] as $arItem):

			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

			$sPreviewImageSrc = isset($arItem['PREVIEW_PICTURE']['SRC'])
				? $arItem['PREVIEW_PICTURE']['SRC']
				: $templateFolder.'images/no-image.png';


			$sDetailImageSrc = isset($arItem['DETAIL_PICTURE']['SRC'])
				? $arItem['DETAIL_PICTURE']['SRC']
				: $sPreviewImageSrc;


			$sTitle = isset($arItem['DETAIL_PICTURE']['TITLE'])
				? $arItem['DETAIL_PICTURE']['TITLE']
				: '';

			$sAlt = isset($arItem['DETAIL_PICTURE']['ALT'])
				? $arItem['DETAIL_PICTURE']['ALT']
				: '';

			$sPreviewText = isset($arItem['PREVIEW_TEXT'])
				? HTMLToTxt($arItem['PREVIEW_TEXT'])
				: '';

		?>
		<div class="col-lg-4 col-md-4 col-xs-6">
			<figure class="photogallery-figure" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<?php if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y'): ?>
					<img class="img-fluid lazy-anim-img" src="<?=$arResult['EMPTY_IMAGE_SRC']?>" data-lazy-img data-src="<?=$sPreviewImageSrc?>"  alt="<?=$sAlt?>" title="<?=$sTitle?>">
				<?php else: ?>
					<img class="img-fluid" src="<?=$sPreviewImageSrc?>" alt="<?=$sAlt?>" title="<?=$sTitle?>">
				<?php endif; ?>

				<a href="<?=$sDetailImageSrc?>" class="photogallery-figure__link" data-fancybox="images"<?php if ($arItem['PREVIEW_TEXT']): ?> data-caption="<?=$sPreviewText?>"<?php endif; ?>></a>
			</figure>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php
endif;
