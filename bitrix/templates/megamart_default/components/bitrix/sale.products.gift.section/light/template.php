<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

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
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_SPGS_TPL_ELEMENT_DELETE_CONFIRM'));

$positionClassMap = array(
	'left' => 'product-light-label-left',
	'center' => 'product-light-label-center',
	'right' => 'product-light-label-right',
	'bottom' => 'product-light-label-bottom',
	'middle' => 'product-light-label-middle',
	'top' => 'product-light-label-top'
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

$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_SPGS_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_SPGS_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_SPGS_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_SPGS_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_SPGS_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_SPGS_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_SPGS_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_SPGS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_SPGS_CATALOG_RELATIVE_QUANTITY_FEW');

$generalParams = array(
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
	'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
	'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
	'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
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

	// 'PRODUCT_BLOCKS' => $arParams['PRODUCT_BLOCKS'],
	'IS_USE_CART' => $arParams['IS_USE_CART'],
	'BASKET_URL' => $arParams['BASKET_URL'],
	'RS_LAZY_IMAGES_USE' => $arParams['RS_LAZY_IMAGES_USE'],
);

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));
$containerName = 'sale-products-gift-container';
?>

<?php
$mainId = $this->GetEditAreaId('section');

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);
$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addData('SECTION_ATTRIBUTES', 'id="'.$mainId.'" data-entity="parent-container"')
	->addData('HEADER_ATTRIBUTES', 'data-showed="false" style="display:none;opacity:0;"')
	->addData('SECTION_MAIN_ATTRIBUTES', ' data-entity="'.$containerName.'"');

