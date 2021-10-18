<?php

namespace Redsign\Tuning;

use Bitrix\Main\Application;
use Redsign\Tuning;

Loc::loadMessages(__FILE__);

class TuningCurrentValues
{

	private static $instance;

	public static $params = array();
	public static $arCurrentValues = array();
	public static $optionName = 'arCurrentValues';

	public function __construct($params = array()) {
		$this->params = $params;

		if ('Y' == $this->params['FROM_SESSION'])
		{
			$this->optionsInstance = new Tuning\OptionManagerSession($options);
		}
		else
		{
			$this->optionsInstance = new Tuning\OptionManagerBitrix($options);
		}
	}

	public function getAll() {
		if (!empty($this->arCurrentValues))
			return $this->arCurrentValues;

		$arValues = unserialize($this->optionsInstance->getOption($this->optionName, serialize(array())));
		if (!empty($arValues))
		{
			$this->$arCurrentValues = $arValues;
			return $this->$arCurrentValues;
		}

		return array();
	}

	public function get($id) {
		if (!empty($this->arCurrentValues[$id]))
		{
			return $this->arCurrentValues[$id];
		}

		return false;
	}

	public function set($id, $value) {
		$this->arCurrentValues[$id] = $value;
		$this->optionsInstance->setOption($this->optionName, serialize($this->arCurrentValues));
	}

	public static function getInstance($params = array()) {
		if (is_null(self::$instance))
		{
			self::$instance = new TuningCurrentValues($params = array());
		}

		return self::$instance;
	}

}
