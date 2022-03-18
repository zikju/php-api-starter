<?php
use Steampixel\Route;
use zikju\Shared\Http\AccessGuard;
use zikju\Shared\Logger\UserLogger;
use zikju\Shared\Http\Response;

$accessGuard = new AccessGuard();

// Log user 'Last Access' datetime
UserLogger::logLastAccess();


/**
 * ---------------------------------------
 * [AUTH] ENDPOINT
 * ---------------------------------------
 */
// Login
Route::add('/auth/login', function() {
    (new zikju\App\Auth\LoginController())->login();
}, 'post');

// Logout
Route::add('/auth/logout', function() {
    (new zikju\App\Auth\LogoutController())->logout();
}, 'get');

// Refresh Token
Route::add('/auth/refresh-token', function() {
    (new zikju\App\Auth\RefreshTokenController())->refresh();
}, 'get');


/**
 * ---------------------------------------
 * [USERS] ENDPOINT
 * ---------------------------------------
 */
// Create user
Route::add('/users', function() use ($accessGuard) {
    $accessGuard->verifyAccessToken();
    $accessGuard->allowAdminOnly();
    (new zikju\App\User\UserController())->createUser();
}, 'post');

// Get user by id
Route::add('/users/([0-9]*)', function($id) use ($accessGuard) {
    $accessGuard->verifyAccessToken();
    (new zikju\App\User\UserController())->getUser($id);
}, 'get');

// Delete user
Route::add('/users/([0-9]*)', function($id) use ($accessGuard) {
    $accessGuard->verifyAccessToken();
    $accessGuard->allowAdminOnly();
    (new zikju\App\User\UserController())->deleteUser($id);
}, 'delete');

// Edit user Core data by id
Route::add('/users/([0-9]*)/edit', function($id) use ($accessGuard) {
    $accessGuard->verifyAccessToken();
    $accessGuard->allowAdminOnly();
    (new zikju\App\User\UserController())->editUserCoreData($id);
}, 'put');

// Edit user Email
Route::add('/users/([0-9]*)/edit/email', function($id) use ($accessGuard) {
    $accessGuard->verifyAccessToken();
    $accessGuard->allowAdminOnly();
    (new zikju\App\User\UserController())->editUserEmail($id);
}, 'put');



/**
 * ---------------------------------------
 * 404 NOT FOUND ROUTE
 * ---------------------------------------
 */
Route::pathNotFound(function($path) {
    // Do not forget to send a status header back to the client
    // The router will not send any headers by default
    // So you will have the full flexibility to handle this case
    header('HTTP/1.0 404 Not Found');
    Response::send(
        'error',
        'ENDPOINT NOT FOUND',
        null,
        404
    );
});




/**
 * ---------------------------------------
 * RUN ROUTER
 * ---------------------------------------
 */
// Route::run('/'); // api scripts are in root folder
Route::run('/php-backend'); // api scripts are in sub-folder
