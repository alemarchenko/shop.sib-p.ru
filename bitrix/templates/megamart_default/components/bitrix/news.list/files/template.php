<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Loader;
use	\Bitrix\Main\Localization\Loc;

$sBlockId = 'files_'.$this->randString(10);

$this->setFrameMode(true);
?>
<div id="<?=$sBlockId?>">
	<?php
	if (count($arResult['ITEMS']) > 0)
	{
		$sTemplatePath = $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/templates/'.$arParams['RS_TEMPLATE'].'.php';
		if (file_exists($sTemplatePath))
		{
			include $sTemplatePath;
		}
	}
	else
	{
		include $templateFolder.'/templates/no_items.php';
	}
	?>
</div>
