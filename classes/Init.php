<?php
declare(strict_types=1);


namespace zikju\Shared;


use Dotenv\Dotenv;
use zikju\Shared\Http\Request;

final class Init
{
    public function __construct()
    {
        $this->loadPsrAutoloader();
    }

    public function loadRouter ()
    {
        return require __DIR__ .'/../router.php';
    }

    public function loadEnvFile (): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->safeLoad();
    }

    public function validateIncomingMethod (): void
    {
        Request::validateIncomingMethod();
    }

    public function setCorsHeaders (): void
    {
        Request::setHeaders();
    }

    public function setDefaultTimezone (string $timezone): void
    {
        // Set default timezone
        date_default_timezone_set($timezone);
    }

    private function loadPsrAutoloader ()
    {
        return require_once (__DIR__ . '/../vendor/autoload.php');
    }
}