<?php
declare(strict_types=1);


namespace zikju\Endpoint\Auth;

use zikju\Shared\Http\Request;
use zikju\Shared\Http\Response;
use zikju\Shared\Util\Password;
use zikju\Shared\Validation\UserValidator;

class AuthController extends AuthModel
{
    protected $request_data;

    function __construct()
    {
        parent::__construct();

        // Define data from request
        $this->request_data = Request::getData();
    }

    public function login ()
    {
        // Validate 'email'
        if (!UserValidator::email($this->request_data['email'])) {
            Response::sendError('INVALID EMAIL');
        }
        $this->email = $this->request_data['email'];

        // Validate 'password'
        if (!UserValidator::password($this->request_data['password'])) {
            Response::sendError('INVALID PASSWORD');
        }
        $this->password = $this->request_data['password'];

        // Get User data from database
        $this->getUserByEmailFromDB();

        // Handle database error
        if ($this->query_result_status === 'error') {
            Response::sendError($this->query_result_message);
        }

        // Verify raw password and password hash from database
        $passwordVerified = Password::verify(
            $this->password,
            $this->userData['password']
        );

        // Invalid password. Response with error
        if (!$passwordVerified) {
            Response::sendError('INVALID LOGIN');
        }

        // Remove row with 'password' from userData array
        unset($this->userData['password']);

        // Send response result
        Response::sendOk(
            $this->query_result_message,
            $this->userData
        );

    }
}