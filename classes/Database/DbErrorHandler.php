<?php
declare(strict_types=1);


namespace zikju\Shared\Database;


use MysqliDb;
use zikju\Shared\Http\Response;

class DbErrorHandler
{
    static function handleMysqlError ()
    {
        $db = MysqliDb::getInstance();

        if ($db->getLastErrno() !== 0) {
            Response::send(
                'error',
                'Database error'
            );
        }
    }
}