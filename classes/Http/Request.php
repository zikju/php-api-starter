<?php
declare(strict_types=1);

namespace zikju\Shared\Http;



class Request
{
    /**
     * List of allowed Methods
     *
     * header("Access-Control-Allow-Method..."
     */
    private static array $ALLOWED_METHODS_LIST = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'OPTIONS'
    ];

    /**
     * List of allowed Headers
     *
     * header("Access-Control-Allow-Headers..."
     */
    private static array $ALLOWED_HEADERS_LIST = [
        'Origin',
        'Content-Type',
        'Authorization',
        'Accept',
        'X-Requested-With',
        'Content-Disposition',
        'X-Refresh-Token',
        'X-Fingerprint'
    ];



    /**
     * Get incoming request method name
     *
     * @return string
     */
    public static function getMethod(): string
    {
        return strtoupper($_SERVER["REQUEST_METHOD"]);
    }



    /**
     * Returns list of allowed methods
     *
     * @return array|string[]
     */
    public static function getAllowedMethods (): array
    {
        return self::$ALLOWED_METHODS_LIST;
    }



    /**
     * Returns list of allowed headers
     *
     * @return array|string[]
     */
    public static function getAllowedHeaders (): array
    {
        return self::$ALLOWED_HEADERS_LIST;
    }



    /**
     * Set CORS Privacy header settings
     */
    public static function setHeaders (): void
    {
        /**
         * IMPORTANT:
         * If you are using: "Access-Control-Allow-Credentials" = true
         * make sure that "Access-Control-Allow-Origin" is not "*",
         * it must be set with a proper domain!
         * (a lot of blood was spilled here :/ )
         * */
        // header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: " . implode(', ', self::getAllowedMethods()));
        header("Access-Control-Allow-Headers: " . implode(', ', self::getAllowedHeaders()));
        header("Access-Control-Max-Age: 60");
        header("Content-Type: application/json; charset=UTF-8");
    }



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



    /**
     * Check if incoming request method is allowed
     */
    public static function validateIncomingMethod (): void
    {
        if (!self::isMethodAllowed()) {
            Response::send(
                'error',
                'REQUEST METHOD IS INVALID'
            );
        }
    }



    /**
     * Check if incoming request method is allowed
     *
     * @return bool
     */
    private static function isMethodAllowed(): bool
    {
        return (in_array(
            Request::getMethod(),
            Request::getAllowedMethods()
        ));
    }
}