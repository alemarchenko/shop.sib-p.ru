<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

use \Bitrix\Main\Localization\Loc;

$const = 12;
if($arParams['USE_VK_GROUPS'] != "N" && $arParams['USE_FB_GROUPS'] != "N"){
    $const = 6;
} else if ($arParams['USE_VK_GROUPS'] != "N" || $arParams['USE_FB_GROUPS'] != "N") {
    $const = 9;
}

$layout = new \Redsign\MegaMart\Layouts\Section();

$layout
	->addModifier('container')
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('inner-spacing')
	->addModifier('outer-spacing');

$layout->start();
?>

<div class="row company-index">
	<? if( $arParams['USE_VK_GROUPS'] != "N"){?>
	<div class="col-md-12 col-lg-3 soc soc-1" >
		<div id="vk_widget"></div>
		<script type="text/javascript">
			function vkWidgetInit() {
				document.getElementById('vk_widget').innerHTML = '<div id="vk_groups"></div>';
				VK.Widgets.Group("vk_groups", {mode: 5, no_cover: 1, width: "auto", color1: 'ffffff', color2: '585f69'}, <?=$arParams['VK_GROUP_ID']?>);
			}
			window.addEventListener('load', vkWidgetInit, false);
			window.addEventListener('resize', vkWidgetInit, false);
		</script>
	</div>
	<?}?>
	<div class="col-md-12 col-lg-<?=$const?> comp" >

		<div>
			<?
			if($arResult["FILE"] <> ''):
				include($arResult["FILE"]);
			endif;
			?>
		</div>
		<div class="text-company">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				".default",
				array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "include/comp_index.php",
					"COMPONENT_TEMPLATE" => "company",
					"SECTIONS_VIEW_MODE" => "",
					"INFO_VK" => "N",
					"PROPS_TABS" => "",
					"RSFLYAWAY_PROP_MORE_PHOTO" => "",
					"RSFLYAWAY_PROP_ARTICLE" => "",
					"USE_CUSTOM_COLLECTION" => "Y",
					"USE_BLOCK_MODS" => "Y",
					"MODS_BLOCK_NAME" => "",
					"RSFLYAWAY_USE_FAVORITE" => "Y",
					"FILTER_PROP_SEARCH" => "",
					"RSFLYAWAY_PROP_OFF_POPUP" => "Y",
					"RSFLYAWAY_HIDE_BASKET_POPUP" => "Y",
					"SORTER_USE_AJAX" => "N",
					"FILTER_USE_AJAX" => "N",
					"SHOW_SECTION_URL" => "Y",
					"DETAIL_INLINE_PROPS" => "",
					"INFO_FACEBOOK" => "N",
					"LANGE" => "0",
				),
				false,
				array(
					"ACTIVE_COMPONENT" => "Y"
				)
			);?>
		</div>

		<script>
			$('.text-company').readmore({
				heightMargin: 16,
				collapsedHeight: <?=$arParams['LANGE']?>,
				speed: 100,
				moreLink: '<div class="cl cl-2"><div class="company-container"><div class="company-btn"><button class="butt hover" type="button" role="presentation"><svg class="icon-svg"><use xlink:href="#svg-arrow-down"></use></svg></button></div><div class="company-text"><a class="o_company2 ml-4"><?=Loc::getMessage('SHOW_ALL')?></a></div></div></div>',
				lessLink: '<div class="cl cl-1"><div class="company-container"><div class="company-btn"><button class="butt butt-2 hover" type="button" role="presentation"><svg class="icon-svg"><use xlink:href="#svg-arrow-up"></use></svg></button></div><div class="company-text"><a class="o_company2 ml-4"><?=Loc::getMessage('HIDE')?></a></div></div></div>',
				afterToggle: function(trigger, element, more) {
					$('.company-btn .butt').removeClass('hover');
				}
			});
			$('.company-btn .butt').removeClass('hover');
		</script>
	</div>

	<? if( $arParams['USE_FB_GROUPS'] != "N"){?>
		<script src="https://vk.com/js/api/openapi.js?139" type="text/javascript"></script>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.12';
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
		<div class="col-md-12 col-lg-3 soc soc-2" >
			<div class="fb-page" data-href="<?=$arParams['FB_GROUP_HREF']?>" data-hide-cover="false" data-show-facepile="true">	</div>
		</div>
	<?}?>
</div>
<?

$layout->end();
