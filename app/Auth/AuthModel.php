<?php
declare(strict_types=1);


namespace zikju\Endpoint\Auth;


use zikju\Shared\Database\DbConnect;
use zikju\Shared\Database\DbErrorHandler;

class AuthModel
{
    protected int $user_id;
    protected string $email;
    protected string $password;
    protected string $password_hash_from_db;

    protected ?array $userDataFromDB;

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

        $this->user_id = $this->userDataFromDB['id'];
        $this->password_hash_from_db = $this->userDataFromDB['password'];

        // Remove row with 'password' from userData array
        unset($this->userDataFromDB['password']);

        // Success!
        $this->query_result_status = 'ok';
        $this->query_result_message = 'Email exist!';
    }
}