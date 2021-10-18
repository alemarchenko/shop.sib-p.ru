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
?>
<div class="row row-borders">
	<?php
	foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $arFile)
	{
		?>
		<div class="col-12">
			<div class="doc row align-items-center px-5 py-6">
				<div class="col-auto col-sm-2 text-center">
					<div class="doc__icon icon-<?=$arFile['FILE_ICON']?>">
						<svg class="icon-svg"><use xlink:href="#svg-folder"></use></svg>
						<div class="doc__type"><?=$arFile['FILE_EXT']?></div>
					</div>
				</div>
				<div class="col">
					<div class="doc__name">
						<?=($arFile['DESCRIPTION'] == '' ? $arFile['ORIGINAL_NAME'] : $arFile['DESCRIPTION'])?>
					</div>
					<a class="btn-link font-size-sm" href="<?=$arFile['FULL_PATH']?>" target="_blank" download>
						<?php
						echo Loc::getMessage('RS_MM_BCE_PREVIEW_DOWNLOAD_FILE').': ';

						if ($arFile['FILE_EXT'] != '')
						{
							echo strtoupper($arFile['FILE_EXT']).', ';
						}
						echo $arFile['SIZE'];
						?>
					</a>
				</div>
				<div class="col-auto d-none d-sm-block">
					<a class="c-icon" href="<?=$arFile['FULL_PATH']?>" download>
						<svg class="icon-svg h4 m-0 lh-1"><use xlink:href="#svg-download"></use></svg>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
	unset($arFile);
	?>
</div>
