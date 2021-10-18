<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Loader;
use	\Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
?>
<?php
	if (file_exists($arResult['TEMPLATE_PATH'])) {
		include($arResult['TEMPLATE_PATH']);
	}
?>
