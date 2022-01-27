<?php
declare(strict_types=1);


namespace zikju\Endpoint\Auth;


use zikju\Shared\Database\DbConnect;
use zikju\Shared\Database\DbErrorHandler;

class AuthModel
{
    protected string $email;
    protected string $password;

    protected $userData;

    // By default query result is successful
    protected array $queryResult = ['status' => 'ok'];

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
        $fields = ['id', 'created_at', 'email', 'password', 'role', 'status', 'notes'];

        // Execute mysqli query
        $this->db->where('email', $this->email);
        $this->userData = $this->db->arrayBuilder()->getOne('users', $fields);

        // Handle mysqli errors
        DbErrorHandler::handleMysqlError();

        // Handle if user not found
        if ($this->db->count < 1) {
            $this->query_result_status = 'error';
            $this->query_result_message = 'INVALID LOGIN';
            return;
        }

        // Success!
        $this->query_result_status = 'ok';
        $this->query_result_message = 'Email exist!';
    }
}