<?php

namespace UDAdsManager\Util;

class Util
{
    public static function isDFPViewPortSize($arr)
    {
        if (! is_array($arr) || (array_keys($arr) !== range(0, count($arr) - 1)) || count($arr) !== 2) {
            return false;
        }

        foreach ($arr as $value) {
            if (! is_numeric($value) or is_float($value)) {
                return false;
            }
        }

        return true;
    }

    public static function isDFPSlotSize($arr)
    {
        if (! is_array($arr) || (array_keys($arr) !== range(0, count($arr) - 1))) {
            return false;
        }

        foreach ($arr as $value) {
            if (! self::isDFPViewPortSize($value)) {
                return false;
            }
        }

        return true;
    }


    public static function sanitizeDFPViewPortSize($content)
    {
        $ad_sizes = json_decode('[' . $content . ']', true);

        if ($content === 'fluid') {
            return $content;
        }

        // check if associative array or empty
        if (self::isDFPSlotSize($ad_sizes) or self::isDFPViewPortSize($ad_sizes)) {
            return $content;
        }

        return null;
    }
}
