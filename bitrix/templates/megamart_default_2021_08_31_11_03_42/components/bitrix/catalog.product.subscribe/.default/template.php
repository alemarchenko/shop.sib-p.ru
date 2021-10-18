<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Bitrix\Main\Localization\Loc;

CJSCore::init(array('popup', 'ajax'));

$this->setFrameMode(true);

$landingId = null;
if (is_callable(["LandingPubComponent", "getMainInstance"]))
{
	$instance = \LandingPubComponent::getMainInstance();
	$landingId = $instance["SITE_ID"];
}

$strMainId = $this->getEditAreaId($arResult['PRODUCT_ID']);
$jsObject = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainId);
$paramsForJs = array(
	'buttonId' => $arResult['BUTTON_ID'],
	'jsObject' => $jsObject,
	'alreadySubscribed' => $arResult['ALREADY_SUBSCRIBED'],
	'productId' => $arResult['PRODUCT_ID'],
	'buttonClass' => htmlspecialcharsbx($arResult['BUTTON_CLASS']),
	'urlListSubscriptions' => '/',
);

$showSubscribe = true;

/* Compatibility with the sale subscribe option */
$saleNotifyOption = Bitrix\Main\Config\Option::get('sale', 'subscribe_prod');
if(strlen($saleNotifyOption) > 0)
	$saleNotifyOption = unserialize($saleNotifyOption);
$saleNotifyOption = is_array($saleNotifyOption) ? $saleNotifyOption : array();
foreach($saleNotifyOption as $siteId => $data)
{
	if($siteId == SITE_ID && $data['use'] != 'Y')
		$showSubscribe = false;
}

$subscribeBtnName = !empty($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : Loc::getMessage('CPST_SUBSCRIBE_BUTTON_NAME');

$paramsForJs['subscribeBtnName'] = $subscribeBtnName;
$paramsForJs['subscribedBtnName'] = !empty($arParams['~MESS_BTN_SUBSCRIBED']) ? $arParams['~MESS_BTN_SUBSCRIBED'] : Loc::getMessage('CPST_TITLE_ALREADY_SUBSCRIBED');

$propertyElem = CIBlockElement::GetByID($arResult['PRODUCT_ID'])->GetNextElement()->GetProperties();
$elemSubscribe = CIBlockElement::GetByID($arResult['PRODUCT_ID'])->GetNext();
	
$ImageUrl = CFile::GetPath($elemSubscribe['PREVIEW_PICTURE']);
$paramsForJs['productName'] = $elemSubscribe['NAME'];
$paramsForJs['ImageUrl'] = $ImageUrl;
$paramsForJs['artnumber'] = $propertyElem['ARTNUMBER']['VALUE'];
$paramsForJs['USE_CAPTCHA'] = $arParams['USE_CAPTCHA'];



$templateData = $paramsForJs;
$templateData['showSubscribe'] = $showSubscribe;

if($showSubscribe):?>
	<a id="<?=htmlspecialcharsbx($arResult['BUTTON_ID'])?>"
			class="<?=htmlspecialcharsbx($arResult['BUTTON_CLASS'])?>"
			data-item="<?=htmlspecialcharsbx($arResult['PRODUCT_ID'])?>"
			style="<?=($arResult['DEFAULT_DISPLAY']?'':'display: none;')?>"
			href="javascript:void(0)" rel="nofollow">
		<span>
			<?=$subscribeBtnName?>
		</span>
	</a>

	<input type="hidden" id="<?=htmlspecialcharsbx($arResult['BUTTON_ID'])?>_hidden">

	<script type="text/javascript">

	
		BX.message({
			CPST_SUBSCRIBE_POPUP_TITLE: '<?=GetMessageJS('CPST_SUBSCRIBE_POPUP_TITLE');?>',
			CPST_SUBSCRIBE_BUTTON_NAME: '<?=GetMessageJS('CPST_SUBSCRIBE_BUTTON_NAME');?>',
			CPST_SUBSCRIBE_BUTTON_CLOSE: '<?=GetMessageJS('CPST_SUBSCRIBE_BUTTON_CLOSE');?>',
			CPST_SUBSCRIBE_MANY_CONTACT_NOTIFY: '<?=GetMessageJS('CPST_SUBSCRIBE_MANY_CONTACT_NOTIFY');?>',
			CPST_SUBSCRIBE_ARTNUMBER: '<?=GetMessageJS('CPST_SUBSCRIBE_ARTNUMBER');?>',
			CPST_SUBSCRIBE_LABLE_CONTACT_INPUT: '<?=GetMessageJS('CPST_SUBSCRIBE_LABLE_CONTACT_INPUT');?>',
			CPST_SUBSCRIBE_VALIDATE_UNKNOW_ERROR: '<?=GetMessageJS('CPST_SUBSCRIBE_VALIDATE_UNKNOW_ERROR');?>',
			CPST_SUBSCRIBE_VALIDATE_ERROR_EMPTY_FIELD: '<?=GetMessageJS('CPST_SUBSCRIBE_VALIDATE_ERROR_EMPTY_FIELD');?>',
			CPST_SUBSCRIBE_VALIDATE_ERROR: '<?=GetMessageJS('CPST_SUBSCRIBE_VALIDATE_ERROR');?>',
			CPST_SUBSCRIBE_CAPTCHA_TITLE: '<?=GetMessageJS('CPST_SUBSCRIBE_CAPTCHA_TITLE');?>',
			CPST_STATUS_SUCCESS: '<?=GetMessageJS('CPST_STATUS_SUCCESS');?>',
			CPST_STATUS_ERROR: '<?=GetMessageJS('CPST_STATUS_ERROR');?>',
			CPST_ENTER_WORD_PICTURE: '<?=GetMessageJS('CPST_ENTER_WORD_PICTURE');?>',
			CPST_TITLE_ALREADY_SUBSCRIBED: '<?=GetMessageJS('CPST_TITLE_ALREADY_SUBSCRIBED');?>',
			CPST_POPUP_SUBSCRIBED_TITLE: '<?=GetMessageJS('CPST_POPUP_SUBSCRIBED_TITLE');?>',
			CPST_POPUP_SUBSCRIBED_TEXT: '<?=GetMessageJS('CPST_POPUP_SUBSCRIBED_TEXT');?>'
		});

		var <?=$jsObject?> = new JCCatalogProductSubscribe(<?=CUtil::phpToJSObject($paramsForJs, false, true)?>);

	</script>
<?endif;
