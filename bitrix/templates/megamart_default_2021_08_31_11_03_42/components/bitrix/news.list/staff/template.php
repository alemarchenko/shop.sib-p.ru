<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

Loc::loadMessages(__FILE__);

if (count($arResult['ITEMS'])):

$sBlockId = $this->randString(10);


$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing');

$layout->start();

?>
<div class="l-staff" id="staff_<?=$sBlockId?>">
	<?php if ($arParams['SHOW_DESCRIPTION'] == 'Y'): ?>
		<?php if (intval($arParams['PARENT_SECTION']) > 0 && strlen($arResult['PARENT_SECTION']['DESCRIPTION']) > 0): ?>
			<div class="block-spacing"><?=$arResult['PARENT_SECTION']['DESCRIPTION']?></div>
		<?php elseif (count($arResult['DESCRIPTION']) > 0): ?>
			<div class="block-spacing"><?=$arResult['DESCRIPTION']?></div>
		<?php endif; ?>
	<?php endif; ?>
	<?php
	if (file_exists($arResult['TEMPLATE_PATH'])) {
		include($arResult['TEMPLATE_PATH']);
	}
	?>
</div>
<?php
$layout->end();
endif;
