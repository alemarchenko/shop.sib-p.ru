<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule('iblock'))
	return;

$arFilterIBlocks = array(
	array(
		'IBLOCK_TYPE' => 'catalog',
		'IBLOCK_CODE' => 'catalog',
		'IBLOCK_XML_ID' => 'redsign_megamart_catalog_catalog_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'offers',
		'IBLOCK_CODE' => 'offers',
		'IBLOCK_XML_ID' => 'redsign_megamart_offers_offers_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'news',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_news_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'articles',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_articles_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'sale_promotions',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_sale_promotions_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'brands',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_brands_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'staff',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_staff_'.WIZARD_SITE_ID,
	),
);

$arrFilterElements = array(
	"catalog" => array(
		"apple-ipad-32gb-wi-fi" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-ipad-mini-4-128gb-wi-fi" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-ipad-pro-9-7-32gb-wi-fi" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-iphone-8" => array(
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"ostavte-otzyv-i-poluchite-podarok-iphone-x",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-iphone-8-plus" => array(
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"ostavte-otzyv-i-poluchite-podarok-iphone-x",
					"besplatnaya_dostavka",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-iphone-x" => array(
			"RECOMMEND" => array(
				"catalog" => array(
					"apple-watch-series-2",
					"apple-watch-sport",
					"apple-watch",
					"apple-watch-edition",
					"apple-watch-series-2-nike-sport-band",
					"apple-macbook-early-2016",
					"apple-macbook-air-13-mid-2017",
				),
			),
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"pokupay-bolshe-plati-menshe",
					"ostavte-otzyv-i-poluchite-podarok-iphone-x",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-macbook-air-13-mid-2017" => array(
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"besplatnaya_dostavka",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-macbook-early-2016" => array(
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"ostavte-otzyv-i-poluchite-podarok-iphone-x",
					"besplatnaya_dostavka",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-tv-4k" => array(
			"RECOMMEND" => array(
				"catalog" => array(
					"apple-ipad-32gb-wi-fi",
					"apple-iphone-8-plus",
					"apple-watch",
					"apple-iphone-x",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-watch" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-watch-edition" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-watch-series-2" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-watch-series-2-nike-sport-band" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"apple-watch-sport" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"apple",
				),
			),
		),
		"bbs-sr" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"bbs",
				),
			),
		),
		"benq-gw2270" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"benq",
				),
			),
		),
		"chekhol-knizhka-xiaomi-dlya-xiaomi-redmi-4" => array(
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"pokupay-bolshe-plati-menshe",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"xiaomi",
				),
			),
		),
		"chekhol-samsung-dlya-samsung-galaxy-a5" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"samsung",
				),
			),
		),
		"dell-optiplex-7050" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"dell",
				),
			),
		),
		"diskovaya-pila-bosch-cs10-15-amp" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"bosch",
				),
			),
		),
		"diskovaya-pila-dewalt-dcs575b-60v" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"dewalt",
				),
			),
		),
		"diskovaya-pila-skil-5280-01-15-amp" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"skil",
				),
			),
		),
		"fotoapparat-canon-eos-200d-kit" => array(
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"pokupay-bolshe-plati-menshe",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"canon",
				),
			),
		),
		"fotoapparat-nikon-d3300-kit" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"nikon",
				),
			),
		),
		"fotoapparat-nikon-d5300-kit" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"nikon",
				),
			),
		),
		"fotoapparat-nikon-d850-kit" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"ryobi",
				),
			),
		),
		"gps-navigator-garmin-gpsmap-64st" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"garmin",
				),
			),
		),
		"gps-navigator-prology-imap-580tr" => array(
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"pokupay-bolshe-plati-menshe",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"prology",
				),
			),
		),
		"huami-amazfit-pace" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"huami",
				),
			),
		),
		"krossovki-dyneckt-s-naptik" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"diesel",
				),
			),
		),
		"leagoo-m5-plus" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"leagoo",
				),
			),
		),
		"lg-43uj630v" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"lg",
				),
			),
		),
		"lg-oled65c6v" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"lg",
				),
			),
		),
		"planshet-samsung-galaxy-tab-a-10-1-sm-t585" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"samsung",
				),
			),
		),
		"pylesos-bez-meshka-dlya-sbora-pyli-vc4100k" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"samsung",
				),
			),
		),
		"replica-b58" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"replica",
				),
			),
		),
		"samsung-gear-s2" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"samsung",
				),
			),
		),
		"samsung-gear-s3-frontier" => array(
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"v-nabore-deshevle",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"samsung",
				),
			),
		),
		"samsung-ue40k6500au" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"samsung",
				),
			),
		),
		"samsung-ue55mu6100u" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"samsung",
				),
			),
		),
		"smartfon-samsung-galaxy-a5-2017" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"samsung",
				),
			),
		),
		"smartfon-samsung-galaxy-s8" => array(
			"DEALS_REF" => array(
				"sale_promotions" => array(
					"v-nabore-deshevle",
				),
			),
			"BRAND_REF" => array(
				"brands" => array(
					"samsung",
				),
			),
		),
		"utyug-redmond-ri-c224-2200vt" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"redmond",
				),
			),
		),
		"utyug-starwind-sir3526-1600vt" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"starwind",
				),
			),
		),
		"walkabout-3-21" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"travelpro",
				),
			),
		),
		"zhiletka-channel-quilted-vest" => array(
			"BRAND_REF" => array(
				"brands" => array(
					"michael-kors",
				),
			),
		),
	),
	"offers" => array(
		"leagoo-m5-plus-black" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"leagoo-m5-plus",
				),
			),
		),
		"leagoo-m5-plus-gray" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"leagoo-m5-plus",
				),
			),
		),
		"leagoo-m5-plus-white" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"leagoo-m5-plus",
				),
			),
		),
		"leagoo-m5-plus-yellow" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"leagoo-m5-plus",
				),
			),
		),
		"apple-iphone-x-64-gb-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-x",
				),
			),
		),
		"apple-iphone-x-64-gb-seryy-kosmos" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-x",
				),
			),
		),
		"apple-iphone-x-256-gb-seryy-kosmos" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-x",
				),
			),
		),
		"apple-iphone-x-256-gb-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-x",
				),
			),
		),
		"apple-iphone-8-64-gb-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8",
				),
			),
		),
		"apple-iphone-8-256-gb-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8",
				),
			),
		),
		"apple-iphone-8-64-gb-zolotoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8",
				),
			),
		),
		"apple-iphone-8-256-gb-zolotoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8",
				),
			),
		),
		"apple-iphone-8-64-gb-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8",
				),
			),
		),
		"apple-iphone-8-256-gb-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8",
				),
			),
		),
		"apple-iphone-8-plus-64-gb-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8-plus",
				),
			),
		),
		"apple-iphone-8-plus-256-gb-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8-plus",
				),
			),
		),
		"apple-iphone-8-plus-64-gb-zolotoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8-plus",
				),
			),
		),
		"apple-iphone-8-plus-256-gb-zolotoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8-plus",
				),
			),
		),
		"apple-iphone-8-plus-64-gb-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8-plus",
				),
			),
		),
		"apple-iphone-8-plus-256-gb-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-iphone-8-plus",
				),
			),
		),
		"smartfon-samsung-galaxy-s8-64-gb-chyernyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-s8",
				),
			),
		),
		"smartfon-samsung-galaxy-s8-64-gb-zolotoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-s8",
				),
			),
		),
		"smartfon-samsung-galaxy-s8-64-gb-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-s8",
				),
			),
		),
		"smartfon-samsung-galaxy-a5-2017-32-gb-chyernyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-a5-2017",
				),
			),
		),
		"smartfon-samsung-galaxy-a5-2017-32-gb-zolotoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-a5-2017",
				),
			),
		),
		"smartfon-samsung-galaxy-a5-2017-32-gb-siniy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-a5-2017",
				),
			),
		),
		"apple-ipad-32gb-wi-fi-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-32gb-wi-fi",
				),
			),
		),
		"apple-ipad-32gb-wi-fi-zolotoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-32gb-wi-fi",
				),
			),
		),
		"apple-ipad-32gb-wi-fi-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-32gb-wi-fi",
				),
			),
		),
		"planshet-samsung-galaxy-tab-a-10-1-sm-t585-16-gb-chyernyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"planshet-samsung-galaxy-tab-a-10-1-sm-t585",
				),
			),
		),
		"planshet-samsung-galaxy-tab-a-10-1-sm-t585-16-gb-belyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"planshet-samsung-galaxy-tab-a-10-1-sm-t585",
				),
			),
		),
		"planshet-samsung-galaxy-tab-a-10-1-sm-t585-16-gb-siniy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"planshet-samsung-galaxy-tab-a-10-1-sm-t585",
				),
			),
		),
		"apple-ipad-mini-4-128gb-wi-fi-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-mini-4-128gb-wi-fi",
				),
			),
		),
		"apple-ipad-mini-4-128gb-wi-fi-chyernyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-mini-4-128gb-wi-fi",
				),
			),
		),
		"apple-ipad-mini-4-128gb-wi-fi-zolotoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-mini-4-128gb-wi-fi",
				),
			),
		),
		"apple-ipad-pro-9-7-32gb-wi-fi-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-pro-9-7-32gb-wi-fi",
				),
			),
		),
		"apple-ipad-pro-9-7-32gb-wi-fi-zolotoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-pro-9-7-32gb-wi-fi",
				),
			),
		),
		"apple-ipad-pro-9-7-32gb-wi-fi-rozovyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-pro-9-7-32gb-wi-fi",
				),
			),
		),
		"apple-ipad-pro-9-7-32gb-wi-fi-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-ipad-pro-9-7-32gb-wi-fi",
				),
			),
		),
		"smartfon-samsung-galaxy-s8-64-gb-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-s8",
				),
			),
		),
		"smartfon-samsung-galaxy-s8-64-gb-siniy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-s8",
				),
			),
		),
		"smartfon-samsung-galaxy-s8-64-gb-rozovyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-s8",
				),
			),
		),
		"samsung-gear-s2-tyemno-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"samsung-gear-s2",
				),
			),
		),
		"samsung-gear-s2-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"samsung-gear-s2",
				),
			),
		),
		"samsung-gear-s3-frontier-tyemno-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"samsung-gear-s3-frontier",
				),
			),
		),
		"samsung-gear-s3-frontier-chyernyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"samsung-gear-s3-frontier",
				),
			),
		),
		"fotoapparat-canon-eos-200d-kit-chyernyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"fotoapparat-canon-eos-200d-kit",
				),
			),
		),
		"fotoapparat-canon-eos-200d-kit-serebristyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"fotoapparat-canon-eos-200d-kit",
				),
			),
		),
		"fotoapparat-canon-eos-200d-kit-belyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"fotoapparat-canon-eos-200d-kit",
				),
			),
		),
		"fotoapparat-nikon-d3300-kit-chyernyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"fotoapparat-nikon-d3300-kit",
				),
			),
		),
		"fotoapparat-nikon-d3300-kit-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"fotoapparat-nikon-d3300-kit",
				),
			),
		),
		"apple-watch-sport-korpus-38-mm-iz-alyuminiya-tsveta-rozovoe-zoloto-sirenevyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-38-mm-iz-alyuminiya-tsveta-seryy-kosmos-chyernyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-38-mm-iz-zolotistogo-alyuminiya-mramorno-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-38-mm-iz-serebristogo-alyuminiya-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-38-mm-iz-serebristogo-alyuminiya-goluboy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-38-mm-iz-serebristogo-alyuminiya-oranzhevyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-42-mm-iz-alyuminiya-tsveta-rozovoe-zoloto-bezhevyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-42-mm-iz-alyuminiya-tsveta-seryy-kosmos-chyernyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-42-mm-iz-zolotistogo-alyuminiya-tyemno-siniy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-42-mm-iz-serebristogo-alyuminiya-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-42-mm-iz-serebristogo-alyuminiya-goluboy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-sport-korpus-42-mm-iz-serebristogo-alyuminiya-oranzhevyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-sport",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-tsveta-seryy-kosmos-blochnyy-braslet-iz-nerzhaveyu" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-tsveta-seryy-kosmos-chyernyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-blochnyy-braslet-iz-nerzhaveyushchey-stali" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-korichnevyy-remeshok-s-klassicheskoy-pryazhkoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-krasnyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-milanskiy-setchatyy-braslet" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-svetlo-rozovyy-remeshok-s-sovremennoy-pryazhkoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-tyemno-siniy-remeshok-s-sovremennoy-pryazhkoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-38-mm-iz-nerzhaveyushchey-stali-chyernyy-remeshok-s-klassicheskoy-pryazhkoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stali-tsveta-seryy-kosmos-blochnyy-braslet-iz-nerzhaveyu" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stali-tsveta-seryy-kosmos-chyernyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stali-bezhevyy-kozhannyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stali-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stali-korichnevyy-remeshok-s-klassicheskoy-pryazhkoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stali-krasnyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stali-milanskiy-setchatyy-braslet" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stali-siniy-kozhannyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stali-chyernyy-remeshok-s-klassicheskoy-pryazhkoy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-korpus-42-mm-iz-nerzhaveyushchey-stli-blochnyy-braslet-iz-nerzhaveyushchey-stali" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch",
				),
			),
		),
		"apple-watch-edition-korpus-38-mm-iz-18-karatnogo-zhyeltogo-zolota-chyernyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-edition",
				),
			),
		),
		"apple-watch-edition-korpus-38-mm-iz-18-karatnogo-zhyeltogo-zolota-yarko-krasnyy-remeshok-s-sovremenn" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-edition",
				),
			),
		),
		"apple-watch-edition-korpus-38-mm-iz-18-karatnogo-rozovogo-zolota-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-edition",
				),
			),
		),
		"apple-watch-edition-korpus-38-mm-iz-18-karatnogo-rozovogo-zolota-remeshok-telesnogo-tsveta-s-sovreme" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-edition",
				),
			),
		),
		"apple-watch-edition-korpus-42-mm-iz-18-karatnogo-zhyeltogo-zolota-chyernyy-remeshok-s-klassicheskoy-" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-edition",
				),
			),
		),
		"apple-watch-edition-korpus-42-mm-iz-18-karatnogo-zhyeltogo-zolota-chyernyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-edition",
				),
			),
		),
		"apple-watch-edition-korpus-42-mm-iz-18-karatnogo-rozovogo-zolota-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-edition",
				),
			),
		),
		"apple-watch-edition-korpus-42-mm-iz-18-karatnogo-rozovogo-zolota-chyernyy-remeshok-s-klassicheskoy-p" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-edition",
				),
			),
		),
		"huami-amazfit-pace-chyernyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"huami-amazfit-pace",
				),
			),
		),
		"huami-amazfit-pace-krasnyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"huami-amazfit-pace",
				),
			),
		),
		"apple-watch-nike-korpus-38-mm-iz-alyuminiya-tsveta-seryy-kosmos-sportivnyy-remeshok-nike-chyernogo-tsveta" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2-nike-sport-band",
				),
			),
		),
		"apple-watch-nike-korpus-38-mm-iz-alyuminiya-tsveta-seryy-kosmos-sportivnyy-remeshok-nike-tsveta-chyernyy-salatovyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2-nike-sport-band",
				),
			),
		),
		"apple-watch-nike-korpus-38-mm-iz-alyuminiya-tsveta-seryy-kosmos-sportivnyy-remeshok-nike-tsveta-chyernyy-kholodnyy-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2-nike-sport-band",
				),
			),
		),
		"apple-watch-nike-korpus-38-mm-iz-serebristogo-alyuminiya-sportivnyy-remeshok-nike-tsveta-chistaya-platina-belyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2-nike-sport-band",
				),
			),
		),
		"apple-watch-nike-korpus-42-mm-iz-alyuminiya-tsveta-seryy-kosmos-sportivnyy-remeshok-nike-tsveta-antratsitovyy-chyernyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2-nike-sport-band",
				),
			),
		),
		"apple-watch-nike-korpus-42-mm-iz-alyuminiya-tsveta-seryy-kosmos-sportivnyy-remeshok-nike-tsveta-chyernyy-salatovyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2-nike-sport-band",
				),
			),
		),
		"apple-watch-nike-korpus-42-mm-iz-alyuminiya-tsveta-seryy-kosmos-sportivnyy-remeshok-nike-tsveta-chyernyy-kholodnyy-seryy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2-nike-sport-band",
				),
			),
		),
		"apple-watch-nike-korpus-42-mm-iz-serebristogo-alyuminiya-sportivnyy-remeshok-nike-tsveta-listovoe-serebro-belyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2-nike-sport-band",
				),
			),
		),
		"apple-watch-nike-korpus-42-mm-iz-serebristogo-alyuminiya-sportivnyy-remeshok-nike-tsveta-listovoe-serebro-salatovyy" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2-nike-sport-band",
				),
			),
		),
		"zhiletka-channel-quilted-vest-chyernyy-l" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"zhiletka-channel-quilted-vest",
				),
			),
		),
		"zhiletka-channel-quilted-vest-chyernyy-m" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"zhiletka-channel-quilted-vest",
				),
			),
		),
		"zhiletka-channel-quilted-vest-chyernyy-s" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"zhiletka-channel-quilted-vest",
				),
			),
		),
		"zhiletka-channel-quilted-vest-krasnyy-l" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"zhiletka-channel-quilted-vest",
				),
			),
		),
		"zhiletka-channel-quilted-vest-krasnyy-m" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"zhiletka-channel-quilted-vest",
				),
			),
		),
		"zhiletka-channel-quilted-vest-krasnyy-s" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"zhiletka-channel-quilted-vest",
				),
			),
		),
		"zhiletka-channel-quilted-vest-seryy-l" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"zhiletka-channel-quilted-vest",
				),
			),
		),
		"zhiletka-channel-quilted-vest-seryy-m" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"zhiletka-channel-quilted-vest",
				),
			),
		),
		"zhiletka-channel-quilted-vest-seryy-s" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"zhiletka-channel-quilted-vest",
				),
			),
		),
		"krossovki-dyneckt-s-naptik-krasnyy-37" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"krossovki-dyneckt-s-naptik",
				),
			),
		),
		"krossovki-dyneckt-s-naptik-krasnyy-42" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"krossovki-dyneckt-s-naptik",
				),
			),
		),
		"krossovki-dyneckt-s-naptik-krasnyy-39" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"krossovki-dyneckt-s-naptik",
				),
			),
		),
		"krossovki-dyneckt-s-naptik-siniy-37" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"krossovki-dyneckt-s-naptik",
				),
			),
		),
		"krossovki-dyneckt-s-naptik-siniy-42" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"krossovki-dyneckt-s-naptik",
				),
			),
		),
		"krossovki-dyneckt-s-naptik-siniy-39" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"krossovki-dyneckt-s-naptik",
				),
			),
		),
		"krossovki-dyneckt-s-naptik-zhyeltyy-37" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"krossovki-dyneckt-s-naptik",
				),
			),
		),
		"krossovki-dyneckt-s-naptik-zhyeltyy-42" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"krossovki-dyneckt-s-naptik",
				),
			),
		),
		"krossovki-dyneckt-s-naptik-zhyeltyy-39" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"krossovki-dyneckt-s-naptik",
				),
			),
		),
		"apple-watch-series-2-korpus-38-mm-iz-zolotistogo-alyuminiya-seryy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2",
				),
			),
		),
		"apple-watch-series-2-korpus-38-mm-iz-nerzhaveyushchey-stali-tsveta-seryy-kosmos-chyernyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2",
				),
			),
		),
		"apple-watch-series-2-korpus-38-mm-iz-nerzhaveyushchey-stali-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2",
				),
			),
		),
		"apple-watch-series-2-korpus-38-mm-iz-serebristogo-alyuminiya-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2",
				),
			),
		),
		"apple-watch-series-2-korpus-42-mm-iz-alyuminiya-tsveta-rozovoe-zoloto-sirenevyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2",
				),
			),
		),
		"apple-watch-series-2-korpus-42-mm-iz-zolotistogo-alyuminiya-korichnevyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2",
				),
			),
		),
		"apple-watch-series-2-korpus-42-mm-iz-nerzhaveyushchey-stali-tsveta-seryy-kosmos-chyernyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2",
				),
			),
		),
		"apple-watch-series-2-korpus-42-mm-iz-nerzhaveyushchey-stali-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2",
				),
			),
		),
		"apple-watch-series-2-korpus-42-mm-iz-serebristogo-alyuminiya-belyy-sportivnyy-remeshok" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-watch-series-2",
				),
			),
		),
		"apple-tv-4k-32gb" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-tv-4k",
				),
			),
		),
		"apple-tv-4k-64gb" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"apple-tv-4k",
				),
			),
		),
		"replica-b58-r17-7-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r16-7-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r15-7-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r18-7-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r17-8-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r18-8-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r17-7" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r16-7" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r17-8" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r16-8" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"replica-b58-r18-8" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"replica-b58",
				),
			),
		),
		"k-k-torus" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"k-k-torus",
				),
			),
		),
		"bbs-sr-r17-7-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"bbs-sr",
				),
			),
		),
		"bbs-sr-r16-7-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"bbs-sr",
				),
			),
		),
		"bbs-sr-r18-7-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"bbs-sr",
				),
			),
		),
		"bbs-sr-r17-8-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"bbs-sr",
				),
			),
		),
		"bbs-sr-r16-8-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"bbs-sr",
				),
			),
		),
		"bbs-sr-r19-8-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"bbs-sr",
				),
			),
		),
		"bbs-sr-r18-8-5" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"bbs-sr",
				),
			),
		),
		"bbs-sr-r19-10" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"bbs-sr",
				),
			),
		),
		"bbs-sr-r18-10" => array(
			"CML2_LINK" => array(
				"catalog" => array(
					"bbs-sr",
				),
			),
		),
	),
	"news" => array(
		"budushchie-mac-budut-predlagatsya-tolko-v-odnoy-konfiguratsii" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"apple-macbook-air-13-mid-2017",
					"apple-macbook-early-2016",
				),
			),
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"apple-vernet-v-prodazhu-iphone-x-iz-za-nizkikh-prodazh-novykh-modeley",
					"kak-sozdat-prototip-stranits-dlya-razrabotki-sayta-chtoby-vse-bylo-khorosho",
				),
			),
			"INTERESTING_SALE" => array(
				"sale_promotions" => array(
					"besplatnaya_dostavka",
					"pri-pokupke-nastolnogo-kompyutera-monitor-v-podarok",
				),
			),
			"BIND_SERVICE" => array(
				"services" => array(
					"arenda-zvuka-i-sveta",
					"vyezd-vracha",
					"remont-kvartir",
					"ekskursiya-po-gorodu",
				),
			),
			"BIND_STAFF2" => array(
				"staff" => array(
					"kornishina-irina",
					"merkulova-anna",
				),
			),
		),
		"samsung-galaxy-note-9-vs-google-pixel-2-xl-chya-kamera-luchshe" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-s8",
					"smartfon-samsung-galaxy-a5-2017",
				),
			),
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"apple-vernet-v-prodazhu-iphone-x-iz-za-nizkikh-prodazh-novykh-modeley",
					"kak-sozdat-prototip-stranits-dlya-razrabotki-sayta-chtoby-vse-bylo-khorosho",
					"obnovlennye-teplovye-karty-tsen-na-zhilye-v-yandeks-nedvizhimost-stali-informativnee",
					"bespilotnyy-avtomobil-yandeks-taksi-proekhal-bolee-1000-km-po-zimney-moskve",
				),
			),
		),
		"samsung-vypustila-novyy-premialnyy-smartfon-kotoryy-vyglyadit-shikarno" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-s8",
					"smartfon-samsung-galaxy-a5-2017",
				),
			),
			"INTERESTING_SALE" => array(
				"sale_promotions" => array(
					"skidki-pri-pokupke-neskolkikh-veshchey",
					"pri-pokupke-nastolnogo-kompyutera-monitor-v-podarok",
				),
			),
			"BIND_SERVICE" => array(
				"services" => array(
					"puteshestvie-na-vozdushnym-share",
					"otdelochnye-raboty",
					"shpaklevochnye-raboty",
					"arenda-zvuka-i-sveta",
				),
			),
		),
		"samyy-dorogoy-smartfon-apple-raskryty-sekrety-iphone-x-plus" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"apple-iphone-x",
					"apple-iphone-8",
					"apple-iphone-8-plus",
					"apple-ipad-32gb-wi-fi",
				),
			),
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"apple-vernet-v-prodazhu-iphone-x-iz-za-nizkikh-prodazh-novykh-modeley",
					"kak-sozdat-prototip-stranits-dlya-razrabotki-sayta-chtoby-vse-bylo-khorosho",
				),
			),
			"BIND_SERVICE" => array(
				"services" => array(
					"ekskursiya-po-gorodu",
					"velopoezdki",
					"meditsinskie-analizy",
					"vyezd-vracha",
				),
			),
		),
		"treyler-novogo-korolya-lva-pobil-rekord-po-prosmotram" => array(
			"BIND_STAFF" => array(
				"staff" => array(
					"borisov-stanislav",
					"erunova-valentina",
					"matveev-dmitriy",
				),
			),
		),
		"tsvet-korpusa-novykh-iphone-budet-menyatsya-ot-vzglyada-polzovatelya" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"apple-iphone-8",
					"apple-iphone-8-plus",
					"apple-iphone-x",
				),
			),
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"kak-nayti-balans-mezhdu-internetom-i-realnoy-zhiznyu-rasskazyvaem-obsuzhdaem",
					"sankt-peterburg-popal-v-troyku-luchshikh-gorodov-dlya-otdykha-sostoyatelnykh-rossiyan",
					"kak-sozdat-prototip-stranits-dlya-razrabotki-sayta-chtoby-vse-bylo-khorosho",
				),
			),
		),
	),
	"articles" => array(
		"kak-sozdat-prototip-stranits-dlya-razrabotki-sayta-chtoby-vse-bylo-khorosho" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"apple-ipad-32gb-wi-fi",
					"apple-ipad-mini-4-128gb-wi-fi",
					"apple-macbook-air-13-mid-2017",
					"apple-watch-series-2-nike-sport-band",
				),
			),
			"INTERESTING_NEWS" => array(
				"news" => array(
					"samsung-vypustila-novyy-premialnyy-smartfon-kotoryy-vyglyadit-shikarno",
					"izvestnyy-bankovskiy-troyan-nachal-podbiratsya-k-kodam-windows",
					"budushchie-mac-budut-predlagatsya-tolko-v-odnoy-konfiguratsii",
				),
			),
			"INTERESTING_SALE" => array(
				"sale_promotions" => array(
					"v-nabore-deshevle",
					"sdelay-zakaz-na-summu-bolee-5000-rubley-i-poluchili-skidku-15",
				),
			),
			"BIND_SERVICE" => array(
				"services" => array(
					"otdelochnye-raboty",
					"skoraya-pomoshch",
					"puteshestvie-na-vozdushnym-share",
					"foto-videosemka-na-svadbu",
				),
			),
			"BIND_STAFF2" => array(
				"staff" => array(
					"borisov-stanislav",
					"kornishina-irina",
				),
			),
		),
		"zashchishchaem-avtorskiy-kontent-v-internete-otchet-po-borbe-s-onlayn-piratstvom" => array(
			"INTERESTING_NEWS" => array(
				"news" => array(
					"izvestnyy-bankovskiy-troyan-nachal-podbiratsya-k-kodam-windows",
					"tsvet-korpusa-novykh-iphone-budet-menyatsya-ot-vzglyada-polzovatelya",
					"treyler-novogo-korolya-lva-pobil-rekord-po-prosmotram",
				),
			),
			"BIND_STAFF" => array(
				"staff" => array(
					"nikolay-strekozhin",
					"andrey-volkov",
					"evgeniy-shinorin",
				),
			),
		),
		"kak-nayti-balans-mezhdu-internetom-i-realnoy-zhiznyu-rasskazyvaem-obsuzhdaem" => array(
			"INTERESTING_SALE" => array(
				"sale_promotions" => array(
					"besplatnaya_dostavka",
					"sdelay-zakaz-na-summu-bolee-5000-rubley-i-poluchili-skidku-15",
					"pri-pokupke-nastolnogo-kompyutera-monitor-v-podarok",
				),
			),
			"BIND_SERVICE" => array(
				"services" => array(
					"arenda-zvuka-i-sveta",
					"remont-kvartir",
					"shpaklevochnye-raboty",
					"meditsinskie-analizy",
				),
			),
		),
		"povyshenie-prozrachnosti-i-kontrolya-nad-reklamoy-v-google-adwords" => array(
			"INTERESTING_NEWS" => array(
				"news" => array(
					"v-playstation-store-startovala-novaya-rasprodazha-vzryvnoe-predlozhenie",
					"samyy-dorogoy-smartfon-apple-raskryty-sekrety-iphone-x-plus",
					"v-rossii-otkryt-priem-zakazov-na-tesla-model-3",
				),
			),
			"INTERESTING_SALE" => array(
				"sale_promotions" => array(
					"v-nabore-deshevle",
					"podarochnye-sertifikaty-megamart-na-10",
				),
			),
		),
		"obnovlennye-teplovye-karty-tsen-na-zhilye-v-yandeks-nedvizhimost-stali-informativnee" => array(
			"INTERESTING_NEWS" => array(
				"news" => array(
					"samsung-galaxy-note-9-vs-google-pixel-2-xl-chya-kamera-luchshe",
					"izvestnyy-bankovskiy-troyan-nachal-podbiratsya-k-kodam-windows",
					"budushchie-mac-budut-predlagatsya-tolko-v-odnoy-konfiguratsii",
				),
			),
		),
		"bespilotnyy-avtomobil-yandeks-taksi-proekhal-bolee-1000-km-po-zimney-moskve" => array(
			"BIND_SERVICE" => array(
				"services" => array(
					"pokraska-sten",
					"skoraya-pomoshch",
					"ekskursiya-po-gorodu",
					"shpaklevochnye-raboty",
				),
			),
		),
		"apple-vernet-v-prodazhu-iphone-x-iz-za-nizkikh-prodazh-novykh-modeley" => array(
			"INTERESTING_NEWS" => array(
				"news" => array(
					"v-playstation-store-startovala-novaya-rasprodazha-vzryvnoe-predlozhenie",
					"samsung-galaxy-note-9-vs-google-pixel-2-xl-chya-kamera-luchshe",
					"treyler-novogo-korolya-lva-pobil-rekord-po-prosmotram",
				),
			),
			"BIND_SERVICE" => array(
				"services" => array(
					"kosmeticheskiy-remont",
					"otdelochnye-raboty",
					"velopoezdki",
					"triatlon",
				),
			),
		),
		"sankt-peterburg-popal-v-troyku-luchshikh-gorodov-dlya-otdykha-sostoyatelnykh-rossiyan" => array(
			"INTERESTING_NEWS" => array(
				"news" => array(
					"samsung-galaxy-note-9-vs-google-pixel-2-xl-chya-kamera-luchshe",
					"treyler-novogo-korolya-lva-pobil-rekord-po-prosmotram",
					"v-rossii-otkryt-priem-zakazov-na-tesla-model-3",
				),
			),
		),
		"mercedes-benz-nauchit-svoi-avtomobili-s-bespilotnikami-obshchatsya-s-lyudmi" => array(
			"INTERESTING_NEWS" => array(
				"news" => array(
					"v-rossii-otkryt-priem-zakazov-na-tesla-model-3",
					"samsung-vypustila-novyy-premialnyy-smartfon-kotoryy-vyglyadit-shikarno",
					"v-playstation-store-startovala-novaya-rasprodazha-vzryvnoe-predlozhenie",
				),
			),
		),
	),
	"sale_promotions" => array(
		"besplatnaya_dostavka" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"apple-macbook-air-13-mid-2017",
					"apple-macbook-early-2016",
					"apple-iphone-x",
					"apple-iphone-8-plus",
				),
			),
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"zashchishchaem-avtorskiy-kontent-v-internete-otchet-po-borbe-s-onlayn-piratstvom",
					"kak-sozdat-prototip-stranits-dlya-razrabotki-sayta-chtoby-vse-bylo-khorosho",
				),
			),
			"BIND_SERVICE" => array(
				"services" => array(
					"velopoezdki",
					"arenda-zvuka-i-sveta",
					"shpaklevochnye-raboty",
					"skoraya-pomoshch",
				),
			),
		),
		"podarochnye-sertifikaty-megamart-na-10" => array(
			"INTERESTING_NEWS" => array(
				"news" => array(
					"budushchie-mac-budut-predlagatsya-tolko-v-odnoy-konfiguratsii",
					"samyy-dorogoy-smartfon-apple-raskryty-sekrety-iphone-x-plus",
					"v-rossii-otkryt-priem-zakazov-na-tesla-model-3",
				),
			),
			"BIND_STAFF" => array(
				"staff" => array(
					"kornishina-irina",
					"merkulova-anna",
					"filipin-evgeniy",
				),
			),
		),
		"ostavte-otzyv-i-poluchite-podarok-iphone-x" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"apple-iphone-8",
					"apple-iphone-8-plus",
					"apple-iphone-x",
					"apple-macbook-early-2016",
				),
			),
			"BIND_STAFF" => array(
				"staff" => array(
					"kornishina-irina",
					"filipin-evgeniy",
					"merkulova-anna",
				),
			),
		),
		"pri-pokupke-nastolnogo-kompyutera-monitor-v-podarok" => array(
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"apple-vernet-v-prodazhu-iphone-x-iz-za-nizkikh-prodazh-novykh-modeley",
					"zashchishchaem-avtorskiy-kontent-v-internete-otchet-po-borbe-s-onlayn-piratstvom",
				),
			),
		),
		"skidki-pri-pokupke-neskolkikh-veshchey" => array(
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"zashchishchaem-avtorskiy-kontent-v-internete-otchet-po-borbe-s-onlayn-piratstvom",
					"sankt-peterburg-popal-v-troyku-luchshikh-gorodov-dlya-otdykha-sostoyatelnykh-rossiyan",
				),
			),
			"BIND_SERVICE" => array(
				"services" => array(
					"arenda-zvuka-i-sveta",
					"otdelochnye-raboty",
					"triatlon",
					"ekskursiya-po-gorodu",
				),
			),
			"BIND_STAFF" => array(
				"staff" => array(
					"merkulova-anna",
					"filipin-evgeniy",
					"kornishina-irina",
				),
			),
		),
		"v-nabore-deshevle" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"smartfon-samsung-galaxy-s8",
					"samsung-gear-s3-frontier",
				),
			),
			"INTERESTING_NEWS" => array(
				"news" => array(
					"budushchie-mac-budut-predlagatsya-tolko-v-odnoy-konfiguratsii",
					"v-playstation-store-startovala-novaya-rasprodazha-vzryvnoe-predlozhenie",
					"izvestnyy-bankovskiy-troyan-nachal-podbiratsya-k-kodam-windows",
				),
			),
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"mercedes-benz-nauchit-svoi-avtomobili-s-bespilotnikami-obshchatsya-s-lyudmi",
					"sankt-peterburg-popal-v-troyku-luchshikh-gorodov-dlya-otdykha-sostoyatelnykh-rossiyan",
				),
			),
		),
		"vospominaniya-vmeste-s-canon" => array(
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"kak-nayti-balans-mezhdu-internetom-i-realnoy-zhiznyu-rasskazyvaem-obsuzhdaem",
					"sankt-peterburg-popal-v-troyku-luchshikh-gorodov-dlya-otdykha-sostoyatelnykh-rossiyan",
				),
			),
			"BIND_SERVICE" => array(
				"services" => array(
					"vyezd-vracha",
					"meditsinskie-analizy",
					"puteshestvie-na-vozdushnym-share",
					"triatlon",
				),
			),
			"BIND_STAFF" => array(
				"staff" => array(
					"kornishina-irina",
					"merkulova-anna",
					"filipin-evgeniy",
				),
			),
		),
		"pokupay-bolshe-plati-menshe" => array(
			"BIND_CATALOG" => array(
				"catalog" => array(
					"fotoapparat-canon-eos-200d-kit",
					"apple-iphone-x",
					"chekhol-knizhka-xiaomi-dlya-xiaomi-redmi-4",
					"gps-navigator-prology-imap-580tr",
				),
			),
			"INTERESTING_NEWS" => array(
				"news" => array(
					"budushchie-mac-budut-predlagatsya-tolko-v-odnoy-konfiguratsii",
					"v-playstation-store-startovala-novaya-rasprodazha-vzryvnoe-predlozhenie",
					"tsvet-korpusa-novykh-iphone-budet-menyatsya-ot-vzglyada-polzovatelya",
				),
			),
			"INTERESTING_ARTICLE" => array(
				"articles" => array(
					"kak-nayti-balans-mezhdu-internetom-i-realnoy-zhiznyu-rasskazyvaem-obsuzhdaem",
					"sankt-peterburg-popal-v-troyku-luchshikh-gorodov-dlya-otdykha-sostoyatelnykh-rossiyan",
				),
			),
			"BIND_STAFF" => array(
				"staff" => array(
					"kornishina-irina",
					"merkulova-anna",
					"filipin-evgeniy",
				),
			),
		),
		"sdelay-zakaz-na-summu-bolee-5000-rubley-i-poluchili-skidku-15" => array(
			"INTERESTING_NEWS" => array(
				"news" => array(
					"v-playstation-store-startovala-novaya-rasprodazha-vzryvnoe-predlozhenie",
					"izvestnyy-bankovskiy-troyan-nachal-podbiratsya-k-kodam-windows",
					"samsung-galaxy-note-9-vs-google-pixel-2-xl-chya-kamera-luchshe",
				),
			),
		),
	),
);

