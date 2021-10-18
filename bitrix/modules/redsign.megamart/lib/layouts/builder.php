<?php
namespace Redsign\MegaMart\Layouts;

use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\Layouts\Parts;

Loc::loadMessages(__FILE__);

class Builder
{
	public static function createFromParams($arParams, $layoutHeader = null, $sPrefix = 'LIST')
	{
		switch ($arParams['RS_'.$sPrefix.'_SECTION']) {
			case 'l_section':
				$layout = new Section;

				if ($arParams['RS_'.$sPrefix.'_SECTION_ADD_CONTAINER'] == 'Y') {
					$layout->addModifier('container');
				}

				// Header
				if (
					$arParams['RS_'.$sPrefix.'_SECTION_SHOW_TITLE'] == 'Y' ||
					$arParams['RS_'.$sPrefix.'_SECTION_SHOW_BUTTON'] == 'Y'
				) {
					if (is_null($layoutHeader)) {
						$layoutHeader = new Parts\SectionHeaderBase();
					}

					if ($arParams['RS_'.$sPrefix.'_SECTION_SHOW_TITLE'] == 'Y') {
						$layoutHeader->addData('TITLE', $arParams['RS_'.$sPrefix.'_SECTION_TITLE']);
					}

					if ($arParams['RS_'.$sPrefix.'_SECTION_SHOW_BUTTON'] == 'Y') {
						$layoutHeader->addData('BUTTONS', [
							0 => [
								'NAME' => $arParams['RS_'.$sPrefix.'_SECTION_BUTTON_NAME'],
								'LINK' => $arParams['RS_'.$sPrefix.'_SECTION_BUTTON_LINK']
							]
						]);
					}

					$layout->useHeader($layoutHeader);
				}

				break;

			case 'none':
			default:
				$layout = new \Redsign\MegaMart\Layouts\EmptySection();
				break;
		}

		return $layout;
	}

	public static function createTemplateParams(
		&$arTemplateParameters,
		&$arCurrentValues,
		$arDisabledParams = [],
		$arAdditionalParams = [],
		$sPrefix = 'LIST'
 	) {
		$arTemplateParameters['RS_'.$sPrefix.'_SECTION'] = [
			'NAME' => Loc::getMessage('RS_PARAMETERS_SECTION'),
			'TYPE' => 'LIST',
			'DEFAULT' => 'l_section',
			'REFRESH' => 'Y',
			'VALUES' => [
				'none' => Loc::getMessage('RS_PARAMETERS_SECTION_NONE'),
				'l_section' => Loc::getMessage('RS_PARAMETERS_SECTION_L_SECTION')
			]
		] + $arAdditionalParams;

		if ($arCurrentValues['RS_'.$sPrefix.'_SECTION'] == 'l_section') {

			$arTemplateParameters['RS_'.$sPrefix.'_SECTION_ADD_CONTAINER'] = [
				'NAME' => Loc::getMessage('RS_PARAMETERS_SECTION_ADD_CONTAINER'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'Y',
				'REFRESH' => 'Y'
			];

			$arTemplateParameters['RS_'.$sPrefix.'_SECTION_SHOW_TITLE'] = [
				'NAME' => Loc::getMessage('RS_PARAMETERS_SECTION_SHOW_TITLE'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'Y',
				'REFRESH' => 'Y'
			];

			if ($arCurrentValues['RS_'.$sPrefix.'_SECTION_SHOW_TITLE'] != 'N') {
				$arTemplateParameters['RS_'.$sPrefix.'_SECTION_TITLE'] = [
					'NAME' => Loc::getMessage('RS_PARAMETERS_SECTION_TITLE'),
					'TYPE' => 'STRING',
					'DEFAULT' => ''
				] + $arAdditionalParams;
			}

			$arTemplateParameters['RS_'.$sPrefix.'_SECTION_SHOW_BUTTON'] = [
				'NAME' => Loc::getMessage('RS_PARAMETERS_SECTION_SHOW_BUTTON'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'Y'
			];

			if ($arCurrentValues['RS_'.$sPrefix.'_SECTION_SHOW_BUTTON'] != 'N') {
				$arTemplateParameters['RS_'.$sPrefix.'_SECTION_BUTTON_NAME'] = [
					'NAME' => Loc::getMessage('RS_PARAMETERS_SECTION_BUTTON_NAME'),
					'TYPE' => 'STRING',
					'DEFAULT' => ''
				] + $arAdditionalParams;

				$arTemplateParameters['RS_'.$sPrefix.'_SECTION_BUTTON_LINK'] = [
					'NAME' => Loc::getMessage('RS_PARAMETERS_SECTION_BUTTON_LINK'),
					'TYPE' => 'STRING',
					'DEFAULT' => ''
				] + $arAdditionalParams;
			}

		}


	}
}
