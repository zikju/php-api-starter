<?php
declare(strict_types=1);


namespace zikju\Shared\Logger;


use zikju\Shared\Database\DbConnect;
use zikju\Shared\Http\Request;
use zikju\Shared\Util\JwtToken;

class UserLogger
{
    /**
     * Update User last_access datetime
     *
     * @throws \Exception
     */
    public static function logLastAccess ()
    {
        $access_token = Request::getAuthorizationHeader();

        if (JwtToken::verify($access_token)) {
            $token_payload = JwtToken::getDecodedPayload($access_token);
            $user_id = $token_payload['user_id'];

            $db = DbConnect::connect();

            $dataset = [
                'last_access' => $db->now()
            ];
            $db->where('id', $user_id);
            $db->update('users', $dataset);

            $db->disconnect();
        }
    }
}