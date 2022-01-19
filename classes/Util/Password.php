<?php


namespace zikju\Shared\Util;


class Password
{

    /**
     * Returns securely hashed password string
     * @param string $password
     * @return string
     */
    public static function hash(string $password): string
    {
        return password_hash(
            $password,
            PASSWORD_DEFAULT,
            array('cost' => 9)
        );

    }

    /**
     * Compares raw password string with hashed password.
     * @param string $password
     * @param string $passwordHashFromDb
     * @return int
     */
    public static function verify(
        string $password,
        string $passwordHashFromDb
    ): int
    {
        return password_verify(
            $password,
            $passwordHashFromDb
        );

    }
}