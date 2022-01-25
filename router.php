<?php
use Steampixel\Route; // Use 'steampixel/simple-php-router' library



/** **************************************** **
 * [USERS] ENDPOINT
 */

// Create user
Route::add('/users', function() {
    (new zikju\Endpoint\User\UserController())->createUser();
}, 'post');

// Edit user Core data by id
Route::add('/users/([0-9]*)/edit', function($id) {
    // TODO: check role access
    (new zikju\Endpoint\User\UserController())->editUserCoreData($id);
}, 'put');

// Get user by id
Route::add('/users/([0-9]*)', function($id) {
    (new zikju\Endpoint\User\UserController())->getUser($id);
}, 'get');

// Delete user
Route::add('/users/([0-9]*)', function($id) {
    // TODO: check role access
    (new zikju\Endpoint\User\UserController())->deleteUser($id);
}, 'delete');

/**
 ** ************************************** **/




/** 404 NOT FOUND ROUTE */
Route::pathNotFound(function($path) {
    // Do not forget to send a status header back to the client
    // The router will not send any headers by default
    // So you will have the full flexibility to handle this case
    header('HTTP/1.0 404 Not Found');
    echo 'The requested path "'.$path.'" was not found!';
});




/** RUN THE ROUTER */
// Route::run('/'); // api scripts are in root folder
Route::run('/php-backend'); // api scripts are in sub-folder
