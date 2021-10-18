<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

$this->setFrameMode(true);

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{

	$layout = new \Redsign\MegaMart\Layouts\Section();
	$layout
		->addModifier('container')
		->addModifier('shadow')
		->addModifier('bg-white')
		->addModifier('outer-spacing');

	$layout->start();
	?>
		<div class="py-5">
			<div class="row">
				<?php
				$sGridClass = '';
				if (Loader::includeModule('redsign.megamart'))
					$sGridClass = ParametersUtils::gridToString($arParams['GRID_RESPONSIVE_SETTINGS']);

				foreach ($arResult["ITEMS"] as $arItem)
				{
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<div class="<?=$sGridClass?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<?php
						if (!empty($arItem['PROPERTIES'][$arParams['LINK_PROP']]['VALUE']))
						{
							?>
							<a class="feature-2" href="<?=$arItem['PROPERTIES'][$arParams['LINK_PROP']]['VALUE']?>"<?php if ($arItem['PROPERTIES'][$arParams['TARGET_PROP']]['VALUE'] != ''): ?> target="_blank"<?php endif; ?>>
							<?php
						}
						else
						{
							?>
							<div class="feature-2">
							<?php
						}
						?>
							<div class="row no-gutters align-items-center my-3">
								<div class="col-3 col-sm-4">

									<?php
									$sIconPath = SITE_TEMPLATE_PATH.'/assets/images/icons/bodymovin/'.$arItem['PROPERTIES'][$arParams['ICON_BODYMOVIN_PROP'][$arItem['IBLOCK_ID']]]['VALUE'].'.json';

									if (
										$arItem['PROPERTIES'][$arParams['ICON_BODYMOVIN_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] != ''
										&& file_exists(Application::getDocumentRoot().$sIconPath))
									{
										?>
										<span class="feature-2__icon" id="<?=$this->GetEditAreaId($arItem['ID']);?>__icon"></span>
										<script>
											var anim,
												params = {
												container: document.getElementById('<?=$this->GetEditAreaId($arItem['ID']);?>__icon'),
												renderer: 'svg',
												loop: false,
												autoplay: true,
												autoloadSegments: true,
												rendererSettings: {
													progressiveLoad:false
												},
												path: '<?=$sIconPath?>'
											};
											anim = bodymovin.loadAnimation(params);
										</script>
										<?php
									}
									elseif ($arItem['PROPERTIES'][$arParams['ICON_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] != '')
									{
										?>
										<span class="feature-2__icon">
											<svg class="icon-svg"><use xlink:href="#svg-<?=$arItem['PROPERTIES'][$arParams['ICON_PROP'][$arItem['IBLOCK_ID']]]['VALUE']?>"></use></svg>
										</span>
										<?php
									}
									elseif (is_array($arItem['PREVIEW_PICTURE']))
									{
										?>
										<span class="float-right">
											<img class="" src="<?=$arItem['PREVIEW_PICTURE']['RESIZE']['src']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
										</span>
										<?php
									}
									?>
								</div>
								<div class="col-9 col-sm-8">
									<div class="pl-4 pr-3 font-weight-bold"><?=$arItem['NAME']?></div>
								</div>
							</div>
							<?php
							if (!empty($arItem['PROPERTIES'][$arParams['LINK_PROP']]['VALUE']))
							{
								?>
								</a>
								<?php
							}
							else
							{
								?>
								</div>
								<?php
							}
							?>
					</div>
					<?php
				}

				if ($arParams["DISPLAY_BOTTOM_PAGER"])
				{
					echo $arResult["NAV_STRING"];
				}
				?>

			</div>
		</div>
	<?php
	$layout->end();
}
