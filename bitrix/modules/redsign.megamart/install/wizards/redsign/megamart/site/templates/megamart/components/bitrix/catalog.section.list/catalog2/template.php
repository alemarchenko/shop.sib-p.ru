<?php

use \Redsign\MegaMart\MyTemplate;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$this->setFrameMode(true);

$path = null;
switch ($arParams['VIEW_MODE'])
{
    case 'buttons':
        $path = $templateFolder.'/include/buttons.php';
        break;
    case 'blocks':
        $path = $templateFolder.'/include/blocks.php';
        break;
    case 'links':
        $path = $templateFolder.'/include/links.php';
        break;
    case 'lines':
        $path = $templateFolder.'/include/lines.php';
        break;
}

if ($path)
{
    include(MyTemplate::getTemplatePart($path));
}


$pathBuffer = null;
switch ($arParams['VIEW_MODE_BUFFER'])
{
    case 'buttons':
        $pathBuffer = $templateFolder.'/include/buttons.php';
        break;
    case 'blocks':
        $pathBuffer = $templateFolder.'/include/blocks.php';
        break;
    case 'links':
        $pathBuffer = $templateFolder.'/include/links.php';
        break;
    case 'lines':
        $pathBuffer = $templateFolder.'/include/lines.php';
        break;
}

$this->SetViewTarget('site_catalog_section_list_buffer');
if ($pathBuffer)
{
    include(MyTemplate::getTemplatePart($pathBuffer));
}
$this->EndViewTarget();
