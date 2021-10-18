<?php

namespace Redsign\Tuning;

use Bitrix\Main\Localization\Loc;
use Redsign\Tuning;

Loc::loadMessages(__FILE__);

class WidgetPainting
{
	const MACROS_NAME = 'TUNING_COLOR';
	public $arAddedType = [];

    private static $instance;

	public function fileGetContents()
	{
		ob_start();
?>
body .rstuning a.active,
body .rstuning a:hover,
body .rstuning a:focus,
body .rstuning a:active {
	color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
body .rstuning__tabs__link.active > span,
body .rstuning__tabs__link:hover > span {
	color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
body .rstuning .spinner-layer {
	border-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
body .rstuning__close-button__link svg {
	fill: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
body .rstuning__hamburger.is-active .rstuning__hamburger-inner,
body .rstuning__hamburger.is-active .rstuning__hamburger-inner::before,
body .rstuning__hamburger.is-active .rstuning__hamburger-inner::after {
	background-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
body .rstuning__hamburger-inner,
body .rstuning__hamburger-inner:before,
body .rstuning__hamburger-inner:after {
	background-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
<?php
		$css = ob_get_contents();
		ob_end_clean();

		$css.= $this->getOptionsCss();

		return $css;
	}

	public function getOptionsCss()
	{
		$css = '';

		$instanceOptionManager = Tuning\OptionManager::getInstance();
		$instanceOption = Tuning\TuningOption::getInstance();

		$optionList = $instanceOptionManager->getOptions();

		foreach ($optionList as $arOption)
		{
			if (in_array($arOption['TYPE'], $this->arAddedType))
				continue;

			$this->arAddedType[] = $arOption['TYPE'];

			$optionObj = $instanceOption->getOptionObjectByName($arOption['TYPE']);
			if ($optionObj != null)
			{
				$css.= $optionObj->getPainting();
			}
		}

		return $css;
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new WidgetPainting();
		}

		return self::$instance;
	}

}
