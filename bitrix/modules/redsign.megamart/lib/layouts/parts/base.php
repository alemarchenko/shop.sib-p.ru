<?php
namespace Redsign\MegaMart\Layouts\Parts;

abstract class Base
{
	protected $layout;
	protected $data = [];

	public function setLayout(\Redsign\MegaMart\Layouts\Base $layout)
	{
		$this->layout = $layout;
	}

	public function addData($sKey, $mVal)
	{
		$this->data[$sKey] = $mVal;

		return $this;
	}

	public function getData($sKey)
	{
		return isset($this->data[$sKey]) ? $this->data[$sKey] : '';
	}

	abstract public function show();
}
