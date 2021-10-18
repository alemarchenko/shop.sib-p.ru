<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

if ($arResult['IS_AJAX']) {
	$APPLICATION->RestartBuffer();
}

$sTemplatePath = $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/templates/'.$arResult['TEMPLATE'].'.php';
?>
<script data-skip-moving>
if (!window.RSLocationChange) {
	function RSLocationChange(id) {
		if (RS.Location && id != RS.Location.getCityId()) {
			RS.Location.change(id);

			if ($.fancybox && $.fancybox.getInstance()) {
				$.fancybox.getInstance().showLoading();
			}
		}
	}
}
</script>
<?php
if (file_exists($sTemplatePath)) {
	include($sTemplatePath);
}

if ($arResult['IS_AJAX']) {
	CMain::FinalActions();
	die();
}
