<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

global $APPLICATION;
$APPLICATION->SetPageProperty("hide_section", "Y");
?>
<div class="l-search-page">
	<?php

	$layout = new \Redsign\MegaMart\Layouts\Section();
	$layout
		->addModifier('bg-white');

	$layout->start();
	?>
	<div class="block-spacing border-bottom border-body-bg">
		<form class="form-inline" action="" method="get">
			<input class="form-control  mb-2 mr-sm-2" type="text" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>" size="40">

			<?php if($arParams["SHOW_WHERE"]): ?>
				<select class="form-control  mb-2 mr-sm-2" name="where">
					<option value=""><?=Loc::getMessage("SEARCH_ALL")?></option>
					<?php foreach($arResult["DROPDOWN"] as $key=>$value): ?>
						<option value="<?=$key?>"<?php if($arResult["REQUEST"]["WHERE"]==$key) echo " selected"?>><?=$value?></option>
					<?php endforeach; ?>
				</select>
			<?php endif; ?>

			<input type="submit" class="btn btn-primary mb-2" value="<?=Loc::getMessage("SEARCH_GO")?>">
		</form>
	</div>

	<?php if(isset($arResult["REQUEST"]["ORIGINAL_QUERY"])): ?>
		<div class="mt-6"><?=Loc::getMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>'))?></div>
	<?php endif; ?>
	<?php
	$layout->end();
	unset($layout);
	?>

	<?php
	if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false || $arParams['ONLY_FORM'] == 'Y'):
		// TODO
	elseif ($arResult["ERROR_CODE"] != 0):
		$layout = new \Redsign\MegaMart\Layouts\Section();
		$layout
			->addModifier('bg-white')
			->addModifier('shadow')
			->addModifier('inner-spacing')
			->addModifier('outer-spacing');

		$layout->start();
	?>
		<div class="l-search-page__error">
			<b><?=Loc::getMessage('SEARCH_ERROR');?></b>
			<p><?=$arResult["ERROR_TEXT"]?></p>
			<p><?=Loc::getMessage('SEARCH_CORRECT_AND_CONTINUE');?></p>
			<p><?=Loc::getMessage("SEARCH_SINTAX")?><br /><b><?=Loc::getMessage("SEARCH_LOGIC")?></b></p>
			<table class="table table-stripped">
					<tr>
			  			<td><?=Loc::getMessage("SEARCH_OPERATOR")?></td><td><?=Loc::getMessage("SEARCH_SYNONIM")?></td>
			  			<td><?=Loc::getMessage("SEARCH_DESCRIPTION")?></td>
					</tr>
		  		<tr>
						<td ><?=Loc::getMessage("SEARCH_AND")?></td><td>and, &amp;, +</td>
						<td><?=Loc::getMessage("SEARCH_AND_ALT")?></td>
		  		</tr>
		  		<tr>
						<td><?=Loc::getMessage("SEARCH_OR")?></td><td>or, |</td>
						<td><?=Loc::getMessage("SEARCH_OR_ALT")?></td>
		  		</tr>
		  		<tr>
						<td><?=Loc::getMessage("SEARCH_NOT")?></td><td>not, ~</td>
						<td><?=Loc::getMessage("SEARCH_NOT_ALT")?></td>
		  		</tr>
		  		<tr>
						<td>( )</td>
						<td>&nbsp;</td>
						<td><?=Loc::getMessage("SEARCH_BRACKETS_ALT")?></td>
		  		</tr>
		  	</table>
		</div>
	<?php
		$layout->end();

	endif; ?>
</div>
