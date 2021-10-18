<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__DIR__.'/template.php');

global $arArticleTags;

$arArticleTags = $arResult['TAGS_ARRAY'];

if (count($arResult['DISPLAY_FILE_PROPS']) > 0) {

	foreach ($arResult['DISPLAY_FILE_PROPS'] as $arProperty) {
		ob_start();

		if ($arProperty['VIEW'] == 'photogallery'):
		?>
			<?php
			$layout = new \Redsign\MegaMart\Layouts\Section();

			$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
			$layoutHeader->addData('TITLE', $arProperty['NAME']);

			$layout
				->useHeader($layoutHeader)
				->addModifier('bg-white')
				->addModifier('shadow')
				->addModifier('outer-spacing')
				->addModifier('inner-spacing');

			$layout->start();
			?>
			<div class="row text-center text-lg-left">
				<?php
				foreach ($arProperty['FILE_VALUE'] as $arFile):
					$sFileName = ($arFile['DESCRIPTION'] == '' ? $arFile['ORIGINAL_NAME'] : $arFile['DESCRIPTION']);
				?>
				<div class="col-lg-3 col-md-4 col-xs-6">
					<a href="<?=$arFile['SRC']?>" class="d-block mb-4 h-100" data-fancybox="images">
						<img class="img-fluid " src="<?=$arFile['SRC']?>" alt="">
					</a>
				</div>
				<?php endforeach; ?>
			</div>
			<?php $layout->end(); ?>

		<?php elseif ($arProperty['VIEW'] == 'line'): ?>
			<?php
			$layout = new \Redsign\MegaMart\Layouts\Section();

			$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
			$layoutHeader->addData('TITLE', $arProperty['NAME']);

			$layout
				->useHeader($layoutHeader)
				->addModifier('bg-white')
				->addModifier('shadow')
				->addModifier('outer-spacing');

			$layout->start();
			?>
			<div class="row row-borders">
				<?php foreach ($arProperty['FILE_VALUE'] as $arFile): ?>
					<div class="col-12">
						<div class="doc row align-items-center px-4 px-sm-5 py-6">
							<div class="col-3 col-sm-3 text-center">
								<div class="doc__icon icon-<?=$arFile['FILE_ICON']?>">
									<svg class="icon-svg"><use xlink:href="#svg-folder"></use></svg>
									<div class="doc__type"><?=$arFile['FILE_EXT']?></div>
								</div>
							</div>
							<div class="col">
								<div class="doc__name">
									<?=($arFile['DESCRIPTION'] == '' ? $arFile['ORIGINAL_NAME'] : $arFile['DESCRIPTION'])?>
								</div>
								<a class="btn-link font-size-sm" href="<?=$arFile['SRC']?>" target="_blank" download="<?=$arFile['ORIGINAL_NAME']?>">
									<?php
									echo Loc::getMessage('RS_ND_ARTICLE_DOWNLOAD_FILE').': ';

									if ($arFile['FILE_EXT'] != '')
									{
										echo strtoupper($arFile['FILE_EXT']).', ';
									}
									echo $arFile['FILE_SIZE'];
									?>
								</a>
							</div>
							<div class="col-auto">
								<a class="c-icon" href="<?=$arFile['SRC']?>" download="<?=$arFile['ORIGINAL_NAME']?>">
									<svg class="icon-svg h4 m-0 lh-1"><use xlink:href="#svg-download"></use></svg>
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php $layout->end(); ?>
		<?php
		endif;
		$APPLICATION->AddViewContent('rs_mm_prop_files', ob_get_clean());
	}
}

global $arBindItemsData;
$arBindItemsData = [];
if (count($arResult['DISPLAY_BIND_PROPS']) > 0) {
	foreach ($arResult['DISPLAY_BIND_PROPS'] as $arProperty) {
		$sIncludeAreaPath = SITE_DIR.'include/templates/news/catalog_items.php';

		if (
			isset($arParams['RS_BIND_PROP_'.$arProperty['CODE'].'_INCLUDE_FILE']) &&
			file_exists($_SERVER['DOCUMENT_ROOT'].$arParams['RS_BIND_PROP_'.$arProperty['CODE'].'_INCLUDE_FILE'])
		) {
			$sIncludeAreaPath = $arParams['RS_BIND_PROP_'.$arProperty['CODE'].'_INCLUDE_FILE'];
		}

		$arBindItemsData[] = [
			'INCLUDE_FILE' => $sIncludeAreaPath,
			'IBLOCK_ID' => $arProperty['LINK_IBLOCK_ID'],
			'BLOCK_NAME' => $arProperty['NAME'],
			'FILTER' => $arProperty['VALUE']
		];
	}
}

if (isset($templateData['ITEM']['PREVIEW_TEXT'])) {
	$APPLICATION->SetPageProperty("og:description", TruncateText(HTMLToTxt($templateData['ITEM']['PREVIEW_TEXT']), 300));
}
if (isset($templateData['ITEM']['DETAIL_PICTURE'])) {
	$APPLICATION->SetPageProperty("og:image", CHTTP::URN2URI($templateData['ITEM']['DETAIL_PICTURE']['SRC']));
}
