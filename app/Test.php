<?php
declare(strict_types=1);


namespace zikju\App;

use zikju\Shared\Database\DbConnect;

class Test
{

    private ?\MysqliDb $db;

    function __construct()
    {
        $this->db = DbConnect::connect();
    }

    public function show ()
    {
        echo 'Hello world!';
    }

}