<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?$APPLICATION->IncludeComponent(
	"redsign:favorite.list", 
	"top", 
	array(
		"COMPONENT_TEMPLATE" => "filter",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "3600",
		"ACTION_VARIABLE" => "favaction",
		"PRODUCT_ID_VARIABLE" => "id",
		"FAVORITE_URL" => "/wishlist/"
	),
	false
);?>