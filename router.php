<?php
// Use 'steampixel/simple-php-router' library
use Steampixel\Route;


// TEST ROUTE
Route::add('/test', function() {
    require __DIR__ . '/src/test.php';
}, 'get');


// 404 NOT FOUND ROUTE
Route::pathNotFound(function($path) {
    // Do not forget to send a status header back to the client
    // The router will not send any headers by default
    // So you will have the full flexibility to handle this case
    header('HTTP/1.0 404 Not Found');
    echo 'The requested path "'.$path.'" was not found!';
});


// Run the router
// Route::run('/'); // api scripts are in root folder
Route::run('/php-backend'); // api scripts are in sub-folder
