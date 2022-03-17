<?php
declare(strict_types=1);


namespace zikju\App\Auth;


use zikju\Shared\Http\Response;
use zikju\Shared\Util\JwtToken;

class RefreshTokenController extends UserSession
{

    public function refresh ()
    {
        // Create Refresh Token (user session in database)
        $this->refreshUserSession();
        $refresh_token = $this->getRefreshToken();

        // Create Access Token (JWT)
        $access_token_payload = $this->getUserPayload();
        $access_token = JwtToken::create($access_token_payload);

        // Send response result
        $responseArray = [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token
        ];

        Response::sendOk(
            'Token successfully refreshed',
            $responseArray
        );
    }


}