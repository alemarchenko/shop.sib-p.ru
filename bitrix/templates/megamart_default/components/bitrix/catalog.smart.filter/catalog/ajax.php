<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main;
use \Bitrix\Main\Web\Uri;

$notInSection = false;
$hideNotAvailable = false;

if (intval($this->__component->SECTION_ID) < 1)
{
    $FILTER_NAME = (string)$arParams["FILTER_NAME"];

    global ${$FILTER_NAME};
    if(!is_array(${$FILTER_NAME}))
        ${$FILTER_NAME} = array();

    $arFilter = $this->__component->makeFilter($FILTER_NAME);

    unset($arFilter['INCLUDE_SUBSECTIONS']);
    unset($arFilter['FACET_OPTIONS']);

    $resElements = CIBlockElement::GetList(array(), $arFilter, array('ID'), false);
    $arResult["ELEMENT_COUNT"] = $resElements->SelectedRowsCount();
}

if ($arParams['INSTANT_RELOAD'] == 'Y' && $arParams['TARGET_ID'])
{
	$arResult['COMPONENT_CONTAINER_ID'] = $arParams['TARGET_ID'];
	$arResult['INSTANT_RELOAD'] = true;
}

$APPLICATION->RestartBuffer();
unset($arResult["COMBO"]);

if (!empty($arResult))
{
    $arResult['JS'] = Main\Page\Asset::getInstance()->getJs();
}

if ($arParams['FILTER_USE_HIDE_NOT_AVAILABLE'] == 'Y')
{
	$request =  \Bitrix\Main\Context::getCurrent()->getRequest();
	if ($request->get('hide_not_available') === 'Y')
	{
		$hideNotAvailable = true;

		$urlKeys = [
			'FILTER_URL',
			'FILTER_AJAX_URL',
			'SEF_SET_FILTER_URL'
		];
	
		if ($arParams['SEF_MODE'] === 'Y')
		{
			$deleteParameters = ['#SECTION_CODE_PATH#', '#SECTION_CODE#', '#SECTION_ID#'];
			$sefRule = str_replace($deleteParameters, '.*', $arParams['SEF_RULE']);

			if (preg_match('/(.*)#SMART_FILTER_PATH#(.*)/', $sefRule, $rulePaths))
			{
				$filterPath = str_replace('/', '\\/', $rulePaths[1]);
				$applyPath = str_replace('/', '\\/', $rulePaths[2]);
	
				foreach ($urlKeys as $key)
				{
					$arResult[$key] = preg_replace(
						'/('.$filterPath.')(.*)('.$applyPath.')/',
						'$1/$2/hide_not_available$3',
						$arResult[$key]
					);

					$arResult[$key] = str_replace('/clear/', '', $arResult[$key]);
				}
			}
		}
	}
}

if ($hideNotAvailable || $notInSection)
{
	$FILTER_NAME = (string)$arParams["FILTER_NAME"];

    global ${$FILTER_NAME};
    if(!is_array(${$FILTER_NAME}))
        ${$FILTER_NAME} = array();

	$arFilter = $this->__component->makeFilter($FILTER_NAME);
	
	if ($notInSection)
	{
		unset($arFilter['INCLUDE_SUBSECTIONS']);
		unset($arFilter['FACET_OPTIONS']);
	}

	if ($hideNotAvailable)
	{
		$arFilter['AVAILABLE'] = 'Y';
	}

	$arResult["ELEMENT_COUNT"] = CIBlockElement::GetList(array(), $arFilter, array(), false);
}


// echo Main\Web\Json::encode($arResult);
echo CUtil::PHPToJSObject($arResult, true);