<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-7-2
 * Time: 下午1:48
 */

namespace Framework\Db;


use Framework\App;
use Framework\Db\Builder\Builder;
use Framework\Db\Builder\Mysql;

class DB
{
    use SQLInterpreter;

    /**
     * 数据库策略映射
     * @var array
     */
    protected $dbStrategyMap = [
        'mysql' => 'Framework\Db\Builder\Mysql'
    ];

    /**
     * 数据库实例
     * @var Builder
     */
    protected $dbInstance;

    /**
     * 自增id
     * 插入数据成功后的自增id, 0为插入失败
     * @var string
     */
    public $id = '';

    /**
     * 数据库配置
     * @var array
     */
    private $dbConfig = [
        'dbtype' => '',
        'dbhost' => '',
        'dbname' => '',
        'username' => '',
        'password' => ''
    ];

    /**
     * @param string $tableName
     * @return DB
     * @throws \Framework\Exceptions\CoreHttpException
     */
    static public function table(string $tableName)
    {
        $db = new self;
        $db->tableName = $tableName;
        $prefix = config('database.dbprefix');
        if (!empty($prefix)) {
            $db->tableName = $prefix . '_' . $db->tableName;
        }
        return $db;
    }

    /**
     * DB constructor.
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function init()
    {
        foreach ($this->dbConfig as $k => $v){
            $this->dbConfig[$k] = config('database')[$k];
        }
        unset($k);
        unset($v);
        $this->decide();
    }

    /**
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function decide()
    {
        $dbConfig = $this->dbConfig;
        $dbStrategyName = $this->dbStrategyMap[$dbConfig['dbtype']];
        $this->dbInstance = APP::$container->getSingle(
            "{$dbConfig['dbtype']}",
            function () use ($dbStrategyName, $dbConfig) {
                return new $dbStrategyName(
                    $dbConfig['dbhost'],
                    $dbConfig['dbname'],
                    $dbConfig['username'],
                    $dbConfig['password']
                );
            }
        );
    }

    /**
     * 查找单条数据
     * @param array $data
     * @return mixed
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function first(array $data = [])
    {
        $this->select($data);
        $this->buildSql();
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    /**
     * 查找所有数据
     * @param array $data
     * @return mixed
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function get(array $data = [])
    {
        $this->select($data);
        $this->buildSql();
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    /**
     * 插入数据
     * @param array $data
     * @return mixed
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function save($data = [])
    {
        $this->insert($data);
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    /**
     * 更新数据
     * @param array $data
     * @return mixed
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function update($data = [])
    {
        $this->updateData($data);
        $this->buildSql();
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    /**
     * 删除数据
     * @return mixed
     */
    public function delete()
    {
        $this->del();
        $this->buildSql();
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    /**
     * 使用Sql语句查询
     * @param string $sql
     * @return mixed
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function query($sql = '')
    {
        $this->querySql($sql);
        return $this->dbInstance->query($this);
    }

    /**
     * 补全SQL语句
     */
    public function buildSql()
    {
        if (!empty($this->where)) {
            $this->sql .= $this->where;
        }
        if (!empty($this->orderBy)) {
            $this->sql .= $this->orderBy;
        }
        if (!empty($this->limit)) {
            $this->sql .= $this->limit;
        }
    }

    /**
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public static function beginTransaction()
    {
        $instance = APP::$container->getSingle('DB', function () {
            return new DB();
        });
        $instance->init('master');
        $instance->dbInstance->beginTransaction();
    }

    /**
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public static function commit()
    {
        $instance = APP::$container->getSingle('DB', function () {
            return new DB();
        });
        $instance->init('master');
        $instance->dbInstance->commit();
    }


    /**
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public static function rollBack()
    {
        $instance = APP::$container->getSingle('DB', function () {
            return new DB();
        });
        $instance->init('master');
        $instance->dbInstance->rollBack();
    }

}