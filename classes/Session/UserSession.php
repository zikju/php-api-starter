<?php
declare(strict_types=1);


namespace zikju\Shared\Session;


use zikju\Shared\Util\Ip;
use zikju\Shared\Util\Random;

class UserSession
{
    private int $user_id;
    private string $refresh_token;
    private string $expire_at;
    private string $ip_address;

    /**
     * UserSession constructor.
     *
     * @param int $user_id
     */
    function __construct(int $user_id)
    {
        $this->user_id = $user_id;
        $this->setRefreshToken();
        $this->setExpireDatetime();
        $this->setIpAddress();
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
    private function setRefreshToken ()
    {
        $this->refresh_token = Random::RandomToken();
    }


    /**
     * Creates datetime when 'refresh_token' will be expired.
     * Default: Current datetime + 60 minutes
     *
     * @param int $mins
     */
    private function setExpireDatetime (int $mins = 60)
    {
        $this->expire_at = date(
            'Y-m-d H:i:s',
            strtotime('+'.$mins.' minutes')
        );
    }


    /**
     * Sets client Ip Address
     */
    private function setIpAddress ()
    {
        $this->ip_address = Ip::get_client_ip();
    }
}