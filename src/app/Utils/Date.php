<?php

namespace App\Utils;

class Date
{
    static function Valid($date)
    {
        try {

            return \Carbon\Carbon::parse($date);
        } catch (\Exception $e) {
            return false;
        }
    }
}
