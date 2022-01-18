<?php

namespace zikju\Core;

class Request
{
    public static array $allowedList = ['GET', 'POST', 'PATCH', 'PUT', 'DELETE', 'OPTIONS'];


    /** Get incoming request method name
     * @return string
     */
    public static function get(): string
    {
        return strtoupper($_SERVER["REQUEST_METHOD"]);
    }


    /** Check if incoming request method is allowed
     * @return bool
     */
    public static function isAllowed(): bool
    {
        return (in_array(
            Request::get(),
            Request::$allowedList
        ));
    }

}