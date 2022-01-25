<?php

namespace zikju\Endpoint\User;


use zikju\Shared\Database\DbConnect;
use zikju\Shared\Database\DbErrorHandler;

class UserModel
{
    protected int $user_id;

    protected array $dataset = [];

    // By default query result is successful
    protected array $queryResult = ['status' => 'ok'];

    private \MysqliDb $db;

    function __construct()
    {
        // Create Database connection
        $this->db = DbConnect::connect();
    }


    /**
     * Inserts new User into database
     *
     * @throws \Exception
     */
    protected function insertUserIntoDB (): void
    {
        // Execute mysqli query
        $this->db->insert('users', $this->dataset);

        // Handle if email already exist
        if ($this->db->getLastErrno() === 1062) {
            $this->queryResult = [
                'status' => 'error',
                'message' => 'EMAIL ALREADY IN USE'
            ];
            return;
        }

        // Handle mysqli errors
        DbErrorHandler::handleMysqlError();

        // Success!
        $this->queryResult['message'] = 'User successfully created!';
    }


    /**
     * Updates Users data in database
     *
     */
    protected function updateUserDataInDB (): void
    {
        // Execute mysqli query
        $this->db->where ('id', $this->user_id);
        $this->db->update ('users', $this->dataset);

        // Handle mysqli errors
        DbErrorHandler::handleMysqlError();

        // Success!
        $this->queryResult['message'] = 'User data successfully updated!';
    }


    /**
     * Gets User data by ID from database
     *
     * @throws \Exception
     */
    protected function getUserFromDB (): void
    {
        // Set fields to get from database
        $fields = ['id', 'created_at', 'email', 'role', 'status', 'notes'];

        // Execute mysqli query
        $this->db->where('id', $this->user_id);
        $userData = $this->db->getOne('users', $fields);

        // Handle mysqli errors
        DbErrorHandler::handleMysqlError();


        // Handle if user not found
        if ($this->db->count < 1) {
            $this->queryResult = [
                'status' => 'error',
                'message' => 'USER NOT FOUND'
            ];
            return;
        }

        // Success!
        $this->queryResult['message'] = 'User data successfully fetched';
        $this->queryResult['payload'] = $userData;
    }


    /**
     * Deletes user by ID from database
     *
     * @throws \Exception
     */
    protected function deleteUserFromDB (): void
    {
        // Execute mysqli query
        $this->db->where('id', $this->user_id);
        $this->db->delete('users');

        // Handle mysqli errors
        DbErrorHandler::handleMysqlError();

        // Success!
        $this->queryResult['message'] = 'User successfully deleted';
    }
}