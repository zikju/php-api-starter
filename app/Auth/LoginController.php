<?php
declare(strict_types=1);


namespace zikju\App\Auth;


use zikju\Shared\Http\Request;
use zikju\Shared\Http\Response;
use zikju\Shared\Util\JwtToken;
use zikju\Shared\Util\Password;
use zikju\Shared\Validation\UserValidator;

class LoginController extends LoginModel
{
    protected $request_data;

    protected string $email;
    protected string $password;

    function __construct()
    {
        parent::__construct();

        // Define data from request
        $this->request_data = Request::getData();
    }


    /**
     * Login User.
     *
     * @throws \Exception
     */
    public function login ()
    {
        // Validate login inputs
        $this->validateLoginEmail();
        $this->validateLoginPassword();

        // Get User data from database
        $userDataFromDB = $this->getUserDataFromDB($this->email);

        // Verify password
        $this->verifyPassword(
            $this->password,
            $userDataFromDB['password']
        );


        // Create Refresh Token (User session)
        $userSession = new UserSession();
        $userSession->createUserSession($userDataFromDB['id']);
        $refresh_token = $userSession->getRefreshToken();

        // Create Access Token (JWT)
        $token_payload = [
            'user_id' => $userDataFromDB['id'],
            'email' => $userDataFromDB['email'],
            'role' => $userDataFromDB['role']
        ];
        $access_token = JwtToken::create($token_payload);


        // Send response result
        $responseArray = [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token
        ];

        Response::sendOk(
            'User successfully logged in',
            $responseArray
        );
    }


    /**
     * Validates login Email.
     */
    private function validateLoginEmail ()
    {
        // Validate 'email'
        if (!UserValidator::email($this->request_data['email'])) {
            Response::sendError('INVALID EMAIL');
        }
        $this->email = $this->request_data['email'];
    }


    /**
     * Validates login Password.
     */
    private function validateLoginPassword ()
    {
        // Validate 'password'
        if (!UserValidator::password($this->request_data['password'])) {
            Response::sendError('INVALID PASSWORD');
        }
        $this->password = $this->request_data['password'];
    }


    /**
     * Verify raw password and password hash from database.
     *
     * @param string $password
     * @param string $password_hash
     */
    private function verifyPassword (string $password, string $password_hash)
    {
        // If passwords doesn't match - response with error
        if (!Password::verify($password, $password_hash))
        {
            Response::sendError('INVALID LOGIN');
        }
    }
}