// $arParams['USE_OWL'] = 'N';
if ($arParams['USE_OWL'] == 'Y')
{
	$layout->useSlider($containerName);
}
$layout->start();
?>
<!-- section-container -->
	<?
	if (!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS']))
	{
		$areaIds = array();

		foreach ($arResult['ITEMS'] as &$item)
		{
			$uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
			$areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
			$this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
			$this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);

			// custom gift labels
			$item['LABEL_VALUE'] = $arParams['TEXT_LABEL_GIFT'] ?: Loc::getMessage('CT_SPGS_TPL_TEXT_LABEL_GIFT_DEFAULT');
			$item['LABEL_ARRAY_VALUE'] = array('gift' => $item['LABEL_VALUE']);
			$item['LABEL_PROP_MOBILE'] = array('gift' => true);
			$item['LABEL'] = !empty($item['LABEL_VALUE']);
		}
		unset($item);
		?>
		<!-- items-container -->
		<?
		if ($arParams['USE_OWL'] == 'Y')
		{

			if (is_array($arParams['SLIDER_RESPONSIVE_SETTINGS']) && count($arParams['SLIDER_RESPONSIVE_SETTINGS']) > 0)
			{
				$arSliderOptions = array_merge(
					array(
						'responsive' => $arParams['SLIDER_RESPONSIVE_SETTINGS'],
					),
					array(
						'items' => 1,
						'margin' => 1,
					)
				);
			}
			else
			{
				$arSliderOptions = array(
					'margin' => 1,
				);
			}

			if (isset($arParams['OWL_CHANGE_DELAY']) && (int)$arParams['OWL_CHANGE_DELAY'] > 0)
			{
				$arSliderOptions['autoplay'] = true;
				$arSliderOptions['autoplayTimeout'] = $arParams['OWL_CHANGE_DELAY'];

				if (isset($arParams['OWL_CHANGE_SPEED']) && (int)$arParams['OWL_CHANGE_SPEED'] >= 0)
				{
					$arSliderOptions['autoplaySpeed'] = $arParams['OWL_CHANGE_SPEED'];
					$arSliderOptions['smartSpeed'] = $arParams['OWL_CHANGE_SPEED'];
				}
				else
				{
					$arSliderOptions['autoplaySpeed'] = 2000;
					$arSliderOptions['smartSpeed'] = 2000;
				}
			}
			?>
			<div data-slider="<?=$containerName?>" data-slider-options="<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($arSliderOptions))?>" class="row row-m-1 show-items-xs-1 show-items-md-2 show-item-lg-3 show-items-xl-4">

				<?php
				foreach ($arResult['ITEMS'] as $item)
				{
					?>
					<div class="col col-12 col-md-6 col-lg-4 col-xl-3">
					<?php
					$APPLICATION->IncludeComponent(
						'bitrix:catalog.item',
						'light',
						array(
							'RESULT' => array(
								'ITEM' => $item,
								'AREA_ID' => $areaIds[$item['ID']],
								'TYPE' => 'CARD',
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
					<?php
				}
				?>
			</div>
			<?php
		}
		else
		{
		foreach ($arResult['ITEM_ROWS'] as $rowData)
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
								'light',
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
							<div class="col-6 col-xs-6 xol-sm-6 col-md-6 col-lg-6 col-xl-6 d-flex">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'light',
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
							<div class="col-12 col-xs-12 xol-sm-4 col-md-4 col-lg-4 col-xl-4 d-flex">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'light',
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
							<div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 d-flex">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'light',
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

/*
					case 4:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 d-flex">
							<?
							$item = array_shift($rowItems);
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.item',
								'light',
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
									<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 d-flex">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'light',
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
									<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 d-flex">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'light',
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
						<div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 d-flex">
							<?
							$item = end($rowItems);
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.item',
								'light',
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
*/

					case 6:
						foreach ($rowItems as $item)
						{
							?>
							<div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-2 col-xl-2 d-flex">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'light',
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

/*
					case 7:
						$rowItemsCount = count($rowItems);
						?>
						<div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 d-flex">
							<?
							$item = array_shift($rowItems);
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.item',
								'light',
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
						<div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
							<div class="row row-borders my--1">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-6 col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 d-flex">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'light',
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
						<div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
							<div class="row row-borders my--1">
								<?
								for ($i = 0; $i < $rowItemsCount - 1; $i++)
								{
									?>
									<div class="col-6 col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 d-flex">
										<?
										$APPLICATION->IncludeComponent(
											'bitrix:catalog.item',
											'light',
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
						<div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 d-flex">
							<?
							$item = end($rowItems);
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.item',
								'light',
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
*/

					case 9:
					default:
						foreach ($rowItems as $item)
						{
							?>
							<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<?
								$APPLICATION->IncludeComponent(
									'bitrix:catalog.item',
									'light',
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
				}
				?>
			</div>
			<?
		}
		unset($generalParams, $rowItems);
		}
		?>
		<!-- items-container -->
		<?
	}
	else
	{
		// load css for bigData/deferred load
		$APPLICATION->IncludeComponent(
			'bitrix:catalog.item',
			'light',
			array(),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
	}


	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedTemplate = $signer->sign($templateName, 'sale.products.gift.section');
	$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'sale.products.gift.section');
	?>

<script>
	BX.message({
<?/*
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_SPGS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BASKET_URL: '<?=$arParams['BASKET_URL']?>',
		ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_SPGS_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_SPGS_CATALOG_TITLE_BASKET_PROPS')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_SPGS_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_SPGS_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_SPGS_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_SPGS_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_SPGS_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_SPGS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_SPGS_CATALOG_MESS_COMPARE_TITLE')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_SPGS_CATALOG_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_SPGS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
*/?>
		SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
	});

	var <?=$obName?> = new JCSaleProductsGiftSectionComponent({
		siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
		componentPath: '<?=CUtil::JSEscape($componentPath)?>',
		deferredLoad: true,
		initiallyShowHeader: '<?=!empty($arResult['ITEM_ROWS'])?>',
		template: '<?=CUtil::JSEscape($signedTemplate)?>',
		parameters: '<?=CUtil::JSEscape($signedParams)?>',
		container: '<?=$containerName?>'
	});
</script>

<!-- section-container -->
<?php
$layout->end();
