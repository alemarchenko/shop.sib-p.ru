<?php

namespace Redsign\Tuning;

use Bitrix\Main\Config\Option;

class OptionManagerBitrix extends OptionManager
{

    function __construct($options) {
        parent::__construct($options);
    }

    public function getOption($optionName, $default = '') {
        $val = Option::get('redsign.tuning', $optionName, $default, SITE_ID);
        return !empty($val) ? $val : $default;
    }

    public function saveOption($optionName, $value) {
        Option::set('redsign.tuning', $optionName, $value, SITE_ID);
    }

}
