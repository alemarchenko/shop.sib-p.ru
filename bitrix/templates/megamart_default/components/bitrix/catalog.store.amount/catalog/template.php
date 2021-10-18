<?php

use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\Layouts;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$this->setFrameMode(true);

if (!function_exists('getStringCatalogStoreAmountEx'))
{
	function getStringCatalogStoreAmountEx($amount, $minAmount, $arReturn){
		$amount = (float)$amount;
		$minAmount = (float)$minAmount;
		$message = $arReturn['NOT_MUCH_GOOD'];
		if ($amount <= 0)
			$message = $arReturn['ABSENT'];
		elseif ($amount >= $minAmount)
			$message = $arReturn['LOT_OF_GOOD'];
		return $message;
	}
}

$this->addExternalCss(SITE_TEMPLATE_PATH.'/assets/styles/shop-list-items.css');

?>

<?php
if ($arParams['ADD_LAYOUT'] == 'Y')
{
	$layout = new Layouts\Section();

	$layoutHeader = new Layouts\Parts\SectionHeaderBase();
	$layoutHeader->addData('TITLE', $arParams['MAIN_TITLE']);

	$layout
		->useHeader($layoutHeader)
		->addModifier('bg-white')
		->addModifier('shadow')
		->addModifier('outer-spacing')
		->addData('SECTION_ATTRIBUTES', ' id="'.$arParams['MAIN_ID'].'_'.$arParams['blockName'].'" data-spy="item" data-target="#l-main__nav" data-title="'.$arParams['MAIN_TITLE'].'"');

	$layout->start();
}
?>

<div
	class="bx_storege"
	id="<?=(!empty($arResult['JS']['ID']) ? $arResult['JS']['ID'].'_main' : 'catalog_store_amount_div')?>"
	style="<?=(($arParams['SHOW_EMPTY_STORE'] == 'N' && !$arParams['HAS_SHOWED_STOCK']) ? 'display:none;' : 'display:block;')?>"
