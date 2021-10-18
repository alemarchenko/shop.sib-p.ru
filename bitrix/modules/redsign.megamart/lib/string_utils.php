<?php

namespace Redsign\Megamart;

class StringUtils {
    
    public static function truncateHTML($string, $length, $postfix = '...'){
        $string = trim($string);
        $postfix = (strlen(strip_tags($string)) > $length) ? $postfix : '';
        $i = 0;
        $tags = []; 

        preg_match_all('/<[^>]+>([^<]*)/', $string, $tagMatches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach($tagMatches as $tagMatch) {
            if ($tagMatch[0][1] - $i >= $length) {
                break;
            }

            $tag = substr(strtok($tagMatch[0][0], " \t\n\r\0\x0B>"), 1);
            if ($tag[0] != '/') {
                $tags[] = $tag;
            }
            elseif (end($tags) == substr($tag, 1)) {
                array_pop($tags);
            }

            $i += $tagMatch[1][1] - $tagMatch[0][1];
        }

        return substr($string, 0, $length = min(strlen($string), $length + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '') . $postfix;
    }

    public static function declOfNum($number, $titles) {
        $cases = array (2, 0, 1, 1, 1, 2);  
        return $number." ".$titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number%10, 5)]];  
    }
}
