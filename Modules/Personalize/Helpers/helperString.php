<?php

namespace Modules\Personalize\Helpers;


class helperString
{

    public static function colon($str)
    {
        return $str . ' : ';
    }

    public static function dividePrice($str, $vahed = '')
    {
        $arr = str_split(strrev($str), 3);
        $ret = "";
        for ($i = count($arr) - 1; $i >= 0; $i--) {
            $ret .= strrev($arr[$i]);
            if ($i != 0)
                $ret .= ',';
        }
        return $ret . ' ' . $vahed;
    }

    public static function compressPrice($str)
    {
        $str = trim(str_replace("تومان", "", $str));
        return str_replace(",", "", $str);
    }


    public static function number2farsi($srting)
    {
        $en_num = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $fa_num = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
        return str_replace($en_num, $fa_num, $srting);

    }


    public static function number2en($srting)
    {
        $en_num = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $fa_num = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
        return str_replace($fa_num, $en_num, $srting);
    }


    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 ||
            (substr($haystack, -$length) === $needle);
    }

}