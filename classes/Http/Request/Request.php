<?php
declare(strict_types=1);


namespace zikju\Shared\Http\Request;


class Request extends RequestHeader
{
    /**
     * Returns incoming request body or query data
     *
     * @return array|string
     */
    public static function getData()
    {
        $method = self::getMethod();

        // 'GET' and 'DELETE' methods
        if ($method === 'GET' || $method === 'DELETE') {
            return $_GET;
        }

        // 'PUT' method
        if ($method === 'PUT') {
            parse_str(file_get_contents('php://input'), $put_data);
            return $put_data;
        }

        // 'POST' method
        if($method === 'POST' && !empty($_POST)) {
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
}