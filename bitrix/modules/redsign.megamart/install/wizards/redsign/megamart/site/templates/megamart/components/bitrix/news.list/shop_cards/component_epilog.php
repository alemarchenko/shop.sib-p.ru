<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__DIR__.'/template.php');

$arParams['PROP_TYPE'] = isset($arParams['PROP_TYPE']) ? $arParams['PROP_TYPE'] : 'TYPE';
$arParams['PROP_COORDS'] = isset($arParams['PROP_COORDS']) ? $arParams['PROP_COORDS'] : 'COORDS';
$arParams['PROP_STORE_ID'] = isset($arParams['PROP_STORE_ID']) ? $arParams['PROP_STORE_ID'] : 'STORE_ID';
$arParams['PROP_CITY'] = isset($arParams['PROP_CITY']) ? $arParams['PROP_CITY'] : 'CITY';

$arParams['PROP_ADDRESS'] = isset($arParams['PROP_ADDRESS']) ? $arParams['PROP_ADDRESS'] : 'ADDRES';
$arParams['PROP_SCHEDULE'] = isset($arParams['PROP_SCHEDULE']) ? $arParams['PROP_SCHEDULE'] : 'SCHEDULE';
$arParams['PROP_PHONE'] = isset($arParams['PROP_PHONE']) ? $arParams['PROP_PHONE'] : 'PHONE_NUMBER';
$arParams['PROP_EMAIL'] = isset($arParams['PROP_EMAIL']) ? $arParams['PROP_EMAIL'] : 'EMAIL';

$arParams['SKIP_DISPLAY_PROPERTIES'] = [
	$arParams['PROP_TYPE'],
	$arParams['PROP_COORDS'],
	$arParams['PROP_STORE_ID']
 ];

if (!empty($templateData['ITEMS']) && is_array($templateData['ITEMS'])):

	$layout = new \Redsign\MegaMart\Layouts\Section();

	$layout
		->addModifier('outer-spacing');

	$layout->start();
?>
<div class="shop-cards" <?php if (!empty($arParams['RS_BACKGROUND_PATH'])): ?>style="background-image: url('<?=$arParams['RS_BACKGROUND_PATH']?>');"<?php endif; ?>>
	<div class="container">
		<div class="row align-items-center shop-cards__list" >


			<div class="col-12 col-sm-6 col-xl-3">
				<div class="shop-cards__item shop-card shop-card-auto" >
					<?php
					if ($arParams['RS_USE_LOCATION'] == 'Y') {
						$APPLICATION->IncludeComponent(
							'redsign:location.main',
							'in_card',
							array()
						);
					}
					?>

					<?php if ($arParams['RS_SHOW_RECALL_BUTTON'] == 'Y'): ?>
						<a href="<?=$arParams['RS_RECALL_LINK']?>" class="btn btn-primary mt-3" data-type="ajax"><?=$arParams['RS_RECALL_TITLE']?></a>
					<?php endif; ?>

					<?php if ($arParams['RS_SHOW_STORES_LINK']): ?>
						<a href="<?=$arParams['RS_STORES_LINK']?>" class="btn btn-link mt-3"><?=Loc::getMessage('RS_ALL_STORES')?></a>
					<?php endif; ?>
				</div>
			</div>

			<?php
			foreach ($templateData['ITEMS'] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="col-12 col-sm-6 col-xl-3">
				<a class="shop-cards__item shop-card" href="<?=$arItem['DETAIL_PAGE_URL']?>"  id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<span class="shop-card__icon d-block">
						<svg class="icon-svg text-primary"><use xlink:href="#svg-map-pin"></use></svg>
					</span>
					<span class="shop-card__name d-block"><?=$arItem['NAME']?></span>

					<?php if (!empty($arItem['DISPLAY_PROPERTIES'])): ?>
						<div class="shop-card__props d-block">
						<?php
						foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp):
							if (in_array($arProp['CODE'], $arParams['SKIP_DISPLAY_PROPERTIES'])) {
								continue;
							}
						?>
							<?php if ($arProp['CODE'] == $arParams['PROP_ADDRESS']): ?>
								<span class="d-block"><?=$arProp['DISPLAY_VALUE']?></span>
							<?php else: ?>
								<span class="d-block"><?=$arProp['NAME']?>: <?=$arProp['DISPLAY_VALUE']?></span>
							<?php endif; ?>
						<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<span class="shop-card__go-button">
						<svg class="icon-svg"><use xlink:href="#svg-arrow-right"></use></svg>
					</span>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<?php
	$layout->end();
endif;
