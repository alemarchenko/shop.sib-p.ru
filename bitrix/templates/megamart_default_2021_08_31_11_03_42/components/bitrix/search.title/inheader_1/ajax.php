<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

if(!empty($arResult["SEARCH"])):?>
<div class="w-100">
<?php
foreach ($arResult['SEARCH'] as $arItem):
	if (isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
		$arCatalogItem = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];
	?>
		<div class="title-search-item">
			<a href="<?=$arItem['URL']?>" class="title-search-item__picture">
			<?php if (is_array($arCatalogItem['PICTURE'])): ?>
				<img src="<?=$arCatalogItem["PICTURE"]["src"]?>">
			<?php endif; ?>
			</a>

			<div class="title-search-item__data">
				<a href="<?=$arItem['URL']?>" class="title-search-item__name"><?=$arItem["NAME"]?></a>

				<?php if (!empty($arCatalogItem['MIN_PRICE'])): ?>
					<div class="title-search-item__price">
						<?=Loc::getMessage('RS_ST_PRICE_FROM');?>
						<span class="title-search-item__price-current"><?=$arCatalogItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span>
						<?php if (isset($arCatalogItem["MIN_PRICE"]['DISCOUNT_VALUE']) && $arCatalogItem["MIN_PRICE"]["DISCOUNT_VALUE"] < $arCatalogItem["MIN_PRICE"]["VALUE"]): ?>
							<span class="title-search-item__price-discount"><?=$arCatalogItem["MIN_PRICE"]["PRINT_VALUE"]?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php else: ?>
		<div class="title-search-item"><a href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a></div>
	<?php endif;	?>
<?php endforeach; ?>
	<div class="title-search-item title-search-item--no-picture">
		<a class="title-search-item__data no-picture" href="<?=$arResult['ALL_RESULTS']['URL']?>"><?=$arResult['ALL_RESULTS']['NAME']?></a>
	</div>
</div>
<?php
endif;
return;
?>
<?
if(!empty($arResult["CATEGORIES"])):?>
	<table class="title-search-result">
		<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
			<tr>
				<th class="title-search-separator">&nbsp;</th>
				<td class="title-search-separator">&nbsp;</td>
			</tr>
			<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<tr>
				<?if($i == 0):?>
					<th>&nbsp;<?echo $arCategory["TITLE"]?></th>
				<?else:?>
					<th>&nbsp;</th>
				<?endif?>

				<?if($category_id === "all"):?>
					<td class="title-search-all"><a href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"]?></a></td>
				<?elseif(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
					$arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];
				?>
					<td class="title-search-item"><a href="<?echo $arItem["URL"]?>"><?
						if (is_array($arElement["PICTURE"])):?>
							<img align="left" src="<?echo $arElement["PICTURE"]["src"]?>" width="<?echo $arElement["PICTURE"]["width"]?>" height="<?echo $arElement["PICTURE"]["height"]?>">
						<?endif;?><?echo $arItem["NAME"]?></a>
						<p class="title-search-preview"><?echo $arElement["PREVIEW_TEXT"];?></p>
						<?foreach($arElement["PRICES"] as $code=>$arPrice):?>
							<?if($arPrice["CAN_ACCESS"]):?>
								<p class="title-search-price"><?=$arResult["PRICES"][$code]["TITLE"];?>:&nbsp;&nbsp;
								<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
									<s><?=$arPrice["PRINT_VALUE"]?></s> <span class="catalog-price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
								<?else:?><span class="catalog-price"><?=$arPrice["PRINT_VALUE"]?></span><?endif;?>
								</p>
							<?endif;?>
						<?endforeach;?>
					</td>
				<?elseif(isset($arItem["ICON"])):?>
					<td class="title-search-item"><a href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"]?></a></td>
				<?else:?>
					<td class="title-search-more"><a href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"]?></a></td>
				<?endif;?>
			</tr>
			<?endforeach;?>
		<?endforeach;?>
		<tr>
			<th class="title-search-separator">&nbsp;</th>
			<td class="title-search-separator">&nbsp;</td>
		</tr>
	</table><div class="title-search-fader"></div>
<?endif;
?>
