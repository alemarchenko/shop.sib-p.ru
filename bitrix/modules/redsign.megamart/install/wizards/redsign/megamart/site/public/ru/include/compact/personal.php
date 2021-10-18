<?$APPLICATION->IncludeComponent(
    "bitrix:system.auth.form",
    "compact",
    array(
        "AUTH_URL" => "#SITE_DIR#auth/",
        "PROFILE_URL" => "#SITE_DIR#personal/",
        "FORGOT_PASSWORD_URL" => "",
        "SHOW_ERRORS" => "N"
    ),
    false
);?>