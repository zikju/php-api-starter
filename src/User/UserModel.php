<?php

namespace zikju\Endpoint\User;


use zikju\Shared\Database\DbConnect;

class UserModel extends DbConnect
{
    protected int $user_id;

    protected array $dataset = [];

    // By default, we believe that query result is successful
    protected array $queryResult = ['status' => 'ok'];

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
        $this->handleMysqlError();

        // Success!
        $this->queryResult['message'] = 'User successfully created!';
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
        $this->handleMysqlError();


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
        $query = $this->db->delete('users');

        // Handle mysqli errors
        $this->handleMysqlError();

        // Success!
        $this->queryResult['message'] = 'User successfully deleted';
    }
}