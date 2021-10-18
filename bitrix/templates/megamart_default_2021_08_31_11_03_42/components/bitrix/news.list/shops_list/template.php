<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (empty($arResult['ITEMS'])) {
	return;
}

$this->addExternalCss(SITE_TEMPLATE_PATH.'/assets/styles/shop-list-items.css');

$layout = new \Redsign\MegaMart\Layouts\Section();

$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addData('SECTION_ATTRIBUTES', 'id="'.$arParams['CONTAINER_ID'].'"');

$layout->start();
?>

<div class="shop-list" id="<?=$arParams['CONTAINER_ID']?>">

	<div class="shop-list-map">
		<div class="shop-list-map__container" id="<?=$arParams['MAP_ID']?>"></div>

		<div class="shop-list-filter">

			<div class="shop-list-filter__group">
				<div class="text-extra small"><?=Loc::getMessage('RS_NL_SHOP_LIST_CHANGE_CITY');?></div>
				<div class="dropdown shop-list-filter-dropdown ">
					<a class="btn btn-dropdown dropdown-toggle" href="#" role="button" id="<?=$arParams['CONTAINER_ID']?>-filter-city" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?=Loc::getMessage('RS_NL_SHOP_LIST_CHANGE_CITY');?>
					</a>

					<div class="dropdown-menu" aria-labelledby="<?=$arParams['CONTAINER_ID']?>-filter-city" x-placement="bottom-start">
						<a class="dropdown-item" href="#" data-filter="CITY" data-filter-val=""><?=Loc::getMessage('RS_NL_SHOP_LIST_CHANGE_ALL')?></a>
						<?php foreach ($arResult['FILTER_LIST']['CITY'] as $sCityFilterCode => $sCityFilterVal): ?>
							<a class="dropdown-item" href="#" data-filter="CITY" data-filter-val="<?=$sCityFilterCode?>"><?=$sCityFilterVal?></a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<?php if (!empty($arResult['FILTER_LIST']['TYPE'])):	?>
			<div class="shop-list-filter__group d-none d-lg-block">
				<?php foreach ($arResult['FILTER_LIST']['TYPE'] as $sTypeFilterCode => $sTypeFilterVal): ?>
					<a href="#" data-filter="TYPE" data-filter-val="<?=$sTypeFilterCode?>" class="btn btn-outline-secondary-primary"><?=$sTypeFilterVal?></a>
				<?php endforeach; ?>
				<a href="#" data-filter="TYPE" data-filter-val="" class="btn btn-primary"><?=Loc::getMessage('RS_NL_SHOP_LIST_ALL_OBJECTS')?></a>
			</div>

			<div class="shop-list-filter__group d-block d-lg-none">
				<div class="text-extra small"><?=Loc::getMessage('RS_NL_SHOP_LIST_CHANGE_TYPE')?></div>
				<div class="dropdown shop-list-filter-dropdown">
					<a class="btn btn-dropdown shop-list-filter-dropdown-button dropdown-toggle" href="#" role="button" id="<?=$arParams['CONTAINER_ID']?>-filter-type" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?=Loc::getMessage('RS_NL_SHOP_LIST_ALL_OBJECTS')?>
					</a>

					<div class="dropdown-menu" aria-labelledby="<?=$arParams['CONTAINER_ID']?>-filter-type" x-placement="bottom-start">
						<a class="dropdown-item" href="#" data-filter="TYPE" data-filter-val=""><?=Loc::getMessage('RS_NL_SHOP_LIST_ALL_OBJECTS');?></a>
						<?php foreach ($arResult['FILTER_LIST']['TYPE'] as $sTypeFilterCode => $sTypeFilterVal): ?>
							<a class="dropdown-item" href="#" data-filter="TYPE" data-filter-val="<?=$sTypeFilterCode?>"><?=$sTypeFilterVal?></a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php endif; ?>

		</div>
	</div>


	<ul class="shop-list-items list-unstyled">
	<?php
	foreach ($arResult['ITEMS'] as $arItem):
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
		<li class="shop-list-item" data-item="<?=$arItem['ID']?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="shop-list-item__container">
				<?php if($arParams["DISPLAY_PICTURE"]!="N"): ?>
				<div class="shop-list-item__picture">
					<?php if (!empty($arItem['PREVIEW_PICTURE'])): ?>
						<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<div class="shop-list-item__data">
					<div class="shop-list-item__name">
						<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
							<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<?=$arItem['NAME']?>
							</a>
						<?php else: ?>
							<?=$arItem['NAME']?>
						<?php endif; ?>

					</div>

					<?php
					if (!empty($arItem['DISPLAY_PROPERTIES'])):
						foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp):
							if (in_array($arProp['CODE'], $arParams['SKIP_DISPLAY_PROPERTIES'])) {
								continue;
							}
					?>
							<?php if ($arProp['CODE'] == $arParams['PROP_SCHEDULE']): ?>
								<div class="shop-list-item__prop shop-list-item__prop--schedule">
									<svg class="icon-svg"><use xlink:href="#svg-clock"></use></svg>
									<?=$arProp['DISPLAY_VALUE']?>
								</div>
                            <?php elseif ($arProp['CODE'] == $arParams['PROP_PHONE']): 
                                $sPhoneUrl = preg_replace('/[^0-9\+]/', '', $arProp['DISPLAY_VALUE']);
                            ?>
								<div class="shop-list-item__prop shop-list-item__prop--phone">
									<svg class="icon-svg "><use xlink:href="#svg-phonetube"></use></svg>
									<a href="tel:<?=$sPhoneUrl?>"><?=$arProp['DISPLAY_VALUE']?></a>
								</div>
							<?php elseif ($arProp['CODE'] == $arParams['PROP_EMAIL']): ?>
								<div class="shop-list-item__prop shop-list-item__prop--email">
									<svg class="icon-svg"><use xlink:href="#svg-e-mail"></use></svg>
									<a href="mailto:<?=$arProp['DISPLAY_VALUE']?>"><?=$arProp['DISPLAY_VALUE']?></a>
								</div>
							<?php else: ?>
								<div class="shop-list-item__prop shop-list-item__prop--custom">
									<?=$arProp['CODE']?>: <?=$arProp['DISPLAY_VALUE']?>
								</div>
							<?php endif; ?>
					<?php
						endforeach;
					endif;
					?>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
</div>

<script>
	function <?=$arParams['ONLOAD_CALLBACK_FN_NAME']?> () {
		new RS.ShopList(<?=CUtil::PhpToJSObject($arResult['JS_PARAMS'])?>);
	}
</script>

<?php
switch ($arParams['MAP_PROVIDER']) {
	case 'google':
		?><script src="//cdnjs.cloudflare.com/ajax/libs/js-marker-clusterer/1.0.0/markerclusterer_compiled.js"></script><?
		?><script async defer src="//maps.googleapis.com/maps/api/js?key=<?=$arParams['GOOGLE_MAP_API_KEY']?>&callback=<?=$arParams['ONLOAD_CALLBACK_FN_NAME']?>"></script><?
		break;

	case 'yandex';
	default:
		?><script async defer src="//api-maps.yandex.ru/2.1/?lang=ru_RU&onload=<?=$arParams['ONLOAD_CALLBACK_FN_NAME']?>"></script><?
		break;
}


$layout->end();
