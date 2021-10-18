<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Web\Json;

$this->setFrameMode(true);

Loc::loadMessages(__FILE__);

$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');
$isFilter = ($arParams['USE_FILTER'] == 'Y');

$arFilter = array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ACTIVE" => "Y",
	"GLOBAL_ACTIVE" => "Y",
	"CNT_ACTIVE" => "Y"
);
if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
	$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
	$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];

$obCache = new CPHPCache();
if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
{
	$arResult['SECTION'] = $obCache->GetVars();
}
elseif ($obCache->StartDataCache())
{
	$arResult['SECTION'] = array();
	if (Loader::includeModule("iblock"))
	{
		$arSelect = array(
			'ID',
			'NAME',
			'LEFT_MARGIN',
			'RIGHT_MARGIN',
			'SECTION_PAGE_URL',
			'DEPTH_LEVEL',
		);

		if ($arParams['FEATURE_FILTER_USER_FIELDS'] != '' && $arParams['FEATURE_FILTER_USER_FIELDS'] != '-')
		{
			$arSelect[] = $arParams['FEATURE_FILTER_USER_FIELDS'];
		}

		if ($arParams['LIST_BACKGROUND_COLOR'] != '' && $arParams['LIST_BACKGROUND_COLOR'] != '-')
		{
			$arSelect[] = $arParams['LIST_BACKGROUND_COLOR'];
		}

		$dbRes = CIBlockSection::GetList(array(), $arFilter, true, $arSelect);
		$dbRes->SetUrlTemplates('', $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section']);
		$arResult['SECTION'] = $dbRes->GetNext();

		if (is_array($arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']]) && count($arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']]) > 0)
		{
			$arFeatureFilter = array();
			$dbUserType = CUserTypeEntity::GetList(
				array(),
				array(
					'ENTITY_ID' => 'IBLOCK_'.$arParams['IBLOCK_ID'].'_SECTION',
					'FIELD_NAME' => $arParams['FEATURE_FILTER_USER_FIELDS']
				)
			);

			if ($arUserType = $dbUserType->Fetch())
			{
				$arFeatureFilter = $arUserType;
			}
			unset($arUserType, $dbUserType);

			if ($arFeatureFilter['USER_TYPE_ID'] == 'hlblock')
			{
				$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($arFeatureFilter['SETTINGS']['HLBLOCK_ID'])->fetch();
				if (!!$hlblock)
				{
					$hlDataClass = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock)->getDataClass();
					$arFeatureFilter['VALUES'] = $hlDataClass::getList(array(
						'filter' => array('=ID' => array_values($arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']]))
					))->fetchAll();
				}
			}

			foreach ($arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']] as $iFeatureFilterkey => $sFeatureFilterId)
			{
				$bElementFind = false;

				foreach ($arFeatureFilter['VALUES'] as $arFieatureFilterVal)
				{
					if ($arFieatureFilterVal['ID'] == $sFeatureFilterId)
					{
						$arFieatureFilterVal['UF_LINK'] = $arResult['SECTION']['SECTION_PAGE_URL'].$arFieatureFilterVal['UF_LINK'];

						$arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']][$iFeatureFilterkey] = $arFieatureFilterVal;
						$bElementFind = true;
						break;
					}
				}
				unset($arFieatureFilterVal);

				if (!$bElementFind)
				{
					unset($arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']][$iFeatureFilterkey]);
				}
			}
			unset($iFeatureFilterkey, $sFeatureFilterId);

			usort($arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']], function($a, $b){
				$c = ($a['UF_SORT'] - $b['UF_SORT']);
				return $c;
			});

			unset($arResult['SECTION']['~UF_FEATURED_FILTER']);
		}

		if (empty($arResult['SECTION'][$arParams['LIST_BACKGROUND_COLOR']]))
		{
			$arPath = array();
			$pathIterator = CIBlockSection::GetNavChain(
				$arResult['SECTION']['IBLOCK_ID'],
				$arResult['SECTION']['ID'],
				array('ID')
			);

			while ($path = $pathIterator->GetNext())
			{
				$arPath[$path['ID']] = $path;
			}

			if (count($arPath) > 0)
			{
				$dbSections = CIBlockSection::GetList(
					array(
						'DEPTH_LEVEL' => 'DESC',
					),
					array(
						'IBLOCK_ID' => $arParams['IBLOCK_ID'],
						'=ID' => array_keys($arPath),
					),
					false,
					$arSelect
				);

				while ($arSection = $dbSections->GetNext())
				{
					if (!empty($arSection[$arParams['LIST_BACKGROUND_COLOR']]))
					{
						$arResult['SECTION'][$arParams['LIST_BACKGROUND_COLOR']] = $arSection[$arParams['LIST_BACKGROUND_COLOR']];
						break;
					}
				}
				unset($dbSections, $arSection);
			}
		}

		if(defined("BX_COMP_MANAGED_CACHE"))
		{
			global $CACHE_MANAGER;
			$CACHE_MANAGER->StartTagCache("/iblock/catalog");

			if ($arResult['SECTION'])
				$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);

			$CACHE_MANAGER->EndTagCache();
		}
		else
		{
			if(!$arResult['SECTION'])
				$arResult['SECTION'] = array();
		}
	}
	$obCache->EndDataCache($arResult['SECTION']);
}

if (!isset($arResult['SECTION']))
	$arResult['SECTION'] = array();

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
}
else
{
	$basketAction = isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
}

