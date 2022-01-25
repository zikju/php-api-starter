<?php

namespace zikju\Endpoint\User;


use zikju\Shared\Http\Request;
use zikju\Shared\Http\Response;
use zikju\Shared\Util\Password;

class UserController extends UserMiddleware
{
    protected $request_data;


    function __construct()
    {
        parent::__construct();

        // Define data from request
        $this->request_data = Request::getData();
    }


    /**
     * Creates new user
     *
     */
    public function createUser (): void
    {
        // Set properties for User Creation
        $this->email = $this->request_data['email'];
        $this->password = $this->request_data['password'];
        $this->role = $this->request_data['role'];
        $this->status = $this->request_data['status'];
        $this->notes = $this->request_data['notes'];

        // Validate 'email'
        $this->validateUserEmail();

        // Validate 'password'
        $this->validateUserPassword();

        // Hash password
        $this->dataset['password'] = Password::hash($this->password);

        // Validate other optional data
        $this->validateUserOptionalData();

        // Insert new user into database
        $this->insertUserIntoDB();

        // Send response result
        Response::send(
            $this->queryResult['status'],
            $this->queryResult['message']
        );
    }


    /**
     * Edits user data
     *
     * @param int $id
     */
    public function editUserData (int $id): void
    {
        // Set user
        $this->user_id = $id;
        $this->validateUserID();

        // Set properties to update
        $this->role = $this->request_data['role'];
        $this->status = $this->request_data['status'];
        $this->notes = $this->request_data['notes'];

        // Validate data
        $this->validateUserOptionalData();

        if(!empty($this->dataset)) {
            // Update user data in database
            $this->updateUserDataInDB();

            // Send response result
            Response::send(
                $this->queryResult['status'],
                $this->queryResult['message']
            );
        }
    }


    /**
     * Gets User data
     *
     * @param int $id
     * @throws \Exception
     */
    public function getUser (int $id): void
    {
        // Set user
        $this->user_id = $id;

        $this->validateUserID();

        // Get User data from database
        $this->getUserFromDB();

        // Send response result
        Response::send(
            $this->queryResult['status'],
            $this->queryResult['message'],
            $this->queryResult['payload']
        );
    }


    /**
     * Deletes user by id
     *
     * @param int $id
     * @throws \Exception
     */
    public function deleteUser (int $id): void
    {
        $this->user_id = $id;

        $this->validateUserID();

        // Insert new user into database
        $this->deleteUserFromDB();

        // Send response result
        Response::send(
            $this->queryResult['status'],
            $this->queryResult['message']
        );
    }

}
