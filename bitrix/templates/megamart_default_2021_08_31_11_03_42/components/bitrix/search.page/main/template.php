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

	$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
	$layoutHeader->addData('TITLE', Loc::getMessage('SEARCH_TITLE'));

	$layout = new \Redsign\MegaMart\Layouts\Section();
	$layout
		->addModifier('bg-white')
		->addModifier('shadow')
		->addModifier('outer-spacing')
		->useHeader($layoutHeader);

	$layout->start();
	?>
	<div class="block-spacing">
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



	<?php if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false || $arParams['ONLY_FORM'] == 'Y'): ?>

	<?php
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

	elseif (isset($arResult['SEARCH_EXT'])):

		$layoutHeader = new \Redsign\MegaMart\Layouts\Parts\SectionHeaderBase();
		$layout = new \Redsign\MegaMart\Layouts\Section();
		$layout
			->addModifier('bg-white')
			->addModifier('shadow')
			->addModifier('outer-spacing')
			->useHeader($layoutHeader);
	?>

		<?php if($arParams["DISPLAY_TOP_PAGER"] != "N"):?>
		<div class="b-search-page-pager mb-5"><?=$arResult["NAV_STRING"]?></div>
		<?php endif; ?>

		<?php foreach ($arResult['SEARCH_EXT']['IBLOCKS'] as $arIblock): ?>
		<div class="b-search-page-block">
			<?php if ($arIblock['ID'] == $arParams['CATALOG_IBLOCK_ID']): ?>
				<?php include 'catalog_items.php'; ?>
			<?php
			elseif(isset($arIblock['ITEMS'])):
				$layoutHeader->addData('TITLE', $arIblock['NAME']);
				$layout->start();
			?>
				<?php foreach ($arIblock['ITEMS'] as $arItem): ?>
				<div class="b-search-page-item">
					<a href="<?=$arItem["URL"]?>" class="b-search-page-item__title"><?=$arItem["TITLE_FORMATED"]?></a>
					<div class="b-search-page-item__body"><?=$arItem['BODY_FORMATED']?></div>
					<div class="b-search-page-item__updated">
						<small><?=Loc::getMessage("SEARCH_MODIFIED")?> <?=$arItem["DATE_CHANGE"]?></small>
					</div>
					<?php if ($arItem["CHAIN_PATH"]): ?>
						<div class="b-search-page-item__chain"><small><?=Loc::getMessage("SEARCH_PATH")?>&nbsp;<?=$arItem["CHAIN_PATH"]?></small></div>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			<?php
				$layout->end();
			endif;
			?>
		</div>
		<?php endforeach; ?>

		<?php
		if (count($arResult['SEARCH_EXT']['OTHER']['ITEMS']) > 0):
			$layoutHeader->addData('TITLE', Loc::getMessage('SEARCH_OTHER'));
			$layout->start();
		?>
			<?php foreach ($arResult['SEARCH_EXT']['OTHER']['ITEMS'] as $arItem): ?>
				<div class="b-search-page-item">
					<a href="<?=$arItem["URL"]?>" class="b-search-page-item__title"><?=$arItem["TITLE_FORMATED"]?></a>
					<div class="b-search-page-item__body"><?=$arItem['BODY_FORMATED']?></div>
					<div class="b-search-page-item__updated">
						<small><?=Loc::getMessage("SEARCH_MODIFIED")?> <?=$arItem["DATE_CHANGE"]?></small>
					</div>
					<?php if ($arItem["CHAIN_PATH"]): ?>
						<div class="b-search-page-item__chain"><small><?=Loc::getMessage("SEARCH_PATH")?>&nbsp;<?=$arItem["CHAIN_PATH"]?></small></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		<?php
			$layout->end();
		endif;
		?>

		<div class="d-flex  flex-column align-items-end mb-7">
			<?php if($arParams["DISPLAY_BOTTOM_PAGER"] != "N"):?>
			<div class="b-search-page-pager"><?=$arResult["NAV_STRING"]?></div>
			<?php endif; ?>

			<div class="d-block">
				<?php if($arResult["REQUEST"]["HOW"]=="d"):?>
					<a href="<?=$arResult["URL"]?>&amp;how=r<?=$arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?echo $arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=Loc::getMessage("SEARCH_SORT_BY_RANK")?></a>&nbsp;|&nbsp;<b><?=Loc::getMessage("SEARCH_SORTED_BY_DATE")?></b>
				<?php else:?>
					<b><?=Loc::getMessage("SEARCH_SORTED_BY_RANK")?></b>&nbsp;|&nbsp;<a href="<?=$arResult["URL"]?>&amp;how=d<?echo $arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?=$arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=Loc::getMessage("SEARCH_SORT_BY_DATE")?></a>
				<?php endif;?>
			</div>
		</div>

	<?php else: ?>
	<div class="l-search-page__error"><?=Loc::getMessage('SEARCH_NOTHING_TO_FOUND');?></div>
	<?php endif; ?>
</div>
