<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

use \Bitrix\Main\Loader;

$component = $this->getComponent();

$arParams['DETAIL_TEMPLATE'] = !empty($arParams['DETAIL_TEMPLATE']) ?  $arParams['DETAIL_TEMPLATE'] : 'catalog';
$arParams['LIST_TEMPLATE'] = !empty($arParams['LIST_TEMPLATE']) ?  $arParams['LIST_TEMPLATE'] : 'catalog';
$arParams['USE_SORTER'] = $arParams['USE_SORTER'] === 'N' ?  $arParams['USE_SORTER'] : 'Y';
$arParams['SHOW_SECTIONS_LIST'] = $arParams['SHOW_SECTIONS_LIST'] === 'N' ?  $arParams['SHOW_SECTIONS_LIST'] : 'Y';

if (empty($arParams['AJAX_ID']) || strlen($arParams['AJAX_ID']) < 1)
{
	 $arParams['AJAX_ID'] = CAjax::GetComponentID($component->componentName, $component->componentTemplate, $arParams['AJAX_OPTION_ADDITIONAL']);
}

$arParams['LIST_ROWS_COUNT'] = isset($arParams['LIST_ROWS_COUNT']) ? $arParams['LIST_ROWS_COUNT'] : 4;
$arParams['USE_WIDGET_PARAMETERS'] = isset($arParams['USE_WIDGET_PARAMETERS']) ? $arParams['USE_WIDGET_PARAMETERS'] : 'Y';


if (
    $arParams['USE_WIDGET_PARAMETERS'] == 'Y' &&
    defined('RS_MM_CATALOG_SECTION_TYPE')
)
{
    switch(RS_MM_CATALOG_SECTION_TYPE)
    {
        case 'type1':

            $arParams["SIDEBAR_INNER_SECTION_SHOW"] = "Y";
            $arParams["SIDEBAR_OUTER_SECTION_SHOW"] = "Y";

            $nLineCount = 3;
            $nRowsCount = $arParams['LIST_ROWS_COUNT'];

            $arTemplateRows = array(
                'VARIANT' => '2',
                'BIG_DATA' => false
            );

            $arResult['FILTER_VIEW_MODE'] = 'VERTICAL';
            break;

        case 'type3':
            $arParams["SIDEBAR_INNER_SECTION_SHOW"] = "N";
            $arParams["SIDEBAR_OUTER_SECTION_SHOW"] = "N";

            $nLineCount = 10;
            $nRowsCount = floor(((int)$arParams['LIST_ROWS_COUNT']) / 2);
            $arTemplateRows = array(
                'VARIANT' => '10',
                'BIG_DATA' => false
            );
            
            $arResult['FILTER_VIEW_MODE'] = 'HORIZONTAL';
            break;
            

        case 'type2':
        default:
            
            $arParams["SIDEBAR_INNER_SECTION_SHOW"] = "N";
            $arParams["SIDEBAR_OUTER_SECTION_SHOW"] = "Y";

            $nLineCount = 4;
            $nRowsCount = $arParams['LIST_ROWS_COUNT'];

            $arTemplateRows = array(
                'VARIANT' => '3',
                'BIG_DATA' => false
            );
            
            $arResult['FILTER_VIEW_MODE'] = 'VERTICAL';

            break;
    }

    $arParams['PAGE_ELEMENT_COUNT'] = $nRowsCount * $nLineCount;
    $arParams['SORTER_OUTPUT_OF_DEFAULT'] = $arParams['PAGE_ELEMENT_COUNT'];
    $arParams['SORTER_OUTPUT_OF'] = array(
        $arParams['PAGE_ELEMENT_COUNT'],
        $arParams['PAGE_ELEMENT_COUNT'] * 1.5,
        $arParams['PAGE_ELEMENT_COUNT'] * 2,
        $arParams['PAGE_ELEMENT_COUNT'] * 2.5,
    );

    $arTemplateRows = array_fill(
        0,
        $nRowsCount,
        $arTemplateRows
    );

    $arParams['LIST_PRODUCT_ROW_VARIANTS'] = Bitrix\Main\Web\Json::encode($arTemplateRows);
}
else
{
    $arResult['FILTER_VIEW_MODE'] = isset($arParams['FILTER_VIEW_MODE'])  ? $arParams['FILTER_VIEW_MODE'] : "VERTICAL";
}

if (isset($arParams['FILTER_USE_HIDE_NOT_AVAILABLE']))
{
	$arParams['FILTER_USE_HIDE_NOT_AVAILABLE'] = $arParams['FILTER_USE_HIDE_NOT_AVAILABLE'] === 'Y' ? 'Y' : 'N';
}
else
{
	$arParams['FILTER_USE_HIDE_NOT_AVAILABLE'] = 'Y';
}

if ($arParams['FILTER_USE_HIDE_NOT_AVAILABLE'] === 'Y')
{
	$request = \Bitrix\Main\Context::getCurrent()->getRequest();
	$hideNotAvailable = false;

	if ($request->get('hide_not_available'))
	{
		$hideNotAvailable = $request->get('hide_not_available') === 'Y';
	}
	elseif ($arParams['SEF_MODE'] === 'Y' && isset($arResult['URL_TEMPLATES']['smart_filter']))
	{
		$deleteParameters = ['#SECTION_CODE_PATH#', '#SECTION_CODE#', '#SECTION_ID'];
		$filterSefRule = str_replace($deleteParameters, '', $arResult['URL_TEMPLATES']['smart_filter']);

		if (preg_match('/(.*)\/#SMART_FILTER_PATH#\/(.*)/', $filterSefRule, $rulePaths))
		{
			$filterPath = str_replace('/', '\\/', $rulePaths[1]);

			if (preg_match('/'.$filterPath.'.*\/hide_not_available\//', $request->getRequestUri(), $matches))
			{
				$hideNotAvailable = true;
			}

			unset($filterPath, $matches);
		}

		unset(
			$deleteParameters,
			$filterSefRule,
			$rulePaths
		);
	}

    $arParams['HIDE_NOT_AVAILABLE'] = $arParams['OFFERS_HIDE_NOT_AVAILABLE'] = ($hideNotAvailable ? 'Y' : ($arParams['HIDE_NOT_AVAILABLE'] == 'L' ? 'L' : 'N'));
    $arParams['OFFERS_HIDE_NOT_AVAILABLE'] = $hideNotAvailable ? 'Y' : $arParams['HIDE_NOT_AVAILABLE '];

	unset(
		$request,
		$hideNotAvailable
	);
}
