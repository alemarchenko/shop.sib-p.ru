<div class="d-flex">
    <div class="d-block mr-4">
        <svg class="icon-svg text-extra h4"><use xlink:href="#svg-map-pin"></use></svg>
    </div>
    <div class="d-block">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include", 
            "", 
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/include/compact/address_area.php",
                "EDIT_TEMPLATE" => ""
            ),
            false
        );?>
    </div>
</div>