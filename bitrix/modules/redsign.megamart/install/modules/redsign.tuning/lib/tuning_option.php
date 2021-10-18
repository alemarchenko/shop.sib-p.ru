<?php

namespace Redsign\Tuning;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\SystemException;
use Bitrix\Main\Localization\Loc;
use Redsign\Tuning;

Loc::loadMessages(__FILE__);

class TuningOption extends OptionCore
{

	private static $instance;

	protected $name = '';
	protected $description = '';
	protected $isSortable = false;

	public function showOption($options = array()) {}

	public function onload($options = array()) {}

	public function getPainting() {
		return '';
	}

	public function prepareValueBeforeRestoreDefault($params) {
		$instanceMacrosManager = Tuning\MacrosManager::getInstance();

		if ('Y' == $params['OPTION']['MULTIPLE'])
		{
			if (empty($params['OPTION']['VALUES']) || empty($params['OPTION']['CONTROL_NAME']))
				return false;

			$value = array();
			$tmpValue = array();

			foreach ($params['OPTION']['VALUES'] as $id2 => $arMultipleOption)
			{
				if (empty($params['OPTION']['DEFAULT']))
					continue;

				$value[$id2] = $params['OPTION']['DEFAULT'];
				
				if (empty($value))
				{
					$value = array();
				}

				// save macros values
				$macrosName = $arMultipleOption['MACROS'];
				$tmpValue = $value[$id2];
				if (!empty($macrosName) && !empty($tmpValue))
				{
					$instanceMacrosManager->set($macrosName, $tmpValue);
				}
			}

			return serialize($value);
		}
		else
		{
			if (empty($params['OPTION']['CONTROL_NAME']))
				return false;
			
			$value = $params['OPTION']['DEFAULT'];
			
			if (empty($value))
			{
				$value = '';
			}

			$macrosName = $params['OPTION']['MACROS'];
			$tmpValue = $value;
			if (!empty($macrosName) && !empty($tmpValue))
			{
				$instanceMacrosManager->set($macrosName, $tmpValue);
			}

			return $value;
		}

		return false;
	}

	public function prepareValueBeforeSave($params) {
		$instanceMacrosManager = Tuning\MacrosManager::getInstance();

		if ('Y' == $params['OPTION']['MULTIPLE'])
		{
			if (empty($params['OPTION']['VALUES']) || empty($params['OPTION']['CONTROL_NAME']))
				return false;

			$value = array();
			$arValue = $params['VALUE'];
			$tmpValue = array();

			foreach ($params['OPTION']['VALUES'] as $id2 => $arMultipleOption)
			{
				if (empty($arValue[$arMultipleOption['CONTROL_NAME']]))
					continue;

				$value[$id2] = $arValue[$arMultipleOption['CONTROL_NAME']];
				
				if (empty($value))
				{
					$value = array();
				}

				// save macros values
				$macrosName = $arMultipleOption['MACROS'];
				$tmpValue = $value[$id2];
				if (!empty($macrosName) && !empty($tmpValue))
				{
					$instanceMacrosManager->set($macrosName, $tmpValue);
				}
			}

			return serialize($value);
		}
		else
		{
			if (empty($params['OPTION']['CONTROL_NAME']))
				return false;
			
			$value = $params['VALUE'];
			
			if (empty($value))
			{
				$value = '';
			}

			$macrosName = $params['OPTION']['MACROS'];
			$tmpValue = $value;
			if (!empty($macrosName) && !empty($tmpValue))
			{
				$instanceMacrosManager->set($macrosName, $tmpValue);
			}

			return $value;
		}

		return false;
	}

	public function prepareValueAfterGet($params) {
		if ('Y' == $params['OPTION']['MULTIPLE'])
		{
			$arValues = $params['VALUE'];

			if (!empty($params['OPTION']['VALUES']))
			{
				foreach ($params['OPTION']['VALUES'] as $id2 => $arValue)
				{
					if (array_key_exists($id2, $arValues))
					{
						$params['OPTION']['VALUES'][$id2]['VALUE'] = $arValues[$id2];
					}
				}
			}
		}

		$params['OPTION']['VALUE'] = $params['VALUE'];
	}

	public function getOptionName() {
		return $this->name;
	}

	public function isSortable() {
		return ($this->isSortable ? $this->isSortable : false);
	}

	public function getOptionDescription() {
		return $this->description;
	}

	public static function getInstance() {
		if (is_null(self::$instance))
		{
			self::$instance = new TuningOption();
		}

		return self::$instance;
	}

}
