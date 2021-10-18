<?$APPLICATION->IncludeComponent(
    "bitrix:system.auth.form",
    "inheader_topline",
    array(
        "AUTH_URL" => "/auth/",
        "PROFILE_URL" => "/personal/",
        "FORGOT_PASSWORD_URL" => "",
        "SHOW_ERRORS" => "N"
    ),
    false
);?>