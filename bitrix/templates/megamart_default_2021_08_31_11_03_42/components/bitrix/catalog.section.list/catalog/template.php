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
$this->setFrameMode(true);

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

// $arViewModeList = $arResult['VIEW_MODE_LIST'];

// $arViewStyles = array(
	// 'LINE' => array(
		// 'CONT' => 'catalog_sections catalog_sections-line',
		// 'TITLE' => 'catalog_sections__title h3',
		// 'DESCRIPTION' => 'catalog_sections__descr',
		// 'LIST' => 'catalog_sections__list-line row',
		// 'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	// ),
	// 'BANNER' => array(
		// 'CONT' => 'catalog_sections',
		// 'TITLE' => 'catalog_sections__title h3',
		// 'DESCRIPTION' => 'catalog_sections__descr',
		// 'LIST' => 'catalog_sections__list-banners row',
		// 'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	// ),
	// 'TILE' => array(
		// 'CONT' => 'catalog_sections',
		// 'TITLE' => 'catalog_sections__title h3',
		// 'DESCRIPTION' => 'catalog_sections__descr',
		// 'LIST' => 'catalog_sections__list row',
		// 'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	// ),
	// 'THUMB' => array(
		// 'CONT' => 'catalog_sections',
		// 'TITLE' => 'catalog_sections__title h3',
		// 'DESCRIPTION' => 'catalog_sections__descr',
		// 'LIST' => 'catalog_sections__list-banners row',
		// 'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	// ),
// );
// $arCurView = $arViewStyles[$arParams['SECTIONS_VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
$layout->addModifier('outer-spacing-quart');
$layout->addModifier('bg-lg');

$layoutHeader = $layout->getHeader();
if ($layoutHeader) {
	$layoutHeader->addData('TITLE_ID', $this->GetEditAreaId($arResult['SECTION']['ID']));
	if (empty($layoutHeader->getData('TITLE'))) {
		$strSectionName = (
			isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
			? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
			: $arResult['SECTION']['NAME']
		);

		$layoutHeader->addData('TITLE', $strSectionName);
	}
}

$layout->start();

/*
	if (strlen($arResult['SECTION']['DESCRIPTION']) > 0): ?>
		<div class="<?=$arCurView['DESCRIPTION']?> row">
			<div class="col-md-8"><?=$arResult['SECTION']['DESCRIPTION']?></div>
		</div>
	<?php endif;
*/

if (0 < $arResult["SECTIONS_COUNT"])
{
?>
<ul class="row list-unstyled mb-0">
<?
	switch ($arParams['SECTIONS_VIEW_MODE'])
	{
		case 'TILE':
		default:

			$iLvl1SectionCount = 0;

			$sGridClass = '';
			if (Loader::includeModule('redsign.megamart'))
				$sGridClass = ParametersUtils::gridToString($arParams['GRID_RESPONSIVE_SETTINGS']);

			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (0 < $intCurrentDepth) {
						if ($arSection['RELATIVE_DEPTH_LEVEL'] == 2) {
							echo '<ul class="tile__sub list-inline">';
						} else {
							echo '<ul>';
						}
					}
				}
				elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (!$boolFirst)
					{
						if ($arSection['RELATIVE_DEPTH_LEVEL'] == 1) {
							echo '</div></div>'; // .tile .tile__body
						}
						echo '</li>';
					}
				}
				else
				{
					while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
					{
						echo '</li></ul>';
						$intCurrentDepth--;
					}
					if ($arSection['RELATIVE_DEPTH_LEVEL'] == 1)
					{
						echo '</div>'; // .tile__head

						if ($arSectionTop)
						{
							if ($arSectionTop['SECTIONS_COUNT'] > 0)
							{
								?>
								<a href="<?=$arSectionTop['SECTION_PAGE_URL']?>">
									<?=Loc::getMessage('RS_MM_BCSL_CATALOG_SECTIONS_COUNT', array('#NUM#' => $arSectionTop['SECTIONS_COUNT']))?>
								</a>
								<?
							}
						}
						echo '</div></div>'; // .tile .tile__body
					}
					echo '</li>';
				}
				?>
				<?php if ($arSection['RELATIVE_DEPTH_LEVEL'] == 1): ?>

					<?php
					$iLvl1SectionCount++;
					if ($arParams['LVL1_SECTION_COUNT'] > 0 && $arParams['LVL1_SECTION_COUNT'] < $iLvl1SectionCount)
					{
						$intCurrentDepth = 0;
						break;
					}

					if (false === $arSection['PICTURE'])
						$arSection['PICTURE'] = array(
							'SRC' => $this->GetFolder().'/images/tile-empty.png',
							'ALT' => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
								? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
								: $arSection["NAME"]
							),
							'TITLE' => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
								? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
								: $arSection["NAME"]
							)
						);

					$arSectionTop = $arSection;
					?><li class="<?=$sGridClass?> mb-5 d-flex" id="<? echo $this->GetEditAreaId($arSection['ID']);?>">
						<div class="tile flex-fill">
							<?php
							if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y')
							{
								?>
								<a href="<?=$arSection['SECTION_PAGE_URL']?>">
									<div class="tile__pic lazy-anim-bg" data-lazy-img data-src="<? echo $arSection['PICTURE']['SRC']; ?>" title="<?=$arSection['PICTURE']['TITLE']?>"></div>
								</a>
								<?php
							}
							else
							{
								?>
								<a href="<?=$arSection['SECTION_PAGE_URL']?>">
									<div class="tile__pic" style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>')" title="<?=$arSection['PICTURE']['TITLE']?>"></div>
								</a>
								<?php
							}
							?>
							<div class="tile__body">
								<div class="tile__head">
									<?php if ('Y' != $arParams['HIDE_SECTION_NAME']): ?>
										<h6 class="tile__title">
											<a href="<?=$arSection['SECTION_PAGE_URL']?>">
												<?php
												echo $arSection['NAME'];
												if ($arParams["COUNT_ELEMENTS"])
												{
													?> <span>(<?=$arSection['ELEMENT_CNT']?>)</span><?
												}
												?>
											</a>
										</h6>
									<?php endif; ?>

				<?php else: ?>
					<li id="<?=$this->GetEditAreaId($arSection['ID']);?>" class="list-inline-item">
						<a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"];?><?
						if ($arParams["COUNT_ELEMENTS"]) {
							?> <span>(<?=$arSection["ELEMENT_CNT"]?>)</span><?
						}
						?></a>
				<?php endif; ?>

				<?php
				$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
				$boolFirst = false;
			}
			unset($arSection);
			while ($intCurrentDepth > 1)
			{
				echo '</li></ul>';
				$intCurrentDepth--;
			}
			if ($intCurrentDepth > 0)
			{
				echo '</div></div></div>'; // .tile, .tile__body
				echo '</li>';
			}
			break;
	}
?>
</ul>
<?
}

$layout->end();
