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
				<?php
				if (is_array($arResult['GROUPED_ITEMS']) && count($arResult['GROUPED_ITEMS']) > 0)
				{

					foreach($arResult['GROUPED_ITEMS'] as $arrValue)
					{
						if (is_array($arrValue['PROPERTIES']) && count($arrValue['PROPERTIES']) > 0)
						{
							?>
							<div>
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
					<div>
						<div class="mb-4 mb-md-5" id="<?=$this->GetEditAreaId('OTHER_PROPS')?>">
							<?php
							if (is_array($arResult['GROUPED_ITEMS']) && count($arResult['GROUPED_ITEMS']) > 0)
							{
								?>
								<h5 class="pt-2 pb-3 mb-3"><?=Loc::getMessage('RS_SLINE.RGL_AL.OTHER_PROPS')?></h5>
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
				?>
</div>

