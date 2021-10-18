<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "main_1",
    array(
        "ROOT_MENU_TYPE" => "catalog",
        "MAX_LEVEL" => "4",
        "CHILD_MENU_TYPE" => "mainsub",
        "USE_EXT" => "Y",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N",
        "MENU_CACHE_TYPE" => "N",
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => array(),
        "COMPONENT_TEMPLATE" => "",
        "MENU_COLOR" => 'inheader',
        "HEAD_TYPE" => defined("RS_MM_HEAD_TYPE")?RS_MM_HEAD_TYPE:"",
    ),
    false
); ?>