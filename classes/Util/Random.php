<?php
declare(strict_types=1);


namespace zikju\Shared\Util;


class Random
{
    public static function RandomToken (int $length = 32): string
    {
        if ($length < 4) $length = 4;
        if ($length > 64) $length = 64;
        return bin2hex(random_bytes(($length-($length%2))/2));
    }
}