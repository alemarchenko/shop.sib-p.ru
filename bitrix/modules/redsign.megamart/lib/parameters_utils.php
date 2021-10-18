<?php

namespace Redsign\MegaMart;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ParametersUtils {

	public static function getSettingsScript($scriptName) {
		return '/bitrix/js/redsign.megamart/component_settings/'.$scriptName.'.js';
	}

	public static function getComponentTemplateList($componentName = '')
	{
		$arReturn = array();
		$arTemplateInfo = \CComponentUtil::GetTemplatesList($componentName);
		if (!empty($arTemplateInfo))
		{
			sortByColumn($arTemplateInfo, array('TEMPLATE' => SORT_ASC, 'NAME' => SORT_ASC));
			$arTemplateList = array();
			$arSiteTemplateList = array(
				'.default' => Loc::getMessage('RS.TEMPLATE_SITE_DEFAULT'),
			);
			$arTemplateID = array();
			foreach ($arTemplateInfo as &$template)
			{
				if ('' != $template["TEMPLATE"] && '.default' != $template["TEMPLATE"])
				{
					$arTemplateID[] = $template["TEMPLATE"];
				}
				if (!isset($template['TITLE']))
				{
					$template['TITLE'] = $template['NAME'];
				}
			}
			unset($template);

			if (!empty($arTemplateID))
			{
				$rsSiteTemplates = \CSiteTemplate::GetList(
					array(),
					array("ID" => $arTemplateID),
					array()
				);
				while ($arSitetemplate = $rsSiteTemplates->Fetch())
				{
					$arSiteTemplateList[$arSitetemplate['ID']] = $arSitetemplate['NAME'];
				}
			}

			foreach ($arTemplateInfo as &$template)
			{
				if (isset($arHiddenTemplates[$template['NAME']]))
				{
					continue;
				}
				$strDescr = $template["TITLE"].' ('.('' != $template["TEMPLATE"] && '' != $arSiteTemplateList[$template["TEMPLATE"]] ? $arSiteTemplateList[$template["TEMPLATE"]] : Loc::getMessage('RS.TEMPLATE_SITE_DEFAULT')).')';
				$arTemplateList[$template['NAME']] = $strDescr;
			}
			unset($template);
			$arReturn = $arTemplateList;
		}

		return $arReturn;
	}

