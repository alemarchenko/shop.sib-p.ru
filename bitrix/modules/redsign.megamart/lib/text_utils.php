<?php

namespace Redsign\Megamart;

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

class TextUtils {
    
    public static function getReadingTime($sText, $nWordsPerMinute = 180) {
        
        Loc::loadMessages(__FILE__);
        $sReadingTime = '';
        $nTotalWordCount = str_word_count(\strip_tags($sText), 0, Loc::getMessage('RS_READING_CYRILLIC'));
        $nReadingTimeMinutes = round($nTotalWordCount / $nWordsPerMinute);
        
        if ($nReadingTimeMinutes > 0) {
            $arMinutesTitles = array(
                Loc::getMessage('RS_READING_MINUTE_TITLE_1'),
                Loc::getMessage('RS_READING_MINUTE_TITLE_2'),
                Loc::getMessage('RS_READING_MINUTE_TITLE_3')
            );
            
            $sReadingTime = StringUtils::declOfNum($nReadingTimeMinutes, $arMinutesTitles);
        } else {
            $sReadingTime = Loc::getMessage('RS_READING_LESS_THAN_MINUTE');
        }
        
        return $sReadingTime;
    }

}

