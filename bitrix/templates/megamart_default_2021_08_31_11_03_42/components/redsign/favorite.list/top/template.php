<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$mainId = $this->GetEditAreaId('favorite');
$compactMainId = $mainId.'__compact';
?>
<script>
BX.message({
"RS_FAVORITE_ADD":"<?=getMessageJs('RS_MM_RFL_TOP_FAVORITE_ADD')?>",
"RS_FAVORITE_DEL":"<?=getMessageJs('RS_MM_RFL_TOP_FAVORITE_DEL')?>"
});
</script>
<div class="favorite-top" id="<?=$mainId?>">

    <?php
    $frame = $this->createFrame($mainId, false)->begin();
	// $frame->setBrowserStorage(true);
        $arIDs = array();
        if (is_array($arResult['ITEMS']) && 0 < count($arResult['ITEMS']))
		{
            foreach ($arResult['ITEMS'] as $arItem)
			{
                $arIDs[] = $arItem['ELEMENT_ID'];
            }
        }
    ?>
		<a href="<?=$arParams['FAVORITE_URL']?>" class="c-icon-count<?=($arResult['COUNT'] > 0 ? ' has-items' : '')?>" rel="nofollow">
			<svg class="icon-svg"><use xlink:href="#svg-heart"></use></svg><span class="c-icon-count__quantity js-favorite-count"><?=$arResult['COUNT']?></span>
			<script>RS.Favorite.init(<?=\Bitrix\Main\Web\Json::encode($arIDs)?>)</script>
		</a>
    <?php $frame->beginStub(); ?>
		<a href="<?=$arParams['FAVORITE_URL']?>" class="c-icon-count" rel="nofollow">
			<svg class="icon-svg"><use xlink:href="#svg-heart"></use></svg><span class="c-icon-count__quantity js-favorite-count"><?=$arResult['COUNT']?></span>
		</a>
    <?php $frame->end(); ?>
</div>
