<?php

namespace zikju\Shared\Http;

class Request
{
    public static array $allowedList = ['GET', 'POST', 'PATCH', 'PUT', 'DELETE', 'OPTIONS'];


    /** Get incoming request method name
     * @return string
     */
    public static function getMethod(): string
    {
        return strtoupper($_SERVER["REQUEST_METHOD"]);
    }


    /** Returns incoming request body or query data
     * @return array|string
     */
    public static function getData()
    {
        $method = self::getMethod();

        // 'GET' and 'DELETE' methods
        if ($method === 'GET' || $method === 'DELETE') {
            return $_GET;
        }

        if(!empty($_POST)) {
            // when using 'application/x-www-form-urlencoded' or 'multipart/form-data'
            // as the HTTP Content-Type in the request.
            // NOTE: if this is the case and $_POST is empty,
            // check the variables_order in php.ini! - it must contain the letter P
            return $_POST;
        }

        // when using application/json as the HTTP Content-Type in the request
        $post = json_decode(file_get_contents('php://input'), true);
        if(json_last_error() == JSON_ERROR_NONE) {
            return $post;
        }

        return [];
    }


    /** Check if incoming request method is allowed
     * @return bool
     */
    public static function isAllowed(): bool
    {
        return (in_array(
            Request::getMethod(),
            Request::$allowedList
        ));
    }

}