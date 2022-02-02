<?php
declare(strict_types=1);


namespace zikju\Endpoint\Auth\Session;


use zikju\Shared\Http\Request;
use zikju\Shared\Http\Response;
use zikju\Shared\Util\Ip;
use zikju\Shared\Util\Random;

class UserSession extends UserSessionModel
{
    protected int $user_id;
    protected string $refresh_token;
    protected string $expire_at;
    protected string $ip_address;
    protected array $user_payload = [];

    /**
     * UserSession constructor.
     *
     * @throws \Exception
     */
    function __construct()
    {
        parent::__construct();

    }

    /**
     * Creates new User session.
     *
     * @param int $user_id
     * @throws \Exception
     */
    public function createUserSession (int $user_id)
    {
        $this->user_id = $user_id;
        $this->refresh_token = $this->generateRefreshToken();
        $this->expire_at = $this->createExpireDatetime();
        $this->ip_address = Ip::get_client_ip();

        // Insert User session into database
        $this->insertIntoDB(
            $this->user_id,
            $this->refresh_token,
            $this->expire_at,
            $this->ip_address
        );
    }


    /**
     * Deletes User session.
     *
     * @throws \Exception
     */
    public function deleteUserSession ()
    {
        // Get 'Refresh Token' from header
        $this->refresh_token = Request::getRefreshTokenHeader();

        if (!empty($this->refresh_token)) {
            // Delete current User session from database
            $this->deleteFromDB($this->refresh_token);
        }
    }


    /**
     * Renew (refresh) User session.
     *
     * @throws \Exception
     */
    public function renewUserSession ()
    {
        // Verify if 'Refresh Token' exist in header
        $this->refresh_token = Request::getRefreshTokenHeader();
        if (!$this->refresh_token || empty($this->refresh_token)) {
            Response::sendError('INVALID_REFRESH_TOKEN');
        }

        // Get current User session from database and backup it to variable
        $userSession = $this->getFromDB($this->refresh_token);
        if (!$userSession || empty($userSession)) {
            Response::sendError('INVALID_REFRESH_TOKEN');
        }

        // Create array with payload information for JWT access_token
        $this->user_payload = [
            'user_id' => $userSession['user_id'],
            'email' => $userSession['email'],
            'role' => $userSession['role']
        ];

        // Delete current User session from database
        $this->deleteFromDB($this->refresh_token);

        // Verify User session expire datetime
        if (!$this->verifyExpiryDatetime($userSession['expires_at'])) {
            Response::sendError('INVALID_REFRESH_TOKEN');
        }

        // Create new User session
        $this->createUserSession($userSession['user_id']);
    }


    /**
     * Returns payload array with User data.
     *
     * @return array
     */
    public function getUserPayload (): array
    {
        return $this->user_payload;
    }

    /**
     * Returns 'refresh_token'.
     *
     * @return string
     */
    public function getRefreshToken (): string
    {
        return $this->refresh_token;
    }


    /**
     * Returns 'expire_at' datetime.
     *
     * @return string
     */
    public function getExpireDatetime (): string
    {
        return $this->expire_at;
    }


    /**
     * Returns client Ip Address.
     *
     * @return string
     */
    public function getIpAddress (): string
    {
        return $this->ip_address;
    }


    /**
     * Creates new random 'refresh_token'
     */
    private function generateRefreshToken (): string
    {
        return Random::RandomToken();
    }


    /**
     * Creates datetime when 'refresh_token' will be expired.
     * Default: Current datetime + 60 minutes
     *
     * @param int $mins
     * @return string
     */
    private function createExpireDatetime (int $mins = 60): string
    {
        return date(
            'Y-m-d H:i:s',
            strtotime('+'.$mins.' minutes')
        );
    }

    /**
     * Check current session expiration
     *
     * @param string $datetime
     * @return bool
     */
    private function verifyExpiryDatetime (string $datetime): bool
    {
        $expires_timestamp = strtotime($datetime);
        $current_timestamp = time();
        return ($expires_timestamp < $current_timestamp);
    }
}