<?php
declare(strict_types=1);


namespace zikju\Endpoint\Auth;


use zikju\Shared\Http\Request\Request;
use zikju\Shared\Http\Response;
use zikju\Shared\Util\JwtToken;
use zikju\Shared\Util\Password;
use zikju\Shared\Validation\UserValidator;
use zikju\Shared\Session\UserSession;

class LoginController extends AuthModel
{
    protected $request_data;

    protected string $access_token;
    protected string $refresh_token;

    function __construct()
    {
        parent::__construct();

        // Define data from request
        $this->request_data = Request::getData();
    }

    public function login ()
    {
        // Validate login inputs
        $this->validateLoginInputs();

        // Get User data from database
        $this->getUserByEmailFromDB();

        // Verify password
        $this->verifyPassword(
            $this->password,
            $this->password_hash_from_db
        );

        // Create User session
        $this->createUserSession();

        // Create Access Token
        $this->createAccessToken();

        // Send response result
        $responseArray = [
            'access_token' => $this->access_token,
            'refresh_token' => $this->refresh_token
        ];

        Response::sendOk('', $responseArray);
    }


    /**
     * Validates login inputs (email and password).
     */
    private function validateLoginInputs ()
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


    /**
     * Creates User session.
     *
     * @throws \Exception
     */
    private function createUserSession ()
    {
        $userSession = new UserSession($this->userDataFromDB['id']);

        // Insert User session into database
        $this->insertUserSessionIntoDB(
            $this->userDataFromDB['id'],
            $userSession->getRefreshToken(),
            $userSession->getExpireDatetime(),
            $userSession->getIpAddress()
        );

        $this->refresh_token = $userSession->getRefreshToken();
    }


    /**
     * Creates Access (JWT) Token.
     */
    private function createAccessToken ()
    {
        // Create 'access_token' (JWT Token)
        $this->access_token = JwtToken::create($this->userDataFromDB);
    }
}