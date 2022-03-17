<?php
declare(strict_types=1);


namespace zikju\App\Auth;


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