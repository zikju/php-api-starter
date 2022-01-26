<?php
declare(strict_types=1);


namespace zikju\Shared\Util;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class JwtToken
{

    /**
     * Default encryption algorithm
     *
     * Supported algorithms:
     * ES384, ES256, HS256, HS384, HS512, RS256, RS384, RS512, EdDSA
    */
    private static string $alg = 'HS256';


    /**
     * Create JWT token
     *
     * @param array $payloadArray
     * @param int $expMin
     * @return string
     */
    public static function create (array $payloadArray, int $expMin = 10): string
    {
        $finalPayload = [];

        if (!empty($payloadArray)){
            $finalPayload['payload'] = $payloadArray;
        }

        // "Expire" timestamp
        $finalPayload['exp'] = strtotime('+' . $expMin . ' minutes');

        return JWT::encode(
            $finalPayload,
            $_ENV['JWT_SECRET_KEY'],
            self::$alg
        );
    }

    /**
     * Verify JWT token
     *
     * @param string $token
     * @return bool
     */
    public static function verify (string $token): bool
    {
        try{
            $decoded = JWT::decode(
                $token,
                new Key($_ENV['JWT_SECRET_KEY'], self::$alg)
            );
        }catch(ExpiredException $e){
            return false;
        }

        return true;
    }


    /**
     * Decode JWT token
     * @param string $token
     * @return array|null
     */
    public static function decode (string $token)
    {
        if (!self::verify($token)) {
            return null;
        }

        return (array) JWT::decode(
            $token,
            new Key($_ENV['JWT_SECRET_KEY'],
                self::$alg
            )
        );
    }
}