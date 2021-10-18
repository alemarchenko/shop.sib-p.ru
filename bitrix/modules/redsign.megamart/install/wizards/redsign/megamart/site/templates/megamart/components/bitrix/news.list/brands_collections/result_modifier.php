<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) 
{
    die();
}


if (empty($arParams['PROP_BRAND']))
{
    $arParams['PROP_BRAND'] = 'BRAND_REF';
}