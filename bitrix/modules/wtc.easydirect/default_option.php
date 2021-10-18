<?
$wtc_easydirect_default_option = array(
    //main
	"ya_login" => "",  //login in yandex
	"ya_token" => "", //Yandex App Token
	"ya_currency"=>"", //Currency
	
    //defaults
    "time_to_set_prices" =>"32", //how often change prices minutes
    "def_max_price" =>"40", //default max price
    "def_rsya_max_price" =>"10", //default max price
    "def_metod" =>"2", //default metod ID
    "def_metod_rsya" =>"2", //default RSYA metod ID
    "notice_email"=>COption::GetOptionString('main', 'email_from'),  //email for notice
    
    "podbor_phrase_region"=>0, //region ID for search words
    
    //CatalogIntegration
    "catalog_auto_update_by_available"=>"N",
    "catalog_auto_update_prices"=>"N",
    "catalog_def_price_type_id"=>0,
    "catalog_update_speed"=>3000,
    "catalog_update_interval"=>120,
    
    //Logs&Statistics
    "write_detail_log"=>"Y",  //write detail log
    "write_phrase_log"=>"Y", //write phrase log
    "log_savetime"=>"7", //how many days keep main log
    "phrase_log_savetime"=>"7", //how many days keep phrase log
    
    //XML params
    "is_yaxml" => "N",  //YA XML IS ACTIVE?
    "url_yaxml" => "", //URL XML
    "yaxml_ip_addr" => "", //IP daress to set request to Yandex
    "yaxml_region" => 0, //manual XML region to check SEO position
    "yaxml_day_limit"=>"700",// Count Day Limit
    "yaxml_hourlimit"=>"50",// Count Hour Limit
    "yaxml_minshows"=>"30", // phrase min shows to check SEO position
    "yaxml_minpremiumbet"=>"20", // phrase min PREMIUM BET to check SEO position
);
?>