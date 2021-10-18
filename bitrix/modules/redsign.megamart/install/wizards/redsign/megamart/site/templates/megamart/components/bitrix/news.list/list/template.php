<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Loader;
use	\Bitrix\Main\Localization\Loc;

$sBlockId = $this->randString(10);

$this->setFrameMode(true);
?>
<div id="<?=$sBlockId?>">
	<?php
	if (file_exists($arResult['TEMPLATE_PATH'])) {
		include($arResult['TEMPLATE_PATH']);
	}
	?>
</div>
