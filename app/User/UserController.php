<?php

namespace zikju\Endpoint\User;


use zikju\Shared\Http\Request\Request;
use zikju\Shared\Http\Response;
use zikju\Shared\Util\Password;
use zikju\Shared\Validation\UserValidator;

class UserController extends UserModel
{
    protected $request_data;

    // Properties for User Creation
    protected string $email;
    protected string $password;
    protected ?string $role;
    protected ?string $status;
    protected ?string $notes;

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
        if (!UserValidator::email($this->email)) {
            Response::sendError('INVALID EMAIL');
        }
        $this->dataset['email'] = $this->email;

        // Validate 'password'
        if (!UserValidator::password($this->password)) {
            Response::sendError('INVALID PASSWORD');
        }
        $this->dataset['password'] = Password::hash($this->password);

        // Validate 'role'
        if(UserValidator::role($this->role)) {
            $this->dataset['role'] = $this->role;
        }

        // Validate 'status'
        if(UserValidator::status($this->status)) {
            $this->dataset['status'] = $this->status;
        }

        // Validate 'notes'
        if(UserValidator::text($this->notes)) {
            $this->dataset['notes'] = $this->notes;
        }

        // Insert new user into database
        $this->insertUserIntoDB();

        // Send response result
        Response::send(
            $this->queryResult['status'],
            $this->queryResult['message']
        );
    }


    /**
     * Gets User data
     *
     * @param int $id
     * @throws \Exception
     */
    public function getUser (int $id): void
    {
        $this->user_id = $id;

        // Validate 'id'
        if (!UserValidator::id($this->user_id)) {
            Response::sendError('INVALID ID');
        }

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

        // Validate 'id'
        if (!UserValidator::id($this->user_id)) {
            Response::sendError('INVALID ID');
        }

        // Delete user from database
        $this->deleteUserFromDB();

        // Send response result
        Response::send(
            $this->queryResult['status'],
            $this->queryResult['message']
        );
    }


    /**
     * Updates user Core data (role, account status, notes...)
     *
     * @param int $id
     */
    public function editUserCoreData (int $id): void
    {
        // Set properties to update
        $this->user_id = $id;
        $this->role = $this->request_data['role'];
        $this->status = $this->request_data['status'];
        $this->notes = $this->request_data['notes'];

        // Validate 'id'
        if (!UserValidator::id($this->user_id)) {
            Response::sendError('INVALID ID');
        }

        // Validate 'role'
        if(UserValidator::role($this->role)) {
            // Add 'role' to dataset array
            $this->dataset['role'] = $this->role;
        }

        // Validate 'status'
        if(UserValidator::status($this->status)) {
            // Add 'status' to dataset array
            $this->dataset['status'] = $this->status;
        }

        // Validate 'notes'
        if(UserValidator::text($this->notes)) {
            // Add 'notes' to dataset array
            $this->dataset['notes'] = $this->notes;
        }

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
     * Updates User email
     *
     * @param int $id
     * @throws \Exception
     */
    public function editUserEmail (int $id)
    {
        $this->user_id = $id;
        $this->email = $this->request_data['email'];

        // Validate 'id'
        if (!UserValidator::id($this->user_id)) {
            Response::sendError('INVALID ID');
        }

        // Validate 'email'
        if (!UserValidator::email($this->email)) {
            Response::sendError('INVALID EMAIL');
        }
        $this->dataset['email'] = $this->email;

        // Update user email in database
        $this->updateUserEmailInDB();

        // Send response result
        Response::send(
            $this->queryResult['status'],
            $this->queryResult['message']
        );
    }
}
