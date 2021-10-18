<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'menu_vml',
	),
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

if ($arResult["SECTIONS_COUNT"] > 0)
{
	$layout = new \Redsign\MegaMart\Layouts\Section();
	$layout
		->addModifier('white')
		->addModifier('shadow')
		->addModifier('outer-spacing');

	$layout->start();
?>
<ul class="b-sidebar-nav">
<?
$intCurrentDepth = 1;
$boolFirst = true;
foreach ($arResult['SECTIONS'] as $index => &$arSection)
{
    $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
    $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

    if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
    {
        if (0 < $intCurrentDepth)
            echo '<ul class="b-sidebar-nav__submenu collapse" id="sidebar_menu_'.($index - 1).'_pc" aria-expanded="false">';
    }
    elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
    {
        if (!$boolFirst)
            echo '</li>';
    }
    else
    {
        while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
        {
            echo '</li></ul>';
            $intCurrentDepth--;
        }
        echo '</li>';
    }

    ?>
    <li class="dropdown-submenu b-sidebar-nav__item js-smenu-item" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
        <a class="b-sidebar-nav__link" href="<?=$arSection["SECTION_PAGE_URL"]?>">
			<?php
			echo $arSection["NAME"];
			if ($arParams["COUNT_ELEMENTS"])
			{
				echo ' ('.$arSection["ELEMENT_CNT"].')';
			}
			?>
            <?php if ($arSection['HAVE_SUBSECTIONS']): ?>
				<span class="b-sidebar-nav__toggle js-smenu-item__toggle collapsed" data-toggle="collapse" aria-haspopup="true" aria-expanded="true" data-target="#sidebar_menu_<?=$index?>_pc">
					<svg class="icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
				</span>
            <?php endif; ?>
        </a>
        <?

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
    echo '</li>';
}
?>
</ul>
<?
	$layout->end();
}