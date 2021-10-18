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
$this->setFrameMode(true);?>
<?
$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($arParams["SHOW_INPUT"] !== "N"):?>
	<div class="w-100">
		<a href="#" class="compact-search-button d-md-none" data-compact-search-open>
			<svg class="icon-svg"><use xlink:href="#svg-search"></use></svg>
		</a>
		<div class="compact-search mx-auto" id="<?echo $CONTAINER_ID?>" >
			<form action="<?echo $arResult["FORM_ACTION"]?>" class="compact-search__form">
				<div class="compact-search__close">
					<a href="#" data-compact-search-close>
						<svg class="icon-svg" ><use xlink:href="#svg-nav-arrow-left"></use></svg>
					</a>
				</div>
				<div class="compact-search__input">
					<div class="form-group bmd-form-group">
						<input id="<?echo $INPUT_ID?>" class="bmd-form-control" type="text" name="q" value="" maxlength="50" autocomplete="off" />
					</div>
				</div>
				<div class="compact-search__button">
					<button name="s" type="submit">
						<svg class="icon-svg"><use xlink:href="#svg-search"></use></svg>
					</button>
				</div>
			</form>
		</div>
	</div>
<?endif?>
<script>
	BX.ready(function(){
		var titleSearchObj = new CompactTitleSearch({
			'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
			'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
			'INPUT_ID': '<?echo $INPUT_ID?>',
			'MIN_QUERY_LEN': 2
		});
	});
</script>
