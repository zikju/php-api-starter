<?php
declare(strict_types=1);


namespace zikju\Endpoint\Auth;


use zikju\Endpoint\Auth\Session\UserSession;
use zikju\Shared\Http\Response;

class LogoutController
{

    /**
     * Logout User.
     */
    public function logout ()
    {
        (new UserSession())->deleteUserSession();
        Response::sendOk('User successfully logged out');
    }

}