<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\MyTemplate;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

$templateLibrary = array('popup');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$this->addExternalCss(SITE_TEMPLATE_PATH.'/assets/styles/catalog-item.css');

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_CPV_TPL_ELEMENT_DELETE_CONFIRM'));

$positionClassMap = array(
	'left' => 'product-cat-label-left',
	'center' => 'product-cat-label-center',
	'right' => 'product-cat-label-right',
	'bottom' => 'product-cat-label-bottom',
	'middle' => 'product-cat-label-middle',
	'top' => 'product-cat-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_CPV_TPL_MESS_PRODUCT_NOT_AVAILABLE');
// $arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_CPV_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_CPV_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_CPV_CATALOG_RELATIVE_QUANTITY_FEW');
$arParams['~MESS_ECONOMY_INFO2'] = Loc::getMessage('RS_MM_BCPV_CATALOG_ECONOMY_INFO2');
$arParams['~MESS_ITEM_ARTNUMBER'] = Loc::getMessage('RS_MM_BCPV_CATALOG_ITEM_ARTNUMBER_MESSAGE');

$generalParams = array(
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
	'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
	'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
	'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'USE_PRODUCT_QUANTITY' => false, // $arParams['USE_PRODUCT_QUANTITY'],
	'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
	'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
	'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
	'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
	'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
	'COMPARE_PATH' => $arParams['COMPARE_PATH'],
	'COMPARE_NAME' => $arParams['COMPARE_NAME'],
	'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
	'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
	'LABEL_POSITION_CLASS' => $labelPositionClass,
	'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
	'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
	'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
	'~BASKET_URL' => $arParams['~BASKET_URL'],
	'~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
	'~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
	'~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
	'~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
	'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
	'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
	'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
	'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
	'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
	'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
	'MESS_BTN_REQUEST' => $arParams['~MESS_BTN_REQUEST'],
	'LINK_BTN_REQUEST' => $arParams['LINK_BTN_REQUEST'],

	// 'PRODUCT_BLOCKS' => $arParams['PRODUCT_BLOCKS'],
	'MESS_ECONOMY_INFO2' => $arParams['~MESS_ECONOMY_INFO2'],
	'MESS_ITEM_ARTNUMBER' => $arParams['~MESS_ITEM_ARTNUMBER'],
	'IS_USE_CART' => $arParams['IS_USE_CART'],
	'BASKET_URL' => $arParams['BASKET_URL'],
	'USE_VOTE_RATING' => $arParams['USE_VOTE_RATING'],
	'VOTE_DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
	'SHOW_RATING' => $arParams['SHOW_RATING'],
	'GRID_RESPONSIVE_SETTINGS' => $arParams['GRID_RESPONSIVE_SETTINGS'],
	'USE_FAVORITE' => $arParams['USE_FAVORITE'],
	'FAVORITE_COUNT_PROP' => $arParams['FAVORITE_COUNT_PROP'],
	'OFFER_TREE_DROPDOWN_PROPS' => $arParams['OFFER_TREE_DROPDOWN_PROPS'],
	'USE_GIFTS' => $arParams['USE_GIFTS'],
	'DISPLAY_PREVIEW_TEXT' => $arParams['DISPLAY_PREVIEW_TEXT'],
	'PREVIEW_TRUNCATE_LEN' => $arParams['PREVIEW_TRUNCATE_LEN'],
	'PRODUCT_PREVIEW' => $arParams['PRODUCT_PREVIEW'],
	'TEMPLATE_VIEW' => $arParams['TEMPLATE_VIEW'],
	'SHOW_ARTNUMBER' => $arParams['SHOW_ARTNUMBER'],
	'ARTNUMBER_PROP' => $arParams['ARTNUMBER_PROP'],
	// 'RS_LAZY_IMAGES_USE' => $arParams['RS_LAZY_IMAGES_USE'],
	'SLIDER_SLIDE_COUNT' => $arParams['SLIDER_SLIDE_COUNT'],
);

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));
$containerName = 'catalog-products-viewed-container';
$mainId = $this->GetEditAreaId('section');

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);

