<?php
declare(strict_types=1);

namespace zikju\Shared\Http\Request;



use zikju\Shared\Http\Response;

class RequestMethod
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
            RequestMethod::getMethod(),
            RequestMethod::getAllowedMethods()
        ));
    }
}