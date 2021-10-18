<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

use \Bitrix\Main\Localization\Loc;

if (!$haveOffers)
{
	if (!empty($item['DISPLAY_PROPERTIES']))
	{
		?>
		<div class="product-cat-info-container product-cat-hidden small" data-entity="props-block">
			<dl class="product-cat-properties">
				<?
				foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty)
				{
					?>
					<dt<?=(!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '')?>>
						<?=$displayProperty['NAME']?>:
					</dt>
					<dd<?=(!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '')?>>
						<?=(is_array($displayProperty['DISPLAY_VALUE'])
							? implode(' / ', $displayProperty['DISPLAY_VALUE'])
							: $displayProperty['DISPLAY_VALUE'])?>
					</dd>
					<?
				}
				?>
			</dl>
		</div>
		<?
	}

	if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES']))
	{
		?>
		<div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
			<?
			if (!empty($item['PRODUCT_PROPERTIES_FILL']))
			{
				foreach ($item['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
				{
					?>
					<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]"
						value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
					<?
					unset($item['PRODUCT_PROPERTIES'][$propID]);
				}
			}

			if (!empty($item['PRODUCT_PROPERTIES']))
			{
				?>
				<table>
					<?
					foreach ($item['PRODUCT_PROPERTIES'] as $propID => $propInfo)
					{
						?>
						<tr>
							<td><?=$item['PROPERTIES'][$propID]['NAME']?></td>
							<td>
								<?
								if (
									$item['PROPERTIES'][$propID]['PROPERTY_TYPE'] === 'L'
									&& $item['PROPERTIES'][$propID]['LIST_TYPE'] === 'C'
								)
								{
									foreach ($propInfo['VALUES'] as $valueID => $value)
									{
										?>
										<label>
											<? $checked = $valueID === $propInfo['SELECTED'] ? 'checked' : ''; ?>
											<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]"
												value="<?=$valueID?>" <?=$checked?>>
											<?=$value?>
										</label>
										<br />
										<?
									}
								}
								else
								{
									?>
									<select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]">
										<?
										foreach ($propInfo['VALUES'] as $valueID => $value)
										{
											$selected = $valueID === $propInfo['SELECTED'] ? 'selected' : '';
											?>
											<option value="<?=$valueID?>" <?=$selected?>>
												<?=$value?>
											</option>
											<?
										}
										?>
									</select>
									<?
								}
								?>
							</td>
						</tr>
						<?
					}
					?>
				</table>
				<?
			}
			?>
		</div>
		<?
	}
}
else
{
	$showProductProps = !empty($item['DISPLAY_PROPERTIES']);
	$showOfferProps = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $item['OFFERS_PROPS_DISPLAY'];

	if ($showProductProps || $showOfferProps)
	{
		?>
		<div class="product-cat-info-container product-cat-hidden small" data-entity="props-block">
			<dl class="product-cat-properties">
				<?
				if ($showProductProps)
				{
					foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty)
					{
						?>
						<dt<?=(!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '')?>>
							<?=$displayProperty['NAME']?>
						</dt>
						<dd<?=(!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '')?>>
							<?=(is_array($displayProperty['DISPLAY_VALUE'])
								? implode(' / ', $displayProperty['DISPLAY_VALUE'])
								: $displayProperty['DISPLAY_VALUE'])?>
						</dd>
						<?
					}
				}

				if ($showOfferProps)
				{
					?>
					<span id="<?=$itemIds['DISPLAY_PROP_DIV']?>" style="display: none;"></span>
					<?
				}
				?>
			</dl>
		</div>
		<?
	}
}