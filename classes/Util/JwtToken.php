<?php
declare(strict_types=1);


namespace zikju\Shared\Util;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
        $tokenPayload = [];

        if (!empty($payloadArray)){
            $tokenPayload['payload'] = $payloadArray;
        }

        // "Expire" timestamp
        $tokenPayload['exp'] = strtotime('+' . $expMin . ' minutes');

        return JWT::encode(
            $tokenPayload,
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
        try {
            JWT::decode(
                $token,
                new Key($_ENV['JWT_SECRET_KEY'], self::$alg)
            );
        } catch (\Exception $e){
            return false;
        }

        return true;
    }


    /**
     * Decode JWT token
     * @param string $token
     * @return array
     */
    public static function decode (string $token)
    {
        if (!self::verify($token)) {
            return [];
        }

        return (array) JWT::decode(
            $token,
            new Key($_ENV['JWT_SECRET_KEY'],
                self::$alg
            )
        );
    }


    /**
     * Returns decoded payload from JWT Token
     *
     * @param string $token
     * @return array
     */
    public static function getDecodedPayload (string $token): array
    {
        return (array) self::decode($token)['payload'];
    }
}