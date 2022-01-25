<?php


namespace zikju\Endpoint\User;


use zikju\Shared\Http\Response;
use zikju\Shared\Validation\UserValidator;

class UserMiddleware extends UserModel
{
    // Properties for User Creation
    protected string $email;
    protected string $password;
    protected ?string $role;
    protected ?string $status;
    protected ?string $notes;


    /**
     * Validates request data from client
     *
     */
    protected function validateUserOptionalData (): void
    {
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
    }


    /**
     * Validates User email
     *
     */
    protected function validateUserEmail (): void
    {
        // Validate 'email' input
        if (!UserValidator::email($this->email)) {
            Response::send (
                'error',
                'INVALID EMAIL'
            );
        }
        // Add 'email' to dataset array
        $this->dataset['email'] = $this->email;
    }

    /**
     * Validates User password
     *
     */
    protected function validateUserPassword (): void
    {
        // Validate 'password' input
        if (!UserValidator::password($this->password)) {
            Response::send (
                'error',
                'INVALID PASSWORD'
            );
        }
        // Add 'password' to dataset array
        $this->dataset['password'] = $this->password;
    }


    /**
     * Validates User ID
     *
     */
    protected function validateUserID (): void
    {
        // Validate 'id'
        if (!UserValidator::id($this->user_id)) {
            Response::send (
                'error',
                'INVALID ID'
            );
        }
    }

}