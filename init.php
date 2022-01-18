<?php
use zikju\Core\Request;
use zikju\Core\Response;

// Set default timezone
date_default_timezone_set('Europe/Vilnius');

// Load helpers functions
require_once (__DIR__ . '/helpers/functions.php');

// Autoload classes from 'classes' dir
// TODO: Separate classes into folders by namespace
spl_autoload_register('autoload_class');


// Validate incoming Request
if (!Request::isAllowed()) {
    $response = new Response(
        'error',
        'Request method is invalid');
    $response->print();
    die();
}


// Set CORS Privacy header settings
/**
 * IMPORTANT:
 * If you are using: "Access-Control-Allow-Credentials" = true
 * make sure that "Access-Control-Allow-Origin" is not "*",
 * it must be set with a proper domain!
 * (a lot of blood was spilled here :/ )
 * */
// header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: " . implode(', ', Request::$allowedList));
header("Access-Control-Allow-Headers: Origin, Content-Type, Authorization, Accept, X-Requested-With, Content-Disposition, RefreshToken");
header("Access-Control-Max-Age: 60");
header("Content-Type: application/json; charset=UTF-8");




// Autoload installed Composer dependencies
require_once (__DIR__ . '/vendor/autoload.php');
