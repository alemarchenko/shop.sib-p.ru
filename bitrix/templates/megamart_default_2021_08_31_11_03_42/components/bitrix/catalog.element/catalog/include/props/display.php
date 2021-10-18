<?php

use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

if ($arResult['MODULES']['redsign.grupper'])
{
	$APPLICATION->IncludeComponent(
		'redsign:grupper.list',
		'catalog',
		array(
			'DISPLAY_PROPERTIES' => $arDisplayProperties,
			'CACHE_TYPE' => 'N',
			'SHOW_OFFERS_PROPS' => $arResult['SHOW_OFFERS_PROPS'] ? 'Y' : 'N',
			'OFFERS_PROP_DIV' => $itemIds['DISPLAY_PROP_DIV'] ?: '',
		),
		$component
	);
}
else	
{
	?>
	<div class="mb-4">
		<dl class="product-cat-properties font-size-sm">
			<?
			foreach ($arDisplayProperties as $property)
			{
				?>
				<dt><?=$property['NAME']?>:</dt>
				<dd><?=(
					is_array($property['DISPLAY_VALUE'])
						? implode(' / ', $property['DISPLAY_VALUE'])
						: $property['DISPLAY_VALUE']
					)?>
				</dd>
				<?
			}
			unset($property);
			?>
		</dl>
		<?php
		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			?>
			<dl class="product-cat-properties" id="<?=$itemIds['DISPLAY_PROP_DIV']?>"></dl>
			<?
		}
		?>
	</div>
	<?
}
?>
