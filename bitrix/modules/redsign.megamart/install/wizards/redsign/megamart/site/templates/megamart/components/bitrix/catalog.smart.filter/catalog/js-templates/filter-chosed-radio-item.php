<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Localization\Loc;

?>
<script id="filter-chosed-radio-item-template" type="text/html"><?
?><span class="bx-filter-chosed-box__value">{{VALUE}}</span><?
?><label <?
    ?>class="bx-filter-chosed-box__reset" <?
    ?>for="{{CONTROL_ID}}" <?
    ?>><svg class="icon-svg bx-filter-chosed-box__reset-icon"><use xlink:href="#svg-close"></use></svg></label><?
?></script>
