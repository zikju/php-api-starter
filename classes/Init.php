<?php
declare(strict_types=1);


namespace zikju\Shared;


use Dotenv\Dotenv;
use zikju\Shared\Http\Request\Request;

final class Init
{
    public function __construct()
    {
        $this->loadPsrAutoloader();
    }


    /**
     * Load routes and endpoints.
     *
     * @return mixed
     */
    public function loadRouter ()
    {
        return require __DIR__ .'/../router.php';
    }


    /**
     * Load environment variables from .env file to $_ENV
     */
    public function loadEnvFile (): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->safeLoad();
    }


    /**
     * Validates incoming request method
     */
    public function validateIncomingMethod (): void
    {
        Request::validateIncomingMethod();
    }

    /**
     * Sets default header CORS
     */
    public function setCorsHeaders (): void
    {
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
        header("Access-Control-Allow-Methods: " . implode(', ', Request::getAllowedMethods()));
        header("Access-Control-Allow-Headers: " . implode(', ', Request::getAllowedHeaders()));
        header("Access-Control-Max-Age: 60");
        header("Content-Type: application/json; charset=UTF-8");
    }

    /**
     * Sets default timezone.
     *
     * @param string $timezone
     */
    public function setDefaultTimezone (string $timezone): void
    {
        // Set default timezone
        date_default_timezone_set($timezone);
    }

    /**
     * Autoload installed packages via Composer.
     *
     * @return mixed
     */
    private function loadPsrAutoloader ()
    {
        return require_once (__DIR__ . '/../vendor/autoload.php');
    }
}