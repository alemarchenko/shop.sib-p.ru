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
<div class=" ya-share2"
	<?php if ($arParams['SOCIAL_COUNTER'] == 'Y'): ?>
		data-counter
	<?php endif; ?>
	<?php if ($arParams['SOCIAL_COPY'] != 'last'): ?>
		data-copy="<?=$arParams['SOCIAL_COPY']?>"
	<?php endif; ?>
	<?php if (intval($arParams['SOCIAL_LIMIT']) > 0): ?>
		data-limit="<?=$arParams['SOCIAL_LIMIT']?>"
	<?php endif; ?>
	<?php if (is_array($arParams['SOCIAL_SERVICES'])): ?>
		data-services="<?=implode(',', $arParams['SOCIAL_SERVICES']);?>"
	<?php endif; ?>
	<?php if (intval($arParams['SOCIAL_SIZE']) > 0): ?>
		data-size="<?=$arParams['SOCIAL_SIZE']?>"
	<?php endif; ?>
	data-lang="<?=LANGUAGE_ID?>"
<?/*?> data-bare=""*/?>></div>
<small class="text-extra"><?=Loc::getMessage('RS_MM_BCE_PREVIEW_SHARE')?></small>