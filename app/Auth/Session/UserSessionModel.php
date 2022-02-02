<?php
declare(strict_types=1);


namespace zikju\Endpoint\Auth\Session;

use zikju\Shared\Database\DbConnect;
use zikju\Shared\Database\DbErrorHandler;
use zikju\Shared\Http\Response;

class UserSessionModel
{
    private \MysqliDb $db;

    function __construct()
    {
        // Create Database connection
        $this->db = DbConnect::connect();
    }


    /**
     * Creates User session in database.
     *
     * @param int $user_id
     * @param string $token
     * @param string $expires_at
     * @param string $ip
     * @throws \Exception
     */
    protected function insertIntoDB (
        int $user_id,
        string $token,
        string $expires_at,
        string $ip
    )
    {
        $fields = array(
            "u_id"          => $user_id,
            "refresh_token" => $token,
            "expires_at"    => $expires_at,
            "ip"            => $ip
        );
        $this->db->insert('users_sessions', $fields);

        // Handle mysqli errors
        DbErrorHandler::handleMysqlError();
    }

    /**
     * Gets User session from database by 'refresh_token' field.
     *
     * @param string $refresh_token
     * @return mixed
     * @throws \Exception
     */
    protected function getFromDB(string $refresh_token)
    {
        $this->db->join(
            "users u",
            "u.id = us.u_id",
            "LEFT"
        );
        $this->db->where("us.refresh_token", $refresh_token);

        $queryResults = $this->db->arrayBuilder()->get(
            "users_sessions us",
            null,
            'us.id, us.u_id AS user_id, us.created_at, us.refresh_token, us.expires_at, u.email, u.role'
        )[0];

        // Handle mysqli errors
        DbErrorHandler::handleMysqlError();

        return $queryResults;
    }


    /**
     * Deletes User session from database by 'refresh_token' field.
     *
     * @param string $refresh_token
     * @return bool
     * @throws \Exception
     */
    protected function deleteFromDB(string $refresh_token): bool
    {
        $this->db->where('refresh_token', $refresh_token);

        return $this->db->delete('users_sessions');
    }
}