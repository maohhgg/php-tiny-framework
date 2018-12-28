<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-7-2
 * Time: 下午2:03
 */

namespace Framework\Orm;

use Framework\Exceptions\CoreHttpException;

trait SQLInterpreter
{
    /**
     * 查询条件
     * @var string
     */
    private $where = '';

    /**
     * 查询参数
     * @var array
     */
    public $params = [];

    /**
     * 排序条件
     * @var string
     */
    private $orderBy = '';

    /**
     * 查询限制
     * @var string
     */
    private $limit = '';

    /**
     * 查询偏移量
     * @var string
     */
    private $offset = '';

    /**
     * 表名称
     * @var string
     */
    public $tableName = '';

    /**
     * 拼接的SQL语句
     * @var string
     */
    public $sql = '';


    /**
     * 插入一条数据
     * @param array $data
     * @throws CoreHttpException
     */
    public function insert(array $data)
    {
        if (empty($data)) {
            throw new CoreHttpException(400, 'argumnet data is null');
        }
        $fieldString = '';
        $valueString = '';
        $i = 0;
        foreach ($data as $k => $v) {
            if ($i === 0) {
                $fieldString .= "`{$k}`";
                $valueString .= ":{$k}";
                $this->params[$k] = $v;
                ++$i;
                continue;
            }
            $fieldString .= " , `{$k}`";
            $valueString .= " , :{$k}";
            $this->params[$k] = $v;
            ++$i;
        }
        unset($k);
        unset($v);

        $this->sql = "INSERT INTO `{$this->tableName}` ({$fieldString}) VALUES ({$valueString})";
    }

    /**
     *  删除数据
     */
    public function del()
    {
        $this->sql = "DELETE FROM `{$this->tableName}`";
    }

    /**
     * 更新一条数据
     *
     * @param  array $data 数据
     * @throws CoreHttpException
     */
    public function updateData(array $data)
    {
        if (empty($data)) {
            throw new CoreHttpException(400, 'argumnet data is null');
        }
        $set = '';
        $dataCopy = $data;
        $pop = array_pop($dataCopy);
        foreach ($data as $k => $v) {
            if ($v === $pop) {
                $set .= "`{$k}` = :$k";
                $this->params[$k] = $v;
                continue;
            }
            $set .= "`{$k}` = :$k,";
            $this->params[$k] = $v;
        }
        unset($k);
        unset($v);
        $this->sql = "UPDATE `{$this->tableName}` SET {$set}";
    }


    /**
     * 查找一条数据
     * @param  array $data 查询的字段
     * @throws CoreHttpException
     */
    public function select(array $data)
    {
        $field = '';
        $count = count($data);
        switch ($count) {
            case 0:
                $field = '*';
                break;
            case 1:
                if (isset($data[0])) {
                    throw new CoreHttpException(400, 'argumnet data is null');
                }
                $field = "`{$data[0]}`";
                break;
            default:
                $last = array_pop($data);
                foreach ($data as $v) {
                    $field .= "{$v},";
                }
                $field .= $last;
                break;
        }
        $this->sql = "SELECT {$field} FROM `{$this->tableName}`";
    }

    /**
     * where 条件
     * @param array $data
     * @return $this
     */
    public function where(array $data)
    {
        if (empty($data)) {
            return $this;
        }

        $count = count($data);

        /* 单条件 */
        if ($count === 1) {
            $field = array_keys($data)[0];
            $value = array_values($data)[0];
            if (!is_array($value)) {
                $this->where = " WHERE `{$field}` = :{$field}";
                $this->params = $data;
                return $this;
            }
            $this->where = " WHERE `{$field}` {$value[0]} :{$field}";
            $this->params[$field] = $value[1];
            return $this;
        }

        /* 多条件 */
        $tmp = $data;
        $last = array_pop($tmp);
        foreach ($data as $k => $v) {
            if ($v === $last) {
                if (!is_array($v)) {
                    $this->where .= "`{$k}` = :{$k}";
                    $this->params[$k] = $v;
                    continue;
                }
                $this->where .= "`{$k}` {$v[0]} :{$k}";
                $this->params[$k] = $v[1];
                continue;
            }
            if (!is_array($v)) {
                $this->where .= " WHERE `{$k}` = :{$k} AND ";
                $this->params[$k] = $v;
                continue;
            }
            $this->where .= " WHERE `{$k}` {$v[0]} :{$k} AND ";
            $this->params[$k] = $v[1];
            continue;
        }
        return $this;
    }

    /**
     * orderBy
     * @param string $sort
     * @return $this
     * @throws CoreHttpException
     */
    public function orderBy(string $sort)
    {
        if (!is_string($sort)) {
            throw new CoreHttpException(400, 'argu is not string');
        }
        $this->orderBy = " order by {$sort}";
        return $this;
    }

    /**
     * limit
     * @param int $start
     * @param int $len
     * @return $this
     */
    public function limit(int $start, int $len)
    {
        if ($len === 0) {
            $this->limit = " limit {$start}";
            return $this;
        }
        $this->limit = " limit {$start},{$len}";
        return $this;
    }

    /**
     * query
     * @param  string $sql
     * @return mixed
     * @throws CoreHttpException
     */
    public function querySql(string $sql)
    {
        if (empty($sql)) {
            throw new CoreHttpException("sql is empty", 400);
        }
        $this->sql = $sql;
    }
}