$arrFilterElementIDs = array();
$arElementsUsed = array();
$arrIBlockIDs = array();


foreach ($arFilterIBlocks as $arFilterIBlock)
{
	$rsIBlock = CIBlock::GetList(array(), array( 'TYPE' => $arFilterIBlock['IBLOCK_TYPE'], 'CODE' => $arFilterIBlock['IBLOCK_CODE'], 'XML_ID' => $arFilterIBlock['IBLOCK_XML_ID'] ));
	if ($arIBlock = $rsIBlock->Fetch())
	{
		$arrIBlockIDs[$arFilterIBlock['IBLOCK_CODE']] = (int)$arIBlock['ID'];
	}
	unset($rsIBlock, $arIBlock);
}
unset($arFilterIBlock);


foreach ($arrFilterElements as $sCatalogCode1 => $arFilterCatalog1)
{
	foreach ($arFilterCatalog1 as $sElementCode1 => $arFilterElement1)
	{
		$arElementsUsed[$sCatalogCode1][] = $sElementCode1;
		foreach ($arFilterElement1 as $sPropertyCode => $arPropertyValue)
		{
			foreach ($arPropertyValue as $sCatalogCode2 => $arFilterCatalog2)
			{
				foreach ($arFilterCatalog2 as $sElementCode2)
				{
						$arElementsUsed[$sCatalogCode2][] = $sElementCode2;
				}
				unset($sElementCode2);
			}
			unset($sCatalogCode2, $arFilterCatalog2);
		}
		unset($sPropertyCode, $arPropertyValue);
	}
	unset($sElementCode1, $arFilterElement1);
}
unset($sCatalogCode1, $arFilterCatalog1);


