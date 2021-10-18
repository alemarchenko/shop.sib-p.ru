<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\ParametersUtils;

if (!Loader::includeModule('redsign.megamart') || !Loader::includeModule('iblock')) {
	return;
}

Loc::loadMessages(__FILE__);

$arProps = array();
$propertyIterator = \Bitrix\Iblock\PropertyTable::getList(array(
	'filter' => array(
		'=ACTIVE' => 'Y',
		'=IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
	),
	'order' => array('SORT' => 'ASC'),
));
while($arProp = $propertyIterator->fetch()) {
  if ($arProp['PROPERTY_TYPE'] != 'S') {
	  continue;
  }

  $arProps[] = $arProp;
}


$arTemplateParameters['FIELD_PARAMS'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => Loc::getMessage('RS.FORMS_PARAMETERS_FIELDS_PARAMS'),
	'TYPE' => 'CUSTOM',
	'JS_FILE' => ParametersUtils::getSettingsScript('form_fields_params'),
	'JS_EVENT' => 'FormFieldsParamSettingsInit',
	'JS_DATA' => str_replace('\'', '"', CUtil::PhpToJSObject(
		array(
			'fields' => $arProps,
			'labels' => array(
				'validate' => Loc::getMessage('RS.FORMS_PARAMETERS_LABEL_VALIDATE'),
				'mask' => Loc::getMessage('RS.FORMS_PARAMETERS_LABEL_MASK')
			)
		)
	)),
  'DEFAULT' => '',
);
