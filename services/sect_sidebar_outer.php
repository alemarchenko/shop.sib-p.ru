<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "sidebar",
    array(
        "ROOT_MENU_TYPE" => "mainsub",
        "CHILD_MENU_TYPE" => "mainsub",
        "MAX_LEVEL" => "3",
        "USE_EXT" => "Y",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => "",
    )
);?>
<?php $APPLICATION->ShowViewContent('site_sidebar_outer'); ?>