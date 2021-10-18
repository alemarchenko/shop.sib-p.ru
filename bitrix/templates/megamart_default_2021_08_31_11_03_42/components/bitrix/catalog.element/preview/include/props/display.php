<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;

if ($arResult['MODULES']['redsign.grupper'])
{
	$APPLICATION->IncludeComponent('redsign:grupper.list',
		'.default',
		array(
			'DISPLAY_PROPERTIES' => $arDisplayProperties,
			'CACHE_TYPE' => 'N',
		),
		$component,
		array('HIDE_ICONS' => 'Y')
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
	</div>
	<?
}

if ($arResult['SHOW_OFFERS_PROPS'])
{
	?>
	<dl class="product-cat-properties" id="<?=$itemIds['DISPLAY_PROP_DIV']?>"></dl>
	<?
}
?>
