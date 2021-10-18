<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

COption::SetOptionString("sale", "SHOP_SITE_".WIZARD_SITE_ID, WIZARD_SITE_ID);
// COption::SetOptionString("main", "auth_components_template", "flat", false, WIZARD_SITE_ID);

//socialservices
if (COption::GetOptionString("socialservices", "auth_services") == "")
{
	$bRu = (LANGUAGE_ID == 'ru');
	$arServices = array(
		"VKontakte" => "N",
		"MyMailRu" => "N",
		"Twitter" => "N",
		"Facebook" => "N",
		"Livejournal" => "Y",
		"YandexOpenID" => ($bRu? "Y":"N"),
		"Rambler" => ($bRu? "Y":"N"),
		"MailRuOpenID" => ($bRu? "Y":"N"),
		"Liveinternet" => ($bRu? "Y":"N"),
		"Blogger" => "Y",
		"OpenID" => "Y",
		"LiveID" => "N",
	);
	COption::SetOptionString("socialservices", "auth_services", serialize($arServices));
}

if (defined("ADDITIONAL_INSTALL"))
{
	return true;
}

COption::SetOptionString(
	"fileman",
	"propstypes",
	serialize(
		array(
			"description" => GetMessage("MAIN_OPT_DESCRIPTION"),
			"keywords" => GetMessage("MAIN_OPT_KEYWORDS"),
			"title" => GetMessage("MAIN_OPT_TITLE"),
			"keywords_inner" => GetMessage("MAIN_OPT_KEYWORDS_INNER"),
			"hide_outer_sidebar" => GetMessage("MAIN_OPT_HIDE_OUTER_SIDEBAR"),
			"hide_inner_sidebar" => GetMessage("MAIN_OPT_HIDE_INNER_SIDEBAR"),
			"outer_sidebar_file" => GetMessage("MAIN_OPT_OUTER_SIDEBAR_FILE"),
			"inner_sidebar_file" => GetMessage("MAIN_OPT_OUTER_INNER_FILE"),
			"wide_page" => GetMessage("MAIN_OPT_WIDE_PAGE"),
			"hide_section" => GetMessage("MAIN_OPT_HIDE_SECTION"),
			"hide_title" => GetMessage("MAIN_OPT_HIDE_TITLE"),
			// "hide_page_title" => GetMessage("MAIN_OPT_HIDE_PAGE_TITLE"),
		)
	),
	false,
	$siteID
);
COption::SetOptionInt("search", "suggest_save_days", 250);
COption::SetOptionString("search", "use_tf_cache", "Y");
COption::SetOptionString("search", "use_word_distance", "Y");
COption::SetOptionString("search", "use_social_rating", "Y");
COption::SetOptionString("iblock", "use_htmledit", "Y");

COption::SetOptionString("main", "captcha_registration", "N");
?>