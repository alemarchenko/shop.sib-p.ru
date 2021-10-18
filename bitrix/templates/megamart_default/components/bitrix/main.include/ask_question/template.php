<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

$arResult['TEMPLATE'] = !isset($arParams['TEMPLATE']) ? $arParams['TEMPLATE'] : 'line';

if(isset($arParams['URL_PARAMS'])) {
    $arParams['BTN_LINK'] = str_replace(array_keys($arParams['URL_PARAMS']), array_values($arParams['URL_PARAMS']), $arParams['BTN_LINK']);
}

$sBlockId = $this->randString(10);


$layout = new \Redsign\MegaMart\Layouts\Section();
$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing');

$layout->start();
?>
<div class="b-ask-question b-ask-question--<?=$arResult['TEMPLATE']?>" id="<?=$sBlockId?>">
	<div class="b-ask-question__container">
		<div class="b-ask-question__mark"><svg class="icon-svg"><use xlink:href="#svg-question"></use></svg></div>
			<div class="b-ask-question__content">
			<?php
			if($arResult["FILE"] <> '') {
				include($arResult["FILE"]);
			}
			?>
		</div>
		<div class="b-ask-question__btns">
			<a href="<?=$arParams['BTN_LINK']?>" data-type="ajax" class="btn btn-primary" title="<?=$arParams['BTN_TEXT']?>"><?=$arParams['BTN_TEXT']?></a>
		</div>
	</div>
</div>
<?php
$layout->end();
