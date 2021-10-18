<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$sBlockId = 'rs-brands-'.randString(5);

if (!isset($arParams['ADD_REVIEW_BUTTON'])) {
	$arParams['ADD_PREVIEW_BUTTON'] = SITE_DIR.'forms/review/';
}

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0):?>

	<?php
	$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
	$layout
		->addModifier('bg-white')
		->addModifier('shadow')
		->addModifier('outer-spacing');

	if (!$layout->hasModifier('container')) {
		$layout->addModifier('inner-spacing');
	}

	if ($arParams['USE_OWL'] == 'Y') {
		$layout->useSlider($sBlockId);
	}

	$layout->start();
	?>

	<?php if ($arParams['SHOW_IBLOCK_DESCRIPTION'] == 'Y'): ?>
	<div class="block-spacing-negative block-spacing border-bottom border-body-bg mb-5">
		<div class="d-flex align-items-center flex-column flex-sm-row flex-md-column flex-lg-row">
			<div class="text-body"><?=$arResult['DESCRIPTION']?></div>
			<div class="my-4 my-sm-0 ml-sm-4 my-md-4 my-lg-0">
				<a href="<?=$arParams['ADD_PREVIEW_BUTTON']?>" class="btn btn-outline-primary" data-type="ajax">
					<?=Loc::getMessage('RS_NL_ADD_REVIEW');?>
				</a>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if($arParams["DISPLAY_TOP_PAGER"]): ?>
		<?=$arResult["NAV_STRING"]?><br />
	<?php endif; ?>

	<div class="pt-6 pb-md-7">
		<div <?php if ($arParams['USE_OWL'] == 'Y'): ?>data-slider="<?=$sBlockId?>" data-slider-options="<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($arResult['RS_SLIDER_OPTIONS']))?>" class="<?=$arResult['RS_SLIDER_CLASSES']?>"<?php else: ?>class="row base-margin-negative" <?php endif; ?>>
			<?php foreach ($arResult['ITEMS'] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div id="<?=$this->GetEditAreaId($arItem['ID']);?>"<?php if ($arParams['USE_OWL'] != 'Y'): ?> class="col-12 mb-4"<?php endif; ?>>
				<div class="review-item<?php if ($arParams['USE_OWL'] == 'Y'):?> review-item--slider<?php else: ?> review-item--line<?php endif; ?>">
					<div class="review-item__face">
						<div class="review-item__img-wrap">
							<?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_AUTHOR_PHOTO']]['FILE_VALUE'])): ?>
								<img class="review-item__img" src="<?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_AUTHOR_PHOTO']]['FILE_VALUE']['SRC']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
							<?php else: ?>
								<img class="review-item__img" src="<?=$templateFolder.'/images/nophoto.png'?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
							<?php  endif; ?>
						</div>
					</div>
					<div class="review-item__data">

						<div class="review-item__userdata">
							<?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_AUTHOR_POSITION']])): ?>
							<div class="review-item__userdata-position"><?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_AUTHOR_POSITION']]['DISPLAY_VALUE']?></div>
							<?php endif; ?>

							<?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_AUTHOR_NAME']])): ?>
							<div class="review-item__userdata-name"><?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_AUTHOR_NAME']]['DISPLAY_VALUE']?></div>
							<?php endif; ?>
						</div>

						<?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_REVIEW']])): ?>
						<div class="review-item__post"><?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_REVIEW']]['DISPLAY_VALUE']?></div>
						<?php endif; ?>

						<?php if ($arParams['DISPLAY_DATE'] == 'Y'): ?>
						<div class="review-item__date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
		<br><?=$arResult["NAV_STRING"]?>
	<?php endif; ?>

	<?php $layout->end(); ?>
<?php endif;
