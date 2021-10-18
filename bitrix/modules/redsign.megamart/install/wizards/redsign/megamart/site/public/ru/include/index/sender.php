<?php if(IsModuleInstalled('sender')):?>
<?$APPLICATION->IncludeComponent(
    "bitrix:sender.subscribe",
    "line",
    Array(
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "CONFIRMATION" => "Y",
        "HIDE_MAILINGS" => "N",
        "SET_TITLE" => "N",
        "SHOW_HIDDEN" => "Y",
        "USER_CONSENT" => "Y",
        "USER_CONSENT_ID" => "#USER_CONSENT_ID#",
        "USER_CONSENT_IS_CHECKED" => "Y",
        "USER_CONSENT_IS_LOADED" => "N",
        "USE_PERSONALIZATION" => "Y",
    )
);?>
<?php endif; ?>