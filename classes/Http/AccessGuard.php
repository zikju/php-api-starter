<?php
declare(strict_types=1);


namespace zikju\Shared\Http;


use zikju\Shared\Util\JwtToken;
use zikju\Shared\Validation\UserValidator;

class AccessGuard
{
    private string $access_token = '';
    private array $token_decoded_payload = [];
    private ?int $user_id = 0;
    private ?string $role = '';

    public function __construct()
    {
        // Get Authorization token from request headers
        $this->access_token = Request::getAuthorizationHeader();

        // Decode 'Access Token' payload
        $this->token_decoded_payload = (array) JwtToken::decode($this->access_token)['payload'];

        // Gets user ID from token
        $this->user_id = $this->token_decoded_payload['id'];

        // Gets user 'role' from token
        $this->role = $this->token_decoded_payload['role'];
    }


    /**
     * Verifies client 'Access Token'
     */
    public function verifyAccessToken ()
    {
        if (!JwtToken::verify($this->access_token)) {
            Response::sendError(
                'INVALID_ACCESS_TOKEN',
                null,
                401
            );
        }
    }


    /**
     * Requires user role = 'admin'
     */
    public function allowAdminOnly ()
    {
        if (!UserValidator::role($this->role) || $this->role !== 'admin') {
            Response::sendError(
                'ACCESS_DENIED',
            );
        }
    }


}