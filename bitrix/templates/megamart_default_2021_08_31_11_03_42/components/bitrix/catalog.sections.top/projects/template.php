<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$this->addExternalCss(SITE_TEMPLATE_PATH.'/components/bitrix/news.list/projects/style.css');

$layoutHeader = new \Redsign\Megamart\Layouts\Parts\SectionHeaderBase();

$layout = new \Redsign\MegaMart\Layouts\Section();
$layout
	->useHeader($layoutHeader)
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing');

foreach ($arResult['SECTIONS'] as $arSection):
	$layoutHeader->addData('TITLE', $arSection['NAME']);
	$layoutHeader->addData('TITLE_LINK', $arSection['SECTION_PAGE_URL']);

	$layout->start();
?>
	<div class="l-project-list">
		<?php if ($arParams['SHOW_DESCRIPTION'] == 'Y' && !empty(trim($arSection['DESCRIPTION']))): ?>
		<div class="block-spacing"><?=$arSection['DESCRIPTION']?></div>
		<?php endif; ?>

		<?php
		$sTemplatePath = $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/templates/type1.php';
		if (file_exists($sTemplatePath)) {
			include $sTemplatePath;
		}
		?>
	</div>
<?php
	$layout->end();
endforeach;
