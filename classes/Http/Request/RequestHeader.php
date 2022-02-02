<?php
declare(strict_types=1);


namespace zikju\Shared\Http\Request;


class RequestHeader extends RequestMethod
{
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
     * Returns list of allowed headers
     *
     * @return array|string[]
     */
    public static function getAllowedHeaders (): array
    {
        return self::$ALLOWED_HEADERS_LIST;
    }


    /**
     * Gets specific header
     *
     * @param string $headerName
     * @return string
     */
    public static function getHeader(string $headerName): string
    {
        $headers = '';
        $upperCaseHeaderName = strtoupper(str_replace('-', '_', $headerName));

        if (isset($_SERVER[$headerName])) {
            $headers = trim($_SERVER["X-Refresh-Token"]);

            //Nginx or fast CGI
        } else if (isset($_SERVER['HTTP_'.$upperCaseHeaderName])) {
            $headers = trim($_SERVER['HTTP_'.$upperCaseHeaderName]);

            // Apache handler
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();

            // Server-side fix for bug in old Android versions.
            // A nice side-effect of this fix means we don't care about capitalization for Authorization
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders[$headerName])) {
                $headers = trim($requestHeaders[$headerName]);
            }
        }
        return $headers;
    }


    /**
     * Gets 'Access Token' from header
     *
     * @return string
     */
    public static function getAuthorizationHeader(): string
    {
        $headers = self::getHeader('Authorization');

        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return '';
    }

    /**
     * Gets 'Refresh Token' from header
     *
     * @return string
     */
    public static function getRefreshTokenHeader()
    {
        $headers = self::getHeader('X-Refresh-Token');

        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            return $headers;
        }

        return '';
    }
}