<?php

namespace Redsign\Tuning;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\SystemException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Event;
use Redsign\Tuning;

Loc::loadMessages(__FILE__);

class TuningCore
{

    public $failInitialize = false;

    private $instanceOptionManager;
    private $instanceOption;
    private $instanceCssFileManager;
    private $instanceMacrosManager;
    private static $instance;

    const EVENT_ON_AFTER_SAVE_OPTIONS = 'onAfterSaveOptions';

    function __construct(array $params)
    {
        if (empty($params))
        {
            $this->failInitialize();
        }
        else
        {
            $tabs = $params['tabs'];
            $instanceOptionManager = $params['options'];

            $this->instanceOptionManager = $instanceOptionManager;
            $this->instanceTab = TabCore::getInstance($tabs);
            $this->instanceOption = TuningOption::getInstance();
            $this->instanceCssFileManager = CssFileManager::getInstance();
            $this->instanceMacrosManager = MacrosManager::getInstance($this);
        }

        self::$instance = $this;
    }

    public function getOptionValue($optionName)
    {
        if ($this->isFailInitialize())
        {
            return false;
        }

        if (!$optionType = $this->getInstanceOptionMananger()->getOptionTypeById($optionName))
            return false;

        if (empty($optionList = $this->getInstanceOptionMananger()->getOptionsByIds(array($optionName))))
            return false;

        $arOption = $optionList[$optionName];
        $optionObj = $this->getInstanceOption()->getOptionObjectByName($optionType);

        if ($optionObj != null)
        {
            $value = $this->getInstanceOptionMananger()->get($optionName);

            $optionObj->prepareValueAfterGet(array(
                'OPTION' => &$arOption,
                'VALUE' => $value,
            ));

            return $arOption['VALUE'];
        }

        return false;
    }

    public function getInstanceOptionMananger()
    {
        return $this->instanceOptionManager;
    }

    public function getInstanceGroup()
    {
        return $this->instanceGroup;
    }

    public function getInstanceOption()
    {
        return $this->instanceOption;
    }

    public function getInstanceCssFileManager()
    {
        return $this->instanceCssFileManager;
    }

    public function getInstanceMacrosManager()
    {
        return $this->instanceMacrosManager;
    }

    public function setOptionValue($optionName, $value)
    {
        $this->instanceOptionManager->set($optionName, $value);
    }

    public function getOptions()
    {
        return $this->instanceOptionManager->getOptions();
    }

    public function restoreDefaultOptions()
    {
        $instanceOption = Tuning\TuningOption::getInstance();
        $instanceCssFileManager = $this->getInstanceCssFileManager();
        $instanceMacrosManager = $this->getInstanceMacrosManager();

        $optionList = $this->getOptions();

        if (!is_array($optionList) || empty($optionList))
            return false;
        
        foreach ($optionList as $id => $arOption)
        {
            $optionObj = $instanceOption->getOptionObjectByName($arOption['TYPE']);

            if ($optionObj != null)
            {
                if ($value = $optionObj->prepareValueBeforeRestoreDefault(array(
                    'OPTION' => &$arOption,
                )))
                {
                    $this->setOptionValue($id, $value);
                }
            }
        }

        $instanceCssFileManager->removeCss();
        
        $event = new Event('redsign.tuning', self::EVENT_ON_AFTER_SAVE_OPTIONS);
		$event->send();
        
        return true;
    }

    public function saveOptions()
    {
        $instanceOption = Tuning\TuningOption::getInstance();

        $optionList = $this->getOptions();

        $request = Application::getInstance()->getContext()->getRequest();

        foreach ($optionList as $id => $arOption)
        {
            $optionObj = $instanceOption->getOptionObjectByName($arOption['TYPE']);

            if ($optionObj != null)
            {
                if ($value = $optionObj->prepareValueBeforeSave(array(
                    'OPTION' => &$arOption,
                    'VALUE' => $request->getPost($arOption['CONTROL_NAME']),
                )))
                {
                    $this->setOptionValue($id, $value);
                }
            }
        }
        
        $event = new Event('redsign.tuning', self::EVENT_ON_AFTER_SAVE_OPTIONS);
        $event->send();
    }

    public function getInstance($params = array())
    {
        $instance = null;

        if (!empty(self::$instance) && self::$instance instanceof TuningCore)
        {
            $instance = self::$instance;

            if ($instance->isFailInitialize())
                return null;
        }
        else
        {
            $instance = new TuningCore($params);
        }

        return $instance;
    }

    public function isFailInitialize()
    {
        return $this->failInitialize === true ? true : false;
    }

    public function failInitialize()
    {
        $this->failInitialize = true;

        // throw new SystemException('Fail initialize tuning.core');
    }

}
