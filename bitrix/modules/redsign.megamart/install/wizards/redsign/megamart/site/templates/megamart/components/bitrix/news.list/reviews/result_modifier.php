<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

if ($arParams['USE_OWL'] == 'Y') {
    $arResponsiveOptions = array();
	$arGridBreakpoints = array(
		'xxs' => 0,
		'xs' => 380,
		'sm' => 576,
		'md' => 768,
		'lg' => 992,
		'xl' => 1200,
	);

    if (isset($arParams['~SLIDER_RESPONSIVE_SETTINGS'])) {
        $arResponsiveOptions = CUtil::JsObjectToPhp($arParams['~SLIDER_RESPONSIVE_SETTINGS']);
    } else {
        $arResponsiveOptions =  array(
			0 => array('items' => 1),
			380 => array('items' =>2),
			576 => array('items' => 3),
			768 => array('items' => 4),
			992 => array('items' => 5),
			1200 => array('items' => 5)
		);
    }


	$sSliderClasses = 'slider-before slider-before--m-1';
	foreach ($arResponsiveOptions as $nBreakpoint => $arOptions) {
		$sGridBreakpoint = array_search($nBreakpoint, $arGridBreakpoints);
		if ($sGridBreakpoint !== false) {

			if ($nBreakpoint == 0) {
				$sSliderClasses .= ' show-items-'.$arOptions['items'];
			} else {
				$sSliderClasses .= ' show-items-'.$sGridBreakpoint.'-'.$arOptions['items'];
			}
		}
	}

	$arResult['RS_SLIDER_CLASSES'] = $sSliderClasses;
    $arResult['RS_SLIDER_OPTIONS'] = array(
        'items' => 4,
        'responsive' => $arResponsiveOptions,
		'margin' => 0,
		'loop' => true
    );

	if (isset($arParams['OWL_CHANGE_DELAY']) && (int) $arParams['OWL_CHANGE_DELAY'] > 0) {
		$arResult['RS_SLIDER_OPTIONS']['autoplay'] = true;
		$arResult['RS_SLIDER_OPTIONS']['autoplayTimeout'] = $arParams['OWL_CHANGE_DELAY'];

		if (isset($arParams['OWL_CHANGE_SPEED']) && (int) $arParams['OWL_CHANGE_SPEED'] >= 0) {
			$arResult['RS_SLIDER_OPTIONS']['autoplaySpeed'] = $arParams['OWL_CHANGE_SPEED'];
			$arResult['RS_SLIDER_OPTIONS']['smartSpeed'] = $arParams['OWL_CHANGE_SPEED'];
		} else {
			$arResult['RS_SLIDER_OPTIONS']['autoplaySpeed'] = 2000;
			$arResult['RS_SLIDER_OPTIONS']['smartSpeed'] = 2000;
		}
	}
}
