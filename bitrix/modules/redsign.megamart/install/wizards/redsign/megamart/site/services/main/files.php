<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID") || !defined("WIZARD_SITE_DIR"))
	return;

use \Bitrix\Main\Loader;

function ___writeToAreasFile($path, $text)
{
	//if(file_exists($fn) && !is_writable($abs_path) && defined("BX_FILE_PERMISSIONS"))
	//	@chmod($abs_path, BX_FILE_PERMISSIONS);

	$fd = @fopen($path, "wb");
	if(!$fd)
		return false;

	if(false === fwrite($fd, $text))
	{
		fclose($fd);
		return false;
	}

	fclose($fd);

	if(defined("BX_FILE_PERMISSIONS"))
		@chmod($path, BX_FILE_PERMISSIONS);
}

if (COption::GetOptionString("main", "upload_dir") == "")
	COption::SetOptionString("main", "upload_dir", "upload");

if (COption::GetOptionString("redsign.megamart", "wizard_installed", "N", WIZARD_SITE_ID) == "N"
	|| WIZARD_INSTALL_DEMO_DATA
)
{
	if(file_exists(WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/"))
	{
		CopyDirFiles(
			WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/",
			WIZARD_SITE_PATH,
			$rewrite = true,
			$recursive = true,
			$delete_after_copy = false
		);
	}
	COption::SetOptionString("redsign.megamart", "template_converted", "Y", "", WIZARD_SITE_ID);

	if (Loader::includeModule('catalog'))
	{
        $catalogPath = WIZARD_ABSOLUTE_PATH.'/site/services/main/ru/public_catalog/sale'; // TODO Lang path
	}
	else
	{
		$catalogPath = WIZARD_ABSOLUTE_PATH.'/site/services/main/ru/public_catalog/corp'; // TODO Lang path
	}

	if (file_exists($catalogPath))
	{
		CopyDirFiles(
			$catalogPath,
			WIZARD_SITE_PATH,
			$rewrite = true,
			$recursive = true,
			$delete_after_copy = true
		);
	}

	COption::SetOptionString("redsign.megamart", "template_converted", "Y", "", WIZARD_SITE_ID);
}
elseif (COption::GetOptionString("redsign.megamart", "template_converted", "N", WIZARD_SITE_ID) == "N")
{
	CopyDirFiles(
		WIZARD_ABSOLUTE_PATH."/site/services/main/".LANGUAGE_ID."/public_convert/",
		WIZARD_SITE_PATH,
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);
	CopyDirFiles(
		WIZARD_SITE_PATH."/include/company_logo.php",
		WIZARD_SITE_PATH."/include/company_logo_old.php",
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = true
	);

	COption::SetOptionString("redsign.megamart", "template_converted", "Y", "", WIZARD_SITE_ID);
}

$wizard =& $this->GetWizard();
// ___writeToAreasFile(WIZARD_SITE_PATH."include/company_name.php", $wizard->GetVar("siteName"));
___writeToAreasFile(WIZARD_SITE_PATH."include/footer/all_rights.php", $wizard->GetVar("siteCopy"));
// ___writeToAreasFile(WIZARD_SITE_PATH."include/schedule.php", $wizard->GetVar("siteSchedule"));
// ___writeToAreasFile(WIZARD_SITE_PATH."include/telephone.php", $wizard->GetVar("siteTelephone"));





/*
if ($wizard->GetVar("templateID") != "megamart")
{
	$arSocNets = array("shopFacebook" => "facebook", "shopTwitter" => "twitter", "shopVk" => "vk", "shopGooglePlus" => "google");
	foreach($arSocNets as $socNet=>$includeFile)
	{
		$curSocnet = $wizard->GetVar($socNet);
		if ($curSocnet)
		{
			$text = '<a href="'.$curSocnet.'"></a>';
			___writeToAreasFile(WIZARD_SITE_PATH."include/socnet_".$includeFile.".php", $text);
		}
	}
}
*/

if(COption::GetOptionString("redsign.megamart", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

WizardServices::PatchHtaccess(WIZARD_SITE_PATH);

// #SITE_DIR#
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".compact.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".footer.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".main.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".sidebar.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".sect_sidebar_outer.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."ajax/cart.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."ajax/wishlist.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."articles/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."auth/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."brands/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."catalog/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."delivery/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."faq/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."info/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."news/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."payment/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."payment/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."projects/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."sale-promotions/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."services/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."company/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."contacts/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."include/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."personal/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."ui/", Array("SITE_DIR" => WIZARD_SITE_DIR));

// #SITE_TEMPLATE_PATH#
// WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."include/", Array("SITE_TEMPLATE_PATH" => BX_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID));


// #MAP_SERVICE
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."contacts/index.php", Array("MAP_SERVICE" => $wizard->GetVar("mapService")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."contacts/shops/index.php", Array("MAP_SERVICE" => $wizard->GetVar("mapService")));

// #SHOP_EMAIL#
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."buy1click/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."company/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."faq/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/ask/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/ask_staff/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/product-ask/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/recall/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/review/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/service-order/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/contacts/map.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));


// #SALE_PHONE#
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/contacts/map.php", Array("SALE_PHONE" => $wizard->GetVar("siteTelephone")));

// #SALE_PHONE_URL#
$sPhoneUrl = preg_replace('/\D/', '', $wizard->GetVar("siteTelephone"));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/contacts/map.php", Array("SALE_PHONE_URL" => $sPhoneUrl));

// #SITE_SCHEDULE#
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/contacts/map.php", Array("SITE_SCHEDULE" => $wizard->GetVar("siteSchedule")));

// #SITE_SMALL_ADDRESS#
//$smallAdress = $wizard->GetVar("shopLocation").', '.$wizard->GetVar("shopAdr");
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/contacts/map.php", Array("SITE_SMALL_ADDRESS" => $wizard->GetVar("siteAddress")));

// SITE META
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/_index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_DESCRIPTION" => htmlspecialcharsbx($wizard->GetVar("siteMetaDescription"))));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_KEYWORDS" => htmlspecialcharsbx($wizard->GetVar("siteMetaKeywords"))));

// #REDSIGN_COPYRIGHT#
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID."/include/footers/type1.php", array('REDSIGN_COPYRIGHT' => GetMessage('REDSIGN_COPYRIGHT')));
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID."/include/footers/type2.php", array('REDSIGN_COPYRIGHT' => GetMessage('REDSIGN_COPYRIGHT')));

