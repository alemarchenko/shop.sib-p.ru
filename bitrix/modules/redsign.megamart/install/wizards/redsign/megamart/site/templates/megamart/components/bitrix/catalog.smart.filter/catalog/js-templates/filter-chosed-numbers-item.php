<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Localization\Loc;

?>
<script id="filter-chosed-numbers-item-template" type="text/html"><?
?>{{#VALUE_MIN}}<span class="bx-filter-chosed-box__value">{{VALUE_MIN}}</span>{{/VALUE_MIN}}<?
?>{{#VALUE_MAX}}<span class="bx-filter-chosed-box__value">{{VALUE_MAX}}</span>{{/VALUE_MAX}}<?
?><span class="bx-filter-chosed-box__reset js-filter-chosed-box__reset" data-property-id="{{ID}}"><svg class="icon-svg bx-filter-chosed-box__reset-icon"><use xlink:href="#svg-close"></use></svg></span><?
?></script>
