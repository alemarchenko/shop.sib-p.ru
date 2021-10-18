<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(false);

$sBlockId = 'wishlist_'.$this->GetEditAreaId($arResult['ID']);

ob_start();

if (!empty($arResult['ITEMS']) > 0):
?>
<div class="simple-basket" id="<?=$sBlockId?>">
	<div class="simple-basket__items scrollbar-inner">
		<table class="simple-basket__table table">
			<thead>
				<th></th>
				<th><?=Loc::getMessage('RS_CS_PANEL_TABLE_TH_NAME')?></th>
				<th><?=Loc::getMessage('RS_CS_PANEL_TABLE_TH_PRICE')?></th>
				<th></th>
			</thead>
			<tbody id="<?=$sBlockId?>-item-table">
				<?php
				foreach ($arResult['ITEMS'] as $arItem):

					$sItemId = $this->GetEditAreaId($arItem['ID']);

					$haveOffers = !empty($arItem['OFFERS']);
					$itemHasDetailUrl = isset($arItem['DETAIL_PAGE_URL']) && $arItem['DETAIL_PAGE_URL'] != '';

					if ($haveOffers)
					{
						$arActualItem = isset($arItem['OFFERS'][$arItem['OFFERS_SELECTED']])
							? $arItem['OFFERS'][$arItem['OFFERS_SELECTED']]
							: reset($arItem['OFFERS']);
					}
					else
					{
						$arActualItem = $arItem;
					}

					if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
					{
						$arPrice = $arItem['ITEM_START_PRICE'];
						$arMinOffer = $arItem['OFFERS'][$arItem['ITEM_START_PRICE_SELECTED']];
						$nMeasureRatio = $arMinOffer['ITEM_MEASURE_RATIOS'][$arMinOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
						$arMorePhoto = $arItem['MORE_PHOTO'];
					}
					else
					{
						$arPrice = $arActualItem['ITEM_PRICES'][$arActualItem['ITEM_PRICE_SELECTED']];
						$nMeasureRatio = $arPrice['MIN_QUANTITY'];
						$arMorePhoto = $arActualItem['MORE_PHOTO'];
					}
				?>
					<tr class="simple-basket-item" id="<?=$sItemId?>" data-entity="item" data-id="<?=$arItem['ID']?>">
						<td class="simple-basket-item__cell simple-basket-item__cell--picture">
							<div class="simple-basket-item__img-block">
								<img class="simple-basket-item__img" alt="<?=$arItem['NAME']?>"
									src="<?=isset($arActualItem['PREVIEW_PICTURE']['SRC']) ? $arActualItem['PREVIEW_PICTURE']['SRC'] : $arItem['PREVIEW_PICTURE']['SRC']?>">
							</diV>
						</td>
						<td class="simple-basket-item__cell simple-basket-item__cell--info">

							<h2 class="simple-basket-item__name">
								<?php if ($itemHasDetailUrl) :?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="basket-item-info-name-link">
										<?=$arItem['NAME']?>
									</a>
								<?php else: ?>
									<?=$arItem['NAME']?>
								<?php endif; ?>
							</h2>
						</td>

						<td class="simple-basket-item__cell simple-basket-item__cell--price text-nowrap">

							<span class="product-light-price-current<?=($price['RATIO_PRICE'] < $price['RATIO_BASE_PRICE'] ? ' discount' : '')?>">
								<?php
								if (!empty($arPrice))
								{
									?>
									<div class="simple-basket-item__price"><?=Loc::getMessage('RS_CS_PANEL_ITEM_PRICE_FROM'); ?><?=$arPrice['PRINT_RATIO_PRICE']?></div>
									<div class="simple-basket-item__price-desc"><?=Loc::getMessage('RS_CS_PANEL_ITEM_PRICE_FOR')?> <?=$nMeasureRatio?> <?=$arActualItem['ITEM_MEASURE']['TITLE']?></div>
									<?php
								}
								else
								{
									echo Loc::getMessage('RS_CS_PANEL_ITEM_PRICE_NO_PRICE');
								}
								?>
							</span>
						</td>

						<td class="simple-basket-item__cell simple-basket-item__cell--actions">
							<div class="simple-basket-item__actions">
								<svg class="icon-svg  trash-anim-icon" data-entity="remove-item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">
									<path xmlns="http://www.w3.org/2000/svg" d="M29,13H25V12a3,3,0,0,0-3-3H18a3,3,0,0,0-3,3v1H11a1,1,0,0,0,0,2H29a1,1,0,0,0,0-2ZM17,13V12a1,1,0,0,1,1-1h4a1,1,0,0,1,1,1v1Z"/>
									<path xmlns="http://www.w3.org/2000/svg" d="M25,31H15a3,3,0,0,1-3-3V15a1,1,0,0,1,2,0V28a1,1,0,0,0,1,1H25a1,1,0,0,0,1-1V15a1,1,0,0,1,2,0V28A3,3,0,0,1,25,31Zm-6-6V19a1,1,0,0,0-2,0v6a1,1,0,0,0,2,0Zm4,0V19a1,1,0,0,0-2,0v6a1,1,0,0,0,2,0Z"/>
								</svg>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<script>
	if (window.$ && $.fn.scrollbar) {
		$('.simple-basket__items').scrollbar();
	}

	new JCWishlistPanel('<?=$sBlockId?>');
	</script>
</div>
<?php elseif ($arParams['RS_SHOW_EMPTY_ERROR'] == 'Y'): ?>
	<div class="basket-empty mt-7 pt-7">
		<h2 class="basket-empty__title"><?=$arParams['RS_EMPTY_ERROR_TITLE']?></h2>
		<div class="basket-empty__descr"><?=$arParams['RS_EMPTY_ERROR_DESC']?></div>

		<?php if (!empty($arParams['RS_EMPTY_ERROR_BUTTON_TITLE']) && !empty($arParams['RS_EMPTY_ERROR_BUTTON_LINK'])): ?>
		<div class="basket-empty__buttons">
			<a href="<?=$arParams['RS_EMPTY_ERROR_BUTTON_LINK']?>" class="btn btn-primary"><?=$arParams['RS_EMPTY_ERROR_BUTTON_TITLE']?></a>
		</div>
		<?php endif; ?>
	</div>
<?php endif;


$templateData = ob_get_clean();
