<?php

namespace App\Util;

class IbanGenerator
{
    static function generator(){
        $prefix = "PL";
        $random = rand(10000000, 99999999);
        $suffix = rand(10,99);

        return $prefix . $random . $suffix;

    }
}