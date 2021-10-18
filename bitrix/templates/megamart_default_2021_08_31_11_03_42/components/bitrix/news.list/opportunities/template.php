<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$arJsItems = array();
foreach($arResult['ITEMS'] as $arItem)
{
    $arJsItems[$arItem['ID']] = array(
        'name' => $arItem['NAME'],
        'description' => $arItem['PREVIEW_TEXT'],
        'activeFrom' => MakeTimeStamp($arItem['ACTIVE_FROM'])
    );
}

if (count($arResult['ITEMS']) > 0):
	$sBlockId = 'j_'.$this->randString(11);

$arParams['MESS_BTN_FILTER_ALL'] = $arParams['MESS_BTN_FILTER_ALL'] ?: Loc::getMessage('RS_NL_FILTER_ALL');
?>
<div id="<?=$sBlockId?>">
	<?php if (isset($arResult['FILTER']['VALUES'])): ?>
	<div class="nav-wrap">
		<ul class="nav nav-slide" id="<?=$sBlockId?>_filter">
			<?php foreach ($arResult['FILTER']['VALUES'] as $arFilter): ?>
				<li class="nav-item">
					<a class="nav-link" href="#" data-filter="<?=$arFilter['XML_ID']?>">
						<span><?=$arFilter['VALUE']?></span>
					</a>
				</li>
			<?php endforeach; ?>

			<li class="nav-item ">
				<a class="nav-link active" href="#" data-filter><span><?=$arParams['MESS_BTN_FILTER_ALL'];?></span></a>
			</li>
		</ul>
	</div>
	<?php endif; ?>

	<div class="accordion mt-5" id="<?=$sBlockId?>_accordion">
		<?php
		foreach ($arResult['ITEMS'] as $index => $arItem):
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			$sItemId = $this->GetEditAreaId($arItem['ID']);
		?>
			<div class="card" id="<?=$sItemId;?>" data-type="<?=$arItem['DISPLAY_PROPERTIES'][$arParams['RS_TYPE_PROPERTY']]['VALUE_XML_ID']?>" data-code="<?=$arItem['CODE']?>" data-item-id="<?=$arItem['ID']?>">
				<div class="card-header">
					<a class="card-header-link " id="<?=$sItemId?>_heading" data-toggle="collapse" data-target="#<?=$sItemId?>_collapse" aria-expanded="true" aria-controls="<?=$sItemId?>_collapse">
						<span class="card-header-link__title"><?=$arItem['NAME']?></span>
						<?php if (isset($arParams['NOTE_PROP']) && !empty($arItem['DISPLAY_PROPERTIES'][$arParams['NOTE_PROP']])):?>
							<span class="card-header-link__desc"><?=$arItem['DISPLAY_PROPERTIES'][$arParams['NOTE_PROP']]['DISPLAY_VALUE']?></span>
						<?php endif; ?>
						<span class="card-header-link__arrow">
							<svg class="icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
						</span>
					</a>
				</div>

				<div id="<?=$sItemId?>_collapse" class="collapse" aria-labelledby="<?=$sItemId?>_heading" data-parent="#<?=$sBlockId?>_accordion">
					<div class="card-body" data-text></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<script>
        BX.message({
            'RS_NL_ITEM_NEW': '<?=Loc::getMessage('RS_NL_ITEM_NEW')?>'
        });

        new RSFilterAccordion('<?=$sBlockId?>', <?=\CUtil::PhpToJSObject($arJsItems)?>);
	</script>
</div>
<?php
endif;
