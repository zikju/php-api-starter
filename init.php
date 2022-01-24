<?php
require_once 'classes/Init.php';

// Initialize app
$app = new \zikju\Shared\Init();

// Set default timezone
$app->setDefaultTimezone('Europe/Vilnius');

// Check if incoming request method is allowed
$app->validateIncomingMethod();

// Set CORS headers
$app->setCorsHeaders();

// Load environment variables from .env file to $_ENV
$app->loadEnvFile();

// Initialize Routes handler (API endpoints)
$app->loadRouter();
