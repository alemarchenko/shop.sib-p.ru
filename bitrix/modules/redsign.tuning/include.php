<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\SystemException;
use \Redsign\Tuning;

Loc::loadMessages(__FILE__);

$arClasses = array(
    '\Redsign\Tuning\Interfaces\OptionCoreInterface' => 'lib/interfaces/option_core_interface.php',
    '\Redsign\Tuning\Interfaces\OptionManagerInterface' => 'lib/interfaces/option_manager_interface.php',
    '\Redsign\Tuning\TuningCore' => 'lib/tuning_core.php',
    '\Redsign\Tuning\TuningCurrentValues' => 'lib/tuning_current_value.php',
    '\Redsign\Tuning\TabCore' => 'lib/tab_core.php',
    '\Redsign\Tuning\OptionCore' => 'lib/option_core.php',
    '\Redsign\Tuning\TuningOption' => 'lib/tuning_option.php',
    '\Redsign\Tuning\OptionManager' => 'lib/option_manager.php',
    '\Redsign\Tuning\OptionManagerBitrix' => 'lib/option_manager_bitrix.php',
    '\Redsign\Tuning\OptionManagerSession' => 'lib/option_manager_session.php',
    '\Redsign\Tuning\CssFileManager' => 'lib/css_file_manager.php',
    '\Redsign\Tuning\MacrosManager' => 'lib/macros_manager.php',
    '\Redsign\Tuning\WidgetPainting' => 'lib/widget_painting.php',
);

Loader::registerAutoLoadClasses('redsign.tuning', $arClasses);

if (!function_exists('rsTuningIsHideTuning'))
{
    function rsTuningIsHideTuning()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        if (strpos(strtolower($request->getUserAgent()), 'lighthouse') !== false)
            return true;

        return false;
    }
}

$arJSCoreConfig = array(
    'rs_tuning' => array(
        'js' => getLocalPath('css/redsign.tuning/tuning.js'),
        'rel' => array('rs_core'),
    ),
);

foreach ($arJSCoreConfig as $ext => $arExt)
{
    CJSCore::RegisterExt($ext, $arExt);
}

$arDefaultSettings = array(
    0 => array(
        'KEY' => 'fromSession',
        'DEFAULT' => '',
    ),
    1 => array(
        'KEY' => 'fileOptions',
        'DEFAULT' => '',
    ),
    2 => array(
        'KEY' => 'fileOptionsExt',
        'DEFAULT' => '',
    ),
    3 => array(
        'KEY' => 'fileColorMacros',
        'DEFAULT' => '',
    ),
    4 => array(
        'KEY' => 'fileColorCompiled',
        'DEFAULT' => '',
    ),
    5 => array(
        'KEY' => 'dirOptionsExt',
        'DEFAULT' => '',
    ),
);

$fromSession = Option::get('redsign.tuning', $arDefaultSettings[0]['KEY'], '', SITE_ID);
$fileOptions = Option::get('redsign.tuning', $arDefaultSettings[1]['KEY'], '', SITE_ID);
$fileOptionsExt = Option::get('redsign.tuning', $arDefaultSettings[2]['KEY'], '', SITE_ID);
$fileColorMacros = Option::get('redsign.tuning', $arDefaultSettings[3]['KEY'], '', SITE_ID);
$fileColorCompiled = Option::get('redsign.tuning', $arDefaultSettings[4]['KEY'], '', SITE_ID);
$dirOptionsExt = Option::get('redsign.tuning', $arDefaultSettings[5]['KEY'], '', SITE_ID);
$arErrors = array();

if (!file_exists(Application::getDocumentRoot().$fileOptions))
{
	$arErrors[] = 'Option file is not found.';
}

$arCurrentValues = array();
$arExcludeParams = array();
foreach ($arDefaultSettings as $arItem)
{
    $arExcludeParams[] = $arItem['KEY'];
}

$temporary = include(Application::getDocumentRoot().$fileOptions);
if (isset($temporary['PARAMETERS']))
{
    $tabs = $temporary['TABS'];
    $options = $temporary['PARAMETERS'];
}
else
{
    $tabs = array();
    $options = $temporary;
}

if (!empty($fileOptionsExt) && $fileOptionsExt != '' && file_exists(Application::getDocumentRoot().$fileOptionsExt))
{
    $temporaryExt = include(Application::getDocumentRoot().$fileOptionsExt);

    if (isset($temporaryExt['PARAMETERS']))
    {
        if (!empty($temporaryExt['TABS']))
        {
            $tabs = array_merge($tabs, $temporaryExt['TABS']);
        }
        if (!empty($temporaryExt))
        {
            $options = array_merge($options, $temporaryExt['PARAMETERS']);
        }
    }
    else
    {
        if (!empty($temporaryExt))
        {
            $options = array_merge($options, $temporaryExt['PARAMETERS']);
        }
    }
}

if (empty($options))
{
    $arErrors[] = 'Options is empty.';
}

if (!empty($arErrors))
{
    $tuning = Tuning\TuningCore::getInstance(array());
    return;
}

if ('Y' == $fromSession)
{
    $optionsInstance = new Tuning\OptionManagerSession($options);
}
else
{
    $optionsInstance = new Tuning\OptionManagerBitrix($options);
}

$optionCore = Tuning\TuningOption::getInstance();

if (!empty($dirOptionsExt) && file_exists(Application::getDocumentRoot().$dirOptionsExt) && is_dir(Application::getDocumentRoot().$dirOptionsExt))
{
    $optionCore->addOptionPath(Application::getDocumentRoot().$dirOptionsExt);
}

$optionCore->defaultInit();

$params = array(
    'tabs' => $tabs,
    'options' => $optionsInstance,
);
$tuning = Tuning\TuningCore::getInstance($params);

$instanceMacrosManager = Tuning\MacrosManager::getInstance();
$instanceMacrosManager->initMacrosList();
