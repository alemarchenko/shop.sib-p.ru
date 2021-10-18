<?php
namespace Redsign\MegaMart\Layouts;

use \Redsign\MegaMart\Layouts\Parts;

class Base
{
	protected $parts = [];
	protected $data = [];
	protected $modifiers = [];
    
    public $slider = null;

	public function registerPart($sKey, Parts\Base $part = null)
	{
		$part->setLayout($this);
		$this->parts[$sKey] = $part;

		return $this;
	}

	public function isRegisteringPart($sKey)
	{
		return isset($this->parts[$sKey]) && !is_null($this->parts[$sKey]);
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

	public function getPart($sKey)
	{
		if (isset($this->parts[$sKey]) && !is_null($this->parts[$sKey])) {
			return $this->parts[$sKey];
		}

		return null;
	}

	public function addModifier($sModifier)
	{
		if (!$this->hasModifier($sModifier)) {
			$this->modifiers[] = $sModifier;
		}

		return $this;
	}

	public function hasModifier($sModifier) {
		return in_array($sModifier, $this->modifiers);
	}
    
    public function start()
	{

	}

	public function end()
	{

	}

	public function getHeader()
	{
		
	}
    
    public function useSlider($sliderId) {
        $this->slider = $sliderId;
    }
}
