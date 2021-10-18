<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;

$arComponentParameters = array(
    'PARAMETERS' => array(
        'ORGANIZATION' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_ORG'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
        'ADR_COUNTRY_NAME' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_ADR_COUNTRY_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
        'ADR_REGION' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_ADR_REGION'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
        'ADR_LOCALITY' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_ADR_LOCALITY'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
        'ADR_STREET_ADDRESS' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_ADR_STREET_ADDRESS'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
        'ADR_EXT_ADDRESS' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_ADR_EXT_ADDRESS'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
        'ADR_POSTAL_CODE' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_ADR_POSTAL_CODE'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
        'WORKHOURS' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_WORKHOURS'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
        'PHONE' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_PHONE'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
        'EMAIL' => array(
            'NAME' => Loc::getMessage('ALFA_MSG_HCARD_PARAMETERS_EMAIL'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
        ),
    )
);