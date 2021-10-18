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
$this->setFrameMode(true);

if (empty($arResult["BRAND_BLOCKS"]))
	return;
$strRand = $this->randString();
$strObName = 'obIblockBrand_'.$strRand;
$blockID = 'bx_IblockBrand_'.$strRand;
$mouseEvents = 'onmouseover="'.$strObName.'.itemOver(this);" onmouseout="'.$strObName.'.itemOut(this)"';


if ($arParams['SINGLE_COMPONENT'] == "Y")
	echo '<div class="bx_item_detail_inc_two_'.count($arResult['BRAND_BLOCKS']).' general row" id="'.$blockID.'">';
else
	echo '<div class="bx_item_detail_inc_two" id="'.$blockID.'">';

$handlerIDS = array();

foreach ($arResult["BRAND_BLOCKS"] as $blockId => $arBB)
{
	$brandID = 'brand_'.$arResult['ID'].'_'.$blockId.'_'.$strRand;
	// $popupID = $brandID.'_popup';

	$popupContext = '';
	$shortDescr = '';
	$useLink = $arBB['LINK'] !== false;
	$usePopup = $arBB['FULL_DESCRIPTION'] !== false;
	// if ($usePopup)
	// {
	// 	if (preg_match('/<a[^>]+>[^<]+<\/a>/', $arBB['FULL_DESCRIPTION']) == 1)
	// 		$useLink = false;
	// 	$popupContext = '<span class="bx_popup brandblock-popup" id="'.$popupID.'">'.
	// 		'<span class="arrow"></span>'.
	// 		'<div class="text">'.$arBB['FULL_DESCRIPTION'].'</div>'.
	// 		'</span>';
	// }

	$tagAttrs = 'id="'.$brandID.'_vidget" tabindex="0" data-toggle="tooltip" title="'.htmlspecialcharsbx($arBB['FULL_DESCRIPTION']).'" class="d-block brandblock-block position-relative"';
	
	if ($arBB['DESCRIPTION'] !== false)
		$shortDescr = '<span class="brandblock-text small">'.htmlspecialcharsbx($arBB['NAME']).'</span>';
	
	if (!empty($arBB['PICT']))
	{
		$shortDescr = '<span class="d-block mb-2"><img class="" src="'.$arBB['PICT']['SRC'].'" alt="'.$arBB['NAME'].'"></span>'.$shortDescr;
	}

	// if ($usePopup)
	// 	$tagAttrs .= ' data-popup="'.$popupID.'"';

	?><div id="<?=$brandID;?>" class="brandblock-container text-center px-5 d-inline-block mb-4"<?// echo ($usePopup ? ' data-popup="'.$popupID.'"' : ''); ?>>
		<?
		if ($useLink)
		{
			?><a href="<?=htmlspecialcharsbx($arBB['LINK']); ?>" <?=$tagAttrs; ?> target="_blank"><?=$popupContext.$shortDescr; ?></a><?
		}
		else
		{
			?><span <?=$tagAttrs; ?>><?=$popupContext.$shortDescr; ?></span><?
		}
		?>
	</div><?

	if ($usePopup)
		$handlerIDS[] = $brandID;
}
?>
	</div>
<?
if (!empty($handlerIDS))
{
	$jsParams = array(
		'blockID' => $blockID
	);
?>
	<script type="text/javascript">
		var <? echo $strObName; ?> = new JCIblockBrands(<? echo CUtil::PhpToJSObject($jsParams); ?>);
	</script>

	<script type="text/javascript">
		$('[data-toggle="tooltip"]').tooltip();
		//$('[data-toggle="tooltip"]').tooltip({container: '.bx_item_detail_inc_two'})
	</script> 
<?
}