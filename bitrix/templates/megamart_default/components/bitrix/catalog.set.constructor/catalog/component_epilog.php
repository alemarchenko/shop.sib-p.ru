<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;

global $APPLICATION;

$loadCurrency = Loader::includeModule('currency');
CJSCore::Init(array('currency'));

// $asset = Asset::getInstance();
// $asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery.scrollbar/jquery.scrollbar.js');
// $asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery-mousewheel/jquery.mousewheel.js');
?>
<script type="text/javascript">
	BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
</script>