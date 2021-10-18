<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Loader::registerAutoLoadClasses(
	'redsign.megamart',
	array(
		'\Redsign\MegaMart\AdminUtils' => 'lib/admin_utils.php',
		'\Redsign\MegaMart\IblockElementExt' => 'lib/iblock_element_ext.php',
		'\Redsign\MegaMart\MyTemplate' => 'lib/my_template.php',
		'\Redsign\MegaMart\ParametersUtils' => 'lib/parameters_utils.php',
		'\Redsign\MegaMart\SVGIconsManager' => 'lib/svg_icons_manager.php',
		'\Redsign\MegaMart\LazyloadUtils' => 'lib/lazyload_utils.php',
		'\Redsign\MegaMart\StringUtils' => 'lib/string_utils.php',
		'\Redsign\MegaMart\TextUtils' => 'lib/text_utils.php',
		'\Redsign\MegaMart\OrderUtils' => 'lib/order_utils.php',
		'\Redsign\MegaMart\ElementListUtils' => 'lib/elementlist_utils.php',
		'\Redsign\MegaMart\Layouts\Base' => 'lib/layouts/base.php',
		'\Redsign\MegaMart\Layouts\EmptySection' => 'lib/layouts/empty_section.php',
		'\Redsign\MegaMart\Layouts\Section' => 'lib/layouts/section.php',
		'\Redsign\MegaMart\Layouts\Parts\Base' => 'lib/layouts/parts/base.php',
		'\Redsign\MegaMart\Layouts\Parts\SectionHeaderBase' => 'lib/layouts/parts/section_header_base.php',
		'\Redsign\MegaMart\Layouts\Parts\SectionHeaderCustom' => 'lib/layouts/parts/section_header_custom.php',
		'\Redsign\MegaMart\GReCaptcha\GReCaptchaEvents' => 'lib/grecaptcha/grecaptcha_events.php',
        '\Redsign\MegaMart\GReCaptcha\GReCaptchaTools' => 'lib/grecaptcha/tools.php',
        '\Redsign\MegaMart\GReCaptcha\GReCaptchaV3' => 'lib/grecaptcha/grecaptcha_v3.php',
        '\Redsign\MegaMart\BrandTools' => 'lib/brand_tools.php',
        '\Redsign\MegaMart\Sale\CashBack' => 'lib/sale/cashback.php',
	)
);