	public static function addCommonParameters (&$arTemplateParameters, $arCurrentValues = array(), $arrParams = array())
	{
		$defaultValues = array('-' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_UNDEFINED'));

		$bIBlock = Loader::includeModule('iblock');

		if (is_array($arTemplateParameters))
		{
			/** if (!isset($arTemplateParameters['ADD_CONTAINER']))
			{
				$arTemplateParameters['ADD_CONTAINER'] = array(
					'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_ADD_CONTAINER'),
					'TYPE' => 'CHECKBOX',
					'VALUE' => 'Y',
					'DEFAULT' => 'Y',
					'REFRESH' => 'N',
				);
			}

			if (!isset($arTemplateParameters['ADD_SECTION']))
			{
				$arTemplateParameters['ADD_SECTION'] = array(
					'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_ADD_SECTION'),
					'TYPE' => 'CHECKBOX',
					'VALUE' => 'Y',
					'DEFAULT' => 'N',
					'REFRESH' => 'N',
				);
			} **/

			if (is_array($arrParams) && count($arrParams) > 0)
			{
				$bUserFieldsNeed = $bIblockPropertyNeed = false;

				foreach ($arrParams as $sParamsName)
				{
					switch ($sParamsName)
					{
						case 'sectionsView':
							$bUserFieldsNeed = true;
						break;

						case 'propertyPrice':
							$bIblockPropertyNeed = true;
						break;

						case 'news':
							$bIblockPropertyNeed = true;
						break;
					}
				}

				if ($bUserFieldsNeed)
				{
					global $USER_FIELD_MANAGER;

					$arProperty_UF = array();
					$arUserFields = $USER_FIELD_MANAGER->GetUserFields(array());
					foreach ($arUserFields as $FIELD_NAME=>$arUserField)
					{
						$arProperty_UF[$FIELD_NAME] = $arUserField['LIST_COLUMN_LABEL'] ? $arUserField['LIST_COLUMN_LABEL']: $FIELD_NAME;
					}
				}

				$arAllPropList = array();
				if ($bIblockPropertyNeed && $bIBlock)
				{
					if (isset($arCurrentValues['IBLOCK_ID']) && intval($arCurrentValues['IBLOCK_ID']) > 0)
					{
						$arFilePropList = $defaultValues;
						$arListPropList = array();

						$rsProps = \CIBlockProperty::GetList(
							array('SORT' => 'ASC', 'ID' => 'ASC'),
							array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y')
						);
						while ($arProp = $rsProps->Fetch())
						{
							$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];

							if ($arProp['CODE'] == '')
							{
								$arProp['CODE'] = $arProp['ID'];
							}

							$arAllPropList[$arProp['CODE']] = $strPropName;

							if ($arProp['PROPERTY_TYPE'] === 'F')
							{
								$arFilePropList[$arProp['CODE']] = $strPropName;
							}

							if ($arProp['PROPERTY_TYPE'] === 'L')
							{
								$arListPropList[$arProp['CODE']] = $strPropName;
							}
						}
					}
				}

				foreach ($arrParams as $sParamsName)
				{
					switch ($sParamsName)
					{
						case 'blockName':
							/** $arTemplateParameters['SHOW_PARENT_TITLE'] = array(
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SHOW_PARENT_TITLE'),
								'TYPE' => 'CHECKBOX',
								'VALUE' => 'Y',
								'DEFAULT' => 'N',
								'REFRESH' => 'Y',
							);

							if ($arCurrentValues['SHOW_PARENT_TITLE'] == 'Y')
							{
								$arTemplateParameters['PARENT_TITLE'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_PARENT_TITLE'),
									'TYPE' => 'STRING',
									'VALUE' => '',
									'DEFAULT' => '',
								);

								$arTemplateParameters['BLOCK_NAME_IS_LINK'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_PARENT_TITLE_IS_LINK'),
									'TYPE' => 'CHECKBOX',
									'VALUE' => 'Y',
									'DEFAULT' => 'N',
								);
							} **/
							break;

						case 'owlSupport':
							$arTemplateParameters['USE_OWL'] = array(
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_USE_OWL'),
								'TYPE' => 'CHECKBOX',
								'VALUE' => 'Y',
								'DEFAULT' => 'N',
								'REFRESH' => 'Y',
							);

							if ($arCurrentValues['USE_OWL'] == 'Y')
							{
								$arTemplateParameters['SLIDER_RESPONSIVE_SETTINGS'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_GRID_RESPONSIVE_SETTINGS'),
									'TYPE' => 'CUSTOM',
									'JS_FILE' => '/bitrix/js/redsign.megamart/component_settings/slider_settings.js',
									'JS_EVENT' => 'SliderResponsiveSettingsInit',
									'JS_DATA' => str_replace('\'', '"', \CUtil::PhpToJSObject(
										array(
											'defaultResolutions' => array(
												'xxs(0px)' => '0',
												'xs(380px)' => '380',
												'sm(576px)' => '576',
												'md(768px)' => '768',
												'lg(992px)' => '992',
												'lg(1200px)' => '1200'
											),
											'defaultValue' => array(
												'0' => array('items' => 1),
												'380' => array('items' => 2),
												'576' => array('items' => 2),
												'768' => array('items' => 3),
												'992' => array('items' => 4),
												'1200' => array('items' => 4)
											),
											'labels' => array(
												'screenResolution' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_SCREEN_RESOLUTION'),
												'elements' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_ELEMENTS'),
												'delete' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_DELETE_BUTTON'),
												'newResolution' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_NEW_RESOLUTION_BUTTON')
											)
										)
									)),
								  'DEFAULT' => '',
								);

								$arTemplateParameters['OWL_CHANGE_SPEED'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_CHANGE_SPEED'),
									'TYPE' => 'STRING',
									'DEFAULT' => '2000',
								);

								$arTemplateParameters['OWL_CHANGE_DELAY'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_CHANGE_DELAY'),
									'TYPE' => 'STRING',
									'DEFAULT' => '8000',
								);
							}
							break;

						case 'gridSettings':
							$arTemplateParameters['GRID_RESPONSIVE_SETTINGS'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_GRID_RESPONSIVE_SETTINGS'),
									'TYPE' => 'CUSTOM',
									'JS_FILE' => '/bitrix/js/redsign.megamart/component_settings/slider_settings.js',
									'JS_EVENT' => 'SliderResponsiveSettingsInit',
									'JS_DATA' => str_replace('\'', '"', \CUtil::PhpToJSObject(
										array(
											'defaultResolutions' => array(
												'xxs(0px)' => 'xxs',
												'xs(380px)' => 'xs',
												'sm(576px)' => 'sm',
												'md(768px)' => 'md',
												'lg(992px)' => 'lg',
												'xl(1200px)' => 'xl'
											),
											'defaultValue' => array(
												'xxs' => array('items' => 1),
												'xs' => array('items' => 2),
												'sm' => array('items' => 2),
												'md' => array('items' => 3),
												'lg' => array('items' => 4),
												'xl' => array('items' => 4)
											),
											'labels' => array(
												'screenResolution' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_SCREEN_RESOLUTION'),
												'elements' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_ELEMENTS'),
												'delete' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_DELETE_BUTTON'),
												'newResolution' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_NEW_RESOLUTION_BUTTON')
											)
										)
									)),
								  'DEFAULT' => '',
								);
							break;

						case 'sectionsView':

							$arViewModeList = array(
								//'LIST' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SECTIONS_VIEW_MODE.LIST'),
								// 'LINE' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SECTIONS_VIEW_MODE.LINE'),
								//'TEXT' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SECTIONS_VIEW_MODE.TEXT'),
								'TILE' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SECTIONS_VIEW_MODE.TILE'),
								// 'THUMB' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SECTIONS_VIEW_MODE.THUMB'),
								// 'BANNER' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SECTIONS_VIEW_MODE.BANNER'),
							);

							$arTemplateParameters['SECTIONS_VIEW_MODE'] = array(
								'PARENT' => 'SECTIONS_SETTINGS',
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SECTIONS_VIEW_MODE'),
								'TYPE' => 'LIST',
								'VALUES' => $defaultValues + $arViewModeList,
								'MULTIPLE' => 'N',
								'DEFAULT' => 'LINE',
								'REFRESH' => 'Y'
							);

							if (isset($arCurrentValues['SECTIONS_VIEW_MODE']))
							{
								// if ('TILE' == $arCurrentValues['SECTIONS_VIEW_MODE'])
								// {
									// $arTemplateParameters['LINE_ELEMENT_COUNT'] = array(
										// 'PARENT' => 'SECTIONS_SETTINGS',
										// 'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_LINE_ELEMENT_COUNT'),
										// 'TYPE' => 'LIST',
										// 'VALUES' => array(
											// '2' => '2',
											// '3' => '3',
											// '4' => '4',
											// '6' => '6',
											// '12' => '12',
										// ),
										// 'DEFAULT' => '3'
									// );

								// }
								// elseif (in_array($arCurrentValues['SECTIONS_VIEW_MODE'], array('BANNER', 'THUMB')))
								// {
									// $arTemplateParameters['BANNER_TYPE'] = array(
										// 'PARENT' => 'SECTIONS_SETTINGS',
										// 'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_BANNER_TYPE'),
										// 'TYPE' => 'LIST',
										// 'VALUES' => array_merge($defaultValues, $arProperty_UF),
										// 'REFRESH' => 'Y'
									// );

								// }
							}
							break;

						case 'news':
							$arTemplateParameters['SLOGAN_CODE'] = array(
								'PARENT' => 'VISUAL',
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_NEWS_SLOGAN_CODE'),
								'TYPE' => 'LIST',
								'VALUES' => $defaultValues + $arAllPropList,
							);

							$arTemplateParameters['STICKER_IBLOCK'] = array(
								'PARENT' => 'VISUAL',
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_NEWS_STICKER_IBLOCK'),
								'TYPE' => 'CHECKBOX',
								'VALUE' => 'Y',
								'DEFAULT' => 'Y',
							);
							$arTemplateParameters['STICKER_CODE'] = array(
								'PARENT' => 'VISUAL',
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_NEWS_STICKER_CODE'),
								'TYPE' => 'LIST',
								'VALUES' => $defaultValues + $arListPropList,
							);

							$arNewsViewModeList = array(
								'TILE' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_NEWS_VIEW_MODE.TILE'),
								'LINE' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_NEWS_VIEW_MODE.LINE'),
							);

							$arTemplateParameters['NEWS_VIEW_MODE'] = array(
								'PARENT' => 'LIST_SETTINGS',
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_NEWS_VIEW_MODE'),
								'TYPE' => 'LIST',
								'VALUES' => $defaultValues + $arNewsViewModeList,
								'MULTIPLE' => 'N',
								'DEFAULT' => 'LINE',
								'REFRESH' => 'Y'
							);

							$arTemplateParameters['DISPLAY_PREVIEW_TEXT'] = array(
								'PARENT' => 'LIST_SETTINGS',
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_NEWS_DISPLAY_PREVIEW_TEXT'),
								'TYPE' => 'CHECKBOX',
								'VALUE' => 'Y',
								'DEFAULT' => 'N',
							);

							break;

						// case 'propertyPrice':
						// 	$arTemplateParameters['PRICE_PROP'] = array(
						// 		'PARENT' => 'VISUAL',
						// 		'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_PRICE_PROP'),
						// 		'TYPE' => 'LIST',
						// 		'VALUES' => $defaultValues + $arAllPropList,
						// 	);
						// 	$arTemplateParameters['DISCOUNT_PROP'] = array(
						// 		'PARENT' => 'VISUAL',
						// 		'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_DISCOUNT_PROP'),
						// 		'TYPE' => 'LIST',
						// 		'VALUES' => $defaultValues + $arAllPropList,
						// 	);

						// 	$arTemplateParameters['CURRENCY_PROP'] = array(
						// 		'PARENT' => 'VISUAL',
						// 		'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_CURRENCY_PROP'),
						// 		'TYPE' => 'LIST',
						// 		'VALUES' => $defaultValues + $arAllPropList,
						// 	);

						// 	$arTemplateParameters['PRICE_DECIMALS'] = array(
						// 		'PARENT' => 'VISUAL',
						// 		'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_PRICE_DECIMALS_PROP'),
						// 		'TYPE' => 'LIST',
						// 		'VALUES' => array(
						// 			'0' => '0',
						// 			'1' => '1',
						// 			'2' => '2',
						// 		),
						// 		'DEFAULT' => '0',
						// 	);

						// 	$arTemplateParameters['SHOW_OLD_PRICE'] = array(
						// 		'PARENT' => 'VISUAL',
						// 		'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SHOW_OLD_PRICE'),
						// 		'TYPE' => 'CHECKBOX',
						// 		'DEFAULT' => 'N'
						// 	);
						// 	break;

						case 'share':

							$arTemplateParameters['USE_SHARE'] = array(
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_USE_SHARE'),
								'TYPE' => 'CHECKBOX',
								'MULTIPLE' => 'N',
								'VALUE' => 'Y',
								'DEFAULT' =>'N',
								'REFRESH'=> 'Y',
							);

							if ($arCurrentValues['USE_SHARE'] == 'Y')
							{
								$arSocialServices = array(
									'blogger' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.BLOGGER'),
									'delicious' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.DELICIOUS'),
									'digg' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.DIGG'),
									'evernote' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.EVERNOTE'),
									'facebook' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.FACEBOOK'),
									'gplus' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.GPLUS'),
									'linkedin' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.LINKEDIN'),
									'lj' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.LJ'),
									'moimir' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.MOIMIR'),
									'odnoklassniki' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.ODNOKLASSNIKI'),
									'pinterest' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.PINTEREST'),
									'pocket' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.POCKET'),
									'qzone' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.QZONE'),
									'reddit' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.REDDIT'),
									'renren' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.RENREN'),
									'sinaWeibo ' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.SINA_WEIBO'),
									'surfingbird' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.SURFINGBIRD'),
									'telegram' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.TELEGRAM'),
									'tencentWeibo' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.TENCENT_WEIBO'),
									'tumblr' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.TUMBLR'),
									'twitter' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.TWITTER'),
									'viber' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.VIBER'),
									'vkontakte' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.VKONTAKTE'),
									'whatsapp' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES.WHATSAPP'),
								);

								$arSocialCopy = array(
									'first' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_COPY.FIRST'),
									'last' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_COPY.LAST'),
									'hidden' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_COPY.HIDDEN'),
								);
								$arSocialSize = array(
									'm' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SIZE.M'),
									's' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SIZE.S'),
								);
								$arTemplateParameters['SOCIAL_SERVICES'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SERVICES'),
									'TYPE' => 'LIST',
									'VALUES' => $defaultValues + $arSocialServices,
									'MULTIPLE' => 'Y',
									'DEFAULT' => '',
									'ADDITIONAL_VALUES' => 'Y',
								);
								$arTemplateParameters['SOCIAL_COUNTER'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_COUNTER'),
									'TYPE' => 'CHECKBOX',
									'DEFAULT' => 'N',
								);
								$arTemplateParameters['SOCIAL_COPY'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_COPY'),
									'TYPE' => 'LIST',
									'VALUES' => $arSocialCopy
								);
								$arTemplateParameters['SOCIAL_LIMIT'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_LIMIT'),
									'TYPE' => 'STRING',
									'DEFAULT' => '',
								);
								$arTemplateParameters['SOCIAL_SIZE'] = array(
									'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_SOCIAL_SIZE'),
									'TYPE' => 'LIST',
									'VALUES' => $arSocialSize
								);
							}

							break;

						case 'lazy-images':
							$arLazyImagesUse = array(
								'Y' => Loc::getMessage('RS_MM_PARAMETERS_LAZY_IMAGES_ENABLE'),
								'N' => Loc::getMessage('RS_MM_PARAMETERS_LAZY_IMAGES_DISABLE'),
								'FROM_MODULE' => Loc::getMessage('RS_MM_PARAMETERS_FROM_MODULE')
							);

							$arTemplateParameters['RS_LAZY_IMAGES_USE'] = array(
								'PARENT' => 'VISUAL',
								'NAME' => Loc::getMessage('RS_MM_PARAMETERS_LAZY_IMAGES_USE'),
								'TYPE' => 'LIST',
								'VALUES' => $arLazyImagesUse,
								'DEFAULT' => 'FROM_MODULE'
							);

							break;
					}

				}
			}
		}
	}
	
	public static function getGridParameters($arParams = array()) {
		$arDefaultParams = array(
			'NAME' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_GRID_RESPONSIVE_SETTINGS'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => '/bitrix/js/redsign.megamart/component_settings/slider_settings.js',
			'JS_EVENT' => 'SliderResponsiveSettingsInit',
			'JS_DATA' => str_replace('\'', '"', \CUtil::PhpToJSObject(
				array(
					'defaultResolutions' => array(
						'xxs(0px)' => 'xxs',
						'xs(380px)' => 'xs',
						'sm(576px)' => 'sm',
						'md(768px)' => 'md',
						'lg(992px)' => 'lg',
						'xl(1200px)' => 'xl'
					),
					'defaultValue' => array(
						'xxs' => array('items' => 1),
						'xs' => array('items' => 2),
						'sm' => array('items' => 2),
						'md' => array('items' => 3),
						'lg' => array('items' => 4),
						'xl' => array('items' => 4)
					),
					'labels' => array(
						'screenResolution' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_SCREEN_RESOLUTION'),
						'elements' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_ELEMENTS'),
						'delete' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_DELETE_BUTTON'),
						'newResolution' => Loc::getMessage('RS_MM_PARAMETERS_UTILS_OWL_RESPONSIVE_NEW_RESOLUTION_BUTTON')
					)
				)
			)),
		  'DEFAULT' => '',
		);
		
		return array_merge(
			$arDefaultParams,
			$arParams
		);
	}

	public static function gridToString ($arGrid = array())
	{
		$sGridClass = '';

		if (is_array($arGrid) && count($arGrid) > 0)
		{
			$sGridClass = implode(
				' ',
				array_map(
					function ($key, $val) {
						return 'col-'.($key == 'xxs' ? '' : $key.'-').$val;
					},
					array_keys($arGrid),
					$arGrid
				)
			);
		}

		return $sGridClass;
	}

	public static function prepareGridSettings ($arGrid = array())
	{
		$arResult = null;

		if (strlen($arGrid) > 0)
		{
			$arResult = \CUtil::JsObjectToPhp($arGrid);

			if (is_array($arResult) && count($arResult) > 0)
			{
				foreach ($arResult as $key => $val)
				{
					if (intval($val['items']) > 0)
					{
						$arResult[$key] = (int) 12 / intval($val['items']);
					}
				}
				unset($key, $val);
			}
		}

		return $arResult;
	}
	
	public static function getTemplateParameters(
		$sPrefix,
		$sTemplateId,
		$sComponentName,
		$sComponentTemplate,
		$arCurrentValues = array(),
		$fnCallback = null
	) {
		$arTemplateParameters = [];
		$arTemplateParametersReturn = [];
		
		$sTemplatePath = getLocalPath('templates/'.$sTemplateId.'/components/bitrix/'.$sComponentName.'/'.$sComponentTemplate);
		$sTemplateParametersPath = $_SERVER['DOCUMENT_ROOT'].$sTemplatePath.'/.parameters.php';
		
		
		if (file_exists($sTemplateParametersPath)) {
			include $sTemplateParametersPath;
		}
		
		if (is_array($arTemplateParameters) && count($arTemplateParameters) > 0) {
			foreach ($arTemplateParameters as $sParameterKey => $arParameter) {
				
				if ($fnCallback) {
					call_user_func_array($fnCallback, array(&$arParameter));
				}
				
				$arTemplateParametersReturn['RSP_'.$sPrefix.'_'.$sParameterKey] = $arParameter;
			}
		}
		
		return $arTemplateParametersReturn;
	}
	
	public static function getTemplateParametersValue($sPrefix, $arParams = []) {
		$sFullPrefix = 'RSP_'.$sPrefix;
		$nFullPrefixLength = strlen($sFullPrefix) + 1;

		$arTemplateParameters = [];
		foreach ($arParams as $sParamKey => $sParamValue) {
			if (strpos($sParamKey, $sFullPrefix) === 0) {
				$sTemplateParamKey = substr($sParamKey, $nFullPrefixLength);
				$arTemplateParameters[$sTemplateParamKey] = $sParamValue;
			}
		}

		return $arTemplateParameters;
	}

}
