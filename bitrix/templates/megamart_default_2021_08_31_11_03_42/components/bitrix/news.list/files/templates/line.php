<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

$strItemEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strItemDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arItemDeleteParams = array('CONFIRM' => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
$layout
	->addModifier('bg-white')
	->addModifier('shadow');

$layout->start();
?>
<div class="row row-borders">
	<?php
	foreach ($arResult['ITEMS'] as $arItem):
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strItemEdit);
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strItemDelete, $arItemDeleteParams);

		if (!isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_FILE']]['FILE_VALUE'])) {
			continue;
		}

		$arFile = $arItem['DISPLAY_PROPERTIES'][$arParams['PROP_FILE']]['FILE_VALUE'];
	?>

	<div class="col-12">
		<div class="doc row align-items-center px-5 py-6" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="col-3 text-center">
				<?php if (isset($arItem['PREVIEW_PICTURE']['SRC'])):?>
					<a class="doc__preview" href="<?=$arFile['SRC']?>" >
						<?php if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y'): ?>
							<img class="img-fluid lazy-anim-img" src="<?=$arResult['EMPTY_IMAGE_SRC']?>" data-lazy-img data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
						<?php else: ?>
							<img class="img-fluid" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
						<?php endif; ?>
					</a>
				<?php else: ?>
					<a class="doc__preview" href="<?=$arFile['SRC']?>" >
						<img class="img-fluid" src="<?=$templateFolder?>/images/file.png">
					</a>
				<?php endif; ?>
			</div>
			<div class="col">
				<a href="<?=$arFile['SRC']?>" target="_blank" class="doc__name">
					<?=$arItem['NAME']?>
				</a>
				<?php if (isset($arItem['PREVIEW_TEXT'])): ?>
					<div class="doc__desc  mt-2"><?=$arItem['PREVIEW_TEXT']?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
