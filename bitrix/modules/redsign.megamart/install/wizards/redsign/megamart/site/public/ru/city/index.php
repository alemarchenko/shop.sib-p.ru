<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Выбрать город");
?>
<? $APPLICATION->IncludeComponent(
	"redsign:location.top", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"COUNT_ITEMS" => "18"
	),
	false
);?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>