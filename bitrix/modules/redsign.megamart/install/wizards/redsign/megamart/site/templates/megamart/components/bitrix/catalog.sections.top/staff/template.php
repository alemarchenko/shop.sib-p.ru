<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<?php foreach ($arResult['SECTIONS'] as $arSection):
	$sBlockId = $arSection['CODE'].'_'.$this->randString();

	$layout = new \Redsign\MegaMart\Layouts\Section();

	$layoutHeader = new \Redsign\Megamart\Layouts\Parts\SectionHeaderBase();
	$layoutHeader->addData('TITLE', $arSection['NAME']);
	$layoutHeader->addData('TITLE_LINK', $arSection['SECTION_PAGE_URL']);

	$layout
		->useHeader($layoutHeader)
		->addModifier('bg-white')
		->addModifier('shadow')
		->addModifier('outer-spacing');

	$layout->start();
	?>
	<div class="l-staff" id="<?=$this->GetEditAreaID($sBlockId)?>">
		<?php if ($arParams['SHOW_DESCRIPTION'] == 'Y' && !empty(trim($arSection['DESCRIPTION']))): ?>
		<div class="block-spacing"><?=$arSection['DESCRIPTION']?></div>
		<?php endif; ?>

		<?php
		if (file_exists($arResult['TEMPLATE_PATH'])) {
			include($arResult['TEMPLATE_PATH']);
		}
		?>
	</div>
	<?php $layout->end(); ?>

<?php endforeach; ?>
