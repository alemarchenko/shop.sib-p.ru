<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("С вами поделились корзиной");
?>

<?php 
$APPLICATION->IncludeComponent(
	"redsign:vbasket.shared.apply", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"NEED_CONFIRM" => "N",
		"PATH_TO_CART" => "/personal/cart/"
	),
	false
); 
?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
