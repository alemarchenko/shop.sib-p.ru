<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arServices = Array(
	"main" => Array(
		"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
		"STAGES" => Array(
			"files.php", // Copy bitrix files
			"search.php", // Indexing files
			"template.php", // Install template
			"theme.php", // Install theme
			"menu.php", // Install menu
			"settings.php",
		),
	),
	"catalog" => Array(
		"NAME" => GetMessage("SERVICE_CATALOG_SETTINGS"),
		"STAGES" => Array(
			"index.php"
		),
	),
	"iblock" => Array(
		"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
		"STAGES" => Array(
			"types.php", //IBlock types
			"banners_banner.php",
			"banners_side_banners.php",
            "catalog_catalog-sale.php",
            "catalog_catalog2-sale.php",
            "catalog_catalog3-sale.php",
			"catalog_catalog4-sale.php",
            "catalog_services.php",
            "content_articles.php",
            "content_brands.php",
            "content_faq.php",
            "content_features.php",
            "content_gallery.php",
            "content_history.php",
            "content_license.php",
            "content_news.php",
            "content_projects.php",
            "content_reviews.php",
            "content_sale_promotions.php",
            "content_shops.php",
            "content_staff.php",
            "content_vacancies.php",
            "forms_ask.php",
            "forms_ask_staff.php",
            "forms_faq.php",
            "forms_product_ask.php",
            "forms_recall.php",
            "forms_review.php",
            "forms_service_order.php",
			"forms_job.php",
            "system_regions.php",
			"references.php",
			"references2.php",
			"binds_props.php",
			"binds_items.php",
		),
	),
	"sale" => Array(
		"NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
		"STAGES" => Array(
			"locations.php",
			"step1.php",
			"step2.php",
			"step3.php"
		),
	),
	"advertising" => Array(
		"NAME" => GetMessage("SERVICE_ADVERTISING"),
	),
	"redsign" => Array(
		"NAME" => GetMessage("SERVICE_REDSIGN"),
        "STAGES" => Array(
			"devcom.php",
			"devfunc.php",
			"favorite.php",
			"forms.php",
			"grupper.php",
			// "location.php",
			"daysarticle.php",
			"quickbuy.php",
			"tuning.php",
			"settings.php",
		),
        "MODULE_ID" => "redsign.megamart"
	),
);
?>