<?php
declare(strict_types=1);

namespace zikju\Shared\Validation;

use Respect\Validation\Validator as v;

class UserValidator extends BaseValidator
{
    const ALLOWED_ACCOUNT_ROLES = [
        'user',
        'admin'
    ];

    const ALLOWED_ACCOUNT_STATUSES = [
        'unregistered',
        'pending',
        'active',
        'disabled'
    ];


    /**
     * Validate 'email' input
     *
     * @param string $email
     * @return bool
     */
    public static function email($email): bool
    {
        return v::email()
            ->noWhitespace()
            ->length(6, 120)
            ->validate($email);
    }


    /**
     * Validate 'password' input
     *
     * @param $password
     * @return bool
     */
    public static function password($password): bool
    {
        return v::length(5, 32)
                ->validate($password);
    }


    /**
     * Validate 'role' input
     *
     * @param $role
     * @return bool
     */
    public static function role($role): bool
    {
        return v::in(self::ALLOWED_ACCOUNT_ROLES)
                ->validate($role);
    }


    /**
     * Validate 'status' input
     *
     * @param $status
     * @return bool
     */
    public static function status($status): bool
    {
        return v::in(self::ALLOWED_ACCOUNT_STATUSES)
                ->validate($status);
    }
}