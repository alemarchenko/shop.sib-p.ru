<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arParams['TITLE_TAB_GENERAL'] =  array(
    'TAB' => 'TAB_GENERAL',
    'TYPE' => 'TITLE',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_TAB_GENERAL'),
);

$arParams['CONTAINER_MAX_WIDTH'] = array(
    'TAB' => 'TAB_GENERAL',
    'TYPE' => 'RADIO',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_SITE_WIDTH'),
    'CONTROL_NAME' => 'cw_container_max_width',
    'DEFAULT' => 'cw_normal',
    'RELOAD' => 'Y',
    'VALUES' => array(
        'SITE_WIDTH_1336PX' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_SITE_WIDTH.SITE_WIDTH_1336PX'),
            'CONTROL_ID' => 'cw_normal',
            'HTML_VALUE' => 'cw_normal',
        ),
        'SITE_WIDTH_1510PX' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_SITE_WIDTH.SITE_WIDTH_1510PX'),
            'CONTROL_ID' => 'cw_1510',
            'HTML_VALUE' => 'cw_1510',
        )
    ),
    'VIEW' => 'buttons',
);

$arParams['COLOR'] = array(
    'TAB' => 'TAB_GENERAL',
    'TYPE' => 'COLORPICKER',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_COLOR'),
    'CONTROL_ID' => 'color',
    'CONTROL_NAME' => 'color',
    'MULTIPLE' => 'Y',
    'VALUES' => array(
        'COLOR_1' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_COLOR.COLOR_1'),
            'CONTROL_ID' => 'color11',
            'CONTROL_NAME' => 'color11',
            'HTML_VALUE' => 'ff5605',
            'DEFAULT' => 'ff5605',
            'MACROS' => 'COLOR_1_1',
			'TUNING_COLOR' => 'Y',
        ),
        'COLOR_2' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_COLOR.COLOR_2'),
            'CONTROL_ID' => 'color12',
            'CONTROL_NAME' => 'color12',
            'HTML_VALUE' => 'ffffff',
            'DEFAULT' => 'ffffff',
            'MACROS' => 'COLOR_1_2',
        ),
    ),
    'SETS' => array(
        'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_COLOR.SETS'),
        'VALUES' => array(
            'SET_1' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_1',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#ff5500',
                'VALUES' => array(
                    'COLOR_1' => 'ff5500',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_2' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_2',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#f5c100',
                'VALUES' => array(
                    'COLOR_1' => 'f5c100',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_3' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_3',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#689400',
                'VALUES' => array(
                    'COLOR_1' => '689400',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_4' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_4',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#313192',
                'VALUES' => array(
                    'COLOR_1' => '313192',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_5' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_5',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#9b007b',
                'VALUES' => array(
                    'COLOR_1' => '9b007b',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_6' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_6',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#d00000',
                'VALUES' => array(
                    'COLOR_1' => 'd00000',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_7' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_7',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#1d1f23',
                'VALUES' => array(
                    'COLOR_1' => '1d1f23',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_8' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_8',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#000000',
                'VALUES' => array(
                    'COLOR_1' => '000000',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_9' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_9',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#ff803b',
                'VALUES' => array(
                    'COLOR_1' => 'ff803b',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_10' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_10',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#ffdc0c',
                'VALUES' => array(
                    'COLOR_1' => 'ffdc0c',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_11' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_11',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#8cc900',
                'VALUES' => array(
                    'COLOR_1' => '8cc900',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_12' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_12',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#4545c4',
                'VALUES' => array(
                    'COLOR_1' => '4545c4',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_13' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_13',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#d600ad',
                'VALUES' => array(
                    'COLOR_1' => 'd600ad',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_14' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_14',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#ff5f60',
                'VALUES' => array(
                    'COLOR_1' => 'ff5f60',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_15' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_15',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#dee2e7',
                'VALUES' => array(
                    'COLOR_1' => 'dee2e7',
                    'COLOR_2' => 'ffffff',
                ),
            ),
            'SET_16' => array(
                'NAME' => '',
                'CONTROL_ID' => 'set_16',
                'CONTROL_NAME' => '',
                'BACKGROUND' => '#ffffff',
                'VALUES' => array(
                    'COLOR_1' => 'ffffff',
                    'COLOR_2' => 'ffffff',
                ),
            ),
        ),
    ),
);

$arFontValues = array(
	'SYSTEM' => array(
		'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_FAMILY.SYSTEM'),
		'CONTROL_ID' => 'ff-system',
		'HTML_VALUE' => 'system',
	),
	'OPEN_SANS' => array(
		'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_FAMILY.OPEN_SANS'),
		'CONTROL_ID' => 'ff-open-sans',
		'HTML_VALUE' => 'open_sans',
	),
	'PT_SANS' => array(
		'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_FAMILY.PT_SANS'),
		'CONTROL_ID' => 'ff-pt-sans',
		'HTML_VALUE' => 'pt_sans',
	),
	'ROBOTO' => array(
		'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_FAMILY.ROBOTO'),
		'CONTROL_ID' => 'ff-roboto',
		'HTML_VALUE' => 'roboto',
	),
);

if (!\Bitrix\Main\Config\Option::get('redsign.megamart', 'GLOBAL_GOOGLE_FONT_NAME') || defined("RS_MM_USE_FONT_FAMILY") && RS_MM_USE_FONT_FAMILY == 'custom')
{
	$arFontValues['CUSTOM'] = array(
		'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_FAMILY.CUSTOM'),
		'CONTROL_ID' => 'ff-custom',
		'HTML_VALUE' => 'custom',
	);
}

$arParams['FONT_FAMILY'] = array(
    'TAB' => 'TAB_GENERAL',
    'TYPE' => 'SELECTBOX',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_FAMILY'),
    'CONTROL_ID' => 'font_family',
    'CONTROL_NAME' => 'font_family',
    'DEFAULT' => 'system',
    'RELOAD' => 'Y',
    'VALUES' => $arFontValues
);

$arParams['FONT_SIZE'] = array(
    'TAB' => 'TAB_GENERAL',
    'TYPE' => 'RADIO',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_SIZE'),
    'CONTROL_NAME' => 'font_size',
    'DEFAULT' => 'medium',
    'RELOAD' => 'Y',
    'VALUES' => array(
        'SMALL' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_SIZE.SMALL'),
            'CONTROL_ID' => 'font_size_small',
            'HTML_VALUE' => 'small',
        ),
        'MEDIUM' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_SIZE.MEDIUM'),
            'CONTROL_ID' => 'font_size_medium',
            'HTML_VALUE' => 'medium',
        ),
        'BIG' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FONT_SIZE.BIG'),
            'CONTROL_ID' => 'font_size_big',
            'HTML_VALUE' => 'big',
        ),
    ),
    'VIEW' => 'buttons',
);

$arParams['POPUP_TYPE'] = array(
    'TAB' => 'TAB_GENERAL',
    'TYPE' => 'RADIO',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_POPUP_TYPE'),
    'CONTROL_NAME' => 'popup_type',
    'DEFAULT' => 'window',
    'RELOAD' => 'Y',
    'VALUES' => array(
        'TEMPLATE_1' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_POPUP_TYPE.POPUP'),
            'CONTROL_ID' => 'window',
            'HTML_VALUE' => 'window',
            'IMAGE' => '/include/tuning/img/popup_type_1.png',
        ),
        'TEMPLATE_2' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_POPUP_TYPE.PANEL'),
            'CONTROL_ID' => 'side',
            'HTML_VALUE' => 'side',
            'IMAGE' => '/include/tuning/img/popup_type_2.png',
        ),
    ),
    'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 6,
);

$arParams['SEARCH_POPUP'] = array(
    'TAB' => 'TAB_GENERAL',
    'TYPE' => 'RADIO',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_SEARCH_POPUP'),
    'CONTROL_NAME' => 'search_popup',
    'DEFAULT' => 'top',
    'RELOAD' => 'Y',
    'VALUES' => array(
        'TOP' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_SEARCH_POPUP.TOP'),
            'CONTROL_ID' => 'search_popup_top',
            'HTML_VALUE' => 'top',
            'IMAGE' => '/include/tuning/img/search_popup_top.png',
        ),
        'FUKK' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_SEARCH_POPUP.FULL'),
            'CONTROL_ID' => 'search_popup_full',
            'HTML_VALUE' => 'full',
            'IMAGE' => '/include/tuning/img/search_popup_full.png',
        )
    ),
    'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 6,
);