// #CART_PATH#
// $sCartPath = Loader::includeModule('catalog')
	// ? 'personal/cart/'
	// : 'cart/';

$sCartPath = 'personal/cart/';

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."ajax/cart.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."ajax/wishlist.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."brands/index.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."catalog/index.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/header/cart.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/index/products.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/index/viewed_items.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/articles/catalog_items.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/articles/services_items.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/news/catalog_items.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/news/services_items.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/sale/catalog_items.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/templates/sale/services_items.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."personal/.sidebar.menu.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."personal/index.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."personal/order/make/index.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."personal/wishlist/index.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."services/index.php", Array("CART_PATH" => $sCartPath));


// if (CModule::IncludeModule("sale"))
// {
	$addResult = \Bitrix\Main\UserConsent\Internals\AgreementTable::add(array(
		"ACTIVE" => \Bitrix\Main\UserConsent\Agreement::ACTIVE,
		"CODE" => "sale_default",
		"NAME" => GetMessage("WIZ_DEFAULT_USER_CONSENT_NAME"),
		"TYPE" => \Bitrix\Main\UserConsent\Agreement::TYPE_CUSTOM,
		"LANGUAGE_ID" => LANGUAGE_ID,
		"AGREEMENT_TEXT" => GetMessage("REDSIGN_AGREEMENT_TEXT"),
		"LABEL_TEXT" => GetMessage("REDSIGN_AGREEMENT_LABEL_TEXT", array("#URL#" => WIZARD_SITE_DIR."company/license_work/")),
		// "DATA_PROVIDER" => \Bitrix\Sale\UserConsent::DATA_PROVIDER_CODE,
	));
	if ($addResult->isSuccess())
	{
        $iAgreementId = $addResult->getId();

        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."articles/sect_sidebar_inner.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."buy1click/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."catalog/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."faq/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/ask/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/ask_staff/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/product-ask/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/recall/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/review/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."forms/service-order/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/footer/sender.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/index/sender.php", Array("USER_CONSENT_ID" => $iAgreementId));
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."news/sect_sidebar_inner.php", Array("USER_CONSENT_ID" => $iAgreementId));
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."personal/order/make/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."sale-promotions/sect_sidebar_inner.php", Array("USER_CONSENT_ID" => $iAgreementId));
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."services/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
	}
// }

$arUrlRewrite = array();
if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
{
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
}

$arNewUrlRewrite = array(
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."news/archive/([0-9]+)?/?([0-9]+)?/?#",
		"RULE" => "YEAR=\$1&MONTH=\$2",
		"ID" => "",
		"PATH" => WIZARD_SITE_DIR."news/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."news/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."projects/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."projects/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."articles/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."articles/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."company/gallery/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."company/gallery/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."company/staff/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."company/staff/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."contacts/shops/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."contacts/shops/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."sale-promotions/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."sale-promotions/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."brands/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."brands/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => WIZARD_SITE_DIR."catalog/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."services/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => WIZARD_SITE_DIR."services/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."personal/#",
		"RULE" => "",
		"ID" => "bitrix:sale.personal.section",
		"PATH" => WIZARD_SITE_DIR."personal/index.php",
	),
);

foreach ($arNewUrlRewrite as $arUrl)
{
	if (!in_array($arUrl, $arUrlRewrite))
	{
		\Bitrix\Main\UrlRewriter::add(WIZARD_SITE_ID, $arUrl);
	}
}
?>