<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "phones",
    array(
        "RS_TEMPLATE" => "header",
        "AREA_FILE_SHOW" => "file",
        "PATH" => "#SITE_DIR#include/empty.php",
        "IGNORE_MULTIREGIONALITY" => "N",
        "GET_FROM" => "module",
        "ANOTHER_BLOCK" => "recall",
        "EDIT_TEMPLATE" => ""
    ),
    false
);?>