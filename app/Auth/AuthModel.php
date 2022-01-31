<?php
declare(strict_types=1);


namespace zikju\Endpoint\Auth;


use zikju\Shared\Database\DbConnect;
use zikju\Shared\Database\DbErrorHandler;

class AuthModel
{
    protected string $email;
    protected string $password;
    protected string $password_hash_from_db;

    protected $userDataFromDB;

    // By default query result is with error
    protected string $query_result_status = 'error';
    protected string $query_result_message;

    private \MysqliDb $db;

    function __construct()
    {
        // Create Database connection
        $this->db = DbConnect::connect();
    }

    /**
     * Get's User data from database by Email
     *
     * @throws \Exception
     */
    protected function getUserByEmailFromDB ()
    {
        // Set fields to get from database
        $fields = ['id', 'password', 'role'];

        // Execute mysqli query
        $this->db->where('email', $this->email);
        $this->userDataFromDB = $this->db->arrayBuilder()->getOne('users', $fields);

        // Handle mysqli errors
        DbErrorHandler::handleMysqlError();

        // Handle if user not found
        if ($this->db->count < 1) {
            $this->query_result_status = 'error';
            $this->query_result_message = 'INVALID LOGIN';
            return;
        }

        $this->password_hash_from_db = $this->userDataFromDB['password'];

        // Remove row with 'password' from userData array
        unset($this->userDataFromDB['password']);

        // Success!
        $this->query_result_status = 'ok';
        $this->query_result_message = 'Email exist!';
    }


    /**
     * Creates User session in database and returns session details
     *
     * @param int $user_id
     * @param string $token
     * @param string $expires_at
     * @param string $ip
     * @throws \Exception
     */
    protected function insertUserSessionIntoDB (
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
}