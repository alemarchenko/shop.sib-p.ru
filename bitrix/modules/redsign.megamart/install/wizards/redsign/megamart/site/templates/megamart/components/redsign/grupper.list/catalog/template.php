<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

$arStickySidebarOptions = array();

$mainId = $this->GetEditAreaId('props');
$itemIds = array(
	'ID' => $mainId,
	'NAV' => $mainId.'_nav',
);

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
?>
<div class="container-fluid">
	<div class="row">
		<?php
		if (is_array($arResult['GROUPED_ITEMS']) && count($arResult['GROUPED_ITEMS']) > 0)
		{
			?>
			<div class="col-lg-4 d-none d-lg-block">
				<div class="position-sticky sticky-compact"<?/*data-sticky-sidebar='<?=\Bitrix\Main\Web\Json::encode($arStickySidebarOptions)?>'*/?>>
					<nav class="nav nav-scrollspy flex-column border-right text-right pr-lg-2" role="tablist" id="<?=$itemIds['NAV']?>">
						<?php
						foreach($arResult['GROUPED_ITEMS'] as $arrValue)
						{
							if (is_array($arrValue['PROPERTIES']) && count($arrValue['PROPERTIES']) > 0)
							{
								?>
								<div class="nav-item">
									<a class="nav-link js-link-scroll h6 mb-0" href="#<?=$this->GetEditAreaId($arrValue['GROUP']['CODE'])?>">
										<span><?=$arrValue['GROUP']['NAME']?></span>
										<svg class="nav-link-icon icon-svg"><use xlink:href="#svg-arrow-right"></use></svg>
									</a>
								</div>
								<?php
							}
						}

						if (is_array($arResult['NOT_GROUPED_ITEMS']) && count($arResult['NOT_GROUPED_ITEMS']) > 0)
						{
							?>
							<div class="nav-item">
								<a class="nav-link js-link-scroll h6 mb-0" href="#<?=$this->GetEditAreaId('OTHER_PROPS')?>">
									<span><?=Loc::getMessage('RS_MM_RGL_CATALOG_OTHER_PROPS')?></span>
									<svg class="nav-link-icon icon-svg"><use xlink:href="#svg-arrow-right"></use></svg>
								</a>
							</div>
							<?php
						}

						if ($arParams['SHOW_OFFERS_PROPS'] == 'Y')
						{
							?>
							<div class="nav-item">
								<a class="nav-link js-link-scroll h6 mb-0" href="#<?=$this->GetEditAreaId('OFFER_PROPS')?>">
									<span><?=Loc::getMessage('RS_MM_RGL_CATALOG_OFFER_PROPS')?></span>
									<svg class="nav-link-icon icon-svg"><use xlink:href="#svg-arrow-right"></use></svg>
								</a>
							</div>
							<?php
						}
						?>
					</nav>
				</div>
			</div>
			<?php
		}
		?>
		<div class="col pl-lg-6">
			<div class="row">
				<?php
				if (is_array($arResult['GROUPED_ITEMS']) && count($arResult['GROUPED_ITEMS']) > 0)
				{

					foreach($arResult['GROUPED_ITEMS'] as $arrValue)
					{
						if (is_array($arrValue['PROPERTIES']) && count($arrValue['PROPERTIES']) > 0)
						{
							?>
							<div class="col-12 col-md-6 col-lg-12">
								<div class="mb-4 mb-md-5" id="<?=$this->GetEditAreaId($arrValue['GROUP']['CODE'])?>">
									<h5 class="pt-2 pb-3 mb-3"><?=$arrValue['GROUP']['NAME']?></h5>
									<div class="product-detail-properties">
										<dl class="product-cat-properties">
											<?
											foreach ($arrValue['PROPERTIES'] as $property)
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
								</div>
							</div>
							<?php
						}
					}
				}

				if (is_array($arResult['NOT_GROUPED_ITEMS']) && count($arResult['NOT_GROUPED_ITEMS']) > 0)
				{
					?>
					<div class="col-12 col-md-6 col-lg-12">
						<div class="mb-4 mb-md-5" id="<?=$this->GetEditAreaId('OTHER_PROPS')?>">
							<?php
							if (is_array($arResult['GROUPED_ITEMS']) && count($arResult['GROUPED_ITEMS']) > 0)
							{
								?>
								<h5 class="pt-2 pb-3 mb-3"><?=Loc::getMessage('RS_MM_RGL_CATALOG_OTHER_PROPS')?></h5>
								<?php
							}
							?>
							<div class="product-detail-properties">
								<dl class="product-cat-properties font-size-sm">
									<?
									foreach ($arResult['NOT_GROUPED_ITEMS'] as $property)
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
						</div>
					</div>
					<?php
				}

				if ($arParams['SHOW_OFFERS_PROPS'] == 'Y')
				{
					?>
					<div class="col-12 col-md-6 col-lg-12">
						<div class="mb-4 mb-md-5" id="<?=$this->GetEditAreaId('OFFER_PROPS')?>">
							<h5 class="pt-2 pb-3 mb-3"><?=Loc::getMessage('RS_MM_RGL_CATALOG_OFFER_PROPS')?></h5>
							<div class="product-detail-properties">
								<dl class="product-cat-properties font-size-sm" id="<?=$arParams['OFFERS_PROP_DIV']?>"></dl>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>

	<?php
	$jsParams = array(
		'VISUAL' => $itemIds,
	);
	?>
	<script>
		var <?=$obName?> = new JCGrupperList(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
	</script>

</div>

