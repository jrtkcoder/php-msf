<?php
/**
 * MySqlCoroutine
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace PG\MSF\Server\DataBase;

use PG\MSF\Server\Coroutine\CoroutineBase;
use PG\MSF\Server\CoreBase\SwooleException;

class MySqlCoroutine extends CoroutineBase
{
    /**
     * @var MysqlAsynPool
     */
    public $mysqlAsynPool;
    public $bindId;
    public $sql;

    public function __construct($_mysqlAsynPool, $_bind_id = null, $_sql = null)
    {
        parent::__construct();
        $this->mysqlAsynPool = $_mysqlAsynPool;
        $this->bindId = $_bind_id;
        $this->sql = $_sql;
        $this->request = '#Mysql:' . $_sql;
        $this->send(function ($result) {
            $this->result = $result;
        });
    }

    public function send($callback)
    {
        $this->mysqlAsynPool->query($callback, $this->bindId, $this->sql);
    }

    public function getResult()
    {
        $result = parent::getResult();
        if (is_array($result) && isset($result['error'])) {
            throw new SwooleException($result['error']);
        }
        return $result;
    }
}