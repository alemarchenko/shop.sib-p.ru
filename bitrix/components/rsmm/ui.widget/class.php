<?php
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();


Loc::loadMessages(__FILE__);

/**
 * Class UiWidget
 */
class UiWidget extends CBitrixComponent
{
	/**
	 * Execute component.
	 *
	 * @return void
	 */
	public function executeComponent()
	{
		global $globalState;
		$this->arResult = array_merge([], (array)$globalState);

		$this->includeComponentTemplate();
	}
}