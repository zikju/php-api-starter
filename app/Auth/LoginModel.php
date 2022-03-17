<?php
declare(strict_types=1);


namespace zikju\App\Auth;


use zikju\Shared\Database\DbConnect;
use zikju\Shared\Database\DbErrorHandler;
use zikju\Shared\Http\Response;

class LoginModel
{
    private \MysqliDb $db;

    function __construct()
    {
        // Create Database connection
        $this->db = DbConnect::connect();
    }

    /**
     * Get's User data from database by Email
     *
     * @param string $email
     * @return array|\MysqliDb|string|null
     * @throws \Exception
     */
    protected function getUserDataFromDB (string $email)
    {
        // Set fields to get from database
        $fields = ['id', 'password', 'email', 'role'];

        // Execute mysqli query
        $this->db->where('email', $email);
        $queryResults = $this->db->arrayBuilder()->getOne('users', $fields);

        // Handle mysqli errors
        DbErrorHandler::handleMysqlError();

        // Handle if user not found
        if ($this->db->count < 1) {
            Response::sendError('INVALID_LOGIN');
        }

        return $queryResults;
    }
}