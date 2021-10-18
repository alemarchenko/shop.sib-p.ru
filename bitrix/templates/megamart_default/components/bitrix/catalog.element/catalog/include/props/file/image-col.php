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
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
			<div class="doc px-5 py-6">

				<?php
				if (CFile::IsImage($arFile['FILE_NAME'], $arFile['CONTENT_TYPE']))
				{
					$resizeImage = CFile::ResizeImageGet(
						$arFile['ID'],
						array('width' => 200, 'height' => 200),
						BX_RESIZE_IMAGE_PROPORTIONAL,
						true
					);
					?>
					<a class="doc__preview fancybox-zoom" href="<?=$arFile['FULL_PATH']?>" data-fancybox="<?=$sPropCode?>" data-caption="<?=($arFile['DESCRIPTION'] == '' ? $arFile['ORIGINAL_NAME'] : $arFile['DESCRIPTION'])?>">
						<?php
						if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y')
						{
							?>
							<img class="img-fluid lazy-anim-img" data-lazy-img data-src="<?=$resizeImage['src']?>" alt="<?=$arFile['DESCRIPTION']?>">
							<?php
						}
						else
						{
							?>
							<img class="img-fluid" src="<?=$resizeImage['src']?>" alt="<?=$arFile['DESCRIPTION']?>">
							<?php
						}
						?>
					</a>
					<?php
					unset($resizeImage);
				}
				else
				{
					?>
					<a class="doc__preview" href="<?=$arFile['FULL_PATH']?>" download>
						<img class="img-fluid" src="<?=$templateFolder?>/images/file.png">
					</a>
					<?
				}
				?>

				<div class="doc__name">
					<?=($arFile['DESCRIPTION'] == '' ? $arFile['ORIGINAL_NAME'] : $arFile['DESCRIPTION'])?>
				</div>
			</div>
		</div>
		<?php
	}
	unset($arFile);
	?>
</div>
