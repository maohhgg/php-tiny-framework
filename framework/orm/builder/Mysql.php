<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-7-2
 * Time: 下午1:37
 */

namespace Framework\Orm\Builder;

use Framework\Orm\DB;
use PDO;
use PDOStatement;

/**
 * Class Mysql
 * @package Framework\Orm\Builder
 */
class Mysql
{
    /**
     * db host
     * @var string
     */
    private $dbhost = '';

    /**
     * db name
     * @var string
     */
    private $dbname = '';

    /**
     * db connect info
     * @var string
     */
    private $dns = '';

    /**
     * db username
     * @var string
     */
    private $username = '';

    /**
     * db password
     * @var string
     */
    private $password = '';

    /**
     * pdo instance
     * @var PDO
     */
    private $pdo;

    /**
     * 预处理实例
     * 代表一条预处理语句，并在该语句被执行后代表一个相关的结果集。
     * @var PDOStatement
     */
    private $pdoStatement = '';


    /**
     * Mysql constructor.
     * @param string $dbhost
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
    public function __construct(string $dbhost, string $dbname, string $username, string $password)
    {
        $this->dbhost = $dbhost;
        $this->dbname = $dbname;
        $this->dns = "mysql:dbname={$this->dbname};host={$this->dbhost};";
        $this->username = $username;
        $this->password = $password;

        $this->connect();
    }

    /**
     * 使用PDO建立mysql链接
     */
    private function connect()
    {
        $this->pdo = new PDO($this->dns, $this->username, $this->password);
    }

    /**
     * 查询一条数据
     * @param DB $db
     * @return mixed
     */
    public function first(DB $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        $this->pdoStatement->execute();
        return $this->pdoStatement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 查询所有数据
     * @param DB $db
     * @return array
     */
    public function get(DB $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        $this->pdoStatement->execute();
        return $this->pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 插入
     * @param DB $db
     * @return bool|string
     */
    public function save(DB $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        $res = $this->pdoStatement->execute();
        if (!$res) {
            return false;
        }
        return $db->id = $this->pdo->lastInsertId();
    }

    /**
     * 删除
     * @param DB $db
     * @return int
     */
    public function delete(DB $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        $this->pdoStatement->execute();
        return $this->pdoStatement->rowCount();
    }

    /**
     * 更新
     * @param DB $db
     * @return bool
     */
    public function update(DB $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        return $this->pdoStatement->execute();
    }

    /**
     *  查询
     * @param DB $db
     * @return array
     */
    public function query(DB $db)
    {
        $res = [];
        foreach ($this->pdo->query($db->sql, PDO::FETCH_ASSOC) as $v) {
            $res[] = $v;
        }
        return $res;
    }

    /**
     * 绑定数据
     * @param DB $db
     */
    public function bindValue(DB $db)
    {
        if (empty($db->params)) {
            return;
        }
        foreach ($db->params as $k => $v) {
            $this->pdoStatement->bindValue(":{$k}", $v);
        }
    }

    /**
     * stop auto commit transaction and start a transaction
     * @return void
     */
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * commit a transaction
     * @return void
     */
    public function commit()
    {
        $this->pdo->commit();
    }

    /**
     * rollback a transaction
     * @return void
     */
    public function rollBack()
    {
        $this->pdo->rollBack();
    }
}