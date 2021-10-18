<?php
$APPLICATION->IncludeComponent(
	"rsmm:megamart.socnet.links", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"FACEBOOK" => "",
		"VKONTAKTE" => "",
		"TWITTER" => "",
		"GOOGLE" => "",
		"INSTAGRAM" => "https://instagram.com/sibproekt/"
	),
	false,
	array(
		"HIDE_ICONS" => "N"
	)
);
?>