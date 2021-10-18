<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

//d-flex align-items-center justify-content-center h4 mb-0 text-light w-100
?>

<?php $this->SetViewTarget('rs_mm_search_popup'); ?>
<div class="search-popup search-popup--<?=RS_MM_SEARCH_POPUP?>">
	<div class="search-popup__inner">
		<a href="#" class="search-popup__close" data-close-search-popup>
			<svg class="icon-svg"><use xlink:href="#svg-close"></use></svg>
		</a>
		<form action="<?echo $arResult["FORM_ACTION"]?>" class="search-popup__form" id="<?echo $CONTAINER_ID?>">
			<div class="form-group bmd-form-group">
				<label for="<?echo $INPUT_ID?>" class="bmd-label-floating"><?=Loc::getMessage('CT_BST_SEARCH_BUTTON');?></label>
				<input id="<?echo $INPUT_ID?>" class="bmd-form-control" type="text" name="q" value="" maxlength="50" autocomplete="off" />
			</div>
			<button name="s" type="submit" class="search-popup__form-button">
				<svg class="icon-svg"><use xlink:href="#svg-search"></use></svg>
			</button>
		</form>
	</div>
	<script>
		BX.ready(function(){
			var searchTitle = new JCTitleSearch({
				'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
				'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
				'INPUT_ID': '<?echo $INPUT_ID?>',
				'MIN_QUERY_LEN': 2
			});
		});
	</script>
</div>
<?php $this->EndViewTarget();