$arParams['CONTROL_PANEL'] = array(
    'TAB' => 'TAB_GENERAL',
    'TYPE' => 'RADIO',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_CONTROL_PANEL'),
    'CONTROL_NAME' => 'control_panel',
    'DEFAULT' => 'side',
    'RELOAD' => 'Y',
    'VALUES' => array(
        'TEMPLATE_1' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_CONTROL_PANEL.SIDE'),
            'CONTROL_ID' => 'control_panel_1',
            'HTML_VALUE' => 'side',
            'IMAGE' => '/include/tuning/img/control_panel_1.png',
        ),
        'TEMPLATE_2' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_CONTROL_PANEL.BOTTOM'),
            'CONTROL_ID' => 'control_panel_2',
            'HTML_VALUE' => 'bottom',
            'IMAGE' => '/include/tuning/img/control_panel_2.png',
        ),
        'TEMPLATE_3' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_CONTROL_PANEL.OFF'),
            'CONTROL_ID' => 'control_panel_3',
            'HTML_VALUE' => 'none',
            'IMAGE' => '/include/tuning/img/control_panel_3.png',
        ),
    ),
    'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 4,
);

$arParams['FOOTER_THEME'] = array(
    'TAB' => 'TAB_GENERAL',
    'TYPE' => 'RADIO',
    'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FOOTER_THEME'),
    'CONTROL_NAME' => 'footer_theme',
    'DEFAULT' => 'dark',
    'RELOAD' => 'Y',
    'VALUES' => array(
        'DARK' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FOOTER_THEME.DARK'),
            'CONTROL_ID' => 'footer_type_dark',
            'HTML_VALUE' => 'dark',
            'IMAGE' => '/include/tuning/img/footer_type_dark.png',
        ),
        'LIGHT' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FOOTER_THEME.LIGHT'),
            'CONTROL_ID' => 'footer_type_light',
            'HTML_VALUE' => 'light',
            'IMAGE' => '/include/tuning/img/footer_type_light.png',
        ),
        'TYPE2_DARK' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FOOTER_THEME.TYPE2_DARK'),
            'CONTROL_ID' => 'footer_type2_dark',
            'HTML_VALUE' => 'type2_dark',
            'IMAGE' => '/include/tuning/img/footer_type2_dark.png',
        ),
        'TYPE2_LIGHT' => array(
            'NAME' => Loc::getMessage('RS_MM_TUNING_PARAM_FOOTER_THEME.TYPE2_LIGHT'),
            'CONTROL_ID' => 'footer_type2_light',
            'HTML_VALUE' => 'type2_light',
            'IMAGE' => '/include/tuning/img/footer_type2_light.png',
        ),
    ),
    'VIEW' => 'images',
    'GRID_SIZE_VALUE' => 6,
);
