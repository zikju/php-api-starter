<?php


namespace zikju\Shared\Database;


use MysqliDb;

class DbConnect extends DbConfig
{
    protected ?MysqliDb $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->connect();
    }

    protected function connect ()
    {
        return new MysqliDb (
            $this->getHost(),
            $this->getUsername(),
            $this->getPassword(),
            $this->getDatabase(),
            $this->getPort()
        );
    }

    public function getConnection ()
    {
        return MysqliDb::getInstance();
    }

    function __destruct()
    {
        $this->db->disconnect();
    }

}