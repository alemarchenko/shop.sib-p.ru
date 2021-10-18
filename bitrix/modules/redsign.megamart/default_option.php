<?php

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$redsign_megamart_default_option = array(
	'global_lazyload_images' => 'Y',

	// data
	'global_phones' => 'a:2:{i:0;s:17:"8 (000) 000-00-00";i:1;s:17:"8 (000) 000-00-00";}',
	'global_emails' => 'a:1:{i:0;s:14:"pro@redsign.ru";}',
	'global_schedule' => Loc::getMessage('RS_MM_DEFAULT_OPTION_SHEDULE'),

	// sale
	'sale_use_order_min_price' => 'N',
	'sale_order_min_price' => 0,
    'sale_order_min_price_error_text' => Loc::getMessage('RS_MM_DEFAULT_OPTION_ORDER_MIN_PRICE_ERROR_TEXT'),
    'use_sale_order_bonus' => 'N',
    'sale_order_bonus' => '2',
    'sale_order_bonus_type' => 'P',
    
    // grecaptcha
    'global_grecaptcha_min_score' => 0.5,
    'global_grecaptcha_block_id' => 'grecaptcha-inline-badge',
    'global_grecaptcha_remove_selectors' => '.captcha-wrap, .bx-captcha',
);
