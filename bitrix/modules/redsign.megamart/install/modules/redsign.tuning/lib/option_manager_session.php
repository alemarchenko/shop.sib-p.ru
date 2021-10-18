<?php

namespace Redsign\Tuning;

use Bitrix\Main\Config\Option;

class OptionManagerSession extends OptionManager
{

    function __construct($options) {
        parent::__construct($options);
    }

    public function getOption($optionName, $default = '') {
        $val = $_SESSION['redsign.tuning'][$optionName];
        return !empty($val) ? $val : $default;
    }

    public function saveOption($optionName, $value) {
        $_SESSION['redsign.tuning'][$optionName] = $value;
    }

}