if (
	$arParams['CATALOG_VIEW_MODE'] == 'VIEW_SECTIONS'
	&& ($arResult['SECTION']['RIGHT_MARGIN'] - $arResult['SECTION']['LEFT_MARGIN']) > 1
)
{
	$arParams['SIDEBAR_INNER_SECTIONS_SHOW'] = (isset($arParams['SIDEBAR_INNER_SECTIONS_SHOW']) && $arParams['SIDEBAR_INNER_SECTIONS_SHOW'] == 'Y' ? 'Y' : 'N');
	$arParams['SIDEBAR_OUTER_SECTIONS_SHOW'] = (isset($arParams['SIDEBAR_OUTER_SECTIONS_SHOW']) && $arParams['SIDEBAR_OUTER_SECTIONS_SHOW'] == 'Y' ? 'Y' : 'N');

	$bSidebarInner = false; // ($arParams["SIDEBAR_INNER_SECTIONS_SHOW"] == "Y");
	$bSidebarOuter = false; // ($arParams["SIDEBAR_OUTER_SECTIONS_SHOW"] == "Y");

	//region Catalog Section
	$sectionListParams = [
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"BANNER_TYPE" => $arParams["BANNER_TYPE"],
		// "SECTIONS_VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
		"VIEW_MODE" => $arParams['SECTION_LIST_VIEW'],
		"SHOW_PARENT_NAME" => 'N',//$arParams["SECTIONS_SHOW_PARENT_NAME"],
		"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
		"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
		"SECTION_USER_FIELDS" => $arParams["SECTION_USER_FIELDS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"GRID_RESPONSIVE_SETTINGS" => $arParams["~SECTIONS_GRID_RESPONSIVE_SETTINGS"],
	];
	if ($sectionListParams["COUNT_ELEMENTS"] === "Y")
	{
		$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_ACTIVE";
		if ($arParams["HIDE_NOT_AVAILABLE"] == "Y")
		{
			$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_AVAILABLE";
		}
	}
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list",
		"catalog",
		$sectionListParams,
		$component
	);
	unset($sectionListParams);
	//endregion
}
else
{
	if (isset($arResult['SECTION']['ELEMENT_CNT']) && 0 >= $arResult['SECTION']['ELEMENT_CNT']) // If not items found
	{
		$arParams['SHOW_SECTIONS_LIST'] = 'N';
		$arParams['SIDEBAR_OUTER_SECTION_SHOW'] = 'N';
		$arParams['SIDEBAR_INNER_SECTION_SHOW'] = 'N';

		$arParams['USE_SORTER'] = 'N';

		$isFilter = false;
	}

	$arParams['SIDEBAR_INNER_SECTION_SHOW'] = (isset($arParams['SIDEBAR_INNER_SECTION_SHOW']) && $arParams['SIDEBAR_INNER_SECTION_SHOW'] == 'Y' ? 'Y' : 'N');
	$arParams['SIDEBAR_OUTER_SECTION_SHOW'] = (isset($arParams['SIDEBAR_OUTER_SECTION_SHOW']) && $arParams['SIDEBAR_OUTER_SECTION_SHOW'] == 'Y' ? 'Y' : 'N');

	$bSidebarInner = ($arParams["SIDEBAR_INNER_SECTION_SHOW"] == "Y");
	$bSidebarOuter = ($arParams["SIDEBAR_OUTER_SECTION_SHOW"] == "Y");

	if ($arParams['SHOW_SECTIONS_LIST'] == 'Y')
	{
		//region Catalog Section
		$sectionListParams = [
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
			// "TOP_DEPTH" => 1,
			"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"VIEW_MODE" => $arParams['SECTION_LIST_VIEW'],
			"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
			"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
			"ADD_SECTIONS_CHAIN" => "N",
			"GRID_RESPONSIVE_SETTINGS" => $arParams['~SECTION_LIST_VIEW_BLOCKS_GRID_RESPONSIVE_SETTINGS'],
			"VIEW_MODE_BUFFER" => (RS_MM_CATALOG_SECTION_TYPE != 'type3' ? 'lines' : ''),
		];
		if ($sectionListParams["COUNT_ELEMENTS"] === "Y")
		{
			$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_ACTIVE";
			if ($arParams["HIDE_NOT_AVAILABLE"] == "Y")
			{
				$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_AVAILABLE";
			}
		}
		$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list",
			"catalog2",
			$sectionListParams,
			$component
		);
		unset($sectionListParams);
		//endregion

		if (RS_MM_CATALOG_SECTION_TYPE != 'type3')
		{
			$APPLICATION->AddViewContent(
				'site_sidebar_outer',
				$APPLICATION->GetViewContent('site_catalog_section_list_buffer'),
				300
			);
		}

		// if (RS_MM_CATALOG_SECTION_TYPE == 'type3')
		// {
		// 	//region Catalog Section
		// 	$sectionListParams = [
		// 		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		// 		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		// 		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		// 		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		// 		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		// 		"CACHE_TIME" => $arParams["CACHE_TIME"],
		// 		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		// 		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		// 		"TOP_DEPTH" => 1,
		// 		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		// 		"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
		// 		"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
		// 		"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
		// 		"ADD_SECTIONS_CHAIN" => "N",
		// 	];
		// 	if ($sectionListParams["COUNT_ELEMENTS"] === "Y")
		// 	{
		// 		$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_ACTIVE";
		// 		if ($arParams["HIDE_NOT_AVAILABLE"] == "Y")
		// 		{
		// 			$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_AVAILABLE";
		// 		}
		// 	}
		// 	$APPLICATION->IncludeComponent(
		// 		"bitrix:catalog.section.list",
		// 		"links",
		// 		$sectionListParams,
		// 		$component
		// 	);
		// 	unset($sectionListParams);
		// 	// endregion
		// }
		// else
		// {
		// 	//region Catalog Section
		// 	$sectionListParams = [
		// 		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		// 		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		// 		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		// 		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		// 		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		// 		"CACHE_TIME" => $arParams["CACHE_TIME"],
		// 		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		// 		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		// 		"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
		// 		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		// 		"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
		// 		"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
		// 		"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
		// 		"ADD_SECTIONS_CHAIN" => "N",
		// 	];
		// 	if ($sectionListParams["COUNT_ELEMENTS"] === "Y")
		// 	{
		// 		$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_ACTIVE";
		// 		if ($arParams["HIDE_NOT_AVAILABLE"] == "Y")
		// 		{
		// 			$sectionListParams["COUNT_ELEMENTS_FILTER"] = "CNT_AVAILABLE";
		// 		}
		// 	}
		// 	ob_start();
		// 	$APPLICATION->IncludeComponent(
		// 		"bitrix:catalog.section.list",
		// 		"sidebar.menu",
		// 		$sectionListParams,
		// 		$component
		// 	);
		// 	$sHtmlContent = ob_get_clean();
		// 	$APPLICATION->AddViewContent('site_sidebar_outer', $sHtmlContent, 300);
		// 	unset($sHtmlContent);

		// 	unset($sectionListParams);
		// 	// endregion
		// }
	}

	if ($isFilter)
	{
		ob_start();
		$APPLICATION->IncludeComponent(
				"bitrix:catalog.smart.filter",
				"catalog",
				[
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"SECTION_ID" => $arResult['SECTION']['ID'],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"PRICE_CODE" => $arParams["~PRICE_CODE"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"SAVE_IN_SESSION" => "N",
					"FILTER_VIEW_MODE" => $arResult["FILTER_VIEW_MODE"],
					"XML_EXPORT" => "N",
					"SECTION_TITLE" => "NAME",
					"SECTION_DESCRIPTION" => "DESCRIPTION",
					'HIDE_NOT_AVAILABLE' => $arParams['FILTER_USE_HIDE_NOT_AVAILABLE'] === 'Y' ? 'N' : $arParams["HIDE_NOT_AVAILABLE"],
					"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
					'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
					'CURRENCY_ID' => $arParams['CURRENCY_ID'],
					"SEF_MODE" => $arParams["SEF_MODE"],
					"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
					"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
					"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
					"INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],

					"SCROLL_PROPS" => $arParams["FILTER_SCROLL_PROPS"],
					"OFFER_SCROLL_PROPS" => $arParams["OFFER_FILTER_SCROLL_PROPS"],
					"SEARCH_PROPS" => $arParams["FILTER_SEARCH_PROPS"],
					"OFFER_SEARCH_PROPS" => $arParams["OFFER_FILTER_SEARCH_PROPS"],
					"OFFER_TREE_COLOR_PROPS" => $arParams["OFFER_TREE_COLOR_PROPS"],
					"OFFER_TREE_BTN_PROPS" => $arParams["OFFER_TREE_BTN_PROPS"],
					"TOP_PROPS" => $arParams["FILTER_TOP_PROPS"],
					"TARGET_ID" => $arParams['AJAX_ID'],
					"FILTER_USE_HIDE_NOT_AVAILABLE" => $arParams['FILTER_USE_HIDE_NOT_AVAILABLE'],
					"FILTER_HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE']
				],
				$component,
				['HIDE_ICONS' => 'Y']
			);

		$sHtmlContent = ob_get_clean();
		if ($arResult["FILTER_VIEW_MODE"] == 'HORIZONTAL')
		{
			$sHtmlContentFilter = $sHtmlContent;
		}
		else
		{
			$APPLICATION->AddViewContent('site_sidebar_outer', $sHtmlContent, 500);
		}
		unset($sHtmlContent);
	}

	if ($isFilter)
	{
		if (
			is_array($arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']])
			&& count($arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']]) > 0
		)
		{
			ob_start();
			?>
			<div class="nav-wrap mt-6" <?=(strlen($arResult['SECTION'][$arParams['LIST_BACKGROUND_COLOR']]) > 0 ? 'style="color:'.$arResult['SECTION'][$arParams['LIST_BACKGROUND_COLOR']].'"' : '')?>>
				<div class="nav">
					<?php
					foreach ($arResult['SECTION'][$arParams['FEATURE_FILTER_USER_FIELDS']] as $arFeatureFilter)
					{
						$checked = false;
						if (isset($arResult['VARIABLES']['SMART_FILTER_PATH']))
						{
							$tmp = str_replace(explode('/', $arFeatureFilter['UF_LINK']), '', $arResult['VARIABLES']['SMART_FILTER_PATH']);
							if (str_replace('/', '', $tmp) == '')
							{
								$checked = true;
							}
						}

						if ($checked)
						{
							?>
							<a class="btn btn-primary btn-rounded mr-2" href="<?=$arResult['SECTION']['SECTION_PAGE_URL'];?>"><?=$arFeatureFilter['UF_NAME']?></a>
							<?php
						}
						else
						{
							?>
							<a class="btn btn-outline-secondary-primary btn-rounded mr-2" href="<?=$arFeatureFilter['UF_LINK'];?>"><?=$arFeatureFilter['UF_NAME']?></a>
							<?php
						}
					}
					?>
				</div>
			</div>
			<?php

			$sHtmlContent = ob_get_clean();
			$APPLICATION->AddViewContent('after-title', $sHtmlContent, 100);
			unset($sHtmlContent);
		}
		?>
		<div class="bg-light py-4 px-2 d-block mb-1 d-md-none">
			<a class="btn btn-outline-secondary w-100" data-fancybox="catalog-filter" data-type="inline" data-popup-type="window" data-touch="false" href="#<?=$arParams['AJAX_ID']?>-filter"><?=Loc::getMessage('RS_MM_BC_CATALOG_FILTER_SHOW')?></a>
		</div>
		<?php
	}

	if ($arResult["FILTER_VIEW_MODE"] == 'HORIZONTAL' && $isFilter)
	{
		?><div class="d-none d-sm-none d-md-block"><?
			echo $sHtmlContentFilter;
		?></div><?
	}

	if ($arParams['USE_SORTER'] == 'Y')
	{
		?>
		<div class="catalog-sorter">
			<?php
			global $alfaCTemplate, $alfaCSortType, $alfaCSortToo, $alfaCOutput;

			$arSorterParams = array();

			if (intval($arParams['SORTER_CNT_TEMPLATES']) > 0)
			{
				 for ($i = 0; $i < $arParams['SORTER_CNT_TEMPLATES']; $i++)
				 {
					$arSorterParams['ALFA_CNT_TEMPLATES_'.$i] = $arParams['SORTER_CNT_TEMPLATES_'.$i];
					$arSorterParams['ALFA_CNT_TEMPLATES_NAME_'.$i] = $arParams['SORTER_CNT_TEMPLATES_NAME_'.$i];
				}
			}

			$APPLICATION->IncludeComponent(
				"redsign:catalog.sorter",
				"catalog",
				array(
					"ALFA_ACTION_PARAM_NAME" => $arParams['SORTER_ACTION_PARAM_NAME'],
					"ALFA_ACTION_PARAM_VALUE" => $arParams['SORTER_ACTION_PARAM_VALUE'],
					"ALFA_CHOSE_TEMPLATES_SHOW" => $arParams['SORTER_CHOSE_TEMPLATES_SHOW'],
					"ALFA_DEFAULT_TEMPLATE" => $arParams['SORTER_DEFAULT_TEMPLATE'],
					"ALFA_SORT_BY_SHOW" => $arParams['SORTER_SORT_BY_SHOW'],
					"ALFA_SORT_BY_NAME" => $arParams['SORTER_SORT_BY_NAME'],
					"ALFA_SORT_BY_DEFAULT" => $arParams['SORTER_SORT_BY_DEFAULT'],
					"ALFA_OUTPUT_OF_SHOW" => $arParams['SORTER_OUTPUT_OF_SHOW'],
					"ALFA_OUTPUT_OF" => $arParams['SORTER_OUTPUT_OF'],
					"ALFA_OUTPUT_OF_DEFAULT" => $arParams['SORTER_OUTPUT_OF_DEFAULT'],
					"ALFA_OUTPUT_OF_SHOW_ALL" => $arParams['SORTER_OUTPUT_OF_SHOW_ALL'],
					"ALFA_SHORT_SORTER" => $arParams['SORTER_SHORT_SORTER'],
					"ALFA_CNT_TEMPLATES" => $arParams['SORTER_CNT_TEMPLATES'],
					"TARGET_ID" => $arParams['AJAX_ID'],
				) + $arSorterParams,
				$component
			);

			$alfaTemplateRows = false;

			switch ($alfaCTemplate)
			{
				case 'view-list':
					$alfaTemplateRows = array_fill (
						0,
						$alfaCOutput,
						array(
						   'VARIANT' => '0',
						   'BIG_DATA' => false
					   )
					);

					$alfaTemplateRows = Json::encode($alfaTemplateRows);
					break;

				case 'view-line':
					$alfaTemplateRows = array_fill (
						0,
						$alfaCOutput,
						array(
						   'VARIANT' => '9',
						   'BIG_DATA' => false
					   )
					);

					$alfaTemplateRows = Json::encode($alfaTemplateRows);
					break;

				default:
					$alfaTemplateRows = $arParams['LIST_PRODUCT_ROW_VARIANTS'];
					break;
			}
			?>
		</div>
		<?php
	}

	$componentSectionParams = array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_SORT_FIELD" => isset($alfaCSortType) ? $alfaCSortType : $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => isset($alfaCSortToo) ? $alfaCSortToo : $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => isset($alfaCSortType) ? $arParams["ELEMENT_SORT_FIELD"] : $arParams["ELEMENT_SORT_FIELD2"],
		"ELEMENT_SORT_ORDER2" => isset($alfaCSortToo) ? $arParams["ELEMENT_SORT_ORDER"] : $arParams["ELEMENT_SORT_ORDER2"],
		"PROPERTY_CODE" => (isset($arParams["LIST_PROPERTY_CODE"]) ? $arParams["LIST_PROPERTY_CODE"] : []),
		"PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"MESSAGE_404" => $arParams["~MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"PAGE_ELEMENT_COUNT" => isset($alfaCOutput) ? $alfaCOutput : $arParams["PAGE_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PRICE_CODE" => $arParams["~PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"]) ? $arParams["PRODUCT_PROPERTIES"] : []),

		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"LAZY_LOAD" => $arParams["LAZY_LOAD"],
		"LAZY_LOAD_VIEW" => $arParams["LAZY_LOAD_VIEW"],
		"MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
		"LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

		"OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"]) ? $arParams["OFFERS_CART_PROPERTIES"] : []),
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => (isset($arParams["LIST_OFFERS_PROPERTY_CODE"]) ? $arParams["LIST_OFFERS_PROPERTY_CODE"] : []),
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => (isset($arParams["LIST_OFFERS_LIMIT"]) ? $arParams["LIST_OFFERS_LIMIT"] : 0),

		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
		'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

		'LABEL_PROP' => $arParams['LABEL_PROP'],
		'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
		'LABEL_PROP_POSITION' => $arParams['LIST_LABEL_PROP_POSITION'],
		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
		'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
		'PRODUCT_ROW_VARIANTS' => $alfaTemplateRows ? $alfaTemplateRows : $arParams['LIST_PRODUCT_ROW_VARIANTS'],
		'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
		'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
		'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
		'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
		'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
		'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
		'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
		'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
		'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
		'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
		'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
		'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
		'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
		'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
		'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
		'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

		'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
		'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
		'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ""),
		'ADD_TO_BASKET_ACTION' => $basketAction,
		'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
		'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
		'COMPARE_NAME' => $arParams['COMPARE_NAME'],
		'USE_COMPARE_LIST' => 'Y',
		'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
		'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
		'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),

		'SITE_LOCATION_ID' => SITE_LOCATION_ID,
		"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
		"FILL_ITEM_ALL_PRICES" => (is_array($arParams['PRICE_CODE']) && count($arParams['PRICE_CODE']) > 1) ? 'Y' :  $arParams['FILL_ITEM_ALL_PRICES'],

		"AJAX_ID" => $arParams['AJAX_ID'],
		"DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		'COMPOSITE_FRAME' => 'Y',

		'IS_USE_CART' => $arParams['IS_USE_CART'],
		'PRICE_PROP' => $arParams['PRICE_PROP'],
		'DISCOUNT_PROP' => $arParams['DISCOUNT_PROP'],
		'CURRENCY_PROP' => $arParams['CURRENCY_PROP'],
		'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
		'SHOW_PARENT_DESCR' => 'Y',

		'TEMPLATE_VIEW' => $arParams['TEMPLATE_VIEW'],
		'GRID_RESPONSIVE_SETTINGS' => $arParams['~GRID_RESPONSIVE_SETTINGS'],
		'USE_VOTE_RATING' => $arParams['LIST_USE_VOTE_RATING'],
		'VOTE_DISPLAY_AS_RATING' => $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'],
		'SHOW_RATING' => $arParams['SHOW_RATING'],

		'USE_GIFTS' => $arParams['USE_GIFTS'],
		'USE_FAVORITE' => $arParams['USE_FAVORITE'],
		'FAVORITE_COUNT_PROP' => $arParams['FAVORITE_COUNT_PROP'],
		'SHOW_ARTNUMBER' => $arParams['SHOW_ARTNUMBER'],
		'ARTNUMBER_PROP' => $arParams['ARTNUMBER_PROP'],
		'OFFER_ARTNUMBER_PROP' => $arParams['OFFER_ARTNUMBER_PROP'],
		'OFFER_TREE_DROPDOWN_PROPS' => $arParams['OFFER_TREE_DROPDOWN_PROPS'],
		'RS_LAZY_IMAGES_USE' => $arParams['RS_LAZY_IMAGES_USE'],
		'BACKGROUND_COLOR' => $arParams['LIST_BACKGROUND_COLOR'],
		'SHOW_SECTION_DESCRIPTION' => $arParams['SHOW_SECTION_DESCRIPTION'],
		'SHOW_ERROR_SECTION_EMPTY' => $arParams['SHOW_ERROR_SECTION_EMPTY'],
		'DISPLAY_PREVIEW_TEXT' => $arParams['LIST_DISPLAY_PREVIEW_TEXT'],
		'SECTION_USER_FIELDS' => (isset($arParams['SECTION_USER_FIELDS']) ? $arParams['SECTION_USER_FIELDS'] : []),

		'MESS_BTN_REQUEST' => $arParams['MESS_BTN_REQUEST'],
		'LINK_BTN_REQUEST' => $arParams['LINK_BTN_REQUEST'],
		'MESS_BTN_BUY1CLICK' => $arParams['MESS_BTN_BUY1CLICK'],
		'CHEAPER_FORM_URL' => $arParams['CHEAPER_FORM_URL'],
		'PRODUCT_PREVIEW' => $arParams['PRODUCT_PREVIEW'],
		'SLIDER_SLIDE_COUNT' => $arParams['LIST_SLIDER_SLIDE_COUNT'],

		'BUY_ON_CAN_BUY' => $arParams['BUY_ON_CAN_BUY'],
		'SECTION_ADD_TO_BASKET_ACTION' => $arParams['SECTION_ADD_TO_BASKET_ACTION'],

		'RS_LIST_SECTION' => 'l_section',
	);

	if (isset($arParams['LIST_BACKGROUND_COLOR']) && !in_array($arParams['LIST_BACKGROUND_COLOR'], $componentSectionParams['SECTION_USER_FIELDS']))
	{
		$componentSectionParams['SECTION_USER_FIELDS'][] = $arParams['LIST_BACKGROUND_COLOR'];
	}
	?>

	<?$intSectionID = $APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		$arParams['LIST_TEMPLATE'],
		$componentSectionParams,
		$component
	);?>

	<?php
	$GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;

	if (ModuleManager::isModuleInstalled("sale"))
	{
		$arRecomData = array();
		$recomCacheID = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		$obCache = new CPHPCache();
		if ($obCache->InitCache(36000, serialize($recomCacheID), "/sale/bestsellers"))
		{
			$arRecomData = $obCache->GetVars();
		}
		elseif ($obCache->StartDataCache())
		{
			if (Loader::includeModule("catalog"))
			{
				$arSKU = CCatalogSku::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
				$arRecomData['OFFER_IBLOCK_ID'] = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
			}
			$obCache->EndDataCache($arRecomData);
		}

		if (!empty($arRecomData))
		{
			if ($arParams['USE_GIFTS_SECTION'] === 'Y')
			{
				CBitrixComponent::includeComponentClass('bitrix:sale.products.gift.section');
				$APPLICATION->IncludeComponent(
					'bitrix:sale.products.gift.section',
					'light',
					array(
						'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
						'IBLOCK_ID' => $arParams['IBLOCK_ID'],

						'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
						'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
						'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

						'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
						'ACTION_VARIABLE' => (!empty($arParams['ACTION_VARIABLE']) ? $arParams['ACTION_VARIABLE'] : 'action').'_spgs',

						'PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
							\Bitrix\Iblock\Component\ElementList::predictRowVariants(
								$arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'],
								$arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT']
							)
						),
						'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'],
						'DEFERRED_PRODUCT_ROW_VARIANTS' => '',
						'DEFERRED_PAGE_ELEMENT_COUNT' => 0,

						'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
						'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
						'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
						'PRODUCT_DISPLAY_MODE' => 'Y',
						'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
						'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
						'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
						'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

						'TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],

						'LABEL_PROP_'.$arParams['IBLOCK_ID'] => array(),
						'LABEL_PROP_MOBILE_'.$arParams['IBLOCK_ID'] => array(),
						'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],

						'ADD_TO_BASKET_ACTION' => $basketAction,
						'MESS_BTN_BUY' => $arParams['~GIFTS_MESS_BTN_BUY'],
						'MESS_BTN_ADD_TO_BASKET' => $arParams['~GIFTS_MESS_BTN_BUY'],
						'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
						'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],

						'PROPERTY_CODE' => (isset($arParams['LIST_PROPERTY_CODE']) ? $arParams['LIST_PROPERTY_CODE'] : []),
						'PROPERTY_CODE_MOBILE' => $arParams['LIST_PROPERTY_CODE_MOBILE'],
						'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],

						'OFFERS_FIELD_CODE' => $arParams['LIST_OFFERS_FIELD_CODE'],
						'OFFERS_PROPERTY_CODE' => (isset($arParams['LIST_OFFERS_PROPERTY_CODE']) ? $arParams['LIST_OFFERS_PROPERTY_CODE'] : []),
						'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
						'OFFERS_CART_PROPERTIES' => (isset($arParams['OFFERS_CART_PROPERTIES']) ? $arParams['OFFERS_CART_PROPERTIES'] : []),
						'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],

						'HIDE_NOT_AVAILABLE' => 'Y',
						'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
						'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
						'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
						'PRICE_CODE' => $arParams['~PRICE_CODE'],
						'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
						'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
						'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
						'BASKET_URL' => $arParams['BASKET_URL'],
						'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
						'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
						'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
						'USE_PRODUCT_QUANTITY' => 'N',
						'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
						'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],

						'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
						'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
						'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

						'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
						'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),

						// 'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
						"DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
						"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],

						'IS_USE_CART' => $arParams['IS_USE_CART'],
						'PRICE_PROP' => $arParams['PRICE_PROP'],
						'DISCOUNT_PROP' => $arParams['DISCOUNT_PROP'],
						'CURRENCY_PROP' => $arParams['CURRENCY_PROP'],
						'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
						'SHOW_PARENT_DESCR' => 'Y',

						'TEMPLATE_VIEW' => $arParams['TEMPLATE_VIEW'],
						// 'GRID_RESPONSIVE_SETTINGS' => $arParams['~GRID_RESPONSIVE_SETTINGS'],
						'USE_VOTE_RATING' => $arParams['LIST_USE_VOTE_RATING'],
						'VOTE_DISPLAY_AS_RATING' => $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'],
						'SHOW_RATING' => $arParams['SHOW_RATING'],

						'USE_GIFTS' => $arParams['USE_GIFTS'],
						'USE_FAVORITE' => $arParams['USE_FAVORITE'],
						'FAVORITE_COUNT_PROP' => $arParams['FAVORITE_COUNT_PROP'],
						'SHOW_ARTNUMBER' => $arParams['SHOW_ARTNUMBER'],
						'ARTNUMBER_PROP' => $arParams['ARTNUMBER_PROP'],
						'OFFER_ARTNUMBER_PROP' => $arParams['OFFER_ARTNUMBER_PROP'],
						'OFFER_TREE_DROPDOWN_PROPS' => $arParams['OFFER_TREE_DROPDOWN_PROPS'],
						'RS_LAZY_IMAGES_USE' => $arParams['RS_LAZY_IMAGES_USE'],
						'PRODUCT_PREVIEW' => $arParams['PRODUCT_PREVIEW'],

						'RS_LIST_SECTION' => 'l_section',
						'RS_LIST_SECTION_SHOW_TITLE' => 'Y',
						'RS_LIST_SECTION_TITLE' => $arParams['GIFTS_SECTION_LIST_BLOCK_TITLE'] ?: \Bitrix\Main\Localization\Loc::getMessage('CT_GIFTS_SECTION_LIST_BLOCK_TITLE_DEFAULT'),
					),
					$component,
					array("HIDE_ICONS" => "Y")
				);
			}

			if (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N')
			{
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					"light",
					array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
						"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
						"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
						"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
						"PROPERTY_CODE" => (isset($arParams["LIST_PROPERTY_CODE"]) ? $arParams["LIST_PROPERTY_CODE"] : []),
						"PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
						"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
						"BASKET_URL" => $arParams["BASKET_URL"],
						"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
						"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
						"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
						"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
						"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_FILTER" => $arParams["CACHE_FILTER"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
						"PAGE_ELEMENT_COUNT" => 0,
						"PRICE_CODE" => $arParams["~PRICE_CODE"],
						"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
						"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

						"SET_TITLE" => "N",
						"SET_BROWSER_TITLE" => "N",
						"SET_META_KEYWORDS" => "N",
						"SET_META_DESCRIPTION" => "N",
						"SET_LAST_MODIFIED" => "N",
						"ADD_SECTIONS_CHAIN" => "N",

						"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
						"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
						"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
						"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
						"PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"]) ? $arParams["PRODUCT_PROPERTIES"] : []),

						"OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"]) ? $arParams["OFFERS_CART_PROPERTIES"] : []),
						"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
						"OFFERS_PROPERTY_CODE" => (isset($arParams["LIST_OFFERS_PROPERTY_CODE"]) ? $arParams["LIST_OFFERS_PROPERTY_CODE"] : []),
						"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
						"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
						"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
						"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
						"OFFERS_LIMIT" => (isset($arParams["LIST_OFFERS_LIMIT"]) ? $arParams["LIST_OFFERS_LIMIT"] : 0),

						"SECTION_ID" => $intSectionID,
						"SECTION_CODE" => "",
						"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
						"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
						'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
						'CURRENCY_ID' => $arParams['CURRENCY_ID'],
						'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
						'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

						'LABEL_PROP' => $arParams['LABEL_PROP'],
						'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
						'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
						'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
						'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
						'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
						'PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
							\Bitrix\Iblock\Component\ElementList::predictRowVariants(
								$arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'],
								$arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT']
							)
						),
						'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
						'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
						'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
						'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
						'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

						"DISPLAY_TOP_PAGER" => 'N',
						"DISPLAY_BOTTOM_PAGER" => 'N',
						"HIDE_SECTION_DESCRIPTION" => "Y",

						"RCM_TYPE" => isset($arParams['BIG_DATA_RCM_TYPE']) ? $arParams['BIG_DATA_RCM_TYPE'] : '',
						"SHOW_FROM_SECTION" => 'Y',

						'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
						'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
						'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
						'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
						'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
						'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
						'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
						'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
						'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
						'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
						'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
						'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
						'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
						'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
						'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
						'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
						'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

						'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
						'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
						'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

						'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
						'ADD_TO_BASKET_ACTION' => $basketAction,
						'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
						'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
						'COMPARE_NAME' => $arParams['COMPARE_NAME'],
						'USE_COMPARE_LIST' => 'Y',
						'BACKGROUND_IMAGE' => '',
						'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),

						// 'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
						"DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
						"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],

						'IS_USE_CART' => $arParams['IS_USE_CART'],
						'PRICE_PROP' => $arParams['PRICE_PROP'],
						'DISCOUNT_PROP' => $arParams['DISCOUNT_PROP'],
						'CURRENCY_PROP' => $arParams['CURRENCY_PROP'],
						'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
						'SHOW_PARENT_DESCR' => 'Y',

						'TEMPLATE_VIEW' => $arParams['TEMPLATE_VIEW'],
						// 'GRID_RESPONSIVE_SETTINGS' => $arParams['~GRID_RESPONSIVE_SETTINGS'],
						'USE_VOTE_RATING' => $arParams['LIST_USE_VOTE_RATING'],
						'VOTE_DISPLAY_AS_RATING' => $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'],
						'SHOW_RATING' => $arParams['SHOW_RATING'],

						'USE_GIFTS' => $arParams['USE_GIFTS'],
						'USE_FAVORITE' => $arParams['USE_FAVORITE'],
						'FAVORITE_COUNT_PROP' => $arParams['FAVORITE_COUNT_PROP'],
						'SHOW_ARTNUMBER' => $arParams['SHOW_ARTNUMBER'],
						'ARTNUMBER_PROP' => $arParams['ARTNUMBER_PROP'],
						'OFFER_ARTNUMBER_PROP' => $arParams['OFFER_ARTNUMBER_PROP'],
						'OFFER_TREE_DROPDOWN_PROPS' => $arParams['OFFER_TREE_DROPDOWN_PROPS'],
						'RS_LAZY_IMAGES_USE' => $arParams['RS_LAZY_IMAGES_USE'],
						'PRODUCT_PREVIEW' => $arParams['PRODUCT_PREVIEW'],

						'RS_LIST_SECTION' => 'l_section',
						'RS_LIST_SECTION_SHOW_TITLE' => 'Y',
						'RS_LIST_SECTION_TITLE' => GetMessage('CATALOG_PERSONAL_RECOM'),
					),
					$component
				);
			}

			if (
				Loader::includeModule('catalog')
				&& (!isset($arParams['LIST_SHOW_VIEWED']) || $arParams['LIST_SHOW_VIEWED'] != 'N')
			)
			{
				$APPLICATION->IncludeComponent(
					'bitrix:catalog.products.viewed',
					'light',
					array(
						'IBLOCK_MODE' => 'single',
						'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
						'IBLOCK_ID' => $arParams['IBLOCK_ID'],
						'ELEMENT_SORT_FIELD' => $arParams['ELEMENT_SORT_FIELD'],
						'ELEMENT_SORT_ORDER' => $arParams['ELEMENT_SORT_ORDER'],
						'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
						'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],
						'PROPERTY_CODE_'.$arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE'],
						'PROPERTY_CODE_'.$arRecomData['OFFER_IBLOCK_ID'] => $arParams['LIST_OFFERS_PROPERTY_CODE'],
						'PROPERTY_CODE_MOBILE'.$arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE_MOBILE'],
						'BASKET_URL' => $arParams['BASKET_URL'],
						'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'].'_cpv',
						'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
						'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
						'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
						'CACHE_TYPE' => $arParams['CACHE_TYPE'],
						'CACHE_TIME' => $arParams['CACHE_TIME'],
						'CACHE_FILTER' => $arParams['CACHE_FILTER'],
						'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
						'DISPLAY_COMPARE' => $arParams['USE_COMPARE'],
						'PRICE_CODE' => $arParams['~PRICE_CODE'],
						'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
						'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
						'PAGE_ELEMENT_COUNT' => $arParams['VIEWED_SECTION_LIST_PAGE_ELEMENT_COUNT'],

						"SET_TITLE" => "N",
						"SET_BROWSER_TITLE" => "N",
						"SET_META_KEYWORDS" => "N",
						"SET_META_DESCRIPTION" => "N",
						"SET_LAST_MODIFIED" => "N",
						"ADD_SECTIONS_CHAIN" => "N",

						'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
						'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
						'ADD_PROPERTIES_TO_BASKET' => (isset($arParams['ADD_PROPERTIES_TO_BASKET']) ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
						'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES']) ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
						'CART_PROPERTIES_'.$arParams['IBLOCK_ID'] => $arParams['PRODUCT_PROPERTIES'],
						'CART_PROPERTIES_'.$arRecomData['OFFER_IBLOCK_ID'] => $arParams['OFFERS_CART_PROPERTIES'],
						'ADDITIONAL_PICT_PROP_'.$arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP'],
						'ADDITIONAL_PICT_PROP_'.$arRecomData['OFFER_IBLOCK_ID'] => $arParams['OFFER_ADD_PICT_PROP'],

						'SHOW_FROM_SECTION' => 'N',
						'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
						'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
						'CURRENCY_ID' => $arParams['CURRENCY_ID'],
						'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
						'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],

						'LABEL_PROP_'.$arParams['IBLOCK_ID'] => $arParams['LABEL_PROP'],
						'LABEL_PROP_MOBILE_'.$arParams['IBLOCK_ID'] => $arParams['LABEL_PROP_MOBILE'],
						'LABEL_PROP_POSITION' => $arParams['LIST_LABEL_PROP_POSITION'],
						// 'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
						'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
						'PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
							\Bitrix\Iblock\Component\ElementList::predictRowVariants(
								$arParams['VIEWED_SECTION_LIST_PAGE_ELEMENT_COUNT'],
								$arParams['VIEWED_SECTION_LIST_PAGE_ELEMENT_COUNT']
							)
						),
						'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
						'ENLARGE_PROP_'.$arParams['IBLOCK_ID'] => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
						'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
						'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
						'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

						'OFFER_TREE_PROPS_'.$arRecomData['OFFER_IBLOCK_ID'] => $arParams['OFFER_TREE_PROPS'],
						'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
						'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
						'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
						'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
						'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
						'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
						'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
						'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
						'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
						'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
						'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
						'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
						'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
						'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
						'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

						'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
						'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
						'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

						'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
						'ADD_TO_BASKET_ACTION' => (isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : array()),
						'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
						'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
						'COMPARE_NAME' => $arParams['COMPARE_NAME'],


						'SITE_LOCATION_ID' => SITE_LOCATION_ID,
						"FILL_ITEM_ALL_PRICES" => (is_array($arParams['PRICE_CODE']) && count($arParams['PRICE_CODE']) > 1) ? 'Y' :  $arParams['FILL_ITEM_ALL_PRICES'],

						"AJAX_ID" => $arParams['AJAX_ID'],
						"DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
						"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
						'COMPOSITE_FRAME' => 'Y',

						'IS_USE_CART' => $arParams['IS_USE_CART'],
						'PRICE_PROP' => $arParams['PRICE_PROP'],
						'DISCOUNT_PROP' => $arParams['DISCOUNT_PROP'],
						'CURRENCY_PROP' => $arParams['CURRENCY_PROP'],
						'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
						'SHOW_PARENT_DESCR' => 'Y',

						'TEMPLATE_VIEW' => $arParams['TEMPLATE_VIEW'],
						'GRID_RESPONSIVE_SETTINGS' => $arParams['~GRID_RESPONSIVE_SETTINGS'],
						'USE_VOTE_RATING' => $arParams['LIST_USE_VOTE_RATING'],
						'VOTE_DISPLAY_AS_RATING' => $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'],
						'SHOW_RATING' => $arParams['SHOW_RATING'],

						'USE_GIFTS' => $arParams['USE_GIFTS'],
						'USE_FAVORITE' => $arParams['USE_FAVORITE'],
						'FAVORITE_COUNT_PROP' => $arParams['FAVORITE_COUNT_PROP'],
						'SHOW_ARTNUMBER' => $arParams['SHOW_ARTNUMBER'],
						'ARTNUMBER_PROP' => $arParams['ARTNUMBER_PROP'],
						'OFFER_ARTNUMBER_PROP' => $arParams['OFFER_ARTNUMBER_PROP'],
						'OFFER_TREE_DROPDOWN_PROPS' => $arParams['OFFER_TREE_DROPDOWN_PROPS'],
						'RS_LAZY_IMAGES_USE' => $arParams['RS_LAZY_IMAGES_USE'],
						'BACKGROUND_COLOR' => $arParams['LIST_BACKGROUND_COLOR'],
						'PRODUCT_PREVIEW' => $arParams['PRODUCT_PREVIEW'],

						'RS_LIST_SECTION' => 'l_section',
						'RS_LIST_SECTION_SHOW_TITLE' => 'Y',
						'RS_LIST_SECTION_TITLE' => GetMessage('CATALOG_VIEWED'),

					),
					$component
				);
			}
		}
	}
}

$APPLICATION->SetPageProperty("hide_section", "Y");
$APPLICATION->SetPageProperty('wide_page', 'N');
$APPLICATION->SetPageProperty('hide_inner_sidebar', $bSidebarInner ? 'N' : 'Y');
$APPLICATION->SetPageProperty('hide_outer_sidebar', $bSidebarOuter ? 'N' : 'Y');