foreach ($arElementsUsed as $sCatalogCode => $arCatalogElementsUsed)
{
	$arElementsUsed[$sCatalogCode] = array_unique($arCatalogElementsUsed);
}
unset($sCatalogCode, $arCatalogElementsUsed);


foreach ($arElementsUsed as $sCatalogCode => $arCatalogElementsUsed)
{
	$res = CIBlockElement::GetList(array('SORT' => 'ASC'), array('IBLOCK_ID' => $arrIBlockIDs[$sCatalogCode], 'CODE' => $arCatalogElementsUsed));
	while ($arElement = $res->GetNext())
	{
		$arElementIDs[$sCatalogCode][$arElement['CODE']] = $arElement['ID'];
	}
	unset($res, $arElement);
}
unset($sCatalogCode, $arCatalogElementsUsed);


foreach ($arrFilterElements as $sCatalogCode1 => $arFilterCatalog1)
{
	foreach ($arFilterCatalog1 as $sElementCode1 => $arFilterElement1)
	{
		$arFilterProps = array();
		foreach ($arFilterElement1 as $sPropertyCode => $arPropertyValue)
		{
			foreach ($arPropertyValue as $sCatalogCode2 => $arFilterCatalog2)
			{
				foreach ($arFilterCatalog2 as $sElementCode2)
				{
					$arFilterProps[$sPropertyCode][] = $arElementIDs[$sCatalogCode2][$sElementCode2];

				}
				unset($sElementCode2);
			}
			unset($sCatalogCode2, $arFilterCatalog2);
		}
		unset($sPropertyCode, $arPropertyValue);
		
		CIBlockElement::SetPropertyValuesEx($arElementIDs[$sCatalogCode1][$sElementCode1], $arrIBlockIDs[$sCatalogCode1],  $arFilterProps);
	}
	unset($sElementCode1, $arFilterCatalog1);
}
unset($sCatalogCode1, $arFilterCatalog1);

if ($arrIBlockIDs['catalog'] > 0)
{
	$index = \Bitrix\Iblock\PropertyIndex\Manager::createIndexer($arrIBlockIDs['catalog']);
	$index->startIndex();
	$index->continueIndex(0);
	$index->endIndex();
}
