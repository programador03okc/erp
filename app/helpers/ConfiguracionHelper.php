<?php

namespace App\Helpers;

use Carbon\Carbon;

class ConfiguracionHelper
{
    public static function encode5t($str)
    {
        for ($i = 0; $i < 5; $i++) {
            $str = strrev(base64_encode($str));
        }
        return $str;
    }

    public static function decode5t($str)
    {
        for ($i = 0; $i < 5; $i++) {
            $str = base64_decode(strrev($str));
        }
        return $str;
    }
}