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
use \Bitrix\Main\ModuleManager;

if ($arParams['SHOW_SIZE_TABLE'] == 'Y' && !empty($arResult['SIZE_TABLE']))
{
    ?>
    <div id="<?=$itemIds['SIZE_TABLE']?>" style="display:none"><?=$arResult['SIZE_TABLE']['PREVIEW_TEXT']?></div>
    <?php
}