>

	<?php if (!empty($arResult["STORES"])): ?>
	<ul class="shop-list-items list-unstyled" id="c_store_amount">
		<?php foreach ($arResult["STORES"] as $pid => $arProperty): ?>
			<?php
			$bImage = isset($arProperty['IMAGE_ID']) && !empty($arProperty['IMAGE_ID']);
			?>
			<li class="shop-list-item" style="display: <? echo ($arParams['SHOW_EMPTY_STORE'] == 'N' && isset($arProperty['REAL_AMOUNT']) && $arProperty['REAL_AMOUNT'] <= 0 ? 'none' : ''); ?>;">
				<div class="shop-list-item__container">
					<?php
					if ($bImage)
					{
						$image = CFile::ResizeImageGet(
							$arProperty['IMAGE_ID'],
							array('width' => 370, 'height' => 200),
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true
						);
						?>
						<div class="shop-list-item__picture">
							<img src="<?=$image['src']?>" alt="<?=$arProperty['TITLE']?>">
						</div>
						<?php
					}
					else
					{
						?>
						<div class="shop-list-item__picture">
							<svg class="shop-list-item__icon icon-svg"><use xlink:href="#svg-map-pin"></use></svg>
						</div>
						<?php
					}
					?>
					<div class="shop-list-item__data">
						<?if (isset($arProperty['USER_FIELDS']['UF_SHOPS_ITEM'])):?>
							<div class="shop-list-item__name">
								<?=$arProperty['USER_FIELDS']['UF_SHOPS_ITEM']['CONTENT']?>
								<span class="badge <?=($arProperty['REAL_AMOUNT'] ? 'badge-primary' : 'badge-secondary')?>" data-entity="stock-amount"><?=($arProperty['REAL_AMOUNT'] ?: '')?></span>
							</div>
						<?elseif (isset($arProperty["TITLE"])):?>
							<div class="shop-list-item__name">
								<a href="<?=$arProperty["URL"]?>"><?=$arProperty["TITLE"]?></a>
								<span class="badge <?=($arProperty['REAL_AMOUNT'] ? 'badge-primary' : 'badge-secondary')?>" data-entity="stock-amount"><?=($arProperty['REAL_AMOUNT'] ?: '')?></span>
							</div>
						<?endif;?>
						<?if (isset($arProperty["PHONE"])):?>
							<div class="shop-list-item__prop shop-list-item__prop--phone">
								<svg class="icon-svg "><use xlink:href="#svg-phonetube"></use></svg>
								<?=GetMessage('S_PHONE')?> <?=$arProperty["PHONE"]?>
							</div>
						<?endif;?>
						<?if (isset($arProperty["SCHEDULE"])):?>
							<div class="shop-list-item__prop shop-list-item__prop--schedule">
								<svg class="icon-svg"><use xlink:href="#svg-clock"></use></svg>
								<?=GetMessage('S_SCHEDULE')?> <?=$arProperty["SCHEDULE"]?>
							</div>
						<?endif;?>
						<?if (isset($arProperty["EMAIL"])):?>
							<div class="shop-list-item__prop shop-list-item__prop--email">
								<svg class="icon-svg"><use xlink:href="#svg-e-mail"></use></svg>
								<?=GetMessage('S_EMAIL')?> <?=$arProperty["EMAIL"]?>
							</div>
						<?endif;?>
						<?if (isset($arProperty["DESCRIPTION"])):?>
							<div class="shop-list-item__prop shop-list-item__prop--custom">
								<?=GetMessage('S_DESCRIPTION')?> <?=$arProperty["DESCRIPTION"]?>
							</div>
						<?endif;?>
						<?if (isset($arProperty["COORDINATES"])):?>
							<div class="shop-list-item__prop shop-list-item__prop--custom">
								<?=GetMessage('S_COORDINATES')?> <?=$arProperty["COORDINATES"]["GPS_N"]?>, <?=$arProperty["COORDINATES"]["GPS_S"]?>
							</div>
						<?endif;?>

						<?
						if (!empty($arProperty['USER_FIELDS']) && is_array($arProperty['USER_FIELDS']))
						{
							foreach ($arProperty['USER_FIELDS'] as $code => $userField)
							{
								if (in_array($code, array('UF_SHOPS_ITEM')))
									continue;

								if (isset($userField['CONTENT']))
								{
									?><div class="shop-list-item__prop shop-list-item__prop--custom">
										<?=$userField['TITLE']?>: <?=$userField['CONTENT']?>
									</div><?
								}
							}
							unset($code, $userField);
						}
						?>
					</div>
					<div class="col flex-grow-1 d-none d-lg-block px-4"><hr class="w-100"></div>
					<div class="col-lg-auto p-0">
						<div class="shop-list-item__prop shop-list-item__prop--custom">
							<span class="product-cat-limit <?=getStringCatalogStoreAmountEx($arProperty['REAL_AMOUNT'], $arParams['MIN_AMOUNT'], $arResult['JS']['CLASSES'])?>" id="<?=$arResult['JS']['ID']?>_<?=$arProperty['ID']?>">
								<span class="product-cat-limit-quantity" data-entity="quantity-limit-value">
									<?php
									echo ($arParams['USE_MIN_AMOUNT'] == 'Y')
										? getStringCatalogStoreAmountEx($arProperty['REAL_AMOUNT'], $arParams['MIN_AMOUNT'], $arResult['JS']['MESSAGES'])
										: $arProperty['AMOUNT']
									?>
								</span>
							</span>
						</div>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>

</div>

<div
	id="<?=(!empty($arResult['JS']['ID']) ? $arResult['JS']['ID'].'_none' : 'catalog_store_amount_div_none')?>"
	style="<?=(($arParams['SHOW_EMPTY_STORE'] == 'N' && !$arParams['HAS_SHOWED_STOCK']) ? 'display:block;' : 'display:none;')?>"
>
	<div class="shop-list-empty text-left">
		<div class="row align-items-center">
			<div class="col text-center mb-4 mb-sm-0">
				<svg class="text-primary icon-svg h4 mb-0 mr-5 d-none d-sm-inline"><use xlink:href="#svg-map-pin"></use></svg>
				<span><?=Loc::getMessage('RS_MM_BCSA_EMPTY_STORE_LIST')?></span>
			</div>
		</div>
	</div>
</div>

<?php
if ($arParams['ADD_LAYOUT'] == 'Y')
{
	$layout->end();
}
?>

<?php if (isset($arResult["IS_SKU"]) && $arResult["IS_SKU"] == 1): ?>
<script type="text/javascript">
	var obStoreAmount = new JCCatalogStoreSKU(<? echo CUtil::PhpToJSObject($arResult['JS'], false, true, true); ?>);
</script>
<?php endif; ?>
