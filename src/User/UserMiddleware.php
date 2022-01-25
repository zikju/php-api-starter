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
    protected function validateUserData (): void
    {
        // Validate 'email'
        if (!UserValidator::email($this->email)) {
            Response::send(
                'error',
                'INVALID EMAIL'
            );
        }
        // Add 'email' to dataset array
        $this->dataset['email'] = $this->email;

        // Validate 'password'
        $this->validateUserPassword();

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
    protected function validateUserID ()
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