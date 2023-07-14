<?php

namespace App\Helpers;


class TokenHelper
{
    private static $numbers = "0123456789";
    public static function generateRandomToken(int $size = 6)
    {
        $token = '';
        for ($i = 0; $i <  $size; $i++) {
            $token .= self::$numbers[rand(0, $size - 1)];
        }
        return $token;
    }

    public static function generateTransactionId()
    {
        return "CHFTRX-" . (100 + rand(149, 888)) . time() . strtoupper(substr(md5(time()), 3, 3));
    }
}