$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addData('HEADER_ATTRIBUTES', 'data-showed="false"  style="display:none;opacity:0;"');


if (
	!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS'])
	&& strlen($arParams['RS_LIST_SECTION_TITLE']) > 0
)
{
	$layout
		->addData('SECTION_ATTRIBUTES', ' id="'.$mainId.'" data-entity="parent-container" data-spy="item" data-target="#l-main__nav" data-title="'.$arParams['RS_LIST_SECTION_TITLE'].'"');

}
else
{
	$layout
		->addData('SECTION_ATTRIBUTES', ' id="'.$mainId.'" data-entity="parent-container"');
}


$layout->start();

?>
<div class="catalog-products-viewed" data-entity="<?=$containerName?>">
	<?
	if (!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS']))
	{
		$areaIds = array();

		foreach ($arResult['ITEMS'] as $item)
		{
			$uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
			$areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
			$this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
			$this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
		}
		?>
		<!-- items-container -->
		<?
		foreach ($arResult['ITEM_ROWS'] as $key => $rowData)
		{
			$rowItems = array_splice($arResult['ITEMS'], 0, $rowData['COUNT']);
			?>
			<div class="row row-borders <?=$rowData['CLASS']?>" data-entity="items-row">
				<?
				switch ($rowData['VARIANT'])
				{
					case 0:
						?>
						<div class="col-12 col-xs-12 xol-sm-12 col-md-12 col-lg-12 col-xl-12 product-cat-line-card">
							<?
							$item = reset($rowItems);
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.item',
								'catalog',
								array(
									'RESULT' => array(
										'ITEM' => $item,
										'AREA_ID' => $areaIds[$item['ID']],
										'TYPE' => 'LINE', // $rowData['TYPE'],
										'MODULES' => $arResult['MODULES'],
										// 'BIG_LABEL' => 'N',
										// 'BIG_DISCOUNT_PERCENT' => 'N',
										'BIG_BUTTONS' => 'Y',
										// 'SCALABLE' => 'N'
									),
									'PARAMS' => array('USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'])
										+ $generalParams
										+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
							?>
						</div>
						<?
						break;

					case 1:
						foreach ($rowItems as $item)
						{
							?>
							<div class="col-6 col-xs-6 xol-sm-6 col-md-6 col-lg-6 col-xl-6">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'catalog',
									array(
										'RESULT' => array(
											'ITEM' => $item,
											'AREA_ID' => $areaIds[$item['ID']],
											'TYPE' => $rowData['TYPE'],
											'MODULES' => $arResult['MODULES'],
											// 'BIG_LABEL' => 'N',
											// 'BIG_DISCOUNT_PERCENT' => 'N',
											// 'BIG_BUTTONS' => 'N',
											// 'SCALABLE' => 'N'
										),
										'PARAMS' => $generalParams
											+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);
								?>
							</div>
							<?
						}
						break;

					case 2:
						foreach ($rowItems as $item)
						{
							?>
							<div class="col-12 col-xs-12 xol-sm-4 col-md-4 col-lg-4 col-xl-4">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'catalog',
									array(
										'RESULT' => array(
											'ITEM' => $item,
											'AREA_ID' => $areaIds[$item['ID']],
											'TYPE' => $rowData['TYPE'],
											'MODULES' => $arResult['MODULES'],
											// 'BIG_LABEL' => 'N',
											// 'BIG_DISCOUNT_PERCENT' => 'N',
											// 'BIG_BUTTONS' => 'Y',
											// 'SCALABLE' => 'N'
										),
										'PARAMS' => $generalParams
											+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);
								?>
							</div>
							<?
						}
						break;

					case 3:
						foreach ($rowItems as $item)
						{
							?>
							<div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'catalog',
									array(
										'RESULT' => array(
											'ITEM' => $item,
											'AREA_ID' => $areaIds[$item['ID']],
											'TYPE' => $rowData['TYPE'],
											'MODULES' => $arResult['MODULES'],
											// 'BIG_LABEL' => 'N',
											// 'BIG_DISCOUNT_PERCENT' => 'N',
											// 'BIG_BUTTONS' => 'N',
											// 'SCALABLE' => 'N'
										),
										'PARAMS' => $generalParams
											+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);
								?>
							</div>
							<?
						}
						break;

					case 4:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<?
							$item = array_shift($rowItems);
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.item',
								'catalog',
								array(
									'RESULT' => array(
										'ITEM' => $item,
										'AREA_ID' => $areaIds[$item['ID']],
										'TYPE' => $rowData['TYPE'],
										'MODULES' => $arResult['MODULES'],
										// 'BIG_LABEL' => 'N',
										// 'BIG_DISCOUNT_PERCENT' => 'N',
										// 'BIG_BUTTONS' => 'Y',
										// 'SCALABLE' => 'Y'
									),
									'PARAMS' => $generalParams
										+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
							unset($item);
							?>
						</div>
						<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="row row-borders my--1">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'catalog',
											array(
												'RESULT' => array(
													'ITEM' => $rowItems[$i],
													'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
													'TYPE' => $rowData['TYPE'],
													'MODULES' => $arResult['MODULES'],
													// 'BIG_LABEL' => 'N',
													// 'BIG_DISCOUNT_PERCENT' => 'N',
													// 'BIG_BUTTONS' => 'N',
													// 'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$rowItems[$i]['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<?
						break;

					case 5:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="row row-borders my--1">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'catalog',
											array(
												'RESULT' => array(
													'ITEM' => $rowItems[$i],
													'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
													'TYPE' => $rowData['TYPE'],
													'MODULES' => $arResult['MODULES'],
													// 'BIG_LABEL' => 'N',
													// 'BIG_DISCOUNT_PERCENT' => 'N',
													// 'BIG_BUTTONS' => 'N',
													// 'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$rowItems[$i]['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<?
							$item = end($rowItems);
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.item',
								'catalog',
								array(
									'RESULT' => array(
										'ITEM' => $item,
										'AREA_ID' => $areaIds[$item['ID']],
										'TYPE' => $rowData['TYPE'],
										'MODULES' => $arResult['MODULES'],
										// 'BIG_LABEL' => 'N',
										// 'BIG_DISCOUNT_PERCENT' => 'N',
										// 'BIG_BUTTONS' => 'Y',
										// 'SCALABLE' => 'Y'
									),
									'PARAMS' => $generalParams
										+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
							unset($item);
							?>
						</div>
						<?
						break;

					case 6:
						foreach ($rowItems as $item)
						{
							?>
							<div class="col-6 col-xs-6 col-sm-4 col-md-2 col-lg-2 col-xl-2">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'catalog',
									array(
										'RESULT' => array(
											'ITEM' => $item,
											'AREA_ID' => $areaIds[$item['ID']],
											'TYPE' => $rowData['TYPE'],
											'MODULES' => $arResult['MODULES'],
											// 'BIG_LABEL' => 'N',
											// 'BIG_DISCOUNT_PERCENT' => 'N',
											// 'BIG_BUTTONS' => 'N',
											// 'SCALABLE' => 'N'
										),
										'PARAMS' => $generalParams
											+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);
								?>
							</div>
							<?
						}

						break;

					case 7:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<?
							$item = array_shift($rowItems);
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.item',
								'catalog',
								array(
									'RESULT' => array(
										'ITEM' => $item,
										'AREA_ID' => $areaIds[$item['ID']],
										'TYPE' => $rowData['TYPE'],
										'MODULES' => $arResult['MODULES'],
										// 'BIG_LABEL' => 'N',
										// 'BIG_DISCOUNT_PERCENT' => 'N',
										// 'BIG_BUTTONS' => 'Y',
										// 'SCALABLE' => 'Y'
									),
									'PARAMS' => $generalParams
										+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
							unset($item);
							?>
						</div>
						<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="row row-borders my--1">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-6 col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'catalog',
											array(
												'RESULT' => array(
													'ITEM' => $rowItems[$i],
													'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
													'TYPE' => $rowData['TYPE'],
													'MODULES' => $arResult['MODULES'],
													// 'BIG_LABEL' => 'N',
													// 'BIG_DISCOUNT_PERCENT' => 'N',
													// 'BIG_BUTTONS' => 'N',
													// 'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$rowItems[$i]['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<?
						break;

					case 8:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<div class="row row-borders my--1">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-6 col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'catalog',
											array(
												'RESULT' => array(
													'ITEM' => $rowItems[$i],
													'AREA_ID' => $areaIds[$rowItems[$i]['ID']],
													'TYPE' => $rowData['TYPE'],
													'MODULES' => $arResult['MODULES'],
													// 'BIG_LABEL' => 'N',
													// 'BIG_DISCOUNT_PERCENT' => 'N',
													// 'BIG_BUTTONS' => 'N',
													// 'SCALABLE' => 'N'
												),
												'PARAMS' => $generalParams
													+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$rowItems[$i]['IBLOCK_ID']])
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
										?>
									</div>
									<?
								}
								?>
							</div>
						</div>
						<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
							<?
							$item = end($rowItems);
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.item',
								'catalog',
								array(
									'RESULT' => array(
										'ITEM' => $item,
										'AREA_ID' => $areaIds[$item['ID']],
										'TYPE' => $rowData['TYPE'],
										'MODULES' => $arResult['MODULES'],
										// 'BIG_LABEL' => 'N',
										// 'BIG_DISCOUNT_PERCENT' => 'N',
										// 'BIG_BUTTONS' => 'Y',
										// 'SCALABLE' => 'Y'
									),
									'PARAMS' => $generalParams
										+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
							unset($item);
							?>
						</div>
						<?
						break;

					case 9:
						foreach ($rowItems as $item)
						{
							?>
							<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 product-cat-table-card">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'catalog',
									array(
										'RESULT' => array(
											'ITEM' => $item,
											'AREA_ID' => $areaIds[$item['ID']],
											'TYPE' => 'TABLE', // $rowData['TYPE'],
											'MODULES' => $arResult['MODULES'],
											// 'BIG_LABEL' => 'N',
											// 'BIG_DISCOUNT_PERCENT' => 'N',
											// 'BIG_BUTTONS' => 'N'
										),
										'PARAMS' => $generalParams
											+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);
								?>
							</div>
							<?
						}
						break;

					case 10:
					case 11:
						foreach ($rowItems as $item)
						{
							?>
							<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-5ths col-xl-5ths d-flex">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'catalog',
									array(
										'RESULT' => array(
											'ITEM' => $item,
											'AREA_ID' => $areaIds[$item['ID']],
											'TYPE' => $rowData['TYPE'],
											'MODULES' => $arResult['MODULES'],
											// 'BIG_LABEL' => 'N',
											// 'BIG_DISCOUNT_PERCENT' => 'N',
											// 'BIG_BUTTONS' => 'N'
										),
										'PARAMS' => $generalParams
											+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);
								?>
							</div>
							<?
						}
						break;

				}
				?>
			</div>
			<?
		}
		unset($generalParams, $rowItems);
		?>
		<!-- items-container -->
		<?
	}
	else
	{
		// load css for bigData/deferred load
		$APPLICATION->IncludeComponent(
			'bitrix:catalog.item',
			'catalog',
			array(),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
	}
	?>
</div>

<script>
	BX.message({
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BASKET_URL: '<?=$arParams['BASKET_URL']?>',
		ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_CPV_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_CPV_CATALOG_TITLE_BASKET_PROPS')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_CPV_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_CPV_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_CPV_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_CPV_CATALOG_MESS_COMPARE_TITLE')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_CPV_CATALOG_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
	});
	var <?=$obName?> = new JCCatalogProductsViewedComponent({
		initiallyShowHeader: '<?=!empty($arResult['ITEM_ROWS'])?>',
		container: '<?=$containerName?>'
	});
</script>

<?php
$layout->end();
