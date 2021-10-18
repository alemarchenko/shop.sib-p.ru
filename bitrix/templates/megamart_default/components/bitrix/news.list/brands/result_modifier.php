<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

$arViewModeList = array('SLIDER', 'ABC');

if ($arParams['RS_TEMPLATE'] == 'FROM_WIDGET' && isset($arParams['RS_TEMPLATE_FROM_WIDGET']))
{
	$arParams['RS_TEMPLATE'] = isset($arParams['RS_TEMPLATE_FROM_WIDGET']);
}

$arParams['RS_TEMPLATE'] = in_array($arParams['RS_TEMPLATE'], $arViewModeList)
	? $arParams['RS_TEMPLATE']
	: 'SLIDER';

switch ($arParams['RS_TEMPLATE'])
{
	case 'ABC':
		$arCharsDigital = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		$arCharsEn = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$arCharsLang = explode(' ', getMessage('RS_MM_BNL_BRANDS_LETTERS_LANG'));
		foreach ($arResult['ITEMS'] as $key => $item)
		{
			$cLetter = mb_substr($item['NAME'], 0, 1);
			if (in_array($cLetter, $arCharsEn))
			{
				$arResult['LETTERS']['LETTER_ENG'][$cLetter][] = $key;
			}
			elseif (in_array($cLetter, $arCharsLang))
			{
				$arResult['LETTERS']['LETTER_LANG'][$cLetter][] = $key;
			}
			elseif (in_array($cLetter, $arCharsDigital))
			{
				$arResult['LETTERS']['DIGITAL'][$cLetter][] = $key;
			}
			else
			{
				$arResult['LETTERS']['OTHER'][$cLetter][] = $key;
			}
		}
		unset($key, $item);

		if (is_array($arResult['LETTERS']['LETTER_ENG']))
		{
			ksort($arResult['LETTERS']['LETTER_ENG']);
		}
		if (is_array($arResult['LETTERS']['LETTER_LANG']))
		{
			ksort($arResult['LETTERS']['LETTER_LANG']);
		}
		if (is_array($arResult['LETTERS']['DIGITAL']))
		{
			ksort($arResult['LETTERS']['DIGITAL']);
		}
		break;

	case 'SLIDER':
	default:
		// if ($arParams['USE_OWL'] == 'Y')
		// {
			$arResponsiveOptions = array();
			$arGridBreakpoints = array(
				'xxs' => 0,
				'xs' => 380,
				'sm' => 576,
				'md' => 768,
				'lg' => 992,
				'xl' => 1200,
			);

			if (isset($arParams['~SLIDER_RESPONSIVE_SETTINGS']))
			{
				$arResponsiveOptions = CUtil::JsObjectToPhp($arParams['~SLIDER_RESPONSIVE_SETTINGS']);
			}
			else
			{
				$arResponsiveOptions =  array(
					0 => array('items' => 1),
					380 => array('items' =>2),
					576 => array('items' => 3),
					768 => array('items' => 4),
					992 => array('items' => 5),
					1200 => array('items' => 5)
				);
			}


			$sSliderClasses = 'row row-borders';
			foreach ($arResponsiveOptions as $nBreakpoint => $arOptions)
			{
				$sGridBreakpoint = array_search($nBreakpoint, $arGridBreakpoints);
				if ($sGridBreakpoint !== false)
				{
					$sSliderClasses .= ' show-items-'.$sGridBreakpoint.'-'.$arOptions['items'];
				}
			}

			$arResult['RS_SLIDER_CLASSES'] = $sSliderClasses;
			$arResult['RS_SLIDER_OPTIONS'] = array(
				'items' => 4,
				'responsive' => $arResponsiveOptions,
				'margin' => 1,
				'loop' => true
			);

			if (isset($arParams['OWL_CHANGE_DELAY']) && (int) $arParams['OWL_CHANGE_DELAY'] > 0)
			{
				$arResult['RS_SLIDER_OPTIONS']['autoplay'] = true;
				$arResult['RS_SLIDER_OPTIONS']['autoplayTimeout'] = $arParams['OWL_CHANGE_DELAY'];

				if (isset($arParams['OWL_CHANGE_SPEED']) && (int) $arParams['OWL_CHANGE_SPEED'] >= 0)
				{
					$arResult['RS_SLIDER_OPTIONS']['autoplaySpeed'] = $arParams['OWL_CHANGE_SPEED'];
					$arResult['RS_SLIDER_OPTIONS']['smartSpeed'] = $arParams['OWL_CHANGE_SPEED'];
				}
				else
				{
					$arResult['RS_SLIDER_OPTIONS']['autoplaySpeed'] = 2000;
					$arResult['RS_SLIDER_OPTIONS']['smartSpeed'] = 2000;
				}
			}
		// }
		break;
}

