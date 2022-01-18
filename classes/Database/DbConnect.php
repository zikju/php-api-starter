<?php


namespace zikju\Database;


use MysqliDb;

class DbConnect
{
    protected ?MysqliDb $db;

    public function __construct()
    {
        $this->db = $this->connect();
    }

    protected function connect () {
        return new MysqliDb (
            $_ENV['DB_HOST'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_DATABASE'],
            $_ENV['DB_PORT']
        );
    }

}