<?php

namespace Redsign\Tuning;

use Bitrix\Main\Config\Option;
use Redsign\Tuning;

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'redsign.tuning');

abstract class OptionManager implements Interfaces\OptionManagerInterface {

    public $options;
    public $optionValuesDefault;
    public $optionValues;
    public $childrenOptions;

    function __construct($options) {
        $this->options = $options;
        $this->optionValuesDefault = array();
        $this->optionValues = array();
        $this->childrenOptions = array();

        $this->initOptions();
    }

    abstract public function getOption($optionName, $default = '');

    abstract public function saveOption($optionName, $value);

    public function set($optionName, $value) {
        if (array_key_exists($optionName, $this->options))
        {
            $this->optionValues[$optionName] = $value;

            $valueTmp = $value;
            if (is_array($value))
            {
                $valueTmp = serialize($value);
            }

            $this->saveOption($optionName, $valueTmp);
        }
    }

    public function get($optionName) {
        if (array_key_exists($optionName, $this->options))
        {
            return $this->optionValues[$optionName];
        }
        else
        {
            return false;
        }
    }

    public function getOptions() {
        return $this->options;
    }

    public function getOptionsByIds($arIds) {
        $arReturnOptions = array();

        foreach ($this->options as $id => $arOption)
        {
            if (in_array($id, $arIds))
            {
                $arReturnOptions[$id] = &$this->options[$id];
            }
        }

        return $arReturnOptions;
    }

    public function saveOptionsByArray($arValues) {
        foreach ($arValues as $optionName => $value)
        {
            $this->set($optionName, $value);
        }
    }

    public function initOptions() {
        if (!is_array($this->options) || empty($this->options))
            return;

        $this->initOptionsByArray($this->options);
    }

    public function initOptionsByArray(&$arOptions) {
        foreach ($arOptions as $id => $arOption)
        {
            $arOptions[$id]['ID'] = $id;
            $this->initOption($id, $arOptions[$id]);

            if (!empty($arOptions[$id]['CHILDREN']))
            {
                $this->addChildrenOptionsByArray($arOptions[$id]['CHILDREN']);
                $this->initOptionsByArray($arOptions[$id]['CHILDREN']);
                $arOptions[$id]['CHILDREN'] = array_keys($arOptions[$id]['CHILDREN']);
            }
        }
    }

    public function initOption($optionName, $arOption) {
        $defaultOption = array();
        $optionValues = array();

        if ('Y' == $arOption['MULTIPLE'])
        {
            if (!empty($arOption['VALUES']))
            {
                $defaultOption[$optionName] = array();
                $optionValues[$optionName] = array();

                foreach ($arOption['VALUES'] as $id => $arMultipleOption)
                {
                    if (!empty($arMultipleOption['DEFAULT']))
                    {
                        $defaultOption[$optionName][$id] = $arMultipleOption['DEFAULT'];
                    }
                }

                $optionValues[$optionName] = unserialize($this->getOption($optionName, serialize($defaultOption[$optionName])));
            }
        }
        else
        {
            if (!empty($arOption['DEFAULT']))
            {
                $defaultOption[$optionName] = $arOption['DEFAULT'];
                $optionValues[$optionName] = $this->getOption($optionName, $arOption['DEFAULT']);
            }
        }

        $this->optionValuesDefault = array_merge($this->optionValuesDefault, $defaultOption);
        $this->optionValues = array_merge($this->optionValues, $optionValues);
    }

    public function addChildrenOptionsByArray($arOptions) {
        if (empty($arOptions) || !is_array($arOptions))
            return;
        
        $arIds = array_keys($arOptions);

        if (empty($this->options) || !is_array($this->options))
        {
            $this->options = $arOptions;
            return;
        }

        foreach ($arOptions as $id => $arOption)
        {
            if (!array_key_exists($id, $this->options))
            {
                $this->options[$id] = $arOption;
            }
        }

        $this->childrenOptions = array_merge($this->childrenOptions, $arIds);
    }

    public function addChildrenOptionsById($id) {
        $this->childrenOptions[] = $id;
    }

    public function isChildrenById($id) {
        return in_array($id, $this->childrenOptions);
    }

    public function getOptionTypeById($id) {
        if (array_key_exists($id, $this->options))
        {
            return $this->options[$id]['TYPE'];
        }
        else
        {
            return false;
        }
    }

    public function getInstance() {
        return Tuning\TuningCore::getInstance()->getInstanceOptionMananger();
    }
}
