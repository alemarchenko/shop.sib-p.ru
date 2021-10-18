<?php
namespace Redsign\MegaMart\Layouts\Parts;

class SectionHeaderCustom extends Base
{
	protected $callbackFn;

	public function defineShowFn($callbackFn)
	{
		if (is_callable($callbackFn)) {
			$this->callbackFn = $callbackFn;
			$this->callbackFn->bindTo($this);
		}
	}

	public function show()
	{
		if (!is_null ($this->callbackFn) && is_callable($this->callbackFn)) {
			call_user_func($this->callbackFn->bindTo($this));
		}
	}
}
