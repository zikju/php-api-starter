<?php
declare(strict_types=1);

namespace zikju\Shared\Database;


use MysqliDb;

class DbConnect
{

    public static function connect ()
    {
        return new MysqliDb (
            self::getHost(),
            self::getUsername(),
            self::getPassword(),
            self::getDatabase(),
            self::getPort()
        );
    }


    public static function getHost(): string
    {
        return $_ENV['DB_HOST'];
    }

    public static function getPort(): string
    {
        return $_ENV['DB_PORT'];
    }

    public static function getUsername(): string
    {
        return $_ENV['DB_USERNAME'];
    }

    public static function getPassword(): string
    {
        return $_ENV['DB_PASSWORD'];
    }

    public static function getDatabase(): string
    {
        return $_ENV['DB_DATABASE'];
    }

}