<?php


namespace zikju\Shared\Database;


use MysqliDb;
use zikju\Shared\Http\Response;

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

    protected function handleMysqlError ()
    {
        if ($this->db->getLastErrno() !== 0) {
            Response::send(
                'error',
                'Database error'
            );
        }
    }

    function __destruct()
    {
        $this->db->disconnect();
    }

}